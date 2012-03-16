<?php
/**
 * KA JavaScriptSDK Helper
 * @version		1.0.0
 */
// no direct access 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.helper');

class Kajssdkhelper {
	function setOpenGraphTags( $plgConfig, &$article, $curPageUrl ) {
        if (JFactory::getApplication()->get( 'kaframework_firstrun.set' )) { return; }
		$document	=& JFactory::getDocument();
		$config     =& JFactory::getConfig();

		switch($plgConfig['jRequest_option']) {
			case 'com_virtuemart':
                if ($plgConfig['virtuemart']['page'] == 'shop.product_details') {
                    $db	=& JFactory::getDBO();
                    $query = "SELECT DISTINCT product_name,product_thumb_image,product_s_desc FROM #__vm_product WHERE product_id = ". $plgConfig['virtuemart']['product_id'] ;
                    $db->setQuery($query);
                    $result = $db->loadAssoc();
                    $ogTitle = $result['product_name'];
                    $ogDescription = $result['product_s_desc'];
                    $ogImageUrl = JURI::base().'components/com_virtuemart/shop_image/product/'.$result['product_thumb_image'];
                } elseif ($plgConfig['virtuemart']['page'] == 'shop.browse') {
                    $db	=& JFactory::getDBO();
                    $query = "SELECT DISTINCT category_name,category_description,category_thumb_image FROM #__vm_category WHERE category_id = ". $plgConfig['virtuemart']['category_id'] ;
                    $db->setQuery($query);
                    $result = $db->loadAssoc();
                    $ogTitle = $result['category_name'];
                    $ogDescription = $result['category_description'];
                    $ogImageUrl = JURI::base().'components/com_virtuemart/shop_image/category/'.$result['category_thumb_image'];
                }
                break;
            case 'com_k2':
                if (!empty($article->title))
                    $ogTitle = $article->title;
                if (!empty($article->metadesc))
                    $ogDescription  = $article->metadesc;
                if (!empty($article->imageMedium)){
                    $ogImageUrl = $article->imageMedium;
                    if (!empty($ogImageUrl)) { $ogImageUrl = JURI::root() . $ogImageUrl; }
                }
                break;
            default:
                if (!empty($article->title))
                    $ogTitle = $article->title;
                if (!empty($article->metadesc))
                    $ogDescription  = $article->metadesc;
                if ($plgConfig['fb_image_tag']) {
                    //$pattern = '/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'\s>]*)/i';
                    //$pattern = '<\s*(img)[^>]*(src)\s*=\s*[quote]\s*([^"]*\/)*[^"\s]+\s*[quote][^>]*>';
                    //$pattern = "/(((http://www)|(http://)|(www))[-a-zA-Z0-9@:%_\+.~#?&//=]+)\.(jpg|jpeg|gif|png|bmp|tiff|tga|svg)/g";
                    $pattern = "/<img[^>]*src\=['\"]?(([^>]*)(jpg|gif|png|jpeg))['\"]?/";
                    preg_match($pattern, $article->text, $matches);
                    if(!empty($matches)){
                        $imageUrl = $matches[1];
                        if (!strstr($imageUrl, 'http://')) { $ogImageUrl = JURI::root() . $imageUrl; }
                    }
                }
                break;
        }
        $document->addCustomTag( '<meta property="og:site_name" content="' . $config->getValue('sitename') . '" />' );
        $document->addCustomTag( '<meta property="og:url" content="' . $curPageUrl . '" />' );
        if(!empty($ogTitle)) {
            $document->addCustomTag( '<meta property="og:title" content="' . $ogTitle . '" />' );
        }
        if(!empty($ogDescription)) {
            $document->addCustomTag( '<meta property="og:description" content="' . $ogDescription . '" />' );
        }
        if(!empty($ogImageUrl)) {
            $document->addCustomTag( '<meta property="og:image" content="'.$ogImageUrl.'" />' );
        }
        if(!empty($plgConfig['fb_admin_ids'])) {
            $document->addCustomTag( '<meta property="fb:admins" content="' . $plgConfig['fb_admin_ids'] . '" />' );
        }
		JFactory::getApplication()->set( 'kaframework_firstrun.set', 1 );
	}
	function getXid(&$article, $params, $curPageUrl) {
        $db = JFactory::getDbo();
        $db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_kaopengraphtags'");
        $kaopt_enabled = ($db->loadResult() == 1);
        
        if($kaopt_enabled) {
            $db =& JFactory::getDBO();
            
            $table          = $db->nameQuote('#__kaopengraphtags_pagesitems');
            $colComponent   = $db->nameQuote('component');
            $valComponent   = $db->Quote(JRequest::getCmd('option'));
            $colObjectId    = $db->nameQuote('object_id');
            $valObjectId    = $db->Quote($article->id);
            $colXid         = $db->nameQuote('xid');
            $valXid         = $db->Quote($curPageUrl);
            $colTitle       = $db->nameQuote('title');
            $valTitle       = $db->Quote($article->title);
            $colPublished   = $db->nameQuote('published');

            $query = "SELECT $colXid FROM $table WHERE $colComponent=$valComponent AND $colObjectId=$valObjectId";
            $db->setQuery( $query );
            $xid = $db->loadResult();

            if( !$xid ) {                
//                if ($plgConfig['jRequest_option'] == 'com_virtuemart') {
//                    $query = "SELECT product_name FROM #__vm_product WHERE `product_id`='".$plgConfig['virtuemart']['product_id']."'";
//                    $db->setQuery( $query );
//                    $article->title = $db->loadResult();
//                    $article->id = $plgConfig['virtuemart']['product_id'];
//                }

                $query = "INSERT INTO $table SET $colXid=$valXid, $colTitle=$valTitle, $colComponent=$valComponent, $colObjectId=$valObjectId, $colPublished='1', firstshown=NOW()";
                $db->setQuery( $query );
                $result = $db->query();
                if (!$result) {
                    $app =& JFactory::getApplication();
                    $app->enqueueMessage(nl2br($db->getErrorMsg()),'error');
                }
//    			$query = "INSERT INTO #__kafacebook_ogtags SET `object_id`='$article->id'";
//    			$db->setQuery( $query );
//    			$db->query();
            }
            //    JError::raiseError('SOME_ERROR', JText('Module requires the Some Extension component'));
        }
        // else {
//            $xid = urlencode($curPageUrl);
        //}
		return $valXid;
	}
    // Latest OgTags Function
    function setOgTags($pageData, $plgConfig) {
        if (JFactory::getApplication()->get( 'kaframework_firstrun.set' )) { return; }
		$document	=& JFactory::getDocument();
		$config     =& JFactory::getConfig();
        $db = JFactory::getDbo();
        $db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_kaopengraphtags'");
        $kaopt_enabled = ($db->loadResult() == 1);
        
        if($kaopt_enabled) {
            $db =& JFactory::getDBO();
            
            $tblPages         = $db->nameQuote('#__kaopengraphtags_pagesitems');
            $tblTags          = $db->nameQuote('#__kaopengraphtags_ogtags');

            $colComponent   = $db->nameQuote('component');
            $valComponent   = $db->Quote(JRequest::getCmd('option'));
            $colId          = $db->nameQuote('id');

            $colXid         = $db->nameQuote('xid');
            $valXid         = $db->Quote($pageData['url']);
            $colTitle       = $db->nameQuote('title');
            $valTitle       = $db->Quote($pageData['title']);
            $colPublished   = $db->nameQuote('published');

            $query = "SELECT $colId FROM $tblPages WHERE $colXid=$valXid";
            $db->setQuery( $query );
            $resultId = $db->loadResult();

            if (empty($resultId)) {
                $query = "INSERT INTO $tblPages SET $colXid=$valXid, $colTitle=$valTitle, $colComponent=$valComponent, $colPublished='1', firstshown=NOW()";
                $db->setQuery( $query );
                $result = $db->query();
                if (!$result) {
                    $app =& JFactory::getApplication();
                    $app->enqueueMessage(nl2br($db->getErrorMsg()),'error');
                }
            }
            // Register the component helper
            $helperTagsPath = JPATH_BASE."/administrator/components/com_kaopengraphtags/helpers/katagshelper.php";
            JLoader::register("KaTagsHelper", $helperTagsPath );
            $tags = KaTagsHelper::getTags($pageData['url']);
            KaTagsHelper::setTags($tags, $pageData, $plgConfig);
            KaTagsHelper::hits($pageData['url']);
                
        // Backward compatability
        } else {
            //$document->_metaTags["property"] = array('og:test' => 'test');
            if ($plgConfig['fb_app_id']) {
                $document->addCustomTag( '<meta property="fb:app_id" content="' . $plgConfig['fb_app_id'] . '" />' );
                //$document->setMetadata('og:app_id', $plgConfig['fb_app_id']);
            }
            if ($plgConfig['fb_admins']) {
                $document->addCustomTag( '<meta property="fb:admins" content="' . $plgConfig['fb_admins'] . '" />' );
                //$document->setMetadata('og:admins', $plgConfig['fb_admins']);
            }
            if ($plgConfig['fb_page_id']) {
                $document->addCustomTag( '<meta property="fb:page_id" content="' . $plgConfig['fb_page_id'] . '" />' );
                //$document->setMetadata('og:page_id', $plgConfig['fb_page_id']);
            }
            if ($plgConfig['enable_og_title'] && !empty($ogTitle)) {
                $document->addCustomTag( '<meta property="og:title" content="' . $ogTitle . '" />' );
                //$document->setMetadata('og:title', $ogTitle);
            }
            if ($plgConfig['enable_og_type']) {
                if ($plgConfig['og_custom_type']) {
                    $type = $plgConfig['og_custom_type'];
                } else {
                    $type = $plgConfig['og_type'];
                }
                $document->addCustomTag( '<meta property="og:type" content="' . $type . '" />' );
                //$document->setMetadata('og:type', $type);
            }
            if ($plgConfig['enable_og_url']) {
                if ($plgConfig['custom_og_url']) {
                    $document->addCustomTag( '<meta property="og:url" content="' . $plgConfig['custom_og_url'] . '" />' );
                    //$document->setMetadata('og:url', $plgConfig['custom_og_url']);
                } else {
                    $document->addCustomTag( '<meta property="og:url" content="' . $pageData['url'] . '" />' );
                    //$document->setMetadata('og:url', $pageData['url']);
                }
            }
            if ($plgConfig['enable_og_site_name']) {
                if ($plgConfig['custom_og_site_name']) {
                    $document->addCustomTag( '<meta property="og:site_name" content="' . $plgConfig['custom_og_site_name'] . '" />' );
                    //$document->setMetadata('og:site_name', $plgConfig['custom_og_site_name']);
                } else {
                    $document->addCustomTag( '<meta property="og:site_name" content="' . $config->getValue('sitename') . '" />' );
                    //$document->setMetadata('og:site_name', $config->getValue('sitename']);
                }
            }
            if($plgConfig['enable_og_image']) {
                if($plgConfig['custom_og_image']) {
                    $document->addCustomTag( '<meta property="og:image" content="'.$plgConfig['custom_og_image'].'" />' );
                    //$document->setMetadata('og:image', $plgConfig['custom_og_image']);
                } else if (!empty($pageData['image_url'])) {
                    $document->addCustomTag( '<meta property="og:image" content="'.$pageData['image_url'].'" />' );
                    //$document->setMetadata('og:image', $ogImageUrl);
                }
            }
            if ($plgConfig['enable_og_description']) {
                if ($plgConfig['custom_og_description']) {
                    $document->addCustomTag( '<meta property="og:description" content="' . $plgConfig['custom_og_description'] . '" />' );
                    //$document->setMetadata('og:description', $plgConfig['custom_og_description']);
                } else if (!empty($ogDescription)) {
                    $document->addCustomTag( '<meta property="og:description" content="'.$ogDescription.'" />' );
                    //$document->setMetadata('og:description', $ogDescription);
                }
            }
        }
    }
    function getPageData( &$article, $plgConfig ) {
        $pageData = array();
        $title = '';
        $ogTitle = '';
        $description = '';
        $ogDescription = '';
        $ogImageUrl = '';
        switch(JRequest::getCmd('option')) {
            case 'com_virtuemart':
                if (JRequest::getVar('page') == 'shop.product_details') {
                    $db	=& JFactory::getDBO();
                    $query = "SELECT DISTINCT product_name,product_thumb_image,product_s_desc FROM #__vm_product WHERE product_id = ". JRequest::getInt('product_id') ;
                    $db->setQuery($query);
                    $result = $db->loadAssoc();
                    $title = $ogTitle = $result['product_name'];
                    $description = $ogDescription = $result['product_s_desc'];
                    $ogImageUrl = JURI::base().'components/com_virtuemart/shop_image/product/'.$result['product_thumb_image'];
                } elseif (JRequest::getVar('page') == 'shop.browse') {
                    $db	=& JFactory::getDBO();
                    $query = "SELECT DISTINCT category_name,category_description,category_thumb_image FROM #__vm_category WHERE category_id = ". JRequest::getInt('category_id') ;
                    $db->setQuery($query);
                    $result = $db->loadAssoc();
                    $title = $ogTitle = $result['category_name'];
                    $description = $ogDescription = $result['category_description'];
                    $ogImageUrl = JURI::base().'components/com_virtuemart/shop_image/category/'.$result['category_thumb_image'];
                }
                break;
            case 'com_k2':
                if (!empty($article->title))
                    $title = $ogTitle = $article->title;
                if (!empty($article->metadesc))
                    $description = $ogDescription  = $article->metadesc;
                if (!empty($article->imageMedium)){
                    $ogImageUrl = $article->imageMedium;
                    if (!empty($ogImageUrl)) { $ogImageUrl = JURI::root() . $ogImageUrl; }
                }
                break;
            case 'com_content':
                $doc =& JFactory::getDocument();
                $title = $doc->getTitle();
                $description = $doc->description;
                
                if (!empty($article->title))
                    $ogTitle = $article->title;
                if (!empty($article->metadesc)) {
                    $ogDescription  = $article->metadesc;
                } else {
                    $ogDescription  = $plgConfig['custom_og_description'];
                }
                if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6
                    $ogImageUrl = $this->getFirstImage($article->introtext);
                } else {
                    $ogImageUrl = $this->getFirstImage($article->text);
                }
                break;
                
            default:
                if (!empty($article->title))
                    $ogTitle = $article->title;
                if (!empty($article->metadesc)) {
                    $ogDescription  = $article->metadesc;
                } else {
                    $ogDescription  = $plgConfig['custom_og_description'];
                }
                $ogImageUrl = $this->getFirstImage($article->text);
                break;
        }
        $pageData['title'] = $title;
        $pageData['og_title'] = $ogTitle;
        $pageData['desc'] = $description;
        $pageData['og_desc'] = $ogDescription;
        $pageData['image_url'] = $ogImageUrl;
        $pageData['url'] = self::getJssdkUrl( $article, $plgConfig );
        
//$mydoc = JFactory::getDocument();
//dump($mydoc, '$mydoc');
//dump($mydoc->getTitle(), 'title');
//dump($article, '$article');
//dump($pageData, '$pageData');

