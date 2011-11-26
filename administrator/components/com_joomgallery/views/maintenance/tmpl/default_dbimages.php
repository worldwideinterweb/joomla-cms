<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');
      if($this->current_tab == 'orphans' || $this->current_tab == 'categories' || $this->current_tab == 'folders'): ?>
<div>
  <ul class="jg-message">
    <li><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_MSG_REFRESH_NECESSARY'); ?></li>
  </ul>
  <script type="text/javascript">
    $$('.cpanel-panel-joom-maintenance-db-images').addEvent('click', function(){
      document.location.href="index.php?option=<?php echo _JOOM_OPTION; ?>&controller=maintenance&tab=images";
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
      <?php echo JHTML::_('joomselect.categorylist', $this->state->get('filter.category'), 'filter_category', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', null, '- ', 'filter'); ?>
      <?php echo $this->lists['img_filter']; ?>
    </div>
  </fieldset>
  <div class="clr"> </div>
  <table class="adminlist">
    <thead>
      <tr>
        <!--<th width="1%">
          <?php echo JText::_('COM_JOOMGALLERY_COMMON_NUM'); ?>
        </th>-->
        <th width="1%">
          <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this);" />
        </th>
        <th width="25"></th>
        <th class="left">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_TITLE', 'a.title', $listDirn, $listOrder); ?>
        </th>
        <th width="15%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_THUMBNAIL', 'a.thumb', $listDirn, $listOrder); ?>
        </th>
        <th width="15%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_MAIMAN_DB_DETAIL', 'a.img', $listDirn, $listOrder); ?>
        </th>
        <th width="15%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_MAIMAN_DB_ORIGINAL', 'a.orig', $listDirn, $listOrder); ?>
        </th>
        <th width="15%" class="nowrap">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_OWNER', 'user', $listDirn, $listOrder); ?>
        </th>
        <th width="15%">
          <?php echo JHtml::_('grid.sort', 'COM_JOOMGALLERY_COMMON_CATEGORY', 'category', $listDirn, $listOrder); ?>
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
        <td colspan="9">
          <?php echo $this->pagination->getListFooter(); ?>
        </td>
      </tr>
<?php     endif; ?>
      <tr>
        <td colspan="9">
          <div class="jg_mntcheckagain">
<?php     if($n && $this->state->get('check_originals')): ?>
            <p>
              <span class="jg_mntchecknote">
                <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_ORIGINALS_NOTE'); ?>
              </span>
            </p>
<?php     endif; ?>
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
              <span class="hasTip jg_mntcheckoriginals" title="<?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_ORIGINALS').'::'.JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_ORIGINALS_DESC'); ?>">
                <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_ORIGINALS'); ?>
                <?php echo JHtml::_('select.booleanlist', 'check_originals', null, $this->state->get('check_originals', 1)); ?>
              </span>
            </p>
          </div>
        </td>
      </tr>
<?php   else: ?>
      <tr>
        <td colspan="9">
          <div class="jg_mntcheck">
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
              </div>
              <span class="hasTip jg_mntcheckoriginals" title="<?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_ORIGINALS').'::'.JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_ORIGINALS_DESC'); ?>">
                <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_CHECK_ORIGINALS'); ?>
                <?php echo JHtml::_('select.booleanlist', 'check_originals', null, $this->state->get('check_originals', 1)); ?>
              </span>
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
<?php       if($row->thumb):
              $link = 'index.php?option='._JOOM_OPTION.'&amp;controller=images&amp;task=edit&amp;cid='.$row->refid; ?>
        <?php echo JHtml::_('joomgallery.minithumbimg', $this->_ambit->getImgObject($row->refid), 'jg_minithumb', 'href="'.$link); ?>
<?php       else: ?>
      &nbsp;
<?php       endif; ?>
        </td>
        <td>
          <a href="index.php?option=<?php echo _JOOM_OPTION; ?>&amp;controller=images&amp;task=edit&amp;cid=<?php echo $row->refid; ?>">
            <?php echo $row->title; ?>
          </a>
        </td>
        <td class="center">
<?php       if($row->thumb): ?>
          <?php echo $this->tick(); ?>
<?php       else: ?>
          <?php echo $this->cross();
                echo $row->thumborphan ? $this->correct('addorphan', $row->thumborphan, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_ADD_ORPHAN')) : '';
                if($row->orig):
                  echo $this->correct('recreate', $row->id, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_RECREATE_FROM_ORIG'), false, '&amp;type=thumb');
                else:
                  if($row->img):
                    echo $this->correct('recreate', $row->id, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_RECREATE_FROM_IMG'));
                  endif;
                endif; ?>
<?php       endif; ?>
        </td>
        <td class="center">
<?php       if($row->img): ?>
          <?php echo $this->tick(); ?>
<?php       else: ?>
          <?php echo $this->cross();
                echo $row->imgorphan ? $this->correct('addorphan', $row->imgorphan, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_ADD_ORPHAN')) : '';
                if($row->orig):
                  echo $this->correct('recreate', $row->id, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_RECREATE_FROM_ORIG'), false, '&amp;type=img');
                endif; ?>
<?php       endif; ?>
        </td>
        <td class="center">
<?php       if($row->orig): ?>
          <?php echo $this->tick(); ?>
<?php       else: ?>
          <?php echo $this->cross();
                echo $row->origorphan ? $this->correct('addorphan', $row->origorphan, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_ADD_ORPHAN')) : ''; ?>
<?php       endif; ?>
        </td>
        <td class="center">
<?php       if($row->owner != -1): ?>
          <?php echo JHTML::_('joomgallery.displayname', $row->owner); echo $this->tick(); ?>
<?php       else: ?>
          <?php echo $this->cross(); ?>
          <?php echo $this->correct('newuser', null, JText::_('COM_JOOMGALLERY_MAIMAN_OPTION_SET_NEW_USER'), 'javascript:joom_selectnewuser('.$i.')'); ?>
          <div id="correctuser<?php echo $i; ?>"></div>
<?php       endif; ?>
        </td>
        <td>
<?php       if($row->catid != -1): ?>
          <?php echo $row->category/*JHtml::_('joomgallery.categorypath', $row->catid)*/; echo $this->tick(); ?>
<?php       else: ?>
          <?php echo $this->cross(); ?>
<?php       endif; ?>
        </td>
        <td>
          <?php echo $row->refid; ?>
        </td>
      </tr>
<?php       $k = 1 - $k;
          endfor; ?>
<?php     if(!$n): ?>
      <tr>
        <td colspan="10">
          <div class="jg_mntnocorrupt">
            <p>
              <span class="jg_mntnocorrupttxt">
                <?php echo JText::_('COM_JOOMGALLERY_MAIMAN_MSG_NO_CORRUPT_IMAGES_FOUND'); ?>
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
    <?php echo $this->lists['img_jobs']; ?>
    <p id="batchjobs"></p>
    <button type="submit" onclick="if(document.adminForm.task.value == ''){return false;}"><?php echo JText::_('COM_JOOMGALLERY_MAIMAN_APPLY'); ?></button>
  </fieldset>
  <div class="jg_displaynone" id="garage">
    <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
    <input type="hidden" name="controller" value="maintenance" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="tab" value="images" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
    <?php echo JHTML::_('list.users', 'newuser', 0, true, null, 'name', false); ?>
    <a href="#correctUser" id="filesave" class="saveorder" title="<?php echo JText::_('COM_JOOMGALLERY_MAIMAN_APPLY'); ?>"></a>
    <span id="usertip"> <?php echo JHTML::_('tooltip', JText::_('COM_JOOMGALLERY_MAIMAN_NOTE_USER_FILTER'), JText::_('COM_JOOMGALLERY_MAIMAN_NOTE')); ?></span>
  </div>
</form>
<?php endif;