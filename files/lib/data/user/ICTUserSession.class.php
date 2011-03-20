<?php
// ict imports
require_once(ICT_DIR.'lib/data/user/AbstractICTUserSession.class.php');

/**
 * Represents a user sessions in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	data.user
 * @category	Icy Tracker
 * 
 * @todo		add whitelist, blacklist, friendinvites and avatar stuff later (if they're LGPL) --RouL
 */
class ICTUserSession extends AbstractICTUserSession {
	/**
	 * @see UserSession::getGroupData()
	 */
	protected function getGroupData() {
		parent::getGroupData();
		
		// get user access from db
		$sql = "SELECT	*
			FROM	ict".ICT_N."_project_access
			WHERE	entityType = 'user'
			AND		entityID = ".$this->userID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			foreach ($row as $column => $value) {
				if ($value == -1 || array_search($column, array('projectID', 'entityID', 'entityType', 'entityName'))) continue;
				
				if (!isset($data[$row['projectID']][$column])) {
					$this->projectPermissions[$row['projectID']][$column] = $value;
				}
				else {
					$this->projectPermissions[$row['projectID']][$column] |= $value;
				}
			}
		}
		
		// get developer access from db
		$sql = "SELECT	*
			FROM	ict".ICT_N."_project_developer
			WHERE	entityType = 'user'
			AND		entityID = ".$this->userID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!isset($this->projectDeveloperPermissions[$row['projectID']])) {
				$this->projectDeveloperPermissions[$row['projectID']] = array();
			}
			foreach ($row as $column => $value) {
				if ($value == -1 || array_search($column, array('projectID', 'entityID', 'entityType', 'entityName'))) continue;
				
				if (!isset($data[$row['projectID']][$column])) {
					$this->projectDeveloperPermissions[$row['projectID']][$column] = $value;
				}
				else {
					$this->projectDeveloperPermissions[$row['projectID']][$column] |= $value;
				}
			}
		}
	}
}

?>