<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php" name="adminFormFavourites" method="post" onsubmit="return confirm(Joomla.JText._('COM_JOOMGALLERY_MAIMAN_FV_ALERT_RESET_FAVOURITES_CONFIRM'))">
  <table class="adminlist jg_mnttable">
    <tr>
      <td width="20%" class="center">
        <button type="submit" onclick="document.adminFormFavourites.task.value = 'synchronize';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_FV_SYNCHRONIZE_FAVOURITES'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_FV_SYNCHRONIZE_FAVOURITES_LONG'); ?>
      </td>
    </tr>
    <tr>
      <td class="center">
        <button type="submit" onclick="document.adminFormFavourites.task.value = 'reset';"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_FV_RESET_FAVOURITES'); ?></button>
      </td>
      <td>
        <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_FV_RESET_FAVOURITES_LONG'); ?>
      </td>
    </tr>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="favourites" />
    <input type="hidden" name="task" value="" />
  </div>
</form>