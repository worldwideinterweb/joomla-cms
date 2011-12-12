<?php

/**
 * @version $Id: plgContentMultiAds.php 1.5
 * @copyright Joe Guo
 * @license GNU/GPLv2,
 * @author Joe Guo - http://www.eboga.org
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
jimport('joomla.environment.browser');

class plgContentMultiads extends JPlugin {

     function onContentPrepare($context, &$article, &$params, $limitstart) {
   
        if ((JRequest :: getVar('view')) != 'article') {
            return true;
        }

        if ($this->ignore($article->id, $article->catid)) {
            return true;
        }
        if (!$this->showAdsForAuthor($article)) {
            return true;
        }
        if (!$this->showAdsForTemplates()) {           
            return true;
        }


        $topAds = $this->param('Content_top_ads');
        $align = $this->param('align');

        $divStart = "<div style='";
        if ($align == 1) {
            $divStart.="float:left;text-align:left;";
        } else if ($align == 0) {
            $divStart.="float:right;text-align:right;";
        } else if ($align == 2) {
            $divStart.="float:center;text-align:center;";
        } else {
            //no align
        }
        
        $margin = $this->param('margin', '1');

        $divStart.=" margin:" . $margin . "px auto;'>";
        $divEnd = "</div>";
        $adsenseSection = $this->param('adsense_section');
        if ($adsenseSection) {
            $article->text = "\n<!-- google_ad_section_start -->\n" . $article->text . "\n<!-- google_ad_section_end -->\n";
        }
        if ($topAds) {
            $article->text = $divStart . $topAds . $divEnd . $article->text;
        }
        $bottomAds = $this->param('Content_bottom_ads');
        if ($bottomAds) {
            $article->text.='<br/>' . $bottomAds;
        }


        return true;
    }

    function showAdsForAuthor(&$article) {
        $createdBy = $article->created_by;
        $authors = $this->param('Only_Authors');
        if (isset($authors) && $authors) {
            $authorsArray = explode(',', $authors);
            if (!empty($authorsArray) && !in_array($createdBy, $authorsArray, false)) {
                return false;
            }
        }
        return true;
    }

    function showAdsForTemplates() {
        $app = & JFactory::getApplication();
        $template = $app->getTemplate();
        
        $template = strtolower($template);
        $templates = $this->param('Only_templates');
        if (!isset($templates) || !trim($templates)) {
            return true;
        }
        $templates = strtolower($templates);
        $templatesArray = explode(',', $templates);

        if (in_array($template, $templatesArray)) {
            return true;
        }

        return false;
    }

    function onContentBeforeDisplay($context, &$article, &$params, $limitstart) {

        if ($this->ignore($article->id, $article->catid)) {
            return '';
        }
        if (!$this->showAdsForTemplates()) {
            return '';
        }
        $ads = $this->showContent('Before_content_ads', true);
        if ($ads) {
            if ($this->showAdsForAuthor($article)) {
                return $ads;
            }
        }
        return '';
    }
	public function onContentAfterTitle($context, &$article, &$params, $limitstart)
	{
		 if ($this->ignore($article->id, $article->catid)) {
            return '';
        }
         if (!$this->showAdsForTemplates()) {
            return '';
        }
        $ads = $this->showContent('After_title_ads', true);
        if ($ads) {
            if ($this->showAdsForAuthor($article)) {
                return $ads;
            }           
        }
        return '';
	}

    function onContentAfterDisplay($context, &$article, &$params, $limitstart) {

        if ($this->ignore($article->id, $article->catid)) {
            return '';
        }
         if (!$this->showAdsForTemplates()) {
            return '';
        }
        $ads = $this->showContent('After_content_ads', true);
        if ($ads) {
            if ($this->showAdsForAuthor($article)) {
                return $ads;
            }
           
        }
        return '';
    }

    function showContent($paramName, $isCount) {
        if (!$this->isFrontPage() && !$this->isBlogView()) {
            return $this->param($paramName);
        } else {
            $frontPageCount = JRequest :: getVar('frontPageAdsCount');
            if (!isset($frontPageCount)) {
                $frontPageCount = 0;
            }
            if ($isCount) {
                $frontPageCount++;
                JRequest :: setVar('frontPageAdsCount', $frontPageCount);
            }
            $adCount = (int) $this->param('Front_page_ads_count');

            if ($frontPageCount <= $adCount) {
                if ($this->isFrontPage() && $frontPageCount <= 1) {
                    return $this->param($paramName);
                } else {
                    return $this->param($paramName);
                }
            }
        }
    }

    function isFrontPage() {
        if ((JRequest :: getVar('view')) == 'featured') {
            return true;
        } else {
            return false;
        }
    }

    function isBlogView() {

        if ((JRequest :: getVar('layout')) == 'blog') {
            return true;
        } else {
            return false;
        }
    }

    function param($name, $default='') {
    	return $this->params->get($name,$default);
        
    }

    function exclude($paramName, $value) {
        $excludeArticlesIds = $this->param($paramName);
        $excludeArticlesIdsArray = explode(',', $excludeArticlesIds);
        if (empty($excludeArticlesIdsArray)) {
            return 0;
        }
        if (!$value) {
            return 0;
        }
        if (in_array($value, $excludeArticlesIdsArray, false)) {
            return 1;
        }
        return 0;
    }

    function ignore($id, $catId) {
        $onlyGuest = $this->param('ONLY_GUEST');
        if ($onlyGuest) {
            $user = & JFactory::getUser();
            $aid = $user->get('aid', 0);
            if ($aid > 0) {
                return true;
            }
        }
        $ip = $this->getClientIp();
        $ignore = $this->exclude('Block_IPs', $ip);
        if ($ignore) {
            return $ignore;
        }
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $browser = &JBrowser::getInstance($userAgent);
        $agent = $browser->getBrowser();
        if (!$agent) {
            $agent = $browser->getAgentString();
        }
       
        $ignore = $this->exclude('Block_User_Agent', $agent);
        if ($ignore) {
            return $ignore;
        }
        $ignore = $this->exclude('Exclude_Article_Ids', $id);
        if ($ignore) {
            return $ignore;
        }
        $ignore = $this->exclude('Exclude_Category_Ids', $catId);
        if ($ignore) {
            return $ignore;
        }
        
        return $ignore;
    }

    function getClientIp() {
        global $_SERVER;
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                  
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    
        return $realip;
    }

}

?>
