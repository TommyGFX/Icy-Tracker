<?php
// it imports
require_once(IT_DIR.'lib/data/project/Project.class.php');
require_once(IT_DIR.'lib/data/project/Version.class.php');

// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows all information about a project.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.page
 * @category 	Icy Tracker
 * @version		$Id$
 */
class ProjectViewPage extends SortablePage {
	// system
	public $templateName = 'projectView';
	public $deletedVersionID = 0;
	public $defaultSortField = 'version';
	
	public $projectID = 0;
	public $project = null;
	public $versions = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedVersionID'])) $this->deletedVersionID = intval($_REQUEST['deletedVersionID']);
		if (isset($_REQUEST['projectID'])) $this->projectID = intval($_REQUEST['projectID']);
		$this->project = Project::getProject($this->projectID);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->readVersions();
	}
	
	/**
	 * Gets a list of versions.
	 */
	protected function readVersions() {
		if ($this->items) {
			$sql = "SELECT		versionID
				FROM		it".IT_N."_project_version
				WHERE		projectID = ".$this->projectID."
				ORDER BY	".$this->sortField." ".$this->sortOrder;
			$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$this->versions[] = Version::getVersion($row['versionID']);
			}
		}
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'version':
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return count($this->project->getVersions());
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'project' => $this->project,
			'versions' => $this->versions,
			'deletedVersionID' => $this->deletedVersionID
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('it.acp.menu.link.content.project.view');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.project.canEditProject', 'admin.project.canDeleteProject', 'admin.project.canAddVersion', 'admin.project.canEditVersion', 'admin.project.canDeleteVersion'));
		
		parent::show();
	}
}
?>