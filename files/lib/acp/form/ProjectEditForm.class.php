<?php
require_once(IT_DIR.'lib/acp/form/ProjectAddForm.class.php');

/**
 * Shows the project edit form.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.form
 * @category 	Icy Tracker
 * @version		$Id$
 */
class ProjectEditForm extends ProjectAddForm {
	public $activeMenuItem = 'it.acp.menu.link.content.project';
	public $neededPermissions = 'admin.project.canEditProject';
	
	public $projectID = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get project id
		if (isset($_REQUEST['projectID'])) $this->projectID = intval($_REQUEST['projectID']);
		
		// get project
		$this->project = new ProjectEditor($this->projectID);
	}
	
	/**
	 * @see ProjectAddForm::validateTitle()
	 */
	public function validateTitle() {
		if (empty($this->title)) {
			throw new UserInputException('title');
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	it".IT_N."_project
			WHERE	title = '".escapeString($this->title)."'
			AND		projectID <> ".$this->projectID;
		$row = WCF::getDB()->getFirstRow($sql);
		if ($row['count']) {
			throw new UserInputException('title', 'notUnique');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save project
		$this->project->update($this->title, $this->description, $this->image, intval($this->ownerID));
		$this->project->removeShowOrder();
		$this->project->addShowOrder(($this->showOrder ? $this->showOrder : null));
		
		// reset cache
		$this->resetCache();
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			// get values
			$this->title = $this->project->title;
			$this->description = $this->project->description;
			$this->image = $this->project->image;
			$this->ownername = $this->project->getOwner()->username;
			$this->showOrder = $this->project->showOrder;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'projectID' => $this->projectID,
			'project' => $this->project,
			'action' => 'edit',
		));
	}
}
?>