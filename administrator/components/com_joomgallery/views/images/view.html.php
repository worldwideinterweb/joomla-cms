<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/images/view.html.php $
// $Id: view.html.php 3492 2011-11-01 14:24:45Z chraneco $
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
 * HTML View class for the images list view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewImages extends JoomGalleryView
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
    $items      = $this->get('Images');
    $state      = $this->get('State');
    $pagination = $this->get('Pagination');

    $this->assignRef('items',       $items);
    $this->assignRef('state',       $state);
    $this->assignRef('pagination',  $pagination);

    if($state->get('filter.inuse') && !$this->get('Total'))
    {
      $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_IMGMAN_MSG_NO_IMAGES_FOUND_MATCHING_YOUR_QUERY'));
    }

    $this->addToolbar();
    parent::display($tpl);
  }

  protected function addToolbar()
  {
    // Get the results for each action
    $canDo = JoomHelper::getActions();

    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_IMGMAN_IMAGE_MANAGER'), 'mediamanager');

    if(($canDo->get('joom.upload') || count(JoomHelper::getAuthorisedCategories('joom.upload'))) && $this->pagination->total)
    {
      JToolbarHelper::addNew('new'/*, 'COM_JOOMGALLERY_COMMON_TOOLBAR_NEW'*/);
    }

    if($canDo->get('core.edit') || $canDo->get('core.edit.own'))
    {
      JToolbarHelper::editList(/*'edit', 'COM_JOOMGALLERY_COMMON_TOOLBAR_EDIT'*/);
      JToolbarHelper::custom('showmove', 'move.png', 'move.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_MOVE');
      JToolbarHelper::custom('recreate', 'refresh.png', 'refresh.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_RECREATE');
      JToolbarHelper::divider();
    }

    if($canDo->get('core.edit.state'))
    {
      JToolbarHelper::publishList(/*'publish', 'COM_JOOMGALLERY_COMMON_TOOLBAR_PUBLISH'*/);
      JToolbarHelper::unpublishList(/*'unpublish', 'COM_JOOMGALLERY_COMMON_TOOLBAR_UNPUBLISH'*/);
      JToolbarHelper::custom('approve', 'upload.png', 'upload_f2.png', 'COM_JOOMGALLERY_IMGMAN_TOOLBAR_APPROVE');
      //JToolbarHelper::spacer();
      JToolbarHelper::divider();
      //JToolbarHelper::spacer();
    }

    //if($canDo->get('core.delete'))
    //{
      JToolbarHelper::deleteList('', 'remove'/*, 'COM_JOOMGALLERY_COMMON_TOOLBAR_REMOVE'*/);
    //}

    //JToolbarHelper::spacer();
    JToolbarHelper::divider();
    //JToolbarHelper::spacer();
    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();
  }
}