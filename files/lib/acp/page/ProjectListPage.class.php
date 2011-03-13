<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');

/**
 * Shows a list of all projects.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.page
 * @category 	Icy Tracker
 */
class ProjectListPage extends AbstractPage {
	public $templateName = 'projectList';
	public $projects, $projectStructure;
	public $projectList = array();
	public $deletedProjectID = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedProjectID'])) $this->deletedProjectID = intval($_REQUEST['deletedProjectID']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->renderProjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'projects' => $this->projectList,
			'deletedProjectID' => $this->deletedProjectID,
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('ict.acp.menu.link.content.project.view');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.project.canEditProject', 'admin.project.canDeleteProject', 'admin.project.canAddVersion', 'admin.project.canEditVersion', 'admin.project.canDeleteVersion'));
		
		parent::show();
	}
	
	/**
	 * Renders the ordered list of all projects.
	 */
	protected function renderProjects() {
		// get project structure from cache		
		$this->projectStructure = WCF::getCache()->get('project', 'projectStructure');
		
		// get projects from cache
		$this->projects = WCF::getCache()->get('project', 'projects');
				
		$this->makeProjectList();
	}
	
	/**
	 * Renders the project structure.
	 */
	protected function makeProjectList() {
		$i = 0; $projects = count($this->projectStructure);
		
		foreach ($this->projectStructure as $projectID) {
			$project = $this->projects[$projectID];
			
			$last = ($i == ($projects - 1));
			$this->projectList[] = array('project' => $project, 'showOrder' => $i + 1, 'maxShowOrder' => $projects);
			
			$i++;
		}
	}
}
?>