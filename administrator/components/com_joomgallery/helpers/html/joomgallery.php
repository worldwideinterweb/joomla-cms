<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/helpers/html/joomgallery.php $
// $Id: joomgallery.php 3386 2011-10-09 16:35:01Z erftralle $
/******************************************************************************\
**   JoomGallery 2                                                            **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                      **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                  **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look             **
**   at administrator/components/com_joomgallery/LICENSE.TXT                  **
\******************************************************************************/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Utility class for creating HTML Grids
 *
 * @static
 * @package JoomGallery
 * @since   1.5.5
 */
class JHTMLJoomGallery
{
  /**
   * Displays the approved state as an clickable button
   */
  function approved( &$row, $i, $actionA = 'Reject image', $actionR = 'Approve image', $altA = 'Approved', $altR = 'Rejected', $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='')
  {
    $img    = $row->approved ? $imgY : $imgX;
    $task   = $row->approved ? 'reject' : 'approve';
    $alt    = $row->approved ? JText::_($altA) : JText::_($altR);
    $action = $row->approved ? JText::_($actionA) : JText::_($actionR);

    $href = '
    <a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
    <img src="images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
    ;

    return $href;
  }

  /**
   * Displays the type of an image or category
   */
  function type(&$row, $user_uploaded = 'COM_JOOMGALLERY_COMMON_USER_UPLOAD', $admin_uploaded = 'COM_JOOMGALLERY_COMMON_ADMIN_UPLOAD')
  {
    $ambit = & JoomAmbit::getInstance();
    if(
        (isset($row->useruploaded)
          AND $row->useruploaded
        )
        OR
        (!isset($row->useruploaded)
          AND $row->owner
        )
      )
    {
    $img    = 'users.png';
    $title  = JText::_($user_uploaded);
    }
    else
    {
    $img    = 'admin.png';
    $title  = JText::_($admin_uploaded);
    }

    $imgsrc = $ambit->getIcon($img);
    $html   = '<img src="'.$imgsrc.'" alt="'.$title.'" title="'.$title.'" />'
    ;

    return $html;
  }

  /**
   * Displays the name or user name of a category, image or comment owner
   * and may link it to the profiles of other extensions (if available).
   *
   * @param   int     $userId
   * @param   bool    $extended
   * @return  string  The user's name
   * @since   1.5.5
   */
  function displayname($userId, $extended = true)
  {
    $userId = intval($userId);

    if(!$userId)
    {
      return JText::_('COM_JOOMGALLERY_COMMON_NO_USER');
    }

    $config     = & JoomConfig::getInstance();
    $dispatcher = & JDispatcher::getInstance();

    // Enable JoomGallery plugins
    #JPluginHelper::importPlugin('joomgallery');

    $realname   = $config->get('jg_realname');

    $plugins    = $dispatcher->trigger('onJoomDisplayUser', array($userId, $realname, $extended));

    foreach($plugins as $plugin)
    {
      if($plugin)
      {
        return $plugin;
      }
    }

    $user = & JFactory::getUser($userId);

    if($realname)
    {
      $username = $user->get('name');
    }
    else
    {
      $username = $user->get('username');
    }

    return $username;
  }

  /**
   * Displays the credits
   *
   * @return  void
   * @since   1.5.5
   */
  function credits()
  {
    $ambit = & JoomAmbit::getInstance();
?>
<div class="footer" align="center">
  <p><br />
    <a href="http://www.joomgallery.net" target="_blank">
      <img src="<?php echo $ambit->getIcon('powered_by.gif'); ?>"  class="jg-poweredby" style="border:#666 solid 1px; padding:2px;display:block;clear:both;" alt="Powered by JoomGallery" />
    </a>
  </p>
  By:
  <a href="mailto:team@joomgallery.net">
    JoomGallery::ProjectTeam
  </a>
  <br />
  [Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam]
  <br />
  <?php echo 'Version '.$ambit->get('version'); ?>
</div>
<?php
  }

  /**
   * returns a select list of available accesslevels
   */
  /*function access($value = null, $name = 'access')
  {
    $db = & JFactory::getDBO();
    $db->setQuery('SELECT id, name FROM #__groups');

    $arr = array();
    $groups = $db->loadObjectList();
    foreach($groups as $group)
    {
      $arr[] = JHTML::_('select.option', $group->id, JText::_($group->name));
    }

    return JHTML::_('select.genericlist', $arr, $name, null, 'value', 'text', $value, $name);
  }*/

