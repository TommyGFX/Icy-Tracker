<?php
// wcf imports
require_once(WCF_DIR.'lib/page/util/menu/PageMenuContainer.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenuContainer.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserProfileMenuContainer.class.php');
require_once(WCF_DIR.'lib/system/style/StyleManager.class.php');

/**
 * This class extends the main WCF class by issuetracker specific functions.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	system
 * @category 	Icy Tracker
 * @version		$Id$
 */
class ITCore extends WCF implements PageMenuContainer, UserCPMenuContainer, UserProfileMenuContainer {
	protected static $pageMenuObj = null;
	protected static $userCPMenuObj = null;
	protected static $userProfileMenuObj = null;
	public static $availablePagesDuringOfflineMode = array(
		'page' => array('Captcha', 'LegalNotice'),
		'form' => array('UserLogin'),
		'action' => array('UserLogout'));
	
	/**
	 * @see WCF::initTPL()
	 */
	protected function initTPL() {
		$this->initStyle();
		
		global $packageDirs;
		require_once(WCF_DIR.'lib/system/template/StructuredTemplate.class.php');
		self::$tplObj = new StructuredTemplate(self::getStyle()->templatePackID, self::getLanguage()->getLanguageID(), ArrayUtil::appendSuffix($packageDirs, 'templates/'));
		$this->assignDefaultTemplateVariables();
		
		$this->initCronjobs();
		
		if (OFFLINE && !self::getUser()->getPermission('user.tracker.canViewTrackerOffline')) {
			$showOfflineError = true;
			foreach (self::$availablePagesDuringOfflineMode as $type => $names) {
				if (isset($_REQUEST[$type])) {
					foreach ($names as $name) {
						if ($_REQUEST[$type] == $name) {
							$showOfflineError = false;
							break 2;
						}
					}
					
					break;
				}
			}
			
			if ($showOfflineError) {
				self::getTPL()->display('offline');
				exit;
			}
		}
		
		//if (self::getUser()->banned && (!isset($_REQUEST['page']) || $_REQUEST['page'] != 'LegalNotice')) {
		//	throw new NamedUserException(WCF::getLanguage()->getDynamicVariable('wcf.user.banned'));
		//}
	}
	
	/**
	 * Initialises the cronjobs.
	 */
	protected function initCronjobs() {
		self::getTPL()->assign('executeCronjobs', WCF::getCache()->get('cronjobs-'.PACKAGE_ID, 'nextExec') < TIME_NOW);
	}
	
	/**
	 * @see WCF::loadDefaultCacheResources()
	 */
	protected function loadDefaultCacheResources() {
		parent::loadDefaultCacheResources();
		$this->loadDefaultITCacheResources();
	}
	
	/**
	 * Loads default cache resources of icy tracker.
	 * Can be called statically from other applications or plugins.
	 */
	public static function loadDefaultITCacheResources() {
		WCF::getCache()->addResource('bbcodes', WCF_DIR.'cache/cache.bbcodes.php', WCF_DIR.'lib/system/cache/CacheBuilderBBCodes.class.php');
		WCF::getCache()->addResource('smileys', WCF_DIR.'cache/cache.smileys.php', WCF_DIR.'lib/system/cache/CacheBuilderSmileys.class.php');
		WCF::getCache()->addResource('cronjobs-'.PACKAGE_ID, WCF_DIR.'cache/cache.cronjobs-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderCronjobs.class.php');
		WCF::getCache()->addResource('help-'.PACKAGE_ID, WCF_DIR.'cache/cache.help-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderHelp.class.php');
	}
	
	/**
	 * Initialises the page header menu.
	 */
	protected static function initPageMenu() {
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		self::$pageMenuObj = new PageMenu();
		if (PageMenu::getActiveMenuItem() == '') PageMenu::setActiveMenuItem('it.header.menu.tracker');
	}
	
	/**
	 * Initialises the user cp menu.
	 */
	protected static function initUserCPMenu() {
		require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');
		self::$userCPMenuObj = UserCPMenu::getInstance();
	}
	
	/**
	 * Initialises the user profile menu.
	 */
	protected static function initUserProfileMenu() {
		require_once(WCF_DIR.'lib/page/util/menu/UserProfileMenu.class.php');
		self::$userProfileMenuObj = UserProfileMenu::getInstance();
	}
	
	/**
	 * @see WCF::getOptionsFilename()
	 */
	protected function getOptionsFilename() {
		return IT_DIR.'options.inc.php';
	}
	
	/**
	 * Initialises the style system.
	 */
	protected function initStyle() {
		//if (isset($_GET['styleID'])) {
		//	self::getSession()->setStyleID(intval($_GET['styleID']));
		//}
		//
		//StyleManager::changeStyle(self::getSession()->getStyleID());
	}
	
	/**
	 * @see PageMenuContainer::getPageMenu()
	 */
	public static final function getPageMenu() {
		if (self::$pageMenuObj === null) {
			self::initPageMenu();
		}
		
		return self::$pageMenuObj;
	}
	
	/**
	 * @see PageMenuContainer::getPageMenu()
	 * @deprecated
	 */
	public static final function getHeaderMenu() {
		return self::getPageMenu();
	}
	
	/**
	 * @see UserCPMenuContainer::getUserCPMenu()
	 */
	public static final function getUserCPMenu() {
		if (self::$userCPMenuObj === null) {
			self::initUserCPMenu();
		}
		
		return self::$userCPMenuObj;
	}
	
	/**
	 * @see UserProfileMenuContainer::getUserProfileMenu()
	 */
	public static final function getUserProfileMenu() {
		if (self::$userProfileMenuObj === null) {
			self::initUserProfileMenu();
		}
		
		return self::$userProfileMenuObj;
	}
	
	/**
	 * Returns the active style object.
	 * 
	 * @return	ActiveStyle
	 */
	public static final function getStyle() {
		return StyleManager::getStyle();
	}
	
	/**
	 * @see WCF::initSession()
	 */
	protected function initSession() {
		// start session
		//require_once(IT_DIR.'lib/system/session/ITSessionFactory.class.php');
		//$factory = new ITSessionFactory();
		require_once(WCF_DIR.'lib/system/session/CookieSessionFactory.class.php');
		$factory = new CookieSessionFactory();
		self::$sessionObj = $factory->get();
		self::$userObj = self::getSession()->getUser();
	}
	
	/**
	 * @see	WCF::assignDefaultTemplateVariables()
	 */
	protected function assignDefaultTemplateVariables() {
		parent::assignDefaultTemplateVariables();
		self::getTPL()->registerPrefilter('icon');
		self::getTPL()->assign(array(
			'timezone' => DateUtil::getTimezone(),
		));
	}
}
?>