<?php
require_once(ICT_DIR.'lib/data/project/Project.class.php');
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');

/**
 * Sorts the order of projects.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.action
 * @category 	Icy Tracker
 */
class ProjectSortAction extends AbstractAction {
	public $showOrder = array();
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_POST['projectListPositions']) && is_array($_POST['projectListPositions'])) $this->showOrder = $_POST['projectListPositions'];
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.project.canEditProject');
		
		foreach ($this->showOrder as $projectID => $showOrder) {
			$projectID = intval($projectID);
			$showOrder = intval($showOrder[0]);
			
			// check project id
			try {
				$project = Project::getProject($projectID);
			}
			catch (IllegalLinkException $e) {
				continue;
			}
			
			// update showOrder
			//TODO: we have to check and reorder the numbers! --RouL
			$sql = "UPDATE	ict".ICT_N."_project
				SET		showOrder = ".$showOrder."
				WHERE	projectID = ".$projectID;
			WCF::getDB()->sendQuery($sql);
		}
		
		// reset cache
		WCF::getCache()->clearResource('project');
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=ProjectList&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>