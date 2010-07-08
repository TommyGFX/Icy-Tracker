<?php
/**
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @version		$Id$
 */
// include config
$packageDirs = array();
require_once(dirname(__FILE__).'/config.inc.php');

// include WCF
require_once(RELATIVE_WCF_DIR.'global.php');
if (!count($packageDirs)) $packageDirs[] = IT_DIR;
$packageDirs[] = WCF_DIR;

// starting it core
require_once(IT_DIR.'lib/system/ITCore.class.php');
new ITCore();
?>