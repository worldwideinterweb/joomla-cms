<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/categories/view.html.php $
// $Id: view.html.php 3492 2011-11-01 14:24:45Z chraneco $
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
 * HTML View class for the categories list view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewCategories extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  function display($tpl = null)
  {
    JHTML::_('behavior.tooltip');

    // Get data from the model
    $items      = $this->get('Categories');
    $state      = $this->get('State');
    $pagination = $this->get('Pagination');

    $this->assignRef('items',       $items);
    $this->assignRef('state',       $state);
    $this->assignRef('pagination',  $pagination);

    if($state->get('filter.inuse') && !$this->get('Total'))
    {
      $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_CATMAN_MSG_NO_CATEGORIES_FOUND_MATCHING_YOUR_QUERY'));
    }

    $this->addToolbar();
    parent::display($tpl);
  }

  /**
   * Add the toolbar and toolbar title.
   *
   * @access  protected
   * @return  void
   *
   * @since 2.0
   */
  protected function addToolbar()
  {
    // Get the results for each action
    $canDo = JoomHelper::getActions();

    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_CATMAN_CATEGORY_MANAGER'), 'categories');

    if($canDo->get('core.create') || count(JoomHelper::getAuthorisedCategories('core.create')))
    {
      JToolbarHelper::addNew('new');
    }

    if($canDo->get('core.edit') || count(JoomHelper::getAuthorisedCategories('core.edit')))
    {
      JToolbarHelper::editList('edit');
      JToolbarHelper::divider();
    }

    if(count(JoomHelper::getAuthorisedCategories('core.edit.state')))
    {
      JToolbarHelper::publishList('publish');
      JToolbarHelper::unpublishList('unpublish');
      JToolbarHelper::divider();
    }

    if($canDo->get('core.delete') || count(JoomHelper::getAuthorisedCategories('core.delete')))
    {
      JToolbarHelper::deleteList('','remove');
      JToolbarHelper::divider();
    }

    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();
  }
}