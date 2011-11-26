<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/models/editimages.php $
// $Id: editimages.php 3381 2011-10-07 19:10:00Z aha $
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

jimport('joomla.form.form');

/**
 * Edit multiple images model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelEditimages extends JoomGalleryModel
{
  /**
   * Images data array
   *
   * @access  protected
   * @var     array
   */
  var $_images;

  /**
   * Retrieves the images data
   *
   * @access  public
   * @return  array   Array of objects containing the images data from the database
   * @since   1.5.5
   */
  function _buildQuery()
  {
    $query = "SELECT
                a.*,
                c.cid AS category_id,
                c.name AS category_name,
                g.title AS groupname
              FROM
                "._JOOM_TABLE_IMAGES." AS a
              LEFT JOIN
                "._JOOM_TABLE_CATEGORIES." AS c
              ON
                c.cid = a.id
              LEFT JOIN
                #__viewlevels AS g
              ON
                g.id = a.access
             ".$this->_buildWhere()."
             ".$this->_buildOrderby();

    return $query;
  }

  /**
   * Returns the 'where' part of the query for loading all selected images
   *
   * @access  protected
   * @return  string    The 'where' part of the query for loading all selected images
   * @since   1.5.5
   */
  function _buildWhere()
  {
    $cid = JRequest::getVar('cid', array(), '', 'array');

    return 'WHERE a.id IN ('.implode(',', $cid).')';
  }

  /**
   * Returns the 'order by' part of the query for loading all selected images
   *
   * @access  protected
   * @return  string    The 'order by' part of the query for loading all selected images
   * @since   1.5.5
   */
  function _buildOrderBy()
  {
    return '';
  }

  /**
   * Retrieves the data of the selected images
   *
   * @access  public
   * @return  array   Array of objects containing the images data from the database
   * @since   1.5.5
   */
  function getImages()
  {
    // Lets load the data if it doesn't already exist
    if(empty($this->_images))
    {
      $query = $this->_buildQuery();
      $this->_images = $this->_getList($query);
    }

    return $this->_images;
  }

  /**
   * Method to get the 'editimages' form
   *
   * @access  public
   * @return  mixed   A JForm object on success, false on failure
   * @since 2.0
   */
  public function getForm()
  {
    JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
    JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
    JForm::addRulePath(JPATH_COMPONENT.'/models/rules');

    $form =& JForm::getInstance(_JOOM_OPTION.'.editimages', 'editimages');
    if(empty($form))
    {
      return false;
    }

    return $form;
  }
}