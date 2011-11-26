<?php defined('_JEXEC') or die('Restricted access');
        #echo $this->sliders->startPane('joomgallery-upload-sliders');
        #echo $this->sliders->startPanel(JText::_('COM_JOOMGALLERY_MINI_EXTENDED'), 'param-page'); ?>
  <div class="jg-slider jg_uploadform">
    <form action="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&controller=upload&task=upload&tmpl=component&redirect='.base64_encode('index.php?option=com_joomgallery&view=mini&tmpl=component')); ?>" method="post" name="SingleUploadForm" enctype="multipart/form-data" onsubmit="return joom_singleuploadcheck()" >
      <?php /*for($i = 0; $i < $this->inputcounter; $i++):*/ ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_PLEASE_SELECT_IMAGE'); ?>
        </div>
        <input type="file" name="arrscreenshot[0<?php /*echo $i;*/ ?>]" class="inputbox" />
      </div>
      <hr />
<?php /*endfor;*/ 
      if(!$this->_config->get('jg_useruseorigfilename')): ?>
      <div class="jg_uprow">
        <div class="jg_uptext hasTip" title="<?php echo JText::_('COM_JOOMGALLERY_COMMON_TITLE'); ?>::<?php echo JText::_('COM_JOOMGALLERY_COMMON_TITLE_DESCRIPTION'); ?>">
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
        <?php echo $this->lists['upload_categories']; ?> 
      </div>
      <!--<div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_DESCRIPTION'); ?>
        </div>
        <textarea class="inputbox" cols="40" rows="5" name="imgtext"></textarea>
      </div>-->
      <!--<div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_AUTHOR_OWNER'); ?>
        </div>
        <b><?php echo $this->_user->get('username'); ?></b>
      </div>-->
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED'); ?>
        </div>
        <?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', 1); ?>
      </div>
<?php if($this->_config->get('jg_delete_original_user') == 2): ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="checkbox" name="original_delete" value="1" />
        </div>
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD'); ?>&nbsp;&sup1;
      </div>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1):
        if($this->_config->get('jg_delete_original_user') == 2):
          $sup2 = '&sup2;';
        else:
          $sup2 = '&sup1;';
        endif; ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="checkbox" name="create_special_gif" value="1" />
        </div>
        <?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF'); ?>&nbsp;<?php echo $sup2; ?>
      </div>
<?php endif; ?>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="submit" value="<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_UPLOAD'); ?>" class="button" />
        </div>
        <!--<input type="button" name="button" value="<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_AND_INSERT'); ?>" class="button"
          onclick="javascript:location.href='<?php echo JRoute::_('index.php?view=userpanel', false); ?>';" />-->
      </div>
      <hr />
<?php if($this->_config->get('jg_delete_original_user') == 2): ?>
      <div class="jg_uploadnotice small sectiontableentry2">
        &sup1;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_DELETE_ORIGINAL_AFTER_UPLOAD_ASTERISK'); ?>
      </div>
<?php endif;
      if($this->_config->get('jg_special_gif_upload') == 1): ?>
      <div class="jg_uploadnotice small sectiontableentry2">
        <?php echo $sup2; ?>&nbsp;<?php echo JText::_('COM_JOOMGALLERY_UPLOAD_CREATE_SPECIAL_GIF_ASTERISK'); ?>
      </div>
<?php endif; ?>
      <input type="hidden" name="id" value="" />
    </form>
  </div>
<?php   #echo $this->sliders->endPanel();*/
        #echo $this->sliders->endPane();