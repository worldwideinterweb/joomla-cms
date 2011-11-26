<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/nametags.php $
// $Id: nametags.php 3378 2011-10-07 18:37:56Z aha $
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
 * Name tags Model
 *
 * Saves and removes name tags.
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelNametags extends JoomGalleryModel
{
  /**
   * The ID of the image the name tag belongs to
   *
   * @access  protected
   * @var     int
   */
  var $_id;

  /**
   * Constructor
   *
   * @access  protected
   * @return  void
   * @since   1.5.5
   */
  function __construct()
  {
    parent::__construct();

    $id = JRequest::getInt('id');
    $this->setId((int)$id);
  }

  /**
   * Method to set the image ID
   *
   * @access  public
   * @param   int     Image ID number
   * @since   1.5.5
   */
  function setId($id)
  {
    // Set new image ID if valid
    if(!$id)
    {
      JError::raiseError(500, JText::_('COM_JOOMGALLERY_COMMON_NO_IMAGE_SPECIFIED'));
    }
    $this->_id  = $id;
  }

  /**
   * Method to get the image ID
   *
   * @access  public
   * @return  int     The image ID
   * @since   1.5.5
   */
  function getId()
  {
    return $this->_id;
  }

  /**
   * Method to save a name tag
   *   
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  function save()
  {
    $yvalue   = JRequest::getInt('yvalue',  0, 'post');
    $xvalue   = JRequest::getInt('xvalue',  0, 'post');
    $height   = $this->_config->get('jg_nameshields_height');

    // Access check
    if(!$by = $this->_user->get('id'))
    {
      JError::raiseError(500, JText::_('COM_JOOMGALLERY_COMMON_PERMISSION_DENIED'));
    }

    // Check for hacking attempt
    $authorised_viewlevels = implode(',', $this->_user->getAuthorisedViewLevels());

    $query = $this->_db->getQuery(true)
          ->select('c.cid')
          ->from(_JOOM_TABLE_IMAGES.' AS a')
          ->leftJoin(_JOOM_TABLE_CATEGORIES.' AS c ON c.cid = a.catid')
          ->where('a.published = 1')
          ->where('a.approved = 1')
          ->where('a.id = '.$this->_id)
          ->where('a.access IN ('.$authorised_viewlevels.')')
          ->where('c.access IN ('.$authorised_viewlevels.')');

    $this->_db->setQuery($query);
    if(!$result = $this->_db->loadResult())
    {
      die('Hacking attempt, aborted!');
    }

    $categories = $this->_ambit->getCategoryStructure();
    if(!isset($categories[$result]))
    {
      die('Hacking attempt, aborted!');
    }

    if($this->_config->get('jg_nameshields_others'))
    {
      $userid = JRequest::getInt('userid');
    }
    else
    {
      $userid = $by;
    }

    // Check whether an existing user was selected
    $user = & JFactory::getUser($userid);
    if(!is_object($user))
    {
      $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_ERROR_SAVING'));
      return false;
    }

    $this->_db->setQuery("SELECT
                            nid
                          FROM
                            "._JOOM_TABLE_NAMESHIELDS."
                          WHERE
                                npicid  = ".$this->_id."
                            AND nuserid = ".$userid."
                        ");
    if($this->_db->loadResult())
    {
      if($userid == $by)
      {
        $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_YOU_ARE_ALREADY_TAGGED'));
      }
      else
      {
        $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_USER_ALREADY_TAGGED'));
      }
      return false;
    }

    $length = strlen($user->get('username')) * $this->_config->get('jg_nameshields_width');

    if(($xvalue < $height) && ($yvalue < $length))
    {
      $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_NOT_SAVED'));
      return false;
    }
  
    $this->_db->setQuery("SELECT
                            MIN(nzindex)
                          FROM
                            "._JOOM_TABLE_NAMESHIELDS."
                          WHERE
                            npicid = ".$this->_id."
                        ");
    $zindex = $this->_db->loadResult();
    if(!$zindex)
    {
      $zindex = 500;
    }
    else
    {
      $zindex--;
    }

    $row  = & $this->getTable('joomgallerynameshields');
    $date = & JFactory::getDate();

    $row->npicid  = $this->_id;
    $row->nuserid = $userid;
    $row->nxvalue = $xvalue;
    $row->nyvalue = $yvalue;
    $row->by      = $by;
    $row->nuserip = $_SERVER['REMOTE_ADDR'];
    $row->ndate   = $date->toMySQL();
    $row->nzindex = $zindex;

    if(!$row->store())
    {
      $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_ERROR_SAVING'));
      return false;
    }

    $this->_mainframe->triggerEvent('onJoomAfterTag', array($row));

    // Send messages
    if($this->_config->get('jg_msg_nametag_type'))
    {
      $image = &$this->getTable('joomgalleryimages');
      $image->load($this->_id);

      $user     = & JFactory::getUser($userid);
      $name     = $this->_config->get('jg_realname') ? $user->get('name') : $user->get('username');
      $by_name  = $this->_config->get('jg_realname') ? $this->_user->get('name') : $this->_user->get('username');

      require_once(JPATH_COMPONENT.DS.'helpers'.DS.'messenger.php');
      $messenger  = new JoomMessenger();

      // General Message
      if($by != $userid)
      {
        $subject = JText::sprintf('COM_JOOMGALLERY_MESSAGE_NEW_NAMETAG_OTHERS_BODY', $name, $by_name, $image->imgtitle, $this->_id);
      }
      else
      {
        $subject = JText::sprintf('COM_JOOMGALLERY_MESSAGE_NEW_NAMETAG_BODY', $name, $image->imgtitle, $this->_id);
      }
      $message    = array(
                          'from'    => $by,
                          'subject' => JText::_('COM_JOOMGALLERY_MESSAGE_NEW_NAMETAG_SUBJECT'),
                          'body'    => $subject,
                          'mode'    => 'nametag'
                        );

      // Message to image owner
      if($this->_config->get('jg_msg_nametag_toowner') && $by != $image->owner)
      {
        // Simply add the owner to the list of recipients
        $message['recipient'] = $image->owner;
      }

      // Send general message
      $messenger->send($message);

      // Message to tagged user
      if($this->_config->get('jg_msg_nametag_totaggeduser') && $by != $userid)
      {
        $url = JRoute::_('index.php?view=detail&id='.$this->_id, false).($this->_config->get('jg_anchors') ? '#joomimg' : '');

        // Ensure that the correct host and path is prepended
        $current_uri  = & JURI::getInstance(JURI::base());
        $current_host = $current_uri->toString(array('scheme', 'host', 'port'));
        $uri          = & JFactory::getUri($url);
        $uri->setHost($current_host);
        $url = $uri->toString();

        $message    = array(
                            'from'      => $by,
                            'recipient' => $userid,
                            'subject'   => JText::sprintf('COM_JOOMGALLERY_MESSAGE_YOU_WERE_TAGGED_SUBJECT', $this->_mainframe->getCfg('sitename')),
                            'body'      => JText::sprintf('COM_JOOMGALLERY_MESSAGE_YOU_WERE_TAGGED_BODY', $name, $image->imgtitle, $url),
                            'type'      => $messenger->getType('nametag')
                          );

        $messenger->send($message);
      }
    }

    return true;
  }

  /**
   * Method to delete a name tag
   *
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  function remove()
  {
    if(!$userid = $this->_user->get('id'))
    {
      JError::raiseError(500, JText::_('COM_JOOMGALLERY_COMMON_PERMISSION_DENIED'));
    }

    if(!$this->_config->get('jg_nameshields_others'))
    {
      $this->_db->setQuery("DELETE
                            FROM 
                              "._JOOM_TABLE_NAMESHIELDS."
                            WHERE 
                                  npicid  = ".$this->_id." 
                              AND nuserid = ".$userid."
                          ");
      if(!$this->_db->query())
      {
        $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_ERROR_DELETING'));
        return false;
      }
    }
    else
    {
      $nid = JRequest::getInt('nid');

      $row = &$this->getTable('joomgallerynameshields');
      $row->load($nid);
      if($row->nuserid == $userid || $row->by == $userid || $this->_user->authorise('core.manage', _JOOM_OPTION))
      {
        if(!$row->delete())
        {
          $this->setError(JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_MSG_ERROR_DELETING'));
          return false;
        }
      }
    }

    return true;
  }
}