<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/favourites.php $
// $Id: favourites.php 3492 2011-11-01 14:24:45Z chraneco $
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
 * JoomGallery Favourites Model
 *
 * Handles the favourites of a user and the zip download
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelFavourites extends JoomGalleryModel
{
  /**
   * The ID of the image to work with
   *
   * @access  protected
   * @var     int
   */
  var $_id;

  /**
   * A comma separated list of favoured images
   *
   * @access  protected
   * @var     string
   */
  var $piclist;

  /**
   * Determines whether the database is used or the session to store the images
   *
   * @access  protected
   * @var     boolean
   */
  var $using_database;

  /**
   * Determines whether the current user already has an entry
   * in the database table for the favourites and the zip download
   *
   * @access  protected
   * @var     boolean
   */
  var $user_exists;

  /**
   * Holds the current layout
   *
   * @access  protected
   * @var     string
   */
  var $layout;

  /**
   * Holds the prefix of the language constants for the favourites
   *
   * @access  protected
   * @var     string
   */
  var $_output;

  /**
   * Constructor
   *
   * @access  protected
   * @return  void
   * @since   1.0.0
   */
  function __construct()
  {
    parent::__construct();

    // Check access rights
    if(   !$this->_config->get('jg_favourites')
       || (!$this->_config->get('jg_usefavouritesforpubliczip') && !$this->_user->get('id'))
      )
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_PERMISSION_DENIED'), 'notice');
    }

    // Set the image id
    $view = JRequest::getCmd('view');
    $task = JRequest::getCmd('task');
    if(   $view != 'favourites'
      &&  $view != 'downloadzip'
      &&  $task != 'removeall'
      &&  $task != 'switchlayout'
      &&  $task != 'createzip'
      )
    {
      $id = JRequest::getInt('id');
      $this->setId($id);
    }

    // Check whether we will work with the database or the session
    if($this->_user->get('id') && $this->_config->get('jg_usefavouritesforzip') != 1)
    {
      $this->using_database = true;
      $this->_output        = 'COM_JOOMGALLERY_FAVOURITES_MSG_';

      $this->_db->setQuery("SELECT
                              piclist,
                              layout
                            FROM
                              "._JOOM_TABLE_USERS."
                            WHERE
                              uuserid = ".$this->_user->get('id')."
                          ");

      if($row = $this->_db->loadObject())
      {
        $this->user_exists  = true;
        $this->piclist      = $row->piclist;
        $this->layout       = $row->layout;
      }
      else
      {
        $this->user_exists  = false;
        $this->piclist      = null;
        $this->layout       = 0;
      }
    }
    else
    {
      $this->using_database = false;
      $this->_output        = 'COM_JOOMGALLERY_FAVOURITES_ZIP_MSG_';

      $this->piclist = $this->_mainframe->getUserState('joom.favourites.pictures');
      $this->layout  = $this->_mainframe->getUserState('joom.favourites.layout');
    }
  }

  /**
   * Method to set the image id
   *
   * @access  public
   * @param   int     Image ID number
   * @return  void
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
   * Method to get the identifier
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
   * Method to get the current layout
   *
   * @access  public
   * @return  string  The name of the current layout
   * @since   1.5.5
   */
  function getLayout()
  {
    return $this->layout;
  }

  /**
   * Method to add an image to the favourites or the zip download
   *
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.0.0
   */
  function addImage()
  {
    $this->_db->setQuery("SELECT
                            id
                          FROM
                            "._JOOM_TABLE_IMAGES." AS a
                          LEFT JOIN
                            "._JOOM_TABLE_CATEGORIES." AS c ON a.catid = c.cid
                          WHERE
                                a.id        = ".$this->_id."
                            AND a.approved  = 1
                            AND a.published = 1
                            AND c.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                            AND c.published = 1
                        ");
    if(!$this->_db->loadResult())
    {
      die('Stop Hacking attempt!');
    }

    $catid = JRequest::getInt('catid');

    if(is_null($this->piclist))
    {
      if($this->using_database)
      {
        if($this->user_exists)
        {
          $this->_db->setQuery("UPDATE
                                  "._JOOM_TABLE_USERS."
                                SET
                                  piclist = ".$this->_id."
                                WHERE
                                  uuserid = '".$this->_user->get('id')."'
                              ");
        }
        else
        {
          $this->_db->setQuery("INSERT INTO
                                  "._JOOM_TABLE_USERS."
                                  (uuserid, piclist)
                                VALUES
                                  (".$this->_user->get('id').", ".$this->_id.")
                              ");
        }

        $return = $this->_db->query();
      }
      else
      {
        $this->_mainframe->setUserState('joom.favourites.pictures', $this->_id);
      }
    }
    else
    {
      $piclist_array = explode(',', $this->piclist);

      if(in_array($this->_id, $piclist_array))
      {
        // Image is already in there
        $this->_mainframe->enqueueMessage($this->output('ALREADY_IN'));
        return true;
      }
      if(count($piclist_array) == $this->_config->get('jg_maxfavourites'))
      {
        // Maximum number of images already reached
        $this->_mainframe->enqueueMessage($this->output('ALREADY_MAX'));
        return true;
      }

      if($this->using_database)
      {
        $this->_db->setQuery("UPDATE
                                "._JOOM_TABLE_USERS."
                              SET
                                piclist = '".$this->piclist.', '.$this->_id."'
                              WHERE
                                uuserid = ".$this->_user->get('id')."
                            ");
        $return = $this->_db->query();
      }
      else
      {
        $this->_mainframe->setUserState('joom.favourites.pictures', $this->piclist.','.$this->_id);
      }
    }

    if(isset($return) && !$return)
    {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }

    $this->_mainframe->enqueueMessage($this->output('SUCCESSFULLY_ADDED'));

    $this->_mainframe->triggerEvent('onJoomAfterAddFavourite', array($this->_id));

    return true;
  }

  /**
   * Method to remove an image from the favourites or the zip download
   *
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.0.0
   */
  function removeImage()
  {
    $piclist = explode(',', $this->piclist);
    if(!in_array($this->_id, $piclist))
    {
      $this->_mainframe->enqueueMessage($this->output('NOT_IN'));
      return true;
    }

    $new_piclist = array();
    foreach($piclist as $picid)
    {
      if($picid != $this->_id)
      {
        array_push($new_piclist, $picid);
      }
    }
    if(!count($new_piclist))
    {
      $new_piclist = NULL;
      $set_piclist = "SET piclist = NULL ";
    }
    else
    {
      $new_piclist = implode(',', $new_piclist);
      $set_piclist = "SET piclist = '".$new_piclist."' ";
    }

    if($this->using_database)
    {
      $this->_db->setQuery("UPDATE
                              "._JOOM_TABLE_USERS."
                              $set_piclist
                            WHERE
                              uuserid = ".$this->_user->get('id')."
                          ");
      if(!$this->_db->query())
      {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
    }
    else
    {
      $this->_mainframe->setUserState('joom.favourites.pictures', $new_piclist);
    }

    $this->_mainframe->enqueueMessage($this->output('SUCCESSFULLY_REMOVED'));

    $this->_mainframe->triggerEvent('onJoomAfterRemoveFavourite', array($this->_id));

    return true;
  }

  /**
   * Method to remove all images from the favourites or the zip download
   *
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.0.0
   */
  function removeAll()
  {
    if($this->using_database)
    {
      $this->_db->setQuery("UPDATE
                              "._JOOM_TABLE_USERS."
                            SET
                              piclist = NULL
                            WHERE
                              uuserid = ".$this->_user->get('id')."
                          ");
      if(!$this->_db->query())
      {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
    }
    else
    {
      $this->_mainframe->setUserState('joom.favourites.pictures', NULL);
    }

    $this->_mainframe->enqueueMessage($this->output('ALL_REMOVED'));

    $this->_mainframe->triggerEvent('onJoomAfterClearFavourites');

    return true;
  }

  /**
   * Method to switch the current layout
   *
   * @access  public
   * @return  boolean True
   * @since   1.0.0
   */
  function switchLayout()
  {
    $layout = JRequest::getCmd('layout');
    if(
        ($layout && $layout != 'default')
      ||
         $this->layout
      )
    {
      if($this->using_database)
      {
        $this->_db->setQuery("UPDATE
                                "._JOOM_TABLE_USERS."
                              SET
                                layout  = '0'
                              WHERE
                                uuserid = ".$this->_user->get('id')."
                            ");
        $this->_db->query();
      }
      else
      {
        $this->_mainframe->setUserState('joom.favourites.layout', 0);
      }
    }
    else
    {
      if($this->using_database)
      {
        $this->_db->setQuery("UPDATE
                                "._JOOM_TABLE_USERS."
                              SET
                                layout = '1'
                              WHERE
                                uuserid = ".$this->_user->get('id')."
                            ");
        $this->_db->query();
      }
      else
      {
        $this->_mainframe->setUserState('joom.favourites.layout', 1);
      }
    }

    return true;
  }

  /**
   * Method to create the zip archive with all selected images
   *
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.0.0
   */
  function createZip()
  {
    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');
    jimport('joomla.filesystem.archive');

    $zip_adapter =& JArchive::getAdapter('zip');

    // Check whether zip download is allowed
    if(    !$this->_config->get('jg_zipdownload')
        && ($this->_user->get('id') || !$this->_config->get('jg_usefavouritesforpubliczip'))
      )
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=favourites', false), JText::_('COM_JOOMGALLERY_FAVOURITES_MSG_NOT_ALLOWED'), 'notice');
    }

    if(is_null($this->piclist))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=favourites', false), $this->output('NO_IMAGES'), 'notice');
    }
    // DB->getEscaped will be deprecated in Joomla! 1.7, choose escaped()
    $query = $this->_db->getQuery(true)
      ->select('id')
      ->select('catid')
      ->select('imgfilename')
      ->from(_JOOM_TABLE_IMAGES.' AS a')
      ->from(_JOOM_TABLE_CATEGORIES.' AS c')
      ->where('id IN ('.$this->_db->getEscaped($this->piclist).')')
      ->where('a.catid      = c.cid')
      ->where('a.published  = 1')
      ->where('a.approved   = 1')
      ->where('c.published  = 1')
      ->where('a.access     IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')')
      ->where('c.access     IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')');

    $this->_db->setQuery($query);
    $rows = $this->_db->loadObjectList();

    if(!count($rows))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=favourites', false), $this->output('NO_IMAGES'), 'notice');
    }

    // Name of the zip archive
    $zipname = 'components/'._JOOM_OPTION.'/joomgallery_'.date('d_m_Y').'__';
    if($userid = $this->_user->get('id'))
    {
      $zipname .= $userid.'_';
    }
    $zipname .= mt_rand(10000, 99999).'.zip';

    $files  = array();

    if($this->_config->get('jg_downloadwithwatermark'))
    {
      $include_watermark = true;

      // Get the 'image' model
      $imageModel = parent::getInstance('image', 'joomgallerymodel');

      // Get the temp path for storing the watermarked image temporarily
      if(!JFolder::exists($this->_ambit->get('temp_path')))
      {
        $this->setError(JText::_('COM_JOOMGALLERY_UPLOAD_ERROR_TEMP_MISSING'));
        return false;
      }
      else
      {
        $tmppath = $this->_ambit->get('temp_path');
      }
    }
    else
    {
      $include_watermark = false;
    }

    $categories = $this->_ambit->getCategoryStructure();
    foreach($rows as &$row)
    {
      if(!isset($categories[$row->catid]))
      {
        continue;
      }

      // Get the original image if existent, otherwise the detail image
      $orig = $this->_ambit->getImg('orig_path', $row->id);
      $img = $this->_ambit->getImg('img_path', $row->id);

      if(file_exists($orig))
      {
        $image = $orig;
      }
      else if(file_exists($img))
      {
        $image = $img;
      }
      else
      {
        $image = null;
        continue;
      }
      $files[$row->id]['name'] = $row->imgfilename;

      // Watermark the image before if needed
      if($include_watermark)
      {
        // Get the image resource of watermarked image
        $imgres = $imageModel->includeWatermark($image);

        // Start output buffering
        ob_start();

        // According to mime type output the watermarked image resource to file
        $info = getimagesize($orig);
        switch($info[2])
        {
          case 1:
            imagegif($imgres);
            break;
          case 2:
            imagejpeg($imgres);
            break;
          case 3:
            imagepng($imgres);
            break;
          default:
            JError::raiseError(404, JText::sprintf('COM_JOOMGALLERY_COMMON_MSG_MIME_NOT_ALLOWED', $mime));
            break;
        }

        // Read the content from output buffer and fill the array element
        $files[$row->id]['data'] = ob_get_contents();

        // Delete the output buffer
        ob_end_clean();
      }
      else
      {
        $files[$row->id]['data'] = JFile::read($image);
      }

    }

    if(!count($files))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=favourites', false), $this->output('NO_IMAGES'), 'notice');
    }

    // Trigger event 'onJoomBeforeZipDownload'
    $plugins = $this->_mainframe->triggerEvent('onJoomBeforeZipDownload', array(&$files));
    if(in_array(false, $plugins, true))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=favourites', false));
    }

    $createzip = $zip_adapter->create($zipname, $files, 'zip');

    if(!$createzip)
    {
      // Workaround for servers with wwwwrun problem
      JoomFile::chmod(JPATH_COMPONENT, '0777', true);
      $createzip = $zip_adapter->create( $zipname, $files, 'zip');
      JoomFile::chmod(JPATH_COMPONENT, '0755', true);
    }
    if($this->_user->get('id'))
    {
      if($this->user_exists)
      {
        $query = $this->_db->getQuery(true)
          ->select("zipname")
          ->from(_JOOM_TABLE_USERS)
          ->where("uuserid = '".$this->_user->get('id')."'");

        $this->_db->setQuery($query);

        if($old_zip = $this->_db->loadResult())
        {
          if(file_exists($old_zip))
          {
            jimport('joomla.filesystem.file');
            JFile::delete($old_zip);
          }
        }
        $query = $this->_db->getQuery(true)
          ->update(_JOOM_TABLE_USERS)
          ->set("time = NOW()")
          ->set("zipname = '".$zipname."'")
          ->where("uuserid = ".$this->_user->get('id'));

        $this->_db->setQuery($query);
      }
      else
      {
        $query = $this->_db->getQuery(true)
          ->insert(_JOOM_TABLE_USERS)
          ->set("uuserid = ".$this->_user->get('id'))
          ->set("time =  = NOW()")
          ->set("zipname = ".$zipname);

        $this->_db->setQuery($query);
      }
    }
    else
    {
      $query = $this->_db->getQuery(true)
        ->insert(_JOOM_TABLE_USERS)
        ->set("time = NOW()")
        ->set("zipname = '".$zipname."'");

      $this->_db->setQuery($query);
    }
    $this->_db->query();

    if(!$createzip)
    {
      $this->setError($zipfile->errorInfo(true));
      return false;
    }

    $this->_mainframe->setUserState('joom.favourites.zipname', $zipname);

    // Message about new zip download
    if(!$this->_user->get('username'))
    {
      $username = JText::_('COM_JOOMGALLERY_COMMON_GUEST');
    }
    else
    {
      $username = $this->_config->get('jg_realname') ? $this->_user->get('name') : $this->_user->get('username');
    }

    if($this->_config->get('jg_msg_zipdownload'))
    {
      $imagefiles = implode(",\n", $files);
      require_once JPATH_COMPONENT.DS.'helpers'.DS.'messenger.php';
      $messenger    = new JoomMessenger();
      $message      = array(
                            'subject'   => JText::_('COM_JOOMGALLERY_MESSAGE_NEW_ZIPDOWNLOAD_SUBJECT'),
                            'body'      => JText::sprintf('COM_JOOMGALLERY_MESSAGE_NEW_ZIPDOWNLOAD_BODY',
                                           $zipname, $username, $imagefiles),
                            'mode'      => 'zipdownload'
                            );
      $messenger->send($message);
    }

    return true;
  }

  /**
   * Method to get all the favourites of the current user
   *
   * @access  public
   * @return  array   An array of images data
   * @since   1.5.5
   */
  function getFavourites()
  {
    if($this->_loadFavourites())
    {
      return $this->_favourites;
    }

    return array();
  }

  /**
   * Method to load the image data from the database
   *
   * @access  private
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  function _loadFavourites()
  {
    // Load the images if they don't already exist
    if(empty($this->_favourites))
    {
      $query = "SELECT
                  *,
                  a.catid,
                  a.owner AS imgowner,
                  ".JoomHelper::getSQLRatingClause('a')." AS rating";
      if($this->_config->get('jg_showcatcom'))
      {
        $query .= ",
                  ( SELECT
                      COUNT(*)
                    FROM
                      "._JOOM_TABLE_COMMENTS."
                    WHERE
                            cmtpic = a.id
                      AND published = 1
                      AND approved  = 1
                  ) AS comments";
      }
      $query .= "
                FROM
                  "._JOOM_TABLE_IMAGES." AS a,
                  "._JOOM_TABLE_CATEGORIES." AS c
                  ".$this->_buildWhereClause()."
                  ".$this->_buildOrderClause();

      $this->_db->setQuery($query);

      $rows = $this->_db->loadObjectList();
      if($this->_db->getErrorNum())
      {
        return false;
      }

      $categories = $this->_ambit->getCategoryStructure();
      foreach($rows as $key => $row)
      {
        if(!isset($categories[$row->catid]))
        {
          unset($rows[$key]);
        }
      }

      $this->_favourites = $rows;

      // The list of favourites is filtered now, so that only valid images are chosen.
      // So we store this list now in order to delete invalid images from the list.
      if($this->using_database)
      {
        $ids = '';
        foreach($rows as $row)
        {
          $ids .= $row->id.',';
        }
        $query = "UPDATE
                    "._JOOM_TABLE_USERS."
                  SET
                    piclist = ".((count($rows)) ? "'".trim($ids, ',')."'" : "NULL")."
                  WHERE
                    uuserid = ".$this->_user->get('id');
        $this->_db->setQuery($query);
        $this->_db->query();
      }

      return true;
    }
  }

  /**
   * Returns the 'WHERE' part of the query for loading the images
   *
   * @access  private
   * @return  string  The 'WHERE' part of the query
   * @since   1.5.5
   */
  function _buildWhereClause()
  {
    $where    = array();
    $where[]  = "a.catid      = c.cid";
    $where[]  = "a.published  = 1";
    $where[]  = "a.approved   = 1";
    $where[]  = "c.published  = 1";
    $where[]  = 'a.access     IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')';
    $where[]  = "c.access     IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")";
    $where[]  = "c.hidden     = 0";
    $where[]  = "c.in_hidden  = 0";

    $where = count($where) ? 'WHERE '.implode(' AND ', $where) : '';

    if(is_null($this->piclist))
    {
      $where .= " LIMIT 0";
    }
    else
    {
      $where .= " AND a.id IN (".$this->_db->getEscaped($this->piclist).")";
    }

    return $where;
  }

  /**
   * Returns the 'ORDER BY' part of the query for loading the images
   *
   * @access  private
   * @return  string  The 'ORDER BY' part of the query
   * @since   1.5.5
   */
  function _buildOrderClause()
  {
    $orderby = '';

    return $orderby;
  }

  /**
   * Returns a language string depending on the used mode for the zip download
   *
   * @access  public
   * @param   string  The main part of the language constant to use
   * @return  string  The translated string of the selected and completed language constant
   * @since   1.5.5
   */
  function output($msg)
  {
    return JText::_($this->_output.$msg);
  }
}