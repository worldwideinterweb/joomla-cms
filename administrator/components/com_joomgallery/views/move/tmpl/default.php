<?php

defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<form action="index.php" method="post" name="adminForm" >
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td align="center">
        <b><?php echo JText::_('COM_JOOMGALLERY_IMGMAN_MOVE_IMAGE_TO_CATEGORY'); ?></b><?php echo $this->lists['cats']; ?>
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
    <tr>
      <td align="left" valign="top" width="20%">
        <strong>
          <?php echo JText::_('COM_JOOMGALLERY_IMGMAN_IMAGES_TO_MOVE'); ?>
        </strong>
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
    <tr>
      <th width="24"></th>
      <th class="title" width="40%">
        <?php echo JText::_('COM_JOOMGALLERY_COMMON_TITLE'); ?>
      </th>
      <th align="left">
        <?php echo JText::_('COM_JOOMGALLERY_IMGMAN_PREVIOUS_CATEGORY'); ?>
      </th>
    </tr>
<?php foreach($this->items as $row): ?>
    <tr>
      <td>
        <img src="<?php echo $this->_ambit->getImg('thumb_url', $row); ?>" border="0" width="24" height="24" alt="Thumb" />
      </td>
      <td align="left">
        <?php echo $row->imgtitle; ?>
        <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
      </td>
      <td align="left">
        <?php echo JHTML::_('joomgallery.categorypath', $row->catid); ?>
      </td>
    </tr>
<?php endforeach; ?>
    <tr>
      <th align="center" colspan="3">
      </th>
    </tr>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="images" />
    <input type="hidden" name="task" value="savemove" />
    <input type="hidden" name="boxchecked" value="1" />
  </div>
</form>
<?php JHTML::_('joomgallery.credits');
