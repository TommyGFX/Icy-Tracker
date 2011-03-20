<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(ICT_DIR.'lib/data/project/Version.class.php');

/**
 * Represents a project in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 */
class Project extends DatabaseObject {
	protected static $projects = null;
	protected static $projectToVersions = null;
	protected static $projectToDevelopers = null;
	protected static $developerData = null;
	protected $owner = null;
	protected $versions = null;
	protected $developers = null;
	
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
		
	}

	/**
	 * Gets the project with the given project id from cache.
	 *
	 * @param	integer		$projectID
	 * 
	 * @return	Project
	 * @throws	IllegalLinkException
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
	
	/**
	 * Gets all assigned developers userIDs and usernames.
	 * 
	 * @return	array
	 */
	public function getDeveloper() {
		if ($this->developers == null) {
			if (self::$projectToDevelopers == null) {
				self::$projectToDevelopers = WCF::getCache()->get('project', 'projectToDevelopers');
			}
			if (self::$developerData === null) {
				self::$developerData = WCF::getCache()->get('project', 'developers');
			}
			$this->developers = array();
			if (isset(self::$projectToDevelopers[$this->projectID])) {
				foreach (self::$projectToDevelopers[$this->projectID] as $userID) {
					$this->developers[$userID] = self::$developerData[$userID];
				}
			}
		}
		
		return $this->developers;
	}
	
	/**
	 * Gets all entities of a given set.
	 * 
	 * possible values for $set: access, developer
	 * 
	 * @param	string	$set
	 * 
	 * @return	array<array>
	 * 
	 * @throws	SystemException
	 */
	public function getEntities($set) {
		if ($set != 'access' && $set != 'developer') {
			throw new SystemException('Unknown entity-set "'.$set.'"');
		}
		
		$sql = "SELECT		entities.*, IFNULL(user.username, usergroup.groupName) entityName
			FROM		ict".ICT_N."_project_".$set." entities
			LEFT JOIN	wcf".WCF_N."_user user
			ON			(entities.entityType = 'user' AND user.userID = entities.entityID)
			LEFT JOIN	wcf".WCF_N."_group usergroup
			ON			(entities.entityType = 'group' AND usergroup.groupID = entities.entityID)
			WHERE		projectID = ".$this->projectID."
			ORDER BY	entityName, entities.entityType";
		$result = WCF::getDB()->sendQuery($sql);
		$entities = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$entity = array();
			$entity['settings'] = array();
			foreach($row as $column => $value) {
				switch ($column) {
					case 'projectID':
						break;
					
					case 'entityID':
						$entity['id'] = $value;
						break;
					
					case 'entityType':
						$entity['type'] = $value;
						break;
					
					case 'entityName':
						$entity['name'] = $value;
						break;
					
					default:
						$entity['settings'][$column] = $value;
				}
			}
			$entities[] = $entity;
		}
		
		return $entities;
	}
	
	/**
	 * Checks if the current user can enter this project.
	 * 
	 * @return	boolean
	 */
	public function canEnterProject() {
		/*
		// check if global developer
		if (WCF::getUser()->getPermission('developer.tracker.isGlobalDeveloper')) {
			return true;
		}
		
		// check if developer in this project 
		$developerEntities = $this->getDeveloperEntities();
		foreach ($developerEntities as $developerEntity) {
			switch ($developerEntity['type']) {
				case 'user':
					if ($developerEntity['id'] == WCF::getUser()->userID) {
						return true;
					}
					break;
			}
		}
		
		// check if user access to this project 
		$accessEntities = $this->getAccessEntities();
		foreach ($accessEntities as $accessEntity) {
			switch ($accessEntity['type']) {
				case 'user':
					if ($accessEntity['id'] == WCF::getUser()->userID) {
						return true;
					}
					break;
					
				case 'group':
					if (array_search($accessEntity['id'], WCF::getUser()->getGroupIDs())) {
						return true;
					}
					break;
			}
		}
		
		return false;
		*/
		return true;
	}
	
	/**
	 * Enters the current user to this project.
	 * 
	 * @throws	PermissionDeniedException
	 */
	public function enterProject() {
		// check permission
		if (!$this->canEnterProject()) {
			throw new PermissionDeniedException();
		}
	}
}
?>