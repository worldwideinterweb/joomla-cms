<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/helpers/routing.php $
// $Id: routing.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery Routing Helper
 *
 * @static
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomRouting
{
  /**
   * Returns the ID of an image or a category by searching the alias
   * in the database tables.
   *
   * @access  public
   * @param   array   $segments An array of segments of the given URL
   * @return  array   Associative array of view and ID, boolean false if nothing was found
   * @since   1.5.5
   */
  function getId($segments)
  {
    $db = & JFactory::getDBO();

    $path = implode('/', $segments);
    $db->setQuery(" SELECT
                      cid
                    FROM
                      "._JOOM_TABLE_CATEGORIES."
                    WHERE
                      alias = '".str_replace(':', '-', $path)."'");
    if($result = $db->loadResult())
    {
      return array('view' => 'category', 'id' => $result);
    }

    $count = count($segments);
    $db->setQuery(" SELECT
                      id
                    FROM
                      "._JOOM_TABLE_IMAGES."
                    WHERE
                      alias = '".str_replace(':', '-', $segments[$count-1])."'");
    if($result = $db->loadResult())
    {
      return array('view' => 'detail', 'id' => $result);
    }

    return false;
  }

  /**
   * Checks an Itemid whether it is related to the gallery view.
   * If not, an Itemid which is related to the gallery view is
   * returned, if found.
   *
   * @access  public
   * @param   int         $Itemid The Itemid to check
   * @return  int/boolean Found Itemid, false if correct Itemid was not found or passed Itemid is correct
   * @since   1.5.5
   */
  function checkItemid($Itemid)
  {
    $menu     = & JSite::getMenu();
    $menuItem = &$menu->getItem($Itemid);
    if(!isset($menuItem->query['view']) || $menuItem->query['view'] == 'gallery')
    {
      return false;
    }

    $db = & JFactory::getDBO();
    $db->setQuery(" SELECT 
                      id 
                    FROM 
                      #__menu
                    WHERE 
                      link LIKE '%option=com_joomgallery&view=gallery%'
                  ");
    $Itemid = intval($db->loadResult());

    if($Itemid)
    {
      return $Itemid;
    }

    return false;
  }
}