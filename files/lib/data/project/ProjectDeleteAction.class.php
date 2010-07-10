<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Deletes a project.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.action
 * @category 	Icy Tracker
 * @version		$Id$
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
		require_once(IT_DIR.'lib/data/project/ProjectEditor.class.php');
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