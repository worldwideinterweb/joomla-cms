<?php


defined('_JEXEC') or die('Direct Access to this location is not allowed.');
error_reporting(E_ERROR);
jimport('joomla.application.component.controller');

class ArtAdminerController extends JController {
	function __construct() {
		parent::__construct();
	}
	
	function adminer() {
		require_once(JPATH_COMPONENT.DS.'admin.artadminer.html.php');
		$option = JRequest::getCmd('option');
		HTML_ArtAdminer::adminer($option);
	}
	
	function settings() {
		require_once(JPATH_COMPONENT.DS.'admin.artadminer.html.php');
		JTable::addIncludePath(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_artadminer' . DS . 'database');
		$settings =& JTable::getInstance('artadminer_setting', 'Table');
		$settings->load(1);
    if (!$settings || !$settings->id) {
      $db	=& JFactory::getDBO();
      $db->setQuery('CREATE TABLE IF NOT EXISTS `#__art_adminer_setting` (`id` int(11) unsigned NOT NULL auto_increment,`cssfile` varchar(255) NOT NULL,`autologin` tinyint(1),PRIMARY KEY  (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
      $db->query();
      
      $db->setQuery("INSERT INTO `#__art_adminer_setting` (`id`, `cssfile`, `autologin`)  VALUES (1, 'adminer2.css', 1) ON DUPLICATE KEY UPDATE id=id;");
      $db->query();
    }
		$option = JRequest::getCmd('option');		
		HTML_ArtAdminer::settings($option, $settings);
	}
	
	function settings_save() {
		JTable::addIncludePath(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_artadminer' . DS . 'database');
		$option = JRequest::getCmd('option');
		$post = JRequest::get('post');
		$row =& JTable::getInstance('artadminer_setting', 'Table');
		
		if (!$row->bind($post)) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		if (!$row->store()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		$this->setMessage('Settings Saved');
		$this->setRedirect('index.php?option=' . $option . '&task=settings');
	}
	
}

?>