<?php
// ict imports
require_once(ICT_DIR.'lib/data/user/ICTUserSession.class.php');
require_once(ICT_DIR.'lib/data/user/ICTGuestSession.class.php');

// wcf imports
require_once(WCF_DIR.'lib/system/session/CookieSession.class.php');

/**
 * ICTSession extends the CookiSession with tracker specific functions
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	system.session
 * @category	Icy Tracker
 */
class ICTSession extends CookieSession {
	protected $userSessionClassName = 'ICTUserSession';
	protected $guestSessionClassName = 'ICTGuestSession';
}
?>