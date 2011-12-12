<?php
/**
 * Spearhead softwares Joomla Facebook Module for Joomla 1.6
 * 
 * @package Spearhead softwares. 
 * @subpackage Modules
 * @link http://www.spearheadsoftwares.com
 * @license GNU/GPL, see http://www.gnu.org/copyleft/gpl.html
 * mod_spearheadfacebooklike is free software.
 * This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */


//no direct access
defined('_JEXEC') or die('Restricted Access');

//include helper files
require_once(dirname(__FILE__).DS.'helper.php');

$fbButton = modSpearheadFacebookLikeHelper::getFacebookLike($params);
$copyRight = modSpearheadFacebookLikeHelper::copyRight();
require(JModuleHelper::getLayoutPath('mod_spearheadfacebooklike'));
?>
