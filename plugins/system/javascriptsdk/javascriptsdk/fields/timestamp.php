<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldTimeStamp extends JFormField {
	protected $type = 'TimeStamp'; //the form field type
	protected function getInput() {
        return '<input name="jform[params][time_stamp]" type="text" readonly="readonly" class="inputbox" id="jform_params_time_stamp" value="'. time() .'" size="15" />';
    }
}