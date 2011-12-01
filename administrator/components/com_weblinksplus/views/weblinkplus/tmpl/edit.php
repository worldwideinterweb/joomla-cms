<?php
/**
 * @version		$Id: edit.php 21529 2011-06-11 22:17:15Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'weblinkplus.cancel' || document.formvalidator.isValid(document.id('weblink-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('weblink-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_weblinksplus&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="weblink-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_WEBLINKSPLUS_NEW_WEBLINK') : JText::sprintf('COM_WEBLINKSPLUS_EDIT_WEBLINK', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?></li>

				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>

				<li><?php echo $this->form->getLabel('url'); ?>
				<?php echo $this->form->getInput('url'); ?></li>
                                
                                <tr>
				<li><label for="article_id">
					<?php echo JText::_( 'Related Article' ); ?>:
				</label>
                                    <div class="fltlft">
				<?php echo $this->articleSelector; ?></div></li>
			

				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?></li>

				<li><?php echo $this->form->getLabel('ordering'); ?>
				<?php echo $this->form->getInput('ordering'); ?></li>

				<li><?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>

			<div>
				<?php echo $this->form->getLabel('description'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('description'); ?>
			</div>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start', 'weblink-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

                <?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_IMAGE'), 'image-details'); ?>
                <fieldset class="panelform">
                  <ul class="adminformlist">
                    <li>
                    	<label class="hasTip" title="Select Thumbnail::Select the image to display as thumbnail.">
												<?php echo JText::_('COM_WEBLINKSPLUS_IMAGE'); ?>:
                      </label>
                      <div style="float:left;">
                      	<div style="float:left;">
	                      	<input class="inputbox" type="text" name="thumbnail" id="thumbnail" size="60" 
	                        	onchange="document.thumbnail_image.src='../'+this.value"
	                          value="<?php echo $this->item->thumbnail; ?>"/>
	                        <div class="button2-left">
	                          <div class="blank">
	                            <a class="modal" title="Imageframe"
																href="index.php?option=com_weblinksplus&view=files&tmpl=component&fieldname=thumbnail&ext=jpg,jpeg,gif,png"
	                              rel="{handler: 'iframe', size: {x: 760, y: 550}}">
	                             Select
	                            </a>
	                          </div>
	                        </div>
	                      </div>
                       	<div style="clear: both;"><?php echo JText::_('COM_WEBLINKSPLUS_AUTOTHUMBNAIL'); ?></div>
												
		                    <img src="../<?php echo $this->item->thumbnail; ?>" name="thumbnail_image" id="thumbnail_image" border="2" alt="Preview" style="width: 350px !important;"/>
                      </div>
                    </li>
                  </ul>
                </fieldset>
            
		<?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_PUBLISHING'), 'publishing-details'); ?>

		<fieldset class="panelform">
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>

				<li><?php echo $this->form->getLabel('created_by_alias'); ?>
				<?php echo $this->form->getInput('created_by_alias'); ?></li>

				<li><?php echo $this->form->getLabel('created'); ?>
				<?php echo $this->form->getInput('created'); ?></li>

				<li><?php echo $this->form->getLabel('publish_up'); ?>
				<?php echo $this->form->getInput('publish_up'); ?></li>

				<li><?php echo $this->form->getLabel('publish_down'); ?>
				<?php echo $this->form->getInput('publish_down'); ?></li>

				<?php if ($this->item->modified_by) : ?>
					<li><?php echo $this->form->getLabel('modified_by'); ?>
					<?php echo $this->form->getInput('modified_by'); ?></li>

					<li><?php echo $this->form->getLabel('modified'); ?>
					<?php echo $this->form->getInput('modified'); ?></li>
				<?php endif; ?>

				<?php if ($this->item->hits) : ?>
					<li><?php echo $this->form->getLabel('hits'); ?>
					<?php echo $this->form->getInput('hits'); ?></li>
				<?php endif; ?>

			</ul>
		</fieldset>

		<?php echo $this->loadTemplate('params'); ?>

		<?php echo $this->loadTemplate('metadata'); ?>

		<?php echo JHtml::_('sliders.end'); ?>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>