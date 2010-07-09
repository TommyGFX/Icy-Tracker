<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a project in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	lib.data
 * @category 	Icy Tracker
 * @version		$Id$
 */
class Project extends DatabaseObject {
	/**
	 * Creates a new Project object.
	 *
	 * @param	integer	$projectID
	 * @param	array	$row
	 */
	public function __construct($projectID, $row = null) {
		if ($projectID !== null) {
			$sql = "SELECT	*
				FROM	it".IT_N."_project";
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Resets the project cache after changes.
	 */
	public static function resetCache() {
		// reset cache
//		WCF::getCache()->clearResource('project');
	}
}
?>