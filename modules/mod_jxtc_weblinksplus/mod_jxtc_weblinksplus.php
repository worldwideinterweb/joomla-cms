<?php
/*
	JoomlaXTC Weblinks Plus Pro

	version 1.0.0

	Copyright (C) 2008,2009,2010,2011 Monev Software LLC.	All Rights Reserved.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.

	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;

$live_site = JURI::base();
$db = &JFactory::getDBO();
$doc =&JFactory::getDocument();
$moduleDir = 'mod_jxtc_weblinksplus';

$config =& JComponentHelper::getParams( 'com_weblinksplus' );
$columns = $params->get('columns',3);
$rows	= $params->get('rows', 3);
$pages = $params->get('pages', 1);

$thumbnailx = $params->get( 'thumbnailx', 20 ); $thumbnailx = $thumbnailx > 0 ? 'width="'.$thumbnailx.'"' : '';
$thumbnaily = $params->get( 'thumbnaily', 20 ); $thumbnaily = $thumbnaily > 0 ? 'height="'.$thumbnaily.'"' : '';

$fullx = $params->get( 'fullx', 20 ); $fullx = $fullx > 0 ? 'width="'.$fullx.'"' : '';
$fully = $params->get( 'fully', 20 ); $fully = $fully > 0 ? 'height="'.$fully.'"' : '';

$category = $params->get('category',0);
$sortfield = $params->get('sortfield', 0);
$sortorder = $params->get('sortorder', 1);

require_once (JPATH_SITE.DS.'components'.DS.'com_weblinksplus'.DS.'helpers'.DS.'route.php');

$template	= $params->get('template','');
$moduletemplate	= trim( $params->get('moduletemplate','{mainarea}'));
$itemtemplate	= trim( $params->get('itemtemplate','{title}'));
if ($template && $template != -1) {
	$moduletemplate=file_get_contents(JPATH_ROOT.DS.'modules'.DS.$moduleDir.DS.'templates'.DS.$template.DS.'module.html');
	$itemtemplate=file_get_contents(JPATH_ROOT.DS.'modules'.DS.$moduleDir.DS.'templates'.DS.$template.DS.'element.html');
	if (file_exists(JPATH_ROOT.DS.'modules'.DS.$moduleDir.DS.'templates'.DS.$template.DS.'template.css')) {
		$doc->addStyleSheet($live_site.'/modules/'.$moduleDir.'/templates/'.$template.'/template.css','text/css');
	}
}

$dateformat	= trim( $params->get('dateformat','Y-m-d' ));
$desclen = (int) $params->get('maxlength', 0);

$query = "SELECT wlp.* FROM #__weblinksplus as wlp , #__categories as c
	WHERE c.extension='com_weblinksplus'
	  AND wlp.catid = c.id
	  AND wlp.state=1
	  AND wlp.approved=1
	  AND c.published=1";
	
if ($category) {
	if (!is_array($category)) $category=array($category);
	if ($category[0] != 0) {
		$query .= ' AND (wlp.catid in ('.implode(',',$category).'))';
	}
}

switch ($sortfield) {
	case 0: 
		$query .= ' ORDER BY RAND()';
	break;
	case 1: // date
		$query .= ' ORDER BY wlp.date '.$sortorder;
	break;
	case 2: // hits
		$query .= ' ORDER BY wlp.hits '.$sortorder;
	break;
	case 3: // Title
		$query .= ' ORDER BY wlp.title '.$sortorder;
	break;
	case 4: // ordering
		$query .= ' ORDER BY wlp.ordering '.$sortorder;
	break;
}

$limit = $columns*$rows*$pages;
$db->setQuery($query, 0, $limit);
$items = $db->loadObjectList();
if (count($items) == 0) return;	// Return empty

require 'incl_render.php';

JPluginHelper::importPlugin('content');
$contentconfig =& JComponentHelper::getParams('com_content');
$dispatcher =& JDispatcher::getInstance();
$item = new stdClass();
$item->text = $modulehtml;
$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$contentconfig, 0 ));
$modulehtml = $item->text;
echo '<div id="'.$jxtc.'">'.$modulehtml.'</div>';
?>
<div style="display:none"><a href="http://www.joomlaxtc.com">JoomlaXTC Social Group Wall - Copyright 2009,2010 Monev Software LLC</a></div>