<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/ProjectEditor.class.php');

// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');

/**
 * Shows the project add form.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.form
 * @category 	Icy Tracker
 */
class ProjectAddForm extends ACPForm {
	// system
	public $templateName = 'projectAdd';
	public $activeMenuItem = 'ict.acp.menu.link.content.project.add';
	public $neededPermissions = 'admin.project.canAddProject';
	public $activeTabMenuItem = 'general';
	
	public $project;
	
	/**
	 * list of additional fields
	 * 
	 * @var	array
	 */
	public $additionalFields = array();
	
	// parameters
	public $title = '';
	public $description = '';
	public $image = '';
	public $ownername = '';
	public $owner = null;
	public $ownerID = 0;
	public $showOrder = '';
	public $developers = array();
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['description'])) $this->description = StringUtil::trim($_POST['description']);
		if (isset($_POST['image'])) $this->image = StringUtil::trim($_POST['image']);
		if (!empty($_POST['showOrder'])) $this->showOrder = $_POST['showOrder'];
		if (!empty($_POST['ownername'])) $this->ownername = StringUtil::trim($_POST['ownername']);
		if (isset($_POST['developer']) && is_array($_POST['developer'])) $this->developers = $_POST['developer'];
		
		if (isset($_POST['activeTabMenuItem'])) $this->activeTabMenuItem = $_POST['activeTabMenuItem'];
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// title
		$this->validateTitle();
		
		// owner
		$this->validateOwner();
		
		// developers
		$this->validateDevelopers();
	}
	
	/**
	 * Validates the projects title.
	 */
	public function validateTitle() {
		if (empty($this->title)) {
			throw new UserInputException('title');
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	ict".ICT_N."_project
			WHERE	title = '".escapeString($this->title)."'";
		$row = WCF::getDB()->getFirstRow($sql);
		if ($row['count']) {
			throw new UserInputException('title', 'notUnique');
		}
	}
	
	/**
	 * Validates the projects owner.
	 */
	public function validateOwner() {
		if (empty($this->ownername)) {
			throw new UserInputException('ownername');
		}
		
		$this->owner = new User(null, null, $username = $this->ownername);
		if (!$this->owner->userID) {
			throw new UserInputException('ownername', 'notValid');
		}
		
		$this->ownerID = $this->owner->userID;
	}
	
	/**
	 * Validates the assigned developers.
	 */
	public function validateDevelopers() {
		foreach ($this->developers as $developer) {
			if (!isset($developer['type']) || $developer['type'] != 'user') {
				throw new UserInputException();
			}
			
			if (!isset($developer['id'])) {
				throw new UserInputException();
			}
			
			$user = new User(intval($developer['id']));
			if (!$user->userID) {
				throw new UserInputException();
			}
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save project
		$this->project = ProjectEditor::create($this->title, $this->description, $this->image, $this->ownerID, intval($this->showOrder), $this->additionalFields);
		
		// save developer
		if (count($this->developers)) {
			$developers = array();
			foreach ($this->developers as $developer) {
				$developers[] = $developer['id'];
			}
			$this->project->addDevelopers($developers);
		}
		
		// reset cache
		Project::resetCache();
		$this->saved();
		
		// reset values
		$this->ownerID = 0;
		$this->title = $this->description = $this->image = $this->ownername = $this->showOrder = '';
		$this->developers = $this->additionalFields = array();
		$this->owner = null;
		
		// show success message
		WCF::getTPL()->assign(array(
			'project' => $this->project,
			'success' => true
		));
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'title' => $this->title,
			'description' => $this->description,
			'image' => $this->image,
			'ownername' => $this->ownername,
			'showOrder' => $this->showOrder,
			'developers' => $this->developers,
			'activeTabMenuItem' => $this->activeTabMenuItem,
			'action' => 'add',
		));
	}
}
?>