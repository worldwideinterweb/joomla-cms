<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.'); ?>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
  <tr>
    <th colspan="2" align="center" bgcolor="#E7E7E7">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_TEAM'); ?>
    </th>
  </tr>
  <tr>
    <td width="50%" align="right">
      <a href="mailto:mab@joomgallery.net">M. Andreas B&ouml;ttcher (aka mab)</a>
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_CHIEF').', '.JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      <a href="mailto:aha@joomgallery.net">Andreas Hartmann (aka aHa)</a>
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
<!--
  <tr>
    <td width="50%" align="right">
      <a href="mailto:hypnotoad@joomgallery.net">Armin Hornung (aka hypnotoad)</a>
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
-->
<!--
  <tr>
    <td width="50%" align="right">
      <a href="mailto:djanesch@joomgallery.net">Daniel Janesch (aka deejay_)</a>
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
-->
  <tr>
    <td width="50%" align="right">
      <a href="mailto:chraneco@joomgallery.net">Patrick Alt (aka chraneco)</a>
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      <a href="mailto:erftralle@joomgallery.net">Ralf (aka erftralle)</a>
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
<!--
  <tr>
    <td width="50%" align="right">
      Benjamin Malte Meier (aka b2m)
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_ADVISORY').', '.JText::_('COM_JOOMGALLERY_HLPIFO_PROGRAMMING'); ?>
    </td>
  </tr>
-->
<!--
  <tr>
    <td width="50%" align="right">
      Dennis Rowedder (aka Wuslon)
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_QUALITY'); ?>
    </td>
  </tr>
-->
  <tr>
    <td width="50%" align="right">
      Claudia Engel (aka Claudia E.)
    </td>
    <td width="50%" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_SUPPORT').', '.JText::_('COM_JOOMGALLERY_HLPIFO_QUALITY'); ?>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      JoomGallery::ProjectTeam&nbsp;
      <img src="<?php echo $this->_ambit->getIcon('flags/gb.png'); ?>" border="0" align="top" width="16" height="11" alt="" />
    </td>
    <td rowspan="<?php echo count($this->languages) + 1; ?>" align="left">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_TRANSLATION'); ?> &sup1;
    </td>
  </tr>
<?php foreach($this->languages as $key => $lang): ?>
  <tr>
    <td width="50%" align="right">
      <?php echo $lang['translator']; ?>&nbsp;
<?php   if($this->params->get('autoinstall_possible')): ?>
      <a href="index.php?option=<?php echo _JOOM_OPTION; ?>&amp;controller=help&amp;task=install&amp;language=<?php echo $key; ?>&amp;downloadlink=<?php echo base64_encode($lang['downloadlink']); ?>"  title="<?php echo $key; ?>">
<?php   endif;
        if(!$this->params->get('autoinstall_possible')): ?>
      <a href="<?php echo $lang['downloadlink']; ?>" title="<?php echo $key; ?>" target="_blank">
<?php   endif; ?>
        <img src="<?php echo $this->_ambit->getIcon('flags/'.$lang['flag']); ?>" border="0" align="top" width="16" height="11" alt="<?php echo $key; ?>" /></a>
    </td>
  </tr>