        return $pageData;
    }
    function getArticleURL( &$article ) {
		$this->base = $base = JUri::base(false);
		$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->sectionid));
		if (substr($url, 0, 1) == '/') {
			$url = substr($url, 1);
		}
		return $this->base . $url;
	}
	function checkMenuIds ($plgConfig) {
        if ($plgConfig['menu_display_mode'] == -1 || !isset($plgConfig['menuIDs'])) { return true; }
		$listMenus = array();
  		$listMenus = explode(',', $plgConfig['menuIDs']);

        $menuIdsFound = 0;
        $menus =& JSite::getMenu();
        $currentMenu = $menus->getActive(); 
  		if ((is_object($currentMenu) && in_array($currentMenu->id, $listMenus)) ) { $menuIdsFound = 1; }
  		unset($listMenus);
  		
  		if ($plgConfig['menu_display_mode'] == 1) {
			if ($menuIdsFound == 1) { return false; } else { return true; }
		} else if ($plgConfig['menu_display_mode'] == 0) {
			if ($menuIdsFound == 1) { return true; } else { return false; }
		}
	}
	function checkCanShow( &$article, $plgConfig ) {
        if (!empty($plgConfig['view_restrictions'])) {
            if (($plgConfig['jRequest_view'] == 'article') && (!empty($plgConfig['view_restrictions']['view_article']))) return false;
            if (($plgConfig['jRequest_view'] == 'frontpage') && (!empty($plgConfig['view_restrictions']['view_frontpage']))) return false;
            if (($plgConfig['jRequest_view'] == 'featured') && (!empty($plgConfig['view_restrictions']['view_featured_articles']))) return false; //1.6 and 1.7
            if (($plgConfig['jRequest_view'] == 'category') && ($plgConfig['jRequest_layout'] == 'blog') && (!empty($plgConfig['view_restrictions']['view_cat_layout_blog']))) return false;
            if (($plgConfig['jRequest_view'] == 'category') && ($plgConfig['jRequest_layout'] != 'blog') && (!empty($plgConfig['view_restrictions']['view_cat']))) return false;
            if (($plgConfig['jRequest_view'] == 'section') && ($plgConfig['jRequest_layout'] == 'blog') && (!empty($plgConfig['view_restrictions']['view_sec_layout_blog']))) return false;
            if (($plgConfig['jRequest_view'] == 'section') && ($plgConfig['jRequest_layout'] != 'blog') && (!empty($plgConfig['view_restrictions']['view_sec']))) return false;
        }
		switch($plgConfig['jRequest_option']) {
			case 'com_content':
                if ($plgConfig['display_mode'] == -1) { return true; }
				$foundSection = 0;
				$foundCategory = 0;
				$foundArticle = 0;
				if (!empty($plgConfig['sectionIDs'])) {
					$listSection = array();
		  			$listSection = @explode ( ",", $plgConfig['sectionIDs'] );
		  			if (in_array($article->sectionid, $listSection) == true) { $foundSection = 1; }
		  			unset($listSection);
		  		}
			  	if (!empty($plgConfig['categoryIDs'])) {
		  			$listCategory = array();
		  			$listCategory = explode(',',$plgConfig['categoryIDs']);
		  			if (in_array($article->catid, $listCategory) == true) { $foundCategory = 1; }
					unset($listCategory);
		  		}
			  	if (!empty($plgConfig['articleIDs'])) {
			  		$listArticle = array();
		  			$listArticle = explode(',',$plgConfig['articleIDs']);
		  			if (in_array($article->id, $listArticle) == true ) { $foundArticle = 1; }
			  		unset($listArticle);
		  		}
		  		if ($plgConfig['display_mode'] == 1) {
					if ( ($foundSection == 1) || ($foundCategory == 1) || ($foundArticle == 1 ) ) { return false; } else { return true; }
				} else if ($plgConfig['display_mode'] == 0) {
					if ( ($foundSection == 1) || ($foundCategory == 1) || ($foundArticle == 1 ) ) { return true; } else { return false; }
				}
				break;
                
			case 'com_k2':
				switch ($plgConfig['jRequest_view']) {
					case 'item':
						if (!empty($plgConfig['integrations']['k2_items']) && ($plgConfig['integrations']['k2_items'] == 'k2_items') && (!defined('KA_FB_COMMENTS_FLAG'))) {
                            define('KA_FB_COMMENTS_FLAG', 1);
                            return true;
                        } else {
                            return false;
                        }
						break;
					case 'itemlist':
						if (!empty($plgConfig['integrations']['k2_cats']) && $plgConfig['integrations']['k2_cats'] == 'k2_cats') { return true; } else { return false; }
						break;
					case 'latest':
						if (!empty($plgConfig['integrations']['k2_latest_items']) && $plgConfig['integrations']['k2_latest_items'] == 'k2_latest_items') { return true; } else { return false; }
						break;
					default:
						return false;
						break;
				}
				break;
                
			case 'com_virtuemart':
				if (!empty($plgConfig['integrations']['virtuemart_category']) && ($plgConfig['integrations']['virtuemart_category'] == 'virtuemart_category') && ($plgConfig['virtuemart']['page'] == 'shop.browse')) {
                    return true;
				} elseif (!empty($plgConfig['integrations']['virtuemart_products']) && ($plgConfig['integrations']['virtuemart_products'] == 'virtuemart_products') && ($plgConfig['virtuemart']['page'] == 'shop.product_details')) {
                    return true;
                } else {
                    return false;
                }
				break;
                
			case 'com_zoo':
				if ($plgConfig['jRequest_view']) {
					switch ($plgConfig['jRequest_view']) {
						case 'item':
                            // Zoo can have multiple texareas, to stop it for multiple text areas check and set flag
                            if (JFactory::getApplication()->get( 'kaframework_firstrun.set' )) { return false; }
							if (!empty($plgConfig['integrations']['zoo_items']) && $plgConfig['integrations']['zoo_items'] == 'zoo_items') { return true; } else { return false; }
							break;
/*						case 'category':
							if ($plgConfig['integrations']['zoo_cats'] == 'zoo_cats') { return true; } else { return false; }
							break;*/
						default:
							return false;
							break;
					}
				} else {
					if ($plgConfig['integrations']['zoo_items'] == 'zoo_items') { return true; } else { return false; }
				}
				break;

			case 'com_quickfaq':
				switch ($plgConfig['jRequest_view']) {
					case 'items':
						if (!empty($plgConfig['integrations']['quick_faq_items']) && $plgConfig['integrations']['quick_faq_items'] == 'quick_faq_items') { return true; } else { return false; }
						break;
//					case 'category':
//						if ($plgConfig['integrations']['zoo_cats'] == 'zoo_cats') { return true; } else { return false; }
//						break;
					default:
						return false;
						break;
				}
                break;

			case 'com_eventlist':
				switch ($plgConfig['jRequest_view']) {
					case 'details':
                        if (JFactory::getApplication()->get( 'kaframework_firstrun.set' )) { return false; }
						if (!empty($plgConfig['integrations']['eventlist_detail_layout']) && $plgConfig['integrations']['eventlist_detail_layout'] == 'eventlist_detail_layout') { return true; } else { return false; }
						break;
					default:
						return false;
						break;
				}
				break;
                
			case 'com_sobi2':
				return true;
				break;
            
            case 'com_mtree':
                if (!empty($plgConfig['integrations']['mosets_tree_page'])) {
                    if (!empty($plgConfig['jRequest_task']) && ($plgConfig['jRequest_task'] == 'viewlink')) { return true; } else { return false; };
                }
                break;

			case 'com_hikashop':
                if (!empty($plgConfig['integrations']['hikashop_product']) && $plgConfig['integrations']['hikashop_product'] == 'hikashop_product') { return true; } else { return false; }
				break;

			default:
				return false;
				break;
		}
	}
	function getJ25ContentUrl(&$article) {
		$catSlug = '';
		if(isset($article->catslug))
			$catSlug = $article->catslug;
		else if(strlen($article->category_alias))
			$catSlug = $article->catid . ':' . $article->category_alias;
		else
			$catSlug = $article->catid;
		$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $catSlug));
        $url = JURI::base() . substr($url, strpos($url, '/', 0) + 1);
        
        return $url;
    }
    function getJssdkUrl( &$article, $plgConfig ) {
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
        
		switch(JRequest::getCmd('option')) {
			case 'com_content':
                if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6+
                    $curPageUrl = Kajssdkhelper::getJ25ContentUrl($article);
                } else {
                    switch($plgConfig['url_method']) {
                        case 'juri':
                            if ($plgConfig['use_ids']) {
                                $curPageUrl	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->sectionid));
                            } else {
                                $curPageUrl	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
                            }
                            break;
                        case 'php':
                            $curPageUrl = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
                            $curPageUrl .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                            break;
                        case 'article_url':
                            if ($plgConfig['use_ids']) {
                                $url = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->sectionid));
                                $curPageUrl	= $base . $url;
                            } else {
                                $curPageUrl	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
                            }
                            break;
                        case 'request_uri':
                            if (!empty($article->permalink)) {
                                //$url = $article->permalink;
                                $curPageUrl = $base . JRoute::_( $article->permalink );
                            } else {
                                //$url = $_SERVER['REQUEST_URI'];
                                $curPageUrl = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                            }
                            break;
                    }
                }
				return $curPageUrl;
				break;
				
			case 'com_k2':
                switch ($plgConfig['jRequest_view']) {
                    case 'itemlist':
                        $curPageUrl = $base . $article->link;
                        $curPageUrl = JRoute::_($curPageUrl);
                        return $curPageUrl;
                    default:
                        $url = $_SERVER['REQUEST_URI'];
                        $curPageUrl = $base . JRoute::_( $url );
                        return $curPageUrl;
                }
			case 'com_virtuemart':
