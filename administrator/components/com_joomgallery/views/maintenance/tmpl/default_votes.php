<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php" name="adminFormVotes" method="post" onsubmit="return confirm(Joomla.JText._('COM_JOOMGALLERY_MAIMAN_ALERT_RESET_VOTES_CONFIRM'))">
  <table class="adminlist jg_mnttable">
    <tr>
      <td width="20%" class="center">
        <button type="submit" onclick="document.adminFormVotes.task.value = 'synchronize';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_SYNCHRONIZE_VOTES'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_SYNCHRONIZE_VOTES_LONG'); ?>
      </td>
    </tr>
    <tr>
      <td class="center">
        <button type="submit" onclick="document.adminFormVotes.task.value = 'reset';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_RESET_VOTES'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_RESET_VOTES_LONG'); ?>
      </td>
    </tr>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="votes" />
    <input type="hidden" name="task" value="" />
  </div>
</form>