<?php
/**
 * @version		$Id: view.html.php 21655 2011-06-23 05:43:24Z chdemko $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a weblink.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @since		1.5
 */
class WeblinksplusViewWeblinkplus extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

                // Build Article Selector
                $db =& JFactory::getDBO();
                
		$nullDate = $db->getNullDate();
                $date =&JFactory::getDate();
                $now = $date->toMySQL();
                
                $query = 'SELECT id as value, title as text FROM #__content WHERE state = 1 AND ( publish_up = '.$db->Quote($nullDate)
                        .' OR publish_up <= '.$db->Quote($now).' ) AND ( publish_down = '.$db->Quote($nullDate).' OR publish_down >= '
                        .$db->Quote($now).' ) ORDER BY title';
		$db->setQuery($query);
		$articles = $db->loadObjectList('value');
		array_unshift($articles,(object)array('value'=>'0','text'=>'None'));
                
		$articleSelector = JHTML::_('select.genericlist', 
                             $articles, 
                             'article_id', 
                             'class="inputbox"', 
                             'value', 
                             'text',
                             $this->item->article_id);
		$this->assignRef('articleSelector',	$articleSelector);
		
                
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
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= WeblinksplusHelper::getActions($this->state->get('filter.category_id'), $this->item->id);

		JToolBarHelper::title(JText::_('COM_WEBLINKSPLUS_MANAGER_WEBLINK'), 'weblinks.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_weblinksplus', 'core.create')))))
		{
			JToolBarHelper::apply('weblinkplus.apply');
			JToolBarHelper::save('weblinkplus.save');
		}
		if (!$checkedOut && (count($user->getAuthorisedCategories('com_weblinksplus', 'core.create')))){
			JToolBarHelper::save2new('weblinkplus.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_weblinksplus', 'core.create')) > 0)) {
			JToolBarHelper::save2copy('weblinkplus.save2copy');
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('weblinkplus.cancel');
		}
		else {
			JToolBarHelper::cancel('weblinkplus.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_WEBLINKS_LINKS_EDIT');
	}
}