// Uncomment to change back to old method if needed.
//				$router =& JSite::getRouter();
//				$url = 'index.php?'.JURI::buildQuery($router->getVars());
                $url = $_SERVER['REQUEST_URI'];
                $curPageUrl = $base . JRoute::_( $url . '&vmcchk=1' );
                return $curPageUrl;
				break;
				
			case 'com_zoo':
				$curPageUrl  = self::makeUrl($params);
				$curPageUrl = JRoute::_($curPageUrl);			
				return $base . $curPageUrl;
				break;
				
			case 'com_quickfaq':
				$curPageUrl  = self::makeUrl($params);
//				JRoute::_('index.php?view=items&id='.$model->get('id'), false)
				$curPageUrl = JRoute::_($curPageUrl);	
//				return urlencode('http://www.ukvisapoints.com/index.php?view=items&cid=1%3Aquick-faq-category&id=1%3Afaq-item-one&option=com_quickfaq&Itemid=77');
				return $base . $curPageUrl;
				break;

			case 'com_eventlist':
				$curPageUrl  = self::makeUrl($params);
				$curPageUrl = JRoute::_($curPageUrl);
				return $base . $curPageUrl;
				break;
				
            case 'com_mtree';
                $curPageUrl = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                return $curPageUrl;
                break;
            
			default:
                $curPageUrl = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                return $curPageUrl;
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
        
		$curPageUrl = 'index.php?option=' . $option;
		if (!empty($view)) { $curPageUrl    .= '&view=' . 	$view; }
		if (!empty($layout)) { $curPageUrl	.= '&layout=' . $layout; }
		if (!empty($task)) { $curPageUrl	.= '&task=' . 	$task; }
		if (!empty($item_id)) { $curPageUrl	.= '&item_id=' .$item_id; }
		if (!empty($cid)) { $curPageUrl		.= '&cid=' .	$cid; }
		if (!empty($id)) { $curPageUrl		.= '&id=' .		$id; }
		if (!empty($Itemid)) { $curPageUrl	.= '&Itemid='.	$Itemid; }
		return $curPageUrl;
	}
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
                        if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6
                            $curPageUrl = ' '.JURI::root().ContentHelperRoute::getArticleRoute($article->id, $article->catid);
                        } else {
                            if ($plgConfig['use_ids']) {
                                $curPageUrl	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->sectionid));
                            } else {
                                $curPageUrl	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
                            }
                        }
                        break;
                    case 'php':
                        if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6
                            $curPageUrl = ' '.JURI::root().ContentHelperRoute::getArticleRoute($article->id, $article->catid);
                        } else {
                            $curPageUrl = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
                            $curPageUrl .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                        }
                        break;
                    case 'article_url':
                        if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6
                            $curPageUrl = ' '.JURI::root().ContentHelperRoute::getArticleRoute($article->id, $article->catid);
                        } else {
                            if ($plgConfig['use_ids']) {
                                $url = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->sectionid));
                                $curPageUrl	= $base . $url;
                            } else {
                                $curPageUrl	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
                            }
                        }
                        break;
                    case 'request_uri':
                        if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6
                            $curPageUrl = ' '.JURI::root().ContentHelperRoute::getArticleRoute($article->id, $article->catid);
                        } else {
                            if (!empty($article->permalink)) {
                                //$url = $article->permalink;
                                $curPageUrl = $base . JRoute::_( $article->permalink );
                            } else {
                                //$url = $_SERVER['REQUEST_URI'];
                                $curPageUrl = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                            }
                        }
                        break;
                }
				return $curPageUrl;
				break;
				
			case 'com_k2':
                switch ($plgConfig['jRequest_view']) {
                    case 'itemlist':
                        $curPageUrl = $base . $article->link;
                        $curPageUrl = JRoute::_($curPageUrl);
                        return $curPageUrl;
                    default:
                        $url = $_SERVER['REQUEST_URI'];
                        $curPageUrl = $base . JRoute::_( $url );
                        return $curPageUrl;
                }
			case 'com_virtuemart':
