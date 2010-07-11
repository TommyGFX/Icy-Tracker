<?php
require_once(IT_DIR.'lib/acp/action/AbstractVersionAction.class.php');

/**
 * Publishes a version.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	acp.action
 * @category 	Icy Tracker
 * @version		$Id$
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