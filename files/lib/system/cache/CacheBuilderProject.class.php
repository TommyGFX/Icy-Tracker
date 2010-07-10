<?php
// it imports
require_once(IT_DIR.'lib/data/project/Project.class.php');

// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches all project and the order of projects.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	system.cache
 * @category 	Icy Tracker
 * @version		$Id$
 */
class CacheBuilderProject implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array('projects' => array(), 'projectStructure' => array());
		
		$sql = "SELECT		*
			FROM 		it".IT_N."_project
			ORDER BY	showOrder";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['projects'][$row['projectID']] = new Project(null, $row);
			$data['projectStructure'][] = $row['projectID'];
		}
		
		return $data;
	}
}
?>