<?php endforeach; ?>
  <tr>
    <td colspan="2" align="center">
      &sup1; <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_DOWNLOAD_TRANSLATIONS'); ?>
    </td>
  </tr>
  <tr>
    <th colspan="2" align="center" bgcolor="#E7E7E7">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_LINKS'); ?>
    </th>
  </tr>
  <tr>
    <td width="50%" align="right">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROJECT_WEBSITE'); ?>
      <img src="<?php echo $this->_ambit->getIcon('flags/de.png'); ?>" border="0" align="top" width="16" height="11" alt="german" />&nbsp;
    </td>
    <td width="50%" align="left">
      <a href='http://www.joomgallery.net/' target='_blank'>http://www.joomgallery.net</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROJECT_WEBSITE'); ?>
      <img src="<?php echo $this->_ambit->getIcon('flags/gb.png'); ?>" border="0" align="top" width="16" height="11" alt="english" />&nbsp;
    </td>
    <td width="50%" align="left">
      <a href='http://www.en.joomgallery.net/' target='_blank'>http://www.en.joomgallery.net/</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_SUPPORT_FORUM'); ?>
      <img src="<?php echo $this->_ambit->getIcon('flags/de.png'); ?>" border="0" align="top" width="16" height="11" alt="german" />&nbsp;
    </td>
    <td width="50%" align="left">
      <a href='http://www.forum.joomgallery.net/' target='_blank'>http://www.forum.joomgallery.net/</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_SUPPORT_FORUM'); ?>
      <img src="<?php echo $this->_ambit->getIcon('flags/gb.png'); ?>" border="0" align="top" width="16" height="11" alt="english" />&nbsp;
    </td>
    <td width="50%" align="left">
      <a href='http://www.forum.en.joomgallery.net/' target='_blank'>http://www.forum.en.joomgallery.net/</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_PROJECTSITE'); ?>
    </td>
    <td width="50%" align="left">
      <a href='http://joomlacode.org/gf/project/joomgallery/' target='_blank'>http://joomlacode.org/gf/project/joomgallery/</a>
    </td>
  </tr>
  <tr>
    <th colspan="2" align="center" bgcolor="#E7E7E7">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_THANKS'); ?>
    </th>
  </tr>
  <tr>
    <td width="50%" align="right">
      Joomla!
    </td>
    <td width="50%" align="left">
      <a href='http://www.joomla.org' target='_blank'>http://www.joomla.org</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      CMotion Image Gallery (modified) - Detail view<br />
      Author: Jscheuer1
    </td>
    <td width="50%" align="left">
      <a href='http://www.dynamicdrive.com/dynamicindex4/cmotiongallery.htm' target='_blank'>http://www.dynamicdrive.com/dynamicindex4/cmotiongallery.htm</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Slimbox (modified) - Detail and Category view<br />
      Author: Christophe Beyls
    </td>
    <td width="50%" align="left">
      <a href='http://www.digitalia.be/software/slimbox' target='_blank'>http://www.digitalia.be/software/slimbox</a>
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Thickbox3.1 (modified) - Detail and Category view<br />
      Author: Cody Lindley
    </td>
    <td width="50%" align="left">
      <a href='http://jquery.com/demo/thickbox/' target='_blank'>http://jquery.com/demo/thickbox/</a><br />
      <a href='http://www.codylindley.com' target='_blank'>http://www.codylindley.com</a>
    </td>
  </tr>
