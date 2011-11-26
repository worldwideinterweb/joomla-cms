<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/helpers/html/joompopup.php $
// $Id: joompopup.php 3385 2011-10-09 16:13:58Z erftralle $
/******************************************************************************\
**   JoomGallery 2                                                            **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                      **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                  **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look             **
**   at administrator/components/com_joomgallery/LICENSE.TXT                  **
\******************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * Utility class for creating HTML output
 *
 * @static
 * @package JoomGallery
 * @since   1.5.5
 */
class JHTMLJoomPopup
{
  /**
   * Adds the CSS of the current template to the pop up windows
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function start()
  {
    // Template CSS is usually not loaded now, but it's better to have it
    $load_template_css = true; // Make available in the plugin params?
    if($load_template_css)
    {
      $doc            = & JFactory::getDocument();
      $mainframe      = & JFactory::getApplication('site');
      $template       = $mainframe->getTemplate();
      $template_file  = false;
      if(is_file(JPATH_THEMES.DS.$template.DS.'css'.DS.'template.css'))
      {
        $template_file = 'templates/'.$template.'/css/template.css';
      }
      else
      {
        if(is_file(JPATH_THEMES.DS.$template.DS.'css'.DS.'template_css.css'))
        {
          $template_file = 'templates/'.$template.'/css/template_css.css';
        }
      }
      if($load_template_css)
      {
        $doc->addStyleSheet(JURI::root().$template_file);

        // To avoid scroll bar with some templates
        $doc->addStyleDeclaration("    body{\n      height:90%;\n    }");
        $doc->addStyleSheet('templates/system/css/system.css');
      }
    }
  }

  /**
   * Displays the form for selecting a user who shall be tagged on an image
   *
   * @access  public
   * @return  void
   * @since   1.5.5
   */
  function nametags()
  {
    JHTML::_('behavior.mootools');
    $user = & JFactory::getUser();
    $config = & JoomConfig::getInstance();
    $username = $config->get('jg_realname') ? 'name' : 'username'; ?>
<div class="gallery minigallery" style="text-align:center;">
  <div class="jg_header">
    <?php echo JText::_('COM_JOOMGALLERY_SELECT_NAMETAG'); ?>
  </div>
  <div>
    <form action="index.php" name="selectnametagform" method="post">
      <div>
        <input type="submit" value="<?php echo JText::_('COM_JOOMGALLERY_DETAIL_NAMETAGS_SELECT_MYSELF'); ?>" class="button" onclick="window.parent.selectnametag(<?php echo $user->get('id'); ?>, '<?php echo $user->get($username); ?>');return false;" />
      </div>
      <div>
        <?php echo JHTML::_('joomselect.users', '', 'selectnametaglist', true, 'onchange="window.parent.selectnametag(this.value, this[this.selectedIndex].text);"', false, $config->get('jg_realname')); ?>
      </div>
    </form>
  </div>
</div>
<?php
  }

  /**
   * Displays the form for reporting an image
   *
   * @access  public
   * @return  void
   * @since   1.5.6
   */
  function report()
  {
    JHTML::_('behavior.formvalidation');
    $doc        = & JFactory::getDocument();
    $user       = & JFactory::getUser();
    $ambit      = & JoomAmbit::getInstance();
    $mainframe  = & JFactory::getApplication('site');
    $doc->addStyleSheet($ambit->getStyleSheet('joomgallery.css')); ?>
<div class="gallery minigallery" style="text-align:center;">
  <fieldset style="width:550px;margin-right:auto; margin-left:auto;">
    <legend><?php echo JText::_('COM_JOOMGALLERY_DETAIL_REPORT_IMAGE'); ?></legend>
    <form action="<?php echo JRoute::_('index.php'); ?>" <?php /*target="testfenster" onsubmit="window.top.setTimeout(window.parent.document.getElementById('sbox-window').close(), 3000);"*/ ?>id="reportimageform" name="reportimageform" method="post" class="form-validate">
<?php if(!$user->get('id')): ?>
      <div>
        <label for="name"><?php echo JText::_('COM_JOOMGALLERY_COMMON_REPORT_YOUR_NAME'); ?></label><br />
        <input type="text" id="name" name="name" value="<?php echo $mainframe->getUserState('report.image.name'); ?>" class="inputbox required" style="width:50%;" />
      </div>
      <div>
        <label for="email"><?php echo JText::_('COM_JOOMGALLERY_COMMON_REPORT_YOUR_EMAIL'); ?></label><br />
        <input type="text" id="email" name="email" value="<?php echo $mainframe->getUserState('report.image.email'); ?>" class="inputbox required validate-email" style="width:50%;" />
      </div>
<?php endif; ?>
      <div>
        <label for="report"><?php echo JText::_('COM_JOOMGALLERY_COMMON_REPORT_YOUR_REPORT'); ?></label><br />
        <textarea id="report" name="report" class="inputbox required" style="width:100%; height:200px;"><?php echo $mainframe->getUserState('report.image.report'); ?></textarea>
      </div>
      <div>
        <?php echo implode('', $mainframe->triggerEvent('onJoomGetCaptcha', array('report'))); ?>
        <input type="hidden" name="id" value="<?php echo JRequest::getInt('id'); ?>" />
        <input type="hidden" name="task" value="sendreport" />
        <input type="hidden" name="tmpl" value="component" />
      </div>
      <div><input type="submit" name="button" value="<?php echo JText::_('COM_JOOMGALLERY_COMMON_REPORT_SEND_REPORT'); ?>" class="button validate" /></div>
    </form>
  </fieldset>
</div>
<?php
  }
}