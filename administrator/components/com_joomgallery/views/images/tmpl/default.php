<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
$saveOrder  = $listOrder == 'a.ordering'; ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
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
                                JHtml::_('select.option', 1, JText::_('COM_JOOMGALLERY_COMMON_OPTION_PUBLISHED_ONLY')),
                                JHtml::_('select.option', 2, JText::_('COM_JOOMGALLERY_COMMON_OPTION_NOT_PUBLISHED_ONLY')),
                                JHtml::_('select.option', 3, JText::_('COM_JOOMGALLERY_COMMON_OPTION_APPROVED_ONLY')),
                                JHtml::_('select.option', 4, JText::_('COM_JOOMGALLERY_COMMON_OPTION_NOT_APPROVED_ONLY')));
              echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.state'), true); ?>
      </select>

      <?php echo JHtml::_('joomselect.categorylist', $this->state->get('filter.category'), 'filter_category', 'class="inputbox" onchange="this.form.submit()"', null, '- ', 'filter'); ?>

      <select name="filter_access" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS'); ?></option>
        <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access')); ?>
      </select>

      <?php echo JHtml::_('joomselect.users', 'filter_owner', $this->state->get('filter.owner'), true, array('' => JText::_('COM_JOOMGALLERY_COMMON_OPTION_SELECT_OWNER')), 'onchange="this.form.submit()"', false, false); ?>

      <select name="filter_type" class="inputbox" onchange="this.form.submit()">
        <?php $options  = array(JHTML::_('select.option', 0, JText::_('COM_JOOMGALLERY_COMMON_OPTION_SELECT_TYPE')),
                                JHTML::_('select.option', 1, JText::_('COM_JOOMGALLERY_COMMON_OPTION_USER_UPLOADED_ONLY')),
                                JHTML::_('select.option', 2, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ADMIN_UPLOADED_ONLY')));
              echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.type'), true); ?>
      </select>

    </div>
  </fieldset>
  <div class="clr"> </div>

  <table class="adminlist">
    <thead>
      <tr>
        <!--<th align="right" width="20">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_NUM'); ?>
        </th>-->
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th width="28"></th>
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_TITLE', 'a.imgtitle', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_APPROVED', 'a.approved', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_CATEGORY', 'category_name', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort',  'COM_JOOMGALLERY_COMMON_REORDER', 'a.ordering', $listDirn, $listOrder); ?>
          <?php if($saveOrder): ?>
            <?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'saveorder'); ?>
          <?php endif; ?>
        </th>
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_ACCESS', 'access_level', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_OWNER', 'a.owner', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_TYPE', 'a.owner', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_AUTHOR', 'a.imgauthor', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_DATE', 'a.imgdate', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_IMGMAN_HITS', 'a.hits', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" class="nowrap">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>
      </tr>
    </thead>
    <tbody>
<?php $disabled = $saveOrder ?  '' : ' disabled="disabled"';
      $display_hidden_asterisk = false;
      $approved_states = array( 1 => array('reject', 'COM_JOOMGALLERY_COMMON_APPROVED', 'COM_JOOMGALLERY_IMGMAN_REJECT_IMAGE', 'COM_JOOMGALLERY_COMMON_APPROVED', false, 'publish', 'publish'),
                                0 => array('approve', 'COM_JOOMGALLERY_COMMON_REJECTED', 'COM_JOOMGALLERY_IMGMAN_APPROVE_IMAGE', 'COM_JOOMGALLERY_COMMON_REJECTED', false, 'unpublish', 'unpublish'));
      foreach($this->items as $i => $item):
        $canEdit    = $this->_user->authorise('core.edit', _JOOM_OPTION.'.image.'.$item->id);
        $canEditOwn = $this->_user->authorise('core.edit.own', _JOOM_OPTION.'.image.'.$item->id) && $item->owner == $this->_user->get('id');
        $canChange  = $this->_user->authorise('core.edit.state', _JOOM_OPTION.'.image.'.$item->id); ?>
      <tr class="row<?php echo $i % 2; ?>">
        <!--<td align="right">
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>-->
        <td class="center">
          <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
          <?php echo JHTML::_('joomgallery.minithumbimg', $item, 'jg_minithumb', $canEdit || $canEditOwn ? 'href="'.JRoute::_('index.php?option='._JOOM_OPTION.'&controller=images&task=edit&cid='.$item->id) : null, true); ?>
        </td>
        <td>
<?php   if($canEdit || $canEditOwn): ?>
            <a href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&controller=images&task=edit&cid='.$item->id);?>">
              <?php echo $this->escape($item->imgtitle); ?></a>
<?php   else: ?>
            <?php echo $this->escape($item->imgtitle); ?>
<?php   endif; ?>
          <p class="smallsub">
            <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.published', $item->published, $i, '', $canChange);
                if($item->published && $item->hidden):
                  echo '<span title="'.JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED_BUT_HIDDEN').'">'.JText::_('COM_JOOMGALLERY_COMMON_HIDDEN_ASTERISK').'</span>';
                  $display_hidden_asterisk = true;
                endif; ?>
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.state', $approved_states, $item->approved, $i, '', $canChange); ?>
        </td>
        <td class="center">
          <?php echo $this->escape($item->category_name); ?>
        </td>
        <td class="order">
<?php   if($canChange):
          if($saveOrder):
            if(strtolower($listDirn) == 'asc'): ?>
          <span><?php echo $this->pagination->orderUpIcon($i, (isset($this->items[$i-1]->catid) && $item->catid == $this->items[$i-1]->catid), 'orderup', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
          <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (isset($this->items[$i+1]->catid) && $item->catid == $this->items[$i+1]->catid), 'orderdown', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
<?php       elseif(strtolower($listDirn) == 'desc'): ?>
          <span><?php echo $this->pagination->orderUpIcon($i, (isset($this->items[$i-1]->catid) && $item->catid == $this->items[$i-1]->catid), 'orderdown', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
          <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (isset($this->items[$i+1]->catid) && $item->catid == $this->items[$i+1]->catid), 'orderup', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
<?php       endif;
          endif; ?>
          <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>"<?php echo $disabled ?> class="text-area-order" />
<?php   else : ?>
          <?php echo $item->ordering; ?>
<?php   endif; ?>
        </td>
        <td class="center">
          <?php echo $this->escape($item->access_level); ?>
        </td>
        <td class="center nowrap">
          <?php echo JHTML::_('joomgallery.displayname', $item->owner); ?>
        </td>
        <td class="center">
          <?php echo JHTML::_('joomgallery.type', $item); ?>
        </td>
        <td class="center">
          <?php echo $item->imgauthor; ?>
        </td>
        <td class="center nowrap">
          <?php echo JHTML::_('date', $item->imgdate, JText::_('DATE_FORMAT_LC4')); ?>
        </td>
        <td class="center">
          <?php echo $item->hits; ?>
        </td>
        <td class="center">
          <?php echo $item->id; ?>
        </td>
      </tr>
<?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="16">
          <?php echo $this->pagination->getListFooter(); ?>
<?php if($display_hidden_asterisk): ?>
          <div align="left">
            <?php echo JText::_('COM_JOOMGALLERY_COMMON_HIDDEN_ASTERISK'); ?> <?php echo JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED_BUT_HIDDEN'); ?>
          </div>
<?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="images" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
<?php JHTML::_('joomgallery.credits');