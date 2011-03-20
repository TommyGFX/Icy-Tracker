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
 * @category	Icy Tracker
 */
class CacheBuilderProjectPermissions implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $groupIDs) = explode('-', $cacheResource['cache']);
		$data = array('groupIDs' => $groupIDs);
		
		$sql = "SELECT	*
			FROM	ict".ICT_N."_project_access
			WHERE	entityType = 'group'
			AND		entityID IN (".$groupIDs.")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			foreach ($row as $column => $value) {
				if ($value == -1 || array_search($column, array('projectID', 'entityID', 'entityType', 'entityName'))) continue;
				
				if (!isset($data[$row['projectID']][$column])) {
					$data[$row['projectID']][$column] = $value;
				}
				else {
					$data[$row['projectID']][$column] |= $value;
				}
			}
		}
		
		return $data;
	}
}
?>