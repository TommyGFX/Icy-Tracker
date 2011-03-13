<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Deletes a project.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.action
 * @category 	Icy Tracker
 */
class ProjectDeleteAction extends AbstractAction {
	public $projectID = 0;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['projectID'])) $this->projectID = intval($_REQUEST['projectID']);
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.project.canDeleteProject');
		
		// delete project
		require_once(ICT_DIR.'lib/data/project/ProjectEditor.class.php');
		$project = new ProjectEditor($this->projectID);	
		$project->delete();
		WCF::getCache()->clearResource('project');
		$this->executed();
		
		// forward to project list page
		HeaderUtil::redirect('index.php?page=ProjectList&deletedProjectID='.$this->projectID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>