<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/jupload/view.html.php $
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
 * HTML View class for the Java upload view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewJupload extends JoomGalleryView
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
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_UPLOAD_JAVA_UPLOAD_MANAGER'));
    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();

    $lists['cats'] = JHTML::_('joomselect.categorylist', 0, 'catid', ' class="inputbox" size="1" style="width:228;"', null, '- ', null, 'joom.upload');

    // Check the php.ini setting 'session.cookie_httponly'
    // If set and = 1 then build the parameter 'readCookieFrom Navigator=false'
    // in Applet (new since V 4.2.1c)
    // and provide the cookie with sessionname=token in parameter 'specificHeaders' 
    $cookieNavigator  = true;
    $sesscook         = @ini_get('session.cookie_httponly');
    if(!empty($sesscook) && $sesscook == 1)
    {
      $cookieNavigator    = false;
      // Get the current session
      $currentSession     = JSession::getInstance('', array());
      $sessionname        = $currentSession->getName();
      // Function getToken() delivers wrong token, so get the right one
      // from $_COOKIE array (since PHP 4.1.0)
      $sessiontoken       = $_COOKIE[$sessionname];
    }

    $this->assignRef('lists',           $lists);
    $this->assignRef('cookieNavigator', $cookieNavigator);
    $this->assignRef('sessionname',     $sessionname);
    $this->assignRef('sessiontoken',    $sessiontoken);

    parent::display($tpl);
  }
}