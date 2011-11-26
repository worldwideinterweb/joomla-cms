<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/helpers/helper.php $
// $Id: helper.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery Global Helper for the Backend
 *
 * @static
 * @package JoomGallery
 * @since 1.5.5
 */
class JoomHelper
{
  /**
   * Add managers to the sub-menu
   *
   * @return  void
   * @since  2.0
   */
  public static function addSubmenu()
  {
    $current_controller = JRequest::getCmd('controller', 'control');

    $controllers = array( 'categories'  => JText::_('COM_JOOMGALLERY_CATEGORY_MANAGER'),
                          'images'      => JText::_('COM_JOOMGALLERY_IMAGE_MANAGER'),
                          'comments'    => JText::_('COM_JOOMGALLERY_COMMENTS_MANAGER'),
                          'upload'      => JText::_('COM_JOOMGALLERY_IMAGE_UPLOAD'),
                          'batchupload' => JText::_('COM_JOOMGALLERY_BATCH_UPLOAD'),
                          'ftpupload'   => JText::_('COM_JOOMGALLERY_FTP_UPLOAD'),
                          'jupload'     => JText::_('COM_JOOMGALLERY_JAVA_UPLOAD'),
                          'config'      => JText::_('COM_JOOMGALLERY_CONFIGURATION_MANAGER'),
                          'cssedit'     => JText::_('COM_JOOMGALLERY_CUSTOMIZE_CSS'),
                          'migration'   => JText::_('COM_JOOMGALLERY_MIGRATION_MANAGER'),
                          'maintenance' => JText::_('COM_JOOMGALLERY_MAINTENANCE_MANAGER'),
                          'help'        => JText::_('COM_JOOMGALLERY_HELP')
                        );

    $canDo = self::getActions();

    if(!$canDo->get('joom.upload') && !count(JoomHelper::getAuthorisedCategories('joom.upload')))
    {
      unset($controllers['upload']);
      unset($controllers['batchupload']);
      unset($controllers['ftpupload']);
      unset($controllers['jupload']);
    }

    if(!$canDo->get('core.admin'))
    {
      unset($controllers['config']);
      unset($controllers['cssedit']);
      unset($controllers['maintenance']);
    }

    foreach($controllers as $controller => $title)
    {
      JSubMenuHelper::addEntry( $title,
                                'index.php?option='._JOOM_OPTION.'&controller='.$controller,
                                $controller == $current_controller
                              );
    }
  }

