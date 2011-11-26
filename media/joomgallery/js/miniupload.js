// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/media/joomgallery/js/miniupload.js $
// $Id: miniupload.js 3405 2011-10-14 07:07:11Z mab $
/****************************************************************************************\
**   JoomGallery  2                                                                     **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

function joom_singleuploadcheck()
{
  var form = document.SingleUploadForm;
  form.imgtitle.style.backgroundColor = '';
  form.catid.style.backgroundColor = '';
  var doublefiles = false;
  // do field validation
  if (form.imgtitle.value == ''|| form.imgtitle.value == null) {
    alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE'));
    form.imgtitle.style.backgroundColor = jg_ffwrong;
    form.imgtitle.focus();
    return false;
  } else if (form.catid.value == "0") {
    alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY'));
    form.catid.style.backgroundColor = jg_ffwrong;
    form.catid.focus();
    return false;
    //Prueft ob ueberhaupt Dateien angeben wurden.
  } else {
    var zaehl = 0;
    var arenofiles = true;
    var fullfields = new Array();
    var screenshotfieldname = new Array();
    var screenshotfieldvalue = new Array();
    for(i=0;i<jg_inputcounter;i++) {
      screenshotfieldname[i] = 'arrscreenshot['+i+']';
      screenshotfieldvalue[i] = document.getElementsByName(screenshotfieldname[i])[0].value;
      document.getElementsByName(screenshotfieldname[i])[0].style.backgroundColor='';
      if(screenshotfieldvalue[i] != "") {
        arenofiles = false;
        fullfields[zaehl] = i;
        zaehl++;
      }
    }
  }
  if(arenofiles) {
    alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_PLEASE_SELECT_IMAGE'));
    document.getElementsByName(screenshotfieldname[0])[0].focus();
    document.getElementsByName(screenshotfieldname[0])[0].style.backgroundColor = jg_ffwrong;
    return false;
    //Prueft ob die Dateitypen auch .jpg,.gif und .png sind
  } else {
    var extensionsnotok = false;
    var searchextensiontest = new Array();
    var searchextension = new Array();
    //However you have to define this RegExp for each item.
    for (i=0;i<fullfields.length;i++) {
      searchextension[i] = new RegExp('\.jpg$|\.jpe$|\.jpeg$|\.gif$|\.png$','ig');
    }
    for(i=0;i<fullfields.length;i++) {
      searchextensiontest = searchextension[i].test(screenshotfieldvalue[fullfields[i]]);
      if(searchextensiontest!=true) {
        extensionsnotok = true;
        document.getElementsByName(screenshotfieldname[fullfields[i]])[0].style.backgroundColor = jg_ffwrong;
      }
    }
  }
  if(extensionsnotok) {
    alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_WRONG_EXTENSION'));
    document.getElementsByName(screenshotfieldname[0])[0].focus();
    return false;
    //Wenn eine Javascriptueberpruefung in den Configurations gewuenscht wurde wird der Dateinamen auf Sonderzeichen ueberprueft
  } else {
    var filenamesnotok = false;
    if(jg_filenamewithjs!=0) {
      var searchwrongchars = /[^ a-zA-Z0-9_-]/;
      var lastbackslash = new Array();
      var endoffilename = new Array();
      var filename = new Array();
      for(i=0;i<fullfields.length;i++) {
        lastbackslash[i] = screenshotfieldvalue[fullfields[i]].lastIndexOf('\\');
        endoffilename[i] = screenshotfieldvalue[fullfields[i]].lastIndexOf('\.')-screenshotfieldvalue[fullfields[i]].length;
        if(lastbackslash[i]<1) {
         lastbackslash[i] = screenshotfieldvalue[fullfields[i]].lastIndexOf('/');
        }
        filename[i] = screenshotfieldvalue[fullfields[i]].slice(lastbackslash[i]+1,endoffilename[i]);
        if(searchwrongchars.test(filename[i])) {
          filenamesnotok = true;
          document.getElementsByName(screenshotfieldname[fullfields[i]])[0].style.backgroundColor = jg_ffwrong;
        }
      }
    }
  }
  if(filenamesnotok) {
    alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ALERT_WRONG_FILENAME'));
    document.getElementsByName(screenshotfieldname[0])[0].focus();
    return false;
  } else if(fullfields.length>1) {
    var feld1 = new Number();
    var feld2 = new Number();
    for(i=0;i<fullfields.length;i++) {
      for(j=fullfields.length-1;j>i;j--) {
        if(screenshotfieldvalue[fullfields[i]].indexOf(screenshotfieldvalue[fullfields[j]])==0) {
          doublefiles = true;
          document.getElementsByName(screenshotfieldname[fullfields[i]])[0].style.backgroundColor = jg_ffwrong;
          document.getElementsByName(screenshotfieldname[fullfields[j]])[0].style.backgroundColor = jg_ffwrong;
          feld1 = i+1;
          feld2 = j+1
          alert(Joomla.JText._('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_ONE') + ' ' + feld1 + ' ' + Joomla.JText._('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_TWO') + ' ' + feld2 + '.');
        }
      }
    }
  }
  if(doublefiles) {
    document.getElementsByName(screenshotfieldname[0])[0].focus();
    return false;
  } else {
    form.submit();
    return true;
  }
}

function joom_categoryCheck()
{
  var form = document.CreateCategoryForm;

  // Do field validation
  form.name.style.backgroundColor = '';
  if(form.name.value == '' || form.name.value == null)
  {
    alert(Joomla.JText._('COM_JOOMGALLERY_COMMON_ERROR_CATEGORY_MUST_HAVE_TITLE'));
    form.name.style.backgroundColor = jg_ffwrong;
    form.name.focus();

    return false;
  }
}