<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/models/move.php $
// $Id: move.php 3378 2011-10-07 18:37:56Z aha $
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
 * Move images model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelMove extends JoomGalleryModel
{
  /**
   * Images data array
   *
   * @access  protected
   * @var     array
   */
  var $_images;

  /**
   * Categories data array
   *
   * @access  protected
   * @var     array
   */
  var $_categories;

  /**
   * Returns the query for loading all selected images
   *
   * @access  protected
   * @return  string    The query to be used to retrieve the images data from the database
   * @since   1.5.5
   */
  function _buildQuery()
  {
    $query = "SELECT
                *
              FROM
                "._JOOM_TABLE_IMAGES."
             ".$this->_buildWhere()."
             ".$this->_buildOrderby();

    return $query;
  }

  /**
   * Returns the 'where' part of the query for loading all selected images
   *
   * @access  protected
   * @return  string    The 'where' part of the query for loading all selected images
   * @since   1.5.5
   */
  function _buildWhere()
  {
    $cids = JRequest::getVar('cid', array(0), 'post', 'array');

    $ids = implode(',', $cids);

    $where = 'WHERE id IN ('.$ids.')';

    return $where;
  }

  /**
   * Returns the 'order by' part of the query for loading all selected images
   *
   * @access  protected
   * @return  string    The 'order by' part of the query for loading all selected images
   * @since   1.5.5
   */
  function _buildOrderBy()
  {
    $orderby = 'ORDER BY imgtitle, id';

    return $orderby;
  }

  /**
   * Retrieves the data of the selected images
   *
   * @access  public
   * @return  array   Array of objects containing the images data from the database
   * @since   1.5.5
   */
  function getImages()
  {
    // Lets load the data if it doesn't already exist
    if(empty($this->_images))
    {
      $query = $this->_buildQuery();
      $this->_images = $this->_getList($query);
    }

    return $this->_images;
  }

  /**
   * Retrieves the categories data
   *
   * @access  public
   * @return  array   Array of objects containing the categories data from the database
   * @since   1.5.5
   */
  function getCategories()
  {
    // Lets load the data if it doesn't already exist
    if(empty($this->_categories))
    {
      $query = "SELECT
                  cid,
                  parent_id,
                  name
                FROM
                  "._JOOM_TABLE_CATEGORIES."
                ORDER BY
                  name";
      $this->_categories = $this->_getList($query);
    }

    return $this->_categories;
  }
}