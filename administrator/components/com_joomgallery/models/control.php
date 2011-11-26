<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/models/control.php $
// $Id: control.php 3378 2011-10-07 18:37:56Z aha $
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
 * Control panel model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelControl extends JoomGalleryModel
{
  /**
   * Menu data array
   *
   * @access  protected
   * @var     array
   */
  var $_data;

  /**
   * Returns the query for loading the menu entries
   *
   * @access  protected
   * @return  string    The query to be used to retrieve the menu entries from the database
   * @since   1.5.5
   */
  function _buildQuery()
  {
    $canDo = JoomHelper::getActions();

    $query = "SELECT
                *
              FROM
                #__menu
              WHERE
                    (   link LIKE 'index.php?option=com_joomgallery&controller=images%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=categories%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=comments%'";
    if($canDo->get('joom.upload') || count(JoomHelper::getAuthorisedCategories('joom.upload')))
    {
      $query .= "
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=upload%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=batchupload%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=ftpupload%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=jupload%'";
    }
    if($canDo->get('core.admin'))
    {
      $query .= "
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=config%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=cssedit%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=maintenance%'";
    }
    $query .= "
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=migration%'
                    OR  link LIKE 'index.php?option=com_joomgallery&controller=help%')
                AND parent_id != 1
                AND menutype = 'main'
              ORDER BY
                id";

    return $query;
  }

  /**
   * Retrieves the data of the backend menu entries for JoomGallery
   *
   * @access  public
   * @return  array   An array of objects containing the data of the menu entries from the database
   * @since   1.5.5
   */
  function getData()
  {
    // Lets load the data if it doesn't already exist
    if(empty($this->_data))
    {
      $query = $this->_buildQuery();
      $this->_data = $this->_getList($query);
    }

    return $this->_data;
  }
}