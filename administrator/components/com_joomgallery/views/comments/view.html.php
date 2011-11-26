<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/comments/view.html.php $
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
 * HTML View class for the comments list view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewComments extends JoomGalleryView
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
    $state      = $this->get('State');
    $items      = $this->get('Comments');
    $pagination = $this->get('Pagination');

    $this->assignRef('items',       $items);
    $this->assignRef('pagination',  $pagination);
    $this->assignRef('state',       $state);

    if($state->get('filter.inuse') && !$this->get('Total'))
    {
      $this->_mainframe->enqueueMessage(JText::_('COM_JOOMGALLERY_COMMAN_MSG_NO_COMMENTS_FOUND_MATCHING_YOUR_QUERY'));
    }

    $this->addToolbar();
    parent::display($tpl);
  }

  function addToolbar()
  {
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_COMMAN_COMMENTS_MANAGER'));
    JToolbarHelper::publishList('publish', 'COM_JOOMGALLERY_COMMAN_TOOLBAR_PUBLISH_COMMENT');
    JToolbarHelper::unpublishList('unpublish', 'COM_JOOMGALLERY_COMMAN_TOOLBAR_UNPUBLISH_COMMENT');
    JToolbarHelper::custom('approve', 'upload.png', 'upload_f2.png', 'COM_JOOMGALLERY_COMMAN_TOOLBAR_APPROVE_COMMENT');
    JToolbarHelper::divider();
    JToolbarHelper::deleteList('', 'remove', 'COM_JOOMGALLERY_COMMAN_TOOLBAR_REMOVE_COMMENT');
    JToolbarHelper::divider();
    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();
  }
}