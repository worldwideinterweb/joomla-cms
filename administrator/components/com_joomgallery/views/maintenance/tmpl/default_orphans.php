<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
      if($this->current_tab != 'orphans'): ?>
<div>
  <ul class="jg-message">
    <li><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_MSG_REFRESH_NECESSARY'); ?></li>
  </ul>
  <script type="text/javascript">
    $$('.cpanel-panel-joom-maintenance-file-images').addEvent('click', function(){
      document.location.href="index.php?option=<?php echo _JOOM_OPTION; ?>&controller=maintenance&tab=orphans";
    });
  </script>
</div>
<?php else:
        $listOrder  = $this->escape($this->state->get('list.ordering'));
        $listDirn   = $this->escape($this->state->get('list.direction')); ?>
<form action="index.php" method="post" name="adminForm">
  <fieldset id="filter-bar" class="jg_mntfilterbar">
    <div class="filter-search fltlft">
      <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER'); ?></label>
      <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?>" />
      <button type="submit"><?php echo JText::_('COM_JOOMGALLERY_COMMON_SEARCH'); ?></button>
      <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('COM_JOOMGALLERY_COMMON_FILTER_CLEAR'); ?></button>
    </div>
    <div class="filter-select fltrt">
      <?php echo $this->lists['orphan_proposal']; ?>
      <?php echo $this->lists['orphan_filter']; ?>
    </div>
  </fieldset>
  <div class="clr"> </div>
  <table class="adminlist">
    <thead>
      <tr>
        <!--<th width="5">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_NUM'); ?>
        </th>-->
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this);" />
        </th>
        <th class="left">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_MAIMAN_OP_FILENAME_AND_PATH', 'a.fullpath', $listDirn, $listOrder); ?>
        </th>
        <th width="15%" class="center">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_TYPE', 'a.type', $listDirn, $listOrder); ?>
        </th>
        <th width="15%" class="center">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_MAIMAN_SUGGESTION', 'a.refid', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" class="nowrap">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>
      </tr>
    </thead>
    <tfoot>
<?php   if($this->checked):
          if($n = count($this->items)): ?>
      <tr>
        <td colspan="5">
          <?php echo $this->pagination->getListFooter(); ?>
        </td>
      </tr>
<?php     endif; ?>
      <tr>
        <td colspan="5">
          <div class="jg_mntcheckagain">
            <p>
              <div class="jg_mnt-button-holder">
                <div class="button-holder">
                  <div class="button1">
                    <div class="next">
                      <a onclick="Joomla.submitform('check');" href="#"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_AGAIN'); ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </p>
          </div>
        </td>
      </tr>
<?php   else: ?>
      <tr>
        <td colspan="5">
          <div class="jg_mntcheckagain">
            <p>
              <span class="jg_mntchecktext">
                <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_PLEASE_CHECK'); ?>
              </span>
            </p>
            <div>
              <div class="jg_mnt-button-holder">
                <div class="button-holder">
                  <div class="button1">
                    <div class="next">
                      <a onclick="Joomla.submitform('check');" href="#"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK'); ?></a>
                    </div>
                  </div>
                </div>
                &nbsp;
              </div>
            </div>
            <div class="jg_mntchecknote">
              <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_NOTE'); ?>
            </div>
          </div>
        </td>
      </tr>
<?php   endif; ?>
    </tfoot>
    <tbody>
<?php   if($this->checked):
          $k = 0;
          for($i = 0; $i < $n; $i++):
            $row = &$this->items[$i]; ?>
      <tr class="row<?php echo $k; ?>">
        <!--<td>
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>-->
        <td>
          <?php echo JHtml::_('grid.id', $i, $row->id); ?>
        </td>
        <td>
          <?php echo $row->type == 'unknown' ? $this->warning(JText::_('COM_JOOMGALLERY_MAIMAN_UNKNOWN_FILE_TYPE'), JText::_('COM_JOOMGALLERY_MAIMAN_UNKNOWN_FILE_TYPE_LONG')) : ''; ?>&nbsp;<?php echo $row->fullpath; ?>
        </td>
        <td align="center">
          <?php if($row->type != 'unknown'):
                  echo JText::_('COM_JOOMGALLERY_MAIMAN_TYPE_'.strtoupper($row->type));
                else:
                  #jimport('joomla.filesystem.file');
                  echo JText::sprintf('COM_JOOMGALLERY_MAIMAN_TYPE_UNKNOWN_VAL'/*, JFile::getExt($row->fullpath)*/);
                endif; ?>
        </td>
        <td class="center">
<?php       if($row->refid): ?>
          <span title="<?php echo JText::_('COM_JOOMGALLERY_MAIMAN_SHOW_IMAGE_DETAILS'); ?>" class="hasTip">
            <a href="index.php?option=<?php echo _JOOM_OPTION; ?>&amp;controller=images&amp;task=edit&amp;cid=<?php echo $row->refid; ?>">
              <?php echo $row->title; ?>
            </a>
          </span>
          <?php echo $this->correct('addorphan', $row->id, JText::_('COM_JOOMGALLERY_MAIMAN_ADD_ORPHAN_TO_IMAGE')); ?>
<?php       else: ?>
          <?php echo $this->cross('COM_JOOMGALLERY_MAIMAN_NO_PROPOSAL'); ?>
          <?php echo $this->correct('deleteorphan', 0, JText::_('COM_JOOMGALLERY_MAIMAN_DELETE_THIS_ORPHAN'), 'javascript:listItemTask(\'cb'.$i.'\', \'deleteorphan\');'); ?>
<?php       endif; ?>
        </td>
        <td>
          <?php echo $row->id; ?>
        </td>
      </tr>
<?php       $k = 1 - $k;
          endfor; ?>
<?php     if(!$n): ?>
      <tr>
        <td colspan="5">
          <div class="jg_mntnoorphans">
            <p>
              <span class="jg_mntnoorphanstxt">
                <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_MSG_NO_ORPHANS_FOUND'); ?>
              </span>
            </p>
          </div>
        </td>
      </tr>
<?php     endif; 
        endif; ?>
    </tbody>
  </table>
  <fieldset class="jg_mntchoices batch">
    <legend><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_BATCH_JOBS'); ?></legend>
    <?php echo $this->lists['orphan_jobs']; ?>
    <p id="batchjobs"></p>
    <button type="submit" onclick="if(document.adminForm.job.value == ''){return false;}else{submitbutton(document.adminForm.job.value);}"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_APPLY'); ?></button>
  </fieldset>
  <div>
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="maintenance" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="tab" value="orphans" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
<?php endif;