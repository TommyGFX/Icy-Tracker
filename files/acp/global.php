<?php
/**
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @version		$Id$
 */
// define paths
define('RELATIVE_IT_DIR', '../');

// include config
$packageDirs = array();
require_once(dirname(dirname(__FILE__)).'/config.inc.php');

// include WCF
require_once(RELATIVE_WCF_DIR.'global.php');
if (!count($packageDirs)) $packageDirs[] = IT_DIR;
$packageDirs[] = WCF_DIR;

// starting IT acp
require_once(IT_DIR.'lib/system/ITACP.class.php');
new ITACP();
?>