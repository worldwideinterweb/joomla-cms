<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/search.php $
// $Id: search.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery search model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelSearch extends JoomGalleryModel
{
  /**
   * Images data array with the search results
   *
   * @access  protected
   * @var     array
   */
  var $_searchResults;

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
   * Method to get the images searched for
   *
   * @access  public
   * @return  object  An object containing the search results
   * @since   1.5.5
   */
  function getSearchResults()
  {
    if($this->_loadSearchResults())
    {
      return $this->_searchResults;
    }

    return array();
  }

  /**
   * Method to get the images searched for from the database
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadSearchResults()
  {
    if(empty($this->_searchResults))
    {
      $sstring        = JRequest::getString('sstring');
      $searchstring   = $this->_db->getEscaped(trim($sstring));
      //$searchstring2  = $this->_db->getEscaped(htmlentities(trim($this->_strtolower_utf8($sstring)), ENT_QUOTES, 'UTF-8'));

      $categories = $this->_ambit->getCategoryStructure();

      $plugins = $this->_mainframe->triggerEvent('onJoomSearch', array($searchstring));

      $query = "SELECT
                  a.*,
                  ".JoomHelper::getSQLRatingClause('a')." AS rating,
                  u.username,
                  ca.cid,
                  ca.name AS name";
      foreach($plugins as $plugin)
      {
        if(isset($plugin['images.select']))
        {
          $query .= ",
                  ".$plugin['images.select'];
        }
      }
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
                  "._JOOM_TABLE_IMAGES." AS a
                LEFT JOIN  "._JOOM_TABLE_CATEGORIES." AS ca ON a.catid = ca.cid
                LEFT JOIN  #__users AS u ON a.owner = u.id";
      foreach($plugins as $plugin)
      {
        if(isset($plugin['images.leftjoin']))
        {
          $query .= "
                LEFT JOIN ".$plugin['images.leftjoin'];
        }
      }
      $query .= "
                WHERE
                      (u.username       LIKE '%$searchstring%'
                    OR a.imgtitle       LIKE '%$searchstring%'
                    OR LOWER(a.imgtext) LIKE '%$searchstring%'";
      foreach($plugins as $plugin)
      {
        if(isset($plugin['images.where.or']))
        {
          $query .= "
                    OR ".$plugin['images.where.or'];
        }
      }
      $query .= ")
                  AND a.published   = 1
                  AND ca.published  = 1
                  AND a.approved    = 1
                  AND ca.access     IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND ca.cid        IN (".implode(',', array_keys($categories)).")
                  AND a.hidden      = 0
                  AND ca.hidden     = 0
                  AND ca.in_hidden  = 0";
      foreach($plugins as $plugin)
      {
        if(isset($plugin['images.where']))
        {
          $query .= "
                  AND ".$plugin['images.where'];
        }
      }
      $query .= "
                GROUP BY
                  a.id
                ORDER BY
                  a.id DESC";

      $this->_db->setQuery($query);

      if(!$rows = $this->_db->loadObjectList())
      {
        return false;
      }

      $this->_searchResults = $rows;
    }

    return true;
  }
}