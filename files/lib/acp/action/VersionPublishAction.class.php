<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

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
class VersionPublishAction extends AbstractAction {
	public $neededPermissions = 'admin.project.canEditVersion';
	public $action = 'publish';

	/**
	 * @see Action::execute()
	 */
	public function action() {
		$this->version->publish();
	}
}
?>