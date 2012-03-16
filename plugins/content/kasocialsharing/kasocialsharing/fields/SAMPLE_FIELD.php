<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');//import the necessary class definition for formfield

class JFormFieldSeparator1 extends JFormField {
	protected $type = 'separator1';

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

        //now get to the business of finding the articles
        $db = &JFactory::getDBO();
        $query = 'SELECT * FROM #__categories WHERE published=1 ORDER BY parent_id';
        $db->setQuery( $query );
        $categories = $db->loadObjectList();

        $articles=array();

        // set up first element of the array as all articles
        $articles[0]->id = '';
        $articles[0]->title = JText::_("ALLARTICLES");

        //loop through categories
        foreach ($categories as $category) {
            $optgroup = JHTML::_('select.optgroup',$category->title,'id','title');
            $query = 'SELECT id,title FROM #__content WHERE catid='.$category->id;
            $db->setQuery( $query );
            $results = $db->loadObjectList();

            if(count($results)>0) {
                array_push($articles,$optgroup);
                foreach ($results as $result) {
                    array_push($articles,$result);
                }
            }
        }

        // Output

        return JHTML::_('select.genericlist',  $articles, $this->name, trim($attr), 'id', 'title', $this->value );

    }
}
























//class JElementSeparator1 extends JElement {
//	var	$_name = 'Separator1';
//	function fetchElement($name, $value, &$node, $control_name) {
//		return '<div style="color:#fff; font-size:12px; font-weight:bold; padding:3px; margin:0; text-align:center; background:#4FAED2;">'.JText::_($value).'</div>';
//	}
//}