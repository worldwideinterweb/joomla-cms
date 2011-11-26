<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<div id="jg-new-popup">
  <div class="m">
    <script type="text/javascript">
      function createConfigRow()
      {
        window.parent.document.id('adminForm').id.value       = document.id('formNew').base.value;
        window.parent.document.id('adminForm').group_id.value = document.id('usergroup').value;
        Joomla.submitform('edit', window.parent.document.id('adminForm'));
      }
    </script>
    <form id="formNew" method="post" action="administrator/index.php">
      <h2><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_NEW_HEADING'); ?></h2>
      <div><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_SELECT_USER_GROUP'); ?></div>
      <div class="jg-configs-usergroups"><?php echo count($this->usergroups) ? JHtml::_('select.genericlist', $this->usergroups, 'usergroup') : JText::_('COM_JOOMGALLERY_CONFIGS_NO_MORE_USER_GROUPS'); ?></div>
      <div><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_SELECT_CONFIG_ROW'); ?></div>
      <div class="jg-configs-config-rows"><?php echo JHtml::_('select.genericlist', $this->items, 'base', null, 'id', 'title'); ?></div>
<?php if(count($this->usergroups)): ?>
      <div class="jg-configs-button-holder">
        <div class="button-holder">
          <div class="button1">
            <div class="next">
              <a onclick="createConfigRow();" href="#"><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_OK'); ?></a>
            </div>
          </div>
        </div>
      </div>
<?php endif; ?>
    </form>
  </div>
</div>