<?php
/**
 * @version		$Id: weblink.php 21766 2011-07-08 12:20:23Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Weblink controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @since		1.6
 */
class WeblinksplusControllerWeblinkplus extends JControllerForm
{
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param	array	$data	An array of input data.
	 * @return	boolean
	 * @since	1.6
	 */
    
         protected $view_list = 'weblinksplus';
         
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		$allow		= null;

		if ($categoryId) {
			// If the category has been passed in the URL check it.
			$allow	= $user->authorise('core.create', $this->option.'.category.'.$categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId) {
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId) {
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option.'.category.'.$categoryId);
		} else {
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}

	/**
	 * Method to run batch operations.
	 *
	 * @return	void
	 * @since	1.7
	 */
	public function batch($model)
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('Weblinkplus', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_weblinksplus&view=weblinksplus'.$this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
        
        public function save() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
                
                $task = JRequest::getCmd( 'task' );

		$db =& JFactory::getDBO();
                
                //Add the path to the tables
                JTable::addIncludePath( JPATH_COMPONENT.DS.'tables' );

		// save the panel parent information
		$row =& JTable::getInstance('Weblinkplus', 'WeblinksplusTable');
		$post	= JRequest::get( 'post' );
                
                $id = $post['jform']['id'];
                
                if($task == 'save2copy'){
                    $post['jform']['title'] = 'Copy of '.$post['jform']['title'];
                    $post['jform']['id'] = 0;
                    $post['jform']['alias'] = '';
                    $task = 'apply';
                }
                
                $post['jform']['thumbnail'] = $post['thumbnail'];
                $post['jform']['article_id'] = $post['article_id'];
                
		if (!$row->bind( $post['jform'] )) { JError::raiseError(500, $row->getError() ); }

		if (!$row->check()) { JError::raiseError(500, $row->getError() ); }

		if (!$row->store()) { JError::raiseError(500, $row->getError() ); }
		$row->checkin();

		// Make thumbnail
		$this->makeThumbnail($row);
		
		switch ($task) {
                        case 'save2new':
                                $msg = JText::_( 'Changes to Weblinks saved' );
				$link = 'index.php?option=com_weblinksplus&view=weblinkplus&layout=edit';
                            break;
			case 'apply':
				$msg = JText::_( 'Changes to Weblinks saved' );
				$link = 'index.php?option=com_weblinksplus&view=weblinkplus&layout=edit&id='. $id;
				break;
			case 'save':
			default:
				$msg = JText::_( 'Weblinks saved' );
				$link = 'index.php?option=com_weblinksplus&view=weblinksplus';
				break;
		}

		$this->setRedirect($link, $msg);
	}
        
        function makeThumbnail($row) {
            $params = new JRegistry();
            $params->loadJSON(JComponentHelper::getParams('com_weblinksplus'));

		$live_site =& JURI::base();
		$imagefile = JPATH_SITE.DS.$row->thumbnail;
		$thumbsource = JPATH_SITE.DS.'media'.DS.'weblinksplus'.DS.'thumbs';
		$info=pathinfo($imagefile);
		$imagename = $info['filename'];
		$imageextension = $info['extension'];
		$thumbfile = $thumbsource.DS.$row->id.'.png';
		$thumbx = $params->get('thumbx', 160);
		$thumby = $params->get('thumby', 160);
		
		// Build thumbnail
		switch($imageextension) {
			case "gif":
				if( function_exists("imagecreatefromgif") ) {
					$orig_img = imagecreatefromgif($imagefile);
					break;
				}
				else {
					echo 'Unsupported function: <b>imagecreatefromgif()</b>';
					exit;
					break;
				}
			case "jpg":
				if( function_exists("imagecreatefromjpeg") ) {
					$orig_img = imagecreatefromjpeg($imagefile);
					break;
				}
				else {
					echo 'Unsupported function: <b>imagecreatefromjpeg()</b>';
					exit;
					break;
				}
			case "png":
				if( function_exists("imagecreatefrompng") ) {
					$orig_img = imagecreatefrompng($imagefile);
					break;
				}
				else {
					echo 'Unsupported function: <b>imagecreatefrompng()</b>';
					exit;
					break;
				}
		}
		
		// Compute new size
		$orig_size = getimagesize($imagefile);
		$maxX = $thumbx;
		$maxY = $thumby;
		$newxsize = $maxX;
		$newysize = $maxY;
		if (($orig_size[0]*$maxY)<($orig_size[1]*$maxX)) {
			$newxsize = $newysize * ($orig_size[0]/$orig_size[1]);
			$adjustX = ($maxX - $newxsize)/2;
			$adjustY = 0;
		}
		else {
			$newysize = $newxsize / ($orig_size[0]/$orig_size[1]);
			$adjustX = 0;
			$adjustY = ($maxY - $newysize)/2;
		}
				
		if( function_exists("imagecreatetruecolor") ) {
		  $new_img = imagecreatetruecolor($maxX,$maxY);
		}
		else {
		  $new_img = imagecreate($maxX,$maxY);
		}
		
		$bgfill = imagecolorallocate( $new_img, 255, 255, 255 );
		
		if( function_exists( "imageAntiAlias" )) {
			imageAntiAlias($new_img,true);
		}
		imagealphablending($new_img, false);
		if( function_exists( "imagesavealpha")) {
			imagesavealpha($new_img,true);
		}
		if( function_exists( "imagecolorallocatealpha")) {
			$transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
		}
					
		imagefill( $new_img, 0,0, $bgfill );
		if( function_exists("imagecopyresampled") ){
			ImageCopyResampled($new_img, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
		}
		else {
			ImageCopyResized($new_img, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
		}
		
		// Save image
		imagepng($new_img,$thumbfile);
        }
}