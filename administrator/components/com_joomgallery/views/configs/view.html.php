<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/configs/view.html.php $
// $Id: view.html.php 3381 2011-10-07 19:10:00Z aha $
/****************************************************************************************\
**   JoomGallery 2                                                                      **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * HTML View class for the configs list view
 *
 * @package JoomGallery
 * @since   2.0
 */
class JoomGalleryViewConfigs extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   2.0
   */
  function display($tpl = null)
  {
    JHTML::_('behavior.tooltip');

    // Get data from the model
    $items = $this->get('Configs');
    $this->assignRef('items', $items);

    if($this->getLayout() == 'new')
    {
      $this->assignRef('usergroups', $this->get('Usergroups'));

      parent::display($tpl);

      return;
    }

    // Get some additional data from the model
    $state      = $this->get('State');
    $pagination = $this->get('Pagination');

    $this->assignRef('state',       $state);
    $this->assignRef('pagination',  $pagination);

    $this->addToolbar();
    parent::display($tpl);
  }

  /**
   * Add the toolbar and toolbar title.
   *
   * @return  void
   * @since  2.0
   */
  protected function addToolbar()
  {
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_CONFIGS_CONFIGURATION_MANAGER'), 'config');

    $toolbar = JToolbar::getInstance('toolbar');
    $toolbar->appendButton('Popup', 'new', 'JTOOLBAR_NEW', 'index.php?option='._JOOM_OPTION.'&amp;controller=config&amp;layout=new&amp;tmpl=component', 400, 350);

    JToolbarHelper::editList('edit');

    JToolbarHelper::deleteList('','remove');
    JToolbarHelper::divider();

    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();
  }
}