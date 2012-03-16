<?php
/**
 * Facebook Like Button plugin
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @package    	Facebook Like Button
 * @link       	http://www.khawaib.co.uk
 * @copyright 	http://www.khawaib.co.uk
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementSeparator2 extends JElement {
	var	$_name = 'Separator2';
	function fetchElement($name, $value, &$node, $control_name) {
		return '<div style="color:#444; background:#ccc; height:12px; font-size:11px; font-weight:bold; padding:3px; margin:0; text-align:center;">'.JText::_($value).'</div>';
	}
}