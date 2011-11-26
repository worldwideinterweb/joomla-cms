<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
      if($this->_config->get('jg_showsubcatcount')): ?>
  <div class="jg_catcountsubcats">
<?php   if($this->totalcategories == 1): ?>
    <?php echo JText::_('COM_JOOMGALLERY_THERE_IS_ONE_SUBCATEGORY_IN_CATEGORY'); ?>
<?php   endif;
        if($this->totalcategories > 1): ?>
    <?php echo JText::sprintf('COM_JOOMGALLERY_CATEGORY_THERE_ARE_SUBCATEGORIES_IN_CATEGORY', $this->totalcategories); ?>
<?php   endif; ?>
  </div>
<?php endif; ?>
  <div class="pagenav jg_paginationsubcats">
<?php
      if($this->cattotalpages > 1):
        if($this->catpage != 1): ?>
      <a class="jg_pagination_begin" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.$this->page.'&catpage=1').JHTML::_('joomgallery.anchor', 'subcategory'); ?>">
        &laquo;&laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_BEGIN'); ?>&nbsp;
      </a>
<?php   endif;
        if($this->catpage == 1): ?>
    &laquo;&laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_BEGIN'); ?>&nbsp;
<?php   endif;
        if($this->catpage - 1 > 0): ?>
    <a class="pagenav_prev jg_pagination_prev" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.$this->page.'&catpage='.($this->catpage - 1)).JHTML::_('joomgallery.anchor', 'subcategory'); ?>">
      &laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_PREVIOUS'); ?>&nbsp;
    </a>
<?php   endif;
        if($this->catpage - 1 <= 0): ?>
    <span class="pagenav_prev jg_pagination_prev">
    &laquo;&nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_PREVIOUS'); ?>&nbsp;
    </span>
<?php   endif; ?>
      <?php echo JHTML::_('joomgallery.pagination', 'index.php?view=category&catid='.$this->category->cid.'&page='.$this->page.'&catpage=%u', $this->cattotalpages, $this->catpage, 'subcategory'); ?>
<?php   if($this->catpage + 1 <= $this->cattotalpages): ?>
    <a class="pagenav_next jg_pagination_next" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.$this->page.'&catpage='.($this->catpage + 1)).JHTML::_('joomgallery.anchor', 'subcategory'); ?>">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_NEXT'); ?>&nbsp;&raquo;
    </a>
<?php   endif;
        if($this->catpage + 1 > $this->cattotalpages): ?>
    <span class="pagenav_next jg_pagination_next">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_NEXT'); ?>&nbsp;&raquo;
    </span>
<?php   endif;
        if($this->catpage != $this->cattotalpages): ?>
    <a class="jg_pagination_end" href="<?php echo JRoute::_('index.php?view=category&catid='.$this->category->cid.'&page='.$this->page.'&catpage='.$this->cattotalpages).JHTML::_('joomgallery.anchor', 'subcategory'); ?>">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_END'); ?>&nbsp;&raquo;&raquo;
    </a>
<?php   endif;
        if($this->catpage == $this->cattotalpages): ?>
    <span class="jg_pagination_end">
      &nbsp;<?php echo JText::_('COM_JOOMGALLERY_COMMON_PAGENAVIGATION_END'); ?>&nbsp;&raquo;&raquo;
    </span>
<?php   endif;
      endif; ?>
  </div>
