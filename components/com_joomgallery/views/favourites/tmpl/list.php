<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
echo $this->loadTemplate('header'); ?>
  <div class="sectiontableheader">
    <?php echo $this->output('HEADING'); ?>
  </div>
  <div class="jg_fav_switchlayout">
    <a href="<?php echo JRoute::_('index.php?task=switchlayout&layout='.$this->getLayout()); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_FAVOURITES_SWITCH_LAYOUT'); ?>
    </a>
  </div>
  <div class="jg_fav_clearlist">
    <a href="<?php echo JRoute::_('index.php?task=removeall'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_FAVOURITES_REMOVE_ALL'); ?>
    </a>
  </div>
  <div class="sectiontableheader">
    <div class="jg_up_entry">
      <div class="jg_up_ename">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_IMAGE_NAME'); ?>
      </div>
      <div class="jg_up_ehits">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_HITS'); ?>
      </div>
      <div class="jg_up_ecat">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_CATEGORY'); ?>
      </div>
      <div class="jg_up_eact">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_ACTION'); ?>
      </div>
    </div>
  </div>
<?php if(!count($this->rows)): ?>
  <div class="jg_txtrow">
    <div class="sectiontableentry1">
      <?php echo JHTML::_('joomgallery.icon', 'arrow.png', 'arrow'); ?>
      <?php echo $this->output('NO_IMAGES'); ?>
    </div>
  </div>
<?php endif;
      $this->i = 0;
      foreach($this->rows as $row): ?>
  <div class="sectiontableentry<?php $this->i++; echo ($this->i%2)+1; ?>">
    <div class="jg_up_entry">
      <div class="jg_up_ename">
        <?php echo JHTML::_('joomgallery.minithumbimg', $row, 'jg_up_eminithumb', true, true); ?>
        <a href="<?php echo $row->link; ?>">
          <?php echo $row->imgtitle; ?>
        </a>
      </div>
      <div class="jg_up_ehits">
        <?php echo $row->hits; ?>
      </div>
      <div class="jg_up_ecat">
        <?php echo JHTML::_('joomgallery.categorypath', $row->catid, ' &raquo; ', true); ?>
      </div>
<?php   if($this->params->get('show_download_icon') == 1): ?>
      <div class="jg_up_esub1<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_COMMON_DOWNLOAD_TIPTEXT', 'COM_JOOMGALLERY_COMMON_DOWNLOAD_TIPCAPTION'); ?>">
        <a href="<?php echo JRoute::_('index.php?task=download&id='.$row->id); ?>">
        <?php echo JHTML::_('joomgallery.icon', 'download.png', 'COM_JOOMGALLERY_COMMON_DOWNLOAD_TIPCAPTION'); ?></a>
      </div>
<?php   endif;
        if($this->params->get('show_download_icon') == -1): ?>
      <div class="jg_up_esub1<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_COMMON_DOWNLOAD_LOGIN_TIPTEXT', 'COM_JOOMGALLERY_COMMON_DOWNLOAD_TIPCAPTION'); ?>">
        <?php echo JHTML::_('joomgallery.icon', 'download_gr.png', 'COM_JOOMGALLERY_COMMON_DOWNLOAD_TIPCAPTION'); ?>
      </div>
<?php   endif; ?>
      <div class="jg_up_esub2<?php echo JHTML::_('joomgallery.tip', $this->output('REMOVE_TIPTEXT'), $this->output('REMOVE_TIPCAPTION'), false, false); ?>">
        <a href="<?php echo JRoute::_('index.php?option=com_joomgallery&task=removeimage&id='.$row->id); ?>">
        <?php echo JHTML::_('joomgallery.icon', 'basket_remove.png', $this->output('REMOVE_TIPCAPTION'), null, null, false); ?></a>
      </div>
<?php   if($row->show_edit_icon): ?>
      <div class="jg_up_esub3<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_COMMON_EDIT_IMAGE_TIPTEXT', 'COM_JOOMGALLERY_COMMON_EDIT_IMAGE_TIPCAPTION'); ?>">
        <a href="<?php echo JRoute::_('index.php?view=edit&id='.$row->id.$this->redirect); ?>">
          <?php echo JHTML::_('joomgallery.icon', 'edit.png', 'COM_JOOMGALLERY_COMMON_EDIT_IMAGE_TIPCAPTION'); ?></a>
      </div>
<?php   else: ?>
      <div class="jg_up_esub3">&nbsp;</div>
<?php   endif;
        if($row->show_delete_icon): ?>
      <div class="jg_up_esub4<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_COMMON_DELETE_IMAGE_TIPTEXT', 'COM_JOOMGALLERY_COMMON_DELETE_IMAGE_TIPCAPTION'); ?>">
        <a href="javascript:if(confirm('<?php echo JText::_('COM_JOOMGALLERY_COMMON_ALERT_SURE_DELETE_SELECTED_ITEM', true); ?>')){ location.href='<?php echo JRoute::_('index.php?task=delete&id='.$row->id.$this->redirect, false);?>';}">
          <?php echo JHTML::_('joomgallery.icon', 'edit_trash.png', 'COM_JOOMGALLERY_COMMON_DELETE_IMAGE_TIPCAPTION'); ?></a>
      </div>
<?php   else: ?>
      <div class="jg_up_esub4">&nbsp;</div>
<?php   endif; ?>
    </div>
  </div>
<?php endforeach; ?>

<?php echo $this->loadTemplate('footer');