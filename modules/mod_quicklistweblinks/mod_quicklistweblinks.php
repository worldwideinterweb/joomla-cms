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

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$jqw_params = modJoesQuicklistWeblinksHelper::getModuleContent( $params );
require( JModuleHelper::getLayoutPath( 'mod_quicklistweblinks' ) );
?>
