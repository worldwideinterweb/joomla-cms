<?php defined('_JEXEC') or die('Restricted access'); ?>
  <div class="jg-slider jg_uploadform">
    <form action="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&controller=categories&task=save&tmpl=component&redirect='.base64_encode('index.php?option='._JOOM_OPTION.'&view=mini&tmpl=component')); ?>" method="post" name="CreateCategoryForm" onsubmit="return joom_categoryCheck()">
      <div class="jg_uprow">
        <div class="jg_uptext hasTip" title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_NAME'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_NAME_DESCRIPTION'); ?>">
          <?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_NAME'); ?>
        </div>
        <input class="inputbox" type="text" name="name" size="42" maxlength="100" value="" />
      </div>
      <!--<div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_ALIAS'); ?>
        </div>
        <input class="inputbox" type="text" name="alias" size="42" maxlength="100" value="" />
      </div>-->
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_PARENT_CATEGORY'); ?>
        </div>
        <?php echo $this->lists['parent_categories']; ?> 
      </div>
      <!--<div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_DESCRIPTION'); ?>
        </div>
        <textarea class="inputbox" cols="40" rows="5" name="imgtext"></textarea>
      </div>-->
      <div class="jg_uprow">
        <div class="jg_uptext">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED'); ?>
        </div>
        <?php echo JHTML::_('select.booleanlist', 'published', 'class="inputbox"', 1); ?>
      </div>
      <div class="jg_uprow">
        <div class="jg_uptext">
          <input type="submit" value="<?php echo JText::_('COM_JOOMGALLERY_MINI_SAVE'); ?>" class="button" />
        </div>
      </div>
      <input type="hidden" name="cid" value="" />
    </form>
  </div>