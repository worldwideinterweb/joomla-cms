<?php
/**
 * Spearhead softwares Joomla Facebook Module for Joomla 1.6
 * 
 * @package Spearhead softwares. 
 * @subpackage Modules
 * @link http://www.spearheadsoftwares.com
 * @license GNU/GPL, see http://www.gnu.org/copyleft/gpl.html
 * mod_spearheadfacebooklike is free software.
 * This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

class modSpearheadFacebookLikeHelper
{
	/**
	 * Helper class for Joomla Facebook Like module
	 * 
	 * @param array $params Variable holding all the parameters of the module helper object 
	 * @access public
	 */
	
	public $fbLikeLinkBase = 'http://www.facebook.com/plugins/like.php?';
	
	/**
	 * @name getFacebookLike
	 * @tutorial Calculates and outputs the facebook like button code
	 * @param array $params Variable holding all the parameters of the module helper object 
	 * @access public
	 */	
	public function getFacebookLike($params)
	{
		
		$type = $params->get('like_button_type','iframe');
		
		// auto discovery is needed for iframe versions only since xfbml detects
		// the current page and attach to the like button automatically.
		// If explicit like_url is specified then it overrides the url taken from
		// auto discovery or from the xfbml 
		// $url is the final url to be attached to the like button.
		
		$auto_discovery = $params->get('auto_discovery');	
		//explicit url variable.
		$like_url = $params->get('like_url');
		
		if($type=='iframe')
		{
			if($auto_discovery=='yes' || $like_url=='')
			{
				$url =  modSpearheadFacebookLikeHelper::autoDiscovery();
			}
			else 
			{
				$url = $like_url;
			}
		}
		elseif($type=='xfbml')
		{
			if($auto_discovery=='yes' || $like_url=='')
			{
				//set url to null as facebook will autodetect the current page.
				//This way we reduce much markup/html taken for the url.
				$url =  '';
				$xfbUrl = '';
			}
			else 
			{
				$url = $like_url;
				$xfbUrl = 'href="'.$url.'"';
			}			
		}
		
		$send_button = ($params->get('send_button')==1)?'true':'false';
		
		$layout = $params->get('layout_style');
		$show_faces = ($params->get('show_faces')==1)? 'true' : 'false';
		$width = $params->get('width');
		$verb_to_display = $params->get('verb_to_display','like');
		$font = $params->get('font','arial');
		$color_scheme = $params->get('color_scheme');
		$language = $params->get('language','en_US');
		$height = $params->get('height');
		$seperator = '&amp;';		
		
		
		$document =& JFactory::getDocument();
		
		
		switch($type)
		{
			case'iframe':
				$fbLike = 	'<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.$seperator
				  			.'layout='.$layout.$seperator
				  			.'show_faces='.$show_faces.$seperator
				  			.'width='.$width.$seperator
				  			.'action='.$verb_to_display.$seperator
				  			.'font='.$font.$seperator
				  			.'colorscheme='.$color_scheme.$seperator
				  			.'height='.$height.$seperator
				  			.'language='.$language
				  			.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:'.$height.'px;" allowTransparency="true"></iframe>';
				break;
			case'xfbml':
				$document->addScript('http://connect.facebook.net/'.$language.'/all.js#xfbml=1');
				$fbLike = '<div id="fb-root"></div>
						<fb:like 
							'.$xfbUrl.' 
							send="'.$send_button.'" 
							layout="'.$layout.'" 
							width="'.$width.'" 
							show_faces="'.$show_faces.'" 
							action="'.$verb_to_display.'" 
							font="'.$font.'">
						</fb:like>';
				break;
		}
		
						  
				  
		return $fbLike;
	}

	/**
	 * @name autoDiscovery
	 * @tutorial Calculates the current url both sef and non sef urls based on joomla settings are returned
	 * @access public
	 */	
	public function autoDiscovery()
	{
		$uri = &JURI::getInstance();
		return urlencode($uri->toString());
	}
	
	public function copyRight()
	{
		return '<div style="display:none"><a href="http://www.spearheadsoftwares.com/products-downloads/facebook-like-joomla">Powered by Spearhead Softwares Joomla Facebook Like Button </a></div>';
	}	
}

