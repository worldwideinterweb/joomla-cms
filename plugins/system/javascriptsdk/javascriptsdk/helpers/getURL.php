<?php
	function getURL( &$article, $plgConfig ) {
		switch($plgConfig['base_method']) {
			case 0:
				$base = JURI::base();
				$base = substr_replace($base,"",-1);
				break;
			case 1;
				$base = JURI::root();
				$base = substr_replace($base,"",-1);
				break;
			case 2;
				$uri  =& JURI::getInstance();
				$base = $uri->toString( array('scheme', 'host', 'port'));
				break;
			case 3;
				$base = $plgConfig['custom_base'];
				break;
		}

		switch($plgConfig['jRequest_option']) {
			case 'com_content':
                switch($plgConfig['url_method']) {
                    case 'juri':
                        if ($plgConfig['use_ids']) {
                            $curPageURL	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->sectionid));
                        } else {
                            $curPageURL	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
                        }
                        break;
                    case 'php':
                        $curPageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
                        $curPageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                        break;
                    case 'article_url':
                        if ($plgConfig['use_ids']) {
                            $url = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->sectionid));
                            $curPageURL	= $base . $url;
                        } else {
                            $curPageURL	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
                        }
                        break;
                    case 'request_uri':
                        if (!empty($article->permalink)) {
                            //$url = $article->permalink;
                            $curPageURL = $base . JRoute::_( $article->permalink );
                        } else {
                            //$url = $_SERVER['REQUEST_URI'];
                            $curPageURL = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                        }
                        break;
                }
				return $curPageURL;
				break;
				
			case 'com_k2':
                switch ($plgConfig['jRequest_view']) {
                    case 'itemlist':
                        $curPageURL = $base . $article->link;
                        $curPageURL = JRoute::_($curPageURL);
                        return $curPageURL;
                    default:
                        $url = $_SERVER['REQUEST_URI'];
                        $curPageURL = $base . JRoute::_( $url );
                        return $curPageURL;
                }
			case 'com_virtuemart':
// Uncomment to change back to old method if needed.
//				$router =& JSite::getRouter();
//				$url = 'index.php?'.JURI::buildQuery($router->getVars());
                $url = $_SERVER['REQUEST_URI'];
                $curPageURL = $base . JRoute::_( $url . '&vmcchk=1' );
                return $curPageURL;
				break;
				
//			case 'com_zoo':
//				$curPageURL  = self::getAllUrlVars($plgConfig);
//				$curPageURL = JRoute::_($curPageURL);			
//				return $base . $curPageURL;
//				break;
				
			case 'com_quickfaq':
				$curPageURL  = self::getAllUrlVars($plgConfig);
//				JRoute::_('index.php?view=items&id='.$model->get('id'), false)
				$curPageURL = JRoute::_($curPageURL);	
				return $base . $curPageURL;
				break;

			case 'com_eventlist':
				$curPageURL  = self::getAllUrlVars($plgConfig);
				$curPageURL = JRoute::_($curPageURL);
				return $base . $curPageURL;
				break;
				
            case 'com_mtree';
                $curPageURL = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                return $curPageURL;
                break;
            
			default:
				break;
		}
	}
	function getAllUrlVars( $plgConfig ) {
		$curPageURL = 'index.php?option=' . $plgConfig['jRequest_option'];
		if (!empty($plgConfig['jRequest_view'])) { $curPageURL 		.= '&view=' . 	$plgConfig['jRequest_view']; }
		if (!empty($plgConfig['jRequest_layout'])) { $curPageURL	.= '&layout=' . $plgConfig['jRequest_layout']; }
		if (!empty($plgConfig['jRequest_task'])) { $curPageURL		.= '&task=' . 	$plgConfig['jRequest_task']; }
		if (!empty($plgConfig['jRequest_item_id'])) { $curPageURL	.= '&item_id=' .$plgConfig['jRequest_item_id']; }
		if (!empty($plgConfig['jRequest_cid'])) { $curPageURL		.= '&cid=' .	$plgConfig['jRequest_cid']; }
		if (!empty($plgConfig['jRequest_id'])) { $curPageURL		.= '&id=' .		$plgConfig['jRequest_id']; }
		if (!empty($plgConfig['jRequest_Itemid'])) { $curPageURL	.= '&Itemid='.	$plgConfig['jRequest_Itemid']; }
		return $curPageURL;
	}
?>
