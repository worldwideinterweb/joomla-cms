<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<table border="0" cellpadding="10" class="adminform">
  <tbody>
    <tr>
      <td width="55%" valign="top">
        <div id="cpanel" class="joom_cpanel">
<?php foreach($this->items as $item):
        $this->item = $item;
        echo $this->loadTemplate('button');
      endforeach; ?>
        </div>
      </td>
      <td width="45%" valign="top">
<?php echo JHtml::_('tabs.start', 'joom-pane1');
      $this->entry      = 1;
      if($this->params->get('dated_extensions')):
        echo JHtml::_('tabs.panel', JText::_('COM_JOOMGALLERY_ADMENU_UPDATECHECK_TITLE'), 'cpanel-panel-joom_update');
        foreach($this->dated_extensions as $name => $extension):
          $this->extension  = $extension;
          $this->name       = $name;
          echo $this->loadTemplate('extension');
          $this->entry++;
          if($this->entry <= count($this->dated_extensions)): ?>
    <hr />
<?php     endif;
        endforeach;
      endif;
      foreach($this->modules as $module):
        echo JHtml::_('tabs.panel', $module->title, 'cpanel-panel-'.$module->name );
        echo JModuleHelper::renderModule($module);
      endforeach;
      if($this->params->get('show_available_extensions')):
        echo JHtml::_('tabs.panel', JText::_('COM_JOOMGALLERY_ADMENU_INSTALLED_EXTENSIONS'), 'cpanel-panel-joom_update2');
        echo JHtml::_('sliders.start', 'joom-pane2', array('startOffset' => -1, 'startTransition' => 0));
        foreach($this->available_extensions as $name => $extension):
          $title = $name;
          if(isset($this->installed_extensions[$name]))
          {
            if(isset($this->dated_extensions[$name]))
            {
              $title .= ' <span class="jg_extnotuptodate">'.JText::_('COM_JOOMGALLERY_ADMENU_EXTENSION_INSTALLED_BUT_NOT_UPTODATE').'</span>';
            }
            else
            {
              $title .= ' <span class="jg_extinstalled">'.JText::_('COM_JOOMGALLERY_ADMENU_EXTENSION_INSTALLED').'</span>';
            }
          }
          else
          {
            $title .= ' <span class="jg_extnotinstalled">'.JText::_('COM_JOOMGALLERY_ADMENU_EXTENSION_NOT_INSTALLED').'</span>';
          }
          echo JHtml::_('sliders.panel', $title, 'cpanel-panel-joom_update3');
          $this->extension  = $extension;
          $this->name       = $name;
          echo $this->loadTemplate('availableextension');
          if($this->entry < count($this->available_extensions)):
            $this->entry++;
          endif;
        endforeach;
        echo JHtml::_('sliders.end');
      endif;
      echo JHtml::_('tabs.end');
      if($this->params->get('show_update_info_text')): ?>
        <div class="jg-system-uptodate">
          <?php echo JText::_('COM_JOOMGALLERY_ADMENU_SYSTEM_UPTODATE'); ?>
        </div>
<?php endif; ?>
      </td>
    </tr>
  </tbody>
</table>
<?php JHTML::_('joomgallery.credits');