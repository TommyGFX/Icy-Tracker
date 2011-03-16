<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/ProjectEditor.class.php');

// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/page/AccessEntitiesSuggestPage.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');

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
		$this->validateAccessEntities($this->developerEntities, AccessEntitiesSuggestPage::FILTER_USER);
		
		// access entities
		$this->validateAccessEntities($this->accessEntities);
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
	 * Validates access entities.
	 */
	public function validateAccessEntities($accessEntities, $filter = AccessEntitiesSuggestPage::FILTER_ALL) {
		foreach ($accessEntities as $entity) {
			switch ($filter) {
				case AccessEntitiesSuggestPage::FILTER_USER:
					if (!isset($entity['type']) || $entity['type'] != 'user') {
						throw new UserInputException();
					}
					break;
					
				case AccessEntitiesSuggestPage::FILTER_GROUP:
					if (!isset($entity['type']) || $entity['type'] != 'group') {
						throw new UserInputException();
					}
					break;
					
				case AccessEntitiesSuggestPage::FILTER_ALL:
				default:
					if (!isset($entity['type']) || ($entity['type'] != 'user' && $entity['type'] != 'group')) {
						throw new UserInputException();
					}
					break;
			}
			
			if (!isset($entity['id'])) {
				throw new UserInputException();
			}
			
			switch ($entity['type']) {
				case 'user':
					$user = new User(intval($entity['id']));
					if (!$user->userID) {
						throw new UserInputException();
					}
					break;
					
				case 'group':
					$user = new Group(intval($entity['id']));
					if (!$user->groupID) {
						throw new UserInputException();
					}
					break;
			}
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
			$this->project->addDeveloperEntities($this->developerEntities);
		}
		
		// save access
		if (count($this->accessEntities)) {
			$this->project->addAccessEntities($this->accessEntities);
		}
		
		// reset cache
		Project::resetCache();
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