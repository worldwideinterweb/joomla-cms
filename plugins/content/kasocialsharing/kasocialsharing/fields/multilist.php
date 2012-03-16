<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldmultilist extends JFormField {
    protected $type = 'multilist';
    protected function getInput() {
        $attr = '';
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
            $attr .= ' disabled="disabled"';
        }
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        $attr .= $this->multiple ? ' multiple="multiple"' : '';
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

        $options = array ();
        foreach ($this->element->option as $option) {
            $val   = $option->getAttribute('value');
            $text  = $option->data();
            $options[] = JHTML::_('select.option', $val, JText::_($text));
        }
        return JHTML::_('select.genericlist', $options,    $this->name, trim($attr) . 'style="width:60%;" size="6"', 'value', 'text', $this->value, $this->id);
    }
}   
