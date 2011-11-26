<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/usercategories.php $
// $Id: usercategories.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery User Categories Model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelUsercategories extends JoomGalleryModel
{
  /**
   * Categories data array
   *
   * @access  protected
   * @var     array
   */
  var $_categories;

  /**
   * Categories number
   *
   * @access  protected
   * @var     int
   */
  var $_total = null;

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
  }

  /**
   * Retrieve the category data
   *
   * @access  public
   * @return  array     Array of objects containing the category data
   * @since   1.5.5
   */
  function getCategories()
  {
    if($this->_loadCategories())
    {
      return $this->_categories;
    }

    return array();
  }

  /**
   * Method to get the total number of categories
   *
   * @access  public
   * @return  int     The total number of categories
   * @since   1.5.5
   */
  function getTotal()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_total))
    {
      $query = $this->_buildQuery();
      $this->_total = $this->_getListCount($query);
    }

    return $this->_total;
  }

  /**
   * Loads the categories data from the database
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadCategories()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_categories))
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

      $this->_categories = $rows;
    }

    return true;
  }

  /**
   * Returns the query to get the category rows from the database
   *
   * @access  protected
   * @return  string    The query to be used to retrieve the category rows from the database
   * @since   1.5.5
   */
  function _buildQuery()
  {
    $query = $this->_db->getQuery(true)
          ->select('c.cid, c.name, c.owner, c.thumbnail, c.parent_id, c.published, c.hidden')
          ->select('(SELECT COUNT(cid) FROM '._JOOM_TABLE_CATEGORIES.' AS b WHERE b.parent_id = c.cid) AS children')
          ->select('(SELECT COUNT(id) FROM '._JOOM_TABLE_IMAGES.' AS a WHERE a.catid = c.cid) AS images')
          ->from(_JOOM_TABLE_CATEGORIES.' AS c')
          ->where('parent_id > 0')
    
    // Join over the images for category thumbnail
          ->select('i.id, i.catid, i.imgthumbname, i.hidden AS imghidden')
          ->leftJoin(_JOOM_TABLE_IMAGES.' AS i ON (     c.thumbnail = i.id
                                                    AND i.published = 1
                                                    AND i.approved  = 1
                                                    AND i.access    IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).'))');

    // Filter by state
    $filter = JRequest::getInt('filter', null);

    switch($filter)
    {
      case 1:
        // Published
        $query->where('published = 1');
        break;
      case 2:
        // Not published
        $query->where('published = 0');
        break;
      default:
        // No filter by state
        break;
    }

    // A Super User will see all categories if the correspondent backend option is enabled
    if(!$this->_config->get('jg_showallpicstoadmin') || !$this->_user->authorise('core.admin'))
    {
      $query->where('c.owner = '.$this->_user->get('id'));
    }

    $query->order('c.lft ASC');

    return $query;
  }
}