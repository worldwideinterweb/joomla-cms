<?php defined('_JEXEC') or die('Restricted access'); ?>
  <div class="jg-slider">
    <div class="jg_bu_extended_options">
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_MODE'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_MODE_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_MODE'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <?php echo $this->options['category']; ?>
      </div>
      <div class="jg_clearboth"></div>
      <hr />
      <div id="jg-category-thumbnails-options"<?php echo $this->params->get('default_category_mode', 0) == 1 ? ' class="jg_displaynone"' : ''; ?>>
        <div class="jg_bu_option_key">
          <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_LIMIT'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_LIMIT_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_LIMIT'); ?></span>
        </div>
        <div class="jg_bu_option_value">
          <input type="text" name="jg_bu_thumbnail_number" id="jg_bu_thumbnail_number" value="<?php echo $this->params->get('default_category_limit', 4); ?>" size="4" maxlength="4" />
        </div>
        <div class="jg_clearboth"></div>
        <div class="jg_bu_option_key">
          <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_COLUMNS'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_COLUMNS_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_COLUMNS'); ?></span>
        </div>
        <div class="jg_bu_option_value">
          <input type="text" name="jg_bu_thumbnail_columns" id="jg_bu_thumbnail_columns" value="<?php echo $this->params->get('default_category_columns', 2); ?>" size="4" maxlength="2" />
        </div>
        <div class="jg_clearboth"></div>
        <div class="jg_bu_option_key">
          <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_ORDERING'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_ORDERING_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_ORDERING'); ?></span>
        </div>
        <div class="jg_bu_option_value">
          <?php echo $this->options['ordering']; ?>
        </div>
        <div class="jg_clearboth"></div>
      </div>
      <div id="jg-category-linkedtext-options"<?php echo !$this->params->get('default_category_mode', 0) ? ' class="jg_displaynone"' : ''; ?>>
        <div class="jg_bu_option_key">
          <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_TEXTLINK'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_TEXTLINK_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_TEXTLINK'); ?></span>
        </div>
        <div class="jg_bu_option_value">
          <input type="text" name="jg_bu_category_linkedtext" id="jg_bu_category_linkedtext" value="<?php echo $this->params->get('default_category_linkedtext', ''); ?>" />
        </div>
        <div class="jg_clearboth"></div>
      </div>
      <hr />
      <div class="jg_bu_option_key">
        <span title="<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_SELECT'); ?>::<?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_SELECT_TIP'); ?>" class="hasTip"><?php echo JText::_('COM_JOOMGALLERY_MINI_CATEGORY_SELECT'); ?></span>
      </div>
      <div class="jg_bu_option_value">
        <?php echo $this->lists['category_categories']; ?>
      </div>
      <div class="jg_clearboth"></div>
    </div>
  </div>
  <script type="text/javascript">
    $('jg_bu_category0').addEvent('click', function(){
      $('jg-category-linkedtext-options').addClass('jg_displaynone');
      $('jg-category-thumbnails-options').removeClass('jg_displaynone');
    });
    $('jg_bu_category1').addEvent('click', function(){
      $('jg-category-linkedtext-options').removeClass('jg_displaynone');
      $('jg-category-thumbnails-options').addClass('jg_displaynone');
    });
  </script>