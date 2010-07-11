<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Unpublishes a version.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.action
 * @category 	Icy Tracker
 * @version		$Id$
 */
class VersionUnpublishAction extends AbstractAction {
	public $versionID = 0;
	public $projectID = 0;
	
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
		WCF::getUser()->checkPermission('admin.project.canEditVersion');
		
		// unpublish version
		require_once(IT_DIR.'lib/data/project/VersionEditor.class.php');
		$version = new VersionEditor($this->versionID);
		$this->projectID = $version->projectID;
		$version->unpublish();
		WCF::getCache()->clearResource('project');
		$this->executed();
		
		// forward to project view page
		HeaderUtil::redirect('index.php?page=ProjectView&projectID='.$this->projectID.'&deletedVersionID='.$this->versionID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>