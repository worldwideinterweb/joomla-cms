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

defined('_JEXEC') or die('Restricted access');

$folder = $this->state->folder;
if ( $folder == '/' ) $folder = '';
?>
<tr class="row<?php echo $this->k ?>">
	<td>
		<a href="index.php?option=<?php echo $this->state->option ?>&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $folder; ?>/<?php echo $this->_tmp_folder->path_relative; ?>">
			<img src="<?php echo JURI::base() ?>components/<?php echo $option; ?>/support/images/folder_sm.png" margin="0" width="16" height="16" alt="<?php echo $this->_tmp_folder->name; ?>" />
		</a>
	</td>
	<td>	
		<a href="index.php?option=<?php echo $this->state->option ?>&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $folder; ?>/<?php echo $this->_tmp_folder->path_relative; ?>">
			<span><b><?php echo $this->_tmp_folder->name; ?></b></span>
		</a>
	</td>
	<td>&nbsp;
	</td>
	<td>&nbsp;
	</td>
</tr>