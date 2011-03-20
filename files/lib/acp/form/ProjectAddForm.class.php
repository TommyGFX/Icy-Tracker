<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/ProjectEditor.class.php');

// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');
require_once(WCF_DIR.'lib/system/session/Session.class.php');

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
	
	public $developerSettings = array();
	public $accessSettings = array();
	
	/**
	 * project editor object
	 * 
	 * @var	ProjectEditor
	 */
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
	public $owner = null;
	public $ownerID = 0;
	public $showOrder = null;
	public $developerEntities = array();
	public $accessEntities = array();
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['description'])) $this->description = StringUtil::trim($_POST['description']);
		if (isset($_POST['image'])) $this->image = StringUtil::trim($_POST['image']);
		if (!empty($_POST['showOrder'])) $this->showOrder = $_POST['showOrder'];
		if (isset($_POST['ownerID'])) $this->ownerID = intval($_POST['ownerID']);
		if (isset($_POST['developerEntities']) && is_array($_POST['developerEntities'])) $this->developerEntities = $_POST['developerEntities'];
		if (isset($_POST['accessEntities']) && is_array($_POST['accessEntities'])) $this->accessEntities = $_POST['accessEntities'];
		
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
		
		// developer access entities
		DynamicListUtil::validateAccessEntities($this->developerEntities, $this->developerSettings, DynamicListUtil::ACCESS_FILTER_USER);
		
		// access entities
		DynamicListUtil::validateAccessEntities($this->accessEntities, $this->accessSettings);
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
		if (empty($this->ownerID) || $this->ownerID == 0) {
			throw new UserInputException('ownerID');
		}
		
		$this->owner = new User($this->ownerID);
		if (!$this->owner->userID) {
			throw new UserInputException('ownerID');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save project
		$this->project = ProjectEditor::create($this->title, $this->description, $this->image, $this->ownerID, $this->showOrder, $this->additionalFields);
		
		// save developer
		if (count($this->developerEntities)) {
			$this->project->addEntities('developer', $this->developerEntities, $this->developerSettings);
		}
		
		// save access
		if (count($this->accessEntities)) {
			$this->project->addEntities('access', $this->accessEntities, $this->accessSettings);
		}
		
		// reset cache
		$this->resetCache();
		$this->saved();
		
		// reset values
		$this->ownerID = 0;
		$this->title = $this->description = $this->image = $this->showOrder = '';
		$this->developerEntities = $this->accessEntities = $this->additionalFields = array();
		$this->owner = null;
		
		// show success message
		WCF::getTPL()->assign(array(
			'project' => $this->project,
			'success' => true
		));
	}
	
	/**
	 * Resets the project cache and all sessions.
	 */
	protected function resetCache() {
		Project::resetCache();
		
		// reset sessions
		Session::resetSessions(array(), true, false);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		// get access entity settings
		$this->developerSettings = DynamicListUtil::getAvailableEntitySettings('ict'.ICT_N.'_project_developer', array('projectID'));
		$this->accessSettings = DynamicListUtil::getAvailableEntitySettings('ict'.ICT_N.'_project_access', array('projectID'));
		
		parent::readData();
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
			'ownerID' => $this->ownerID,
			'showOrder' => $this->showOrder,
			'developerEntities' => $this->developerEntities,
			'accessEntities' => $this->accessEntities,
			'developerSettings' => $this->developerSettings,
			'accessSettings' => $this->accessSettings,
			'activeTabMenuItem' => $this->activeTabMenuItem,
			'action' => 'add',
		));
		WCF::getTPL()->append('specialStyles', '
		<style type="text/css">
			@import url("'.RELATIVE_WCF_DIR.'acp/style/accessList.css");
		</style>');
	}
}
?>