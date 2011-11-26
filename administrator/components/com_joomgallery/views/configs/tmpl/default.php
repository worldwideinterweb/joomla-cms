<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$saveOrder  = (($listOrder == 'c.ordering' || !$listOrder) && (strtoupper($listDirn) == 'ASC' || !$listDirn) && !$this->state->get('filter.published') && !$this->state->get('filter.search')); ?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
  <fieldset id="filter-bar">
    <div class="filter-search fltlft">
      <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER'); ?></label>
      <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?>" />
      <button type="submit"><?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?></button>
      <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_CLEAR'); ?></button>
    </div>
    <div class="filter-select fltrt">
    </div>
  </fieldset>
  <div class="clr"> </div>

  <table class="adminlist">
    <thead>
      <!--<tr>
      <th align="right" width="20">
        <?php echo JText::_('Num'); ?>
      </th>-->
      <tr>
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_CONFIGS_TITLE', 'g.title', $listDirn, $listOrder); ?>
        </th>
        <th class="left">
          <span title="<?php echo JText::_('COM_JOOMGALLERY_CONFIGS_GROUPS').'::'.JText::_('COM_JOOMGALLERY_CONFIGS_GROUPS_TIP'); ?>" class="hasTip">
            <?php echo JText::_('COM_JOOMGALLERY_CONFIGS_GROUPS'); ?>
          </span>
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_CONFIGS_GROUP', 'g.lft', $listDirn, $listOrder); ?>
        </th>
        <th width="10%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_REORDER', 'c.ordering', $listDirn, $listOrder); ?>
          <?php if($saveOrder) echo JHTML::_('grid.order', $this->items); ?>
        </th>
        <th width="1%" class="nowrap">
          <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'c.id', $listDirn, $listOrder); ?>
        </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="6">
          <?php echo $this->pagination->getListFooter(); ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
<?php $i = 0;
      foreach($this->items as $item): ?>
      <tr class="row<?php echo $i % 2; ?>">
        <!--<td>
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>-->
        <td width="20">
          <?php echo JHTML::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
<?php   if($item->title): ?>
          <?php /*echo str_repeat('<span class="gi">|&mdash;</span>', $item->level);*/ ?>
          <a href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&controller=config&task=edit&id='.$item->id); ?>">
            <?php echo $item->id == 1 ? '<i>'.JText::sprintf('COM_JOOMGALLERY_CONFIGS_DEFAULT_TITLE', $this->escape($item->title)).'</i>' : $this->escape($item->title); ?></a>
<?php   else: ?>
          <?php echo JText::_('COM_JOOMGALLERY_CONFIGS_MISSING'); ?>
<?php   endif; ?>
        </td>
        <td>
<?php   if($item->usergroups && $item->title): ?>
          <ul>
<?php     foreach($item->usergroups as $group): ?>
            <li><?php echo $group; ?></li>
<?php     endforeach; ?>
          </ul>
<?php   else:
          if($item->title): ?>
          <img src="<?php echo $this->_ambit->getIcon('error.png'); ?>" alt="Warning" /> <?php echo JText::_('COM_JOOMGALLERY_CONFIGS_BAD_ORDERING'); ?>
<?php     endif;
        endif; ?>
        </td>
        <td>
<?php   if($item->title): ?>
          <?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level); ?>
          <!--<a href="<?php echo JRoute::_('index.php?option='._JOOM_OPTION.'&controller=config&id='.$item->id); ?>">-->
            <?php echo $this->escape($item->title); ?><!--</a>-->
<?php   else: ?>
          <img src="<?php echo $this->_ambit->getIcon('error.png'); ?>" alt="Warning" /> <?php echo JText::_('COM_JOOMGALLERY_CONFIGS_MISSING_TEXT'); ?>
<?php   endif; ?>
        </td>
        <td class="order">
<?php if($saveOrder && $item->id != 1): ?>
          <span><?php echo $this->pagination->orderUpIcon($i, $i > 1, 'orderup', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span>
          <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, $i < count($this->items), 'orderdown', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
          <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="text-area-order" />
<?php else: ?>
          <input type="hidden" name="order[]" size="5" value="<?php echo $item->ordering;?>" /><?php echo $item->ordering; ?>
<?php endif; ?>
        </td>
        <td class="center">
          <?php echo $item->id; ?>
        </td>
      </tr>
<?php   $i++;
      endforeach; ?>
    </tbody>
  </table>
  <fieldset class="batch">
    <legend><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_NOTICES'); ?></legend>
    <ul>
      <li><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_INFO_1'); ?></li>
      <li><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_INFO_2'); ?></li>
      <li><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_INFO_3'); ?></li>
      <li><?php echo JText::_('COM_JOOMGALLERY_CONFIGS_INFO_4'); ?></li>
    </ul>
  </fieldset>
  <div class="jg_displaynone">
<?php if(count($this->items) == 1):
        echo $this->loadTemplate('info'); ?>
    <script type="text/javascript">
      SqueezeBox.open($('jg-info-popup'), {
        handler: 'adopt',
        size: {x: 500, y: 350}
      });
    </script>
<?php endif; ?>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="config" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="group_id" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
<?php JHtml::_('joomgallery.credits');
      JHtml::_('behavior.modal');