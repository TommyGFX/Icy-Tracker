<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(IT_DIR.'lib/data/project/Version.class.php');

/**
 * Represents a project in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.it
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 */
class Project extends DatabaseObject {
	protected static $projects = null;
	protected static $projectToVersions = null;
	protected $owner = null;
	protected $versions = null;
	
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
	
	/**
	 * Gets the user object of the owner of this project.
	 * 
	 * @return	User
	 */
	public function getOwner() {
		if ($this->owner == null) {
			if ($this->ownerID) {
				$this->owner = new User($this->ownerID);
			}
		}
		
		return $this->owner;
	}
	
	/**
	 * Gets all versions of this project.
	 * 
	 * @return	array<Version>
	 */
	public function getVersions() {
		if ($this->versions == null) {
			if (self::$projectToVersions === null) {
				self::$projectToVersions = WCF::getCache()->get('project', 'projectToVersions');
			}
			
			$this->versions = array();
			if (isset(self::$projectToVersions[$this->projectID])) {
				$versionIDs = self::$projectToVersions[$this->projectID];
				foreach ($versionIDs as $versionID) {
					$this->versions[] = Version::getVersion($versionID);
				}
			}
		}
		return $this->versions;
	}
}
?>