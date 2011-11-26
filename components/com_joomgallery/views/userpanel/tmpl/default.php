<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
echo $this->loadTemplate('header'); ?>
  <div class="jg_userpanelview">
    <div class="jg_up_head">
<?php if($this->params->get('show_upload_button')): ?>
      <input type="button" name="button" value="<?php echo JText::_('COM_JOOMGALLERY_COMMON_UPLOAD_NEW_IMAGE'); ?>" class="button"
        onclick="javascript:location.href='<?php echo JRoute::_('index.php?view=upload', false); ?>';" />
<?php endif;
      if($this->params->get('show_categories_button')): ?>
      <input type="button" name="button" value="<?php echo JText::_('COM_JOOMGALLERY_COMMON_CATEGORIES'); ?>" class="button"
      onclick="javascript:location.href='<?php echo JRoute::_('index.php?view=usercategories', false); ?>';" />
<?php endif; ?>
      <form action="<?php echo JRoute::_('index.php?view=userpanel'); ?>" method="post" name="form">
<?php if(!is_null($this->pagination)): ?>
        <div class="pagination">
          <?php echo $this->pagination->getListFooter(); ?>
        </div>
<?php endif; ?>
        <div class="jg_up_filter_entry">
          <div class="jg_up_filter_ecol1">
            <?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_SEARCH'); ?><br />
            <input class="inputbox" type="text" name="search" size="20" value="<?php echo $this->search; ?>" />
          </div>
          <div class="jg_up_filter_ecol2">
            <?php echo JText::_('COM_JOOMGALLERY_COMMON_SORT_BY_ORDERING'); ?><br />
            <?php echo $this->lists['ordering']; ?>
          </div>
        </div>
        <div class="jg_up_filter_entry">
          <div class="jg_up_filter_ecol1">
            <?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_BY_TYPE'); ?><br />
            <?php echo $this->lists['filter']; ?>
          </div>
          <div class="jg_up_filter_ecol2">
            <?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_BY_CATEGORY'); ?><br />
            <?php echo $this->lists['cats']; ?>
          </div>
        </div>
      </form>
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
        <div class="jg_up_epubl">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED');?>
        </div>
<?php if($this->_config->get('jg_approve')): ?>
        <div class="jg_up_eappr">
          <?php echo JText::_('COM_JOOMGALLERY_USERPANEL_APPROVED'); ?>
        </div>
<?php endif; ?>
      </div>
    </div>
<?php if(!count($this->rows)): ?>
    <div class="jg_txtrow">
      <div class="sectiontableentry1">
        <?php echo JHTML::_('joomgallery.icon', 'arrow.png', 'arrow'); ?>
        <?php echo JText::_('COM_JOOMGALLERY_USERPANEL_YOU_DO_NOT_HAVE_IMAGE'); ?>
      </div>
    </div>
<?php endif;
      $this->i = 0;
      $display_hidden_asterisk = false;
      foreach($this->rows as $row): ?>
    <div class="sectiontableentry<?php $this->i++; echo ($this->i%2)+1; ?>">
      <div class="jg_up_entry">
<?php   if($row->approved && $row->published)
        {
          $link = JHTML::_('joomgallery.openImage', $this->_config->get('jg_detailpic_open'), $row);
        }
        else
        {
          $link = '#';
        } ?>
        <div class="jg_up_ename">
<?php   if($this->_config->get('jg_showminithumbs')):
          echo JHTML::_('joomgallery.minithumbimg', $row, 'jg_up_eminithumb', $row->approved && $row->published, true);
        else: ?>
          <div class="jg_floatleft">
            <?php echo JHTML::_('joomgallery.icon', 'arrow.png', 'arrow'); ?>
          </div>
<?php   endif;
        if($row->approved && $row->published): ?>
          <a <?php echo $row->atagtitle; ?> href="<?php echo $link; ?>">
<?php   endif; ?>
            <?php echo $row->imgtitle; ?>
<?php   if($row->approved && $row->published): ?>
          </a>
<?php   endif; ?>
        </div>
        <div class="jg_up_ehits">
        <?php echo $row->hits; ?>
        </div>
        <div class="jg_up_ecat">
          <?php echo JHTML::_('joomgallery.categorypath', $row->catid, ' &raquo ', true, false, true); ?>
        </div>
