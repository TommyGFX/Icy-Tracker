<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a project version in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 * @version		$Id$
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