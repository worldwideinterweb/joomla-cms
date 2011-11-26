<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/helpers/html/joomselect.php $
// $Id: joomselect.php 3385 2011-10-09 16:13:58Z erftralle $
/******************************************************************************\
**   JoomGallery 2                                                            **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                      **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                  **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look             **
**   at administrator/components/com_joomgallery/LICENSE.TXT                  **
\******************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * Utility class for creating HTML select lists
 *
 * @static
 * @package JoomGallery
 * @since   1.5.5
 */
class JHTMLJoomSelect
{
  /**
   * Construct HTML List of selectable categories
   *
   * @access  public
   * @param   int     $currentcat catid, current cat or parent
   * @param   string  $cname      Name of the HTML element
   * @param   string  $extra      Some extra code to add to the element
   * @param   int     $orig       A category to ignore (its sub-categories will be filtered out, too)
   * @param   string  $separator  A string with which the categories will be separated in the category paths
   * @param   string  $task       Null/filter
   * @param   string  $action     Action to check for each category
   * @param   mixed   $idtag      String to use as id tag for the select box
   * @return  string  The HTML output
   * @since   1.0.0
   */
  function categoryList($currentcat, $cname = 'catid', $extra = null, $orig = null, $separator  = '- ', $task = null, $action = 'core.create', $idtag = false)
  {
    $user           = JFactory::getUser();
    $ambit          = JoomAmbit::getInstance();
    $cats           = $ambit->getCategoryStructure();
    $options        = array();
    //$filter         = ($cname == 'parent_id' && $orig != null) ? true : false;
    $origfound      = false;
    $filtercatkeys  = array();

    $paths          = array();

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

    foreach($cats as $key => $cat)
    {
      // Check whether a certain category and it's sub-categories
      // have to be filtered out of the list
      if($orig)
      {
        if(!$origfound)
        {
          if($cat->cid == $orig)
          {
            $origfound            = true;
            $filtercatkeys[$orig] = true;
          }
        }
        else
        {
          if(isset($filtercatkeys[$cat->parent_id]))
          {
            $filtercatkeys[$key] = true;
          }
        }
      }

      if($cat->parent_id > 1)
      {
        //$paths[$key] = $paths[$cat->parent_id].$separator.$cat->name;
        $paths[$key] = $paths[$cat->parent_id] + 1;
      }
      else
      {
        $paths[$key] = 0;
      }

      if(     $task == 'filter'
          ||  $key == $currentcat
          ||  (     !isset($filtercatkeys[$key])
                &&  (     $user->authorise($action, _JOOM_OPTION.'.category.'.$key)
                      ||  (     $action2
                            &&  $cat->owner == $user->get('id')
                            &&  $user->authorise($action2, _JOOM_OPTION.'.category.'.$key)
                          )
                    )
              )
        )
      {
        // Build select option for that category
        $options[$key] = new stdClass();
        $options[$key]->cid   = $cat->cid;
        $options[$key]->path  = str_repeat($separator, $paths[$key]).$cat->name;
      }
    }

    if($task == 'filter' || $currentcat == 0 || ($action == 'core.create' && $user->authorise($action, _JOOM_OPTION)))
    {
      $rootcat = new stdClass();
      $rootcat->cid   = 0;
      $rootcat->path  = ($task == 'filter') ? JText::_('COM_JOOMGALLERY_COMMON_ALL') : '';
      array_unshift($options, $rootcat);
    }

    $count = count($options);
    if(!$count || ($count == 1 && !reset($options)->cid))
    {
      // Return a hidden field in order to avoid JavaScript errors
      return '-<input type="hidden" name="'.$cname.'" value="0" />';
    }

    // Sort the array with key pathname if more than one element
    if($count > 1)
    {
      //usort($options, array('JHTMLJoomSelect', 'sortCatArray'));
    }

    $attribs = 'class="inputbox"';
    if($extra)
    {
      // Add the default class only if no class is given by caller
      if(strpos($extra, 'class=') === false && strpos($extra, 'class =') === false)
      {
        $attribs .= ' '.$extra;
      }
      else
      {
        $attribs = $extra;
      }
    }

    $output = JHTML::_('select.genericlist', $options, $cname, $attribs, 'cid', 'path', $currentcat, $idtag);

    return $output;
  }

  /**
   * Construct HTML list of users
   *
   * @access  public
   * @param   array   $active     Array of selected users
   * @param   string  $name       Name of the HTML select list to use
   * @param   boolean $nouser     True, if 'No user' should be included on top of the list
   * @param   string  $javascript Additional code in the select list
   * @param   boolean $reg        True, if registered users should be ignored
   * @param   boolean $realname   True, if the real name of the users shall be used instead of their user names
   * @return  string  The HTML output
   * @since   1.5.5
   */
  function users($active, $name, $nouser = false, $javascript = null, $reg = false, $realname = false)
  {
    $db = & JFactory::getDBO();

    $and = '';
    if($reg)
    {
      // Does not include registered users in the list
      // TODO: Is this necessary in the gallery?
      //$and = ' AND gid > 18';
    }

    $username = 'username';
    if($realname)
    {
      // The real name should be used in the select box
      $username = 'name';
    }

    $query = 'SELECT
                id AS value,
                '.$username.' AS text
              FROM
                #__users
              WHERE
                      block = 0
                '.$and.'
              ORDER BY
                '.$username;
    $db->setQuery($query);
    if($nouser)
    {
      $users[] = JHTML::_('select.option',  '0', JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_SELECT_USER'));
      $users = array_merge($users, $db->loadObjectList());
    }
    else
    {
      $users = $db->loadObjectList();
    }

    return JHTML::_('select.genericlist', $users, $name, 'class="inputbox"'. $javascript, 'value', 'text', $active);
  }

  /**
   * Callback function for sorting an array of objects to assembled names of
   * categories with alle parent categories
   * @see categoryList() and userCategoryList()
   *
   * @access  public
   * @param   object $a Element one
   * @param   object $b Element two
   * @return  int     0 if names equal, -1 if a < b, 1 if a > b
   * @since   1.0.0
   */
  function sortCatArray($a, $b)
  {
    return strcmp($a->path, $b->path);
  }
}