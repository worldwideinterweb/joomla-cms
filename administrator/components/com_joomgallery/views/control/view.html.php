<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/control/view.html.php $
// $Id: view.html.php 3378 2011-10-07 18:37:56Z aha $
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
 * HTML View class for the control panel view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewControl extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  function display($tpl = null)
  {
    $params = JComponentHelper::getParams('com_joomgallery');
    jimport('joomla.html.pane');

    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_ADMENU_ADMINMENU') , 'joom');

    $canDo = JoomHelper::getActions();
    if($canDo->get('core.admin'))
    {
      JToolBarHelper::preferences('com_joomgallery');
      JToolBarHelper::spacer();
    }

    // Get data from the model
    $items  = & $this->get('Data');

    $lang = & JFactory::getLanguage();

    $modules  = & JModuleHelper::getModules('joom_cpanel');

    if($this->_config->get('jg_checkupdate'))
    {
      $available_extensions = JoomExtensions::getAvailableExtensions();
      $params->set('url_fopen_allowed', @ini_get('allow_url_fopen'));
      $params->set('curl_loaded', extension_loaded('curl'));

      // If there weren't any available extensions found
      // loading the RSS feed wasn't successful
      if(count($available_extensions))
      {
        $installed_extensions = JoomExtensions::getInstalledExtensions();
        $this->assignRef('available_extensions',  $available_extensions);
        $this->assignRef('installed_extensions',  $installed_extensions);
        $params->set('show_available_extensions', 1);

        $dated_extensions = JoomExtensions::checkUpdate();
        if(count($dated_extensions))
        {
          $this->assignRef('dated_extensions', $dated_extensions);
          $params->set('dated_extensions', 1);
        }
        else
        {
          $params->set('dated_extensions', 0);
          $params->set('show_update_info_text', 1);
        }
      }
    }
    else
    {
      $params->set('dated_extensions', 0);
    }

    $this->assignRef('items',   $items);
    $this->assignRef('lang',    $lang);
    $this->assignRef('canDo',   $canDo);
    $this->assignRef('modules', $modules);
    $this->assignRef('params',  $params);

    parent::display($tpl);
  }
}