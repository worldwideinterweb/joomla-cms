<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<table>
  <tr>
    <td>
      <p>
        <?php echo ($this->edit)? JText::_('COM_JOOMGALLERY_CSSMAN_EDIT_CSS_EXPLANATION') : JText::_('COM_JOOMGALLERY_CSSMAN_NEW_CSS_EXPLANATION'); ?>      
      </p>
    </td>
  </tr>
</table>
<form action="index.php" name="adminForm" method="post">
  <table class="adminform">
    <tbody>
      <tr>
        <th><?php echo $this->file ?></th>
      </tr>
      <tr>
        <td>
          <textarea cols="110" rows="25" name="csscontent" class="inputbox"><?php echo $this->content ?></textarea>
        </td>
      </tr>
    </tbody>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>">
    <input type="hidden" name="controller" value="cssedit" />
    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="1" />
  </div>
</form>
<?php JHTML::_('joomgallery.credits');