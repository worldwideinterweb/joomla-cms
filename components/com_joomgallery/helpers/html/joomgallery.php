<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/helpers/html/joomgallery.php $
// $Id: joomgallery.php 3451 2011-10-21 13:57:03Z erftralle $
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
 * Utility class for creating HTML output
 *
 * @static
 * @package JoomGallery
 * @since   1.5.5
 */
class JHTMLJoomGallery
{
  /**
   * Displays the approved state as a clickable button
   *
   * @access  public
   * @param   object  $row      Image object, holds image information
   * @param   int     $i        Number of the image in the current list
   * @param   string  $actionA  Description of action if image is approved
   * @param   string  $actionR  Description of action if image is rejected
   * @param   string  $altA     Alternative text for the icon if image is approved
   * @param   string  $altR     Alternative text for the icon if image is rejected
   * @param   string  $imgY     Icon if image is approved
   * @param   string  $imgX     Icon if image is rejected
   * @param   string  $prefix   Optional prefix of the task
   * @return  string  The HTML output
   * @since   1.5.5
   */
  function approved(&$row, $i, $actionA = 'Reject image', $actionR = 'Approve image', $altA = 'Approved', $altR = 'Rejected', $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix = '')
  {
    $img  = $row->approved ? $imgY : $imgX;
    $task = $row->approved ? 'reject' : 'approve';
    $alt  = $row->approved ? JText::_($altA) : JText::_($altR);
    $action = $row->approved ? JText::_($actionA) : JText::_($actionR);

    $href = '
    <a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
    <img src="images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
    ;

    return $href;
  }

