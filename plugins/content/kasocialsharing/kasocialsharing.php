<?php
/**
 * KA Social Sharing plugin
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @package    	KA Social Share Sharing
 * @link       	http://www.khawaib.co.uk
 * @copyright 	http://www.khawaib.co.uk
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
if (version_compare(JVERSION,'1.6.0','ge')) {
    if (!class_exists('Kajssdkhelper')) {
        require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'javascriptsdk'.DS.'javascriptsdk'.DS.'helpers'.DS.'kajssdkhelper.php');
    }
} else {
    if (!class_exists('Kajssdkhelper')) {
        require_once(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'javascriptsdk'.DS.'helpers'.DS.'kajssdkhelper.php');
    }
}
jimport( 'joomla.plugin.plugin' );
jimport('joomla.filesystem.file');
jimport('joomla.version');
class plgContentkasocialsharing extends JPlugin {
    function plgContentkasocialsharing( &$subject, $params ) {
		parent::__construct( $subject, $params );
	}
    // J! 1.5
    function onPrepareContent( &$article, &$params, $limitstart ) {
        if (version_compare(JVERSION,'1.6.0','ge')) { return; } // Make sure its not Joomla 1.5
        global $mainframe;
        $Kajssdkhelper = new Kajssdkhelper();

        if($mainframe->isAdmin() || strpos($_SERVER["PHP_SELF"], "index.php") === false) return false;
        $plgConfig = $Kajssdkhelper->getConfiguration('content', 'kasocialsharing');

        //require(JPATH_BASE  . DS .  'plugins' . DS . 'content' . DS . 'kasocialsharing' . DS . 'tmpl' . DS . 'default' . DS . 'default.php');
		if (Kajssdkhelper::checkMenuIds($plgConfig) == false) return;
        $curPageURL = Kajssdkhelper::getJssdkUrl($article, $plgConfig);

        if (Kajssdkhelper::checkCanShow($article, $plgConfig, $curPageURL) == false) return;
        
        $finalHtml = $this->prepareFinalHTML( $curPageURL, $plgConfig, $article );
		Kajssdkhelper::setFinalHtml( $article, $finalHtml, $plgConfig );
	}
    // J! 1.6/1.7
    public function onContentBeforeDisplay($context, &$article, &$params, $page = 0) {
        $app = JFactory::getApplication();
        $Kajssdkhelper = new Kajssdkhelper();
       
        if($app->isAdmin() || strpos($_SERVER["PHP_SELF"], "index.php") === false) return false;
        $plgConfig = $Kajssdkhelper->getConfiguration('content', 'kasocialsharing');

		if (Kajssdkhelper::checkMenuIds($plgConfig) == false) return;
        $curPageURL = Kajssdkhelper::getJssdkUrl($article, $plgConfig);

        if (Kajssdkhelper::checkCanShow($article, $plgConfig, $curPageURL) == false) return;
        
        $finalHtml = $this->prepareFinalHTML( $curPageURL, $plgConfig, $article );
		Kajssdkhelper::setFinalHtml( $article, $finalHtml, $plgConfig );
	}
	function prepareFinalHTML( $curPageURL, $plgConfig, &$article ) {
		global $mainframe;
		$document	= & JFactory::getDocument();

        if(version_compare(JVERSION,'1.6.0','ge')) {
            $styleSheet = JURI::base() . 'plugins' . DS . 'content' . DS . 'kasocialsharing' . DS . 'kasocialsharing' . DS . 'css' . DS . 'style1.css';
        } else {
            $styleSheet = JURI::base() . 'plugins' . DS . 'content' . DS . 'kasocialsharing' . DS . 'css' . DS . 'style1.css';
        }
        $document->addStyleSheet($styleSheet,'text/css',"screen");

        $fbSendHtml = '';
        $fbShareHtml = '';
        $fbLikeHtml = '';
        $twitterBtn = '';
        $tweetMe = '';
        $stumbleUpon = '';
        $reddit = '';
        $digg = '';
        $linkedInShare = '';
        $googleBuzzShare = '';
        $googleplus1Share = '';
        $shareThis = '';
        
		if ($plgConfig[ 'enable_fb_send' ]) {
            $fbSendHtml = $this->getSendHtml( $plgConfig, $curPageURL );
			$fbSendHtml = '<span class="kafbsend">'.$fbSendHtml.'</span>';
		}
		if ($plgConfig[ 'enable_share' ]) {
            if ( $plgConfig[ 'render_mode' ] == 'xfbml' ) {
                $fbShareHtml = $this->getShareXFBML( $plgConfig, $curPageURL );
            } else {
                $fbShareHtml = $this->getShareJavaScript( $plgConfig, $curPageURL );
            }
			$fbShareHtml = '<span class="kafbshare">'.$fbShareHtml.'</span>';
		}
		if ($plgConfig[ 'enable_like' ]) {
            if ( $plgConfig[ 'render_mode' ] == 'xfbml' ) {
               $fbLikeHtml = $this->getXfbmlLikeBtn( $plgConfig, $curPageURL );
            } else {
               $fbLikeHtml = $this->getIFrameLikeBtn( $plgConfig, $curPageURL );
            }
			$fbLikeHtml = '<span class="kafblike">'.$fbLikeHtml.'</span>';
		}
        if ($plgConfig[ 'enable_twitter_btn' ]) {
            $twitterBtn = $this->getTwitterBtn( $plgConfig, $curPageURL, $article );
            $twitterBtn = '<span class="katwitterbtn">'.$twitterBtn.'</span>';
        }
		if ($plgConfig[ 'enable_tweetme' ]) {
			$tweetMe = $this->getTweetMe( $plgConfig, $article );
			$tweetMe = '<span class="katweetme">'.$tweetMe.'</span>';
		}
		if ($plgConfig[ 'enable_stumbleupon' ]) {
			$stumbleUpon = $this->getStumbleUpon( $plgConfig, $curPageURL, $article );
			$stumbleUpon = '<span class="kastumbleupon">'.$stumbleUpon.'</span>';
		}
		if ($plgConfig[ 'enable_reddit' ]) {
			$reddit = $this->getReddit( $plgConfig, $curPageURL, $article );
			$reddit = '<span class="kareddit">'.$reddit.'</span>';
		}
		if ($plgConfig[ 'enable_digg' ]) {
			$digg = $this->getDigg( $plgConfig, $curPageURL, $article );
			$digg = '<span class="kadigg">'.$digg.'</span>';
		}
		if ($plgConfig[ 'enable_linkedin' ]) {
			$linkedInShare = $this->getLinkedInShare( $plgConfig, $curPageURL, $article );
			$linkedInShare = '<span class="kalinkedinshare">'.$linkedInShare.'</span>';
		}
		if ($plgConfig[ 'enable_googlebuzz' ]) {
			$googleBuzzShare = $this->getGoogleBuzz( $plgConfig, $article );
			$googleBuzzShare = '<span class="kagooglebuzzshare">'.$googleBuzzShare.'</span>';
		}
        if ($plgConfig['enable_googleplus1']) {
			$googleplus1Share = $this->getGooglePlus1( $plgConfig, $curPageURL, $article );
			$googleplus1Share = '<span class="kagoogleplus1share">'.$googleplus1Share.'</span>';
        }
		if ($plgConfig[ 'enable_sharethis' ]) {
			$shareThis = $this->getShareThis( $plgConfig, $article );
			$shareThis = '<span class="kasharethis">'.$shareThis.'</span>';
		}
        
        $patterns = array ('/\[send\]/', '/\[share\]/', '/\[like\]/', '/\[twitter\]/', '/\[tweetme\]/', '/\[stumbleupon\]/', '/\[reddit\]/', '/\[digg\]/', '/\[linkedin\]/', '/\[buzz\]/', '/\[plus1\]/', '/\[sharethis\]/');
        $replace  = array ($fbSendHtml, $fbShareHtml, $fbLikeHtml, $twitterBtn, $tweetMe, $stumbleUpon, $reddit, $digg, $linkedInShare, $googleBuzzShare, $googleplus1Share, $shareThis);

        $finalHtml = '<!-- KA Social Sharing Start -->';
		$finalHtml .= '<div class="kasocialplugin">';
        $finalHtml .= preg_replace($patterns, $replace, $plgConfig['ordering']);
		$finalHtml .= '</div>';

        if (trim($plgConfig['debug'])) {
            $finalHtml .= $this->getDebugText($plgConfig, $article, $curPageURL);
		}
        
        $finalHtml .= '<!-- KA Social Sharing End-->';

		return $finalHtml;
	}
    function getDebugText($plgConfig, $article, $curPageURL) {
        $user = JFactory::getUser();
//        if ($user->id != $plgConfig['debug_id']) {
//            return '';
//        }
        
        $document	= & JFactory::getDocument();
        $config =& JFactory::getConfig();

        // Additional Info
        $plgConfig['Name']          = 'KA Social Sharing';
        $plgConfig['Plugin Type']   = 'Content';
        if(version_compare(JVERSION,'1.6.0','ge')) {
            $xmlFile = JPATH_SITE . DS . 'plugins' . DS . 'content'  . DS . 'kasocialsharing' . DS . 'kasocialsharing.xml';
        } else {
            $xmlFile = JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . 'kasocialsharing.xml';
        }
        $plgConfig['Plugin Version']= Kajssdkhelper::getExtensionVersion($xmlFile);
        $version                    = new JVersion;
        $plgConfig['Joomla Version']= $version->RELEASE . '.' .  $version->DEV_LEVEL;
        $plgConfig['PHP Version']   = phpversion();
        //$plgConfig['MySQL Version'] = preg_replace('#[^0-9\.]#', '', mysql_get_server_info()); // Gives error on servers

        $plgConfig['OG:Title']      = $article->title;
        $plgConfig['OG:Description']= $article->metadesc;
        $plgConfig['OG:Sitename']   = $config->getValue('sitename');
        $plgConfig['OG:URL']        = $curPageURL;
        //$plgConfig['Apache Version'] = $_SERVER['SERVER_SOFTWARE'];
        $configData = Kajssdkhelper::varDumpReturn( $plgConfig );
        $debugData = '<div style="clear:both; color:red; font-size:12px;"><pre>'.$configData.'</pre></div>';
                    
        return $debugData;
    }
    function getSendHtml($plgConfig, $curPageURL) {
        $fbSendHtml = '<fb:send ';
        $fbSendHtml .= 'href="'.$curPageURL.'" ';
        $fbSendHtml .= 'colorscheme="'.$plgConfig[ 'fb_send_cs' ].'" ';
        if (!empty($plgConfig[ 'fb_send_font' ])) {
            $fbSendHtml .= 'font="'.$plgConfig[ 'fb_send_font' ].'" ';
        }
        if (!empty($plgConfig[ 'fb_send_ref' ])) {
            $fbSendHtml .= 'ref="'.$plgConfig[ 'fb_send_ref' ].'" ';
        }
        $fbSendHtml .= '></fb:send>';

        return $fbSendHtml;
    }
	function getShareXFBML($plgConfig, $curPageURL) {
        // To fix IE8 warning message removed http:// from URL
//        $curPageURL = str_replace('http://', '', $curPageURL);
		$shareXfbml = '<fb:share-button class="url" href="'.$curPageURL.'" type="'.$plgConfig[ 'share_btn_style' ].'"></fb:share-button>';
//        $shareXfbml = '<div style="float:left; margin-right:10px;">';
//        $shareXfbml .= '<a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a>';
//        $shareXfbml .= '<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
//        $shareXfbml .= '</div>';
//$shareXfbml  = '<fb:share-button class="meta">';
//$shareXfbml .= '<meta name="medium" content="mult"/>';
//$shareXfbml .= '<meta name="title" content="name of fan page"/>';
//$shareXfbml .= '<meta name="description" content="description of fan page"/>';
//$shareXfbml .= '<link rel="image_src" href="url to image location" />';
//$shareXfbml .= '<link rel="target_url" href="http://facebook.com/Anti.Social.Development"/>';
//$shareXfbml .= '</fb:share-button>';

		return $shareXfbml;
	}
	function getShareJavaScript( $plgConfig, $curPageURL ) {
		$shareJavaScript = '<a name="fb_share" type="'.$plgConfig['share_btn_style'].'" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
		return $shareJavaScript;
	}
	function getXfbmlLikeBtn( $plgConfig, $curPageURL ) {
	  	if ($plgConfig['font']) {
  			$font = str_replace('+',' ', $plgConfig['font']);
  			$fontString .= ' font="' . $font . '"';
  		} else {
  			$fontString = '';
  		}
		$html = '<fb:like href="' . $curPageURL . '" layout="' . $plgConfig['layout_style'] . '"';
		if ($plgConfig['show_faces'] == 1) {
			$html .= ' show_faces="true"';
		} else {
			$html .= ' show_faces="false"';
		}
        $style = '';
		$html .= ' width="' . $plgConfig['width'] . '" action="' . $plgConfig['verb'] . '" font="' . $fontString . '" style="'.$style.'" colorscheme="' . $plgConfig['colour_scheme'] . '"></fb:like>';
        return $html;
	}
	function getIFrameLikeBtn( $plgConfig, $curPageURL ) {
		//$html = '<iframe scrolling="no" frameborder="0" style="vertical-align: middle; border: medium none; overflow: hidden; width: 500px; height: 30px;float:left;" allowtransparency="true" src="http://www.facebook.com/plugins/like.php?href='.$curPageURL.'&amp;layout='.$plgConfig['layout_style'].'&amp;show-faces=false&amp;width=100&amp;action=like&amp;colorscheme=light"></iframe>'; 	// Mashable
		$html = '<iframe src="http://www.facebook.com/plugins/like.php?href='.$curPageURL.'&amp;layout='.$plgConfig['layout_style'].'&amp;';
		if ($plgConfig['show_faces'] == 1) {
			$html .= 'show_faces=true';
		} else {
			$html .= 'show_faces=false';
		}
		if ($plgConfig['width']) {
			$html .= '&amp;width='.$plgConfig['width'];
		}
		if ($plgConfig['height']) {
			$html .= '&amp;height='.$plgConfig['height'];
		}
		$html .= '&amp;action='.$plgConfig['verb'].'&amp;font=segoe+ui&amp;colorscheme='.$plgConfig['colour_scheme'];
		$html .= '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; float:left;';
		if ($plgConfig['width']) {
			$html .= ' width:'.$plgConfig['width'].'px;';
		}
		if ($plgConfig['height']) {
			$html .= ' height:'.$plgConfig['height'].'px;';
		}
		$html .= '"></iframe>';
		return $html;
	}
    function getTwitterBtn( $plgConfig, $curPageURL, $article ) {
//        $twitterHtml  = '<a href="http://twitter.com/share" class="twitter-share-button" data-count="' . $plgConfig['twitter_btn_style'];
//        $twitterHtml .= '" data-via="' . $plgConfig['twitter_id'] . '">Tweet</a>';
//        $twitterHtml .= '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
        $twitterHtml  = '<a href="http://twitter.com/share"';
        $twitterHtml .= ' class="twitter-share-button"';
        if ($plgConfig['twitter_btn_twt_text']) {
            $twitterHtml .= ' data-text="' . $plgConfig['twitter_btn_twt_text'] . '"';
        } elseif (!empty($article->title)) {
            $twitterHtml .= ' data-text="' . $article->title . '"';
        } else {
            $twitterHtml .= ' data-text=""';
        }
        if ($plgConfig['twitter_btn_url']) {
            $twitterHtml .= ' data-url="' . $plgConfig['twitter_btn_url'] . '"';
        } else {
            $twitterHtml .= ' data-url="' . $curPageURL . '"';
        }
        $twitterHtml .= ' data-count="' . $plgConfig['twitter_btn_style'] . '"';
        $twitterHtml .= ' data-via="' . $plgConfig['twitter_id'] . '"';
        if ($plgConfig['twitter_btn_lang']) {
            $twitterHtml .= ' data-lang="' . $plgConfig['twitter_btn_lang'] . '"';
        }
        if ($plgConfig['twitter_btn_rel_acc']) {
            $twitterHtml .= ' data-related="' . $plgConfig['twitter_btn_rel_acc'] . ':' . $plgConfig['twitter_btn_rel_acc_desc'] .'"';
        }
        //$twitterHtml .= '" data-related="' . $plgConfig['twitter_id_2'];
        $twitterHtml .= '>Tweet</a>';
//        $twitterHtml .= '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
        $doc =& JFactory::getDocument();
        $doc->addScript("http://platform.twitter.com/widgets.js");

        return $twitterHtml;
    }
	function getShareThis($plgConfig, &$article) {
		if ($plgConfig['sharethis_popup']) {$popUp = 'popup=true';} else {$popUp = '';}
		$shareThis = '<script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher='.($plgConfig['sharethis_id']).'&amp;type=website&amp;'.$popUp.'&amp;style=horizontal&amp;post_services=email%2Cfacebook%2Ctwitter%2Cgbuzz%2Cmyspace%2Cdigg%2Csms%2Cwindows_live%2Cdelicious%2Cstumbleupon%2Creddit%2Cgoogle_bmarks%2Clinkedin%2Cbebo%2Cybuzz%2Cblogger%2Cyahoo_bmarks%2Cmixx%2Ctechnorati%2Cfriendfeed%2Cpropeller%2Cwordpress%2Cnewsvine"></script>';
		return $shareThis;
	}
	function getTweetMe($plgConfig, &$article) {
		$tweetme = '<script type="text/javascript">tweetmeme_style = \'compact\';</script><script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>';
		return $tweetme;
	}
	function getGoogleBuzz($plgConfig, &$article) {
		$googleBuzzShare = '<a title="Post on Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count" data-locale="en_GB"></a>';
		$googleBuzzShare .= '<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>';
		return $googleBuzzShare;

	}
	function getGooglePlus1($plgConfig, $curPageURL, &$article) {
        $googlePlus1Share = '';
        if (!JFactory::getApplication()->get( 'kasocialsharing_googleplus1.set' )) {
            $googlePlus1Share = '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
            JFactory::getApplication()->set( 'kasocialsharing_googleplus1.set', 1 );
            
        }
		$googlePlus1Share .= '<g:plusone size="medium" href="'.$curPageURL.'"></g:plusone>';
		return $googlePlus1Share;

	}
	function getStumbleUpon($plgConfig, $curPageURL, $article) {
		$stumbleUpon = '<script src="http://www.stumbleupon.com/hostedbadge.php?s=' . $plgConfig['su_btn_style'] . '&r=' . $curPageURL . '"></script>';
//        $stumbleUpon = '<script src="http://www.stumbleupon.com/hostedbadge.php?s=1&r=http://www.ukvisapoints.com/index.php?option=com_content&view=article&id=5&Itemid=2"></script>';
		return $stumbleUpon;
	}
	function getReddit($plgConfig, $curPageURL, $article) {
		$reddit = '<a href="http://reddit.com/submit" onclick="window.location = \'http://reddit.com/submit?url=\' + encodeURIComponent(window.location); return false">';
        $reddit .= '<img src="http://reddit.com/static/spreddit7.gif" alt="submit to reddit" border="0" />';
        $reddit .= '</a>';
		return $reddit;
	}
	function getDigg($plgConfig, $curPageURL, $article) {
        if (!JFactory::getApplication()->get( 'kasocialsharing.digg.set' )) {
            $document	= & JFactory::getDocument();
            $document->addScriptDeclaration('(function() {var s = document.createElement(\'SCRIPT\'), s1 = document.getElementsByTagName(\'SCRIPT\')[0];s.type = \'text/javascript\';s.async = true;s.src = \'http://widgets.digg.com/buttons.js\';s1.parentNode.insertBefore(s, s1);})();');
            JFactory::getApplication()->set( 'kasocialsharing.digg.set', 1 );
        }
        $digg = '<a class="DiggThisButton DiggCompact"></a>';
		return $digg;
	}
    function getLinkedInShare($plgConfig, $curPageURL, &$article) {
        $linkedInShare = '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>';
        $linkedInShare .= '<script type="IN/Share" data-url="'.$curPageURL.'"';
        if ($plgConfig['linkedin_btn_style'] == 1) {
            $linkedInShare .= ' data-counter="right"';
        } elseif ($plgConfig['linkedin_btn_style'] == 2) {
            $linkedInShare .= ' data-counter="top"';
        }
        $linkedInShare .= '></script>';
        return $linkedInShare;
	}
}
?>