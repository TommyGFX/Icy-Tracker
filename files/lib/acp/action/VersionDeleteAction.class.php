<?php
require_once(IT_DIR.'lib/acp/action/AbstractVersionAction.class.php');

/**
 * Deletes a version.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.action
 * @category 	Icy Tracker
 * @version		$Id$
 */
class VersionDeleteAction extends AbstractVersionAction {
	public $neededPermissions = 'admin.project.canDeleteVersion';
	public $action = 'delete';

	/**
	 * Deletes the version.
	 */
	public function action() {
		$this->version->delete();
	}
}
?>