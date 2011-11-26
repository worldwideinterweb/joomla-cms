<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction')); ?>
<form action="index.php" method="post" name="adminForm">
  <fieldset id="filter-bar">
    <div class="filter-search fltlft">
      <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER'); ?></label>
      <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?>" />
      <button type="submit" class="btn"><?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?></button>
      <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_CLEAR'); ?></button>
    </div>
    <div class="filter-select fltrt">
      <select name="filter_state" class="inputbox" onchange="this.form.submit()">
        <?php $options  = array(JHtml::_('select.option', 0, JText::_('JOPTION_SELECT_PUBLISHED')),
                                JHtml::_('select.option', 1, JText::_('COM_JOOMGALLERY_COMMAN_OPTION_PUBLISHED_ONLY')),
                                JHtml::_('select.option', 2, JText::_('COM_JOOMGALLERY_COMMAN_OPTION_NOT_PUBLISHED_ONLY')),
                                JHtml::_('select.option', 3, JText::_('COM_JOOMGALLERY_COMMAN_OPTION_APPROVED_ONLY')),
                                JHtml::_('select.option', 4, JText::_('COM_JOOMGALLERY_COMMAN_OPTION_NOT_APPROVED_ONLY')));
              echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.state'), true); ?>
      </select>
    </div>
  </fieldset>
  <div class="clr"> </div>
  <table class="adminlist">
    <thead>
      <tr>
        <!--<th align="right" width="10">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_NUM'); ?>
        </th>-->
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th width="28"></th>
        <th class="left" width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_AUTHOR', 'user', $listDirn, $listOrder); ?>
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMAN_TEXT', 'c.cmttext', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_PUBLISHED', 'c.published', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_APPROVED', 'c.approved', $listDirn, $listOrder); ?>
        </th>
        <th class="left" width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_IMAGE', 'i.imgtitle', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMAN_IP', 'c.cmtip', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_DATE', 'c.cmtdate', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" class="nowrap">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_ID', 'c.cmtid', $listDirn, $listOrder); ?>
        </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="10">
          <?php echo $this->pagination->getListFooter(); ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
<?php $approved_states = array( 1 => array('reject', 'COM_JOOMGALLERY_COMMON_APPROVED', 'COM_JOOMGALLERY_COMMAN_REJECT_COMMENT', 'COM_JOOMGALLERY_COMMON_APPROVED', false, 'publish', 'publish'),
                                0 => array('approve', 'COM_JOOMGALLERY_COMMON_REJECTED', 'COM_JOOMGALLERY_COMMAN_APPROVE_COMMENT', 'COM_JOOMGALLERY_COMMON_REJECTED', false, 'unpublish', 'unpublish'));
      foreach($this->items as $i => $item):
        $canEdit    = $this->_user->authorise('core.edit', _JOOM_OPTION.'.image.'.$item->cmtpic);
        $canEditOwn = $this->_user->authorise('core.edit.own', _JOOM_OPTION.'.image.'.$item->cmtpic) && $item->owner == $this->_user->get('id'); ?>
      <tr class="row<?php echo $i % 2; ?>">
        <!--<td align="right">
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>-->
        <td>
          <?php echo JHtml::_('grid.id', $i, $item->cmtid); ?>
        </td>
        <td>
          <?php echo JHTML::_('joomgallery.minithumbimg', $item, 'jg_minithumb', $canEdit || $canEditOwn ? 'href="index.php?option='._JOOM_OPTION.'&amp;controller=images&amp;task=edit&amp;cid='.$item->cmtpic : null, true); ?>
        </td>
        <td>
          <?php echo $item->cmtname; ?>
        </td>
        <td>
          <?php echo $item->cmttext; ?>
        </td>
        <td class="center">
          <?php echo JHtml::_('jgrid.published', $item->published, $i); ?>
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.state', $approved_states, $item->approved, $i); ?>
        </td>
        <td width="10%">
<?php   if($canEdit || $canEditOwn): ?>
          <a href="index.php?option=<?php echo _JOOM_OPTION; ?>&amp;controller=images&amp;task=edit&amp;cid=<?php echo $item->cmtpic; ?>">
            <?php echo $this->escape($item->imgtitle); ?>
          </a>
<?php   else: ?>
          <?php echo $this->escape($item->imgtitle); ?>
<?php   endif; ?>
        </td>
        <td class="center">
          <?php echo $item->cmtip; ?>
        </td>
        <td width="10%" class="center">
          <?php echo JHtml::_('date', $item->cmtdate, JText::_('DATE_FORMAT_LC4')); ?>
        </td>
        <td>
          <?php echo $item->cmtid; ?>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION;?>" />
    <input type="hidden" name="controller" value="comments" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
<?php JHTML::_('joomgallery.credits');