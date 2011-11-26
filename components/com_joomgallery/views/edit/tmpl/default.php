<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
echo $this->loadTemplate('header'); ?>
  <script language="javascript" type="text/javascript">
  Joomla.submitbutton = function(task)
  {
    if(document.formvalidator.isValid(document.id('adminForm'))) {
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
  <div class="edit">
    <form action = "<?php echo JRoute::_('index.php?task=save'.$this->redirect.$this->slimitstart); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
      <fieldset>
        <legend><?php echo JText::_('COM_JOOMGALLERY_EDIT_EDIT_IMAGE'); ?></legend>
        <div class="formelm">
          <?php echo $this->form->getLabel('imgtitle'); ?>
          <?php echo $this->form->getInput('imgtitle'); ?>
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('alias'); ?>
          <?php echo $this->form->getInput('alias'); ?>
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('imgtext'); ?>
          <?php echo $this->form->getInput('imgtext'); ?>
        </div>
        <div class="jg_clearboth">
          &nbsp;
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('owner'); ?>
          <b><?php echo JHTML::_('joomgallery.displayname', $this->image->owner) ?></b>
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('imagelib'); ?>
          <?php echo $this->form->getInput('imagelib'); ?>
        </div>
      </fieldset>
      <fieldset>
        <legend><?php echo JText::_('COM_JOOMGALLERY_EDIT_PUBLISHING'); ?></legend>
        <div class="formelm">
          <?php echo $this->form->getLabel('catid'); ?>
          <?php echo $this->form->getInput('catid'); ?>
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('imgauthor'); ?>
          <?php echo $this->form->getInput('imgauthor'); ?>
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('published'); ?>
          <?php echo $this->form->getInput('published'); ?>
        </div>
        <div class="formelm">
          <?php echo $this->form->getLabel('access'); ?>
          <?php echo $this->form->getInput('access'); ?>
        </div>
      </fieldset>
      <div class="jg_up_buttons">
        <button type="button" class="button" onclick="Joomla.submitbutton()">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_SAVE'); ?>
        </button>
        <?php $url = !empty($this->redirecturl) ? JRoute::_($this->redirecturl) : JRoute::_('index.php?view=userpanel'.$this->slimitstart, false); ?>
        <button type="button" class="button" onclick="javascript:location.href='<?php echo $url; ?>';">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_CANCEL'); ?>
        </button>
      </div>
      <?php echo $this->form->getInput('id'); ?>
    </form>
  </div>
<?php JHtml::_('behavior.formvalidation');
      JHTML::_('behavior.tooltip');
echo $this->loadTemplate('footer');