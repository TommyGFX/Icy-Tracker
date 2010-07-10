<?php
// it imports
require_once(IT_DIR.'lib/data/project/ProjectEditor.class.php');

// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');

/**
 * Shows the project add form.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.form
 * @category 	Icy Tracker
 * @version		$Id$
 */
class ProjectAddForm extends ACPForm {
	// system
	public $templateName = 'projectAdd';
	public $activeMenuItem = 'it.acp.menu.link.content.project.add';
	public $neededPermissions = 'admin.project.canAddProject';
	
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
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->closed = $this->countUserPosts = $this->invisible = $this->allowDescriptionHtml = 0;
		
		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['description'])) $this->description = StringUtil::trim($_POST['description']);
		if (isset($_POST['image'])) $this->image = StringUtil::trim($_POST['image']);
		if (!empty($_POST['showOrder'])) $this->showOrder = $_POST['showOrder'];
		if (!empty($_POST['ownername'])) $this->ownername = StringUtil::trim($_POST['ownername']);
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
	}
	
	/**
	 * Validates the projects title.
	 */
	public function validateTitle() {
		if (empty($this->title)) {
			throw new UserInputException('title');
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	it".IT_N."_project
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
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save project
		$this->project = ProjectEditor::create($this->title, $this->description, $this->image, $this->ownerID, intval($this->showOrder), $this->additionalFields);
		
		// reset cache
		Project::resetCache();
		$this->saved();
		
		// reset values
		$this->ownerID = 0;
		$this->title = $this->description = $this->image = $this->ownername = $this->showOrder = '';
		$this->additionalFields = array();
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
			'action' => 'add',
		));
	}
}
?>