<?php
/**
* @copyright    Copyright (C) 2009 Open Source Matters. All rights reserved.
* @license      GNU/GPL
*/
 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
 
/**
 * Renders a menus multiple item select element
 *
 */
class JElementMenus extends JElement {
  var   $_name = 'menus';

	function fetchElement($name, $value, &$node, $control_name){
		$document =& JFactory::getDocument();
		$menus = array();
		
//		$temp->value = '';
//		$temp->text = JText::_("Select all menus");
		$menus = JHTML::_('menu.linkoptions');
//		array_unshift($menus, $temp);

		return JHTML::_('select.genericlist',  $menus, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:98%;" multiple="multiple" size="10"', 'value', 'text', $value );		
	}
}