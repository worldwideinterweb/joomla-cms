<?php 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldMenus extends JFormField {
    protected $type = 'menus';
    protected function getInput() {
        $session = JFactory::getSession();
        $options = array();
        
        $attr = '';
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
            $attr .= ' disabled="disabled"';
        }
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        $attr .= $this->multiple ? ' multiple="multiple"' : '';
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		$menus = array();
		$menus = JHTML::_('menu.linkoptions');
        return JHTML::_('select.genericlist',  $menus, ''.$this->name.'[]', 'class="inputbox" style="width:62%;" multiple="multiple" size="10"', 'value', 'text', '' );
    }
}