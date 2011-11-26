<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php" name="adminFormComments" method="post" onsubmit="return confirm(Joomla.JText._('COM_JOOMGALLERY_MAIMAN_CM_ALERT_RESET_COMMENTS_CONFIRM'))">
  <table class="adminlist jg_mnttable">
    <tr>
      <td width="20%" class="center">
        <button type="submit" onclick="document.adminFormComments.task.value = 'synchronize';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CM_SYNCHRONIZE_COMMENTS'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CM_SYNCHRONIZE_COMMENTS_LONG'); ?>
      </td>
    </tr>
    <tr>
      <td class="center">
        <button type="submit" onclick="document.adminFormComments.task.value = 'reset';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CM_RESET_COMMENTS'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CM_RESET_COMMENTS_LONG'); ?>
      </td>
    </tr>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="comments" />
    <input type="hidden" name="task" value="" />
  </div>
</form>