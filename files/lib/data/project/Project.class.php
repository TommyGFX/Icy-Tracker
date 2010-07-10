<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a project in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 * @version		$Id$
 */
class Project extends DatabaseObject {
	protected static $projects = null;
	protected $owner = null;
	
	/**
	 * Creates a new Project object.
	 *
	 * @param	integer	$projectID
	 * @param	array	$row
	 * @param	Project	$cacheObject
	 */
	public function __construct($projectID, $row = null, $cacheObject = null) {
		if ($projectID !== null) $cacheObject = self::getProject($projectID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
		
//		if ($projectID !== null) {
//			$sql = "SELECT	*
//				FROM	it".IT_N."_project
//				WHERE	projectID = ".$projectID;
//			$row = WCF::getDB()->getFirstRow($sql);
//		}
//		parent::__construct($row);
	}

	/**
	 * Gets the project with the given project id from cache.
	 *
	 * @param	integer		$projectID
	 * 
	 * @return	Project
	 */
	public static function getProject($projectID) {
		if (self::$projects === null) {
			self::$projects = WCF::getCache()->get('project', 'projects');
		}

		if (!isset(self::$projects[$projectID])) {
			throw new IllegalLinkException();
		}

		return self::$projects[$projectID];
	}
	
	/**
	 * Resets the project cache after changes.
	 */
	public static function resetCache() {
		// reset cache
		WCF::getCache()->clearResource('project');
		
		self::$projects = null;
	}
	
	public function getOwner() {
		if ($this->owner == null) {
			if ($this->ownerID) {
				$this->owner = new User($this->ownerID);
			}
		}
		
		return $this->owner;
	}
}
?>