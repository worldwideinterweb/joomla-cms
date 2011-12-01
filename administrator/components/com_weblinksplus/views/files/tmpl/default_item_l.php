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

?>
<tr class="row<?php echo $this->k ?>">
	<td>
    <a href="javascript:setModalItem('<?php echo $this->_tmp_file->name; ?>')" 
       id="<?php echo $this->_tmp_file->path; ?>" 
       title="<?php echo $this->_tmp_file->name; ?>">
	    <img
	        src="<?php echo $this->baseURL.$this->_tmp_file->path_relative16; ?>"  
	        margin="0"
	        width="16" 
	        height="16" 
	        alt="<?php echo $this->_tmp_file->name; ?>"
	        title="<?php $this->_tmp_file->name; ?>" />
    </a>
	</td>
	<td>
    <a href="javascript:setModalItem('<?php echo $this->_tmp_file->name; ?>')" 
       id="<?php echo $this->_tmp_file->path; ?>" 
       title="<?php echo $this->_tmp_file->name; ?>">
    	<span title="<?php echo $this->_tmp_file->name; ?>">
   	 	<?php echo $this->_tmp_file->name; ?>
    	</span>
    </a>
  </td>
  <td align="right" width="1%" style="white-space:nowrap">
   		<?php echo $this->_tmp_file->date ?>
	</td>
  <td align="right" width="1%" style="white-space:nowrap">
   	<span title="<?php echo $this->_tmp_file->size; ?>">
   		<?php echo number_format($this->_tmp_file->size) ?> Bytes
   	</span>
	</td>
</tr>
