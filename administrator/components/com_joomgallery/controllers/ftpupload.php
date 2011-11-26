<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/controllers/ftpupload.php $
// $Id: ftpupload.php 3378 2011-10-07 18:37:56Z aha $
/****************************************************************************************\
**   JoomGallery 2                                                                      **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * JoomGallery FTP Upload Controller
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryControllerFtpupload extends JoomGalleryController
{
  /**
   * Constructor
   *
   * @return  void
   * @since   1.5.5
   */
  public function __construct()
  {
    parent::__construct();

    // Set view
    JRequest::setVar('view', 'ftpupload');
  }

  /**
   * Displays the FTP upload form
   *
   * @return  void
   * @since   2.0
   */
  public function display()
  {
    // Access check
    if(!count(JoomHelper::getAuthorisedCategories('joom.upload')))
    {
      $this->setRedirect(JRoute::_($this->_ambit->getRedirectUrl('categories'), false), JText::_('No categories found into which you are allowed to upload'), 'notice');

      return;
    }

    parent::display();
  }

  /**
   * Uploads the selected images
   *
   * @return  void
   * @since   1.5.5
   */
  public function upload()
  {
    require_once JPATH_COMPONENT.DS.'helpers'.DS.'upload.php';
    $uploader = new JoomUpload();
    if($uploader->upload(JRequest::getCmd('type', 'ftp')))
    {
      $msg  = JText::_('COM_JOOMGALLERY_UPLOAD_MSG_SUCCESSFULL');
      $url  = $this->_ambit->getRedirectUrl();

      // Set custom redirect if we are asked for that
      if($redirect = JRequest::getVar('redirect', '', '', 'base64'))
      {
        $url_decoded  = base64_decode($redirect);
        if(JURI::isInternal($url))
        {
          $url = $url_decoded;
        }
      }

      $this->setRedirect(JRoute::_($url, false), $msg);
    }
    else
    {
      if($error = $uploader->getError())
      {
        $this->setRedirect($this->_ambit->getRedirectUrl(), $error, 'error');
      }
    }
  }
}