  /**
   * returns a select list of available usergroups
   */
  /*function usergroup($value = null, $name = 'access')
  {
    $acl    =& JFactory::getACL();
    $gtree  = $acl->get_group_children_tree(null, 'USERS', false);

    return JHTML::_('select.genericlist', $gtree, $name, null, 'value', 'text', $value, $name);
  }*/

  /**
   * Construct an indented list of items
   * @see showBackendAllowedCat in <backend>/helpers/html/joomselect.php
   * @see getCategories in <backend>/models/categories/categories.php
   *
   * @param  int     $mode      mode of output
   *                            1 = left aligned indented output
   *                            2 = hierachical output, indented per depth level
   *                                to the right
   * @param  int     $id        level of recursion depth
   * @param  string  $indent    indent chars per level
   * @param  array   $list      category objects
   * @param  array   $children  subcategory objects
   * @return array   list with indented category items
   */
  function catTreeRecurse($mode, $id, $indent = '&nbsp;&nbsp;&nbsp;', &$list, &$children)
  {
    if($mode == 1)
    {
      static $separator = '&raquo;';
    }
    else
    {
      static $pre    = '&raquo;&nbsp;';
      static $spacer = '&nbsp;&nbsp;&nbsp;';
    }

    if(isset($children[$id]))
    {
      foreach($children[$id] as $child)
      {
        if($mode == 1)
        {
          $catname   = $child->name;
        }
        else
        {
          if($child->parent_id == 0)
          {
            $catname = $child->name;
          }
          else
          {
            $catname = $pre . $child->name;
          }

        }
        $id        = $child->cid;
        $list[$id] = $child;
        $list[$id]->name = $indent . $catname;

        if($mode == 1)
        {
          $list = JHTMLJoomGallery::catTreeRecurse($mode, $id, $indent . $catname . $separator, $list, $children);
        }
        else
        {
          $list = JHTMLJoomGallery::catTreeRecurse($mode, $id, $indent . $spacer, $list, $children);
        }
      }
    }
    return $list;
  }

  /**
   * Creates the path to a category which can be displayed
   * @param  int     id of category
   * @return string  category path
   */
  function categoryPath(&$catid)
  {
    static $catPaths = array();
    if(isset($catPaths[$catid]))
    {
      return $catPaths[$catid];
    }
    $separator = ' &raquo; ';
    $path      = '';

    // Get category and their parents
    $pathCats = JoomHelper::getAllParentCategories($catid);

    // Construct the HTML
    if(count($pathCats) == 1)
    {
      $path = reset($pathCats)->name;
    }
    else
    {
      // Reindex the array with index from 0 to n
      $pathCatsidx = array_values($pathCats);
      $count = count($pathCatsidx);
      if(isset($pathCatsidx[0]))
      {
        $path = $pathCatsidx[0]->name;
      }

      for($i=1; $i < $count; $i++)
      {
        $path .= $separator.$pathCatsidx[$i]->name;
      }
    }

    $catPaths[$catid] = $path;
    return $path;
  }

  /**
   * Creates the HTML output to display a minithumb for an image
   *
   * @access  public
   * @param   object  $img            Image object holding the image data
   * @param   string  $class          CSS class name for minithumb styling
   * @param   boolean $linkattribs    Link attributes for creating a link on the minithumb, if null no link will created
   * @param   boolean $showtip        Shows the thumbnail as tip on hoovering above minithumb
   * @return  string  The HTML output
   * @since   1.5.7
   */
  function minithumbimg($img, $class = null, $linkattribs = null, $showtip = true)
  {
    jimport('joomla.filesystem.file');

    $ambit    = & JoomAmbit::getInstance();
    $config   = & JoomConfig::getInstance();
    $html     = '';
    $linked   = ($linkattribs !== null) ? true : false;

    $thumb = $ambit->getImg('thumb_path', $img);
    if(JFile::exists($thumb))
    {
      $imginfo  = getimagesize($thumb);
      $url      = $ambit->getImg('thumb_url', $img);

      if($showtip)
      {
        $html .= '<span class="hasTip" title="'.htmlspecialchars('<img src="'.$url.'" width="'.$imginfo[0].'" height="'.$imginfo[1].'" alt="'.$img->imgtitle.'" />', ENT_QUOTES, 'UTF-8').'">';
      }
      if($linked)
      {
        $html .= '<a '.$linkattribs.'">';
      }
      $html .= '<img src="'.$url.'" alt="'.htmlspecialchars($img->imgtitle, ENT_QUOTES, 'UTF-8').'"';
      if($class !== null)
      {
        $html .= ' class="'.$class.'"';
      }
      $html .= '>';
      if($linked)
      {
        $html .= '</a>';
      }
      if($showtip)
      {
        $html .= '</span>';
      }
    }
    return $html;
  }

