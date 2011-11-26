<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<!-- --------------------------------------------------------------------------------------------------- -->
<!-- --------     A DUMMY APPLET, THAT ALLOWS THE NAVIGATOR TO CHECK THAT JAVA IS INSTALLED   ---------- -->
<!-- --------               If no Java: Java installation is prompted to the user.            ---------- -->
<!-- --------------------------------------------------------------------------------------------------- -->
<!--"CONVERTED_APPLET"-->
<!-- HTML CONVERTER -->
<script language="JavaScript" type="text/javascript"><!--
    var _info = navigator.userAgent;
    var _ns = false;
    var _ns6 = false;
    var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
//--></script>
    <comment>
        <script language="JavaScript" type="text/javascript"><!--
        var _ns = (navigator.appName.indexOf("Netscape") >= 0 && ((_info.indexOf("Win") > 0 && _info.indexOf("Win16") < 0 && java.lang.System.getProperty("os.version").indexOf("3.5") < 0) || (_info.indexOf("Sun") > 0) || (_info.indexOf("Linux") > 0) || (_info.indexOf("AIX") > 0) || (_info.indexOf("OS/2") > 0) || (_info.indexOf("IRIX") > 0)));
        var _ns6 = ((_ns == true) && (_info.indexOf("Mozilla/5") >= 0));
//--></script>
    </comment>

<script language="JavaScript" type="text/javascript"><!--
    if (_ie == true) document.writeln('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" WIDTH = "0" HEIGHT = "0" NAME = "JUploadApplet"  codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#Version=5,0,0,3"><noembed><xmp>');
    else if (_ns == true && _ns6 == false) document.writeln('<embed ' +
      'type="application/x-java-applet;version=1.5" \
            CODE = "wjhk.jupload2.EmptyApplet" \
            ARCHIVE = "<?php echo JURI::root(); ?>media/joomgallery/java/wjhk.jupload.jar" \
            NAME = "JUploadApplet" \
            WIDTH = "0" \
            HEIGHT = "0" \
            type ="application/x-java-applet;version=1.6" \
            scriptable ="false" ' +
      'scriptable=false ' +
      'pluginspage="http://java.sun.com/products/plugin/index.html#download"><noembed><xmp>');
//--></script>
<applet  code = "wjhk.jupload2.EmptyApplet" ARCHIVE = "<?php echo JURI::root(); ?>media/joomgallery/java/wjhk.jupload.jar" WIDTH = "0" HEIGHT = "0" NAME = "JUploadApplet"></xmp>
    <param name = CODE VALUE = "wjhk.jupload2.EmptyApplet" >
    <param name = ARCHIVE VALUE = "<?php echo JURI::root(); ?>media/joomgallery/java/wjhk.jupload.jar" >
    <param name = NAME VALUE = "JUploadApplet" >
    <param name = "type" value="application/x-java-applet;version=1.5">
    <param name = "scriptable" value="false">
    <param name = "type" VALUE="application/x-java-applet;version=1.6">
    <param name = "scriptable" VALUE="false">
</xmp>
Java 1.5 or higher plugin required.
</applet>
</noembed>
</embed>
</object>
<form name="JavaUploadForm">
  <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminlist">
    <tr>
      <td align="center" colspan="2">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_JUPLOAD_NOTE'); ?>
      </td>
    </tr>
    <tr>
      <td align="right" width="50%">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_IMAGE_ASSIGN_TO_CATEGORY'); ?>
      </td>
      <td align="left">
        <?php echo $this->lists['cats']; ?>
      </td>
    </tr>
<?php if($this->_config->get('jg_delete_original_user') == 2)
      {
        $sup1 = '&sup1;';
        $sup2 = '&sup2;';
      }
      else
      {
        $sup2 = '&sup1;';
      }
      if(!$this->_config->get('jg_useruseorigfilename')): ?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_GENERIC_TITLE'); ?>
      </td>
      <td align="left">
        <input type="text" name="imgtitle" size="34" maxlength="256" value="" />
      </td>
    </tr>
    <!--<tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_ALIAS'); ?>
      </td>
      <td align="left">
        <input type="text" name="alias" size="34" maxlength="256" value="" />
      </td>
    </tr>-->
