<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementSeparator1 extends JElement {
	var	$_name = 'Separator1';
	function fetchElement($name, $value, &$node, $control_name) {
		return '<div style="color:#fff; font-size:12px; font-weight:bold; padding:3px; margin:0; text-align:center; background:#4FAED2;">'.JText::_($value).'</div>';
	}
}