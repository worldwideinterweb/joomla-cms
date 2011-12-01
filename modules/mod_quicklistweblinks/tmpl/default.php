<?php
/**
* Joes Quicklist Weblinks
* @package	mod_quicklistweblinks
* @author	joel lipman
* @link		http://www.joellipman.com
* @license	GNU/GPL v3
* @modified 03/07/2011
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$document =& JFactory::getDocument();
$document->addCustomTag($jqw_params->inlinestylecode);
?>
<div id="joesquicklistweblinks"<?php echo $jqw_params->moduleclasssfx; ?>>
	<?php
		for ($i=0; $i<$jqw_params->linkcount; $i++) {

			# get values
			$this_id	=$jqw_params->weblinkentries->$i->linkid;
			$this_title	=$jqw_params->weblinkentries->$i->linktitle;
			$this_hits	=$jqw_params->weblinkentries->$i->linkhits;
			$this_desc	=$jqw_params->weblinkentries->$i->linkdesc;
			$this_img_path	=$jqw_params->weblinkentries->$i->linkimgs;
			$this_date	=$jqw_params->weblinkentries->$i->linkdate;

			# different widths for inline or popup images
			$this_img_str_inline= '<img src="'.JURI::base().$this_img_path.'" style="float:left;width:'.$jqw_params->previewwidth.'px;height:'.$jqw_params->previewheight.'px;margin-right:5px;border:'.$jqw_params->imgborderwidth.'px solid '.$jqw_params->imgbordercolor.';" alt="'.$this_title.'" />'."\n";
			$this_img_str_popup= '<img src="'.JURI::base().$this_img_path.'" style="float:left;width:'.$jqw_params->popupwidth.'px;height:'.$jqw_params->popupheight.'px;margin-right:5px;border:'.$jqw_params->imgborderwidth.'px solid '.$jqw_params->imgbordercolor.';" alt="'.$this_title.'" />'."\n";

			# add text to values
			$this_link_ellipsis=(strlen($this_desc)>$jqw_params->desclength)?'&#133;':'';
			$this_desc_inline = '<span style="font-size:'.$jqw_params->descfontsize.'%;display:block;">'.substr($this_desc, 0, $jqw_params->desclength).$this_link_ellipsis.'</span>';
			$this_link_ellipsis=(strlen($this_title)>$jqw_params->titlelength)?'&#133;':'';
			$this_title_inline =substr($this_title, 0, $jqw_params->titlelength).$this_link_ellipsis;
			$this_desc_popup	= ($i < $jqw_params->desccount) ? '<span class="jqw_popup_description">'.$this_desc.'</span><br />' : '';
			$this_title_popup = ($jqw_params->allowpopups) ? '<b><span class="jqw_popup_title">'.$this_title.'</span></b><br />' : '';
			$this_date_popup='<span class="jqw_popup_date">'.trim($jqw_params->prefixdatetxt.' '.$this_date).'</span>';
			$this_hits_popup = (trim($this_hits)!="") ? '<span class="jqw_popup_hits">'.trim($jqw_params->prefixhittxt.' '.$this_hits)."</span><br />" : '';

			# draw the inline image cell - uses div for rounded corners with borders
			echo '<div style="float:left;width:'.($jqw_params->previewwidth+4).'px;">';
			if (($i<$jqw_params->previewcount)&&(($jqw_params->linkdispimgs=="inline")||($jqw_params->linkdispimgs=="both"))) {
				echo '<a class="jqw_thumbnail" href="'.JURI::base().'index.php?task=weblink.go&id='.$this_id.'&option=com_weblinks"'.$jqw_params->opener_str." style=\"text-decoration:none;\">\n";
				if ($jqw_params->imgroundedcorners) {
					echo '<div class="jqw_thumb_preview" style="background: url(\''.JURI::base().$this_img_path.'\');" />&nbsp;</div>';
					$this_img_str_popup= '<div class="jqw_thumb_popup" style="background: url(\''.JURI::base().$this_img_path.'\');"/>&nbsp;</div>';
				} else {
					echo $this_img_str_inline;
				}
				if ($jqw_params->popuponimg) {
					echo '<span class="jqw_popup">';
					echo (($jqw_params->linkdispimgs=="popup")||($jqw_params->linkdispimgs=="both"))?$this_img_str_popup:'';
					echo $this_title_popup;
					echo (($jqw_params->linkdispdesc=="popup")||($jqw_params->linkdispdesc=="both"))?$this_desc_popup:'';
					echo (($jqw_params->linkdispdate=="popup")||($jqw_params->linkdispdate=="both"))?$this_date_popup.'<br />':'';
					echo (($jqw_params->linkdisphits=="popup")||($jqw_params->linkdisphits=="both"))?$this_hits_popup.'<br />':'';
					echo '</span>';
				}
				echo '</a>';
			}
			echo '</div>';

			# write main column with users options and mouseover
			echo '<div style="float:left;">';
			echo '<a class="jqw_thumbnail" href="'.JURI::base().'index.php?task=weblink.go&id='.$this_id.'&option=com_weblinks"'.$jqw_params->opener_str.$jqw_params->forcenounderline.'>';
			echo $this_title_inline.'<br />';
			echo (($jqw_params->linkdispdesc=="inline")||($jqw_params->linkdispdesc=="both"))?$this_desc_inline:'';
			if ($jqw_params->popupontxt) {
				echo '<span class="jqw_popup">';
				echo (($jqw_params->linkdispimgs=="popup")||($jqw_params->linkdispimgs=="both"))?$this_img_str_popup:'';
				echo $this_title_popup;
				echo (($jqw_params->linkdispdesc=="popup")||($jqw_params->linkdispdesc=="both"))?$this_desc_popup:'';
				echo (($jqw_params->linkdispdate=="popup")||($jqw_params->linkdispdate=="both"))?$this_date_popup.'<br />':'';
				echo (($jqw_params->linkdisphits=="popup")||($jqw_params->linkdisphits=="both"))?$this_hits_popup.'<br />':'';
				echo '</span>';
			}
			echo '</a>';
			echo '</div>';

			# write remaining columns (date and hits)
			echo (($jqw_params->linkdispdate=="inline")||($jqw_params->linkdispdate=="both"))?'<div class="jqw_date_inline" style="float:left;width:10%">'.$this_date.'</div>':'';
			echo (($jqw_params->linkdisphits=="inline")||($jqw_params->linkdisphits=="both"))?'<div class="jqw_hits_inline" style="float:left;width:5%">'.$this_hits.'</div>':'';
			echo '<div style="clear:both"></div>';
		}

		echo $jqw_params->morelinks;
		if ($jqw_params->poweredby) echo ' <a href="http://www.joellipman.com/" target="_blank" style="font-size:8px;text-decoration:none">JoelLipman.Com</a>';
		if (trim($jqw_params->debug)!="") echo $jqw_params->debug;
		echo '<div style="clear:both"></div>';

	?>
</div>
