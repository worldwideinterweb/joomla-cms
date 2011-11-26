<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
  <div class="jg_minicount">
<?php if($this->total < 1): ?>
    <?php echo JText::_('COM_JOOMGALLERY_PLUGIN_MINI_NO_IMAGES'); ?>
<?php endif;
      if($this->total == 1): ?>
    <?php echo JText::_('COM_JOOMGALLERY_MINI_ONE_IMAGE_FOUND'); ?>
<?php endif;
      if($this->total > 1): ?>
    <?php echo JText::sprintf('COM_JOOMGALLERY_MINI_IMAGES_FOUND', $this->total); ?>
<?php endif; ?>
  </div>
  <div class="pagenav jg_paginationmini">
<?php if($this->totalpages > 1):
        if($this->page != 1): ?>
    <a class="jg_pagination_begin" href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&tmpl=component'); ?>" onclick="javascript:ajaxRequest('<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&format=json', false); ?>', 1); return false;" class="jg_pagenav">
      &laquo;&laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_BEGIN'); ?></a>
<?php   endif;
        if($this->page == 1): ?>
    <span class="jg_pagenav">
      &laquo;&laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_BEGIN'); ?>
    </span>
<?php   endif;
        if($this->page - 1 > 0): ?>
    <a class="pagenav_prev jg_pagination_prev" href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&tmpl=component&page='.($this->page-1)); ?>" onclick="javascript:ajaxRequestPrevPage('<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&format=json', false); ?>'); return false;" class="jg_pagenav">
      &laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_PREVIOUS'); ?></a>
<?php   endif;
        if($this->page - 1 <= 0): ?>
    <span class="pagenav_prev jg_pagination_prev">
      &laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_PREVIOUS'); ?>&nbsp;
    </span>
<?php   endif; ?>
    <?php echo JHTML::_('joomgallery.pagination', 'index.php?option='._JOOM_OPTION.'&view=mini&tmpl_component&page=%u', $this->totalpages, $this->page, '', 'javascript:ajaxRequest(\''.JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&format=json', false).'\', %u); return false;'); ?>
<?php   if($this->page + 1 <= $this->totalpages): ?>
    <a class="pagenav_next jg_pagination_next" href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&tmpl=component&page='.($this->page+1)); ?>" onclick="javascript:ajaxRequestNextPage('<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&format=json', false); ?>'); return false;" class="jg_pagenav">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_NEXT'); ?>&nbsp;&raquo;</a>
<?php   endif;
        if($this->page + 1 > $this->totalpages): ?>
    <span class="pagenav_next jg_pagination_next">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_NEXT'); ?>&nbsp;&raquo;
    </span>
<?php   endif;
        if($this->page != $this->totalpages): ?>
    <a class="jg_pagination_end" href="<?php echo JRoute::_('index.php?index.php?option='._JOOM_OPTION.'&view=mini&tmpl=component&page='.$this->totalpages); ?>" onclick="javascript:ajaxRequest('<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&view=mini&format=json', false); ?>', <?php echo $this->totalpages; ?>); return false;" class="jg_pagenav">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_END'); ?>&nbsp;&raquo;&raquo;</a>
<?php   endif;
        if($this->page == $this->totalpages): ?>
    <span class="jg_pagination_end">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_END'); ?>&nbsp;&raquo;&raquo;
    </span>
<?php   endif;
      endif; ?>
  </div>