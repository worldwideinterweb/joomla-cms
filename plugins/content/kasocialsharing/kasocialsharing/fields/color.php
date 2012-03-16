<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldColor extends JFormField {
	protected $type = 'Color'; //the form field type
	protected function getInput() {
		$img=JUri::root()."modules/mod_kafacebookfanboxpro/images/color_wheel.png";
		$imgx=JUri::root()."modules/mod_kafacebookfanboxpro/images/";
		$field = '';
        static $embedded;
		if(!$embedded) {
			$css2=JURI::root()."modules/mod_kafacebookfanboxpro/css/mooRainbow.css";
			$jspath=JURI::root()."modules/mod_kafacebookfanboxpro/js/mooRainbow.1.2b2.js";

			$field  = '<link href="'. $css2 .'" type="text/css" rel="stylesheet" />';
			$field .= '<script src="'.  $jspath .'"></script>';
			$embedded = true;
			$field .= '<script>
				window.addEvent(\'domready\',function(){
					Element.extend({
						getSiblings: function() {
							return this.getParent().getChildren().remove(this);
						}
					});
					$$(\'.rainbowbtn\').each(function(item){
					 	item.color=new MooRainbow(item.id, {
							startColor: [58, 142, 246],
							wheel: true,
							id:item.id+\'x\',
							onChange: function(color) {
								item.getSiblings()[0].value = color.hex;
							},
							onComplete: function(color) {
								item.getSiblings()[0].value = color.hex;
							},
							imgPath: \''. $imgx .'\'
						});
					});
				});
			</script>';
		}
        $field .= '<input name="jform[params]['. $this->element['name'] .']" type="text" id="jform_params_'. $this->element['name'] .'" value="'. $this->value .'" size="10" />';
        $field .= '<img src="'. $img .'" id="img'. $this->element['name'] .'" alt="[r]" class="rainbowbtn" width="16" height="16" />';
		return $field;
	}
}
?>