<?php
/*
	JoomlaXTC File Browser
	
	Version 2.6.1
	
	Copyright (C) 2010-2011  Monev Software LLC.	All Rights Reserved.
	
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

defined('_JEXEC') or die;

$max_chars = 12;
$short_name = $this->_tmp_file->name;
if( strlen( $short_name ) > $max_chars ) {
    $short_name = substr( $short_name, 0, $max_chars ) . '...';
}
if ($this->_tmp_file->isimage) { // image
	$title = $this->_tmp_file->name." (".$this->_tmp_file->realwidth."x".$this->_tmp_file->realheight.") ".number_format($this->_tmp_file->size)." bytes";
}
else {
	$title = $this->_tmp_file->name." (".number_format($this->_tmp_file->size)." bytes)";
}
?>
<div class="item">
    <a 
        href="javascript:setModalItem('<?php echo $this->_tmp_file->name; ?>')" 
        id="<?php echo $this->_tmp_file->path; ?>" 
        title="<?php echo $title; ?>">
		<?php if ($this->_tmp_file->isimage) { // image ?>
    <img
        src="<?php echo $this->baseURL.$this->_tmp_file->path_relative; ?>"  
        width="<?php echo $this->_tmp_file->width; ?>" 
        height="<?php echo $this->_tmp_file->height; ?>" 
        alt="<?php echo $this->_tmp_file->name; ?>"
        title="<?php echo $title; ?>" />
    <?php } else {?>
    <img
        src="<?php echo JURI::base() ?>components/<?php echo $option; ?>/support/images/default_32.png" width="32" height="32" alt="<?php echo $this->_tmp_file->name; ?>" 
        title="<?php echo $title; ?>" />
    <?php } ?>
    <span title="<?php echo $title; ?>"><?php echo $short_name; ?></span></a>
</div>
