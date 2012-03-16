<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');//import the necessary class definition for formfield

class JFormFieldCategories extends JFormField {
	protected $type = 'categories'; //the form field type

	protected function getInput() {
        // Initialize variables.
        $session = JFactory::getSession();
        $options = array();
        $attr = '';

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
            $attr .= ' disabled="disabled"';
        }

        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        $attr .= $this->multiple ? ' multiple="multiple"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

        
        // Output
//        return JHTML::_('select.genericlist',  $articles, $this->name, trim($attr), 'id', 'title', $this->value );
        return JHTML::_('select.genericlist',  $categories, $this->name, trim($attr), 'id', 'title', $this->value );

    }
}