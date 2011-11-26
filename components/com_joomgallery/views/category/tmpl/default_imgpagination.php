<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<?php if($this->_config->get('jg_showpiccount')): ?>
  <div class="jg_catcountimg">
<?php   if($this->totalimages == 1): ?>
    <?php echo JText::_('COM_JOOMGALLERY_THERE_IS_ONE_PICTURE_IN_CATEGORY'); ?>
<?php   endif;
        if($this->totalimages > 1): ?>
    <?php echo JText::sprintf('COM_JOOMGALLERY_CATEGORY_THERE_ARE_IMAGES_IN_CATEGORY', $this->totalimages); ?>
<?php   endif; ?>
  </div>
<?php endif; ?>
  <div class="pagenav jg_paginationimg">
<?php
      if($this->totalpages > 1):
        if($this->page != 1): ?>
    <a class="jg_pagination_begin" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page=1&catpage='.$this->catpage.$this->order_url).JHTML::_('joomgallery.anchor', 'category'); ?>">
      &laquo;&laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_BEGIN'); ?></a>&nbsp;
<?php   endif;
        if($this->page == 1): ?>
      &laquo;&laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_BEGIN'); ?>&nbsp;
<?php   endif;
        if($this->page - 1 > 0): ?>
    <a class="pagenav_prev jg_pagination_prev" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.($this->page - 1).'&catpage='.$this->catpage.$this->order_url).JHTML::_('joomgallery.anchor', 'category'); ?>">
      &laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_PREVIOUS'); ?></a>&nbsp;
<?php   endif;
        if($this->page - 1 <= 0): ?>
    <span class="pagenav_prev jg_pagination_prev">
    &laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_PREVIOUS'); ?>&nbsp;
    </span>
<?php   endif; ?>
      <?php echo JHTML::_('joomgallery.pagination', 'index.php?view=category&catid='.$this->category->cid.'&page=%u&catpage='.$this->catpage.$this->order_url, $this->totalpages, $this->page, 'category'); ?>
<?php   if($this->page + 1 <= $this->totalpages): ?>
    &nbsp;
    <a class="pagenav_next jg_pagination_next" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.($this->page + 1).'&catpage='.$this->catpage.$this->order_url).JHTML::_('joomgallery.anchor', 'category'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_NEXT'); ?>&nbsp;&raquo;</a>
<?php   endif;
        if($this->page + 1 > $this->totalpages): ?>
    <span class="pagenav_next jg_pagination_next">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_NEXT'); ?>&nbsp;&raquo;
    </span>
<?php   endif;
        if($this->page != $this->totalpages): ?>
    &nbsp;
    <a class="jg_pagination_end" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.$this->totalpages.'&catpage='.$this->catpage.$this->order_url).JHTML::_('joomgallery.anchor', 'category'); ?>">
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_END'); ?>&nbsp;&raquo;&raquo;
    </a>
<?php   endif;
        if($this->page == $this->totalpages): ?>
    <span class="jg_pagination_end">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_END'); ?>&nbsp;&raquo;&raquo;
    </span>
<?php   endif;
      endif; ?>
  </div>
