<?php defined('_JEXEC') or die('Restricted access');
      echo $this->sliders->startPane('joomgallery-images-sliders');
      if($this->extended > 0):
        echo $this->sliders->startPanel(JText::_('COM_JOOMGALLERY_MINI_EXTENDED'), 'param-page-images-options'); ?>
  <script type="text/javascript">
    $('param-page-images-options').addEvent('click', function(){
      $('jg_bu_minis').addClass('jg_displaynone');
    });
  </script>
  <div class="joomgallery-slider">
    <div class="jg_bu_extended_options">
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_TYPE'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_TYPE_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_TYPE'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <?php echo $this->options['type']; ?>
      </div>
      <div class="jg_clearboth"></div>
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_POSITION'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_POSITION_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_POSITION'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <?php echo $this->options['position']; ?>
      </div>
      <div class="jg_clearboth"></div>
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_LINKED'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_LINKED_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_LINKED'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <?php echo $this->options['linked']; ?>
      </div>
      <div class="jg_clearboth"></div>
      <div id="jg-image-linkedimagetype-options"<?php echo ($this->params->get('default_type', 'thumb') != 'thumb' || $this->params->get('default_linked', 1) != 1 || $this->params->get('openimage') == 0 || ($this->params->get('openimage') == 'default' && $this->_config->get('jg_detailpic_open') == 0)) ? ' class="jg_displaynone"' : ''; ?>>
        <div class="jg_bu_option_key">
          <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_LINKED_IMAGE_TYPE'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_LINKED_IMAGE_TYPE_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_LINKED_IMAGE_TYPE'); ?></span>
        </div>
        <div class="jg_bu_option_value">
          <?php echo $this->options['linked_type']; ?>
        </div>
        <div class="jg_clearboth"></div>
      </div>
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_ALTTEXT'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_ALTTEXT_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_ALTTEXT'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <input type="text" name="jg_bu_alttext" id="jg_bu_alttext" value="<?php echo $this->params->get('default_alttext', ''); ?>" />
      </div>
      <div class="jg_clearboth"></div>
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_ADD_CLASS'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_ADD_CLASS_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_ADD_CLASS'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <input type="text" name="jg_bu_class" id="jg_bu_class" value="<?php echo $this->params->get('default_class', ''); ?>" />
      </div>
      <div class="jg_clearboth"></div>
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_TEXTLINK'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_TEXTLINK_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_TEXTLINK'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <input type="text" name="jg_bu_text" id="jg_bu_text" value="<?php echo $this->params->get('default_linkedtext', ''); ?>" />
      </div>
      <div class="jg_clearboth"></div>
    </div>
  </div>
<?php   echo $this->sliders->endPanel();
      endif;
      echo $this->sliders->startPanel(JText::_('COM_JOOMGALLERY_MINI_SEARCH'), 'param-page-images-search'); ?>
  <script type="text/javascript">
    $('param-page-images-search').addEvent('click', function(){
      $('jg_bu_minis').removeClass('jg_displaynone');
    });
<?php if($this->extended > 0 && (($this->params->get('openimage') != 0 && $this->params->get('openimage') != 'default') || ($this->params->get('openimage') == 'default' && $this->_config->get('jg_detailpic_open') != 0))): ?>
    $$('#jg_bu_typethumb, #jg_bu_typeimg, #jg_bu_typeorig, #jg_bu_linked0, #jg_bu_linked1, #jg_bu_linked2').addEvent('click', function(){
      if($('jg_bu_typethumb').checked && $('jg_bu_linked1').checked)
      {
        $('jg-image-linkedimagetype-options').addClass('jg_displaynone');
        $('jg-image-linkedimagetype-options').removeClass('jg_displaynone');
        $$('#joomgallery-images-sliders div.jpane-slider')[0].setStyle('height', 'auto');
      }
      else
      {
        $('jg-image-linkedimagetype-options').removeClass('jg_displaynone');
        $('jg-image-linkedimagetype-options').addClass('jg_displaynone');
      }
    });
<?php endif; ?>
  </script>
  <div class="joomgallery-slider">
    <div class="jg_bu_search">
      <form action="<?php echo JRoute::_('index.php?view=mini&tmpl=component&e_name='.$this->e_name.'&object='.JRequest::getVar('object')); ?>" name="adminForm" method="post" onsubmit="javascript:ajaxRequest('<?php echo JRoute::_('index.php?view=mini&format=json', false); ?>', 1, 'search=' + this.search.value); return false;">
        <div class="pagination">
          <div id="jg_bu_pagelinks" class="pageslinks">
            <?php echo $this->loadTemplate('pagination'); ?>
          </div>
          <div class="jg_clearboth">
            <span class="jg_bu_limit">
              <?php echo JText::_('Display Num').$this->lists['limit']; ?>
            </span>
            <span class="jg_bu_searchfield">
              <?php echo JText::_('COM_JOOMGALLERY_MINI_SEARCH_IMAGE'); ?>
              <input type="text" name="search" value="<?php echo $this->search; ?>" class="inputbox" />
            </span>
          </div>
        </div>
<?php if($this->extended != -1): ?>
        <div class="jg_bu_filter jg_clearboth">
          <?php echo JText::_('COM_JOOMGALLERY_MINI_FILTER_BY_CATEGORY'); ?>
          <?php echo $this->lists['image_categories']; ?>
        </div>
<?php endif; ?>
      </form>
    </div>
  </div>
<?php echo $this->sliders->endPanel();
      echo $this->sliders->endPane();