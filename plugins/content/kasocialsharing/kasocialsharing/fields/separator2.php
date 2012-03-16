<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldSeparator2 extends JFormField {
	protected $type = 'Separator2'; //the form field type
	protected function getInput() {
        return '<span style="float:left; width:100%; color:#444; background:#ccc; height:12px; font-size:11px; font-weight:bold; padding:3px; margin:0; text-align:center;">' . JText::_($this->element['default']) . '</span>';
    }
}