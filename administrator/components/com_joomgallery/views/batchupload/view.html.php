<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/batchupload/view.html.php $
// $Id: view.html.php 3405 2011-10-14 07:07:11Z mab $
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
 * HTML View class for the batch upload view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewBatchupload extends JoomGalleryView
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
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_UPLOAD_BATCH_UPLOAD_MANAGER'));
    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();

    $script = "    function joom_checkme() {
      var form = document.adminForm;
      form.zippack.style.backgroundColor = '';
      form.catid.style.backgroundColor = '';";
    if(!$this->_config->get('jg_useorigfilename'))
    {
      $script .= "
        form.imgtitle.style.backgroundColor = '';";
    }
    $script .= "
      if (form.zippack.value == '' || form.zippack.value == null) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_ONE_FILE'));
        form.zippack.style.backgroundColor = ffwrong;
        form.zippack.focus();
        return false;
      }";
    if(!$this->_config->get('jg_useorigfilename'))
    {
      $script .= " else if (form.imgtitle.value == '' || form.imgtitle.value == null) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE'));
        form.imgtitle.style.backgroundColor = ffwrong;
        form.imgtitle.focus();
        return false;
      }";
    }
    $script .= "
      var filecounterok = true;";
    if(!$this->_config->get('jg_useorigfilename') && $this->_config->get('jg_filenamenumber'))
    {
      $script .= "
      form.filecounter.style.backgroundColor = '';
      if (form.filecounter.value != '') {
        var searchwrongchars1 = /[^0-9]/;
        if(searchwrongchars1.test(form.filecounter.value)) {
          filecounterok = false;
          alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_WRONG_VALUE'));
          form.filecounter.style.backgroundColor = ffwrong;
          form.filecounter.focus();
          return false;
        }
      }";
    }
    $script .= "
      if (form.catid.value == '0' && filecounterok) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY'));
        form.catid.style.backgroundColor = ffwrong;
        form.catid.focus();
        return false;
      } else {
        var filenamesnotok = false;";
    if($this->_config->get('jg_filenamewithjs') != 0  && $this->_config->get('jg_useorigfilename') == 0)
    {
      $script .= "
         var searchwrongchars = /[^ a-zA-Z0-9_-]/;
         if(searchwrongchars.test(form.imgtitle.value)) {
           filenamesnotok = true;
         }";
    }
    $script .= "
      }
      if(filenamesnotok) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_WRONG_FILENAME'));
        form.imgtitle.style.backgroundColor = ffwrong;
        form.imgtitle.focus();
        return false;
      } else {
        form.submit();
        return true;
      }
    }";
    $this->_doc->addScriptDeclaration($script);

    $this->_doc->addScriptDeclaration('    var ffwrong = \''.$this->_config->get('jg_wrongvaluecolor').'\';');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_ONE_FILE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_VALUE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_FILENAME');

    $lists['cats'] = JHTML::_('joomselect.categorylist', 0, 'catid', ' class="inputbox" size="1" style="width:228;"', null, '- ', null, 'joom.upload');

    $this->assignRef('lists', $lists);

    parent::display($tpl);
  }
}