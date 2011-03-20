<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');

// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Shows the content of a project.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	page
 * @category 	Icy Tracker
 */
class ProjectPage extends AbstractPage {
	// system
	public $templateName = 'project';
	
	/**
	 * project id
	 * 
	 * @var	integer
	 */
	public $projectID;
	
	/**
	 * project object
	 * 
	 * @var	Project
	 */
	public $project;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['projectID'])) $this->projectID = intval($_REQUEST['projectID']);
		$this->project = new Project($this->projectID);
		$this->project->enterProject();
	}
	
	/**
	 * @see AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
	}
	
	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'projectID' => $this->projectID,
			'project' => $this->project,
			'allowSpidersToIndexThisPage' => true,
		));
	}
}
?>