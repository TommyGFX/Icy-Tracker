<?php
// it imports
require_once(IT_DIR.'lib/data/project/Project.class.php');
require_once(IT_DIR.'lib/data/project/VersionEditor.class.php');

// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');

/**
 * Shows the version add form.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.form
 * @category 	Icy Tracker
 * @version		$Id$
 */
class VersionAddForm extends ACPForm {
	// system
	public $templateName = 'versionAdd';
	public $activeMenuItem = 'it.acp.menu.link.content.project.view';
	public $neededPermissions = 'admin.project.canAddVersion';
	
	public $projectID = 0;
	public $project = null;
	public $version = null;
	
	/**
	 * list of additional fields
	 * 
	 * @var	array
	 */
	public $additionalFields = array();
	
	// parameters
	public $versionname = '';
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['projectID'])) $this->projectID = intval($_REQUEST['projectID']);
		$this->project = Project::getProject($this->projectID);
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['versionname'])) $this->versionname = StringUtil::trim($_POST['versionname']);
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// version
		$this->validateVersion();
	}
	
	/**
	 * Validates the version.
	 */
	public function validateVersion() {
		if (empty($this->versionname)) {
			throw new UserInputException('versionname');
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	it".IT_N."_project
			WHERE	title = '".escapeString($this->versionname)."'
			AND		projectID = ".$this->projectID;
		$row = WCF::getDB()->getFirstRow($sql);
		if ($row['count']) {
			throw new UserInputException('versionname', 'notUnique');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save project
		$this->version = VersionEditor::create($this->projectID, $this->versionname, $this->additionalFields);
		
		// reset cache
		Project::resetCache();
		$this->saved();
		
		// reset values
		$this->versionname = '';
		$this->additionalFields = array();
		$this->version = null;
		
		// show success message
		WCF::getTPL()->assign(array(
			'version' => $this->version,
			'success' => true,
		));
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'versionname' => $this->versionname,
			'project' => $this->project,
			'action' => 'add',
		));
	}
}
?>