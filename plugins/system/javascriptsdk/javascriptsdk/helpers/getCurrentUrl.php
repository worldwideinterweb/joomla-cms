<?php
	function getCurrentUrl( &$article, $params ) {
		switch($params->get('base_method')) {
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
				$base = $params->get('custom_base');
				break;
		}

		switch(JRequest::getCmd('option')) {
			case 'com_content':
                switch($params->get('url_method')) {
                    case 'juri':
                        if ($params->get('use_ids')) {
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
                        if ($params->get('use_ids')) {
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
                switch ($params->get('view')) {
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
				
			case 'com_zoo':
				$curPageURL  = self::getAllUrlVars($params);
				$curPageURL = JRoute::_($curPageURL);			
				return $base . $curPageURL;
				break;
				
			case 'com_quickfaq':
				$curPageURL  = self::getAllUrlVars($params);
//				JRoute::_('index.php?view=items&id='.$model->get('id'), false)
				$curPageURL = JRoute::_($curPageURL);	
//				return urlencode('http://www.ukvisapoints.com/index.php?view=items&cid=1%3Aquick-faq-category&id=1%3Afaq-item-one&option=com_quickfaq&Itemid=77');
				return $base . $curPageURL;
				break;

			case 'com_eventlist':
				$curPageURL  = self::getAllUrlVars($params);
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
	function makeUrl( $params ) {
        $option = JRequest::getCmd('option');
        $view   = JRequest::getCmd('view');
        $layout = JRequest::getCmd('layout');
        $task   = JRequest::getCmd('task');
        $item_id= JRequest::getInt('item_id');
        $cid    = JRequest::getInt('cid');
        $id     = JRequest::getInt('id');
        $Itemid = JRequest::getInt('Itemid');
        
		$curPageURL = 'index.php?option=' . $option;
		if (!empty($view)) { $curPageURL    .= '&view=' . 	$view; }
		if (!empty($layout)) { $curPageURL	.= '&layout=' . $layout; }
		if (!empty($task)) { $curPageURL	.= '&task=' . 	$task; }
		if (!empty($item_id)) { $curPageURL	.= '&item_id=' .$item_id; }
		if (!empty($cid)) { $curPageURL		.= '&cid=' .	$cid; }
		if (!empty($id)) { $curPageURL		.= '&id=' .		$id; }
		if (!empty($Itemid)) { $curPageURL	.= '&Itemid='.	$Itemid; }
		return $curPageURL;
	}
?>
