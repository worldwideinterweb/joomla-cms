<?php
/**
 * @version		$Id: view.html.php 21705 2011-06-28 21:19:50Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of weblinks.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @since		1.5
 */
class WeblinksplusViewWeblinksplus extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/weblinksplus.php';

		$state	= $this->get('State');
		$canDo	= WeblinksplusHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_WEBLINKSPLUS_MANAGER_WEBLINKS'), 'weblinks.png');
		if (count($user->getAuthorisedCategories('com_weblinksplus', 'core.create')) > 0) {
			JToolBarHelper::addNew('weblinkplus.add');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('weblinkplus.edit');
		}
		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::publish('weblinksplus.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('weblinksplus.unpublish', 'JTOOLBAR_UNPUBLISH', true);


			JToolBarHelper::divider();
			JToolBarHelper::archiveList('weblinksplus.archive');
			JToolBarHelper::checkin('weblinksplus.checkin');
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'weblinksplus.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('weblinksplus.trash');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_weblinksplus');
			JToolBarHelper::divider();
		}

		JToolBarHelper::help('JHELP_COMPONENTS_WEBLINKS_LINKS');
	}
}
