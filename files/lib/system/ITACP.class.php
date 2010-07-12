<?php
require_once(WCF_DIR.'lib/system/WCFACP.class.php');

/**
 * This class extends the main WCFACP class by issuetracker specific functions.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	system
 * @category 	Icy Tracker
 * @version		$Id$
 */
class ITACP extends WCFACP {
	/**
	 * @see WCF::getOptionsFilename()
	 */
	protected function getOptionsFilename() {
		return IT_DIR.'options.inc.php';
	}
	
	/**
	 * Initialises the template engine.
	 */
	protected function initTPL() {
		global $packageDirs;
		
		self::$tplObj = new ACPTemplate(self::getLanguage()->getLanguageID(), ArrayUtil::appendSuffix($packageDirs, 'acp/templates/'));
		$this->assignDefaultTemplateVariables();
	}
	
	/**
	 * Does the user authentication.
	 */
	protected function initAuth() {
		parent::initAuth();
		
		// user ban
		if (self::getUser()->banned) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see WCF::assignDefaultTemplateVariables()
	 */
	protected function assignDefaultTemplateVariables() {
		parent::assignDefaultTemplateVariables();
		
		self::getTPL()->assign(array(
			'additionalHeaderButtons' => '<li><a href="'.RELATIVE_IT_DIR.'index.php?page=Index"><img src="'.RELATIVE_IT_DIR.'icon/indexS.png" alt="" /> <span>'.WCF::getLanguage()->get('it.acp.jumpToTracker').'</span></a></li>',
			'pageTitle' => WCF::getLanguage()->get(StringUtil::encodeHTML(PAGE_TITLE)) . ' - ' . StringUtil::encodeHTML(PACKAGE_NAME . ' ' . PACKAGE_VERSION)
		));
	}
	
	/**
	 * @see WCF::loadDefaultCacheResources()
	 */
	protected function loadDefaultCacheResources() {
		parent::loadDefaultCacheResources();
		$this->loadDefaultITCacheResources();
	}
	
	/**
	 * Loads default cache resources of icy tracker acp.
	 * Can be called statically from other applications or plugins.
	 */
	public static function loadDefaultITCacheResources() {
		WCF::getCache()->addResource('project', IT_DIR.'cache/cache.project.php', IT_DIR.'lib/system/cache/CacheBuilderProject.class.php');
	}
}
?>