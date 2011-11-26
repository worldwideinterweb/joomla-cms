<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php" name="adminFormNametags" method="post" onsubmit="return confirm(Joomla.JText._('COM_JOOMGALLERY_MAIMAN_NT_ALERT_RESET_NAMETAGS_CONFIRM'))">
  <table class="adminlist jg_mnttable">
    <tr>
      <td width="20%" class="center">
        <button type="submit" onclick="document.adminFormNametags.task.value = 'synchronize';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_NT_SYNCHRONIZE'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_NT_SYNCHRONIZE_LONG'); ?>
      </td>
    </tr>
    <tr>
      <td class="center">
        <button type="submit" onclick="document.adminFormNametags.task.value = 'reset';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_NT_RESET'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_NT_RESET_LONG'); ?>
      </td>
    </tr>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="nametags" />
    <input type="hidden" name="task" value="" />
  </div>
</form>