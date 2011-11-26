<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php" name="adminFormDatabase" method="post">
  <table class="adminlist jg_mnttable">
    <tr>
      <td width="20%" class="center">
        <button type="submit" onclick="document.adminFormDatabase.task.value = 'optimize';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_DB_OPTIMIZE'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_DB_OPTIMIZE_LONG'); ?> 
      </td>
    </tr>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="maintenance" />
    <input type="hidden" name="task" value="" />
  </div>
</form>