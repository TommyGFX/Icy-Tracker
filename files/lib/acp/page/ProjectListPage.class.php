<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

// it imports
require_once(IT_DIR.'lib/data/project/Project.class.php');

/**
 * Shows a list of all projects.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.page
 * @category 	Icy Tracker
 * @version		$Id$
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
		WCFACP::getMenu()->setActiveMenuItem('it.acp.menu.link.content.project.view');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.project.canEditProject', 'admin.project.canDeleteProject'));
		
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