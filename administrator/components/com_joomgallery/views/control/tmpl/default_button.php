<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
          <div class="jg-float<?php echo ($this->lang->isRTL()) ? 'right' : 'left'; ?>">
            <div class="icon">
              <a href="<?php echo $this->item->link; ?>">
                <?php echo JHTML::_('image.site', $this->item->img, '', NULL, NULL, JText::_($this->item->title)); ?>
                <span><?php echo JText::_($this->item->title); ?></span></a>
            </div>
          </div>