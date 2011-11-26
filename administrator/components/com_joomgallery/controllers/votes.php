<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/controllers/votes.php $
// $Id: votes.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery Votes Controller
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryControllerVotes extends JoomGalleryController
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
   * Resets all votes of all images in the gallery
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function reset()
  {
    // Delete all votes
    $query = "DELETE FROM "._JOOM_TABLE_VOTES;
    $this->_db->setQuery($query);

    if(!$this->_db->query())
    {
      $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=votes'), $this->_db->getErrorMsg(), 'error');
      return;
    }

    $query = "UPDATE "._JOOM_TABLE_IMAGES." SET imgvotes = 0, imgvotesum = 0";
    $this->_db->setQuery($query);

    if(!$this->_db->query())
    {
      $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=votes'), $this->_db->getErrorMsg(), 'error');
      return;
    }

    $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=votes'), JText::_('COM_JOOMGALLERY_MAIMAN_MSG_ALL_VOTES_DELETED'));
  }

  /**
   * Synchronizes the votes with users registered and exiting images.
   *
   * Votes of users that aren't registed any more will be deleted.
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function synchronize()
  {
    // Synchronize users-votes-images
    $query = "DELETE
                v
              FROM
                "._JOOM_TABLE_VOTES." AS v
              LEFT JOIN
                #__users AS u
              ON
                v.userid = u.id
              LEFT JOIN
                "._JOOM_TABLE_IMAGES." AS i
              ON
                v.picid  = i.id
              WHERE
                    v.userid != 0
                AND (   u.id IS NULL
                    OR  i.id IS NULL
                    )";
    $this->_db->setQuery($query);

    if(!$this->_db->query())
    {
      $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=votes'), $this->_db->getErrorMsg(), 'error');
      return;
    }

    $query = "UPDATE
                "._JOOM_TABLE_IMAGES." AS p
              SET
                p.imgvotes    = ( SELECT
                                    COUNT(*)
                                  FROM
                                    "._JOOM_TABLE_VOTES." as v
                                  WHERE
                                    v.picid = p.id),
                p.imgvotesum  = ( SELECT
                                    SUM(vote)
                                  FROM
                                    "._JOOM_TABLE_VOTES." as v
                                  WHERE
                                    v.picid = p.id)";
    $this->_db->setQuery($query);

    if(!$this->_db->query())
    {
      $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=votes'), $this->_db->getErrorMsg(), 'error');
      return;
    }

    $this->setRedirect($this->_ambit->getRedirectUrl('maintenance&tab=votes'), JText::_('COM_JOOMGALLERY_MAIMAN_MSG_USERVOTES_SYNCHRONIZED'));
  }
}