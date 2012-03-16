<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldIswritable extends JFormField {
    protected $type = 'is_writable'; //the form field type
    
	protected function getInput() {
        $cssFileAbsPath = JPATH_SITE . DS . 'modules' . DS . 'mod_kafacebookfanboxpro' . DS . 'css' . DS . 'fbfanboxpro' . '.css';
        if (is_writable($cssFileAbsPath)) {
            return '<span style="float:left; width:100%; color:#10791F; border:1px solid #8CD31E; background:#CBE17E; font-size:11px; padding:3px; margin:0; text-align:center;">'.JText::_('[root]/modules/mod_kafacebookfanboxpro/css/fbfanboxpro.css is writeable.').'</span>';
        } else {
            return '<span style="float:left; width:100%; color:#CC0000; border:1px solid #FE7B7A; background:#FFD6D6; font-size:11px; padding:3px; margin:0; text-align:center;">'.JText::_('[root]/modules/mod_kafacebookfanboxpro/css/fbfanboxpro.css not writeable.').'</span>';
        }
	}
}