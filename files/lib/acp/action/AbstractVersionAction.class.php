<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.action
 * @category 	Icy Tracker
 */
abstract class AbstractVersionAction extends AbstractAction {
	public $version = null;
	public $versionID = 0;
	public $projectID = 0;
	//public $neededPermissions = '';
	//public $action = ''
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['versionID'])) $this->versionID = intval($_REQUEST['versionID']);
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission($this->neededPermissions);
		
		// unpublish version
		require_once(ICT_DIR.'lib/data/project/VersionEditor.class.php');
		$this->version = new VersionEditor($this->versionID);
		$this->projectID = $this->version->projectID;
		
		$this->action();
		
		WCF::getCache()->clearResource('project');
		$this->executed();
		
		// forward to project view page
		HeaderUtil::redirect('index.php?page=ProjectView&projectID='.$this->projectID.'&actionVersionID='.$this->versionID.'&actionType='.$this->action.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>