<?php   if($row->show_edit_icon): ?>
        <div class="jg_up_esub1<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_COMMON_EDIT_IMAGE_TIPTEXT', 'COM_JOOMGALLERY_COMMON_EDIT_IMAGE_TIPCAPTION'); ?>">
          <a href="<?php echo JRoute::_('index.php?view=edit&id='.$row->id.$this->slimitstart); ?>">
            <?php echo JHTML::_('joomgallery.icon', 'edit.png', 'COM_JOOMGALLERY_COMMON_EDIT'); ?></a>
        </div>
<?php   else: ?>
        <div class="jg_up_esub1">&nbsp;</div>
<?php   endif;
        if($row->show_delete_icon): ?>
        <div class="jg_up_esub2<?php echo JHTML::_('joomgallery.tip', 'COM_JOOMGALLERY_COMMON_DELETE_IMAGE_TIPTEXT', 'COM_JOOMGALLERY_COMMON_DELETE_IMAGE_TIPCAPTION'); ?>">
          <a href="javascript:if(confirm('<?php echo JText::_('COM_JOOMGALLERY_COMMON_ALERT_SURE_DELETE_SELECTED_ITEM', true); ?>')){ location.href='<?php echo JRoute::_('index.php?task=delete&id='.$row->id.$this->slimitstart, false);?>';}">
            <?php echo JHTML::_('joomgallery.icon', 'edit_trash.png', 'COM_JOOMGALLERY_COMMON_DELETE'); ?></a>
        </div>
<?php   else: ?>
        <div class="jg_up_esub2">&nbsp;</div>
<?php   endif;
        $p_img    = 'cross';
        $p_title  = JText::_('COM_JOOMGALLERY_COMMON_PUBLISH_IMAGE_TIPCAPTION');
        $p_text   = JText::_('COM_JOOMGALLERY_COMMON_PUBLISH_IMAGE_TIPTEXT');
        if($row->published):
          $p_img = 'tick';
          $p_title = JText::_('COM_JOOMGALLERY_COMMON_UNPUBLISH_IMAGE_TIPCAPTION');
          $p_text  = JText::_('COM_JOOMGALLERY_COMMON_UNPUBLISH_IMAGE_TIPTEXT');
          endif; ?>
        <div class="jg_up_epubl">
<?php   if($this->_user->authorise('core.edit.state', _JOOM_OPTION.'.image.'.$row->id)): ?>
          <a href="<?php echo JRoute::_('index.php?task=publishimage&id='.$row->id.$this->slimitstart); ?>"<?php echo JHTML::_('joomgallery.tip', $p_text, $p_title, true, false); ?>>
            <?php echo JHTML::_('joomgallery.icon', $p_img.'.png', $p_img); ?></a>
<?php   else: ?>
          <?php echo JHTML::_('joomgallery.icon', $p_img.'.png', $p_img); ?>
<?php   endif;
        if($row->published && $row->hidden):
          $h_title = JText::_('COM_JOOMGALLERY_COMMON_HIDDEN_ASTERISK');
          $h_text  = JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED_BUT_HIDDEN');
          echo '<span'.JHTML::_('joomgallery.tip', $h_text, $h_title, true, false).'>'.JText::_('COM_JOOMGALLERY_COMMON_HIDDEN_ASTERISK').'</span>';
          $display_hidden_asterisk = true;
        endif; ?>
        </div>
<?php   if($this->_config->get('jg_approve')):
          $a_img = 'cross';
          if($row->approved):
            $a_img = 'tick';
          endif; ?>
        <div class="jg_up_eappr">
          <?php echo JHTML::_('joomgallery.icon', $a_img.'.png', $a_img); ?>
        </div>
<?php   endif; ?>
      </div>
    </div>
<?php endforeach; ?>
  </div>
<?php if($display_hidden_asterisk): ?>
  <div align="right">
    <?php echo JText::_('COM_JOOMGALLERY_COMMON_HIDDEN_ASTERISK'); ?> <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED_BUT_HIDDEN'); ?>
  </div>
<?php endif;
      echo $this->loadTemplate('footer');