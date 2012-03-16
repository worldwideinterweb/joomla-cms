<?php
# KA JavaScript SDK
# Copyright (C) 2010 by Khawaib Ahmed
# @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
# Homepage   : www.Khawaib.co.uk
# Author     : Khawaib Ahmed
# Email      : khawaib@khawaib.co.uk

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin');
if (version_compare(JVERSION,'1.6.0','ge')) {
    if (!class_exists('Kajssdkhelper')) {
        require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'javascriptsdk'.DS.'javascriptsdk'.DS.'helpers'.DS.'kajssdkhelper.php');
    }
} else {
    if (!class_exists('Kajssdkhelper')) {
        require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'javascriptsdk'.DS.'helpers'.DS.'kajssdkhelper.php');
    }
}
class plgSystemJavascriptsdk extends JPlugin {
	function plgSystemJavascriptsdk(&$subject, $config) {
		parent::__construct($subject, $config);
	}
    // 1.5
    function onAfterDisplayTitle(&$article, &$params, $limitstart) {
        if (!$this->canShow()) return;
        $Kajssdkhelper = new Kajssdkhelper();
        $plgConfig = $Kajssdkhelper->getConfiguration('system', 'javascriptsdk');

        if (Kajssdkhelper::checkCanShow($article, $plgConfig) == false) return;
        if (!defined('KA_JAVASCRIPT_SDK_OPEN_GRAPH')) {
            $curPageURL = Kajssdkhelper::getJssdkUrl($article, $plgConfig);
            $pageData = Kajssdkhelper::getPageData($article, $plgConfig);
            Kajssdkhelper::setOgTags($pageData, $plgConfig);
            
            define('KA_JAVASCRIPT_SDK_OPEN_GRAPH', 1);
        }
    }
    // 1.7
    function onContentAfterTitle($context, &$article, &$params, $limitstart) {
        if (!$this->canShow()) return;
        $Kajssdkhelper = new Kajssdkhelper();
        $plgConfig = $Kajssdkhelper->getConfiguration('system', 'javascriptsdk');
        
        if (Kajssdkhelper::checkCanShow($article, $plgConfig) == false) return;
        if (!defined('KA_JAVASCRIPT_SDK_OPEN_GRAPH')) {
            $pageData = Kajssdkhelper::getPageData($article, $plgConfig);
            Kajssdkhelper::setOgTags($pageData, $plgConfig);

            define('KA_JAVASCRIPT_SDK_OPEN_GRAPH', 1);
        }
    }
	function onAfterRender() {
        if (!$this->canShow()) return;

        $locale = $this->params->get('locale', '');
		if ($locale == 'default') {
            $lg = &JFactory::getLanguage();
			$langTag = $lg->getTag();

            if ($langTag == 'ar-AA') {
                $langTag = 'ar_AR';
            } else {
                    $langTag = str_replace('-', '_', $langTag);
            }
		} else {
			$langTag = $locale;
		}
        
		$buffer = JResponse::getBody();
        
//		$javascriptsdk = '
//        <!-- KAJSSDK by www.Khawaib.co.uk Start -->
//        <div id="fb-root"></div>
//        <script type="text/javascript">
//        window.fbAsyncInit = function() { FB.init({appId: \''.$this->params->get('fb_app_id').'\', status: true, cookie: true, xfbml: true}); };
//        (function() { var e = document.createElement(\'script\');
//        e.type = \'text/javascript\';
//        e.src = document.location.protocol + \'//connect.facebook.net/'.$langTag.'/all.js\';
//        e.async = true;
//        document.getElementById(\'fb-root\').appendChild(e);
//        }());
//        </script>
//        <!-- KAJSSDK by www.Khawaib.co.uk End -->';
        
		$javascriptsdk = '
        <!-- KAJSSDK by www.Khawaib.co.uk Start -->
        <div id="fb-root"></div>
        <script type="text/javascript">
        window.fbAsyncInit = function() { FB.init({
            appId:\''.$this->params->get('fb_app_id').'\',
            status:true,
            cookie:true,
            xfbml:true,
            oauth:true
        });};';
//channelUrl:\'http://www.yourdomain.com/channel.html\' //custom channel
        if ($this->params->get('asynchronous')) {
            $javascriptsdk .= '
            (function(d){
                var js, id = \'facebook-jssdk\';
                if (d.getElementById(id)) {return;}
                js = d.createElement(\'script\');
                js.id = id;
                js.async = true;
                js.src = "//connect.facebook.net/'.$langTag.'/all.js";
                d.getElementsByTagName(\'head\')[0].appendChild(js);
            }(document));';
        } else {
            $javascriptsdk .= '
            (function() {
                var e = document.createElement(\'script\');
                e.type = \'text/javascript\';
                e.src = document.location.protocol + \'//connect.facebook.net/'.$langTag.'/all.js\';
                e.async = true;
                document.getElementById(\'fb-root\').appendChild(e);
            }());';
        }
        $javascriptsdk .= '</script>
        <!-- KAJSSDK by www.Khawaib.co.uk End -->';
            
        if ($this->params->get('og_xmlns')) {
            $ogxmlns = '';
            if ($this->params->get('custom_xmlns_namespace')) {
                $ogxmlns = '<html ' . $this->params->get('custom_xmlns_namespace');
            } else {
                // Check if already added
                if (strpos($buffer, 'xmlns:og="http://ogp.me/ns#"') === false) {
                    $ogxmlns = '<html xmlns:og="http://ogp.me/ns#"';
                }
            }
            $buffer = JString::str_ireplace("<html", $ogxmlns, $buffer);
        }
        if ($this->params->get('fb_xmlns')) {
            // Check if already added
            if (strpos($buffer, 'xmlns:fb="http://www.facebook.com/2008/fbml"') === false) {
                $fbxmlns = '<html xmlns:fb="http://www.facebook.com/2008/fbml"';
                $buffer = JString::str_ireplace("<html", $fbxmlns, $buffer);
            }
        }
        
        if ($this->params->get('enable_sdk')) {
            // Check if already added
            if (strpos($buffer, 'id="fb-root') === false) {
                if ($this->params->get('insertion_mode') == 0) {
                    $position2 = strpos($buffer, "<body");
                    $position = strpos($buffer, ">" , $position2);
                    if($position > 0) {
                        $buffer = substr($buffer, 0, $position+1) . $javascriptsdk . substr($buffer, $position+1);
                        JResponse::setBody($buffer);
                    }
                } else {
                    $buffer = JString::str_ireplace("</body>", $javascriptsdk . "</body>", $buffer);
                }
            }
        }
        
		JResponse::setBody($buffer);
		return true;
	}
        
    function canShow() {
        $document =& JFactory::getDocument();
        $docType = $document->getType();
        $app = JFactory::getApplication();
        
        $fb_app_id = $this->params->get('fb_app_id');
        if ($this->params->get('fb_app_id') == '' || strpos($_SERVER["PHP_SELF"], "index.php") === false || $docType != 'html') { return false; }
        if ($app->isAdmin() && $this->params->get('admin_load')==0) { return false; }

        return true;
    }
    function getFirstImage($text) {
        $first_img = '';
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $text, $matches);
        if (isset($matches[1][0])) {
            $first_img = $matches[1][0];
            if (!strstr($first_img, 'http://') && !strstr($first_img, 'https://'))
                $first_img = JURI::root() . $first_img;
        }

        return $first_img;
    }
}
?>