<?php
// wcf imports
require_once(WCF_DIR.'lib/system/session/UserSession.class.php');

/**
 * Abstract class for ict user and guest sessions.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	data.user
 * @category	Icy Tracker
 */
class AbstractICTUserSession extends UserSession {
	protected $projectPermissions = array();
	protected $projectDeveloperPermissions = array();
	
	/**
	 * Checks whether the current user has the give permission on the given project.
	 * 
	 * @param	string	$permission
	 * @param	integer	$projectID
	 * 
	 * @return	mixed
	 */
	public function getProjectPermission($permission, $projectID) {
		if (isset($this->projectPermissions[$projectID][$permission])) {
			return $this->projectPermissions[$projectID][$permission];
		}
		return $this->getPermission('user.tracker.'.$permission);
	}
	
	/**
	 * Checks whether the current user has the given developer permission on the given project.
	 * 
	 * @param	string	$permission
	 * @param	integer	$projectID
	 * 
	 * @return	mixed
	 */
	public function getProjectDeveloperPermission($permission, $projectID) {
		if (isset($this->projectDeveloperPermissions[$projectID][$permission])) {
			return $this->projectDeveloperPermissions[$projectID][$permission];
		}
		
		return (($this->getPermission('developer.tracker.isGlobalDeveloper') || isset($this->projectDeveloperPermissions[$projectID])) && $this->getPermission('developer.tracker.'.$permission));
	}
	
	/**
	 * Checks whether the current user is a developer on the given project.
	 * 
	 * @param	integer	$projectID
	 * 
	 * @return	boolean
	 */
	public function isDeveloper($projectID) {
		return ($this->getPermission('developer.tracker.isGlobalDeveloper') || isset($this->projectDeveloperPermissions[$projectID]));
	}
	
	/**
	 * @see UserSession::getGroupData()
	 */
	protected function getGroupData() {
		parent::getGroupData();
		
		// register group access cache resource
		$groups = implode(",", $this->groupIDs);
		$cacheFileSuffix = StringUtil::getHash(implode("-", $this->groupIDs));
		WCF::getCache()->addResource('projectPermissions-'.$groups, ICT_DIR.'cache/cache.projectPermissions-'.$cacheFileSuffix.'.php', ICT_DIR.'lib/system/cache/CacheBuilderProjectPermissions.class.php');
		
		// get group access data from cache
		$this->projectPermissions = WCF::getCache()->get('projectPermissions-'.$groups);
		if (isset($this->projectPermissions['groupIDs']) && $this->projectPermissions['groupIDs'] != $groups) {
			$this->projectPermissions = array();
		}
	}
}

?>