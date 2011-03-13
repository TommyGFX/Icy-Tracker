<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');

// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Shows the start page of the tracker.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	page
 * @category 	Icy Tracker
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
		
		$this->projectStructure = WCF::getCache()->get('project', 'projectStructure');
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