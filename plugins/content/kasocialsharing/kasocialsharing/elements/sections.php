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
class JElementSections extends JElement {

  var   $_name = 'Sections';

  function fetchElement($name, $value, &$node, $control_name) {
    $db = &JFactory::getDBO();

    $section  = $node->attributes('section');
    $class    = $node->attributes('class');
    if (!$class) {
      $class = "inputbox";
    }

    if (!isset($section)) {
      // alias for section
      $section = $node->attributes('scope');
      if (!isset($section)) {
        $section = 'content';
      }
    }

    if ($section == 'content') {
      $query = 'SELECT c.id AS value, c.title AS text' .
        ' FROM #__sections AS c' .
        ' WHERE c.published = 1' .
//        ' AND c.section = '.$db->Quote($section).
        ' ORDER BY c.title';
    }
    $db->setQuery($query);
    $options = $db->loadObjectList();
  
    return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="inputbox"  style="width:98%;" size="10" multiple="multiple"', 'value', 'text', $value, $control_name.$name);
  
  }
}