<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldSeparator1 extends JFormField {
	protected $type = 'separator1'; //the form field type
	protected function getInput() {
        return '<span style="float:left; width:100%; color:#fff; font-size:12px; font-weight:bold; padding:3px; margin:0; text-align:center; background:#4FAED2;">' . JText::_($this->element['default']) . '</span>';
    }
}