  /**
   * Creates the HTML output to display a minithumb for a category
   *
   * @access  public
   * @param   object  $cat      Category object holding the category data
   * @param   string  $class    CSS class name for minithumb styling
   * @param   boolean $linkattribs    Link attributes for creating a link on the minithumb, if null no link will created
   * @param   boolean $showtip  Shows the thumbnail as tip on hoovering above minithumb
   * @return  string  The HTML output
   * @since   1.5.7
   */
  function minithumbcat($cat, $class = null, $linkattribs = null, $showtip = true)
  {
    $ambit  = & JoomAmbit::getInstance();
    $config = & JoomConfig::getInstance();
    $html   = '';
    $linked   = ($linkattribs !== null) ? true : false;

    if(isset($cat->thumbnail) && !empty($cat->thumbnail))
    {
      $thumb = $ambit->getImg('thumb_path', $cat->thumbnail, null, $cat->cid);

      jimport('joomla.filesystem.file');
      if(JFile::exists($thumb))
      {
        $imginfo  = getimagesize($thumb);
        $url      = $ambit->getImg('thumb_url', $cat->thumbnail, null, $cat->cid);

        // Clean category name
        $catname = str_replace('&nbsp;', '', $cat->name);
        $catname = trim(str_replace('&raquo;', '', $catname));

        if($showtip)
        {
          $html .= '<span class="hasTip" title="'.htmlspecialchars('<img src="'.$url.'" width="'.$imginfo[0].'" height="'.$imginfo[1].'" alt="'.$catname.'" />', ENT_QUOTES, 'UTF-8').'">';
        }
        if($linked)
        {
          $html .= '<a '.$linkattribs.'">';
        }
        $html .= '<img src="'.$url.'" alt="'.htmlspecialchars($catname, ENT_QUOTES, 'UTF-8').'"';
        if($class !== null)
        {
          $html .= ' class="'.$class.'"';
        }
        $html .= '>';
        if($linked)
        {
          $html .= '</a>';
        }
        if($showtip)
        {
          $html .= '</span>';
        }
      }
    }
    return $html;
  }

