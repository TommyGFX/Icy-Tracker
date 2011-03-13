<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a project version in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 */
class Version extends DatabaseObject {
	protected static $versions = null;
	
	/**
	 * Creates a new Version object.
	 *
	 * @param	integer	$versionID
	 * @param	array	$row
	 * @param	Version	$cacheObject
	 */
	public function __construct($versionID, $row = null, $cacheObject = null) {
		if ($versionID !== null) $cacheObject = self::getVersion($versionID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}

	/**
	 * Gets the version with the given version id from cache.
	 *
	 * @param	integer		$versionID
	 * 
	 * @return	Version
	 */
	public static function getVersion($versionID) {
		if (self::$versions === null) {
			self::$versions = WCF::getCache()->get('project', 'versions');
		}

		if (!isset(self::$versions[$versionID])) {
			throw new IllegalLinkException();
		}

		return self::$versions[$versionID];
	}
}
?>