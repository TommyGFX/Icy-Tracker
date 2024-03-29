<?php
/**
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 */
$packageID = $this->installation->getPackageID();

// set installation date
$sql = "UPDATE	wcf".WCF_N."_option
	SET	optionValue = ".TIME_NOW."
	WHERE	optionName = 'install_date'
		AND packageID = ".$packageID;
WCF::getDB()->sendQuery($sql);

// set page url and cookie path
if (!empty($_SERVER['SERVER_NAME'])) {
	// domain
	$pageURL = 'http://' . $_SERVER['SERVER_NAME'];
	
	// port
	if (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80) {
		$pageURL .= ':' . $_SERVER['SERVER_PORT'];
	}
	
	// file
	$path = FileUtil::removeTrailingSlash(FileUtil::getRealPath(FileUtil::addTrailingSlash(dirname(dirname(WCF::getSession()->requestURI))) . $this->installation->getPackage()->getDir()));
	$pageURL .= $path;
	
	$sql = "UPDATE	wcf".WCF_N."_option
		SET	optionValue = '".escapeString($pageURL)."'
		WHERE	optionName = 'page_url'
			AND packageID = ".$packageID;
	WCF::getDB()->sendQuery($sql);
	
	$sql = "UPDATE	wcf".WCF_N."_option
		SET	optionValue = '".escapeString($path)."'
		WHERE	optionName = 'cookie_path'
			AND packageID = ".$packageID;
	WCF::getDB()->sendQuery($sql);
}

// admin options
$sql = "UPDATE 	wcf".WCF_N."_group_option_value
	SET	optionValue = 1
	WHERE	groupID = 4
		AND optionID IN (
			SELECT	optionID
			FROM	wcf".WCF_N."_group_option
			WHERE	packageID IN (
					SELECT	dependency
					FROM	wcf".WCF_N."_package_dependency
					WHERE	packageID = ".$packageID."
				)
		)
		AND optionValue = '0'";
WCF::getDB()->sendQuery($sql);

// refresh all style files
require_once(WCF_DIR.'lib/data/style/StyleEditor.class.php');
$sql = "SELECT * FROM wcf".WCF_N."_style";
$style = new StyleEditor(null, WCF::getDB()->getFirstRow($sql));
$style->writeStyleFile();

?>