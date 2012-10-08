<?php
/**
 * @package Gantry Template Framework - RocketTheme
 * @version 3.2.12 October 30, 2011
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

// load and inititialize gantry class
require_once('lib/gantry/gantry.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $gantry->language; ?>" lang="<?php echo $gantry->language;?>" >
    <head>
        <?php
            $gantry->displayHead();
            $gantry->addStyles(array('template.css','joomla.css','style.css'));
        ?>
    </head>
    <body <?php echo $gantry->displayBodyTag(); ?>>
<script type="text/javascript">
window.Meebo||function(c){function p(){return["<",i,' onload="var d=',g,";d.getElementsByTagName('head')[0].",
j,"(d.",h,"('script')).",k,"='//cim.meebo.com/cim?iv=",a.v,"&",q,"=",c[q],c[l]?
"&"+l+"="+c[l]:"",c[e]?"&"+e+"="+c[e]:"","'\"></",i,">"].join("")}var f=window,
a=f.Meebo=f.Meebo||function(){(a._=a._||[]).push(arguments)},d=document,i="body",
m=d[i],r;if(!m){r=arguments.callee;return setTimeout(function(){r(c)},100)}a.$=
{0:+new Date};a.T=function(u){a.$[u]=new Date-a.$[0]};a.v=5;var j="appendChild",
h="createElement",k="src",l="lang",q="network",e="domain",n=d[h]("div"),v=n[j](d[h]("m")),
b=d[h]("iframe"),g="document",o,s=function(){a.T("load");a("load")};f.addEventListener?
f.addEventListener("load",s,false):f.attachEvent("onload",s);n.style.display="none";
m.insertBefore(n,m.firstChild).id="meebo";b.frameBorder="0";b.name=b.id="meebo-iframe";
b.allowTransparency="true";v[j](b);try{b.contentWindow[g].open()}catch(w){c[e]=
d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{var t=
b.contentWindow[g];t.write(p());t.close()}catch(x){b[k]=o+'d.write("'+p().replace(/"/g,
'\\"')+'");d.close();'}a.T(1)}({network:"worldwideinterweb_na32ja"});
</script>
        <?php /** Begin Drawer **/ if ($gantry->countModules('drawer')) : ?>
        <div id="rt-drawer">
            <div class="rt-container">
                <?php echo $gantry->displayModules('drawer','standard','standard'); ?>
                <div class="clear"></div>
            </div>
        </div>
        <?php /** End Drawer **/ endif; ?>
		<?php /** Begin Top **/ if ($gantry->countModules('top')) : ?>
		<div id="rt-top" <?php echo $gantry->displayClassesByTag('rt-top'); ?>>
			<div class="rt-container">
				<?php echo $gantry->displayModules('top','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Top **/ endif; ?>
		<?php /** Begin Header **/ if ($gantry->countModules('header')) : ?>
		<div id="rt-header">
			<div class="rt-container">
				<?php echo $gantry->displayModules('header','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Header **/ endif; ?>
		<?php /** Begin Menu **/ if ($gantry->countModules('navigation')) : ?>
		<div id="rt-menu">
			<div class="rt-container">
				<?php echo $gantry->displayModules('navigation','basic','basic'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Menu **/ endif; ?>
		<?php /** Begin Showcase **/ if ($gantry->countModules('showcase')) : ?>
		<div id="rt-showcase">
			<div class="rt-container">
				<?php echo $gantry->displayModules('showcase','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Showcase **/ endif; ?>
		<?php /** Begin Feature **/ if ($gantry->countModules('feature')) : ?>
		<div id="rt-feature">
			<div class="rt-container">
				<?php echo $gantry->displayModules('feature','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Feature **/ endif; ?>
		<?php /** Begin Utility **/ if ($gantry->countModules('utility')) : ?>
		<div id="rt-utility">
			<div class="rt-container">
				<?php echo $gantry->displayModules('utility','standard','basic'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Utility **/ endif; ?>
		<?php /** Begin Breadcrumbs **/ if ($gantry->countModules('breadcrumb')) : ?>
		<div id="rt-breadcrumbs">
			<div class="rt-container">
				<?php echo $gantry->displayModules('breadcrumb','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Breadcrumbs **/ endif; ?>
		<?php /** Begin Main Top **/ if ($gantry->countModules('maintop')) : ?>
		<div id="rt-maintop">
			<div class="rt-container">
				<?php echo $gantry->displayModules('maintop','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Main Top **/ endif; ?>
		<?php /** Begin Main Body **/ ?>
	    <?php echo $gantry->displayMainbody('mainbody','sidebar','standard','standard','standard','standard','standard'); ?>
		<?php /** End Main Body **/ ?>
		<?php /** Begin Main Bottom **/ if ($gantry->countModules('mainbottom')) : ?>
		<div id="rt-mainbottom">
			<div class="rt-container">
				<?php echo $gantry->displayModules('mainbottom','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Main Bottom **/ endif; ?>
		<?php /** Begin Bottom **/ if ($gantry->countModules('bottom')) : ?>
		<div id="rt-bottom">
			<div class="rt-container">
				<?php echo $gantry->displayModules('bottom','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Bottom **/ endif; ?>
		<?php /** Begin Footer **/ if ($gantry->countModules('footer')) : ?>
		<div id="rt-footer">
			<div class="rt-container">
				<?php echo $gantry->displayModules('footer','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Footer **/ endif; ?>
		<?php /** Begin Debug **/ if ($gantry->countModules('debug')) : ?>
		<div id="rt-debug">
			<div class="rt-container">
				<?php echo $gantry->displayModules('debug','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Debug **/ endif; ?>
		<?php /** Begin Analytics **/ if ($gantry->countModules('analytics')) : ?>
		<?php echo $gantry->displayModules('analytics','basic','basic'); ?>
		<?php /** End Analytics **/ endif; ?>
<script type="text/javascript">
  Meebo("domReady");
</script>
	</body>
</html>
<?php
$gantry->finalize();
?>
