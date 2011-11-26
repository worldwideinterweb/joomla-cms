<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<table class="adminlist">
  <tr>
    <th class="center" colspan="2">
      <?php echo JText::_(count($this->files) ? 'COM_JOOMGALLERY_MIGMAN_NOTE' : 'COM_JOOMGALLERY_MIGMAN_NOTE_NO_SCRIPTS'); ?>
    </th>
  </tr>
<?php $show_jmtablerow = true;
      foreach($this->files as $file):
        require_once $file;
      endforeach; ?>
</table>
<?php JHtml::_('joomgallery.credits');
      JHtml::_('behavior.tooltip');
      JHtml::_('behavior.formvalidation');