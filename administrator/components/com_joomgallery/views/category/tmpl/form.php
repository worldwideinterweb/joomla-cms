<?php defined('_JEXEC') or die('Restricted access');?>
<script language="javascript" type="text/javascript">
  Joomla.submitbutton = function(task)
  {
    if (task == 'cancel' || document.formvalidator.isValid(document.id('adminform'))) {
      <?php echo $this->form->getField('description')->save(); ?>
      Joomla.submitform(task, document.getElementById('adminform'));
    }
    else {
      var msg = new Array();
      msg.push('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
      if (document.adminForm.name.hasClass('invalid')) {
          msg.push('<?php echo $this->escape(JText::_('COM_JOOMGALLERY_CATMAN_ALERT_CATEGORY_MUST_HAVE_TITLE'));?>');
      }
      alert(msg.join('\n'));
    }
  }
</script>
<form action="index.php" method="post" name="adminForm" id="adminform">
  <div class="width-60 fltlft">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_JOOMGALLERY_FIELDSET_CATEGORY'); ?></legend>
      <ul class="adminformlist">
        <li><?php echo $this->form->getLabel('name'); ?>
        <?php echo $this->form->getInput('name'); ?></li>
        <li><?php echo $this->form->getLabel('alias'); ?>
        <?php echo $this->form->getInput('alias'); ?></li>
        <li><?php echo $this->form->getLabel('parent_id'); ?>
        <?php echo $this->form->getInput('parent_id'); ?></li>
        <li><?php echo $this->form->getLabel('published'); ?>
        <?php echo $this->form->getInput('published'); ?></li>
        <li><?php echo $this->form->getLabel('access'); ?>
        <?php echo $this->form->getInput('access'); ?></li>
<?php if($this->_user->authorise('core.admin', _JOOM_OPTION.'.category.'.$this->item->cid)): ?>
        <li><span class="faux-label"><?php echo JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ?></span>
          <div class="button2-left">
            <div class="blank">
              <button type="button" onclick="document.location.href='#access-rules';">
                <?php echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ?></button>
            </div>
          </div>
        </li>
<?php endif; ?>
      </ul>
      <div class="clr"></div>
      <?php echo $this->form->getLabel('description'); ?>
      <div class="clr"></div>
      <?php echo $this->form->getInput('description'); ?>
      <div class="clr"></div>
      <?php echo $this->form->getLabel('imagelib'); ?>
      <div class="clr"></div>
      <?php echo $this->form->getInput('imagelib'); ?>
    </fieldset>
  </div>
<?php if(!$this->isNew): ?>
  <div class="width-40 fltrt">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_JOOMGALLERY_FIELDSET_CATEGORY_IMMUTABLE'); ?></legend>
        <ul class="adminformlist">
          <li><?php echo $this->form->getLabel('cid'); ?>
          <?php echo $this->form->getInput('cid'); ?></li>
          <li><?php echo $this->form->getLabel('publishhiddenstate'); ?>
          <?php echo $this->form->getInput('publishhiddenstate'); ?></li>
<?php   if($this->form->getValue('notice')): ?>
          <li><?php echo $this->form->getLabel('notice'); ?>
          <span id="notice"><?php echo $this->form->getValue('notice'); ?></span></li>
<?php   endif; ?>
        </ul>
    </fieldset>
  </div>
<?php endif; ?>
  <div class="width-40 fltrt">
    <?php echo  JHtml::_('sliders.start', 'category-slider'); ?>
      <?php echo JHtml::_('sliders.panel', JText::_('COM_JOOMGALLERY_COMMON_PARAMETERS'), 'details-page'); ?>
      <fieldset class="panelform">
        <ul class="adminformlist">
          <li><?php echo $this->form->getLabel('owner'); ?>
          <?php echo $this->form->getInput('owner'); ?></li>
          <li><?php echo $this->form->getLabel('hidden'); ?>
          <?php echo $this->form->getInput('hidden'); ?></li>
          <li><?php echo $this->form->getLabel('ordering'); ?>
          <?php echo $this->form->getInput('ordering'); ?></li>
          <li><?php echo $this->form->getLabel('thumbnail'); ?>
          <?php echo $this->form->getInput('thumbnail'); ?></li>
          <li><?php echo $this->form->getLabel('img_position'); ?>
          <?php echo $this->form->getInput('img_position'); ?></li>
        </ul>
      </fieldset>
<?php if($this->additional_params): ?>
      <?php echo $this->loadTemplate('params'); ?>
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
<?php if($this->_user->authorise('core.admin', _JOOM_OPTION.'.category.'.$this->item->cid)): ?>
  <div  class="width-100 fltlft">
    <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->cid, array('useCookie' =>1 )); ?>
    <?php echo JHtml::_('sliders.panel', JText::_('COM_JOOMGALLERY_FIELDSET_CATEGORY_RULES'), 'access-rules'); ?>
      <fieldset class="panelform">
        <?php echo $this->form->getLabel('rules'); ?>
        <?php echo $this->form->getInput('rules'); ?>
      </fieldset>
    <?php echo JHtml::_('sliders.end'); ?>
  </div>
<?php endif; ?>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="categories" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid" value="<?php echo $this->item->cid; ?>" />
  </div>
</form>
<?php JHtml::_('behavior.formvalidation');
      JHTML::_('behavior.keepalive');
      JHTML::_('behavior.tooltip');
      JHTML::_('joomgallery.credits');