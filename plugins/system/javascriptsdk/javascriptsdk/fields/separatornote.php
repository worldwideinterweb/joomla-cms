<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldSeparatornote extends JFormField {
	protected $type = 'Separatornote'; //the form field type
	protected function getInput() {
        return '<span style="float:left; width:100%; color:#333; border:1px solid #B1C9E0; background:#E4EDF7; font-size:11px; padding:3px; margin:0; text-align:center;">' . JText::_($this->element['description']) . '</span>';
    }
}