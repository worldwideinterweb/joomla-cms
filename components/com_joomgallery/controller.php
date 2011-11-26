<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/controller.php $
// $Id: controller.php 3495 2011-11-01 21:56:13Z chraneco $
/****************************************************************************************\
**   JoomGallery 2                                                                   **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

jimport('joomla.application.component.controller');

/**
 * JoomGallery Component Controller
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryController extends JController
{
  /**
   * JApplication object
   *
   * @access  protected
   * @var     object
   */
  var $_mainframe;

  /**
   * JoomConfig object
   *
   * @access  protected
   * @var     object
   */
  var $_config;

  /**
   * JoomAmbit object
   *
   * @access  protected
   * @var     object
   */
  var $_ambit;

  /**
   * JUser object, holds the current user data
   *
   * @access  protected
   * @var     object
   */
  var $_user;

  /**
   * JDatabase object
   *
   * @access  protected
   * @var     object
   */
  var $_db;

  /**
   * Constructor
   *
   * @access  protected
   * @return  void
   * @since   1.5.5
   */
  function __construct($config = array())
  {
    parent::__construct($config);

    $this->_ambit     = & JoomAmbit::getInstance();
    $this->_config    = & JoomConfig::getInstance();

    /*$this->_mainframe = & JFactory::getApplication('site');
    $this->_user      = & JFactory::getUser();*/
  }

  /**
   * Parent display method for all views
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function display()
  {
    // Set a default view if none exists
    if(!$view = JRequest::getCmd('view'))
    {
      JRequest::setVar('view', 'gallery');
    }

    // Increase hit counter in detail view if image view isn't used
    if($view == 'detail' && $this->_config->get('jg_use_real_paths'))
    {
      $id = JRequest::getInt('id');

      $img_model = $this->getModel('image');
      $img_model->setId($id);
      $img_model->hit();
    }

    parent::display();

    // Possibly delete zips
    if(   $view == 'favourites'
      &&
          $this->_config->get('jg_favourites')
      &&
        (   $this->_config->get('jg_zipdownload')
        ||  $this->_config->get('jg_usefavouritesforpubliczip')
        )
      )
    {
      $this->_db  = & JFactory::getDBO();
      $this->_db->setQuery("SELECT
                              uid,
                              uuserid,
                              zipname
                            FROM
                              "._JOOM_TABLE_USERS."
                            WHERE
                               zipname != ''
                              AND time != ''
                              AND time < NOW()-INTERVAL 60 SECOND
                          ");
      $ziprows = $this->_db->loadObjectList();
      if(count($ziprows))
      {
        jimport('joomla.filesystem.file');
        foreach($ziprows as $row)
        {
          if(JFile::exists($row->zipname))
          {
            JFile::delete($row->zipname);
          }
          if($row->uuserid != 0)
          {
            $this->_db->setQuery("UPDATE
                                    "._JOOM_TABLE_USERS."
                                  SET
                                    time = '',
                                    zipname = ''
                                  WHERE
                                    uid = '".$row->uid."'
                                ");
          }
          else
          {
            $this->_db->setQuery("DELETE
                                  FROM
                                    "._JOOM_TABLE_USERS."
                                  WHERE
                                        uuserid = '0'
                                    AND zipname = '".$row->zipname."'
                                ");
          }
          $this->_db->query();
        }
      }
    }
  }

  /**
   * Saves a name tag
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function savenametag()
  {
    $model = $this->getModel('nametags');

    if(!$model->save())
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), $model->getError(), 'error');
    }
    else
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_SAVED'));
    }
  }

  /**
   * Deletes a specific name tag
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function removenametag()
  {
    $model = $this->getModel('nametags');

    if(!$model->remove())
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), $model->getError(), 'error');
    }
    else
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_DELETED'));
    }
  }

  /**
   * Shows the popup window for selecting a user for the name tag
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function selectnametag()
  {
    JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
    JHTML::_('joompopup.nametags');
  }

  /**
   * Shows the popup window for reporting an image
   *
   * @access  public
   * @return  void
   * @since   1.5.6
   */
  function report()
  {
    JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
    JHTML::_('joompopup.report');
  }

  /**
   * Shows the popup window for reporting an image
   *
   * @access  public
   * @return  void
   * @since   1.5.6
   */
  function sendReport()
  {
    $this->_user      = & JFactory::getUser();
    $this->_mainframe = & JFactory::getApplication('site');
    $id               = JRequest::getInt('id');
    $catid            = JRequest::getInt('catid');
    $toplist          = JRequest::getVar('toplist');
    $sstring          = JRequest::getVar('sstring');

    if(!$id)
    {
      //$this->setRedirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_NO_IMAGE_SPECIFIED'), 'notice');
      JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
      JHTML::_('joompopup.start');
      $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_COMMON_NO_IMAGE_SPECIFIED'), 'notice');

      return;
    }

    // Determine correct redirect URL
    $redirect_url = 'index.php?view=detail&id='.$id;
    if($catid)
    {
      // Request was initiated by toplist view
      $redirect_url = 'index.php?view=category&catid='.$catid;
    }
    elseif($toplist !== null)
    {
      // Request was initiated from toplist view
      if(empty($toplist))
      {
        // Toplist view 'Most viewed'
        $redirect_url = JRoute::_('index.php?view=toplist', false);
      }
      else
      {
        // Any other toplist view
        $redirect_url = JRoute::_('index.php?view=toplist&type='.$toplist, false);
      }
    }
    elseif($sstring !== null)
    {
     // Request was initiated from search view
      $redirect_url = JRoute::_('index.php?view=search&sstring='.$sstring, false);
    }

    // Do some security checks
    if(   !$this->_config->get('jg_report_images')
      ||  (!$this->_config->get('jg_report_unreg') && !$this->_user->get('id'))
      )
    {
      $msg = JText::_('ALERTNOTAUTH');
      if(!$this->_user->get('id'))
      {
        $msg .= JText::_('You need to login.');
      }

      //$this->setRedirect(JRoute::_($redirect_url, false), $msg, 'notice');
      JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
      JHTML::_('joompopup.start');
      $this->_mainframe->enqueueMessage($msg, 'notice');

      return;
    }

    if(  (    $this->_config->get('jg_detail_report_images') == 2
           || $this->_config->get('jg_category_report_images') == 2
           || $this->_config->get('jg_toplist_report_images') == 2
           || $this->_config->get('jg_search_report_images') == 2
         )
        && !$this->_user->get('id')
      )
    {
      $fromname = $this->_mainframe->getUserStateFromRequest('report.image.name', 'name', '', 'post');
      $from     = $this->_mainframe->getUserStateFromRequest('report.image.email', 'email', '', 'post');
    }
    else
    {
      $fromname = $this->_user->get('name');
      $from     = $this->_user->get('id');
    }

    $report = $this->_mainframe->getUserStateFromRequest('report.image.report', 'report', '', 'post');

    if(!$report || !$fromname || !$from)
    {
      //$this->setRedirect(JRoute::_($redirect_url, false), JText::_('COM_JOOMGALLERY_COMMON_MSG_FORM_NOT_FILLED'), 'notice');
      JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
      JHTML::_('joompopup.start');
      $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_COMMON_MSG_FORM_NOT_FILLED'), 'notice');

      return;
    }

    // Captcha
    $valid  = true;
    $msg    = '';
    $plugins  = $this->_mainframe->triggerEvent('onJoomCheckCaptcha');
    foreach($plugins as $key => $result)
    {
      if(is_array($result) && isset($result['valid']) && !$result['valid'])
      {
        $valid = false;
        if(isset($result['error']) && $result['error'])
        {
          $msg = $result['error'];
        }
        else
        {
          $msg = JText::_('COM_JOOMGALLERY_COMMON_MSG_SECURITY_CODE_WRONG');
        }
        break;
      }
    }
    if(!$valid)
    {
      //$this->setRedirect(JRoute::_($redirect_url, false), $msg, 'notice');
      JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
      JHTML::_('joompopup.start');
      $this->_mainframe->enqueueMessage($msg, 'notice');

      return;
    }

    // Prepare links
    $image    = $this->_ambit->getImgObject($id);
    $link     = JRoute::_($redirect_url);
    $img_src  = JRoute::_($this->_ambit->getImg('img_url', $image));

    $current_uri  = & JURI::getInstance(JURI::base());
    $current_host = $current_uri->toString(array('scheme', 'host', 'port'));

    // Ensure that the correct host and path is prepended
    $uri      = & JFactory::getUri($link);
    $uri->setHost($current_host);
    $link     = $uri->toString();
    $uri      = & JFactory::getUri($img_src);
    $uri->setHost($current_host);
    $img_src  = $uri->toString();

    $text = JText::sprintf( 'COM_JOOMGALLERY_REPORT_IMAGE_BODY',
                            $image->id,
                            $image->imgtitle,
                            $fromname,
                            $from,
                            $link,
                            $img_src,
                            $report
                          );

    $subject = JText::sprintf('COM_JOOMGALLERY_REPORT_IMAGE_SUBJECT', $this->_mainframe->getCfg('sitename'));

    // Create the message
    require_once JPATH_COMPONENT.DS.'helpers'.DS.'messenger.php';
    $messenger = new JoomMessenger();

    $message = array( 'from'      => $from,
                      'fromname'  => $fromname,
                      'subject'   => $subject,
                      'body'      => $text,
                      'mode'      => 'report'
                    );

    // Message to image owner
    if($this->_config->get('jg_msg_report_toowner'))
    {
      $messenger->addRecipients($image->owner);
    }

    // Send the message
    if(!$messenger->send($message))
    {
      //$this->setRedirect(JRoute::_($redirect_url, false), JText::_('COM_JOOMGALLERY_COMMON_REPORT_NOT_SENT'), 'error');
      JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
      JHTML::_('joompopup.start');
      $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_COMMON_REPORT_NOT_SENT'), 'error');

      return;
    }

    $this->_mainframe->triggerEvent('onJoomAfterReport', array($message));

    //$this->setRedirect(JRoute::_($redirect_url, false), JText::_('COM_JOOMGALLERY_COMMON_REPORT_SENT'));
    JHTML::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
    JHTML::_('joompopup.start');
    $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_COMMON_REPORT_SENT'));
  }

  /**
   * Votes an image
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function vote()
  {
    $model = $this->getModel('vote');

    if(!$model->vote())
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), $model->getError(), 'error');
    }
    else
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), JText::_('COM_JOOMGALLERY_DETAIL_RATINGS_MSG_YOUR_VOTE_COUNTED'));
    }
  }

  /**
   * Saves a comment
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function comment()
  {
    $model = $this->getModel('comments');

    if(!$return = $model->save())
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), $model->getError(), 'error');
    }
    else
    {
      if($return == 1)
      {
        $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), JText::_('COM_JOOMGALLERY_DETAIL_MSG_COMMENT_SAVED'));
      }
      else
      {
        $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), JText::_('COM_JOOMGALLERY_DETAIL_MSG_COMMENT_SAVED_BUT_NEEDS_ARROVAL'));
      }
    }
  }

  /**
   * Deletes a specific comment
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function removecomment()
  {
    $model = $this->getModel('comments');

    if(!$model->remove())
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), $model->getError(), 'error');
    }
    else
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$model->getId(), false), JText::_('COM_JOOMGALLERY_DETAIL_MSG_COMMENT_DELETED'));
    }
  }

  /**
   * Sends a mail with a link to the current image to a given address
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function send2friend()
  {
    $this->_user      = & JFactory::getUser();
    $id               = JRequest::getInt('id');

    if(!$this->_user->get('id'))
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$id, false), JText::_('You need to login.'), 'notice');
      return;
    }

    require_once JPATH_COMPONENT.DS.'helpers'.DS.'messenger.php';

    $this->_mainframe = & JFactory::getApplication('site');

    $send2friendname  = JRequest::getVar('send2friendname', '', 'post');
    $send2friendemail = JRequest::getVar('send2friendemail', '', 'post');

    // Prepare link
    $link = JRoute::_('index.php?view=detail&id='.$id);

    $current_uri  = & JURI::getInstance(JURI::base());
    $current_host = $current_uri->toString(array('scheme', 'host', 'port'));

    // Ensure that the correct host and path is prepended
    $uri  = & JFactory::getUri($link);
    $uri->setHost($current_host);
    $link = $uri->toString();

    $text = JText::sprintf( 'COM_JOOMGALLERY_MESSAGE_IMAGE_FROM_FRIEND_BODY',
                            $this->_user->get('name'),
                            $this->_user->get('email'),
                            $link
                          );

    $subject = $this->_mainframe->getCfg('sitename') . ' - ' . JText::_('COM_JOOMGALLERY_MESSAGE_IMAGE_FROM_FRIEND_SUBJECT');

    $message = array( 'from'      => $this->_user->get('email'),
                      'fromname'  => $this->_user->get('name'),
                      'recipient' => $send2friendemail,
                      'subject'   => $subject,
                      'body'      => $text,
                      'mode'      => 'send2friend'
                    );

    $messenger = new JoomMessenger();

    if(!$messenger->send($message))
    {
      $this->setRedirect(JRoute::_('index.php?view=detail&id='.$id, false), JText::_('COM_JOOMGALLERY_DETAIL_SENDTOFRIEND_MSG_MAIL_NOT_SENT'), 'error');
      return;
    }

    $this->_mainframe->triggerEvent('onJoomAfterSend2Friend', array($message));

    $this->setRedirect(JRoute::_('index.php?view=detail&id='.$id, false), JText::_('COM_JOOMGALLERY_DETAIL_SENDTOFRIEND_MSG_MAIL_SENT'));
  }

  /**
   * Adds an image to the list of the favourites
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function addimage()
  {
    $model = $this->getModel('favourites');

    // Determine correct redirect URL
    if($catid = JRequest::getInt('catid'))
    {
      // Request was initiated from category view
      $url = JRoute::_('index.php?view=category&catid='.$catid, false);
    }
    elseif(($toplist = JRequest::getVar('toplist')) !== null)
    {
      // Request was initiated from toplist view
      if(empty($toplist))
      {
        // Toplist view 'Most viewed'
        $url = JRoute::_('index.php?view=toplist', false);
      }
      else
      {
        // Any other toplist view
        $url = JRoute::_('index.php?view=toplist&type='.$toplist, false);
      }
    }
    elseif(($sstring = JRequest::getVar('sstring')) !== null)
    {
     // Request was initiated from search view
      $url = JRoute::_('index.php?view=search&sstring='.$sstring, false);
    }
    else
    {
      // Request was initiated from detail view or any othe view
      $url = JRoute::_('index.php?view=detail&id='.$model->getId(), false);
    }

    // Add the image to the list of favourite images
    if(!$model->addImage())
    {
      $this->setRedirect($url, $model->getError(), 'error');
    }
    else
    {
      // Message is set by the model
      $this->setRedirect($url);
    }
  }

  /**
   * Removes an image from the list of the favourites
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function removeimage()
  {
    $model = $this->getModel('favourites');

    if(!$model->removeImage())
    {
      $this->setRedirect(JRoute::_('index.php?view=favourites', false), $model->getError(), 'error');
    }
    else
    {
      // Message is set by the model
      $this->setRedirect(JRoute::_('index.php?view=favourites', false));
    }
  }

  /**
   * Clears the list of the favourites
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function removeall()
  {
    $model = $this->getModel('favourites');

    if(!$model->removeAll())
    {
      $this->setRedirect(JRoute::_('index.php?view=favourites', false), $model->getError(), 'error');
    }
    else
    {
      // Message is set by the model
      $this->setRedirect(JRoute::_('index.php?view=favourites', false));
    }
  }

  /**
   * Switches the layout of the favourites view
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function switchlayout()
  {
    $model = $this->getModel('favourites');

    $model->switchLayout();

    $this->setRedirect(JRoute::_('index.php?view=favourites', false));
  }

  /**
   * Creates a zip archive of the favourites
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function createzip()
  {
    $model = $this->getModel('favourites');

    if(!$model->createZip())
    {
      $this->setRedirect(JRoute::_('index.php?view=favourites', false), JText::sprintf('COM_JOOMGALLERY_ERROR_CREATING_ZIP', $model->getError()), 'error');
    }
    else
    {
      $this->setRedirect(JRoute::_('index.php?view=downloadzip', false));
    }
  }

  /**
   * Uploads the selected images
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function upload()
  {
    $this->_mainframe = & JFactory::getApplication('site');
    $type = $this->_mainframe->getUserStateFromRequest('joom.upload.type', 'type', 'single', 'post', 'cmd');

    // If the applet in JAVA upload checks for the serverProtocol,
    // it issues a HEAD request
    // Simply return an empty doc to send a HTTP 200
    if($type == 'java' && $_SERVER['REQUEST_METHOD'] == 'HEAD')
    {
      jexit();
    }


    require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'upload.php';
    $uploader = new JoomUpload();
    if($uploader->upload($type))
    {
      $msg  = JText::_('COM_JOOMGALLERY_UPLOAD_MSG_SUCCESSFULL');

      // Set redirect if we are asked for that
      if($redirect = JRequest::getVar('redirect', '', '', 'base64'))
      {
        $url  = base64_decode($redirect);
        if(JURI::isInternal($url))
        {
          $this->setRedirect(JRoute::_($url, false), $msg);

          return;
        }
      }

      // Set a redirect according to the correspondent setting in configuration manager
      switch($this->_config->get('jg_redirect_after_upload'))
      {
        case 1:
          $this->setRedirect(JRoute::_('index.php?view=upload&tab='.$type, false), $msg);
          break;
        case 2:
          $this->setRedirect(JRoute::_('index.php?view=userpanel', false), $msg);
          break;
        case 3:
          $this->setRedirect(JRoute::_('index.php?view=gallery', false), $msg);
          break;
        default:
          // Don't set a redirect in order
          // to display the debug output
          break;
      }
    }
    else
    {
      if($error = $uploader->getError())
      {
        $this->setRedirect(JRoute::_('index.php?view=upload&tab='.$type, false), $error, 'error');
      }
    }
  }

   /**
   * Sends message about uploads in JAVA uploads if activated in backend
   *
   * @access  public
   * @return  void
   * @since   1.5.7
   */
  function sendmessage()
  {
    require_once(JPATH_COMPONENT.DS.'helpers'.DS.'messenger.php');
    $this->_mainframe = & JFactory::getApplication('site');
    $this->_user      = & JFactory::getUser();

    $counter = $this->_mainframe->getUserState('joom.upload.java.counter', 0);
    $messenger  = new JoomMessenger();
    $message    = array(
                          'from'      => $this->_user->get('id'),
                          'subject'   => JText::_('COM_JOOMGALLERY_MESSAGE_NEW_IMAGES_SUBMITTED_SUBJECT'),
                          'body'      => JText::sprintf('COM_JOOMGALLERY_MESSAGE_NEW_IMAGES_SUBMITTED_BODY', $this->_user->get('username'), $counter),
                          'mode'      => 'upload'
                        );
    $messenger->send($message);
    $this->_mainframe->setUserState('joom.upload.java.counter', 0);
    $msg  = JText::_('COM_JOOMGALLERY_UPLOAD_OUTPUT_UPLOAD_COMPLETE');
    $this->setRedirect(JRoute::_('index.php?view=upload&tab=java', false), $msg);
  }

   /**
   * Checks errors in JAVA uploads, at the moment only a call of sendmessage
   * to send a message for images formerly uploaded succesfully
   *
   * @access  public
   * @return  void
   * @since   1.5.7
   */
  function checkerror()
  {
    $this->sendmessage();
  }

  /**
   * Saves an image after editing
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function save()
  {
    $model = $this->getModel('edit');

    /*$data = JRequest::get('post');

    //editing more than one image?
    if(isset($data['cids']))
    {
      //we need selected fields
      if(!isset($data['change']))
      {
        $this->setRedirect($this->_ambit->getRedirectUrl(), JText::_('Please check the boxes of fields you want to change'), 'notice');
        return;
      }

      $cids_string  = $data['cids'];
      $cids         = explode(',', $cids_string);
      $change       = $data['change'];

      //delete all unselected fields
      foreach($data as $key => $value)
      {
        if(!in_array($key, $change))
        {
          unset($data[$key]);
        }
      }

      //save each image
      $return = array();
      foreach($cids as $cid)
      {
        $data['cid']  = $cid;
        $return[]     = $model->store($data);
      }

      if(!in_array(false, $return))
      {
      $this->setRedirect($this->_ambit->getRedirectUrl(), JText::sprintf('Successfully saved %d images.', count($return)));
      }
      else
      {
        $this->setRedirect($this->_ambit->getRedirectUrl(), JText::sprintf('Error saving images.'), 'error');
      }
      return;
    }*/

    // Get limitstart from request to set the correct limitstart (page) for redirect url
    $slimitstart = '';
    if(JRequest::getVar('limitstart', null) != null)
    {
      $slimitstart = '&limitstart='.JRequest::getInt('limitstart', 0);
    }

    // Set standard redirect URL
    $redirect = 'index.php?view=userpanel'.$slimitstart;
    // Is there any redirect requested?
    $url = JRequest::getVar('redirect', null, 'default', 'base64');
    if($url !== null)
    {
      $url = base64_decode($url);
      if(JURI::isInternal($url))
      {
        $redirect = $url;
      }
    }

    // Editing only one image
    if($id = $model->store())
    {
      $msg  = JText::_('COM_JOOMGALLERY_COMMON_MSG_IMAGE_SAVED');
      $this->setRedirect(JRoute::_($redirect, false), $msg);
    }
    else
    {
      $msg  = $model->getError();
      $this->setRedirect(JRoute::_($redirect, false), $msg, 'error');
    }
  }

  /**
   * Saves a category
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function savecategory()
  {
    $model = $this->getModel('editcategory');

    // Get limitstart from request to set the correct limitstart (page) for redirect url
    $slimitstart = '';
    if(JRequest::getVar('limitstart', null) != null)
    {
      $slimitstart = '&limitstart='.JRequest::getInt('limitstart', 0);
    }

    // Set default redirect URL
    $redirect = 'index.php?view=usercategories'.$slimitstart;

    // Check whether a redirect is requested
    if($url = JRequest::getVar('redirect', '', '', 'base64'))
    {
      $url = base64_decode($url);
      if(JURI::isInternal($url))
      {
        $redirect = $url;
      }
    }

    if($id = $model->store())
    {
      $msg  = JText::_('COM_JOOMGALLERY_COMMON_MSG_CATEGORY_SAVED');
      $this->setRedirect(JRoute::_($redirect, false), $msg);
    }
    else
    {
      $msg  = $model->getError();
      $this->setRedirect(JRoute::_($redirect, false), $msg, 'error');
    }
  }

  /**
   * Deletes an image
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function delete()
  {
    $model = $this->getModel('edit');

    // Get limitstart from request to set the correct limitstart (page) for redirect url
    $slimitstart = '';
    if(JRequest::getVar('limitstart', null) != null)
    {
      $slimitstart = '&limitstart='.JRequest::getInt('limitstart', 0);
    }

    // Set standard redirect URL
    $redirect = 'index.php?view=userpanel'.$slimitstart;
    // Is there any redirect requested?
    $url = JRequest::getVar('redirect', null, 'default', 'base64');
    if($url !== null)
    {
      $url = base64_decode($url);
      if(JURI::isInternal($url))
      {
        $redirect = $url;
      }
    }

    if($model->delete())
    {
      $msg  = JText::_('COM_JOOMGALLERY_COMMON_MSG_IMAGE_AND_COMMENTS_DELETED');
      $this->setRedirect(JRoute::_($redirect, false), $msg);
    }
    else
    {
      $msg  = $model->getError();
      $this->setRedirect(JRoute::_($redirect, false), $msg, 'error');
    }
  }

  /**
   * Publishes resp. unpublishes an image
   *
   * @access  public
   * @return  void
   * @since   1.5.7
   */
  function publishimage()
  {
    $model = $this->getModel('edit');

    // Get limitstart from request to set the correct limitstart (page) for redirect url
    $slimitstart = '';
    if(JRequest::getVar('limitstart', null) != null)
    {
      $slimitstart = '&limitstart='.JRequest::getInt('limitstart', 0);
    }

    if($model->publish())
    {
      $msg  = JText::_('COM_JOOMGALLERY_COMMON_MSG_SUCCESS_CHANGE_PUBLISH_STATE');
      $this->setRedirect(JRoute::_('index.php?view=userpanel'.$slimitstart, false), $msg);
    }
    else
    {
      $msg  = $model->getError();
      $this->setRedirect(JRoute::_('index.php?view=userpanel'.$slimitstart, false), $msg, 'error');
    }
  }

  /**
   * Deletes a category
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function deletecategory()
  {
    $model = $this->getModel('editcategory');

    // Get limitstart from request to set the correct limitstart (page) for redirect url
    $slimitstart = '';
    if(JRequest::getVar('limitstart', null) != null)
    {
      $slimitstart = '&limitstart='.JRequest::getInt('limitstart', 0);
    }

    if($model->delete())
    {
      $msg  = JText::_('COM_JOOMGALLERY_COMMON_MSG_SUCCESS_DELETING_CATEGORY');
      $this->setRedirect(JRoute::_('index.php?view=usercategories'.$slimitstart, false), $msg);
    }
    else
    {
      $msg  = $model->getError();
      $this->setRedirect(JRoute::_('index.php?view=usercategories'.$slimitstart, false), $msg, 'error');
    }
  }

  /**
   * Publishes resp. unpublishes a category
   *
   * @access  public
   * @return  void
   * @since   1.5.7
   */
  function publishcategory()
  {
    $model = $this->getModel('editcategory');

    // Get limitstart from request to set the correct limitstart (page) for redirect url
    $slimitstart = '';
    if(JRequest::getVar('limitstart', null) != null)
    {
      $slimitstart = '&limitstart='.JRequest::getInt('limitstart', 0);
    }

    if($model->publish())
    {
      $msg  = JText::_('COM_JOOMGALLERY_COMMON_MSG_SUCCESS_CHANGE_PUBLISH_STATE');
      $this->setRedirect(JRoute::_('index.php?view=usercategories'.$slimitstart, false), $msg);
    }
    else
    {
      $msg  = $model->getError();
      $this->setRedirect(JRoute::_('index.php?view=usercategories'.$slimitstart, false), $msg, 'error');
    }
  }

  /**
   * Redirects to the image view with the download parameter set to 1.
   * The image will be offered as a downloadable file then.
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function download()
  {
    // Check permissions
    if(   !$this->_config->get('jg_download')
      ||  (!$this->_config->get('jg_download_unreg') && !JFactory::getUser()->get('id'))
      )
    {
      $this->setRedirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_NOT_ALLOWED_VIEW_IMAGE'), 'notice');

      return;
    }

    $type = $this->_config->get('jg_downloadfile') ? 'orig' : 'img';

    $this->setRedirect(JRoute::_('index.php?view=image&format=raw&id='.JRequest::getInt('id').'&download=1&type='.$type, false));
  }
}