  /**
   * Creates the pagination in detail/category/sub-catagory view
   *
   * @access  public
   * @param   string  $url          Base URL according to view, completion in this function
   * @param   int     $pageCount    Total count of all pages
   * @param   int     $currentPage  Current page
   * @param   string  $anchortag    Anchor to append
   * @param   string  $onclick      JavaScript code to insert in every link (for using Ajax pagination for example)
   * @return  string  All completed URLs to pages
   * @since   1.5.5
   */
  function pagination($url, &$pageCount, &$currentPage, $anchortag = '', $onclick = '')
  {
    $retVal   = '';
    $ellipsis = '&hellip;';
    $workPage  = 2;

    // Onclick event
    if($onclick)
    {
      $onclick = ' onclick="'.$onclick.'"';
    }

    // Variable for current page found and assembled
    $currItemfound = false;

    // Work on left edge
    if($currentPage == 1)
    {
      $currItemfound = true;
      $retVal .= '<span class="jg_pagenav_active">1</span>&nbsp;';
      $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, 2)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' 2" class="jg_pagenav"'.sprintf($onclick, 2).'>2</a>'."\n";
    }
    else
    {
      // Current page not 1
      $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, 1)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' 1" class="jg_pagenav"'.sprintf($onclick, 1).'>1</a>'."\n";
      if($currentPage == 2)
      {
        $currItemfound = true;
        $retVal .= '&nbsp;<span class="jg_pagenav_active">2</span>';
      }
      else
      {
        $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, 2)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' 2" class="jg_pagenav"'.sprintf($onclick, 2).'>2</a>'."\n";
      }
    }
    // Range left from current page to 1 not assembled yet
    if(!$currItemfound)
    {
      // Construct pages left to current page
      // according to difference to left implement jumps
      // If difference to current page too low, output them exactly
      if($currentPage - $workPage < 6)
      {
        $workPage++;
        for ($i = $workPage; $i < $currentPage; $i++)
        {
          $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $i)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$i.'" class="jg_pagenav"'.sprintf($onclick, $i).'>'.$i.'</a>'."\n";
          $workPage++;
        }
      }
      else
      {
        // Otherwise output of remaining links evt. in steps
        // and in addition output of 2 left neighbours
        // completion of range at position 3 to (current page -3)
        $endRange = $currentPage - 3;
        $jump = ceil(($endRange - 5) / 4);
        if($jump == 0)
        {
          $jump = 1;
        }
        $workPage = $workPage + $jump;
        for($i = 1; $i < 4; $i++)
        {
          if($jump == 1)
          {
            $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
          }
          else
          {
            $retVal .= $ellipsis.'&nbsp;<a href="'.JRoute::_(sprintf($url, $workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
          }
          $workPage = $workPage + $jump;
        }
        if($workPage != ($currentPage-2))
        {
          $retVal .= $ellipsis;
        }
        // Output of 2 pages left beside current page
        $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $currentPage-2)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.($currentPage-2).'" class="jg_pagenav"'.sprintf($onclick, $currentPage-2).'>'.($currentPage-2).'</a>'."\n";
        $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $currentPage-1)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.($currentPage-1).'" class="jg_pagenav"'.sprintf($onclick, $currentPage-2).'>'.($currentPage-1).'</a>'."\n";
      }
      // Current page
      $retVal .= '&nbsp;<span class="jg_pagenav_active">'.$currentPage.'</span>&nbsp;';
      $currItemfound = true;
      $workPage = $currentPage;
    }
    // Current page found, right beside construct 2 pages
    // max to end
    if($pageCount - $workPage < 3)
    {
      $endRangecount = $pageCount - $workPage;
    }
    else
    {
      $endRangecount = 2;
    }
    $workPage++;
    for($i = 1; $i <= $endRangecount; $i++)
    {
      $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
      $workPage++;
    }
    if($workPage == $pageCount)
    {
      $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url,$workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
      return $retVal;
    }
    // All ready
    if($workPage > $pageCount)
    {
      return $retVal;
    }
    // If only 3 pages to end remain
    if($workPage < $pageCount && ($pageCount - $workPage) < 7)
    {
      for($i = $workPage; $i <= $pageCount; $i++)
      {
        $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
        $workPage++;
      }
    }
    else
    {
      // Output of remaining pages in steps
      // and in addition output of last page and the neighbour left
      // Complete the range (current page + 3) to (last page - 3)
      $startRange = $workPage;
      $endRange   = $pageCount-3;
      $jump       = ceil(($endRange - $startRange) / 4);
      $workPage   = $workPage + $jump;
      for($i = 1; $i < 4; $i++)
      {
        if($jump == 1)
        {
          $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
        }
        else
        {
          $retVal .= $ellipsis.'&nbsp;<a href="'.JRoute::_(sprintf($url, $workPage)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.$workPage.'" class="jg_pagenav"'.sprintf($onclick, $workPage).'>'.$workPage.'</a>'."\n";
        }
        $workPage  = $workPage + $jump;
      }
      $retVal .= $ellipsis;
      // Output of penultimate
      $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $pageCount - 1)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.($pageCount-1).'" class="jg_pagenav"'.sprintf($onclick, $pageCount-1).'>'.($pageCount-1).'</a>'."\n";
      // Output of last
      $retVal .= '&nbsp;<a href="'.JRoute::_(sprintf($url, $pageCount)).$anchortag.'" title="'.JText::_('COM_JOOMGALLERY_COMMON_PAGE').' '.($pageCount).'" class="jg_pagenav"'.sprintf($onclick, $pageCount).'>'.($pageCount).'</a>'."\n";
    }

    return $retVal;
  }
}