// Uncomment to change back to old method if needed.
//				$router =& JSite::getRouter();
//				$url = 'index.php?'.JURI::buildQuery($router->getVars());
                $url = $_SERVER['REQUEST_URI'];
                $curPageUrl = $base . JRoute::_( $url . '&vmcchk=1' );
                return $curPageUrl;
				break;
				
			case 'com_zoo':
				$curPageUrl  = self::getAllUrlVars($plgConfig);
				$curPageUrl = JRoute::_($curPageUrl);			
				return $base . $curPageUrl;
				break;
				
			case 'com_quickfaq':
				$curPageUrl  = self::getAllUrlVars($plgConfig);
//				JRoute::_('index.php?view=items&id='.$model->get('id'), false)
				$curPageUrl = JRoute::_($curPageUrl);	
				return $base . $curPageUrl;
				break;

			case 'com_eventlist':
				$curPageUrl  = self::getAllUrlVars($plgConfig);
				$curPageUrl = JRoute::_($curPageUrl);
				return $base . $curPageUrl;
				break;
				
            case 'com_mtree';
                $curPageUrl = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                return $curPageUrl;
                break;
            
			default:
                $curPageUrl = $base . JRoute::_(  $_SERVER['REQUEST_URI'] );
                return $curPageUrl;
                break;
		}
	}
	function getAllUrlVars( $plgConfig ) {
		$curPageUrl = 'index.php?option=' . $plgConfig['jRequest_option'];
		if (!empty($plgConfig['jRequest_view'])) { $curPageUrl 		.= '&view=' . 	$plgConfig['jRequest_view']; }
		if (!empty($plgConfig['jRequest_layout'])) { $curPageUrl	.= '&layout=' . $plgConfig['jRequest_layout']; }
		if (!empty($plgConfig['jRequest_task'])) { $curPageUrl		.= '&task=' . 	$plgConfig['jRequest_task']; }
		if (!empty($plgConfig['jRequest_item_id'])) { $curPageUrl	.= '&item_id=' .$plgConfig['jRequest_item_id']; }
		if (!empty($plgConfig['jRequest_cid'])) { $curPageUrl		.= '&cid=' .	$plgConfig['jRequest_cid']; }
		if (!empty($plgConfig['jRequest_id'])) { $curPageUrl		.= '&id=' .		$plgConfig['jRequest_id']; }
		if (!empty($plgConfig['jRequest_Itemid'])) { $curPageUrl	.= '&Itemid='.	$plgConfig['jRequest_Itemid']; }
		return $curPageUrl;
	}
    function getConfiguration($plgType='content', $plgName) {
//        $db = JFactory::getDbo();
//        $query = 'SELECT params FROM #__extensions WHERE element="'.$plgName.'"';
//        $db->setQuery($query);
//        $result = $db->loadObjectList();
//        $params = json_decode($result[0]->params, true);
//		  $plgConfig = array();
//        foreach ($params as $key => $value) {
//            $plgConfig[$key] = $value;
//        }
        
        $plugin = JPluginHelper::getPlugin($plgType, $plgName);
//        $pParams = new JRegistry();
//        $pParams->loadJSON($plugin->params);
//        $link = $pParams->get('ordering');
        $plgConfig	= array();
        if (version_compare(JVERSION,'1.6.0','ge')) { // 1.6
            $params = json_decode($plugin->params, true);
            foreach ($params as $key => $value) {
                switch ($key) {
                    case 'view_restrictions':
                        $view_restrictions = $value;
                        if (!empty($view_restrictions)) {
                            if (is_array( $view_restrictions )) {
                                foreach ( $view_restrictions as $k => $v ) {
                                    $plgConfig['view_restrictions'][$v] = $v;
                                }
                            } else {
                                $plgConfig['view_restrictions'][$view_restrictions] = $view_restrictions;
                            }
                        }
                        break;
                    case 'integrations':
                        $displayOptions = $value;
                        if (!empty($displayOptions)) {
                            if (is_array( $displayOptions )) {
                                foreach ( $displayOptions as $k => $v ) {
                                    $plgConfig['integrations'][$v] = $v;
                                }
                            } else {
                                $plgConfig['integrations'][$displayOptions] = $displayOptions;
                            }
                        }
                        break;
                    default:
                        $plgConfig[$key] = $value;
                }
            }
        } else { // 1.5            
//            $plugin =& JPluginHelper::getPlugin($plgType, $plgName);
            $params	= new JParameter( $plugin->params );
            $params = $params->_registry['_default']['data'];
            foreach ($params as $key => $value) {
                switch ($key) {
                    case 'menuIDs':
                    case 'sectionIDs':
                    case 'categoryIDs':
                        if (( !empty( $value )) && (is_array( $value ))) {
                            $plgConfig[$key] = implode( ',', $value );
                        } else {
                            $plgConfig[$key] = $value;
                        }
                        break;
                    case 'view_restrictions':
                        $view_restrictions = $value;
                        if (!empty($view_restrictions)) {
                            if (is_array( $view_restrictions )) {
                                foreach ( $view_restrictions as $k => $v ) {
                                    $plgConfig['view_restrictions'][$v] = $v;
                                }
                            } else {
                                $plgConfig['view_restrictions'][$view_restrictions] = $view_restrictions;
                            }
                        }
                        break;
                    case 'integrations':
                        $displayOptions = $value;
                        if (!empty($displayOptions)) {
                            if (is_array( $displayOptions )) {
                                foreach ( $displayOptions as $k => $v ) {
                                    $plgConfig['integrations'][$v] = $v;
                                }
                            } else {
                                $plgConfig['integrations'][$displayOptions] = $displayOptions;
                            }
                        }
                        break;
                    default:
                        $plgConfig[$key] = $value;
                }
            }
        }

		$plgConfig['jRequest_option']	= JRequest::getCmd('option');
		$plgConfig['jRequest_view']		= JRequest::getCmd('view');
		$plgConfig['jRequest_task']		= JRequest::getCmd('task');
		$plgConfig['jRequest_layout']	= JRequest::getCmd('layout');
		$plgConfig['jRequest_id']		= JRequest::getInt('id');
		$plgConfig['jRequest_item_id']	= JRequest::getInt('item_id');
		$plgConfig['jRequest_cid']		= JRequest::getInt('cid');
		$plgConfig['jRequest_id']		= JRequest::getInt('id');
		$plgConfig['jRequest_Itemid']	= JRequest::getInt('Itemid');

		if ($plgConfig['jRequest_option'] == 'com_virtuemart') {
			$plgConfig['virtuemart']['page'] 		= JRequest::getVar('page');
			$plgConfig['virtuemart']['flypage'] 	= JRequest::getVar('flypage');
			$plgConfig['virtuemart']['product_id'] 	= JRequest::getVar('product_id');
			$plgConfig['virtuemart']['category_id'] = JRequest::getVar('category_id');
		}
        
        return $plgConfig;
    }
    function setContentText( &$article, $finalHtml, $plgConfig ) {
        if(isset($article->text)){
            switch ($plgConfig['display_location']):
                case 'top':
                    $article->text = $finalHtml . $article->text;
                    break;
                case 'bottom':
                    $article->text = $article->text . $finalHtml;
                    break;
                case 'both':
                    $article->text = $finalHtml . $article->text . $finalHtml;
                    break;
            endswitch;
        } else if(isset($article->introtext)) {
            switch ($plgConfig['display_location']):
                case 'top':
                    $article->introtext = $finalHtml . $article->introtext;
                    break;
                case 'bottom':
                    $article->introtext = $article->introtext . $finalHtml;
                    break;
                case 'both':
                    $article->introtext = $finalHtml . $article->introtext . $finalHtml;
                    break;
            endswitch;
        }
    }
	function setFinalHtml ( &$article, $finalHtml, $plgConfig ) {
		switch ($plgConfig['jRequest_option']):
			case 'com_content':
                    Kajssdkhelper::setContentText( $article, $finalHtml, $plgConfig );
				break;
			case 'com_virtuemart':
			case 'com_zoo':
			case 'com_quickfaq';
			case 'com_eventlist';
			case 'com_sobi2':
            case 'com_hikashop':
            case 'com_mtree':
                    switch ($plgConfig['display_location']):
                        case 'top':
                            $article->text = $finalHtml . $article->text;
                            break;
                        case 'bottom':
                            $article->text = $article->text . $finalHtml;
                            break;
                        case 'both':
                            $article->text = $finalHtml . $article->text . $finalHtml;
                            break;
                    endswitch;
				break;
			case 'com_k2':
				if ((!empty($article->introtext) && !stristr($article->introtext,'class="kasocialplugin"')) && (!empty($article->introtext) && !stristr($article->fulltext,'class="kasocialplugin"'))){
                    if ($plgConfig['jRequest_view'] == 'item') {
						switch ($plgConfig['display_location']):
							case 'top':
								$article->introtext = $finalHtml . $article->introtext;
								break;
							case 'bottom':
								$article->fulltext = $article->fulltext . $finalHtml;
								break;
							case 'both':
								$article->introtext = $finalHtml . $article->introtext;
//								$article->text = $article->text . $finalHtml;
								$article->fulltext = $article->fulltext . $finalHtml;
								break;
						endswitch;
                    } else if ($plgConfig['jRequest_layout'] == 'latest') {
						switch ($plgConfig['display_location']):
							case 'top':
								$article->text = $finalHtml . $article->text;
								break;
							case 'bottom':
								$article->text = $article->text . $finalHtml;
								break;
							case 'both':
								$article->text = $finalHtml . $article->text;
//								if ($plgConfig['jRequest_view'] == 'latest') { $article->text = $article->text . $finalHtml; }
								$article->text = $article->text . $finalHtml;
								break;
						endswitch;
					} else if ($plgConfig['jRequest_layout'] == 'category') {
						switch ($plgConfig['display_location']):
							case 'top':
								$article->text = $finalHtml . $article->text;
								break;
							case 'bottom':
								$article->text = $article->text . $finalHtml;
								break;
							case 'both':
								$article->text = $finalHtml . $article->text . $finalHtml;
								break;
						endswitch;
					}
				}
				break;

			default:
				break;
		endswitch;
	}
    function varDumpReturn( $mixed = null ) {
		ob_start();
		print_r($mixed);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
    function getExtensionVersion($xmlFile) {
        if (JFile::exists($xmlFile)) {
            $xml_items = '';
            if ($data = JApplicationHelper::parseXMLInstallFile($xmlFile)) {
                foreach ($data as $key => $value) {
                    $xml_items[$key] = $value;
                }
            }
            if (isset($xml_items['version']) && $xml_items['version'] != '') {
                return $xml_items['version'];
            } else {
                return '';
            }

        }
    }
}
?>
