<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/router.php $
// $Id: router.php 3499 2011-11-07 17:50:48Z chraneco $
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
 * Builds the SEF URL for all links in JoomGallery
 *
 * @static
 * @param   array $query  An array containing all paramters of the original URL
 * @return  array An array of the segments which will be added to the SEF URL
 * @since   1.5.5
 */
function JoomGalleryBuildRoute(&$query)
{
  $segments = array();
  $db       = & JFactory::getDBO();

  if(!defined('_JOOM_OPTION'))
  {
    require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'includes'.DS.'defines.php';
  }

  if(isset($query['view']) && $query['view'] == 'toplist')
  {
    if(isset($query['type']))
    {
      switch($query['type'])
      {
        case 'toprated':
          $segment = JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_TOP_RATED'));
          if(trim(str_replace('-', '', $segment)) == '')
          {
            $segments[] = 'top-rated';
          }
          else
          {
            $segments[] = $segment;
          }
          break;
        case 'lastadded':
          $segment = JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_ADDED'));
          if(trim(str_replace('-', '', $segment)) == '')
          {
            $segments[] = 'last-added';
          }
          else
          {
            $segments[] = $segment;
          }
          break;
        case 'lastcommented':
          $segment = JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_COMMENTED'));
          if(trim(str_replace('-', '', $segment)) == '')
          {
            $segments[] = 'last-commented';
          }
          else
          {
            $segments[] = $segment;
          }
          break;
        default:
          $segment = JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_MOST_VIEWED'));
          if(trim(str_replace('-', '', $segment)) == '')
          {
            $segments[] = 'most-viewed';
          }
          else
          {
            $segments[] = $segment;
          }
          break;
      }
    }
    else
    {
      $segment = JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_MOST_VIEWED'));
      if(trim(str_replace('-', '', $segment)) == '')
      {
        $segments[] = 'most-viewed';
      }
      else
      {
        $segments[] = $segment;
      }
    }

    unset($query['type']);
    unset($query['view']);
  }

  if(isset($query['view']) && $query['view'] == 'edit')
  {
    $segments[] = 'edit';
    $db->setQuery(" SELECT
                        alias
                      FROM
                        "._JOOM_TABLE_IMAGES."
                      WHERE
                        id = ".$query['id']);
    if(!$segment = $db->loadResult())
    {
      // Append ID of image if alias was not found?
      $segment = 'alias-not-found-'.$query['id'];
    }
    $segments[] = $segment;
    unset($query['view']);
    unset($query['id']);
  }

  if(isset($query['view']) && $query['view'] == 'editcategory')
  {
    if(isset($query['catid']))
    {
      $segments[] = 'editcategory';
      $db->setQuery(" SELECT
                        alias
                      FROM
                        "._JOOM_TABLE_CATEGORIES."
                      WHERE
                        cid = ".$query['catid']);
      if(!$segment = $db->loadResult())
      {
        // Append ID of category if alias was not found
        $segment = 'alias-not-found-'.$query['catid'];
      }
      $segments[] = $segment;
    }
    else
    {
      $segments[] = 'newcategory';
    }
    unset($query['view']);
    unset($query['catid']);
  }

  if(isset($query['view']) && $query['view'] == 'gallery')
  {
    unset($query['view']);

    JLoader::register('JoomRouting', JPATH_ROOT.DS.'components'.DS._JOOM_OPTION.DS.'helpers'.DS.'routing.php');
    if(isset($query['Itemid']) && $Itemid = JoomRouting::checkItemid($query['Itemid']))
    {
      $query['Itemid'] = $Itemid;
    }
  }
  if(isset($query['view']) && $query['view'] == 'image')
  {
    $sef_image = 0;
    //require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'helpers'.DS.'config.php';
    //JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'tables');
    //$config = JoomFactory::getConfig();
    //if($config->get('jg_image_sef'))
    if(!$sef_image)
    {
      $segments[] = 'image';
      return $segments;
    }

    unset($query['view']);
    $query['format'] = 'jpg';
    $segment = 'image-'.$query['id'];
    if(isset($query['type']))
    {
      $segment .= '-'.$query['type'];
      unset($query['type']);
    }
    else
    {
      $segment .= '-thumb';
    }
    if(isset($query['width']) && isset($query['height']))
    {
      $segment .= '-'.$query['width'].'-'.$query['height'];
      unset($query['width']);
      unset($query['height']);

      if(isset($query['pos']))
      {
        $segment .= '-'.$query['pos'];
      }

      if(isset($query['x']))
      {
        if(!isset($query['pos']))
        {
          $segment .= '-0';
        }

        $segment .= '-'.$query['x'];
      }

      if(isset($query['y']))
      {
        if(!isset($query['pos']))
        {
          $segment .= '-0';
        }
        else
        {
          unset($query['pos']);
        }

        if(!isset($query['x']))
        {
          $segment .= '-0';
        }
        else
        {
          unset($query['x']);
        }

        $segment .= '-'.$query['y'];

        unset($query['y']);
      }
      else
      {
        if(isset($query['pos']))
        {
          unset($query['pos']);
        }
        if(isset($query['x']))
        {
          unset($query['x']);
        }
      }
    }

    $segments[] = $segment;

    if($sef_image == 1)
    {
      unset($query['id']);

      return $segments;
    }

    //if($config->get('jg_image_sef') == 2)
    //{
      $db->setQuery('SELECT alias FROM '._JOOM_TABLE_IMAGES.' WHERE id = '.(int) $query['id']);
      if($segment = $db->loadResult())
      {
        $segments[] = $segment;
      }
    //}

    unset($query['id']);
  }
  if(isset($query['view']) && $query['view'] == 'mini')
  {
    $segments[] = 'mini';
    unset($query['view']);
  }
  if(isset($query['view']) && $query['view'] == 'search')
  {
    $segments[] = 'search';
    unset($query['view']);
  }
  if(isset($query['view']) && $query['view'] == 'upload')
  {
    $segments[] = 'upload';
    unset($query['view']);
  }
  if(isset($query['view']) && $query['view'] == 'usercategories')
  {
    $segments[] = 'usercategories';
    unset($query['view']);
  }
  if(isset($query['view']) && $query['view'] == 'userpanel')
  {
    $segments[] = 'userpanel';
    unset($query['view']);
  }

  if(isset($query['view']) && $query['view'] == 'favourites')
  {
    $segments[] = 'favourites';

    unset($query['view']);

    if(isset($query['layout']))
    {
      if($query['layout'] == 'default')
      {
        unset($query['layout']);
      }
    }
  }

  if(isset($query['view']) and $query['view'] == 'category')
  {
    $db->setQuery(" SELECT
                      alias
                    FROM
                      "._JOOM_TABLE_CATEGORIES."
                    WHERE
                      cid = ".$query['catid']);
    if(!$segment = $db->loadResult())
    {
      // Append ID of category if alias was not found
      $segment = 'alias-not-found-'.$query['catid'];
    }
    $segments[] = $segment;
    unset($query['catid']);
    unset($query['view']);
  }

  if(isset($query['id']) && isset($query['view']) && $query['view'] == 'detail')
  {
    $db->setQuery(" SELECT
                      catid, alias
                    FROM
                      "._JOOM_TABLE_IMAGES."
                    WHERE
                      id = ".$query['id']);
    $result_array = $db->loadAssoc();
    $db->setQuery(" SELECT
                      alias
                    FROM
                      "._JOOM_TABLE_CATEGORIES."
                    WHERE
                      cid = ".$result_array['catid']);
    if(!$segment = $db->loadResult())
    {
      // Append ID of category if alias was not found
      $segment = 'alias-not-found-'.$query['catid'];
    }
    $segments[] = $segment;
    if(!$segment = $result_array['alias'])
    {
      // Append ID of image if alias was not found
      $segment = 'alias-not-found-'.$query['id'];
    }
    $segments[] = $segment;
    unset($query['id']);
    unset($query['view']);
  }

  if(isset($query['task']) && $query['task'] == 'savecategory')
  {
    $segments[] = 'savecategory';
    unset($query['task']);
  }

  if(isset($query['task']) && $query['task'] == 'deletecategory')
  {
    $segments[] = 'deletecategory';
    unset($query['task']);
  }

  return $segments;
}

/**
 * Analyses a SEF URL to retreive the parameters for JoomGallery
 *
 * @static
 * @param   array $segments An array containing the segments of the SEF URL
 * @return  array An array of the parameters retreived
 * @since   1.5.5
 */
function JoomGalleryParseRoute($segments)
{
  require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'includes'.DS.'defines.php');
  JLoader::register('JoomRouting', JPATH_ROOT.DS.'components'.DS._JOOM_OPTION.DS.'helpers'.DS.'routing.php');

  $vars = array();

  $language = & JFactory::getLanguage();
  $language->load('com_joomgallery');
  if(   $segments[0] == str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_TOP_RATED')))
    ||  $segments[0] == str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_ADDED')))
    ||  $segments[0] == str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_COMMENTED')))
    ||  $segments[0] == str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_MOST_VIEWED')))
    ||  $segments[0] == str_replace('-', ':', 'top-rated')
    ||  $segments[0] == str_replace('-', ':', 'last-added')
    ||  $segments[0] == str_replace('-', ':', 'last-commented')
    ||  $segments[0] == str_replace('-', ':', 'most-viewed')
    )
  {
    $vars['view'] = 'toplist';

    switch($segments[0])
    {
      case str_replace('-', ':', 'top-rated'):
      case str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_TOP_RATED'))):
        $vars['type'] = 'toprated';
        break;
      case str_replace('-', ':', 'last-added'):
      case str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_ADDED'))):
        $vars['type'] = 'lastadded';
        break;
      case str_replace('-', ':', 'last-commented'):
      case str_replace('-', ':', JFilterOutput::stringURLSafe(JText::_('COM_JOOMGALLERY_COMMON_TOPLIST_LAST_COMMENTED'))):
        $vars['type'] = 'lastcommented';
        break;
      default:
        break;
    }

    return $vars;
  }

  if($segments[0] == 'newcategory')
  {
    $vars['view'] = 'editcategory';
    return $vars;
  }

  if($segments[0] == 'editcategory')
  {
    array_shift($segments);
    if($result_array = JoomRouting::getId($segments))
    {
      $vars['catid'] = $result_array['id'];
    }
    $vars['view'] = 'editcategory';

    return $vars;
  }

  if($segments[0] == 'edit')
  {
    array_shift($segments);
    if($result_array = JoomRouting::getId($segments))
    {
      $vars['id']   = $result_array['id'];
      $vars['view'] = 'edit';
    }
    else
    {
      $vars['view'] = 'upload';
    }

    return $vars;
  }

  if($segments[0] == 'savecategory')
  {
    $vars['task'] = 'savecategory';

    return $vars;
  }

  if($segments[0] == 'deletecategory')
  {
    $vars['task'] = 'deletecategory';

    return $vars;
  }

  if(strpos($segments[0], 'image') === 0)
  {
    $vars['view'] = 'image';
    $vars['format'] = 'raw';
    $exploded = explode('-', str_replace(':', '-', $segments[0]));
    if(isset($exploded[1]))
    {
      $vars['id'] = $exploded[1];
    }
    if(isset($exploded[2]))
    {
      $vars['type'] = $exploded[2];
    }
    if(isset($exploded[3]))
    {
      $vars['width'] = $exploded[3];
    }
    if(isset($exploded[4]))
    {
      $vars['height'] = $exploded[4];
    }
    if(isset($exploded[5]))
    {
      $vars['pos'] = $exploded[5];
    }
    if(isset($exploded[6]))
    {
      $vars['x'] = $exploded[6];
    }
    if(isset($exploded[7]))
    {
      $vars['y'] = $exploded[7];
    }

    //JRequest::setVar('format', 'raw');
    //JFactory::getDocument();

    return $vars;
  }

  if($result_array = JoomRouting::getId($segments))
  {
    if($result_array['view'] == 'category')
    {
      $vars['view']   = 'category';
      $vars['catid']  = $result_array['id'];
    }
    else
    {
      $vars['view']   = 'detail';
      $vars['id']  = $result_array['id'];
    }

    return $vars;
  }

  $valid_views = array( 'downloadzip',
                        'favourites',
                        'mini',
                        'search',
                        'upload',
                        'usercategories',
                        'userpanel'
                      );
  if(in_array($segments[0], $valid_views))
  {
    $vars['view'] = $segments[0];
    return $vars;
  }

  $vars['view'] = 'gallery';

  return $vars;
}