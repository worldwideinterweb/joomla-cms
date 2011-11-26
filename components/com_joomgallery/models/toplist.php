<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/toplist.php $
// $Id: toplist.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery toplist model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelToplist extends JoomGalleryModel
{
  /**
   * Images data array with the last commented images
   *
   * @access  protected
   * @var     int
   */
  var $_lastCommented;
 
  /**
   * Images data array with the last added images
   *
   * @access  protected
   * @var     int
   */
  var $_lastAdded;

  /**
   * Images data array with the top rated images
   *
   * @access  protected
   * @var     int
   */
  var $_topRated;

  /**
   * Images data array with the most viewed images
   *
   * @access  protected
   * @var     int
   */
  var $_mostViewed;

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
   * Method to get the last commented images
   *
   * @access  public
   * @return  object  An object containing the last commented images
   * @since   1.5.5
   */
  function getLastCommented()
  {
    if($this->_loadLastCommented())
    {
      return $this->_lastCommented;
    }

    return array();
  }

  /**
   * Method to load the last commented images from the database
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadLastCommented()
  {
    if(empty($this->_lastCommented))
    {
      $categories = $this->_ambit->getCategoryStructure();

      $rows = null;
      $this->_mainframe->triggerEvent('onJoomGetLastComments', array(&$rows, $this->_config->get('jg_toplist')));

      // If the data was not delivered by any plugin
      if(!$rows)
      {
        $query = "SELECT
                    a.*,
                    cc.*,
                    ca.*,
                    a.owner AS owner,
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
                    "._JOOM_TABLE_CATEGORIES." AS ca,
                    "._JOOM_TABLE_COMMENTS." AS cc
                  WHERE
                                a.id = cc.cmtpic
                    AND a.catid      = ca.cid
                    AND a.published  = 1
                    AND a.approved   = 1
                    AND cc.published = 1
                    AND ca.published = 1
                    AND cc.approved  = 1
                    AND ca.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                    AND ca.cid        IN (".implode(',', array_keys($categories)).")
                    AND a.hidden     = 0
                    AND ca.hidden    = 0
                    AND ca.in_hidden = 0
                 ORDER BY
                    cc.cmtdate DESC
                 LIMIT ".$this->_config->get('jg_toplist');

        $this->_db->setQuery($query);

        if(!$rows = $this->_db->loadObjectList())
        {
          return false;
        }
      }

      $this->_lastCommented = $rows;
    }

    return true;
  }

  /**
   * Method to get the last added images
   *
   * @access  public
   * @return  object  An object containing the last added images
   * @since   1.5.5
   */
  function getLastAdded()
  {
    if($this->_loadLastAdded())
    {
      return $this->_lastAdded;
    }

    return array();
  }

  /**
   * Method to load the last added images from the database
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadLastAdded()
  {
    if(empty($this->_lastAdded))
    {
      $categories = $this->_ambit->getCategoryStructure();

      $query = "SELECT
                  *,
                  a.owner AS owner,
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
                  "._JOOM_TABLE_IMAGES." As a,
                  "._JOOM_TABLE_CATEGORIES." AS ca
                WHERE
                           a.catid = ca.cid
                  AND a.published  = 1
                  AND a.approved   = 1
                  AND ca.published = 1
                  AND ca.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND ca.cid        IN (".implode(',', array_keys($categories)).")
                  AND a.hidden     = 0
                  AND ca.hidden    = 0
                  AND ca.in_hidden = 0
                ORDER BY
                  a.id DESC
                LIMIT ".$this->_config->get('jg_toplist');

      $this->_db->setQuery($query);

      if(!$rows = $this->_db->loadObjectList())
      {
        return false;
      }

      $this->_lastAdded = $rows;
    }

    return true;
  }

  /**
   * Method to get the top rated images
   *
   * @access  public
   * @return  object  An object containing the top rated images
   * @since   1.5.5
   */
  function getTopRated()
  {
    if($this->_loadTopRated())
    {
      return $this->_topRated;
    }

    return array();
  }

  /**
   * Method to load the top rated images from the database
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadTopRated()
  {
    if(empty($this->_topRated))
    {
      $categories = $this->_ambit->getCategoryStructure();

      $query = "SELECT
                  *,
                  a.owner AS owner,
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
                  "._JOOM_TABLE_CATEGORIES." AS ca
                WHERE
                           a.catid = ca.cid
                  AND a.imgvotes   > '0'
                  AND a.published  = 1
                  AND a.approved   = 1
                  AND ca.published = 1
                  AND ca.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND ca.cid        IN (".implode(',', array_keys($categories)).")
                  AND a.hidden     = 0
                  AND ca.hidden    = 0
                  AND ca.in_hidden = 0
                ORDER BY
                  rating DESC,
                  imgvotesum DESC
                LIMIT ".$this->_config->get('jg_toplist');

      $this->_db->setQuery($query);

      if(!$rows = $this->_db->loadObjectList())
      {
        return false;
      }

      $this->_topRated = $rows;
    }

    return true;
  }

  /**
   * Method to get the most viewed images
   *
   * @access  public
   * @return  object  An object containing the most viewed images
   * @since   1.5.5
   */
  function getMostViewed()
  {
    if($this->_loadMostViewed())
    {
      return $this->_mostViewed;
    }

    return array();
  }

  /**
   * Method to load the most viewd images from the database
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadMostViewed()
  {
    if(empty($this->_mostViewed))
    {
      $categories = $this->_ambit->getCategoryStructure();

      $query = "SELECT
                  *,
                  a.owner AS owner,
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
                  "._JOOM_TABLE_CATEGORIES." AS ca
                WHERE
                      a.hits > 0
                  AND a.catid      = ca.cid
                  AND a.published  = 1
                  AND a.approved   = 1
                  AND ca.published = 1
                  AND ca.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND ca.cid        IN (".implode(',', array_keys($categories)).")
                  AND a.hidden     = 0
                  AND ca.hidden    = 0
                  AND ca.in_hidden = 0
                ORDER BY
                  hits DESC
                LIMIT ".$this->_config->get('jg_toplist');

      $this->_db->setQuery($query);

      if(!$rows = $this->_db->loadObjectList())
      {
        return false;
      }

      $this->_mostViewed = $rows;
    }

    return true;
  }
}