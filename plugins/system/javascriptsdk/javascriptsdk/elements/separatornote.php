<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementSeparatornote extends JElement {
	var	$_name = 'separatornote';
	function fetchElement($name, $value, &$node, $control_name) {
		return '<div style="color:#333 !important; border:1px solid #B1C9E0; background:#E4EDF7; font-size:11px; padding:3px; margin:0; text-align:center;">'.JText::_($node->attributes('description')).'</div>';
	}
}