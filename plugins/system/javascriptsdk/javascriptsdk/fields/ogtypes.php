<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldOgtypes extends JFormField {
    protected $type = 'Ogtypes';
    public function getLabel() {
        return '<span style="">' . parent::getLabel() . '</span>';
	}
 
	public function getInput() {
//        return '<select id="'.$this->id.'" name="'.$this->name.'">'.
//		       '<option value="1" >New York</option>'.
//		       '<option value="2" >Chicago</option>'.
//		       '<option value="3" >San Francisco</option>'.
//		       '</select>';
        
        $activities     = array('activity'=>'activity', 'sport'=>'sport');
        $businesses     = array('bar'=>'bar', 'company'=>'company', 'cafe'=>'cafe', 'hotel'=>'hotel', 'restaurant'=>'restaurant');
        $groups         = array('cause'=>'cause', 'sports_league'=>'sports_league', 'sports_team'=>'sports_team');
        $organisations  = array('band'=>'band', 'government'=>'government', 'non_profit'=>'non_profit', 'school'=>'school', 'university'=>'university');
        $people         = array('actor'=>'actor', 'athlete'=>'athlete', 'author'=>'author', 'director'=>'director', 'musician'=>'musician', 'politician'=>'politician', 'public_figure'=>'public_figure');
        $places         = array('city'=>'city', 'country'=>'country', 'landmark'=>'landmark', 'state_province'=>'state_province');
        $products       = array('album'=>'album', 'book'=>'book', 'drink'=>'drink', 'food'=>'food', 'game'=>'game', 'product'=>'product', 'song'=>'song', 'movie'=>'movie', 'tv_show'=>'tv_show', 'upc'=>'upc', 'isbn'=>'isbn');
        $websites       = array('blog'=>'blog', 'website'=>'website', 'article'=>'article');

        $options = array(); // Empty array to add options into
        $options[] = JHTML :: _('select.option', '', JText::_('SELECT_OG_TYPE')); // Initial option
        $options[] = JHTML::_('select.optgroup', 'Activities'); // optgroup option to add group of items
        foreach($activities as $key => $text) { // loop through items
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>'); // Close OptGroup

        $options[] = JHTML::_('select.optgroup', 'Businesses');
        foreach($businesses as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');

        $options[] = JHTML::_('select.optgroup', 'Groups');
        foreach($groups as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');
        
        $options[] = JHTML::_('select.optgroup', 'Organisations');
        foreach($organisations as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');
        
        $options[] = JHTML::_('select.optgroup', 'People');
        foreach($people as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');
        
        $options[] = JHTML::_('select.optgroup', 'Places');
        foreach($places as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');
        
        $options[] = JHTML::_('select.optgroup', 'Products');
        foreach($products as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');
        
        $options[] = JHTML::_('select.optgroup', 'Websites');
        foreach($websites as $key => $text) {
            $options[] = JHTML::_('select.option', $key, $text);
        }
        $options[] = JHTML::_('select.optgroup', '</OPTGROUP>');
        
        $select = JHTML::_(
         'select.genericlist', // Select element
         $options,             // Options that we created above
         $this->name,                // The name select element in HTML
         'size="1" ',          // Extra parameters to add for select element
         'value',              // The name of the object variable for the option value
         'text',               // The name of the object variable for the option text
         $this->value,               // The key that is selected (accepts an array or a string)
         false                 // Flag to translate the option results
        );

        return $select;
//	}
        
	}
}

?>

