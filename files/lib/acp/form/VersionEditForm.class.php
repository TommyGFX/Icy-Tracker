<?php
require_once(IT_DIR.'lib/acp/form/VersionAddForm.class.php');

/**
 * Shows the version edit form.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.form
 * @category 	Icy Tracker
 * @version		$Id$
 */
class VersionEditForm extends VersionAddForm {
	public $activeMenuItem = 'it.acp.menu.link.content.project.view';
	public $neededPermissions = 'admin.project.canEditVersion';
	
	public $versionID = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get project id
		if (isset($_REQUEST['versionID'])) $this->versionID = intval($_REQUEST['versionID']);
		
		// get project
		$this->version = new VersionEditor($this->versionID);
	}
	
	/**
	 * @see VersionAddForm::validateVersion()
	 */
	public function validateVersion() {
		if (empty($this->versionname)) {
			throw new UserInputException('versionname');
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	it".IT_N."_project_version
			WHERE	version = '".escapeString($this->versionname)."'
			AND		projectID = ".$this->projectID."
			AND		versionID <> ".$this->versionID;
		$row = WCF::getDB()->getFirstRow($sql);
		if ($row['count']) {
			throw new UserInputException('versionname', 'notUnique');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// save version
		$this->version->update($this->versionname, $this->additionalFields);
		
		// reset cache
		Project::resetCache();
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
			$this->versionname = $this->version->version;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'versionID' => $this->versionID,
			'version' => $this->version,
			'action' => 'edit',
		));
	}
}
?>