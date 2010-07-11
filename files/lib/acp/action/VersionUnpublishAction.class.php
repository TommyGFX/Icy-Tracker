<?php
require_once(IT_DIR.'lib/acp/action/AbstractVersionAction.class.php');

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
class VersionUnpublishAction extends AbstractVersionAction {
	public $neededPermissions = 'admin.project.canEditVersion';
	public $action = 'unpublish';

	/**
	 * Unpublishes the version.
	 */
	public function action() {
		$this->version->unpublish();
	}
}
?>