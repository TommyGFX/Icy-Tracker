<?php
require_once(IT_DIR.'lib/acp/action/AbstractVersionAction.class.php');

/**
 * Unpublishes a version.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.it
 * @subpackage	acp.action
 * @category 	Icy Tracker
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