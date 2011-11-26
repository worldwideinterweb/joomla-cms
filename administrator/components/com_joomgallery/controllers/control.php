<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/controllers/control.php $
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
 * JoomGallery Control Controller
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryControllerControl extends JoomGalleryController
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

    // Set view
    if(JRequest::getCmd('view') != 'mini')
    {
      JRequest::setVar('view', 'control');
    }
  }

  /**
   * Updates a passed and dated extension
   *
   * The cURL library needs to be installed on the server for this.
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function update()
  {
    $extension  = JRequest::getCmd('extension', 0, 'get');
    $extensions = JoomExtensions::checkUpdate();

    if(!isset($extensions[$extension]['updatelink']) || !extension_loaded('curl'))
    {
      $this->setRedirect('index.php?option='._JOOM_OPTION, JText::_('COM_JOOMGALLERY_ADMENU_MSG_ERROR_FETCHING_ZIP'), 'error');
    }
    else
    {
      JoomExtensions::autoUpdate($extensions[$extension]['updatelink']);
    }

    // Tell JoomGallery to do the update check again after the update
    $mainframe = & JFactory::getApplication('administrator');
    $mainframe->setUserState('joom.update.checked', null);
  }

  /**
   * Installs a new extension for JoomGallery
   *
   * The cURL library needs to be installed on the server for this.
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function install()
  {
    $extension  = JRequest::getCmd('extension', 0, 'get');
    $extensions = JoomExtensions::getAvailableExtensions();

    if(!isset($extensions[$extension]['updatelink']) || !extension_loaded('curl'))
    {
      $this->setRedirect('index.php?option='._JOOM_OPTION, JText::_('COM_JOOMGALLERY_ADMENU_MSG_ERROR_FETCHING_ZIP'), 'error');
    }
    else
    {
      JoomExtensions::autoUpdate($extensions[$extension]['updatelink']);
    }
  }
}