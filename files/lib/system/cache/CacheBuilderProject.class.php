<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');
require_once(ICT_DIR.'lib/data/project/Version.class.php');

// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches all project, the order of projects, project versions and developerNames + "developer assignment".
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	system.cache
 * @category 	Icy Tracker
 */
class CacheBuilderProject implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array(
			'projects' => array(),
			'projectStructure' => array(),
			'versions' => array(),
			'projectToVersions' => array(),
			'developers' => array(),
			'projectToDevelopers' => array(),
			'developerToProject' => array(),
			'access' => array(),
			'projectToAccess' => array(),
			'accessToProject' => array(),
		);
		
		// projects and projectStructure
		$sql = "SELECT		*
			FROM 		ict".ICT_N."_project
			ORDER BY	showOrder";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['projects'][$row['projectID']] = new Project(null, $row);
			$data['projectStructure'][] = $row['projectID'];
		}
		
		// versions
		// TODO: maybe we need a separate project-cache fo each project --RouL
		$sql = "SELECT		version.*, COUNT(DISTINCT issue.issueID) AS solutions, COUNT(DISTINCT issue_version.issueID) AS relations
			FROM		ict".ICT_N."_project_version version
			LEFT JOIN	ict".ICT_N."_issue issue
			ON			(issue.solvedVersionID = version.versionID)
			LEFT JOIN	ict".ICT_N."_issue_version issue_version
			ON			(issue_version.versionID = version.versionID)
			GROUP BY	version.versionID
			ORDER BY	projectID, version";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['versions'][$row['versionID']] = new Version(null, $row);
			$data['projectToVersions'][$row['projectID']][] = $row['versionID'];
		}
		
		// developers (entities)
		// TODO: put this into an extra cache
		$sql = "SELECT		developer.projectID, developer.entityType, user.userID AS entityID, user.username AS entityName
			FROM		ict".ICT_N."_project_developer developer
			LEFT JOIN	wcf".WCF_N."_user user
			ON			(developer.entityType = 'user' AND user.userID = developer.entityID)
			WHERE		developer.entityType = 'user'
			ORDER BY	entityName, developer.projectID";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['developers'][$row['entityType']][$row['entityID']]['id'] = $row['entityID'];
			$data['developers'][$row['entityType']][$row['entityID']]['name'] = $row['entityName'];
			$data['developers'][$row['entityType']][$row['entityID']]['type'] = $row['entityType'];
			$data['developerToProject'][$row['entityType']][$row['entityID']][] = $row['projectID'];
			$data['projectToDevelopers'][$row['projectID']][] = array('type' => $row['entityType'], 'id' => $row['entityID']);
		}
		
		// access entities
		// TODO: put this into an extra cache
		$sql = "SELECT		project_access.projectID, project_access.entityType, IFNULL(user.userID, userGroup.groupID) AS entityID, IFNULL(user.username, userGroup.groupName) AS entityName
			FROM		ict".ICT_N."_project_access project_access
			LEFT JOIN	wcf".WCF_N."_user user
			ON			(project_access.entityType = 'user' AND user.userID = project_access.entityID)
			LEFT JOIN	wcf".WCF_N."_group userGroup
			ON			(project_access.entityType = 'group' AND userGroup.groupID = project_access.entityID)
			ORDER BY	entityName, project_access.projectID";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['access'][$row['entityType']][$row['entityID']]['id'] = $row['entityID'];
			$data['access'][$row['entityType']][$row['entityID']]['name'] = $row['entityName'];
			$data['access'][$row['entityType']][$row['entityID']]['type'] = $row['entityType'];
			$data['accessToProject'][$row['entityType']][$row['entityID']][] = $row['projectID'];
			$data['projectToAccess'][$row['projectID']][] = array('type' => $row['entityType'], 'id' => $row['entityID']);
		}
		
		return $data;
	}
}
?>