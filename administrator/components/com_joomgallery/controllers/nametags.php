<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/controllers/nametags.php $
// $Id: nametags.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery Nametags Controller
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryControllerNametags extends JoomGalleryController
{
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
  }

  /**
   * Removes all nametags in the gallery
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function reset()
  {
    // Delete all nametags
    $query = "DELETE FROM "._JOOM_TABLE_NAMESHIELDS;
    $this->_db->setQuery($query);

    if(!$this->_db->query())
    {
      $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=nametags'), $this->_db->getErrorMsg(), 'error');
      return;
    }

    $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=nametags'), JText::_('COM_JOOMGALLERY_MAIMAN_MSG_ALL_NAMETAGS_DELETED'));
  }

  /**
   * Synchronizes the nametags with users registered and existing images.
   *
   * Nametags of users that aren't registed any more will be deleted.
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function synchronize()
  {
    // Synchronize users-nametags-images
    $query = "DELETE
                n
              FROM
                "._JOOM_TABLE_NAMESHIELDS." AS n
              LEFT JOIN
                #__users AS u
              ON
                n.nuserid = u.id
              LEFT JOIN
                "._JOOM_TABLE_IMAGES." AS i
              ON
                n.npicid  = i.id
              WHERE
                    u.id IS NULL
                OR  i.id IS NULL";
    $this->_db->setQuery($query);

    if(!$this->_db->query())
    {
      $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=nametags'), $this->_db->getErrorMsg(), 'error');
      return;
    }

    $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=nametags'), JText::_('COM_JOOMGALLERY_MAIMAN_MSG_NAMETAGS_SYNCHRONIZED'));
  }
}