  /**
   * Returns a list of the actions that can be performed
   *
   * @param   string  $type The type of the content to check
   * @param   int     $id   The ID of the content (category or image)
   * @return  JObject An object holding the results of the check
   * @since   2.0
   */
  public static function getActions($type = 'component', $id = 0)
  {
    static $cache = array();

    // Create a unique key for the this pair of parameters
    $key = $type.':'.$id;

    if(isset($cache[$key]))
    {
      return $cache[$key];
    }

    $user   = JFactory::getUser();
    $result = new JObject();

    $actions = array('core.admin', 'core.manage', 'joom.upload', 'joom.upload.inown', 'core.create', 'joom.create.inown', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete');

    switch($type)
    {
      case 'category':
        $assetName = _JOOM_OPTION.'.category.'.$id;
        break;
      case 'image':
        $assetName = _JOOM_OPTION.'.image.'.$id;
        break;
      default:
        $assetName = _JOOM_OPTION;
        break;
    }

    foreach($actions as $action)
    {
      $result->set($action, $user->authorise($action, $assetName));
    }

    // Store the result for better performance
    $cache[$key] = $result;

    return $result;
  }

  /**
   * Returns a list of all categories for that a user has permission for a given action
   *
   * @param   string  $action The action to check for
   * @return  array   List of category objects for which the current can do the selected action to (empty array if none)
   * @since   2.0
   */
  public function getAuthorisedCategories($action)
  {
    $user = JFactory::getUser();
    $cats = JoomAmbit::getInstance()->getCategoryStructure();
    $allowedCategories = array();
    foreach($cats as $category)
    {
      $action2 = false;
      if($action == 'joom.upload')
      {
        $action2 = 'joom.upload.inown';
      }
      if($action == 'core.create')
      {
        $action2 = 'joom.create.inown';
      }
      if($action == 'core.edit')
      {
        $action2 = 'core.edit.own';
      }

      if(     $user->authorise($action, _JOOM_OPTION.'.category.'.$category->cid)
          ||  (     $action2
                &&  $category->owner == $user->get('id')
                &&  $user->authorise($action2, _JOOM_OPTION.'.category.'.$category->cid)
              )
        )
      {
        $allowedCategories[] = $category;
      }
    }

    return $allowedCategories;
  }

  /**
   * Returns all parent categories of a specific category
   *
   * @access  public
   * @param   int     $category The ID of the specific child category
   * @return  array   An array of parent category objects with cid,name,parent_id
   * @since   1.5.5
   */
  function getAllParentCategories($category)
  {
    // Get category structure from ambit
    $ambit      = JoomAmbit::getInstance();
    $cats       = $ambit->getCategoryStructure();
    $parents    = array();

    /*if($child)
    {
      $parents[$category]->cid       = $cats[$category]->cid;
      $parents[$category]->name      = $cats[$category]->name;
      $parents[$category]->parent_id = $cats[$category]->parent_id;
      $parents[$category]->published = $cats[$category]->published;
    }*/

    if(!$category)
    {
      return $parents;
    }

    $parentcat = $cats[$category]->parent_id;
    while($parentcat != 1)
    {
      $category   = $parentcat;
      $parents[$category]->cid       = $cats[$category]->cid;
      $parents[$category]->name      = $cats[$category]->name;
      $parents[$category]->parent_id = $cats[$category]->parent_id;
      $parents[$category]->published = $cats[$category]->published;
      $parentcat  = $cats[$category]->parent_id;
    }

    // Reverse the array to get the right order
    $parents = array_reverse($parents, true);

    return $parents;
  }

  /**
   * Returns all categories and their sub-categories with published or no images
   *
   * @access  public
   * @param   int     $cat          Category ID
   * @param   boolean $rootcat      True, if $cat shall also be returned as an
   *                                element of the array
   * @param   boolean $noimgcats    True if @return shall also include categories
   *                                with no images
   * @param   boolean $all          True if all categories shall be selected, defaults to true
   * @param   boolean $nohiddencats True, if sub-categories of hidden categories should be
   *                                filtered out, defaults to false
   * @return  array   An array of found categories
   * @since   1.5.5
   */
  function getAllSubCategories($cat, $rootcat = false, $noimgcats = false, $all = true, $nohiddencats = false)
  {
    // Initialise variables
    $cat              = (int) $cat;
    $parentcats       = array();
    $parentcats[$cat] = true;
    $branchfound      = false;
    $allsubcats       = array();

    // Get category structure from ambit
    $ambit = JoomAmbit::getInstance();
    $cats  = $ambit->getCategoryStructure($all);

    $stopindex = count($cats);

    $keys = array_keys($cats);
    $startindex = array_search($cat, $keys);
    if($startindex === false)
    {
      return $allsubcats;
    }

    // Find all cats which are subcategories of cat
    $hidden = array();
    for($j = $startindex + 1; $j < $stopindex; $j++)
    {
      $i = $keys[$j];
      $parentcat = $cats[$i]->parent_id;
      if(isset($parentcats[$parentcat]))
      {
        $parentcats[$i] = true;
        $branchfound = true;

        // Don't include hidden sub-categories
        if($nohiddencats)
        {
          if($cats[$i]->hidden)
          {
            $hidden[$i] = true;
          }
          else
          {
            if(isset($hidden[$cats[$i]->parent_id]))
            {
              $hidden[$i] = true;
            }
          }
        }

        if(!isset($hidden[$i]))
        {
          if(!$noimgcats)
          {
            // Only categories with images
            if($cats[$i]->piccount > 0)
            {
              // Subcategory with images in array
              $allsubcats[] = $i;
            }
          }
          else
          {
            $allsubcats[] = $i;
          }
        }
      }
      else
      {
        if($branchfound)
        {
          // Branch has been processed completely
          break;
        }
      }
    }

    // Add rootcat
    if($rootcat)
    {
      if(!$noimgcats)
      {
        // Includes images
        if($cats[$cat]->piccount > 0)
        {
          $allsubcats[] = $cat;
        }
      }
      else
      {
        $allsubcats[] = $cat;
      }
    }

    return $allsubcats;
  }

  /**
   * Wrap text
   *
   * @param   string  $text Text to wrap
   * @param   int     $nr   Number of chars to wrap
   * @return  string  Wrapped text
   * @since   1.0.0
   */
  function processText($text, $nr = 40)
  {
    $mytext   = explode(' ', trim($text));
    $newtext  = array();
    foreach($mytext as $k => $txt)
    {
      if(strlen($txt) > $nr)
      {
        $txt  = wordwrap($txt, $nr, '- ', 1);
      }
      $newtext[]  = $txt;
    }

    return implode(' ', $newtext);
  }

  /**
   * Reads the category path from array.
   * If not set read db and add to array.
   *
   * @param   int     $catid  The ID of the category
   * @return  string  The category path
   * @since   1.0.0
   */
  function getCatPath($catid)
  {
    static $catpath = array();

    if(!isset($catpath[$catid]))
    {
      $database = & JFactory::getDBO();
      $database->setQuery(" SELECT
                              catpath
                            FROM
                              "._JOOM_TABLE_CATEGORIES."
                            WHERE
                              cid= ".$catid
                          );

      if(!$path = $database->loadResult())
      {
        $catpath[$catid] = '';
      }
      else
      {
        $catpath[$catid] = $path.'/';
      }
    }

    return $catpath[$catid];
  }

  /**
   * Resort an array of category objects to ensure, that a parent category
   * is always listed before it's child categories. The function expects a $cats
   * category list, which is already sorted by parent ascending.
   *
   * @access  public
   * @param   array   $cats         Array of category objects to resort
   * @param   array   $catssorted   Resorted category object array
   * @return  void
   * @since   1.5.5
   */
  function sortCategoryList(&$cats, &$catssorted)
  {
    // First create a two dimensional array containing the child category objects
    // for each parent category id
    $children = array();
    foreach($cats as $key => $cat)
    {
      $pcid = $cat->parent_id;
      $list = isset($children[$pcid]) ? $children[$pcid] : array();
      $list[$key] = $cat;
      $children[$pcid] = $list;
    }

    // Now resort the given $cats array with the help of the $children array
    JoomHelper::sortCategoryListRecurse(0, $children, $catssorted);
  }

  /**
   * Helper function for JoomHelper::sortCategoryList().
   *
   * @access  public
   * @param   int     $catid          Category id
   * @param   array   $children       Two dimensional array containing the child
   *                                  category objects for each parent category id
   * @param   array   $catssorted     Resorted category object array
   * @return  void
   * @since   1.5.6
   */
  function sortCategoryListRecurse($catid, &$children, &$catssorted)
  {
    if(isset($children[$catid]))
    {
      foreach($children[$catid] as $key => $cat)
      {
        $catssorted[$key] = $cat;
        JoomHelper::sortCategoryListRecurse($cat->cid, $children, $catssorted);
      }
    }
  }

  /**
   * Returns the rating clause for an SQL - query dependent on the
   * rating calculation method selected.
   *
   * @access  public
   * @param   string  $tablealias   Table alias
   * @return  string  Rating clause
   * @since   1.5.6
   */
  function getSQLRatingClause($tablealias = '')
  {
    $db                   = & JFactory::getDBO();
    $config               = & JoomConfig::getInstance();
    static $avgimgvote    = 0.0;
    static $avgimgrating  = 0.0;
    static $avgdone       = false;

    $maxvoting            = $config->get('jg_maxvoting');
    $imgvotesum           = 'imgvotesum';
    $imgvotes             = 'imgvotes';
    if($tablealias != '')
    {
      $imgvotesum = $tablealias.'.'.$imgvotesum;
      $imgvotes   = $tablealias.'.'.$imgvotes;
    }

    // Standard rating clause
    $clause = 'ROUND(LEAST(IF(imgvotes > 0, '.$imgvotesum.'/'.$imgvotes.', 0.0), '.(float)$maxvoting.'), 2)';

    // Advanced (weigthed) rating clause (Bayes)
    if($config->get('jg_ratingcalctype') == 1)
    {
      if(!$avgdone)
      {
        // Needed values for weighted rating calculation
        $db->setQuery('SELECT
                         count(*) As imgcount,
                         SUM(imgvotes) As sumimgvotes,
                         SUM(imgvotesum/imgvotes) As sumimgratings
                       FROM
                         '._JOOM_TABLE_IMAGES.'
                        WHERE
                          imgvotes > 0'
                      );
        $row = $db->loadObject();
        if($row != null)
        {
          if($row->imgcount > 0)
          {
            $avgimgvote   = round($row->sumimgvotes / $row->imgcount, 2 );
            $avgimgrating = round($row->sumimgratings / $row->imgcount, 2);
            $avgdone      = true;
          }
        }
      }
      if($avgdone)
      {
        $clause = 'ROUND(LEAST(IF(imgvotes > 0, (('.$avgimgvote.'*'.$avgimgrating.') + '.$imgvotesum.') / ('.$avgimgvote.' + '.$imgvotes.'), 0.0), '.(float)$maxvoting.'), 2)';
      }
    }

    return $clause;
  }
  /**
   * Returns the rating of an image
   *
   * @access  public
   * @param   string  $imgid   Image id to get the rating for
   * @return  float   Rating
   * @since   1.5.6
   */
  function getRating($imgid)
  {
    $db     = & JFactory::getDBO();
    $rating = 0.0;

    $db->setQuery('SELECT
                    '.JoomHelper::getSQLRatingClause().' AS rating
                  FROM
                    '._JOOM_TABLE_IMAGES.'
                  WHERE
                    id = '.$imgid
                 );
    if(($result = $db->loadResult()) != null)
    {
      $rating = $result;
    }

    return $rating;
  }
}