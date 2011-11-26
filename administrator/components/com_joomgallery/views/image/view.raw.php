<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/image/view.raw.php $
// $Id: view.raw.php 3380 2011-10-07 19:06:48Z erftralle $
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
 * Raw View class for the image view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewImage extends JoomGalleryView
{
  /**
   * Raw view display method, outputs one image
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  function display()
  {
    jimport('joomla.filesystem.file');

    $type     = JRequest::getWord('type', 'thumb');
    //$download = JRequest::getCmd('download');
    //$model    = &$this->getModel();

    $image    = &$this->get('Data');

    /*$crop_image = false;
    $cropwidth  = JRequest::getInt('width');
    $cropheight = JRequest::getInt('height');
    if($cropwidth && $cropheight)
    {
      $crop_image = true;
    }*/

    $img      = $this->_ambit->getImg($type.'_path', $image);

    //$include_watermark = false;

    // Check access rights
    // If the thumbnail is required, we won't have to do more checks than the
    // general access level check in the model.
    // Additionally the hit counter gets only increased if we are not
    // displaying a thumbnail.
    /*if($type != 'thumb')
    {
      // Downloading
      if($download)
      {
        // Is the download allowed for the user group of the current user?
        if( (     ($this->_config->get('jg_showdetaildownload') == 0)
              ||  ($this->_config->get('jg_showdetaildownload') == 1  && $this->_user->get('aid') < 1)
              ||  ($this->_config->get('jg_showdetaildownload') == 2  && $this->_user->get('aid') < 2)
              ||  ($this->_config->get('jg_showdetailpage') == 0      && $this->_user->get('aid') < 1)
            )
          &&
            (     ($this->_config->get('jg_showcategorydownload') == 0)
              ||  ($this->_config->get('jg_showcategorydownload') == 1 && $this->_user->get('aid') < 1)
              ||  ($this->_config->get('jg_showcategorydownload') == 2 && $this->_user->get('aid') < 2)
            )
          &&
            (     ($this->_config->get('jg_showtoplistdownload') == 0)
              ||  ($this->_config->get('jg_showtoplistdownload') == 1 && $this->_user->get('aid') < 1)
              ||  ($this->_config->get('jg_showtoplistdownload') == 2 && $this->_user->get('aid') < 2)
            )
          &&
            (     ($this->_config->get('jg_showfavouritesdownload') == 0)
              ||  ($this->_config->get('jg_showfavouritesdownload') == 1 && $this->_user->get('aid') < 1)
              ||  ($this->_config->get('jg_showfavouritesdownload') == 2 && $this->_user->get('aid') < 2)
            )
          &&
            (     ($this->_config->get('jg_showsearchdownload') == 0)
              ||  ($this->_config->get('jg_showsearchdownload') == 1 && $this->_user->get('aid') < 1)
              ||  ($this->_config->get('jg_showsearchdownload') == 2 && $this->_user->get('aid') < 2)
            )
          )
        {
          $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_NO_ACCESS'), 'error');
        }

        // Is the download of the requested image type allowed?
        if(!$this->_config->get('jg_downloadfile') && $type == 'orig')
        {
          $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_NO_ACCESS'), 'notice');
        }
        if($this->_config->get('jg_downloadfile') == 1 && !JFile::exists($img))
        {
          $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_ORIGINAL_NOT_AVAILABLE'), 'notice');
        }
        if($this->_config->get('jg_downloadfile') == 2 && $type == 'orig')
        {
          if(!JFile::exists($img))
          {
            // Offer detail image for download if original images isn't available
            $type = 'img';
            $img  = $this->_ambit->getImg($type.'_path', $image);
          }
        }

        // Include watermark when downloading image?
        if($this->_config->get('jg_downloadwithwatermark'))
        {
          $include_watermark = true;
        }

        // Trigger event 'onJoomBeforeDownload'
        $plugins = $this->_mainframe->triggerEvent('onJoomBeforeDownload', array(&$image, &$img, &$type, &$include_watermark));
        if(in_array(false, $plugins, true))
        {
          $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false));
        }

        // Message about new download
        if(!$this->_user->get('username'))
        {
          $username = JText::_('COM_JOOMGALLERY_COMMON_GUEST');
        }
        else
        {
          $username = $this->_config->get('jg_realname') ? $this->_user->get('name') : $this->_user->get('username');
        }

        require_once JPATH_COMPONENT.DS.'helpers'.DS.'messenger.php';
        $messenger    = new JoomMessenger();
        $message      = array(
                              'subject'   => JText::_('COM_JOOMGALLERY_MESSAGE_NEW_DOWNLOAD_SUBJECT'),
                              'body'      => JText::sprintf('COM_JOOMGALLERY_MESSAGE_NEW_DOWNLOAD_BODY',
                                             $image->imgtitle, $image->imgfilename, $username),
                              'mode'      => 'download'
                              );
        $messenger->send($message);

      }
      // Displaying, not downloading
      else
      {
        if(!$this->_config->get('jg_showdetailpage') && $this->_user->get('aid') < 1)
        {
          $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_NO_ACCESS'), 'notice');
        }

        // Include watermark when displaying image in the detail view?
        if($this->_config->get('jg_watermark'))
        {
          $include_watermark = true;
        }

        // Link to original image in detail view or category view
        if(   ($type == 'orig')
            &&
              (
                  (         !$this->_config->get('jg_detailpic_open')
                    &&
                      (     !$this->_config->get('jg_bigpic')
                        ||  ($this->_config->get('jg_bigpic') == 1 && !$this->_user->get('aid'))
                      )
                  )
                ||
                  (     $this->_config->get('jg_detailpic_open')
                    &&  !$this->_config->get('jg_lightboxbigpic')
                  )
              )
          )
        {
          $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_NO_ACCESS'), 'notice');
        }
      }

      // Increase hit counter
      $model->hit();
    }*/

    if(!JFile::exists($img))
    {
      $this->_mainframe->redirect(JRoute::_('index.php', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_IMAGE_NOT_EXIST'), 'error');
    }

    $info = getimagesize($img);
    switch($info[2])
    {
      case 1:
        $mime = 'image/gif';
       break;
      case 2:
        $mime = 'image/jpeg';
        break;
      case 3:
        $mime = 'image/png';
        break;
      default:
        JError::raiseError(404, JText::sprintf('COM_JOOMGALLERY_COMMON_MSG_MIME_NOT_ALLOWED', $info[2]));
        break;
    }

    // Set mime encoding
    $this->_doc->setMimeEncoding($mime);

    // Set header to specify the file name
    $disposition = 'inline';
    /*if($download)
    {
      // Allow downloading
      $disposition = 'attachment';
    }*/
    JResponse::setHeader('Content-disposition', $disposition.'; filename='.basename($img));

    // Inlude watermark
    /*if(($include_watermark || $crop_image) && !$model->isGif($img))
    {
      $img_resource = null;
      if($crop_image)
      {
        $croppos  = JRequest::getInt('pos');
        $offsetx  = JRequest::getInt('x');
        $offsety  = JRequest::getInt('y');
        $img_resource = $model->cropImage($img, $cropwidth, $cropheight, $croppos, $offsetx, $offsety);
      }

      if($include_watermark)
      {
        $img_resource = $model->includeWatermark($img, $img_resource, $cropwidth, $cropheight);
      }

      if(!$img_resource)
      {
        echo JFile::read($img);
      }
      else
      {
        switch($mime)
        {
          case 'image/gif':
            imagegif($img_resource);
            break;
          case 'image/png':
            imagepng($img_resource);
            break;
          case 'image/jpeg':
            $quali = JRequest::getInt('quali', 95);
            imagejpeg($img_resource, '', $quali);
            break;
          default:
            JError::raiseError(404, JText::sprintf('COM_JOOMGALLERY_COMMON_MSG_MIME_NOT_ALLOWED', $mime));
            break;
        }

        imagedestroy($img_resource);
      }
    }
    else
    {*/
      echo JFile::read($img);
    //}
  }
}