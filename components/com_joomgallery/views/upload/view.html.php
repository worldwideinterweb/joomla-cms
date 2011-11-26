<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/views/upload/view.html.php $
// $Id: view.html.php 3406 2011-10-14 07:11:20Z mab $
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
 * HTML View class for the upload view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewUpload extends JoomGalleryView
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
    if(!$this->_config->get('jg_userspace'))
    {
      $msg = JText::_('ALERTNOTAUTH');

      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), $msg, 'notice');
    }

    // TODO: This check may be removed later on
    if(!$this->_user->get('id'))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_YOU_ARE_NOT_LOGGED'), 'notice');
    }

    if(!count(JoomHelper::getAuthorisedCategories('joom.upload')))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=userpanel', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_YOU_ARE_NOT_ALLOWED_TO_UPLOAD'), 'notice');
    }

    $params     = &$this->_mainframe->getParams();

    // Breadcrumbs
    if($this->_config->get('jg_completebreadcrumbs'))
    {
      $breadcrumbs  = &$this->_mainframe->getPathway();
      $breadcrumbs->addItem(JText::_('COM_JOOMGALLERY_COMMON_USER_PANEL'), 'index.php?view=userpanel');
      $breadcrumbs->addItem(JText::_('COM_JOOMGALLERY_COMMON_UPLOAD_NEW_IMAGE'));
    }

    // Header and footer
    JoomHelper::prepareParams($params);

    $pathway = null;
    if($this->_config->get('jg_showpathway'))
    {
      $pathway  = '<a href="'.JRoute::_('index.php?view=userpanel').'">'.JText::_('COM_JOOMGALLERY_COMMON_USER_PANEL').'</a>';
      $pathway .= ' &raquo; '.JText::_('COM_JOOMGALLERY_COMMON_UPLOAD_NEW_IMAGE');
    }

    $backtarget = JRoute::_('index.php?view=gallery');
    $backtext   = JText::_('COM_JOOMGALLERY_COMMON_BACK_TO_GALLERY');

    // Get number of images and hits in gallery
    $numbers  = JoomHelper::getNumberOfImgHits();

    // Load modules at position 'top'
    $modules['top'] = JoomHelper::getRenderedModules('top');
    if(count($modules['top']))
    {
      $params->set('show_top_modules', 1);
    }
    // Load modules at position 'btm'
    $modules['btm'] = JoomHelper::getRenderedModules('btm');
    if(count($modules['btm']))
    {
      $params->set('show_btm_modules', 1);
    }

    $count = &$this->get('ImageNumber');

    if($count >= $this->_config->get('jg_maxuserimage'))
    {
      $msg = JText::sprintf('COM_JOOMGALLERY_UPLOAD_OUTPUT_MAY_ADD_MAX_OF', $this->_config->get('jg_maxuserimage'));
      $this->_mainframe->redirect(JRoute::_('index.php?view=userpanel', false), $msg, 'notice');
    }

    $inputcounter   = $this->_config->get('jg_maxuserimage') - $count;
    $remainder      = $inputcounter;
    if($inputcounter > $this->_config->get('jg_maxuploadfields'))
    {
      $inputcounter = $this->_config->get('jg_maxuploadfields');
    }

    $maxfilesizekb = number_format($this->_config->get('jg_maxfilesize') / 1024, 2, ',', '.');

    $this->assignRef('count',         $count);
    $this->assignRef('remainder',     $remainder);
    $this->assignRef('maxfilesizekb', $maxfilesizekb);
    $this->assignRef('inputcounter',  $inputcounter);
    $this->_doc->addScriptDeclaration('    var jg_inputcounter = '.$inputcounter.';');

    $lists['cats'] = JHTML::_('joomselect.categorylist', 0, 'catid', null, null, '- ', null, 'joom.upload');

    $this->assignRef('params',          $params);
    $this->assignRef('lists',           $lists);
    $this->assignRef('pathway',         $pathway);
    $this->assignRef('modules',         $modules);
    $this->assignRef('backtarget',      $backtarget);
    $this->assignRef('backtext',        $backtext);
    $this->assignRef('numberofpics',    $numbers[0]);
    $this->assignRef('numberofhits',    $numbers[1]);

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

    $this->assignRef('cookieNavigator', $cookieNavigator);
    $this->assignRef('sessionname',     $sessionname);
    $this->assignRef('sessiontoken',    $sessiontoken);

    $this->_doc->addScript($this->_ambit->getScript('userpanel.js'));
    $this->_doc->addScriptDeclaration('    var jg_filenamewithjs = '.$this->_config->jg_filenamewithjs.';
    var jg_ffwrong = \''.$this->_config->get('jg_wrongvaluecolor').'\';
    var jg_useorigfilename = '.$this->_config->get('jg_useruseorigfilename').';');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_ONE_IMAGE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_ONE_FILE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_FILENAME_DOUBLE_ONE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_FILENAME_DOUBLE_TWO');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_FILENAME');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_EXTENSION');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_VALUE');

    parent::display($tpl);
  }
}