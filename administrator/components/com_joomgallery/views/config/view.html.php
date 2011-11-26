<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/config/view.html.php $
// $Id: view.html.php 3410 2011-10-15 09:39:38Z chraneco $
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
 * HTML View class for the configuration view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewConfig extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  function display()
  {
    // Load language files of frontend for Exif and IPTC data
    $language = & JFactory::getLanguage();
    $language->load(_JOOM_OPTION.'.exif', JPATH_SITE);
    $language->load(_JOOM_OPTION.'.iptc', JPATH_SITE);

    $display = true;
    if($this->_config->isExtended())
    {
      $config_id = JRequest::getInt('id');

      // Overwrite config object with specified one
      $this->_config = JoomConfig::getInstance($config_id);

      if(JRequest::getInt('group_id') || ($config_id && $config_id != 1))
      {
        $display = false;
      }
    }

    // Check the installation of GD
    $gdver = $this->get('GDVersion');
    // Returns version, 0 if not installed, or -1 if appears
    // to be installed but not verified
    if($gdver > 0)
    {
      $gdmsg = JText::sprintf('COM_JOOMGALLERY_CONFIG_GS_IP_GDLIB_INSTALLED', $gdver);
    }
    else
    {
      if($gdver == -1)
      {
        $gdmsg = JText::_('COM_JOOMGALLERY_CONFIG_GS_IP_GDLIB_NO_VERSION');
      }
      else
      {
        $gdmsg = JText::_('COM_JOOMGALLERY_CONFIG_GS_IP_GDLIB_NOT_INSTALLED') .
                '<a href="http://www.php.net/gd" target="_blank">http://www.php.net/gd</a>'
                . JText::_('COM_JOOMGALLERY_GD_MORE_INFO');
      }
    }

    // Check the installation of ImageMagick
    // first check if exec() has been diabled in php.ini
    if($this->get('DisabledExec'))
    {
      $immsg = JText::_('COM_JOOMGALLERY_CONFIG_GS_IP_IMAGIC_EXEC_DISABLED');
    }
    else
    {
      $imver = $this->get('IMVersion');
      // Returns version, 0 if not installed or path not properly configured
      if($imver)
      {
        $immsg = JText::_('COM_JOOMGALLERY_CONFIG_GS_IP_IMAGIC_INSTALLED') .  $imver;
      }
      else
      {
        $immsg = JText::_('COM_JOOMGALLERY_CONFIG_GS_IP_IMAGIC_NOT_INSTALLED');
      }
    }

    // Check the installation of Exif
    $exifmsg = '';
    if(!extension_loaded('exif'))
    {
      $exifmsg    = '<div style="color:#f00;font-weight:bold; text-align:center;">[' . JText::_('COM_JOOMGALLERY_CONFIG_DV_ED_NOT_INSTALLED') . ' ' . JText::_('COM_JOOMGALLERY_CONFIG_DV_ED_NO_OPTIONS') . ']</div>';
    }
    else
    {
      $exifmsg    = '<div style="color:#080; text-align:center;">[' . JText::_('COM_JOOMGALLERY_CONFIG_DV_ED_INSTALLED') . ']</div>';
      if(!function_exists('exif_read_data'))
      {
        $exifmsg = '<div style="color:#f00;font-weight:bold; text-align:center;">[' . JText::_('COM_JOOMGALLERY_CONFIG_DV_ED_INSTALLED_BUT') . ' ' . JText::_('COM_JOOMGALLERY_CONFIG_DV_ED_NO_OPTIONS') . ']</div>';
      }
    }

    // Check pathes and watermark file
    $writeable   = '<span style="color:#080;">'
      . JText::_('COM_JOOMGALLERY_CONFIG_GS_PD_DIRECTORY_WRITEABLE') .
      '</span>';
    $unwriteable = '<span style="color:#f00;">'
      . JText::_('COM_JOOMGALLERY_CONFIG_GS_PD_DIRECTORY_UNWRITEABLE') .
      '</span>';

    if(is_writeable($this->getPath('img')))
    {
      $write_pathimages = $writeable;
    }
    else
    {
      $write_pathimages = $unwriteable;
    }
    if(is_writeable($this->getPath('orig')))
    {
      $write_pathoriginalimages = $writeable;
    }
    else
    {
      $write_pathoriginalimages = $unwriteable;
    }
    if(is_writeable($this->getPath('thumb')))
    {
      $write_paththumbs = $writeable;
    }
    else
    {
      $write_paththumbs = $unwriteable;
    }
    if(is_writeable($this->getPath('ftp')))
    {
      $write_pathftpupload = $writeable;
    }
    else
    {
      $write_pathftpupload = $unwriteable;
    }
    if(is_writeable($this->getPath('temp')))
    {
      $write_pathtemp = $writeable;
    }
    else
    {
      $write_pathtemp = $unwriteable;
    }
    if(is_writeable($this->getPath('wtm')))
    {
      $write_pathwm = $writeable;
    }
    else
    {
      $write_pathwm = $unwriteable;
    }
    if(is_file($this->getPath('wtm').DS.$this->_config->get('jg_wmfile')))
    {
      $wmfilemsg = '<span style="color:#080;">'
        . JText::_('COM_JOOMGALLERY_CONFIG_GS_PD_FILE_EXIST') .
        '</span>';
    }
    else
    {
      $wmfilemsg = '<span style="color:#f00;">'
        . JText::_('COM_JOOMGALLERY_CONFIG_GS_PD_FILE_NOT_EXIST') .
        '</span>';
    }

    // Check whether CSS file (joom_settings.css) is writeable
    if(is_writeable(JPATH_ROOT.DS.'media'.DS.'joomgallery'.DS.'css'.DS.$this->_config->getStyleSheetName()))
    {
      $cssfilemsg = '<div style="color:#080; text-align:center;">['.JText::_('COM_JOOMGALLERY_CONFIG_GS_PD_CSS_CONFIGURATION_WRITEABLE').']</div>';
    }
    else
    {
      $cssfilemsg = '<div style="color:#f00;font-weight:bold; text-align:center;">['.JText::_('COM_JOOMGALLERY_CONFIG_GS_PD_CSS_CONFIGURATION_NOT_WRITEABLE').' '.JText::_('COM_JOOMGALLERY_COMMON_CHECK_PERMISSIONS').']</div>';
    }

    // Check whether additional plugins for displaying images are enabled
    $display_plugins_enabled = false;
    JPluginHelper::importPlugin('joomgallery');
    $this->_mainframe->triggerEvent('onJoomOpenImage', array(&$display_plugins_enabled));
    if($display_plugins_enabled)
    {
      $display_plugins_enabled = true;
    }

    // Exif
    require_once JPATH_COMPONENT.DS.'includes'.DS.'exifarray.php';

    $ifdotags   = explode(',', $this->_config->get('jg_ifdotags'));
    $subifdtags = explode(',', $this->_config->get('jg_subifdtags'));
    $gpstags    = explode(',', $this->_config->get('jg_gpstags'));

    $exif_definitions = array(
      1 => array ('TAG' => 'IFD0', 'JG' => $ifdotags, 'NAME' => 'jg_ifdotags[]', 'HEAD' => JText::_('COM_JOOMGALLERY_IFD0TAGS')),
      2 => array ('TAG' => 'EXIF', 'JG' => $subifdtags, 'NAME' => 'jg_subifdtags[]', 'HEAD' => JText::_('COM_JOOMGALLERY_SUBIFDTAGS')),
      3 => array ('TAG' => 'GPS',  'JG' => $gpstags,  'NAME' => 'jg_gpstags[]',  'HEAD' => JText::_('COM_JOOMGALLERY_GPSTAGS'))
    );

    // IPTC
    require_once JPATH_COMPONENT.DS.'includes'.DS.'iptcarray.php';

    $iptctags   = explode(',', $this->_config->get('jg_iptctags'));

    $iptc_definitions = array(
    1 => array ('TAG' => 'IPTC', 'JG' => $iptctags, 'NAME' => 'jg_iptctags[]', 'HEAD' => JText::_('COM_JOOMGALLERY_IPTCTAGS')),
    );

    // Include javascript for form validation, cleaning and submitting
    $this->_doc->addScript($this->_ambit->getScript('config.js'));

    JText::script('COM_JOOMGALLERY_CONFIG_GS_PD_ALERT_THUMBNAIL_PATH_SUPPORT');

    $this->assignRef('display',                   $display);
    $this->assignRef('cssfilemsg',                $cssfilemsg);
    $this->assignRef('exifmsg',                   $exifmsg);
    $this->assignRef('gdmsg',                     $gdmsg);
    $this->assignRef('immsg',                     $immsg);
    $this->assignRef('write_pathimages',          $write_pathimages);
    $this->assignRef('write_pathoriginalimages',  $write_pathoriginalimages);
    $this->assignRef('write_paththumbs',          $write_paththumbs);
    $this->assignRef('write_pathftpupload',       $write_pathftpupload);
    $this->assignRef('write_pathtemp',            $write_pathtemp);
    $this->assignRef('write_pathwm',              $write_pathwm);
    $this->assignRef('wmfilemsg',                 $wmfilemsg);
    $this->assignRef('display_plugins_enabled',   $display_plugins_enabled);
    $this->assignRef('exif_definitions',          $exif_definitions);
    $this->assignRef('exif_config_array',         $exif_config_array);
    $this->assignRef('iptc_definitions',          $iptc_definitions);
    $this->assignRef('iptc_config_array',         $iptc_config_array);

    $this->addToolbar();
    parent::display();
  }

  function addToolbar()
  {
    $title = JText::_('COM_JOOMGALLERY_CONFIG_CONFIGURATION_MANAGER');
    if($this->_config->isExtended())
    {
      $config_title = $this->get('ConfigTitle');
      if(JRequest::getInt('id') == 1)
      {
        $config_title = JText::sprintf('COM_JOOMGALLERY_CONFIGS_DEFAULT_TITLE', $config_title);
      }

      $title .= ' :: '.JText::sprintf('COM_JOOMGALLERY_CONFIG_EDIT_TITLE', $config_title);
    }

    JToolBarHelper::title($title, 'config');
    JToolbarHelper::apply('apply');
    JToolbarHelper::save('save');
    if($this->_config->isExtended())
    {
      JToolBarHelper::cancel('cancel', 'JTOOLBAR_CANCEL');
    }
    else
    {
      JToolbarHelper::divider();
      JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    }

    JToolbarHelper::spacer();
  }

  function getPath($type)
  {
    switch($type)
    {
      case 'thumb':
        $path = JPath::clean(JPATH_ROOT.DS.$this->_config->get('jg_paththumbs'));
        if(!JFolder::exists($path))
        {
          $path = JPath::clean($this->_config->get('jg_paththumbs'));
        }
        break;
      case 'img':
        $path = JPath::clean(JPATH_ROOT.DS.$this->_config->get('jg_pathimages'));
        if(!JFolder::exists($path))
        {
          $path = JPath::clean($this->_config->get('jg_pathimages'));
        }
        break;
      case 'orig':
        $path = JPath::clean(JPATH_ROOT.DS.$this->_config->get('jg_pathoriginalimages'));
        if(!JFolder::exists($path))
        {
          $path = JPath::clean($this->_config->get('jg_pathoriginalimages'));
        }
        break;
      case 'ftp':
        $path = JPath::clean(JPATH_ROOT.DS.$this->_config->get('jg_pathftpupload'));
        if(!JFolder::exists($path))
        {
          $path = JPath::clean($this->_config->get('jg_pathftpupload'));
        }
        break;
      case 'temp':
        $path = JPath::clean(JPATH_ROOT.DS.$this->_config->get('jg_pathtemp'));
        if(!JFolder::exists($path))
        {
          $path = JPath::clean($this->_config->get('jg_pathtemp'));
        }
        break;
      default:
        $path = JPath::clean(JPATH_ROOT.DS.$this->_config->get('jg_wmpath'));
        if(!JFolder::exists($path))
        {
          $path = JPath::clean($this->_config->get('jg_wmpath'));
        }
        break;
    }

    return $path;
  }
}