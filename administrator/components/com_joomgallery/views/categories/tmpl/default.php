<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$ordering   = $this->state->get('ordering.array');
$saveOrder  = (($listOrder == 'c.lft' || !$listOrder) && (strtoupper($listDirn) == 'ASC' || !$listDirn) && !$this->state->get('filter.published') && !$this->state->get('filter.search')); ?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
  <fieldset id="filter-bar">
    <div class="filter-search fltlft">
      <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER'); ?></label>
      <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?>" />
      <button type="submit"><?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?></button>
      <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_CLEAR'); ?></button>
    </div>
    <div class="filter-select fltrt">

      <select name="filter_published" class="inputbox" onchange="this.form.submit()">
        <?php $options  = array(JHtml::_('select.option', 0, JText::_('JOPTION_SELECT_PUBLISHED')),
                                JHtml::_('select.option', 1, JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED')),
                                JHtml::_('select.option', 2, JText::_('COM_JOOMGALLERY_CATMAN_NOT_PUBLISHED')));
              echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.published'), true);?>
      </select>

      <select name="filter_access" class="inputbox" onchange="this.form.submit()">
        <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS'); ?></option>
        <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access')); ?>
      </select>

      <select name="filter_type" class="inputbox" onchange="this.form.submit()">
        <?php $options  = array(JHTML::_('select.option', 0, JText::_('COM_JOOMGALLERY_COMMON_OPTION_SELECT_TYPE')),
                                JHTML::_('select.option', 1, JText::_('COM_JOOMGALLERY_CATMAN_OPTION_USERCATEGORIES_ONLY')),
                                JHTML::_('select.option', 2, JText::_('COM_JOOMGALLERY_CATMAN_OPTION_BACKENDCATEGORIES_ONLY')));
              echo JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.type'), true);?>
      </select>

    </div>
  </fieldset>
  <div class="clr"> </div>

  <table class="adminlist">
    <!--<tr>
      <th align="right" width="20">
        <?php echo JText::_('Num'); ?>
      </th>-->
    <thead>
      <tr>
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th width="28"></th>
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_CATEGORY', 'c.name', $listDirn, $listOrder); ?>
        </th>
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_PARENT_CATEGORY', 'c.parent_id', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_PUBLISHED', 'c.published', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_REORDER', 'c.lft', $listDirn, $listOrder); ?>
          <?php if($saveOrder) echo JHTML::_('grid.order', $this->items); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_ACCESS', 'access_level', $listDirn, $listOrder); ?>
        </th>
        <th width="5%" class="nowrap">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_OWNER', 'c.owner', $listDirn, $listOrder); ?>
        </th>
        <th width="5%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_TYPE', 'c.owner', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" class="nowrap">
          <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'c.cid', $listDirn, $listOrder); ?>
        </th>
      </tr>
    </thead>
    <tbody>
<?php $i = 0;
      $disabled = $saveOrder ?  '' : 'disabled="disabled"';
      $display_hidden_asterisk = false;
      $originalOrders = array();
      foreach($this->items as $item):
        $orderkey   = array_search($item->cid, $ordering[$item->parent_id]);
        $canEdit    = $this->_user->authorise('core.edit', _JOOM_OPTION.'.category.'.$item->cid);
        $canEditOwn = $this->_user->authorise('core.edit.own', _JOOM_OPTION.'.category.'.$item->cid) && $item->owner == $this->_user->get('id');
        $canChange  = $this->_user->authorise('core.edit.state', _JOOM_OPTION.'.category.'.$item->cid); ?>
      <tr class="row<?php echo $i % 2; ?>">
        <!--<td>
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>-->
        <td width="20">
          <?php echo JHTML::_('grid.id', $i, $item->cid); ?>
        </td>
        <td>
          <?php echo JHTML::_('joomgallery.minithumbcat', $item, 'jg_minithumb', $canEdit || $canEditOwn ? 'href="'.JRoute::_('index.php?option='._JOOM_OPTION.'&controller=categories&task=edit&cid='.$item->cid) : null, true); ?>
        </td>
        <td>
          <?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1); ?>
<?php if($canEdit || $canEditOwn): ?>
          <a href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&controller=categories&task=edit&cid='.$item->cid); ?>">
            <?php echo $this->escape($item->name); ?></a>
<?php else: ?>
          <?php echo $this->escape($item->name); ?>
<?php endif; ?>
          <p class="smallsub" title="<?php /*echo $this->escape($item->path);*/ ?>">
            <?php echo str_repeat('<span class="gtr">|&mdash;</span>', $item->level - 1); ?>
            <?php echo JText::sprintf('COM_JOOMGALLERY_COMMON_ALIAS_NAME', $this->escape($item->alias)); ?>
          </p>
        </td>
        <td>
          <?php echo JHTML::_('joomgallery.categorypath', $item->cid); ?>
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.published', $item->published, $i, '', $canChange);
                if($item->published && $item->hidden):
                  echo '<span title="'.JText::_('COM_JOOMGALLERY_COMMON_PUBLISHED_BUT_HIDDEN').'">'.JText::_('COM_JOOMGALLERY_COMMON_HIDDEN_ASTERISK').'</span>';
                  $display_hidden_asterisk = true;
                endif; ?>
        </td>
        <td class="order">
<?php if($canChange):
        if($saveOrder): ?>
          <span><?php echo $this->pagination->orderUpIcon($i, isset($ordering[$item->parent_id][$orderkey - 1]), 'orderup', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
          <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, isset($ordering[$item->parent_id][$orderkey + 1]), 'orderdown', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
<?php endif; ?>
          <input type="text" name="order[]" size="5" value="<?php echo $orderkey + 1; ?>" <?php echo $disabled ?> class="text-area-order" />
            <?php $originalOrders[] = $orderkey + 1; ?>
<?php else : ?>
          <?php echo $orderkey + 1; ?>
<?php endif; ?>
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
          <?php echo $item->cid; ?>
        </td>
      </tr>
<?php   $i++;
      endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="11">
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
<?php /*echo $this->loadTemplate('batch');TODO*/ ?>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="categories" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
<?php JHTML::_('joomgallery.credits');