<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/upload/view.html.php $
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
 * HTML View class for the single upload view
 *
 * @package JoomGallery
 * @since
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
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_UPLOAD_IMAGE_UPLOAD_MANAGER'));
    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();

    $script = "    function joom_checkme() {
      var form = document.adminForm;
      form.catid.style.backgroundColor = '';
      var doublefiles = false;
      /* do field validation */";
    if(!$this->_config->get('jg_useorigfilename'))
    {
    $script .= "
      form.imgtitle.style.backgroundColor = '';
      if (form.imgtitle.value == '' || form.imgtitle.value == null) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE'));
        form.imgtitle.style.backgroundColor = ffwrong;
        form.imgtitle.focus();
        return false;
      }";
    }
    $script .= "
      if (form.catid.value == '0') {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY'));
        form.catid.style.backgroundColor = ffwrong;
        form.catid.focus();
        return false;
      }
      /* checks if files already exist */
      else {
        var zaehl = 0;
        var arenofiles = true;
        var fullfields = new Array();
        var screenshotfieldname = new Array();
        var screenshotfieldvalue = new Array();
        for(i=0;i<10;i++) {
          screenshotfieldname[i] = 'arrscreenshot['+i+']';
          screenshotfieldvalue[i] = document.getElementsByName(screenshotfieldname[i])[0].value;
          document.getElementsByName(screenshotfieldname[i])[0].style.backgroundColor='';
          if(screenshotfieldvalue[i] != '') {
            arenofiles = false;
            fullfields[zaehl] = i;
            zaehl++;
          }
        }
      }
      if(arenofiles) {
       alert(Joomla.JText._('COM_JOOMGALLERY_IMGMAN_CHOOSE_IMAGE'));
       document.getElementsByName(screenshotfieldname[0])[0].focus();
       return false;
      }
      /* check the file types .jpg,.gif or .png */
      else {
        var extensionsnotok = false;
        var searchextensiontest = new Array();
        var searchextension = new Array();
        /* However you have to define this RegExp for each item. */";
    for($i=0; $i < 10; $i++)
    {
      $script .= "
        searchextension[$i] = new RegExp('\.jpg$|\.jpeg$|\.jpe$|\.gif$|\.png$','ig');";
    }
    $script .= "
        for(i=0;i<fullfields.length;i++) {
          searchextensiontest = searchextension[i].test(screenshotfieldvalue[fullfields[i]]);
          if(searchextensiontest!=true) {
            extensionsnotok = true;
            document.getElementsByName(screenshotfieldname[fullfields[i]])[0].style.backgroundColor = ffwrong;
          }
        }
      }
      if(extensionsnotok) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_WRONG_EXTENSION'));
        document.getElementsByName(screenshotfieldname[0])[0].focus();
        return false;
      }
      else {
        var filenamesnotok = false;";
    if($this->_config->get('jg_filenamewithjs') == 0)
    {
      $script .= "
        var searchwrongchars = /[^ a-zA-Z0-9_-]/;
        var lastbackslash = new Array();
        var endoffilename = new Array();
        var filename = new Array();
        for(i=0;i<fullfields.length;i++) {
          lastbackslash[i] = screenshotfieldvalue[fullfields[i]].lastIndexOf('\\\\');
          if(lastbackslash[i]<1) {
            lastbackslash[i] = screenshotfieldvalue[fullfields[i]].lastIndexOf('/');
          }
          endoffilename[i] = screenshotfieldvalue[fullfields[i]].lastIndexOf('\\.')-screenshotfieldvalue[fullfields[i]].length;
          filename[i] = screenshotfieldvalue[fullfields[i]].slice(lastbackslash[i]+1,endoffilename[i]);
          if(searchwrongchars.test(filename[i])) {
            filenamesnotok = true;
            document.getElementsByName(screenshotfieldname[fullfields[i]])[0].style.backgroundColor = ffwrong;
          }
        }";
    }
    $script .= "
      }
      if(filenamesnotok) {
        alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_WRONG_FILENAME'));
        document.getElementsByName(screenshotfieldname[0])[0].focus();
        return false;
      }
      else if(fullfields.length>1) {
        var feld1 = new Number();
        var feld2 = new Number();
        for(i=0;i<fullfields.length;i++) {
          for(j=fullfields.length-1;j>i;j--) {
            if(screenshotfieldvalue[fullfields[i]].indexOf(screenshotfieldvalue[fullfields[j]])==0) {
              doublefiles = true;
              document.getElementsByName(screenshotfieldname[fullfields[i]])[0].style.backgroundColor = ffwrong;
              document.getElementsByName(screenshotfieldname[fullfields[j]])[0].style.backgroundColor = ffwrong;
              feld1 = i+1;
              feld2 = j+1
              alert(Joomla.JText._('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_ONE') + feld1 + Joomla.JText._('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_TWO') + feld2 + '.');
            }
          }
        }
      }
      if(doublefiles) {
        document.getElementsByName(screenshotfieldname[0])[0].focus();
        return false;
      }
      else {
        form.submit();
        return true;
      }
    }";
    $this->_doc->addScriptDeclaration($script);

    $this->_doc->addScriptDeclaration('    var ffwrong = \''.$this->_config->get('jg_wrongvaluecolor').'\';');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY');
    JText::script('COM_JOOMGALLERY_IMGMAN_CHOOSE_IMAGE');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_EXTENSION');
    JText::script('COM_JOOMGALLERY_COMMON_ALERT_WRONG_FILENAME');
    JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_ONE');
    JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_TWO');

    $lists['cats'] = JHTML::_('joomselect.categorylist', 0, 'catid', ' class="inputbox" size="1" style="width:228;"', null, '- ', null, 'joom.upload');

    $this->assignRef('lists', $lists);

    parent::display($tpl);
  }
}
