<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Version.class.php');

/**
 * VersionEditor provides functions to edit the data of a project version.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.it
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 */
class VersionEditor extends Version {
	/**
	 * Creates a new VersionEditor object.
	 * @see Version::__construct()
	 */
	public function __construct($versionID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($versionID, $row, $cacheObject);
		else {
			$sql = "SELECT		version.*, COUNT(DISTINCT issue.issueID) AS solutions, COUNT(DISTINCT issue_version.issueID) AS relations
				FROM		ict".ICT_N."_project_version version
				LEFT JOIN	ict".ICT_N."_issue issue
				ON			(issue.solvedVersionID = version.versionID)
				LEFT JOIN	ict".ICT_N."_issue_version issue_version
				ON			(issue_version.versionID = version.versionID)
				WHERE		version.versionID = ".$versionID."
				GROUP BY	version.versionID";
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}
	
	/**
	 * Deletes this version.
	 * 
	 * Don't delete versions that have relations or solutions!
	 */
	public function delete() {
		self::deleteData($this->versionID);
	}
	
	/**
	 * Deletes the versions with the given IDs.
	 * 
	 * @param	string	$versionIDs
	 */
	public static function deleteData($versionIDs) {
		$versionIDs = implode(',', ArrayUtil::toIntegerArray(explode(',', $versionIDs)));
		
		// delete versions
		$sql = "DELETE FROM	ict".ICT_N."_project_version
			WHERE versionID IN(".$versionIDs.")";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Updates this version.
	 * 
	 * @param	string	$version
	 * @param	integer	$published
	 * @param	array	$additionalFields
	 */
	public function update($version = null, $published = null, $additionalFields = array()) {
		$fields = array();
		if ($version !== null) $fields['version'] = $version;
		if ($published !== null) $fields['published'] = $published;
		
		$this->updateData(array_merge($fields, $additionalFields));
	}
	
	/**
	 * Updates the data of this version.
	 *
	 * @param	array	$fields
	 */
	public function updateData($fields = array()) { 
		$updates = '';
		foreach ($fields as $key => $value) {
			if (!empty($updates)) $updates .= ',';
			$updates .= $key.'=';
			if (is_int($value)) $updates .= $value;
			else $updates .= "'".escapeString($value)."'";
		}
		
		if (!empty($updates)) {
			$sql = "UPDATE	ict".ICT_N."_project_version
				SET	".$updates."
				WHERE	versionID = ".$this->versionID;
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Creates a new version.
	 * 
	 * @param	integer	$projectID
	 * @param	string	$version
	 * @param	integer	$published
	 * @param	array	$additionalFields
	 * 
	 * @return	ProjectEditor
	 */
	public static function create($projectID, $version, $additionalFields = array()) {
		// save data
		$versionID = self::insert($projectID, $version, $additionalFields);
		
		// get version
		$version = new VersionEditor($versionID, null, null, false);
		
		// return new project
		return $version;
	}
	
	/**
	 * Creates the version row in database table.
	 *
	 * @param	integer	$projectID
	 * @param	string	$version
	 * @param	integer	$published
	 * @param	array	$additionalFields
	 * 
	 * @return	integer
	 */
	public static function insert($projectID, $version, $additionalFields = array()) { 
		$keys = $values = '';
		foreach ($additionalFields as $key => $value) {
			$keys .= ','.$key;
			if (is_int($value)) $values .= ",".$value;
			else $values .= ",'".escapeString($value)."'";
		}
		
		$sql = "INSERT INTO	ict".ICT_N."_project_version
			(
				projectID,
				version
				".$keys."
			)
			VALUES
			(
				'".intval($projectID)."',
				'".escapeString($version)."'
				".$values."
			)";
		WCF::getDB()->sendQuery($sql);
		
		return WCF::getDB()->getInsertID();
	}
	
	/**
	 * Publishes this version.
	 */
	public function publish($additionalFields = array()) {
		$additionalFields['published'] = 1;
		$this->updateData($additionalFields);
	}
	
	/**
	 * Un-publishes this version.
	 */
	public function unpublish($additionalFields = array()) {
		$additionalFields['published'] = 0;
		$this->updateData($additionalFields);
	}
}
?>