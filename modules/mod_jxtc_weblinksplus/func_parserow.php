<?php
/*
	JoomlaXTC weblinks plus Wall

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

$original = $item->thumbnail ? '<img src="'.$item->thumbnail.'" alt="'.$item->title.'" '.$fullx.' '.$fully.'/>' : '';
$thumbnail = $item->thumbnail ? '<img src="media/weblinksplus/thumbs/'.$item->id.'.png" alt="'.$item->title.'" '.$thumbnailx.' '.$thumbnaily.'/>' : '';

$rowdescription = strip_tags($item->description);
if (!empty($desclen)) {
        $rowdescription=substr($rowdescription,0,$desclen);
        $pos = strrpos($rowdescription,' ');
        if ($pos !== false) {
                $rowdescription=substr($rowdescription,0,$pos).'...';
        }
}

$link = WeblinksplusHelperRoute::getWeblinkplusRoute($item->id, $item->catid);

$itemhtml = str_replace( '{title}', htmlspecialchars($item->title), $itemhtml );
$itemhtml = str_replace( '{url}', htmlspecialchars($item->url), $itemhtml );
$itemhtml = str_replace( '{description}', htmlspecialchars($item->description), $itemhtml );
$itemhtml = str_replace( '{rowdescription}', $rowdescription, $itemhtml );
$itemhtml = str_replace( '{hits}', $item->hits, $itemhtml );
$itemhtml = str_replace( '{originalurl}', $item->thumbnail, $itemhtml );
$itemhtml = str_replace( '{original}', $thumbnail, $itemhtml );
$itemhtml = str_replace( '{thumbnailurl}', 'media/weblinksplus/thumbs/'.$item->id.'.png', $itemhtml );
$itemhtml = str_replace( '{thumbnail}', $thumbnail, $itemhtml );
$itemhtml = str_replace( '{date}', date($dateformat,strtotime($item->date)), $itemhtml );
$itemhtml = str_replace( '{link}', $link, $itemhtml );

while (($ini=strpos($itemhtml,"{date")) !== false) {
	$fin = strpos($itemhtml,"}",$ini);
	$filter=substr($itemhtml,$ini,$fin-$ini+1);
	$hold=explode(' ',substr($filter,1,-1));
	$fmt = isset($hold[1]) ? $hold[1] : $dateformat;
	$val=date(trim($fmt),strtotime($item->date));
	$itemhtml = str_replace($filter,$val,$itemhtml);
}
?>
