<?php
/*
	JoomlaXTC File Browser
	
	Version 2.4.0
	
	Copyright (C) 2010  Monev Software LLC.	All Rights Reserved.
	
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
	
	THIS LICENSE MIGHT NOT APPLY TO OTHER FILES CONTAINED IN THE SAME PACKAGE.
	
	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

defined('_JEXEC') or die;

$default_folder = $this->state->default_folder;
$folder = $this->state->folder;
$fieldname = $this->state->fieldname;
$fileview = $this->state->fileview;
$extview = $this->state->extview;
$ext = $this->state->ext;
$option = JRequest::getCmd( 'option');

// Errors?
$message = empty($ext) ? '' : JText::_('Valid file extensions are:').' '.$ext;
if (!is_readable( JPATH_SITE.DS.$folder )) {
    $message = JText::_( 'Folder not readable' );
} elseif ( empty( $this->files ) && empty( $this->folders )) {
    $message = JText::_( 'No files or folders found' );
} elseif ( empty( $this->files )) {
    $message = JText::_( 'No files found' );
}

// Breadcrumbs
$paths=explode('/',$folder);
$lastpath=array_pop($paths);
$crumbs=array();
$crumbpaths=array();
foreach ($paths as $path) {
	$crumbpaths[] = $path;
	$crumbs[] = '<a href="index.php?option='.$option.'&amp;view=files&amp;tmpl=component&amp;folder='.implode('/',$crumbpaths).'">'.$path.'</a>';
}
$crumbs[]=$lastpath;
$breadcrumb = implode('/',$crumbs);
	
// js & styling
$css = 'html,body {margin:0 !important; padding:0 !important}
input {float:none !important}
#folder-indicator {
	font-size:1.5em;
	font-weight:bold;
	color:#0b55c4;
}
	
#browser {height:390px;width:100%;overflow:auto;position:relative}
.item { float: left; border: 1px solid #ccc; margin: 3px;	 position: relative; }

.item a       {
	display: table-cell !important;
	display: block;
	width: 80px; height: 90px;
	overflow: hidden;
	vertical-align: middle;
	text-align: center;
	text-decoration: none;
	color: black;

	line-height: 90px;
}

.item img { display: inline; margin-top: expression(( 80 - this.height ) / 2);}

.item img {margin: auto;}

.item span {
	line-height: 100%;
	clear:both;
	display: block;
	width: 100%;
	position: absolute;
	bottom: 0;
	left: 0;
	padding: 2px 0;
	background-color: #eee;
	overflow: hidden;
}
';
$script="function setModalItem( file ) {
		current_item = '$folder' + '/' + file; // Needed
		document.getElementById('folder-indicator').innerHTML = current_item;
		document.getElementById('apply').disabled = false;
	}
	
	function submitModalForm() {
		window.parent.document.getElementById('$fieldname').value = current_item;
		if (window.parent.document.$fieldname"."_image) {
			window.parent.document.$fieldname"."_image.src='../' + current_item;
		}
		window.parent.document.getElementById('sbox-window').close();
	}";
		$doc =&JFactory::getDocument();
		$doc->addScriptDeclaration($script);
		$doc->addStyleDeclaration($css);
?>
<fieldset>
	<form action="index.php" id="fileForm" method="post" enctype="multipart/form-data">
		<div style="float:left">
			<span id="folder-indicator">
				<?php echo $breadcrumb; ?>
			</span>
	  </div>
		<div style="float:right">
				<button type="button" id="apply" disabled="disabled" onclick="javascript:submitModalForm();"><?php echo JText::_('Select') ?></button>
		</div>
	</form>
</fieldset>
<fieldset>
	<div style="float:left">
		<?php echo $message; ?>
	</div>
	<div style="float:right">
		<b><?php echo JText::_('View mode:') ?></b>&nbsp;
		<a href="index.php?option=<?php echo $option; ?>&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $folder; ?>&amp;fileview=g">
			<?php echo ($fileview=='g' ? '<u>'.JText::_('Icons').'</u>' : JText::_('Icons')); ?>
		</a>|
		<a href="index.php?option=<?php echo $option; ?>&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $folder; ?>&amp;fileview=l">
			<?php echo ($fileview=='l' ? '<u>'.JText::_('List').'</u>' : JText::_('List')); ?>
		</a>
	</div>
	<?php if ($ext) { ?>
	<div style="float:right">
		<b><?php echo JText::_('Extension filter:') ?></b>&nbsp;
		<?php if ($extview) { ?>
			<a href="index.php?option=<?php echo $option; ?>&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $folder; ?>&amp;extview=0">
				<?php echo JText::_('On'); ?>
			</a>
		<?php } else { ?>
			<a href="index.php?option=<?php echo $option; ?>&amp;view=files&amp;tmpl=component&amp;folder=<?php echo $folder; ?>&amp;extview=l">
				<?php echo JText::_('Off'); ?>
			</a>
		<?php } ?>
		&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<?php } ?>
</fieldset>
<div style="clear:both"></div>
<div id="browser">
<?php 
	switch($fileview) {
		case 'l':
			$this->k=0;
			require 'default_header_l.php';
			//echo $this->loadTemplate('header_l');
		break;
	}

	switch($fileview) {
		case 'g':
			require 'default_parent_g.php';
			//echo $this->loadTemplate('parent_g');
		break;
		case 'l':
			require 'default_parent_l.php';
			//echo $this->loadTemplate('parent_l');
		  $this->k = 1 - $this->k;
		break;
	}

  if( count( $this->folders ) > 0 ) {
    for ($i=0,$n=count($this->folders); $i<$n; $i++) {
        $this->setFolder($i);
    	switch($fileview) {
    		case 'g':
					require 'default_folder_g.php';
    			//echo $this->loadTemplate('folder_g');
    		break;
    		case 'l':
					require 'default_folder_l.php';
    			//echo $this->loadTemplate('folder_l');
		      $this->k = 1 - $this->k;
    		break;
    	}
    } 
  }

  if( count( $this->files ) > 0 ) {
    for ($i=0,$n=count($this->files); $i<$n; $i++) {
        $this->setFile($i);
    	switch($fileview) {
    		case 'g':
					require 'default_item_g.php';
    			//echo $this->loadTemplate('item_g');
    		break;
    		case 'l':
					require 'default_item_l.php';
    			//echo $this->loadTemplate('item_l');
		      $this->k = 1 - $this->k;
    		break;
    	}
    }
  }

	switch($fileview) {
		case 'l':
		require 'default_footer_l.php';
		//echo $this->loadTemplate('footer_l');
		break;
	}
?>
</div>
<br style="clear:both"/>
<fieldset>
	<form action="index.php" id="form1"    method="post" enctype="multipart/form-data">
		<?php echo JText::_('File upload:') ?>
		<input type=file name="upfile" size="100">&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value="<?php echo JText::_('Submit') ?>">
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="option" value="<?php echo $option ?>" />
		<input type="hidden" name="view" value="files" />
		<input type="hidden" name="folder" value="<?php echo $this->state->folder; ?>" />
	</form>
</fieldset>
