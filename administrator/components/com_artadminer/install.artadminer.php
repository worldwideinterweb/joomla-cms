<?php

defined('_JEXEC') or die('Direct Access to this location is not allowed.');
define('JPATH_BASE', dirname(__FILE__) );

jimport('joomla.application.component.controller'); 

function com_install() 
{
	updateMenuIcons();
	return true;
}

function updateMenuIcons() {
	$db	=& JFactory::getDBO(); 
	$queries = array();
	$queries[] = 'UPDATE `#__components` SET admin_menu_img= "../administrator/components/com_artadminer/images/artetics_logo.png" WHERE admin_menu_link="option=com_artadminer"';
	$queries[] = 'UPDATE `#__components` SET admin_menu_img= "../includes/js/ThemeOffice/controlpanel.png" WHERE admin_menu_link="option=com_artadminer&task=settings"';
	
	$db->setQuery(join($queries, ';'));
	$db->queryBatch();
}

?>