  /**
   * Displays the name or user name of a category, image or comment owner
   * and may link it to the profiles of other extensions (if available).
   *
   * @access  public
   * @param   int     $userId   The ID of the user who will be displayed
   * @param   boolean $context  The context in which the user will be dispayed
   * @return  string  The user's name
   * @since   1.5.5
   */
  function displayName($userId, $context = null)
  {
    $userId = intval($userId);

    if(!$userId)
    {
      return JText::_('COM_JOOMGALLERY_COMMON_NO_DATA');
    }

    $config     = & JoomConfig::getInstance();
    $dispatcher = & JDispatcher::getInstance();

    $realname   = $config->get('jg_realname') ? true : false;

    $plugins    = $dispatcher->trigger('onJoomDisplayUser', array(&$userId, $realname, $context));

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
   * Fires onPrepareContent for a text if configured in the gallery
   *
   * @access  public
   * @param   string  $text The text to be transformed.
   * @return  string  The text after transformation.
   * @since   1.5.5
   */
  function text($text)
  {
    $config = & JoomConfig::getInstance();

    if($config->get('jg_contentpluginsenabled'))
    {
      $text = JHTML::_('content.prepare', $text);
    }

    return $text;
  }

  /**
   * Returns the HTML tag of a specified icon
   *
   * @access  public
   * @param   string  $icon       Filename of the icon
   * @param   string  $alt        Alternative text of the icon
   * @param   string  $extra      Additional HTML code in the tag
   * @param   string  $path       Path to the icon, if null the default path is used
   * @param   boolean $translate  Determines whether the text will be translated, defaults to true.
   * @return  string  The HTML output
   * @since   1.5.5
   */
  function icon($icon, $alt = 'Icon', $extra = null, $path = null, $translate = true)
  {
    if(is_null($path))
    {
      $ambit = JoomAmbit::getInstance();
      $path = $ambit->get('icon_url');
    }

    if($extra)
    {
      $extra = ' '.$extra;
    }

    if($translate)
    {
      $alt = JText::_($alt);
    }

    return '<img src="'.$path.$icon.'" alt="'.$alt.'" class="pngfile jg_icon"'.$extra.' />';
  }

  /**
   * Displays the toplist bar
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function toplistbar()
  {
    $config = JoomConfig::getInstance();
    $separator = "    -\n";

    echo JText::sprintf('COM_JOOMGALLERY_TOPLIST_TOP', $config->get('jg_toplist')); ?>:
<?php
    if($config->get('jg_showrate'))
    {
?>
    <a href="<?php echo JRoute::_('index.php?view=toplist&type=toprated'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_TOP_RATED'); ?></a>
<?php
      if($config->get('jg_showlatest') || $config->get('jg_showcom') || $config->get('jg_showmostviewed'))
      {
        echo $separator;
      }
    }
    if($config->get('jg_showlatest'))
    {
?>
    <a href="<?php echo JRoute::_('index.php?view=toplist&type=lastadded'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_ADDED'); ?></a>
<?php
      if($config->get('jg_showcom') || $config->get('jg_showmostviewed'))
      {
        echo $separator;
      }
    }
    if($config->get('jg_showcom'))
    {
?>
    <a href="<?php echo JRoute::_('index.php?view=toplist&type=lastcommented'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_COMMENTED'); ?></a>
<?php
      if($config->get('jg_showmostviewed'))
      {
        echo $separator;
      }
    }
    if($config->get('jg_showmostviewed'))
    {
?>
    <a href="<?php echo JRoute::_('index.php?view=toplist'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_MOST_VIEWED'); ?></a>
<?php
    }
  }

  /**
   * Creates the name tags
   *
   * @access  public
   * @param   array   $rows An array of name tag objects
   * @return  string  The HTML output
   * @since   1.5.5
   */
  function nametags(&$rows)
  {
    if(!count($rows))
    {
      return '';
    }

    $config = & JoomConfig::getInstance();
    $width  = $config->get('jg_nameshields_width');

    $html   = '';
    $i      = 1;
    foreach($rows as $row)
    {
      $name     = JHTMLJoomGallery::displayName($row->nuserid, 'nametag');
      $length   = strlen(trim(strip_tags($name))) * $width;

      $icon     = '';
      $html    .= '<div id="jg-nametag-'.$i.'" style="position:absolute; top:'.$row->nxvalue.'px; left:'.$row->nyvalue.'px; width:'.$length.'px; z-index:'.$row->nzindex.'" class="nameshield';
      //if($config->get('jg_nameshields_others'))
      //{
        $user = & JFactory::getUser();
        if($row->by == $user->get('id') || $row->nuserid == $user->get('id') || $user->authorise('core.manage', _JOOM_OPTION))
        {
          $html .= '" onmouseover="javascript:document.id(\'jg-nametag-remove-icon-'.$row->nid.'\').position({relativeTo: \'jg-nametag-'.$i.'\', position: \'upperRight\', edge: \'bottomLeft\'}).wink(3000);';
          $icon = '<a id="jg-nametag-remove-icon-'.$row->nid.'" class="jg-nametag-remove-icon jg_displaynone" href="javascript:if(confirm(\''.JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_ALERT_SURE_DELETE_OTHERS', true).'\')){ location.href=\''.JRoute::_('index.php?task=removenametag&id='.$row->npicid.'&nid='.$row->nid, false).'\';}">'
                        .JHTML::_('joomgallery.icon', 'tag_delete.png', 'COM_JOOMGALLERY_DETAIL_NAMETAGS_DELETE_OTHERS_TIPCAPTION').'</a>';
        }
      //}
      $html    .= '">';
      $html    .= $name;
      $html    .= '</div>';
      $html    .= $icon;

      $i++;
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

    $anchortag = JHTMLJoomGallery::anchor($anchortag);

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

  /**
   * Creates the path to a category which can be displayed
   *
   * @access  public
   * @param   int     $catid      The category ID
   * @param   string  $separator  The separator
   * @param   boolean $linked     True if elements shall be linked, defaults to false
   * @param   boolean $with_home  True if the home link shall be included,
   *                              defaults to false
   * @param   boolean $all        True if all categories shall be shown
   *                              defaults to false
   * @return  string  The HTML output
   * @since   1.5.5
   */
  function categoryPath(&$catid, $separator = ' &raquo; ', &$linked = false, &$with_home = false, $all = false)
  {
    static $catPaths = array();
    if(isset($catPaths[$catid]))
    {
      return $catPaths[$catid];
    }

    $path = '';
    $catid = intval($catid);

    // Get category and their parents
    $pathCats = JoomHelper::getAllParentCategories($catid, true, $all);

    // Construct the HTML
    if(count($pathCats) == 1)
    {
      // Link to category only if category published
      if($linked && $pathCats[$catid]->published)
      {
        $path = '<a href="'. JRoute::_('index.php?view=category&catid='.$catid).'" class="jg_pathitem">' . $pathCats[$catid]->name  . '</a>';
      }
      else
      {
        $path = $pathCats[$catid]->name;
      }
    }
    else
    {
      // Reindex the array with index from 0 to n
      $pathCatsidx = array_values($pathCats);
      $count = count($pathCatsidx);

      // First element
      if($linked && $pathCatsidx[0]->published)
      {
        $path = '<a href="'. JRoute::_('index.php?view=category&catid='.$pathCatsidx[0]->cid).'" class="jg_pathitem">' . $pathCatsidx[0]->name  . '</a>';
      }
      else
      {
        $path = $pathCatsidx[0]->name;
      }

      for($i=1; $i < $count; $i++)
      {
        if($linked && $pathCatsidx[$i]->published)
        {
          $path .= $separator.'<a href="'. JRoute::_('index.php?view=category&catid='.$pathCatsidx[$i]->cid).'" class="jg_pathitem">' . $pathCatsidx[$i]->name  . '</a>';
        }
        else
        {
          $path .= $separator.$pathCatsidx[$i]->name;
        }
      }
    }

    if($with_home)
    {
      $home = '<a href="'. JRoute::_('index.php?view=gallery') . '" class="jg_pathitem">' . JText::_('COM_JOOMGALLERY_COMMON_HOME') . '</a>';
      $path = $home . $separator . $path . ' ';
    }
    $catPaths[$catid] = $path;
    return $path;
  }

  /**
   * Creates a JavaScript tree with all sub-categories of a category
   *
   * @access  public
   * @param   int     $rootcatid  The category ID
   * @param   string  $align      Alignment of the tree
   * @return  void
   * @since   1.5.0
   */
  function categoryTree($rootcatid, $align)
  {
    $ambit      = JoomAmbit::getInstance();
    $config     = & JoomConfig::getInstance();
    $user       = & JFactory::getUser();
    $categories = $ambit->getCategoryStructure(true);

    // Check access rights settings
    $filter_cats        = false;
    $restricted_hint    = false;
    $restricted_cats    = false;
    $root_access        = false;
    if(!$config->get('jg_showrestrictedhint') && !$config->get('jg_showrestrictedcats'))
    {
      $filter_cats = true;
    }
    else
    {
      if($config->get('jg_showrestrictedhint'))
      {
        $restricted_hint = true;
      }
      if($config->get('jg_showrestrictedcats'))
      {
        $restricted_cats = true;
      }
    }

    // Array to hold the relevant sub-category objects
    $subcategories = array();
    // Array to hold the valid parent categories
    $validParentCats = array();
    $validParentCats[$rootcatid] = true;

    // Get all relevant subcategories
    $keys = array_keys($categories);
    $startindex = array_search($rootcatid, $keys);
    if($startindex !== false)
    {
      $stopindex     = count($categories);
      $root_access   = in_array($categories[$rootcatid]->access, $user->getAuthorisedViewLevels());

      for($j = $startindex + 1; $j < $stopindex; $j++)
      {
        $i = $keys[$j];
        $parentcat = $categories[$i]->parent_id;
        if(isset($validParentCats[$parentcat]))
        {
          // Hide empty categories
          $empty = false;
          if($config->get('jg_hideemptycats'))
          {
            $subcatids = JoomHelper::getAllSubCategories($i, true, ($config->get('jg_hideemptycats') == 1));
            // If 'jg_hideemptycats' is set to 1 the root category will always be in $subcatids, so check check whether there are images in it
            if(   !count($subcatids)
              ||  (count($subcatids) == 1 && $config->get('jg_hideemptycats') == 1 && !$categories[$i]->piccount)
              )
            {
              $empty  = true;
            }
          }

          if(    $categories[$i]->published
             && ($filter_cats == false || in_array($categories[$i]->access, $user->getAuthorisedViewLevels()))
             && !$categories[$i]->hidden
             && (!$config->get('jg_hideemptycats') || !$empty)
            )
          {
            $validParentCats[$i]  = true;
            $subcategories[$i]    = $categories[$i];
            if(
                (     $parentcat == $rootcatid
                  &&  !$root_access
                )
              ||
                (     $parentcat != $rootcatid
                  &&
                      !$subcategories[$categories[$i]->parent_id]->access
                )
              || !in_array($categories[$i]->access, $user->getAuthorisedViewLevels())
              )
            {
              $subcategories[$i]->access = false;
            }
          }
        }
        else
        {
          if($parentcat == 0)
          {
            // Branch has been processed completely
            break;
          }
        }
      }
    }

    // Show the treeview
    $count = count($subcategories);
    if(!$count)
    {
      return;
    }

    if($config->get('jg_showcatthumb') == 0 || $config->get('jg_showcatthumb') == 1)
    {
      switch($config->get('jg_ctalign'))
      {
        case 2:
          $align = 'jg_element_txt_r';
          break;
        case 3:
          $align = 'jg_element_txt_c';
          break;
        default:
          break;
      }
    }

    if($align == 'jg_element_txt' || $align == 'jg_element_txt_l')
    {
?>
          <div class="jg_treeview_l">
<?php
    }
    elseif($align == 'jg_element_txt_r')
    {
?>
          <div class="jg_treeview_r">
<?php
    }
    else
    {
?>
          <div class="jg_treeview_c">
<?php
    }
            // Debug
            // echo "ctalign=".$ctalign;
?>
            <table>
              <tr>
                <td>
                  <script type="text/javascript" language="javascript">
                  <!--
                  // Create new dTree object
                  var jg_TreeView<?php echo $rootcatid;?> = new jg_dTree( <?php echo "'"."jg_TreeView".$rootcatid."'"; ?>,
                                                                          <?php echo "'".$ambit->getScript('dTree/img/')."'"; ?>);
                  // dTree configuration
                  jg_TreeView<?php echo $rootcatid;?>.config.useCookies = true;
                  jg_TreeView<?php echo $rootcatid;?>.config.inOrder = true;
                  jg_TreeView<?php echo $rootcatid;?>.config.useSelection = false;
                  // Add root node
                  jg_TreeView<?php echo $rootcatid;?>.add( 0, -1, ' ', <?php echo "'".JRoute::_( 'index.php?view=gallery'.$rootcatid)."'"; ?>, false);
                  // Add node to hold all subcategories
                  jg_TreeView<?php echo $rootcatid;?>.add( <?php echo $rootcatid; ?>, 0, <?php echo "'".JText::_('COM_JOOMGALLERY_COMMON_SUBCATEGORIES')."(".$count.")"."'";?>,
                                                           <?php echo $root_access ? "'".JRoute::_('index.php?view=category&catid='.$rootcatid)."'" : "''"; ?>,
                                                           <?php echo $root_access ? 'false' :'true'; ?> );
<?php
    foreach($subcategories as $category)
    {
      // Create sub-category name and sub-category link
      if($filter_cats == false || $category->access)
      {
        if($category->access)
        {
          $cat_name = addslashes(trim($category->name));
          $cat_link = JRoute::_('index.php?view=category&catid='.$category->cid);
        }
        else
        {
          $cat_name = ($restricted_cats == true ? addslashes(trim($category->name)) : JText::_('COM_JOOMGALLERY_COMMON_NO_ACCESS'));
          $cat_link = '';
        }
      }
      if($restricted_hint == true)
      {
        if(!$category->access)
        {
          $cat_name .= '<span class="jg_rm">'.self::icon('group_key.png', 'COM_JOOMGALLERY_COMMON_TIP_YOU_NOT_ACCESS_THIS_CATEGORY').'</span>';
        }
      }
      if($config->get('jg_showcatasnew'))
      {
        $isnew = JoomHelper::checkNewCatg($category->cid);
      }
      else
      {
        $isnew = '';
      }
      $cat_name .= $isnew;

      // Add node
      if($category->parent_id == $rootcatid)
      {
?>
                  jg_TreeView<?php echo $rootcatid;?>.add(<?php echo $category->cid;?>,
                                                          <?php echo $rootcatid;?>,
                                                          <?php echo "'".$cat_name."'";?>,
                                                          <?php echo "'".$cat_link."'"; ?>,
                                                          <?php echo $category->access ? 'false' :'true'; ?>
                                                          );
<?php
      }
      else
      {
?>
                  jg_TreeView<?php echo $rootcatid;?>.add(<?php echo $category->cid;?>,
                                                          <?php echo $category->parent_id;?>,
                                                          <?php echo "'".$cat_name."'";?>,
                                                          <?php echo "'".$cat_link."'"; ?>,
                                                          <?php echo $category->access ? 'false' :'true'; ?>
                                                          );
<?php
      }
    }
?>
                  document.write(jg_TreeView<?php echo $rootcatid;?>);
                  -->
                  </script>
                </td>
              </tr>
            </table>
          </div>
<?php
  }

  /**
   * Returns the string of an anchor for a URL if using anchors is enabled
   *
   * @access  public
   * @param   string  $name Name of the anchor
   * @return  string  The string of the anchor
   * @since   1.5.5
   */
  function anchor($name = 'joomimg')
  {
    $config   = & JoomConfig::getInstance();

    $anchor = '';
    if($name && $config->get('jg_anchors'))
    {
      $anchor = '#'.$name;
    }

    return $anchor;
  }

  /**
   * Returns the HTML output of a tooltip if showing tooltips is enabled
   *
   * @access  public
   * @param   string  $text       The text of the tooltip
   * @param   string  $title      The title of the tooltip
   * @param   boolean $addclass   True, if the class attribute shall be added and false if it's already there
   * @param   boolean $translate  True, if the text and the title shall be translated
   * @param   string  $class      The name of the class used by Mootools to detect the tooltips
   * @return  string  The HTML output created
   * @since   1.5.5
   */
  function tip($text = 'Tooltip', $title = null, $addclass = false, $translate = true, $class = 'hasHint')
  {
    $config   = & JoomConfig::getInstance();

    $html = '';
    if($config->get('jg_tooltips'))
    {
      static $loaded = false;

      if(!$loaded)
      {
        $params = array();
        if($config->get('jg_tooltips') == 2)
        {
          $params['className'] = 'jg-tooltip-wrap';
        }

        JHTML::_('behavior.tooltip', '.'.$class, $params);
        $loaded = true;
      }

      if($translate)
      {
        $text = JText::_($text);
      }

      if($title)
      {
        if($translate)
        {
          $title = JText::_($title);
        }

        $text = $title.'::'.$text;
      }

      if($addclass)
      {
        $html = ' class="'.$class.'" title="'.$text.'"';
      }
      else
      {
        $html = ' '.$class.'" title="'.$text;
      }
    }

    return $html;
  }

  /**
   * Creates invisible links to images in order that
   * the popup boxes recognize them
   *
   * @access  public
   * @param   array   $rows   An array of image objects to use
   * @param   int     $start  Index of the first image to use
   * @param   int     $end    Index of the last image to use, if null we will use every image from $start to end
   * @return  string  The HTML output
   * @since   1.5.5
   */
  function popup(&$rows, $start = 0, $end = null)
  {
    $config   = & JoomConfig::getInstance();
    $ambit    = & JoomAmbit::getInstance();
    $user     = & JFactory::getUser();
    $view     = JRequest::getCmd('view');

    $html = '';

    if( ($view == 'category' && $config->get('jg_detailpic_open') > 4
         && (    $config->get('jg_showdetailpage') == 1
             || ($config->get('jg_showdetailpage') == 0 && $user->get('id'))
            )
        )
      ||
        (     $view == 'detail'
          &&  (   ($config->get('jg_bigpic') == 1 && $user->get('id'))
                || ($config->get('jg_bigpic_unreg') == 1 && !$user->get('id'))
              )
          && $config->get('jg_bigpic_open') > 4
        )
      )
    {
      if(is_null($end))
      {
        $rows = array_slice($rows, (int)$start);
      }
      else
      {
        $rows = array_slice($rows, (int)$start, (int)$end);
      }

      $html = '  <div class="jg_displaynone">';

      foreach($rows as $row)
      {
        if(  ($view == 'detail' && is_file($ambit->getImg('orig_path', $row)))
           || $view == 'category'
          )
        {
          if($view == 'detail')
          {
            $type = $config->get('jg_bigpic_open');
          }
          else
          {
            $type = $config->get('jg_detailpic_open');
          }
          $link = JHTMLJoomGallery::openImage($type, $row);

          // Set the title attribute in a tag with title and/or description of image
          // if a box is activated
          if($type > 1)
          {
            $atagtitle = JHTML::_('joomgallery.getTitleforATag', $row);
          }
          else
          {
            $atagtitle = 'title="'.$row->imgtitle.'"';
          }

          $html .= '
      <a href="'.$link.'" '.$atagtitle.'>'.$row->id.'</a>';
        }
      }
      $html .= '
    </div>';
    }

    return $html;
  }

  /**
   * Returns the title attribute of HTML a tag
   *
   * @access  public
   * @param   object   $image  The object which holds the image data
   * @return  string   The title attribute of HTML a Tag
   * @since   2.0
   */
  function getTitleforATag($image)
  {
    $config = & JoomConfig::getInstance();

    $atagtitle = '';
    $tagtitle  = '';
    $tagdesc   = '';
    if(    $config->get('jg_show_title_in_popup')
        || $config->get('jg_show_description_in_popup'))
    {
      if($config->get('jg_show_title_in_popup'))
      {
        $tagtitle = $image->imgtitle;
      }
      if(   $config->get('jg_show_description_in_popup')
         && !empty($image->imgtext))
      {
        if($config->get('jg_show_description_in_popup') == 1)
        {
          // Show description without html tag modifications
          $tagdesc = htmlspecialchars($image->imgtext);
        }
        else
        {
          // Strip html tags of description before
          $tagdesc = strip_tags($image->imgtext);
        }
      }
      if(!empty($tagtitle))
      {
        $atagtitle = 'title="'.$tagtitle;
        if(!empty($tagdesc))
        {
          $atagtitle .= ' '.$tagdesc.'"';
        }
        else
        {
          $atagtitle .= '"';
        }
      }
      else
      {
        if(!empty($atagdesc))
        {
          $atagtitle = 'title="'.$tagdesc.'"';
        }
      }
    }
    return $atagtitle;
  }

  /**
   * Returns the link to a given image, which opens the image in slimbox, for example
   *
   * @access  public
   * @param   int         $open   Use of lightbox, javascript window or DHTML container?
   * @param   int/object  $image  The id of the image or an object which holds the image data
   * @param   string      $type   The image type ('thumb', 'img', 'orig'), use 'false' for default value
   * @param   string      $group  Name of a group to group images in the popups
   * @return  string      The link to the image
   * @since   1.0.0
   */
  function openImage($open, $image, $type = false, $group = null)
  {
    static $loaded = array();

    $config = & JoomConfig::getInstance();
    $ambit  = & JoomAmbit::getInstance();
    $user   = & JFactory::getUser();

    // No detail view for guests if adjusted like that
    if(!$config->get('jg_showdetailpage') && !$user->get('id'))
    {
      return 'javascript:alert(\''.JText::_('COM_JOOMGALLERY_COMMON_ALERT_NO_DETAILVIEW_FOR_GUESTS', true).'\')';
    }

    if(!is_object($image))
    {
      $image  = $ambit->getImgObject($image);
    }

    if(!$type)
    {
      if(     $config->get('jg_detailpic_open') > 4
          &&  $config->get('jg_lightboxbigpic')
        )
      {
        $type = 'orig';
      }
      else
      {
        if(JRequest::getCmd('view') == 'detail')
        {
          $type = 'orig';
        }
        else
        {
          $type = 'img';
        }
      }
    }

    if(!$group)
    {
      $group = 'joomgallery';
    }

    $img_url  = $ambit->getImg($type.'_url',   $image);
    $img_path = $ambit->getImg($type.'_path',  $image);

    switch($open)
    {
      case 1: // New window
        $link = $img_url."\" target=\"_blank";
        break;
      case 2: // JavaScript window
        $imginfo = getimagesize($img_path);
        $link    = "javascript:joom_openjswindow('".$img_url."','".JoomHelper::fixForJS($image->imgtitle)."', '".$imginfo[0]."','".$imginfo[1]."')";

        if(!isset($loaded[2]))
        {
          $doc    = & JFactory::getDocument();
          $doc->addScript($ambit->getScript('jswindow.js'));
          $script = '    var resizeJsImage = '.$config->get('jg_resize_js_image').';
    var jg_disableclick = '.$config->get('jg_disable_rightclick_original').';';
          $doc->addScriptDeclaration($script);
          $loaded[2] = true;
        }
        break;
      case 3: // DHTML container
        $imginfo = getimagesize($img_path);
        $link    = "javascript:joom_opendhtml('".$img_url."','";

        if($config->get('jg_show_title_in_popup'))
        {
          $link .= JoomHelper::fixForJS($image->imgtitle)."','";
        }
        else
        {
          $link .= "','";
        }
        if($config->get('jg_show_description_in_popup'))
        {
          if($config->get('jg_show_description_in_popup') == 1)
          {
            $link .= JoomHelper::fixForJS($image->imgtext)."','";
          }
          else
          {
            $link .= JoomHelper::fixForJS(strip_tags($image->imgtext))."','";
          }
        }
        else
        {
          $link .= "','";
        }
        $link .= $imginfo[0]."','".$imginfo[1]."')";

        if(!isset($loaded[3]))
        {
          $doc    = & JFactory::getDocument();
          $doc->addScript($ambit->getScript('dhtml.js'));
          $script = '    var resizeJsImage = '.$config->get('jg_resize_js_image').';
    var jg_padding = '.$config->jg_openjs_padding.';
    var jg_dhtml_border = "'.$config->jg_dhtml_border.'";
    var jg_openjs_background = "'.$config->jg_openjs_background.'";
    var jg_disableclick = '.$config->jg_disable_rightclick_original.';';
          $doc->addScriptDeclaration($script);
          $loaded[3] = true;
        }
        break;
      case 4: // Modalbox
        #$imginfo = getimagesize($img_path);
        $link = $img_url.'" class="modal" rel="'./*{handler: 'iframe', size: {x: ".$imginfo[0].", y: ".$imginfo[1]."}}*/'" title="'.$image->imgtitle;

        if(!isset($loaded[4]))
        {
          JHTML::_('behavior.mootools'); // Loads mootools only, if it hasn't already been loaded
          JHTML::_('behavior.modal');
          $loaded[4] = true;
        }
        break;
      case 5: // Thickbox3
        $link = $img_url.'" rel="thickbox.'.$group;

        if(!isset($loaded[5]))
        {
          $doc = & JFactory::getDocument();
          $doc->addScript($ambit->getScript('thickbox3/js/jquery-latest.pack.js'));
          $doc->addScript($ambit->getScript('thickbox3/js/thickbox.js'));
          $doc->addStyleSheet($ambit->getScript('thickbox3/css/thickbox.css'));
          $script = '    var resizeJsImage = '.$config->get('jg_resize_js_image').';
    var joomgallery_image = "'.JText::_('COM_JOOMGALLERY_COMMON_IMAGE', true).'";
    var joomgallery_of = "'.JText::_('COM_JOOMGALLERY_POPUP_OF', true).'";
    var joomgallery_close = "'.JText::_('COM_JOOMGALLERY_POPUP_CLOSE', true).'";
    var joomgallery_prev = "'.JText::_('COM_JOOMGALLERY_POPUP_PREVIOUS', true).'";
    var joomgallery_next = "'.JText::_('COM_JOOMGALLERY_POPUP_NEXT', true).'";
    var joomgallery_press_esc = "'.JText::_('COM_JOOMGALLERY_POPUP_ESC', true).'";
    var tb_pathToImage = "'.$ambit->getScript('thickbox3/images/loadingAnimation.gif').'";';
          $doc->addScriptDeclaration($script);
          $loaded[5] = true;
        }
        break;
      case 6: // Slimbox
        $link = $img_url.'" rel="lightbox['.$group.']';

        if(!isset($loaded[6]))
        {
          $doc = & JFactory::getDocument();
          JHTML::_('behavior.mootools'); // Loads mootools only, if it hasn't already been loaded
          $doc->addScript($ambit->getScript('slimbox/js/slimbox.js'));
          $doc->addStyleSheet($ambit->getScript('slimbox/css/slimbox.css'));
          $script = '    var resizeJsImage = '.$config->get('jg_resize_js_image').';
    var resizeSpeed = '.$config->get('jg_lightbox_speed').';
    var joomgallery_image = "'.JText::_('COM_JOOMGALLERY_COMMON_IMAGE', true).'";
    var joomgallery_of = "'.JText::_('COM_JOOMGALLERY_POPUP_OF', true).'";';
          $doc->addScriptDeclaration($script);
          $loaded[6] = true;
        }
        break;
      case 12: // Plugins
        if(!isset($loaded[12]))
        {
          $loaded[12] = & JDispatcher::getInstance();
        }
        $link = '';
        $loaded[12]->trigger('onJoomOpenImage', array(&$link, $image, $img_url, $group, $type));
        break;
      default:  // Detail view
        $link = JRoute::_('index.php?view=detail&id='.$image->id);
        break;
    }

    return $link;
  }

  /**
   * Creates the HTML output to display the rating of an image
   *
   * @access  public
   * @param   object  $image          Image object holding the image data
   * @param   boolean $shortText      In case of text output return text without COM_JOOMGALLERY_COMMON_RATING_VAR
   * @param   string  $ratingclass    CSS class name of rating div in case of displaying stars
   * @param   string  $tooltipclass   CSS tooltip class of rating div in case of displaying stars
   * @return  string  The HTML output
   * @since   1.5.6
   */
  function rating($image, $shortText, $ratingclass, $tooltipclass = null)
  {
    $config     = & JoomConfig::getInstance();
    $db         = & JFactory::getDBO();
    $html       = '';
    $maxvoting  = $config->get('jg_maxvoting');

    // Standard rating output as text
    if($config->get('jg_ratingdisplaytype') == 0)
    {
      $rating = number_format((float) $image->rating, 2, JText::_('COM_JOOMGALLERY_COMMON_DECIMAL_SEPARATOR'), JText::_('COM_JOOMGALLERY_COMMON_THOUSANDS_SEPARATOR'));
      if($image->imgvotes > 0)
      {
        if($image->imgvotes == 1)
        {
          $html = $rating.' ('.$image->imgvotes.' '.  JText::_('COM_JOOMGALLERY_COMMON_ONE_VOTE') . ')';
        }
        else
        {
          $html = $rating.' ('.$image->imgvotes.' '.  JText::_('COM_JOOMGALLERY_COMMON_VOTES') . ')';
        }
      }
      else
      {
        $html = JText::_('COM_JOOMGALLERY_COMMON_NO_VOTES');
      }
      if(!$shortText)
      {
        $html = JText::sprintf('COM_JOOMGALLERY_COMMON_RATING_VAR', $html);
      }
      // Same as &nbsp; but &#160; also works in XML
      $html .= '&#160;';
    }

    // Rating output with star images
    if($config->get('jg_ratingdisplaytype') == 1)
    {
      $width = 0;
      if($config->get('jg_maxvoting') > 0 && $image->imgvotes > 0)
      {
        $width = (int) ($image->rating / (float) $config->get('jg_maxvoting') * 100.0);
      }

      if(isset($tooltipclass))
      {
        $html .= '<div class="'.$ratingclass.' '.JHTML::_('joomgallery.tip', JText::sprintf('COM_JOOMGALLERY_COMMON_RATING_TIPTEXT_VAR', $image->rating, $image->imgvotes), JText::_('COM_JOOMGALLERY_COMMON_RATING_TIPCAPTION'), false, false, $tooltipclass).'">';
      }
      else
      {
        $html .= '<div class="'.$ratingclass.' '.JHTML::_('joomgallery.tip', JText::sprintf('COM_JOOMGALLERY_COMMON_RATING_TIPTEXT_VAR', $image->rating, $image->imgvotes), JText::_('COM_JOOMGALLERY_COMMON_RATING_TIPCAPTION'), false, false).'">';
      }
      $html .= '  <div style="width:'.$width.'%"></div>';
      $html .= '</div>';
    }

    return $html;
  }

  /**
   * Creates the HTML output to display a minithumb for an image
   *
   * @access  public
   * @param   object  $img      Image object holding the image data
   * @param   string  $class    CSS class name for minithumb styling
   * @param   boolean $linked   Create a link on the minithumb
   * @param   boolean $showtip  Shows the thumbnail as tip on hoovering above minithumb
   * @return  string  The HTML output
   * @since   1.5.7
   */
  function minithumbimg($img, $class = null, $linked = true, $showtip = true)
  {
    jimport('joomla.filesystem.file');

    $ambit  = & JoomAmbit::getInstance();
    $config = & JoomConfig::getInstance();
    $html   = '';

    $thumb = $ambit->getImg('thumb_path', $img);
    if(JFile::exists($thumb))
    {
      $imginfo  = getimagesize($thumb);
      $link     = $linked ? JHTML::_('joomgallery.openImage', $config->get('jg_detailpic_open'), $img) : '';
      $url      = $ambit->getImg('thumb_url', $img);

      if($showtip)
      {
        $html .= '<span'.JHTML::_('joomgallery.tip', htmlspecialchars('<img src="'.$url.'" width="'.$imginfo[0].'" height="'.$imginfo[1].'" alt="'.$img->imgtitle.'" />', ENT_QUOTES, 'UTF-8'), null, true, false).'>';
      }
      if($linked)
      {
        // Set the title attribute in a tag with title and/or description of image
        // if a box is activated
        if($config->get('jg_detailpic_open') > 1)
        {
          $atagtitle = JHTML::_('joomgallery.getTitleforATag', $img);
        }
        else
        {
          // Set the imgtitle by default
          $atagtitle = 'title="'.$img->imgtitle.'"';
        }
        $html .= '<a '.$atagtitle.' href="'.$link.'">';
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
   * @param   boolean $linked   Create a link on the minithumb
   * @param   boolean $showtip  Shows the thumbnail as tip on hoovering above minithumb
   * @return  string  The HTML output
   * @since   1.5.7
   */
  function minithumbcat($cat, $class = null, $linked = true, $showtip = true)
  {
    $ambit  = & JoomAmbit::getInstance();
    $config = & JoomConfig::getInstance();
    $html   = '';

    if(isset($cat->thumbnail) && !empty($cat->thumbnail))
    {
      $thumb = $ambit->getImg('thumb_path', $cat->thumbnail, null, $cat->cid);

      jimport('joomla.filesystem.file');
      if(JFile::exists($thumb))
      {
        $imginfo  = getimagesize($thumb);
        $link     = $linked ? JRoute::_('index.php?view=category&catid='.$cat->cid) : '';
        $url      = $ambit->getImg('thumb_url', $cat->thumbnail, null, $cat->cid);

        if($showtip)
        {
          $html .= '<span'.JHTML::_('joomgallery.tip', htmlspecialchars('<img src="'.$url.'" width="'.$imginfo[0].'" height="'.$imginfo[1].'" alt="'.$cat->name.'" />', ENT_QUOTES, 'UTF-8'), null, true, false).'>';
        }
        if($linked)
        {
          $html .= '<a href="'.$link.'">';
        }
        $html .= '<img src="'.$url.'" alt="'.htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8').'"';
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
   * Replace bbcode tags (b/u/i/url/email) with HTML tags
   *
   * @access  public
   * @param   string  $text The text to be modified
   * @return  string  The modified text
   * @since   1.0.0
   */
  function BBDecode($text)
  {
    static $bbcode_tpl    = array();
    static $patterns      = array();
    static $replacements  = array();

    // First: If there isn't a "[" and a "]" in the message, don't bother.
    if((strpos($text, '[') === false || strpos($text, ']') === false))
    {
      return $text;
    }

    // [b] and [/b] for bolding text.
    $text = str_replace('[b]',  '<b>',  $text);
    $text = str_replace('[/b]', '</b>', $text);

    // [u] and [/u] for underlining text.
    $text = str_replace('[u]',  '<u>',  $text);
    $text = str_replace('[/u]', '</u>', $text);

    // [i] and [/i] for italicizing text.
    $text = str_replace('[i]',  '<i>',  $text);
    $text = str_replace('[/i]', '</i>', $text);

    if(!count($bbcode_tpl))
    {
      // We do URLs in several different ways..
      $bbcode_tpl['url']    = '<span class="bblink"><a href="{URL}" target="_blank">{DESCRIPTION}</a></span>';
      $bbcode_tpl['email']  = '<span class="bblink"><a href="mailto:{EMAIL}">{EMAIL}</a></span>';
      $bbcode_tpl['url1']   = str_replace('{URL}', '\\1\\2', $bbcode_tpl['url']);
      $bbcode_tpl['url1']   = str_replace('{DESCRIPTION}', '\\1\\2', $bbcode_tpl['url1']);
      $bbcode_tpl['url2']   = str_replace('{URL}', 'http://\\1', $bbcode_tpl['url']);
      $bbcode_tpl['url2']   = str_replace('{DESCRIPTION}', '\\1', $bbcode_tpl['url2']);
      $bbcode_tpl['url3']   = str_replace('{URL}', '\\1\\2', $bbcode_tpl['url']);
      $bbcode_tpl['url3']   = str_replace('{DESCRIPTION}', '\\3', $bbcode_tpl['url3']);
      $bbcode_tpl['url4']   = str_replace('{URL}', 'http://\\1', $bbcode_tpl['url']);
      $bbcode_tpl['url4']   = str_replace('{DESCRIPTION}', '\\2', $bbcode_tpl['url4']);
      $bbcode_tpl['email']  = str_replace('{EMAIL}', '\\1', $bbcode_tpl['email']);

      // [url]xxxx://www.phpbb.com[/url] code..
      $patterns[1]      = '#\[url\]([a-z]+?://){1}([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+\(\)]+)\[/url\]#si';
      $replacements[1]  = $bbcode_tpl['url1'];
      // [url]www.phpbb.com[/url] code.. (no xxxx:// prefix).
      $patterns[2]      = '#\[url\]([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+\(\)]+)\[/url\]#si';
      $replacements[2]  = $bbcode_tpl['url2'];
      // [url=xxxx://www.phpbb.com]phpBB[/url] code..
      $patterns[3]      = '#\[url=([a-z]+?://){1}([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+\(\)]+)\](.*?)\[/url\]#si';
      $replacements[3]  = $bbcode_tpl['url3'];
      // [url=www.phpbb.com]phpBB[/url] code.. (no xxxx:// prefix).
      $patterns[4]      = '#\[url=([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+\(\)]+)\](.*?)\[/url\]#si';
      $replacements[4]  = $bbcode_tpl['url4'];
      //[email]user@domain.tld[/email] code..
      $patterns[5]      = '#\[email\]([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#si';
      $replacements[5]  = $bbcode_tpl['email'];
    }

    $text = preg_replace($patterns, $replacements, $text);

    return $text;
  }
}