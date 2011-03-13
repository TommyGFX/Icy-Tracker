<?php
require_once(ICT_DIR.'lib/acp/action/AbstractVersionAction.class.php');

/**
 * Publishes a version.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.action
 * @category 	Icy Tracker
 */
class VersionPublishAction extends AbstractVersionAction {
	public $neededPermissions = 'admin.project.canEditVersion';
	public $action = 'publish';

	/**
	 * Publishes the version.
	 */
	public function action() {
		$this->version->publish();
	}
}
?>