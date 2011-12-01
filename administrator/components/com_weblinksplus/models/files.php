<?php
/*
	JoomlaXTC File Browser
	
	Version 2.6.1
	
	Copyright (C) 2010-2011  Monev Software LLC.	All Rights Reserved.
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.
	
	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

defined('_JEXEC') or die;

// Import Joomla! libraries
jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

// Disable warnings because of open_basedir Warnings
ini_set( 'display_errors', 0 );

require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php' );

class xtcModelFiles extends JModel {
    function getState($property = null) {
        static $set;

        $option = JRequest::getCmd( 'option' );
        $application = JFactory::getApplication();

// Get component parm
				$config =& JComponentHelper::getParams( $option );
				$mediasource = $config->get( 'mediasource', 'media' );
				$mediaObey = $config->get( 'mediaObey', 0);
//
//				// Check if source dir is changed
//        $olddefault = $application->getUserState( $option.'.olddefault',$mediasource );
//        echo "OLD [$olddefault] vs [$mediasource]";
//				if ($olddefault != $mediasource) {
//         	$set = 0;
//        }
//        $application->setUserState( $option.'.olddefault',$mediasource);

        if (!$set) {
            $this->setState('option', $option);
						// Get fieldname
            $fieldname = $application->getUserStateFromRequest( $option.'.fieldname', 'fieldname', '' );
            $this->setState('fieldname', $fieldname);

            // Get extensions
            $ext = $application->getUserStateFromRequest( $option.'.ext', 'ext', '' );
            $this->setState('ext', $ext);
            $this->setState('mediaObey', $mediaObey);

            $default_folder = $mediasource;
            $this->setState('default_folder', $default_folder);

						$fileview = $application->getUserStateFromRequest( $option.'.fileview', 'fileview', 'g' );
            $this->setState('fileview', $fileview);

						$extview = $application->getUserStateFromRequest( $option.'.extview', 'extview', '1' );
            $this->setState('extview', $extview);

            // current folder
            $folder = $application->getUserStateFromRequest( $option.'.files.folder', 'folder', $default_folder );
            $hold=split(DS,$folder);
            if ($hold[0] && $hold[0] != $default_folder) { // Root folder changed
	            $this->setState('folder', $default_folder);
	            $folder = $default_folder;
            }
            $folder = str_replace( DS.DS, DS, $folder );
            if( $folder == '.' ) $folder = '';
            if( $folder == DS ) $folder = '';
            if( $folder == 'undefined' ) $folder = '';
            if( $folder != '/' ) $folder = ereg_replace( '^/', '', $folder );
            $folder = str_replace( DS, '/', $folder );
            $this->setState('folder', $folder);

            // current item 
            $current = $application->getUserStateFromRequest( $option.'.files.current', 'current', '' );
            $this->setState('current', $current);

            // current base-path
            $basePath = JPATH_SITE.DS.$folder;
            $this->setState('basepath', $basePath);

            // current parent
            $parent = dirname($folder);
            if( $parent == '.' ) $parent = '/';
            if( $folder == '/' ) $parent = null;
            $this->setState('parent', $parent);

            $set = true;
        }
        return parent::getState($property);
    }

    function getFiles() {
        $list = $this->getList();
        return $list['files'];
    }

    function getFolders() {
        $list = $this->getList();
        return $list['folders'];
    }

    function getDocuments() {
        $list = $this->getList();
        return $list['docs'];
    }

    function getList() {
        static $list;

        // Only process the list once per request
        if (is_array($list)) {
            return $list;
        }

        // Initialize variables
        $basePath = $this->getState('basepath');
        $baseFolder = $this->getState('folder');
        $default_folder = $this->getState('default_folder');
        $ext = $this->getState('ext');
        $extview = $this->getState('extview');
        $mediaObey = $this->getState('mediaObey');

				$baseFolder = trim($baseFolder);
			  $null = JFolder::makeSafe($baseFolder);	// Make sure is legal
				if (empty($baseFolder) || $baseFolder == '/') {
					$baseFolder = $this->getState('default_folder');
				  $this->setState('folder',$baseFolder);
				}

//echo "[$basePath][$baseFolder][$default_folder]";

        $files = array ();
        $folders = array ();
        $docs = array ();


				// Get upload files

				include 'getuploads.php';

        // Get the list of files and folders from the given folder
		    JPath::check( $basePath );
        if( is_readable( $basePath )) {
            $fileList = JFolder::files($basePath);
            $folderList = JFolder::folders($basePath);
        }
        else {
            $fileList = false;
            $folderList = false;
        }

        // Iterate over the files if they exist
        if ($fileList !== false) {
            $media_params = JComponentHelper::getParams( 'com_media' );	// Get legal extensions
            $allowed = explode( ',', $media_params->get( 'upload_extensions' ));
            foreach ($fileList as $file) {
                if (is_file($basePath.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
                    // First read the Media Manager parameters and check if this extension is Joomla! legal
                    $extension = strtolower(JFile::getExt($file));
                    if( $mediaObey && in_array( $extension, $allowed ) == false ) { // Not found in allowed extenions
                        continue;
                    }

                    // If component extensions enforced, Check if this extension is allowed
                    if (!empty($ext) && $extview) { // use extension filter flag
	                    if (in_array($extension,explode(',',$ext)) == false ) { // Not found in requested extensions
	                        continue;
	                    }
	                  }
                    $tmp = new JObject();
                    $tmp->name = $file;
                    $tmp->path = JPath::clean($basePath.DS.$file);
                    $tmp->path_relative = $baseFolder.DS.basename($tmp->path);
                    $tmp->size = filesize($tmp->path);
										$tmp->date = date("Y-m-d H:i:s", getlastmod($tmp->path));
                    $extension = strtolower(JFile::getExt($file));
	                  $iconfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'images'.DS.'mime-icon-16'.DS.$extension.'.png';
	                  if (file_exists($iconfile)) {
	                      $tmp->path_relative16 = 'administrator'.DS.'components'.DS.'com_media'.DS.'images'.DS.'mime-icon-16'.DS.$extension.'.png';
	                  } else {
	                      $tmp->path_relative16 = 'administrator'.DS.'components'.DS.'com_media'.DS.'images'.DS.'con_info.png';
	                  }
	                  $tmp->path_relative16 = str_replace( DS, '/', $tmp->path_relative16 );

                    switch ($extension) {
                      // Image
                      case 'jpg':
                      case 'png':
                      case 'gif':
                      case 'xcf':
                      case 'odg':
                      case 'bmp':
                      case 'jpeg':
                        $tmp->isimage = true;
                        $info = @getimagesize($tmp->path);
                        $tmp->realwidth = @$info[0];
                        $tmp->realheight = @$info[1];
                        $tmp->width = @$info[0];
                        $tmp->height = @$info[1];
                        $tmp->type = @$info[2];
                        $tmp->mime = @$info['mime'];

                        $msize = 60;
                        if (($info[0] > $msize) || ($info[1] > $msize)) {
                            $dimensions = MediaHelper::imageResize($info[0], $info[1], $msize);
                            $tmp->width = $dimensions[0];
                            $tmp->height = $dimensions[1];
                        }

                        $tmp->path = str_replace( DS, '/', $tmp->path );
                        $tmp->path_relative = str_replace( DS, '/', $tmp->path_relative );
                        $files[] = $tmp;
                      break;
                      // Non-image document
                      default:
                        $tmp->isimage = false;
                        $iconfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'images'.DS.'mime-icon-32'.DS.$extension.'.png';
                        if (file_exists($iconfile)) {
                            $tmp->path_relative = 'administrator'.DS.'components'.DS.'com_media'.DS.'images'.DS.'mime-icon-32'.DS.$extension.'.png';
                        } else {
                            $tmp->path_relative = 'administrator'.DS.'components'.DS.'com_media'.DS.'images'.DS.'con_info.png';
                        }

                        $info = @getimagesize(JPATH_SITE.DS.$tmp->path_relative);
                        $tmp->width = @$info[0];
                        $tmp->height = @$info[1];

                        $tmp->path = str_replace( DS, '/', $tmp->path );
                        $tmp->path_relative = str_replace( DS, '/', $tmp->path_relative );
                        $files[] = $tmp;
                       break;
                    }
                }
            }
        }

        // Iterate over the folders if they exist
        if ($folderList !== false) {
            foreach ($folderList as $folder) {
    
                $tmp = new JObject();
                $tmp->name = basename($folder);
                $tmp->path = JPath::clean($basePath.DS.$folder);
                $tmp->path_relative = $tmp->name;
                $count = MediaHelper::countFiles($tmp->path);
                $tmp->files = $count[0];
                $tmp->folders = $count[1];

                $tmp->path = str_replace( DS, '/', $tmp->path );
                $tmp->path_relative = str_replace( DS, '/', $tmp->path_relative );

                $folders[] = $tmp;
            }
        }

        $list = array('folders' => $folders, 'docs' => $docs, 'files' => $files);

        return $list;
    }
}
?>