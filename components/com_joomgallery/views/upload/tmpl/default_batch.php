<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="<?php echo JRoute::_('index.php?task=upload&type=batch'); ?>" method="post" name="BatchUploadForm" enctype="multipart/form-data" onsubmit="return joom_batchuploadcheck();">
  <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminlist">
    <tr valign="middle">
      <td align="center" colspan="2">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_BATCH_UPLOAD_NOTE'); ?>
      </td>
    </tr>
    <tr>
      <td align="right" width="50%">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_BATCH_ZIP_FILE'); ?>
      </td>
      <td align="left" width="50%">
        <input type="file" name="zippack" accept="application/zip, application/x-zip-compressed">
      </td>
    </tr>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_IMAGE_ASSIGN_TO_CATEGORY'); ?>
      </td>
      <td align="left">
        <?php echo $this->lists['cats']; ?>
      </td>
    </tr>
<?php if(!$this->_config->get('jg_useruseorigfilename') && $this->_config->get('jg_useruploadnumber'))
      {
        $sup1 = '&sup1;';
        $sup2 = '&sup2;';
        if($this->_config->get('jg_delete_original_user') != 2)
        {
          $sup3 = '&sup2;';
        }
        else
        {
          $sup3 = '&sup3;';
        }
      }
      else
      {
        if($this->_config->get('jg_delete_original_user') != 2)
        {
          $sup3 = '&sup1;';
        }
        else
        {
          $sup2 = '&sup1;';
          $sup3 = '&sup2;';
        }
      }
      if(!$this->_config->get('jg_useruseorigfilename') && $this->_config->get('jg_useruploadnumber')): ?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_COUNTER_NUMBER'); ?>&nbsp;<?php echo $sup1; ?>
      </td>
      <td align="left">
        <input type="text" name="filecounter" value="1" size="5" maxlength="5" />
      </td>
    </tr>
<?php endif;
      if(!$this->_config->get('jg_useruseorigfilename')): ?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_GENERIC_TITLE'); ?>
      </td>
      <td align="left">
        <input type="text" name="imgtitle" size="34" maxlength="256" value="" />
      </td>
    </tr>
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
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD'); ?>&nbsp;<?php echo $sup2; ?>
      </td>
      <td align="left">
        <input type="checkbox" name="original_delete" value="1" />
      </td>
    </tr>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1): ?>
      <tr>
        <td align="right">
          <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF'); ?>&nbsp;<?php echo $sup3; ?>
        </td>
        <td align="left">
          <input type="checkbox" name="create_special_gif" value="1" />
        </td>
      </tr>
<?php endif;
      if($this->_config->get('jg_redirect_after_upload')): ?>
    <tr>
      <td align="right">
        <?php echo JText::_('COM_JOOMGALLERY_DEBUG_MODE'); ?>
      </td>
      <td align="left">
        <input type="checkbox" name="debug" value="1" />
      </td>
    </tr>
<?php endif; ?>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" value="<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_START_BATCHUPLOAD'); ?>" class="button" />
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <div align="center" class="smallgrey">
<?php if(!$this->_config->get('jg_useruseorigfilename') && $this->_config->get('jg_useruploadnumber')): ?>
          <br /><?php echo $sup1; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_COUNTER_NUMBER_ASTERISK'); ?>
<?php endif;
      if($this->_config->get('jg_delete_original_user') == 2): ?>
          <br /><?php echo $sup2; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD_ASTERISK'); ?>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1): ?>
          <br /><?php echo $sup3; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF_ASTERISK'); ?>
<?php endif;
      if($this->_config->get('jg_redirect_after_upload')): ?>
          <br /><b><?php echo JText::_('COM_JOOMGALLERY_DEBUG_MODE_ASTERISK'); ?></b>
<?php endif; ?>
        </div>
      </td>
    </tr>
  </table>
</form>