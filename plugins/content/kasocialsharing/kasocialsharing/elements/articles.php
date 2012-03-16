<?php
/**
* @copyright    Copyright (C) 2009 Open Source Matters. All rights reserved.
* @license      GNU/GPL
*/
 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
 
/**
 * Renders a content articles multiple item select element
 *
 */
class JElementArticles extends JElement {

  var   $_name = 'Articles';

  function fetchElement($name, $value, &$node, $control_name) {
    $db = &JFactory::getDBO();

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
//		$query = 'SELECT a.id AS value, CONCAT_WS( "/",s.title, c.title ) AS text' .
      $query = 'SELECT a.id AS value, CONCAT_WS( " / ",c.title, a.title ) AS text' .
        ' FROM #__content AS a' .
//        ' LEFT JOIN #__sections AS s ON s.id=c.section' .
        ' LEFT JOIN #__categories AS c ON c.id=a.catid' .
        ' WHERE a.state = 1' .
//        ' AND s.scope = '.$db->Quote($section).
        ' ORDER BY c.title, a.title';
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
  
  }
}