<?php endif; ?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_GENERIC_DESCRIPTION_OPTIONAL'); ?>
      </td>
      <td align="left">
        <input type="text" name="imgtext" size="34" maxlength="1000" />
      </td>
    </tr>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_AUTHOR_OWNER'); ?>
      </td>
      <td align="left">
        <b><?php echo JHTML::_('joomgallery.displayname', $this->_user->get('id'), 'upload'); ?></b>
      </td>
    </tr>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED'); ?>
      </td>
      <td align="left">
        <?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', 1); ?>
      </td>
    </tr>
<?php if($this->_config->get('jg_delete_original_user') == 2): ?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD'); ?>&nbsp;&sup1;
      </td>
      <td align="left">
        <input type="checkbox" name="original_delete" value="1" />
      </td>
    </tr>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1):?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF'); ?>&nbsp;<?php echo $sup2; ?>
      </td>
      <td align="left">
        <input type="checkbox" name="create_special_gif" value="1" />
      </td>
    </tr>
<?php endif;?>
    <tr>
      <td colspan="2" align="center">
        <div align="center" class="smallgrey">
<?php if($this->_config->get('jg_delete_original_user') == 2): ?>
          <?php echo $sup1; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD_ASTERISK'); ?>
<?php endif;
if($this->_config->get('jg_special_gif_upload') == 1):?>
          <br /><?php echo $sup2; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF_ASTERISK'); ?>
<?php endif;?>
        </div>
      </td>
    </tr>
    <tr>
<?php //If 'originals deleted' setted in backend AND the picture has to be resized
      //this will be done local within in the applet, so only the detail picture
      //will be uploaded
?>
      <td colspan="2" align="center">
        <applet name="JUpload" code="wjhk.jupload2.JUploadApplet" archive="<?php echo JURI::root(); ?>media/joomgallery/java/wjhk.jupload.jar" width="700" height="600" mayscript>
          <param name="postURL" value="<?php echo JURI::root(); ?>index.php?option=<?php echo _JOOM_OPTION; ?>&task=upload&type=java">
          <param name="lookAndFeel" value="system">
          <param name="showLogWindow" value=false>
          <param name="showStatusBar" value="true">
          <param name="formdata" value="JavaUploadForm">
          <param name="debugLevel" value="0">
<?php if($this->_config->get('jg_msg_upload_type') != 0):?>
          <param name="afterUploadURL" value="<?php echo JURI::root(); ?>index.php?option=<?php echo _JOOM_OPTION; ?>&task=sendmessage&type=javaupload">
          <param name="urlToSendErrorTo" value="<?php echo JURI::root(); ?>index.php?option=<?php echo _JOOM_OPTION; ?>&task=checkerror&type=javaupload">
<?php else: ?>
          <param name="afterUploadURL" value="javascript:alert('<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_OUTPUT_UPLOAD_COMPLETE', true); ?>');">
<?php endif;?>
          <param name="nbFilesPerRequest" value="1">
          <param name="stringUploadSuccess" value="JOOMGALLERYUPLOADSUCCESS">
          <param name="stringUploadError" value="JOOMGALLERYUPLOADERROR (.*)">
          <param name="uploadPolicy" value="PictureUploadPolicy">
          <param name="pictureTransmitMetadata" value="true">
          <param name="allowedFileExtensions" value="jpg/jpeg/jpe/png/gif">
<?php if($this->_config->get('jg_delete_original_user') == 1 && $this->_config->get('jg_resizetomaxwidth')): ?>
          <param name="maxPicHeight" value="<?php echo $this->_config->get('jg_maxwidth'); ?>">
          <param name="maxPicWidth" value="<?php echo $this->_config->get('jg_maxwidth'); ?>">
          <param name="pictureCompressionQuality" value="<?php echo ($this->_config->get('jg_picturequality')/100); ?>">
<?php else: ?>
          <param name="pictureCompressionQuality" value="0.8">
<?php endif; ?>
          <param name="fileChooserImagePreview" value="false">
          <param name="fileChooserIconFromFileContent" value="-1">
<?php if(!$this->cookieNavigator): ?>
          <param name="readCookieFromNavigator" value="false">
          <param name="specificHeaders" value="Cookie: <?php echo $this->sessionname.'='.$this->sessiontoken;?>">
<?php endif; ?>
          Java 1.5 or higher plugin required.
        </applet>
      </td>
    </tr>
  </table>
</form>