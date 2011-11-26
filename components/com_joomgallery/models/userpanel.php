<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/userpanel.php $
// $Id: userpanel.php 3401 2011-10-14 06:39:05Z chraneco $
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
 * JoomGallery User Panel Model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelUserpanel extends JoomGalleryModel
{
  /**
   * Images data array
   *
   * @var array
   */
  protected $_images;

  /**
   * Images number
   *
   * @var int
   */
  protected $_total;

  /**
   * Categories data array
   *
   * @var array
   */
  protected $_categories;

  /**
   * Constructor
   *
   * @return  void
   * @since   1.5.5
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Retrieves the images data
   *
   * @return  array An array of images objects
   * @since   1.5.5
   */
  public function getImages()
  {
    if($this->_loadImages())
    {
      return $this->_images;
    }

    return array();
  }

  /**
   * Method to get the total number of images
   *
   * @return  int   The total number of images
   * @since   1.5.5
   */
  public function getTotal()
  {
    // Let's load the data if it doesn't already exist
    if (empty($this->_total))
    {
      $query = $this->_buildQuery();
      $this->_total = $this->_getListCount($query);
    }

    return $this->_total;
  }

  /**
   * Retrieves the categories data from the database
   *
   * @return  array An array of category IDs
   * @since   1.5.5
   */
  public function getCategories()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_categories))
    {
      $query = $this->_db->getQuery(true)
            ->select('cid')
            ->from(_JOOM_TABLE_CATEGORIES);
      if(!$this->_config->get('jg_showallpicstoadmin') || !$this->_user->authorise('core.admin'))
      {
        $query->where('owner = '.$this->_user->get('id'));
      }

      $this->_db->setQuery($query);

      $this->_categories  = $this->_db->loadColumn();
    }

    return $this->_categories;
  }

  /**
   * Loads the images data from the database
   *
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  protected function _loadImages()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_images))
    {
      jimport('joomla.filesystem.file');

      $query = $this->_buildQuery();

      // Get the pagination request variables
      $limit      = JRequest::getInt('limit', 0);
      $limitstart = JRequest::getInt('limitstart', 0);

      if(!$rows = $this->_getList($query, $limitstart, $limit))
      {
        return false;
      }

      $this->_images = $rows;
    }

    return true;
  }

  /**
   * Returns the query to get the images rows from the database
   *
   * @return  string  The query to get the image rows from the database
   * @since   1.5.5
   */
  protected function _buildQuery()
  {
    $query = $this->_db->getQuery(true)
          ->select('*')
          ->from(_JOOM_TABLE_IMAGES);

    // Filter by state
    $filter = JRequest::getInt('filter');
    switch($filter)
    {
      case 1:
        // Approved
        $query->where('approved = 1');
        break;
      case 2:
        // Not approved
        $query->where('approved = 0');
        break;
      case 3:
        // Published
        $query->where('published = 1');
        break;
      case 4:
        // Not published
        $query->where('published = 0');
        break;
      default:
        // No filter by state
        break;
    }

    // Filter by category
    if($catid  = JRequest::getInt('catid'))
    {
      $query->where('catid = '.$catid);
    }

    // A Super User will see all images if the correspondent backend option is enabled
    if(!$this->_config->get('jg_showallpicstoadmin') || !$this->_user->authorise('core.admin'))
    {
      $query->where('owner = '.$this->_user->get('id'));
    }

    // Search
    $search = trim(JRequest::getString('search'));
    if(!empty($search))
    {
      $search   = $this->_db->Quote('%'.$this->_db->getEscaped($search, true).'%', false);
      $query->where('(LOWER(imgtitle) LIKE '.$search.' OR LOWER(imgtext) LIKE '.$search.')');
    }

    // Add the order clause
    $sordercat = JRequest::getInt('ordering');
    switch($sordercat)
    {
      case 1:
        $query->order('imgdate DESC');
        break;
      case 2:
        $query->order('imgtitle ASC');
        break;
      case 3:
        $query->order('imgtitle DESC');
        break;
      case 4:
        $query->order('hits ASC');
        break;
      case 5:
        $query->order('hits DESC');
        break;
      case 6:
        $query->order('catid ASC,imgtitle ASC');
        break;
      case 7:
        $query->order('catid ASC,imgtitle DESC');
        break;
      default:
        $query->order('imgdate ASC');
        break;
    }

    return $query;
  }
}