<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
  <div class="jg_uploadform">
    <form action="<?php echo JRoute::_('index.php?task=upload&type=single'); ?>" method="post" name="SingleUploadForm" enctype="multipart/form-data" onsubmit="return joom_singleuploadcheck()" >
<?php if(!$this->_config->get('jg_useruseorigfilename')): ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_TITLE'); ?>
        </div>
        <input class="inputbox" type="text" name="imgtitle" size="42" maxlength="100" value="" />
      </div>
      <!--<div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_ALIAS'); ?>
        </div>
        <input class="inputbox" type="text" name="alias" size="42" maxlength="100" value="" />
      </div>-->
<?php endif; ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_CATEGORY'); ?>
        </div>
        <?php echo $this->lists['cats']; ?> 
      </div>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_DESCRIPTION'); ?>
        </div>
        <textarea class="inputbox" cols="40" rows="5" name="imgtext"></textarea>
      </div>
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
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_COUNTER_NUMBER'); ?>&nbsp;<?php echo $sup1; ?>
        </div>
        <input type="text" name="filecounter" value="1" size="5" maxlength="5" />
      </div>
<?php endif; ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_AUTHOR_OWNER'); ?>
        </div>
        <b><?php echo JHTML::_('joomgallery.displayname', $this->_user->get('id'), 'upload'); ?></b>
      </div>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED'); ?>
        </div>
        <?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', 1); ?>
      </div>
<?php for($i = 0; $i < $this->inputcounter; $i++): ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_IMAGE_PATH'); ?>:
        </div>
        <input type="file" name="arrscreenshot[<?php echo $i; ?>]" class="inputbox" />
      </div>
<?php endfor;
      if($this->_config->get('jg_delete_original_user') == 2): ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="checkbox" name="original_delete" value="1" />
        </div>
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD'); ?>&nbsp;<?php echo $sup2; ?>
      </div>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1): ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="checkbox" name="create_special_gif" value="1" />
        </div>
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF'); ?>&nbsp;<?php echo $sup3; ?>
      </div>
<?php endif;
      if($this->_config->get('jg_redirect_after_upload')): ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="checkbox" name="debug" value="1" />
        </div>
        <?php echo JText::_('COM_JOOMGALLERY_DEBUG_MODE'); ?>
      </div>
<?php endif; ?>
      <div class="jg_txtrow">
        <input type="submit" value="<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_UPLOAD'); ?>" class="button" />
        <input type="button" name="button" value="<?php echo JText::_('COM_JOOMGALLERY_COMMON_CANCEL'); ?>" class="button"
          onclick="javascript:location.href='<?php echo JRoute::_('index.php?view=userpanel', false); ?>';" />
      </div>
<?php if(!$this->_config->get('jg_useruseorigfilename') && $this->_config->get('jg_useruploadnumber')): ?>
      <div class="jg_uploadnotice small sectiontableentry2">
        <?php echo $sup1; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_COUNTER_NUMBER_ASTERISK'); ?>
      </div>
<?php endif;
      if($this->_config->get('jg_delete_original_user') == 2): ?>
      <div class="jg_uploadnotice small sectiontableentry2">
        <?php echo $sup2; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD_ASTERISK'); ?>
      </div>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1): ?>
      <div class="jg_uploadnotice small sectiontableentry2">
        <?php echo $sup3; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF_ASTERISK'); ?>
      </div>
<?php endif;
      if($this->_config->get('jg_redirect_after_upload')): ?>
      <div>
        <b><?php echo JText::_('COM_JOOMGALLERY_DEBUG_MODE_ASTERISK'); ?></b>
      </div>
<?php endif; ?>
      <div>
        <input type="hidden" name="id" value="" />
      </div>
    </form>
  </div>