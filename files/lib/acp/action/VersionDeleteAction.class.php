<?php
require_once(ICT_DIR.'lib/acp/action/AbstractVersionAction.class.php');

/**
 * Deletes a version.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.action
 * @category 	Icy Tracker
 */
class VersionDeleteAction extends AbstractVersionAction {
	public $neededPermissions = 'admin.project.canDeleteVersion';
	public $action = 'delete';

	/**
	 * Deletes the version.
	 */
	public function action() {
		if ($this->version->solutions == 0 && $this->version->relations == 0) {
			$this->version->delete();
		}
		else throw new IllegalLinkException();
	}
}
?>