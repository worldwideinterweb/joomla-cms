<?php defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(task)
{
  if(task == 'cancel' || task == 'resethits' || task == 'resetvotes' || document.formvalidator.isValid(document.id('adminform'))) {
    <?php echo $this->form->getField('imgtext')->save(); ?>
    Joomla.submitform(task, document.getElementById('adminform'));
  }
  else {
    var msg = new Array();
    msg.push('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
    if(document.adminForm.imgtitle.hasClass('invalid')) {
        msg.push('<?php echo $this->escape(JText::_("COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE"));?>');
    }
    if(document.adminForm.catid.hasClass('invalid')) {
      msg.push('<?php echo $this->escape(JText::_("COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY"));?>');
    }
    if(document.adminForm.imgfilename && document.adminForm.imgfilename.hasClass('invalid')) {
      msg.push('<?php echo $this->escape(JText::_("COM_JOOMGALLERY_IMGMAN_ALERT_SELECT_IMAGE_FILENAME"));?>');
    }
    if(document.adminForm.imgthumbname && document.adminForm.imgthumbname.hasClass('invalid')) {
      msg.push('<?php echo $this->escape(JText::_("COM_JOOMGALLERY_IMGMAN_ALERT_SELECT_THUMBNAIL_FILENAME"));?>');
    }
    alert(msg.join('\n'));
  }
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminform" enctype="multipart/form-data">
  <div class="width-60 fltlft">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_JOOMGALLERY_FIELDSET_IMAGE'); ?></legend>
      <ul class="adminformlist">
        <li><?php echo $this->form->getLabel('imgtitle'); ?>
        <?php echo $this->form->getInput('imgtitle'); ?></li>
        <li><?php echo $this->form->getLabel('alias'); ?>
        <?php echo $this->form->getInput('alias'); ?></li>
        <li><?php echo $this->form->getLabel('catid'); ?>
        <?php echo $this->form->getInput('catid'); ?></li>
        <li><?php echo $this->form->getLabel('published'); ?>
        <?php echo $this->form->getInput('published'); ?></li>
        <li><?php echo $this->form->getLabel('access'); ?>
        <?php echo $this->form->getInput('access'); ?></li>
<?php if($this->_user->authorise('core.admin', _JOOM_OPTION.'.image.'.$this->item->id)): ?>
        <li><span class="faux-label"><?php echo JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ?></span>
          <div class="button2-left">
            <div class="blank">
              <button type="button" onclick="document.location.href='#access-rules';">
                <?php echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ?></button>
            </div>
          </div>
        </li>
<?php endif; ?>
<?php if($this->isNew): ?>
        <li><?php echo $this->form->getLabel('detail_catid'); ?>
        <?php echo $this->form->getInput('detail_catid'); ?></li>
        <li><?php echo $this->form->getLabel('imgfilename'); ?>
        <?php echo $this->form->getInput('imgfilename'); ?></li>
        <li><?php echo $this->form->getLabel('original_exists'); ?>
        <?php echo $this->form->getInput('original_exists'); ?></li>
        <li><?php echo $this->form->getLabel('copy_original'); ?>
        <?php echo $this->form->getInput('copy_original'); ?></li>
        <li><?php echo $this->form->getLabel('thumb_catid'); ?>
        <?php echo $this->form->getInput('thumb_catid'); ?></li>
        <li><?php echo $this->form->getLabel('imgthumbname'); ?>
        <?php echo $this->form->getInput('imgthumbname'); ?></li>
<?php endif; ?>
      </ul>
      <div class="clr"></div>
      <?php echo $this->form->getLabel('imgtext'); ?>
      <div class="clr"></div>
      <?php echo $this->form->getInput('imgtext'); ?>
      <div class="clr"></div>
      <div class="fltlft">
        <?php echo $this->form->getLabel('imagelib'); ?><br />
        <?php echo $this->form->getInput('imagelib'); ?>
     </div>
     <div class="fltrt">
        <?php echo $this->form->getLabel('imagelib2'); ?><br />
        <?php echo $this->form->getInput('imagelib2'); ?>
     </div>
    </fieldset>
  </div>
<?php if(!$this->isNew): ?>
  <div class="width-40 fltrt">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_JOOMGALLERY_FIELDSET_IMAGE_IMMUTABLE'); ?></legend>
        <ul class="adminformlist">
          <li><?php echo $this->form->getLabel('id'); ?>
          <?php echo $this->form->getInput('id'); ?></li>
          <li><?php echo $this->form->getLabel('publishhiddenstate'); ?>
          <?php echo $this->form->getInput('publishhiddenstate'); ?></li>
          <li><?php echo $this->form->getLabel('hits'); ?>
            <div class="fltlft">
              <?php echo $this->form->getInput('hits'); ?>
            </div>
<?php if($this->item->hits): ?>
            <div class="button2-left">
              <div class="blank">
                <button name="reset_hits" type="button" onclick="Joomla.submitbutton('resethits');">
                  <?php echo JText::_('COM_JOOMGALLERY_IMGMAN_RESET_IMAGE_HITS'); ?>
                </button>
              </div>
            </div>
<?php endif; ?>
          </li>
          <li><?php echo $this->form->getLabel('rating'); ?>
            <div class="fltlft">
              <?php echo $this->form->getInput('rating'); ?>
            </div>
<?php if($this->item->imgvotes): ?>
            <div class="button2-left">
              <div class="blank">
                <button name="reset_votes" type="button" onclick="Joomla.submitbutton('resetvotes');">
                  <?php echo JText::_('COM_JOOMGALLERY_IMGMAN_RESET_IMAGE_VOTES'); ?>
                </button>
              </div>
            </div>
<?php endif; ?>
          </li>
          <li><?php echo $this->form->getLabel('date'); ?>
          <?php echo $this->form->getInput('date'); ?></li>
        </ul>
    </fieldset>
  </div>
<?php endif; ?>
  <div class="width-40 fltrt">
    <?php echo  JHtml::_('sliders.start', 'image-slider'); ?>
      <?php echo JHtml::_('sliders.panel', JText::_('COM_JOOMGALLERY_COMMON_PARAMETERS'), 'details-page'); ?>
      <fieldset class="panelform">
        <ul class="adminformlist">
          <li><?php echo $this->form->getLabel('owner'); ?>
          <?php echo $this->form->getInput('owner'); ?></li>
          <li><?php echo $this->form->getLabel('imgauthor'); ?>
          <?php echo $this->form->getInput('imgauthor'); ?></li>
          <li><?php echo $this->form->getLabel('hidden'); ?>
          <?php echo $this->form->getInput('hidden'); ?></li>
        </ul>
      </fieldset>
<?php if($this->additional_params): ?>
      <?php echo $this->loadTemplate('params'); ?>
<?php endif ?>
<?php if(!$this->isNew): ?>
      <?php echo JHtml::_('sliders.panel', JText::_('COM_JOOMGALLERY_IMGMAN_REPLACE_FILES'), 'files-page'); ?>
      <fieldset class="panelform">
        <ul class="adminformlist">
          <li><?php echo $this->form->getLabel('spacer', 'files'); ?>
          <?php echo $this->form->getInput('spacer', 'files'); ?></li>
          <li><?php echo $this->form->getLabel('thumb', 'files'); ?>
          <?php echo $this->form->getInput('thumb', 'files'); ?></li>
          <li><?php echo $this->form->getLabel('img', 'files'); ?>
          <?php echo $this->form->getInput('img', 'files'); ?></li>
          <li><?php echo $this->form->getLabel('orig', 'files'); ?>
          <?php echo $this->form->getInput('orig', 'files'); ?></li>
        </ul>
      </fieldset>
<?php endif ?>
      <?php echo JHtml::_('sliders.panel', JText::_('COM_JOOMGALLERY_COMMON_METADATA_INFORMATION'), 'metadata-page'); ?>
      <fieldset class="panelform">
        <ul class="adminformlist">
          <li><?php echo $this->form->getLabel('metadesc'); ?>
          <?php echo $this->form->getInput('metadesc'); ?></li>
          <li><?php echo $this->form->getLabel('metakey'); ?>
          <?php echo $this->form->getInput('metakey'); ?></li>
        </ul>
      </fieldset>
    <?php echo JHtml::_('sliders.end'); ?>
  </div>
  <div class="clr"></div>
<?php if($this->_user->authorise('core.admin', _JOOM_OPTION.'.image.'.$this->item->id)): ?>
  <div  class="width-100 fltlft">
    <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie' =>1 )); ?>
    <?php echo JHtml::_('sliders.panel', JText::_('COM_JOOMGALLERY_FIELDSET_IMAGE_RULES'), 'access-rules'); ?>
      <fieldset class="panelform">
        <?php echo $this->form->getLabel('rules'); ?>
        <?php echo $this->form->getInput('rules'); ?>
      </fieldset>
    <?php echo JHtml::_('sliders.end'); ?>
  </div>
<?php endif; ?>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="images" />
    <input type="hidden" name="task" value="new" />
    <input type="hidden" name="cid" value="<?php echo $this->item->id; ?>" />
  </div>
</form>
<?php JHtml::_('behavior.formvalidation');
      JHTML::_('behavior.keepalive');
      JHTML::_('behavior.tooltip');
      JHTML::_('joomgallery.credits');