<!--<tr>
    <td width="50%" align="right">
      Lightbox2 (modified) - Detail and Category view<br />
      Author: Lokesh Dhakar
    </td>
    <td width="50%" align="left">
      <a href='http://www.lokeshdhakar.com/projects/lightbox2/' target='_blank'>http://www.lokeshdhakar.com/projects/lightbox2/</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      wz_dragdrop.js - Nameshields in Detail view<br />
      Author: Walter Zorn
    </td>
    <td width="50%" align="left">
      <a href='http://www.walterzorn.com/dragdrop/dragdrop_e.htm' target='_blank'>http://www.walterzorn.com/dragdrop/dragdrop_e.htm</a><br />
    </td>
  </tr>-->
  <tr>
    <td width="50%" align="right">
      pngbehavior.htc (PNG in IE 6)<br />
      Author: Erik Arvidsson
    </td>
    <td width="50%" align="left">
      <a href='http://webfx.eae.net' target='_blank'>http://webfx.eae.net</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      ImageMagick<br />
      Author: ImageMagick Studio LLC
    </td>
    <td width="50%" align="left">
      <a href='http://www.imagemagick.org/script/index.php' target='_blank'>http://www.imagemagick.org/script/index.php</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Jupload - Java Applet for uploading<br />
      Author:  Etienne Gauthier
    </td>
    <td width="50%" align="left">
      <a href='http://jupload.sourceforge.net/' target='_blank'>http://jupload.sourceforge.net/</a><br />
    </td>
  </tr>
  <!--<tr>
    <td width="50%" align="right">
      EasyCaptcha (Connector to EasyCaptcha in Detailview)<br />
      Author: Easy-Joomla.org Project
    </td>
    <td width="50%" align="left">
      <a href='http://www.easy-joomla.org/' target='_blank'>http://www.easy-joomla.org/</a><br />
    </td>
  </tr>-->
  <!--<tr>
    <td width="50%" align="right">
      recursive creation of directories
    </td>
    <td width="50%" align="left">
      <a href='http://www.developers-guide.net/forums/3134,php-rekursives-erstellen-von-verzeichnissen' target='_blank'>http://www.developers-guide.net/forums/3134,php-rekursives-erstellen-von-verzeichnissen</a><br />
    </td>
  </tr>-->
  <!--<tr>
    <td width="50%" align="right">
      multiple joomla select lists<br />
      Author: ecomeback
    </td>
    <td width="50%" align="left">
      <a href='http://www.joomlaportal.de/505887-post4.html' target='_blank'>http://www.joomlaportal.de/505887-post4.html</a><br />
    </td>
  </tr>-->
  <tr>
    <td width="50%" align="right">
      Watermark (modified)<br />
      Author: Michael Mueller
    </td>
    <td width="50%" align="left">
      <a href='http://www.php4u.net' target='_blank'>http://www.php4u.net</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      fastimagecopyresampled (fast conversion of pictures in GD)<br>
      Author: Tim Eckel
    </td>
    <td width="50%" align="left">
      <a href='http://de.php.net/manual/en/function.imagecopyresampled.php#77679' target='_blank'>http://de.php.net/manual/en/function.imagecopyresampled.php#77679</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      TabPane (Backend)<br>
      Author: Erik Arvidsson
    </td>
    <td width="50%" align="left">
      <a href='http://webfx.eae.net/dhtml/tabpane/tabpane.html' target='_blank'>http://webfx.eae.net/dhtml/tabpane/tabpane.html</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      NestedTabs (Backend)<br>
      Author: PerfectDesigningLTD
    </td>
    <td width="50%" align="left">
      <a href='http://www.perfectdesigning.net/development_projects/support/joomla!_tab_system.html'>http://www.perfectdesigning.net/development_projects/support/joomla!_tab_system.html</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Wonderful Icons<br />
      Author:  Mark James
    </td>
    <td width="50%" align="left">
      <a href='http://www.famfamfam.com' target='_blank'>http://www.famfamfam.com</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Smoothgallery (modified) slideshow in detail view<br />
      Author: Jonathan Schemoul
    </td>
    <td width="50%" align="left">
      <a href='http://smoothgallery.jondesign.net' target='_blank'>http://smoothgallery.jondesign.net</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Resize Image with Different Aspect Ratio - resizing thumbnails<br />
      Author: Nash
    </td>
    <td width="50%" align="left">
      <a href='http://nashruddin.com/Resize_Image_to_Different_Aspect_Ratio_on_the_fly' target='_blank'>http://nashruddin.com/Resize_Image_to_Different_Aspect_Ratio_on_the_fly</a><br />
    </td>
  </tr>
  <tr>
    <td width="50%" align="right">
      Weighted rating according to Thomas Bayes<br />
      Author: Michael Jašek
    </td>
    <td width="50%" align="left">
      <a href='http://www.buntesuppe.de/blog/123/bayessche-bewertung' target='_blank'>http://www.buntesuppe.de/blog/123/bayessche-bewertung</a><br />
    </td>
  </tr>
  <tr>
    <th colspan="2" align="center" bgcolor="#E7E7E7">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_DONATIONS'); ?>
    </th>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_DONATIONS_LONG'); ?>
      <div style="text-align:center;padding:10px 0;margin:0;vertical-align:middle;">
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=donate%40joomgallery%2enet&amp;item_name=JoomGallery&amp;tax=0&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" title="Donate" target="_blank">
        <img src="<?php echo $this->_ambit->getIcon('others/donate.gif'); ?>"  alt="Donate!" title="Donate!" border="0"/></a>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_SPONSORS'); ?>
      <a href="mailto:joom@joomgallery.net">joom@joomgallery.net</a>
    </td>
  </tr>
  <tr>
    <th colspan="2" align="center" bgcolor="#E7E7E7">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_LICENCE'); ?>
    </th>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <?php echo JText::_('COM_JOOMGALLERY_HLPIFO_NO_GUARANTEE'); ?>
    </td>
  </tr>
  <tr>
    <th colspan="2" align="center" bgcolor="#E7E7E7">
      &nbsp;
    </th>
  </tr>
</table>
<form action="index.php" name="adminForm" method="post">
  <input type="hidden" name="option" value="<?php echo _JOOM_OPTION; ?>" />
  <input type="hidden" name="task" value="" />
</form>
<?php JHTML::_('joomgallery.credits');
