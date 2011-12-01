<?php
/**
 * @version		$Id: weblink.php 20899 2011-03-07 20:56:09Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since		1.5
 */
class WeblinksplusControllerWeblinkplus extends JControllerForm
{
	/**
	 * @since	1.6
	 */
	protected $view_item = 'form';

	/**
	 * @since	1.6
	 */
	protected $view_list = 'categories';

	/**
	 * Method to add a new record.
	 *
	 * @return	boolean	True if the article can be added, false if not.
	 * @since	1.6
	 */
	public function add()
	{
		if (!parent::add()) {
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param	array	$data	An array of input data.
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('id'), 'int');
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
	 * Method to cancel an edit.
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 *
	 * @return	Boolean	True if access level checks pass, false otherwise.
	 * @since	1.6
	 */
	public function cancel($key = 'w_id')
	{
		parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 * @param	string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return	Boolean	True if access level check and checkout passes, false otherwise.
	 * @since	1.6
	 */
	public function edit($key = null, $urlVar = 'w_id')
	{
		$result = parent::edit($key, $urlVar);

		return $result;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 * @param	array	$config	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 * @since	1.5
	 */
	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param	int		$recordId	The primary key id for the item.
	 * @param	string	$urlVar		The name of the URL variable for the id.
	 *
	 * @return	string	The arguments to append to the redirect URL.
	 * @since	1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId	= JRequest::getInt('Itemid');
		$return	= $this->getReturnPage();

		if ($itemId) {
			$append .= '&Itemid='.$itemId;
		}

		if ($return) {
			$append .= '&return='.base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return	string	The return URL.
	 * @since	1.6
	 */
	protected function getReturnPage()
	{
		$return = JRequest::getVar('return', null, 'default', 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JURI::base();
		}
		else {
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param	JModel	$model		The data model object.
	 * @param	array	$validData	The validated data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function postSaveHook(JModel &$model, $validData = array())
	{
		$task = $this->getTask();

		if ($task == 'save') {
			$this->setRedirect(JRoute::_('index.php?option=com_weblinksplus&view=category&id='.$validData['catid'], false));
		}
	}

	/**
	 * Method to save a record.
	 *
	 * @param	string	$key	The name of the primary key of the URL variable.
	 * @param	string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return	Boolean	True if successful, false otherwise.
	 * @since	1.6
	 */
	/*public function save($key = null, $urlVar = 'w_id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result) {
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}*/
        
        public function save() {
		// Check for request forgeries
		$db =& JFactory::getDBO();
                
                //Add the path to the tables
                JTable::addIncludePath( 'administrator'.DS.'components'.DS.'com_weblinksplus'.DS.'tables' );

		// save the panel parent information
		$row =& JTable::getInstance('Weblinkplus', 'WeblinksplusTable');
		$post	= JRequest::get( 'post' );
                
                $fieldName = 'thumbnail';

                if (isset($_FILES[$fieldName])) {                        
                        $params = new JRegistry();
                        $params->loadJSON(JComponentHelper::getParams('com_weblinksplus'));
                        $mediaObey = $params->get('mediasource', 'media');
                        
                        $basePath = JPATH_SITE.DS.$mediaObey;
                    
                        $post['jform']['thumbnail'] = $mediaObey.'/'.$_FILES[$fieldName]['name'];

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
                                echo $uploadPath;
                                if(!JFile::upload($fileTemp, $uploadPath)) {
                                  $err = 'ERROR MOVING FILE';
                                }
                        }

                        if ($err) { // Error found
                                $lang =& JFactory::getLanguage();
                                $lang->load('com_media');
                          echo '<strong style="color:#ff0000">ERROR:&nbsp;'.JText::_( $err ).'</strong>';
                        }
                }
                else{
                    $post['jform']['thumbnail'] = '';
                }
                
		if (!$row->bind( $post['jform'] )) { JError::raiseError(500, $row->getError() ); }

		if (!$row->check()) { JError::raiseError(500, $row->getError() ); }

		if (!$row->store()) { JError::raiseError(500, $row->getError() ); }
		$row->checkin();

		// Make thumbnail
		$this->makeThumbnail($row);
                
		$this->setRedirect($this->getReturnPage());
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

	/**
	 * Go to a weblink
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function go()
	{
		// Get the ID from the request
		$id = JRequest::getInt('id');

		// Get the model, requiring published items
		$modelLink	= $this->getModel('Weblinkplus', '', array('ignore_request' => true));
		$modelLink->setState('filter.published', 1);

		// Get the item
		$link	= $modelLink->getItem($id);

		// Make sure the item was found.
		if (empty($link)) {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKSPLUS_ERROR_WEBLINK_NOT_FOUND'));
		}

		// Check whether item access level allows access.
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();

		if (!in_array($link->access, $groups)) {
			return JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		}

		// Check whether category access level allows access.
		$modelCat = $this->getModel('Category', 'WeblinksplusModel', array('ignore_request' => true));
		$modelCat->setState('filter.published', 1);

		// Get the category
		$category = $modelCat->getCategory($link->catid);

		// Make sure the category was found.
		if (empty($category)) {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKSPLUS_ERROR_WEBLINK_NOT_FOUND'));
		}

		// Check whether item access level allows access.
		if (!in_array($category->access, $groups)) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Redirect to the URL
		// TODO: Probably should check for a valid http link
		if ($link->url) {
			$modelLink->hit($id);
			JFactory::getApplication()->redirect($link->url);
		}
		else {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKSPLUS_ERROR_WEBLINK_URL_INVALID'));
		}
	}
}
