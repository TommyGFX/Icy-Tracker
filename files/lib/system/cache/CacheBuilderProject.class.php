<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');
require_once(ICT_DIR.'lib/data/project/Version.class.php');

// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches all project, the order of projects and project versions.
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
		$data = array('projects' => array(), 'projectStructure' => array(), 'versions' => array(), 'projectToVersions' => array());
		
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
		$sql = "SELECT		*
			FROM		ict".ICT_N."_project_version
			ORDER BY	projectID, version";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['versions'][$row['versionID']] = new Version(null, $row);
			$data['projectToVersions'][$row['projectID']][] = $row['versionID'];
		}
		
		return $data;
	}
}
?>