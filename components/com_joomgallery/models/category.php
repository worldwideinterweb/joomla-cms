<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/category.php $
// $Id: category.php 3496 2011-11-02 09:46:34Z erftralle $
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

/**
 * Category view model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelCategory extends JoomGalleryModel
{
  /**
   * Category data object
   *
   * @access  protected
   * @var     object
   */
  var $_category;

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
  var $_totalcategories;

  /**
   * Categories data array (without empty categories)
   *
   * @access  private
   * @var     array
   */
  var $_categorieswithoutempty;

  /**
   * Categories number (without empty categories)
   *
   * @access  private
   * @var     int
   */
  var $_totalcategorieswithoutempty = null;

  /**
   * Images data array
   *
   * @access  protected
   * @var     array
   */
  var $_images;

  /**
   * Images number
   *
   * @access  protected
   * @var     int
   */
  var $_totalimages;

  /**
   * Images data array holding all images of
   * the current category an its sub-categories
   *
   * @access  protected
   * @var     array
   */
  var $_allimages;

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

    $id = JRequest::getInt('catid', 0);
    $this->setId($id);
  }

  /**
   * Method to set the category identifier
   *
   * @access  public
   * @param   int     $id The Category ID
   * @return  void
   * @since   1.5.5
   */
  function setId($id)
  {
    // Set new category ID if valid and wipe data
    if(!$id)
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_NO_CATEGORY_SPECIFIED'), 'notice');
    }

    $this->_id                          = $id;
    $this->_category                    = null;
    $this->_categories                  = null;
    $this->_totalcategories             = null;
    $this->_categorieswithoutempty      = null;
    $this->_totalcategorieswithoutempty = null;
    $this->_images                      = null;
    $this->_totalimages                 = null;
  }

  /**
   * Method to get the data of the current category
   *
   * @access  public
   * @return  object  An object with the category data
   * @since   1.5.5
   */
  function getCategory()
  {
    if($this->_loadCategory())
    {
      return $this->_category;
    }

    return false;
  }

  /**
   * Retrieves the data of all sub-categories
   *
   * @access  public
   * @return  array   Array of objects containing the categories data from the database
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
  function getTotalCategories()
  {
    // Let's load the categories if they doesn't already exist
    if(empty($this->_totalcategories))
    {
      $query = $this->_buildCategoriesQuery();
      $this->_totalcategories = $this->_getListCount($query);
    }

    return $this->_totalcategories;
  }

  /**
   * Returns the array of all available sub-categories
   * of the the current category without empty categories
   *
   * @access  public
   * @return  array Categories objects array
   * @since   1.5.7
   */
  function getCategoriesWithoutEmpty()
  {
    if($this->_loadCategoriesWithoutEmpty())
    {
      // We still have to select the categories according to the pagination
      $limit      = $this->_config->get('jg_subperpage');#JRequest::getVar('limit', 0, '', 'int');
      $limitstart = JRequest::getInt('catlimitstart', 0);
      $cats = array_slice($this->_categorieswithoutempty, $limitstart, $limit);

      return $cats;
    }

    return array();
  }

  /**
   * Method to get the total number of categories without empty categories
   *
   * @access  public
   * @return  int     The total number of categories without empty ones
   * @since   1.5.7
   */
  function getTotalCategoriesWithoutEmpty()
  {
    // Let's calculate the number if it doesn't already exist
    if(empty($this->_totalcategorieswithoutempty))
    {
      if(!$this->_loadCategoriesWithoutEmpty())
      {
        return 0;
      }
    }

    return $this->_totalcategorieswithoutempty;
  }

  /**
   * Retrieves the data of all images of the current category
   *
   * @access  public
   * @return  array   Array of objects containing the images data from the database
   * @since   1.5.5
   */
  function getImages()
  {
    if($this->_loadImages())
    {
      return $this->_images;
    }

    return array();
  }

  /**
   * Retrieves the id from the first image of a category
   * needed for Option 'Skip category view'
   *
   * @access  public
   * @param   int    category id
   * @return  int    id of detail image
   * @since   2.0
   */
  function getImageCat($catId)
  {
    $orderby = array();

    if($this->_config->get('jg_firstorder'))
    {
      $orderby[] = 'a.'.$this->_config->get('jg_firstorder');
    }
    if($this->_config->get('jg_secondorder'))
    {
      $orderby[] = 'a.'.$this->_config->get('jg_secondorder');
    }
    if($this->_config->get('jg_thirdorder'))
    {
      $orderby[] = 'a.'.$this->_config->get('jg_thirdorder');
    }
    $orderby = count($orderby) ? implode(', ', $orderby) : '';

    $where = array();

    $where[] = "a.published = 1";
    $where[] = "a.catid     = ".$catId;
    $where[] = "a.approved  = 1";
    $where[] = "a.hidden    = 0";
    $where[] = "a.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")";
    $where[] = "c.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")";

    $where = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $query = "SELECT
                id
              FROM
                "._JOOM_TABLE_IMAGES." AS a
              LEFT JOIN
                "._JOOM_TABLE_CATEGORIES." AS c
              ON
                c.cid = a.catid
              ".$where."
              ORDER BY ".$orderby;

    $this->_db->setQuery($query);
    $img = $this->_db->loadResult();

    return $img;
  }

  /**
   * Method to get the total number of images
   *
   * @access  public
   * @return  int     The total number of images
   * @since   1.5.5
   */
  function getTotalImages()
  {
    // Let's load the categories if they doesn't already exist
    if(empty($this->_totalimages))
    {
      $query = $this->_buildImagesQuery();
      $this->_totalimages = $this->_getListCount($query);
    }

    return $this->_totalimages;
  }

  /**
   * Retrieves the data of all images of the current category and its sub-categories
   *
   * @access  public
   * @return  array   Array of objects containing the images data from the database
   * @since   1.5.7
   */
  function getAllImages()
  {
    if($this->_loadAllImages())
    {
      return $this->_allimages;
    }

    return array();
  }

  /**
   * Method to get one image selected by chance
   *
   * @access  public
   * @return  object  An object with the data of the selected image
   * @since   1.5.5
   */
  function getRandomImage($catid = 0, $random_catid = 0)
  {
    $query = "  SELECT
                  *,
                  c.access
                FROM
                  "._JOOM_TABLE_IMAGES." AS p
                LEFT JOIN
                  "._JOOM_TABLE_CATEGORIES." AS c
                ON
                  c.cid = p.catid";
    if($this->_config->get('jg_showrandomcatthumb') == 1)
    {
      $query.= "
                WHERE
                      p.catid = ".$catid;
    }
    else
    {
      if($this->_config->get('jg_showrandomcatthumb') >= 2)
      {
        $query.= "
                WHERE
                      p.catid = ".$random_catid;
      }
    }
    $query.= "
                  AND p.published = 1
                  AND p.approved  = 1
                  AND c.access   IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND c.published = 1
              ";

    $this->_db->setQuery($query);
    if(!$rows = $this->_db->loadObjectList())
    {
      return false;
    }

    $row = $rows[mt_rand(0, count($rows) - 1)];

    return $row;
  }

  /**
   * Method to load the data of the current category
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadCategory()
  {
    // Check whether the requested category exists and
    // whether the current user is allowed to see it
    $categories = $this->_ambit->getCategoryStructure();
    if(!isset($categories[$this->_id]))
    {
      JError::raiseError(500, JText::sprintf('Category with ID %d not found', $this->_id));
    }

    // Let's load the data if it doesn't already exist
    if(empty($this->_category))
    {
      $this->_db->setQuery("SELECT
                              cid,
                              name,
                              parent_id,
                              description,
                              metakey,
                              metadesc,
                              params
                            FROM
                              "._JOOM_TABLE_CATEGORIES."
                            WHERE
                                  cid       = ".$this->_id."
                              AND published = 1
                              AND access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")"
                          );
      if(!$row = $this->_db->loadObject())
      {
        JError::raiseError(500, JText::sprintf('Category with ID %d not found', $this->_id));
      }

      $this->_category = $row;
    }

    return true;
  }

  /**
   * Method to load the data of all sub-categories
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
      // Get the pagination request variables
      $limit      = $this->_config->get('jg_subperpage');#JRequest::getVar('limit', 0, '', 'int');
      $limitstart = JRequest::getInt('catlimitstart', 0);

      $query = $this->_buildCategoriesQuery();

      if(!$rows = $this->_getList($query, $limitstart, $limit))
      {
        return false;
      }

      $this->_categories = $rows;
    }

    return true;
  }

  /**
   * Loads the data of all available categories from the database
   * and checks whether the categories are empty.
   * This method stores only the categories which aren't empty after that.
   *
   * @access  private
   * @return  boolean True on success, false otherwise
   * @since   1.5.7
   */
  function _loadCategoriesWithoutEmpty()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_categorieswithoutempty))
    {
      $query = $this->_buildCategoriesQuery();
      if(!$cats = $this->_getList($query))
      {
        return false;
      }

      foreach($cats as $key => $cat)
      {
        // Get all sub-categories for each category which contain images
        $subcategories = JoomHelper::getAllSubCategories($cat->cid, true);
        if(!count($subcategories))
        {
          unset($cats[$key]);
        }
      }

      $this->_categorieswithoutempty      = $cats;
      $this->_totalcategorieswithoutempty = count($cats);
    }

    return true;
  }

  /**
   * Method to load the data of all images of the current category
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.5
   */
  function _loadImages()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_images))
    {
      // Get the pagination request variables
      $limitstart = JRequest::getInt('limitstart', 0);
      if($limitstart == -1)
      {
        // If we want to display all images in a popup box we will need all images
        $limit  = null;
      }
      else
      {
        $limit  = JRequest::getInt('limit', 0);
      }

      $query = $this->_buildImagesQuery();

      if(!$rows = $this->_getList($query, $limitstart, $limit))
      {
        return false;
      }

      $this->_images = $rows;
    }

    return true;
  }

  /**
   * Method to load the data of all images of the current category and its sub-categories
   *
   * @access  protected
   * @return  boolean   True on success, false otherwise
   * @since   1.5.7
   */
  function _loadAllImages()
  {
    // Let's load the data if it doesn't already exist
    if(empty($this->_allimages))
    {
      $limit = JRequest::getInt('limit', 0);

      if(!$limit)
      {
        // RSS in category view is disabled
        return false;
      }

      if($limit < 0)
      {
        // If $limit is negative all images will be loaded
        $limit  = null;
      }

      $catids = JoomHelper::getAllSubCategories($this->_id, true);

      $query = "SELECT
                  *,
                  a.owner AS owner
                FROM
                  "._JOOM_TABLE_IMAGES." AS a
                LEFT JOIN
                  "._JOOM_TABLE_CATEGORIES." AS c
                ON
                  c.cid = a.catid
                WHERE
                      c.cid IN (".implode(',', $catids).")
                  AND a.published = 1
                  AND a.approved  = 1
                  AND a.hidden    = 0
                  AND a.access   IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND c.published = 1
                  AND c.access   IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                  AND c.hidden    = 0
                ORDER BY
                  a.imgdate DESC";
      if(!$rows = $this->_getList($query, 0, $limit))
      {
        return false;
      }

      $this->_allimages = $rows;
    }

    return true;
  }

  /**
   * Returns the query for loading the categories
   *
   * @access  protected
   * @return  string    The query to be used to retrieve the categories data from the database
   * @since   1.5.5
   */
  function _buildCategoriesQuery()
  {
    $query = $this->_db->getQuery(true)
          ->select('c.*')
          ->from(_JOOM_TABLE_CATEGORIES.' AS c');

    // Join over the images if necessary
    if($this->_config->get('jg_showcatthumb') == 2)
    {
      $query->select('i.id, i.catid, i.imgthumbname, i.hidden AS imghidden')
            ->leftJoin(_JOOM_TABLE_IMAGES.' AS i ON (     c.thumbnail = i.id
                                                      AND i.published = 1
                                                      AND i.approved  = 1
                                                      AND i.access    IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).'))');
    }

    $query->where('c.published = 1')
          ->where('c.hidden    = 0')
          ->where('c.parent_id = '.$this->_id);

    if(!$this->_config->get('jg_showrestrictedcats'))
    {
      $query->where('c.access IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')');
    }

    if($this->_config->get('jg_hideemptycats'))
    {
      $query->where(' (((SELECT COUNT(id) FROM '._JOOM_TABLE_IMAGES.' AS i WHERE i.catid = c.cid) != 0)
                    OR
                      ((SELECT COUNT(cid) FROM '._JOOM_TABLE_CATEGORIES.' AS sc WHERE sc.parent_id = c.cid) != 0))');
    }

    if($this->_config->get('jg_ordersubcatbyalpha'))
    {
      $query->order('c.name');
    }
    else
    {
      $query->order('c.lft ASC');
    }

    return $query;
  }

  /**
   * Returns the query for loading the images
   *
   * @access  protected
   * @return  string    The query to be used to retrieve the images data from the database
   * @since   1.5.5
   */
  function _buildImagesQuery()
  {
    $query = "SELECT
                *,
                a.owner AS owner,
                ".JoomHelper::getSQLRatingClause('a')." AS rating";
    if($this->_config->get('jg_showcatcom'))
    {
      $query .= ",
                ( SELECT
                    COUNT(cmtid)
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
              LEFT JOIN
                "._JOOM_TABLE_CATEGORIES." AS c
              ON
                c.cid = a.catid
              ".$this->_buildImagesWhere()."
              ".$this->_buildImagesOrderby();

    return $query;
  }

  /**
   * Returns the 'where' part of the query for loading the images
   *
   * @access  protected
   * @return  string    The 'where' part of the query
   * @since   1.5.5
   */
  function _buildImagesWhere()
  {
    /*$filtercat = JRequest::getInt('filter');*/

    $where = array();

    $where[] = "a.published = 1";
    $where[] = "a.catid     = ".$this->_id;
    $where[] = "a.approved  = 1";
    $where[] = "a.hidden    = 0";
    $where[] = "a.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")";
    $where[] = "c.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")";

    /*//Filter by type
    switch($filtercat) {
      //published
      case 1:
        $where[] = 'published = 1';
        break;
      //not published
      case 2:
        $where[] = 'published = 0';
        break;
      //user categories
      case 3:
        $where[] = 'owner IS NOT NULL';
        break;
      //admin categories
      case 4:
        $where[] = 'owner IS NULL';
        break;
      default:
        break;
    }

    if ($searchtext = JRequest::getString('search'))
    {
      $filter   = $this->_db->Quote('%'.$this->_db->getEscaped($searchtext, true).'%', false);
      $where[]  = "(c.name LIKE $filter OR c.description LIKE $filter)";
    }*/

    $where = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    return $where;
  }

  /**
   * Returns the 'order by' part of the query for loading the images
   *
   * @access  protected
   * @return  string    The 'order by' part of the query
   * @since   1.5.5
   */
  function _buildImagesOrderBy()
  {
    $orderby = array();

    if($this->_config->get('jg_firstorder'))
    {
      $orderby[] = 'a.'.$this->_config->get('jg_firstorder');
    }
    if($this->_config->get('jg_secondorder'))
    {
      $orderby[] = 'a.'.$this->_config->get('jg_secondorder');
    }
    if($this->_config->get('jg_thirdorder'))
    {
      $orderby[] = 'a.'.$this->_config->get('jg_thirdorder');
    }

    $orderby = count($orderby) ? implode(', ', $orderby) : '';

    if($this->_config->get('jg_usercatorder'))
    {
      $user_orderby   = JRequest::getCmd('orderby');
      $user_orderdir  = JRequest::getCmd('orderdir');

      switch($user_orderby)
      {
        case 'user':
          $orderby = 'a.owner';
          break;
        case 'date':
          $orderby = 'a.imgdate';
          break;
        case 'rating':
          $orderby = 'rating';
          break;
        case 'title':
          $orderby = 'a.imgtitle';
          break;
        case 'hits':
          $orderby = 'a.hits';
          break;
        default:
          // Use selected ordering above
          break;
      }
      if(    $user_orderby == 'title'
          || $user_orderby == 'hits'
          || $user_orderby == 'user'
          || $user_orderby == 'date'
          || $user_orderby == 'rating'
        )
      {
        if($user_orderdir == 'desc')
        {
          $orderby .= ' DESC';
        }
        else
        {
          if($user_orderdir == 'asc')
          {
            $orderby .= ' ASC';
          }
        }
      }
      if($user_orderby == 'rating')
      {
          $orderby .= ', imgvotesum DESC';
      }
    }

    return 'ORDER BY '.$orderby;
  }
}