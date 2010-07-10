<?php
// wbb imports
require_once(WBB_DIR.'lib/data/board/Project.class.php');

// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');

/**
 * Caches all project and the order of boards.
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
			FROM 		it".IT_N."_board
			ORDER BY	showOrder";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['projects'][$row['boardID']] = new Project(null, $row);
			$data['projectStructure'][] = $row['boardID'];
		}
		
		return $data;
	}
}
?>