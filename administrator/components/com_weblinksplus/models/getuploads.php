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

$fieldName = 'upfile';

if (isset($_FILES[$fieldName])) {
	
	$err = '';
	
	//validate for errors the server registered on uploading
	$fileError = $_FILES[$fieldName]['error'];
	switch ($fileError) {
	  case 1:
	  $err = 'FILE TO LARGE THAN PHP INI ALLOWS';
		break;
	  case 2:
	  $err = 'FILE TO LARGE THAN HTML FORM ALLOWS';
		break;
		case 3:
	  $err = 'ERROR PARTIAL UPLOAD';
	  break;
	  case 4:
	  $err = 'ERROR NO FILE';
	  break;
	}
	
	if (!$err && $mediaObey) { // Validate with Joomla! media settings
		require_once JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_media'.DS.'helpers'.DS.'media.php';
		$canUpload = MediaHelper::canUpload($_FILES[$fieldName],$err);
	}
	
	if (!$err) { // validation passed, move the file
		$fileTemp = $_FILES[$fieldName]['tmp_name'];
		$newFileName = JFile::makesafe($_FILES[$fieldName]['name']); 
		$uploadPath = $basePath.DS.$newFileName;
		if(!JFile::upload($fileTemp, $uploadPath)) {
		  $err = 'ERROR MOVING FILE';
		}
	}
	
	if ($err) { // Error found
		$lang =& JFactory::getLanguage();
		$lang->load('com_media');
	  echo '<strong style="color:#ff0000">ERROR:&nbsp;'.JText::_( $err ).'</strong>';
	}

	return;
}
?>