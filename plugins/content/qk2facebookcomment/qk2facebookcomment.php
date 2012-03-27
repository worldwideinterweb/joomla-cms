<?php
/*------------------------------------------------------------------------
# plg_qk2facebookcomment - Q K2 Facebook comment Joomla 1.7 Plugin
# ------------------------------------------------------------------------
# author    NetQ Creative Software, JSC
# copyright Copyright (C) 2010 Netqcreative.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.netqcreative.com
# Technical Support:  Forum - http://www.netqcreative.com/Support
-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('K2Plugin', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'k2plugin.php');
class plgContentqk2facebookcomment extends K2Plugin 
{
	// Some params
	var $pluginName = 'Qsocial comment';
	var $pluginNameHumanReadable = 'Social share on K2 content items';
	
	function plgContentqk2facebookcomment( & $subject, $params)
	{
		global $pluginCount;
		$pluginCount = 0;
		parent::__construct($subject, $params);
	}
	/**
	 * Below we list all available FRONTEND events, to trigger K2 plugins.
	 * Watch the different prefix "onK2" instead of just "on" as used in Joomla! already.
	 * Most functions are empty to showcase what is available to trigger and output. A few are used to actually output some code for example reasons.
	 */
	function k2facebookcomment($item, $params, $limitstart) {
		global $pluginCount;
		global $mainframe;
		$pluginCount++;
		$option = JRequest::getVar('option');
		$document = &JFactory::getDocument();
		require_once(JPATH_BASE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
		if($item->id) {
			$link = JRoute::_(K2HelperRoute::getItemRoute($item->id,$item->catid));
			$jURI = & JURI::getInstance();
			$link = $jURI->getScheme()."://".$jURI->getHost().$link;
		} else {
			$jURI =& JURI::getInstance();
			$link = $jURI->toString();
		}
		preg_match_all('/src="([^"]+)"/i', $item->text, $matches);
		if(!empty($matches[1][0])) {
			$imageUrl = JURI::root() . $matches[1][0]; 
			$document->addCustomTag( '<meta property="og:image" content="'.$imageUrl.'" />' );
		}else{
			$document->addCustomTag( '<meta property="og:image" content="'.$item->imageMedium.'" />' );
		}
		$fbcomment = '<div id="fb-root"></div>';
		$fbcomment .= '
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/'.$this->params->get('lang', 'en_US').'/all.js#xfbml=1&appId='.$this->params->get('api', '170039526351901').'";
				fjs.parentNode.insertBefore(js, fjs);
				}(document, "script", "facebook-jssdk"));
			</script>
			';
		$fbcomment .= '<div class="fb-comments" data-href="'.$link.'" data-num-posts="'.$this->params->get('numposts', 5).'" data-width="'.$this->params->get('width', 500).'"></div>';	
		if ($pluginCount > 1){
			$fbcomment = '';
		}	
		$view = JRequest::getVar('view');
		$layout = JRequest::getVar('layout');
		// display item future
		$displayfronpage = $this->params->get('displayfronpage');
		if($displayfronpage ==0 && $item->featured==1){
			return false;
		}
		//ex category
		$excludecategories = $this->params->get('excludecategories');
		if($excludecategories) {
		$categoriesArray = explode(",",$excludecategories);
		//var_dump($row);
			if(strlen(array_search($item->catid,$categoriesArray))) {
				return;
			}
		}
		
		$exitems = $this->params->get('excludeitem');
 		
		if($exitems){
			$articlesArray = explode(",",$exitems);
			if(strlen(array_search($item->id,$articlesArray))){
				return ;
			}
		}
		
		//display comment facebook in layout category
		$show_category = $this->params->get('displaycategory', 0);
	  	if($show_category == 0 && $option == 'com_k2' && $view == 'itemlist' && ($layout == 'category' ||  $layout == 'categorys')){
	  		$fbcomment = '';
	  	}
		return $fbcomment;
	}
		
	function onK2AfterDisplay(& $item, & $params, $limitstart) {
	
		global $mainframe;
			if($this->params->get('fb_position') == 5) {
	 		$this->k2facebookcomment(& $item, & $params, $limitstart) ;
	 		return $this->k2facebookcomment(& $item, & $params, $limitstart) ;
		}
	}
	
	function onK2BeforeDisplayContent( & $item, & $params, $limitstart) {
	
		global $mainframe;
		if($this->params->get('fb_position') == 1) {
	 		$this->k2facebookcomment(& $item, & $params, $limitstart) ;
	 		return $this->k2facebookcomment(& $item, & $params, $limitstart) ;
		}
	}
	
	function onK2AfterDisplayContent( & $item, & $params, $limitstart) {
		global $mainframe;
		if($this->params->get('fb_position') == 4) {
	 		$this->k2facebookcomment(& $item, & $params, $limitstart) ;
	 		return $this->k2facebookcomment(& $item, & $params, $limitstart) ;
		}elseif($this->params->get('fb_position') == 3) {
			$html = $this->k2facebookcomment(& $item, & $params, $limitstart) ;
			$item->text = $item->text.$html;
			return ;
		}elseif($this->params->get('fb_position') == 2) {
			$html = $this->k2facebookcomment(& $item, & $params, $limitstart) ;
			$item->text = $html.$item->text;
			return ;
		}
	} 
	
	function onAfterRender() {
		$document = &JFactory::getDocument();
		jimport( 'joomla.document.html.renderer.component');
		global $mainframe, $option ;
		$view = JRequest::getVar('view');
		$layout = JRequest::getVar('task');
		$db = &JFactory::getDBO();
		//
		if($option == 'com_k2'){
			
 			$categories = $this->params->get('excludecategories', '');
 			
		
				if($view == 'item' && $layout == ''){
			 		$id_article = JString::strrpos(JRequest::getVar('id'), ':');
			 		$id_article = substr_replace(JRequest::getVar('id'), '', $id_article);
			 	} elseif ($view == 'item' && $layout == 'item'){
			 		$id_article = JRequest::getVar('id');
			 	}
			 	
			 	if(!empty($id_article)){
				 	$db->setQuery("SELECT * FROM #__k2_items WHERE id = ".$id_article);
				 	$catitem = $db->LoadObject();
				 	preg_match_all('/src="([^"]+)"/i', $catitem->introtext.$catitem->fulltext, $matches);
					if(!empty($matches[1][0])) {
						$imageUrl = JURI::root() . $matches[1][0]; 
						$document->addCustomTag( '<meta property="og:image" content="'.$imageUrl.'" />' );
					}
			 		if($categories){
						$categoriesArray = explode(",",$categories);
						if(strlen(array_search($catitem->catid,$categoriesArray))){
							return;
						}	
					}
				}	
				
				
				$exitems = $this->params->get('excludeitem');
				if($exitems){
					$articlesArray = explode(",",$exitems);
					if(strlen(array_search($id_article,$articlesArray))){
						return ;
					}
				}
				
				
 			$jURI =& JURI::getInstance();
			$link = $jURI->toString();
			
			$fbcomment = '<div id="fb-root"></div>';
			$fbcomment .= '
				<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) {return;}
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/'.$this->params->get('lang', 'en_US').'/all.js#xfbml=1&appId='.$this->params->get('api', '170039526351901').'";
					fjs.parentNode.insertBefore(js, fjs);
					}(document, "script", "facebook-jssdk"));
				</script>
				';
			$fbcomment .= '<div class="fb-comments" data-href="'.$link.'" data-num-posts="'.$this->params->get('numposts', 5).'" data-width="'.$this->params->get('width', 500).'"></div>';	
			
 			
			$display_category = $this->params->get('displaycategory', 0);
		  	if($display_category == 0 && $option == 'com_k2' && $view == 'itemlist' && $layout == 'category' ){
		  		$fbcomment = '';
		  		$display = 'yes';
	  		} else $display = '';
	  		
			$uri = & JFactory::getURI();
			$body = JResponse::getBody();

			$position 	 = $this->params->get('fb_position', 0);
			
			// show item
			if($position == 6 && empty($display)){
				$setbody = preg_replace("/<div class=\"itemBackToTop\">/", "\n\n".$fbcomment."\n\n<div class=\"itemBackToTop\">", $body);
				JResponse::setBody($setbody);
			}
			return true;
		}
	}
	
}
