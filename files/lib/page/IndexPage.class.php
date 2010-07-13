<?php
// it imports
require_once(IT_DIR.'lib/data/project/Project.class.php');

// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Shows the start page of the tracker.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	page
 * @category 	Icy Tracker
 * @version		$Id$
 */
class IndexPage extends AbstractPage {
	// system
	public $templateName = 'index';
	
	// data
	public $projects = array();
	public $projectStructure = array();
	
	/**
	 * @see AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->projectStructure = WCF::getCache()->get('projects', 'projectStructure');
	}
	
	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		$this->renderProjects();
		
		WCF::getTPL()->assign(array(
			'projects' => $this->projects,
			'allowSpidersToIndexThisPage' => true,
		));
	}
	
	/**
	 * Renders the project order.
	 */
	public function renderProjects() {
		if (count($this->projectStructure)) {
			foreach ($this->projectStructure as $projectID) {
				$this->projects[] = Project::getProject($projectID);
			}
		}
	}
}
?>