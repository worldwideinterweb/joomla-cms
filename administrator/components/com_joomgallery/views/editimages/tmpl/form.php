<?php defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(task)
{
  if(task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
    <?php echo $this->form->getField('imgtext')->save(); ?>
    Joomla.submitform(task, document.getElementById('adminForm'));
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
    alert(msg.join('\n'));
  }
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
  <div class="width-100">
    <fieldset class="adminform">
      <legend><?php echo JText::_('COM_JOOMGALLERY_FIELDSET_EDITIMAGES'); ?></legend>
      <ul class="adminformlist">
        <li><?php echo $this->form->getLabel('imgtitle'); ?>
        <?php echo $this->form->getInput('imgtitle'); ?>
        <?php echo $this->form->getInput('imgtitlestartcounter'); ?></li>
        <li><?php echo $this->form->getLabel('catid'); ?>
        <?php echo $this->form->getInput('catid'); ?></li>
        <li><?php echo $this->form->getLabel('access'); ?>
        <?php echo $this->form->getInput('access'); ?></li>
      </ul>
      <div class="fltlft"></div>
      <?php echo $this->form->getLabel('imgtext'); ?>
      <div class="fltlft">
        <?php echo $this->form->getInput('imgtext'); ?>
      </div>
      <div class="clr"></div>
      <ul class="adminformlist">
        <li>&nbsp;</li>
        <li><?php echo $this->form->getLabel('owner'); ?>
        <?php echo $this->form->getInput('owner'); ?></li>
        <li><?php echo $this->form->getLabel('imgauthor'); ?>
        <?php echo $this->form->getInput('imgauthor'); ?></li>
        <li><?php echo $this->form->getLabel('published'); ?>
        <?php echo $this->form->getInput('published'); ?></li>
        <li><?php echo $this->form->getLabel('txtclearvotes'); ?>
        <?php echo $this->form->getInput('txtclearvotes'); ?>
        <?php echo $this->form->getInput('clearvotes'); ?></li>
        <li><?php echo $this->form->getLabel('txtclearhits'); ?>
        <?php echo $this->form->getInput('txtclearhits'); ?>
        <?php echo $this->form->getInput('clearhits'); ?></li>
      </ul>
    </fieldset>
  </div>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="images" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cids" value="<?php echo $this->cids; ?>" />
  </div>
</form>
<?php JHtml::_('behavior.formvalidation');
      JHTML::_('behavior.tooltip');
      JHTML::_('joomgallery.credits');