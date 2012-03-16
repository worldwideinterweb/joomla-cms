<?php
/**
* @copyright    Copyright (C) 2009 Open Source Matters. All rights reserved.
* @license      GNU/GPL
*/
 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
 
/**
 * Renders a content categories multiple item select element
 *
 */
class JElementCategories extends JElement {
	var   $_name = 'categories';

	function fetchElement($name, $value, &$node, $control_name) {
		$db = &JFactory::getDBO();
		$query = 'SELECT * FROM #__sections WHERE published = 1' . ' ORDER BY ordering';
		$db->setQuery( $query );
		$sections = $db->loadObjectList();
		$categories = array();

/*		// All Categories
		$categories[0]->id = '';
		$categories[0]->title = JText::_("Select all categories");*/
    
		// Category listings, grouped by sections
		foreach ($sections as $section) {
			$optgroup = JHTML::_('select.optgroup',$section->title, 'id', 'title');
			$query = 'SELECT id,title FROM #__categories WHERE published=1 AND section = ' . $section->id . ' ORDER BY ordering';
			$db->setQuery( $query );
			$results = $db->loadObjectList();
			array_push( $categories,$optgroup );
			foreach ( $results as $result ) {
				array_push( $categories,$result );
			}
		}
    
/*		// Create the 'Uncategorised' listings
		$optgroup = JHTML::_('select.optgroup',JText::_("Uncategorised"),'id','title');
		array_push( $categories,$optgroup );
		$uncategorised = array();
		$uncategorised['id'] = '0';
		$uncategorised['title'] = JText::_("Uncategorised");
		array_push( $categories,$uncategorised );*/

		return JHTML::_('select.genericlist',  $categories, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:98%;" multiple="multiple" size="10"', 'id', 'title', $value );    
    
/* 
	    $section  = $node->attributes('section');
	    $class    = $node->attributes('class');
	    if (!$class) {
	      $class = "inputbox";
	    }
	
	    if (!isset ($section)) {
	      // alias for section
	      $section = $node->attributes('scope');
	      if (!isset ($section)) {
	        $section = 'content';
	      }
	    }
	
	    if ($section == 'content') {
	      // This might get a conflict with the dynamic translation
	      // - TODO: search for better solution
	      $query = 'SELECT c.id AS value, CONCAT_WS( " / ",s.title, c.title ) AS text' .
	        ' FROM #__categories AS c' .
	        ' LEFT JOIN #__sections AS s ON s.id=c.section' .
	        ' WHERE c.published = 1' .
	        ' AND s.scope = '.$db->Quote($section).
	        ' ORDER BY s.title, c.title';
	    } else {
	      $query = 'SELECT c.id AS value, c.title AS text' .
	        ' FROM #__categories AS c' .
	        ' WHERE c.published = 1' .
	        ' AND c.section = '.$db->Quote($section).
	        ' ORDER BY c.title';
	    }
	    $db->setQuery($query);
	    $options = $db->loadObjectList();
	  
	    return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="inputbox" size="7" multiple="multiple"', 'value', 'text', $value, $control_name.$name);
*/
	}
}