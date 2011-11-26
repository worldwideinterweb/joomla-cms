<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="gallery minigallery">
  <div class="jg_header" style="display:none;">
    <?php echo JText::_('COM_JOOMGALLERY_PLUGIN_MINI_INTRO'); ?>
  </div>
<?php echo $this->tabs->startPane('joomgallery-tabs');
      echo $this->tabs->startPanel(JText::_('COM_JOOMGALLERY_PLUGIN_MINI_INSERT_IMAGE'), 'param-page-images');
      echo $this->loadTemplate('images');
      echo $this->tabs->endPanel();
      if($this->extended > 0):
        echo $this->tabs->startPanel(JText::_('COM_JOOMGALLERY_PLUGIN_MINI_INSERT_CATEGORY'), 'param-page-categories');
        echo $this->loadTemplate('categories');
        echo $this->tabs->endPanel();
        echo $this->tabs->startPanel(JText::_('COM_JOOMGALLERY_PLUGIN_MINI_UPLOAD_IMAGE'), 'param-page-upload');
        echo $this->loadTemplate('upload');
        echo $this->tabs->endPanel();
        echo $this->tabs->startPanel(JText::_('COM_JOOMGALLERY_MINI_CREATE_CATEGORY'), 'param-page-createcategory');
        echo $this->loadTemplate('createcategory');
        echo $this->tabs->endPanel();
      endif;
      echo $this->tabs->endPane(); ?>
  <div id="jg_bu_minis" class="jg_bu_minis test<?php echo $this->extended > 0 ? ' jg_displaynone' : ''; ?>">
<?php echo $this->loadTemplate('minis'); ?>
  </div>
</div>