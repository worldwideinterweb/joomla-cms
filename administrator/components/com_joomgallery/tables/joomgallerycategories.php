<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/tables/joomgallerycategories.php $
// $Id: joomgallerycategories.php 3489 2011-10-30 09:00:04Z chraneco $
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

jimport('joomla.database.tablenested');

/**
 * JoomGallery categories table class
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class TableJoomgalleryCategories extends JTableNested
{
  /** @var int Primary key */
  var $cid          = null;
  /** @var int */
  var $asset_id     = null;
  /** @var int */
  var $owner        = 0;
  /** @var string */
  var $name         = null;
  /** @var string */
  var $alias        = null;
  /** @var string */
  var $description  = null;
  /** @var int */
  var $ordering     = 0;
  /** @var string */
  var $access       = 0;
  /** @var int */
  var $published    = 0;
  /** @var int */
  var $hidden       = 0;
  /** @var int */
  var $in_hidden    = 0;
  /** @var int */
  var $thumbnail    = 0;
  /** @var int */
  var $img_position = null;
  /** @var string */
  var $catpath      = null;
  /** @var string */
  var $params       = null;
  /** @var string */
  var $metakey      = null;
  /** @var string */
  var $metadesc     = null;

  /**
   * Helper variable for checking whether
   * 'hidden' state is changed
   *
   * @var     int
   */
  private $_hidden = 0;

  /**
   * Constructor
   *
   * @param   object  $db A database connector object
   * @since   1.5.5
   */
  public function TableJoomgalleryCategories(&$db)
  {
    parent::__construct(_JOOM_TABLE_CATEGORIES, 'cid', $db);
  }

  /**
   * Overloaded load function, loads a specific row.
   *
   * @param   mixed   The primary key, if it is not specified the value of the current key is used
   * @return  boolean True on success, false otherwise
   * @since   1.5.7
   */
  public function load($oid = null)
  {
    if(!parent::load($oid))
    {
      return false;
    }

    // Store the current value of 'hidden' in order to
    // be able to detect changes of this state later on
    $this->_hidden = $this->hidden;

    return true;
  }

  /**
   * Overloaded check function, validates the row.
   * This method should always be called afore calling 'store'.
   *
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  public function check()
  {

    if(empty($this->name))
    {
      $this->setError(JText::_('COM_JOOMGALLERY_COMMON_ERROR_CATEGORY_MUST_HAVE_TITLE'));
      return false;
    }

    JFilterOutput::objectHTMLSafe($this->name);

    // Check whether state is allowed regarding parent categories
    if($this->published && $this->parent_id > 1)
    {
      $query = "SELECT
                  published
                FROM
                  "._JOOM_TABLE_CATEGORIES."
                WHERE
                  cid = ".$this->parent_id;
      $this->_db->setQuery($query);
      if(!$published = $this->_db->loadResult())
      {
        $this->published = 0;
        if($this->cid)
        {
          JError::raiseNotice('100', JText::sprintf('COM_JOOMGALLERY_COMMON_NOT_ALLOWED_TO_PUBLISH_CATEGORY', $this->cid));
        }
        else
        {
          JError::raiseNotice('100', JText::_('COM_JOOMGALLERY_COMMON_NOT_ALLOWED_TO_PUBLISH_NEW_CATEGORY'));
        }
      }
      /*if($parent->access > $this->access)
      {
        $this->access = $parent->access;
        JError::raiseNotice('100', JText::_('COM_JOOMGALLERY_COMMON_ACCESS_LEVEL_FOR_CATEGORY_NOT_ALLOWED'));
      }*/
    }

    // Trim slashes from catpath
    $this->catpath = trim($this->catpath, '/');

    if(empty($this->alias))
    {
      if(!empty($this->catpath))
      {
        $catpath  = explode('/', trim($this->catpath, '/'));
        $segments = array();
        foreach($catpath as $segment)
        {
          $segment    = str_replace('_', ' ', rtrim(rtrim($segment, '0123456789'), '_'));
          $segment    = JFilterOutput::stringURLSafe($segment);
          if($segment)
          {
            $segments[] = $segment;
          }
          else
          {
            $datenow      = & JFactory::getDate();
            // TODO $datenow->toFormat deprecated, JDATE::format() instead
            $segments[] = $datenow->toFormat('%Y-%m-%d-%H-%M-%S');
          }
        }
        $this->alias = implode('/', $segments);
      }
    }
    else
    {
      $alias = explode('/', trim($this->alias, '/'));
      $segments = array();
      foreach($alias as $segment)
      {
        $segment    = JFilterOutput::stringURLSafe($segment);
        if($segment)
        {
          $segments[] = $segment;
        }
        else
        {
          $datenow      = & JFactory::getDate();
          // TODO $datenow->toFormat deprecated, JDATE::format() instead
          $segments[] = $datenow->toFormat('%Y-%m-%d-%H-%M-%S');
        }
      }
      $this->alias = implode('/', $segments);
    }

    if(trim(str_replace('-', '', $this->alias)) == '' && !empty($this->catpath))
    {
      $datenow      = & JFactory::getDate();
      // TODO $datenow->toFormat deprecated, JDATE::format() instead
      $this->alias  = $datenow->toFormat('%Y-%m-%d-%H-%M-%S');
    }

    // clean up keywords -- eliminate extra spaces between phrases
    // and cr (\r) and lf (\n) characters from string
    if(!empty($this->metakey))
    {
      // array of characters to remove
      $bad_characters = array("\n", "\r", "\"", '<', '>');
      // remove bad characters
      $after_clean = JString::str_ireplace($bad_characters, '', $this->metakey);
      // create array using commas as delimiter
      $keys = explode(',', $after_clean);
      $clean_keys = array();
      foreach($keys as $key)
      {
        // ignore blank keywords
        if(trim($key))
        {
          $clean_keys[] = trim($key);
        }
      }
      // put array back together delimited by ', '
      $this->metakey = implode(', ', $clean_keys);
    }

    // clean up description -- eliminate quotes and <> brackets
    if(!empty($this->metadesc))
    {
      $bad_characters = array("\"", '<', '>');
      $this->metadesc = JString::str_ireplace($bad_characters, '', $this->metadesc);
    }

    return true;
  }

  /**
   * Overloaded store function
   *
   * @return  boolean True on success, false otherwise
   * @since   1.5.7
   */
  public function store()
  {
    if(!parent::store())
    {
      return false;
    }

    // Set state of all sub-categories
    // according to the settings of this category
    $cats = JoomHelper::getAllSubCategories($this->cid, false, true, true, false);

    if(count($cats))
    {
      $query = "UPDATE
                  "._JOOM_TABLE_CATEGORIES."
                SET
                  published = ".$this->published."
                WHERE
                  cid IN (".implode(',', $cats).")";
      $this->_db->setQuery($query);
      if(!$this->_db->query())
      {
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
    }

    // Set 'parent_hidden' of all sub-categories
    // according to 'hidden' of this category
    // if the 'hidden' state was changed
    if($this->_hidden != $this->hidden)
    {
      if($this->hidden == 0)
      {
        // If 'hidden' is 0 only the categories
        // which aren't hidden must be changed
        $cats = JoomHelper::getAllSubCategories($this->cid, false, true, true, true);
      }

      if(count($cats))
      {
        $query = "UPDATE
                    "._JOOM_TABLE_CATEGORIES."
                  SET
                    in_hidden = ".$this->hidden."
                  WHERE
                    cid IN (".implode(',', $cats).")";
        $this->_db->setQuery($query);
        if(!$this->_db->query())
        {
          $this->setError($this->_db->getErrorMsg());
          return false;
        }
      }
    }

    /*// Set state of all images of this category and its
    // sub-categories according to the settings of this category
    $cats[] = $this->cid;
    $query = "UPDATE
                "._JOOM_TABLE_IMAGES."
              SET
                published = ".$this->published."
              WHERE
                catid IN (".implode(',', $cats).")";
    $this->_db->setQuery($query);
    if(!$this->_db->query())
    {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }*/

    return true;
  }

  /**
   * Reorders the categories according
   * to the latest changes
   *
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  public function reorderAll()
  {
    $query = 'SELECT DISTINCT parent_id
                FROM '.$this->_db->nameQuote($this->_tbl);
    $this->_db->setQuery($query);
    $parents = $this->_db->loadResultArray();

    foreach($parents as $parent)
    {
      $this->reorder('parent_id = '.$parent);
    }
  }

  /**
   * Returns the ordering value to place a new item first in its group
   *
   * @param   string $where query WHERE clause for selecting MAX(ordering).
   * @return  int    The ordring number
   * @since   1.5.5
   */
  public function getPreviousOrder($where = '')
  {
    if(!in_array('ordering', array_keys($this->getProperties())))
    {
      $this->setError(get_class($this).' does not support ordering');
      return false;
    }

    $query = 'SELECT MIN(ordering)' .
        ' FROM ' . $this->_tbl .
        ($where ? ' WHERE '.$where : '');

    $this->_db->setQuery($query);
    $maxord = $this->_db->loadResult();

    if($this->_db->getErrorNum())
    {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }

    return $maxord - 1;
  }

  /**
   * Method to compute the name of the asset
   *
   * @return  string  The asset name
   * @since   2.0
   */
  protected function _getAssetName()
  {
    return _JOOM_OPTION.'.category.'.$this->cid;
  }

  /**
   * Method to return the title to use for the asset table
   *
   * @return  string The title of the asset
   * @since   2.0
   */
  protected function _getAssetTitle()
  {
    return $this->name;
  }

  /**
   * Get the parent asset id for the current category
   *
   * @return  int The parent asset id for the category
   * @since   2.0
   */
  protected function _getAssetParentId()
  {
    // Get the database object
    $db = $this->getDbo();

    // Check whether the category has a parent category
    if($this->parent_id > 1)
    {
      // Build the query to get the asset id for the parent category
      $query  = $db->getQuery(true);
      $query->select('asset_id');
      $query->from(_JOOM_TABLE_CATEGORIES);
      $query->where('cid = '.(int) $this->parent_id);

      // Get the asset id from the database
      $db->setQuery($query);
      if($result = $db->loadResult())
      {
        return $result;
      }
    }

    // Build the query to get the asset id of the component asset
    $query  = $db->getQuery(true);
    $query->select('id');
    $query->from('#__assets');
    $query->where('name = '.$db->quote(_JOOM_OPTION));

    // Get the asset id from the database.
    $db->setQuery($query);
    if($result = $db->loadResult())
    {
      return $result;
    }

    // If the parser reaches this point there was something wrong
    throw new JException(JText::_('Parent asset ID could not be found'));
  }

  /**
   * Method to recursively rebuild the whole nested set tree.
   *
   * @param   integer  $parentId  The root of the tree to rebuild.
   * @param   integer  $leftId    The left id to start with in building the tree.
   * @param   integer  $level     The level to assign to the current nodes.
   * @param   string   $path      The path to the current nodes.
   *
   * @return  integer  1 + value of root rgt on success, false on failure
   *
   * @link    http://docs.joomla.org/JTableNested/rebuild
   * @since   11.1
   */
  public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
  {
    // If no parent is provided, try to find it.
    if ($parentId === null)
    {
      // Get the root item.
      $parentId = $this->getRootId();
      if ($parentId === false) return false;

    }

    // Build the structure of the recursive query.
    if (!isset($this->_cache['rebuild.sql']))
    {
      $query  = $this->_db->getQuery(true);
      $query->select($this->_tbl_key.', alias');
      $query->from($this->_tbl);
      $query->where('parent_id = %d');
      $query->order('parent_id, lft, ordering');

      $this->_cache['rebuild.sql'] = (string) $query;
    }

    // Make a shortcut to database object.

    // Assemble the query to find all children of this node.
    $this->_db->setQuery(sprintf($this->_cache['rebuild.sql'], (int) $parentId));
    $children = $this->_db->loadObjectList();

    // The right value of this node is the left value + 1
    $rightId = $leftId + 1;

    // execute this function recursively over all children
    foreach ($children as $node)
    {
      // $rightId is the current right value, which is incremented on recursion return.
      // Increment the level for the children.
      // Add this item's alias to the path (but avoid a leading /)
      $rightId = $this->rebuild($node->{$this->_tbl_key}, $rightId, $level + 1, $path.(empty($path) ? '' : '/').$node->alias);

      // If there is an update failure, return false to break out of the recursion.
      if ($rightId === false) return false;
    }

    // We've got the left value, and now that we've processed
    // the children of this node we also know the right value.
    $query = $this->_db->getQuery(true);
    $query->update($this->_tbl);
    $query->set('lft = '. (int) $leftId);
    $query->set('rgt = '. (int) $rightId);
    $query->set('level = '.(int) $level);
    $query->where($this->_tbl_key.' = '. (int)$parentId);
    $this->_db->setQuery($query);

    // If there is an update failure, return false to break out of the recursion.
    if (!$this->_db->query())
    {
      $e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_REBUILD_FAILED', get_class($this), $this->_db->getErrorMsg()));
      $this->setError($e);
      return false;
    }

    // Return the right value of this node + 1.
    return $rightId + 1;
  }
}