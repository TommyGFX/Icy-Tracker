<?php
/**
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */
// define paths
define('RELATIVE_ICT_DIR', '../');

// include config
$packageDirs = array();
require_once(dirname(dirname(__FILE__)).'/config.inc.php');

// include WCF
require_once(RELATIVE_WCF_DIR.'global.php');
if (!count($packageDirs)) $packageDirs[] = ICT_DIR;
$packageDirs[] = WCF_DIR;

// starting ict acp
require_once(ICT_DIR.'lib/system/ICTACP.class.php');
new ITACP();
?>