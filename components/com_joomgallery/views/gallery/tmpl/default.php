<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
echo $this->loadTemplate('header');?>
<?php if($this->_config->get('jg_anchors')): ?>
  <a name="gallery"></a>
<?php endif;
      if($this->params->get('show_pagination_top')):
        echo $this->loadTemplate('pagination');
      endif;
      if($this->_config->get('jg_showallcathead')): ?>
  <div class="sectiontableheader">
    <?php echo JText::_('COM_JOOMGALLERY_COMMON_CATEGORIES'); ?>
  </div>
<?php endif;
      $this->i  = 0;
      $num_rows = ceil(count($this->rows ) / $this->_config->get('jg_colcat'));
      $index    = 0;
      for($row_count = 0; $row_count < $num_rows; $row_count++):?>
  <div class="jg_row sectiontableentry<?php echo ($this->i%2)+1; ?>">
<?php   for($col_count = 0; (($col_count < $this->_config->get('jg_colcat')) && ($index < count($this->rows))); $col_count++):
          $row = $this->rows[$index];
          $row->gallerycontainer    = 'jg_element_gal';
          if(!isset($row->photocontainer)):
            $row->photocontainer    = 'jg_photo_container';
            $row->textcontainer     = 'jg_element_txt';
          endif;
          if($this->_config->get('jg_showcatthumb') == 1 && $this->_config->get('jg_ctalign') == 0 && ($this->i%2) == 0):
            $row->gallerycontainer  = 'jg_element_gal_r';
            $row->photocontainer    = 'jg_photo_container_r';
            $row->textcontainer     = 'jg_element_txt_r';
          endif; ?>
    <div class="<?php echo $row->gallerycontainer; ?>">
<?php     if($row->thumb_src):?>
      <div class="jg_imgalign_gal">
        <div class="<?php echo $row->photocontainer; ?>">
          <a title="<?php echo $row->name; ?>" href="<?php echo $row->link ?>">
            <img src="<?php echo $row->thumb_src; ?>" class="jg_photo" alt="<?php echo $row->name; ?>" />
          </a>
        </div>
      </div>
<?php     endif; ?>
      <div class="<?php echo $row->textcontainer; ?>">
        <ul>
          <li>
<?php     if(in_array($row->access, $this->_user->getAuthorisedViewLevels())): ?>
            <a href="<?php echo $row->link; ?>">
              <b><?php echo $this->escape($row->name); ?></b>
            </a>
<?php     else: ?>
            <span class="jg_no_access<?php echo JHTML::_('joomgallery.tip', JText::_('COM_JOOMGALLERY_COMMON_TIP_YOU_NOT_ACCESS_THIS_CATEGORY'), $this->escape($row->name), false, false); ?>">
              <b><?php echo $this->escape($row->name); ?></b>
              <?php if($this->_config->get('jg_showrestrictedhint')): echo JHtml::_('joomgallery.icon', 'group_key.png', 'COM_JOOMGALLERY_COMMON_TIP_YOU_NOT_ACCESS_THIS_CATEGORY'); endif; ?>
            </span>
<?php     endif; ?>
          </li>
<?php     if(in_array($row->access, $this->_user->getAuthorisedViewLevels())):
            if($this->_config->get('jg_showtotalcatimages') || $row->isnew): ?>
          <li>
<?php       if($this->_config->get('jg_showtotalcatimages')): ?>
            <?php echo JText::sprintf($row->picorpics, $row->pictures); ?>
<?php       endif;
            echo $row->isnew; ?>
          </li>
<?php       endif;
            if($this->_config->get('jg_showtotalcathits')): ?>
          <li>
            <?php echo JText::sprintf('COM_JOOMGALLERY_COMMON_HITS_VAR', $row->totalhits); ?>
          </li>
<?php       endif;
          endif;
          if($row->description && $this->_config->get('jg_showdescriptioningalleryview')): ?>
          <li>
            <?php echo JHTML::_('joomgallery.text', $row->description); ?>
          </li>
<?php     endif; ?>
        </ul>
      </div>
<?php     //TODO: config option? -> add possibility of solution without javascript (like it had been before dtree was added)
          if($this->_config->get('jg_showsubsingalleryview')):
            JHTML::_('joomgallery.categorytree', $row->cid, $row->textcontainer);
          endif; ?>
    </div>
<?php     $index++;
        endfor; ?>
    <div class="jg_clearboth"></div>
  </div>
<?php   $this->i++;
      endfor;
      if($this->_config->get('jg_showallcathead')): ?>
  <div class="sectiontableheader">
    &nbsp;
  </div>
<?php endif;
      if($this->params->get('show_pagination_bottom')):
        echo $this->loadTemplate('pagination');
      endif;
echo $this->loadTemplate('footer');
