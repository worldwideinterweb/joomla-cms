<?php
/**
 * @version		$Id: edit.php 21321 2011-05-11 01:05:59Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'weblinkplus.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
<?php echo $this->form->getField('description')->save(); ?>
            Joomla.submitform(task);
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>
<div class="edit<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->def('show_page_heading', 1)) : ?>
        <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    <?php endif; ?>
    <form action="<?php echo JRoute::_('index.php?option=com_weblinksplus&view=form&w_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
        <fieldset>
            <legend><?php echo JText::_('COM_WEBLINKSPLUS_LINK'); ?></legend>

            <div class="formelm">
                <?php echo $this->form->getLabel('title'); ?>
                <?php echo $this->form->getInput('title'); ?>
            </div>

            <div class="formelm">
                <?php echo $this->form->getLabel('alias'); ?>
                <?php echo $this->form->getInput('alias'); ?>
            </div>

            <div class="formelm">
                <?php echo $this->form->getLabel('catid'); ?>
                <?php echo $this->form->getInput('catid'); ?>
            </div>
            <div class="formelm">
                <?php echo $this->form->getLabel('url'); ?>
                <?php echo $this->form->getInput('url'); ?>
            </div>
            <div class="formelm">
                <label for="jform_article_id">
                    <?php echo JText::_('Related Article'); ?>:
                </label>
                <?php echo $this->articleSelector; ?>
            </div>


            <div class="formelm">
                <label for="jform_Image" style="float:left">
                    <?php echo JText::_('COM_WEBLINKSPLUS_IMAGE'); ?>:
                </label>
                <div class="button2-left">
                    <input name="thumbnail" id="thumbnail" type=file size="50" />
                </div>
                </div>
            <div style="clear:both"></div>

            <?php if ($this->user->authorise('core.edit.state', 'com_weblinksplus.weblinkplus')): ?>
                <div class="formelm">
                    <?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?>
                </div>
            <?php endif; ?>
            <div class="formelm">
                <?php echo $this->form->getLabel('language'); ?>
                <?php echo $this->form->getInput('language'); ?>
            </div>
            <div class="formelm-buttons">
                <button type="button" onclick="Joomla.submitbutton('weblinkplus.save')">
                    <?php echo JText::_('JSAVE') ?>
                </button>
                <button type="button" onclick="Joomla.submitbutton('weblinkplus.cancel')">
                    <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
            <div>
                <?php echo $this->form->getLabel('description'); ?>
                <?php echo $this->form->getInput('description'); ?>
            </div>
        </fieldset>

        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>