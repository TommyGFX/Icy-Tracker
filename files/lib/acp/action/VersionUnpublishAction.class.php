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
	public $neededPermissions = 'admin.project.canEditVersion';
	public $action = 'unpublish';

	/**
	 * @see Action::execute()
	 */
	public function action() {
		$this->version->unpublish();
	}
}
?>