<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/upload.php $
// $Id: upload.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery frontend upload model
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelUpload extends JoomGalleryModel
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
   * Returns the number of images of the current user
   *
   * @access  public   
   * @return  int     The number of images of the current user
   * @since   1.5.5  
   */
  function getImageNumber()
  {
    $this->_db->setQuery("SELECT 
                            COUNT(id)
                          FROM 
                            "._JOOM_TABLE_IMAGES."
                          WHERE 
                            owner = ".$this->_user->get('id')
                        );
    return $this->_db->loadResult();
  }
}