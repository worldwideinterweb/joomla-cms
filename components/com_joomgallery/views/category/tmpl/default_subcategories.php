<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');?>
<?php if($this->_config->get('jg_anchors')): ?>
  <a name="subcategory"></a>
<?php endif;
      if($this->params->get('show_pagination_cat_top')):
        echo $this->loadTemplate('catpagination');
      endif; ?>
  <div class="jg_subcat">
<?php if($this->_config->get('jg_showsubcathead')): ?>
    <div class="sectiontableheader">
<?php if($this->params->get('show_feed_icon')): ?>
      <div class="jg_feed_icon">
        <a href="<?php echo $this->params->get('feed_url'); ?>"<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_CATEGORY_FEED_SUBCATEGORIES_TIPTEXT', 'COM_JOOMGALLERY_CATEGORY_FEED_TIPCAPTION', true); ?>>
          <?php echo JHtml::_('joomgallery.icon', 'feed.png', 'COM_JOOMGALLERY_COMMON_TIP_YOU_NOT_ACCESS_THIS_CATEGORY'); ?>
        </a>
      </div>
<?php $this->params->set('show_feed_icon', 0);
      endif; ?>
      <?php echo JText::_('COM_JOOMGALLERY_COMMON_SUBCATEGORIES'); ?>
    </div>
<?php endif;
      $cat_count = count($this->categories);
      $num_rows  = ceil($cat_count / $this->_config->get('jg_colsubcat'));
      $index     = 0;
      $this->i   = 0;
      for($row_count = 0; $row_count < $num_rows; $row_count++): ?>
    <div class="jg_row sectiontableentry<?php $this->i++; echo ($this->i%2)+1; ?>">
<?php   for($col_count = 0; ($col_count < $this->_config->get('jg_colsubcat')) && ($index < $cat_count); $col_count++):
          $row = $this->categories[$index]; ?>
      <div class="<?php echo $this->gallerycontainer; ?>">
<?php
          if($this->_config->get('jg_showsubthumbs')):
            if($this->_config->get('jg_showsubthumbs') == 2):
              $row->photocontainer  = 'jg_subcatelem_photo';
            endif;?>
<?php       if ($row->thumb_src): ?>
        <div class="jg_imgalign_catsubs">
          <div class="<?php echo $row->photocontainer; ?>">
            <a title="<?php echo $row->name; ?>" href="<?php echo $row->link; ?>">
              <img src="<?php echo $row->thumb_src; ?>" hspace="4" vspace="0" class="jg_photo" alt="<?php echo $row->name; ?>" />
            </a>
<?php       endif;
          endif;
          if($this->_config->get('jg_showsubthumbs') && $row->thumb_src):?>
          </div>
        </div>
<?php     endif; ?>
        <div class="<?php echo $row->textcontainer; ?>">
          <ul>
            <li>
              <?php echo JHTML::_('joomgallery.icon', 'arrow.png', 'arrow'); ?>
<?php     if(in_array($row->access, $this->_user->getAuthorisedViewLevels())): ?>
              <a href="<?php echo $row->link; ?>">
                <?php echo $this->escape($row->name); ?></a>
<?php     else: ?>
              <span class="jg_no_access<?php echo JHTML::_('joomgallery.tip', JText::_('COM_JOOMGALLERY_COMMON_TIP_YOU_NOT_ACCESS_THIS_CATEGORY'), $this->escape($row->name), false, false); ?>">
                <?php echo $this->escape($row->name); ?>
                <?php if($this->_config->get('jg_showrestrictedhint')): echo JHtml::_('joomgallery.icon', 'group_key.png', 'COM_JOOMGALLERY_COMMON_TIP_YOU_NOT_ACCESS_THIS_CATEGORY'); endif; ?>
              </span>
<?php     endif; ?>
            </li>
<?php     if(in_array($row->access, $this->_user->getAuthorisedViewLevels())):
            if($this->_config->get('jg_showtotalsubcatimages') || $row->isnew): ?>
          <li>
<?php       if($this->_config->get('jg_showtotalsubcatimages')): ?>
            <?php echo JText::sprintf($row->picorpics, $row->pictures); ?>
<?php       endif;
            echo $row->isnew; ?>
          </li>
<?php       endif;
            if($this->_config->get('jg_showtotalsubcathits')): ?>
          <li>
            <?php echo JText::sprintf('COM_JOOMGALLERY_COMMON_HITS_VAR', $row->totalhits); ?>
          </li>
<?php       endif;
          endif;
          if($row->description && $this->_config->get('jg_showdescriptionincategoryview')): ?>
            <li>
              <?php echo JHTML::_('joomgallery.text', $row->description); ?>
            </li>
<?php     endif; ?>
            <?php echo $row->event->afterDisplayCatThumb; ?>
          </ul>
        </div>
      </div>
<?php     $index++;
        endfor; ?>
      <div class="jg_clearboth"></div>
    </div>
<?php endfor;
      if($this->params->get('show_pagination_cat_bottom')):
        echo $this->loadTemplate('catpagination');
      endif;?>
  </div>