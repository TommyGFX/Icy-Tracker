<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');
require_once(ICT_DIR.'lib/data/project/VersionEditor.class.php');
require_once(ICT_DIR.'lib/data/issue/IssueEditor.class.php');

/**
 * ProjectEditor provides functions to edit the data of a project.
 * 
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	lib.data.project
 * @category 	Icy Tracker
 */
class ProjectEditor extends Project {
	/**
	 * Creates a new ProjectEditor object.
	 * @see Project::__construct()
	 */
	public function __construct($projectID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($projectID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	ict".ICT_N."_project
				WHERE	projectID = ".$projectID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}
	
	/**
	 * Deletes this project.
	 */
	public function delete() {
		self::deleteData($this->projectID);
	}
	
	/**
	 * Deletes the projects with the given IDs.
	 * 
	 * @param	string	$projectIDs
	 */
	public static function deleteData($projectIDs) {
		$projectIDs = implode(',', ArrayUtil::toIntegerArray(explode(',', $projectIDs)));
		
		// delete issues
		$sql = "SELECT	issueID
			FROM	ict".ICT_N."_issue
			WHERE	projectID IN (".$projectIDs.")";
		$result = WCF::getDB()->sendQuery($sql);
		$issueIDs = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$issueIDs[] = $row['issueID'];
		}
		if (count($issueIDs)) {
			IssueEditor::deleteData(implode(',', $issueIDs));
		}
		
		// delete versions
		$sql = "SELECT	versionID
			FROM	ict".ICT_N."_project_version
			WHERE	projectID IN (".$projectIDs.")";
		$result = WCF::getDB()->sendQuery($sql);
		$versionIDs = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$versionIDs[] = $row['versionID'];
		}
		if (count($versionIDs)) {
			VersionEditor::deleteData(implode(',', $versionIDs));
		}
		
		// delete developers
		$sql = "DELETE FROM ict".ICT_N."_project_developer
			WHERE projectID IN(".$projectIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		// delete users
		$sql = "DELETE FROM ict".ICT_N."_project_access
			WHERE projectID IN(".$projectIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		// delete projects
		$sql = "DELETE FROM ict".ICT_N."_project
			WHERE projectID IN(".$projectIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		// cleanup showorder
		$sql = "SELECT		projectID
			FROM		ict".ICT_N."_project
			ORDER BY	showOrder";
		$result = WCF::getDB()->sendQuery($sql);
		$i = 0;
		while ($row = WCF::getDB()->fetchArray($result)) {
			$i++;
			$sql = "UPDATE	ict".ICT_N."_project
				SET		showOrder = ".$i."
				WHERE	projectID = ".$row['projectID'];
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Removes a projects showOrder from project order.
	 */
	public function removeShowOrder() {
		// unshift projects
		$sql = "UPDATE	ict".ICT_N."_project
			SET		showOrder = showOrder - 1
			WHERE	showOrder > ".$this->showOrder;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Updates this project.
	 * 
	 * @param	string	$title
	 * @param	string	$description
	 * @param	string	$image
	 * @param	integer	$ownerID
	 * @param	integer	showOrder
	 * @param	array	$additionalFields
	 */
	public function update($title = null, $description = null, $image = null, $ownerID = null, $additionalFields = array()) {
		$fields = array();
		if ($title !== null) $fields['title'] = $title;
		if ($description !== null) $fields['description'] = $description;
		if ($image !== null) $fields['image'] = $image;
		if ($ownerID !== null) $fields['ownerID'] = $ownerID;
		
		$this->updateData(array_merge($fields, $additionalFields));
	}
	
	/**
	 * Updates the data of this project.
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
			$sql = "UPDATE	ict".ICT_N."_project
				SET	".$updates."
				WHERE	projectID = ".$this->projectID;
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Creates a new project.
	 * 
	 * @param	string	$title
	 * @param	string	$description
	 * @param	string	$image
	 * @param	integer	$ownerID
	 * @param	integer	showOrder
	 * @param	array	$additionalFields
	 * 
	 * @return	ProjectEditor
	 */
	public static function create($title, $description, $image, $ownerID, $showOrder, $additionalFields = array()) {
		// save data
		$projectID = self::insert($title, $description, $image, $ownerID, $additionalFields);
		
		// get project
		$project = new ProjectEditor($projectID, null, null, false);
		
		// add showOrder
		$project->addShowOrder($showOrder);
		
		// return new project
		return $project;
	}
	
	/**
	 * Adds a project to a specific position in the project order.
	 * 
	 * @param	integer	$showOrder
	 */
	public function addShowOrder($showOrder = null) {
		// shift projects
		if ($showOrder !== null) {
			$sql = "UPDATE	ict".ICT_N."_project
				SET		showOrder = showOrder + 1
				WHERE	showOrder >= ".intval($showOrder);
			WCF::getDB()->sendQuery($sql);
		}
		
		// get final showOrder
		$sql = "SELECT 	IFNULL(MAX(showOrder), 0) + 1 AS showOrder
			FROM	ict".ICT_N."_project
			".($showOrder !== null ? "WHERE showOrder <= ".intval($showOrder) : '');
		$row = WCF::getDB()->getFirstRow($sql);
		$showOrder = $row['showOrder'];
		
		// save showOrder
		$sql = "UPDATE	ict".ICT_N."_project
			SET		showOrder = ".$showOrder."
			WHERE	projectID = ".$this->projectID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Adds entities of a given set to this project.
	 * 
	 * possible values for $set: access, developer
	 * 
	 * @param	string	$set
	 * @param	array	$entities
	 * @param	array	$settings
	 * 
	 * @throws	SystemException
	 */
	public function addEntities($set, $entities, $settings) {
		if ($set != 'access' && $set != 'developer') {
			throw new SystemException('Unknown entity-set "'.$set.'"');
		}
		
		$rows = array();
		foreach ($entities as $entity) {
			$rowSettings = array();
			foreach ($settings as $setting) {
				$rowSettings[] = intval($entity['settings'][$setting]);
			}
			$rows[] = "(".$this->projectID.", ".intval($entity['id']).", '".escapeString($entity['type'])."', ".implode(', ', $rowSettings).")";
		}
		
		$sql = "INSERT INTO	ict".ICT_N."_project_".$set." (
				projectID,
				entityID,
				entityType,
				".implode(', ', $settings)."
			) VALUES ".implode(', ', $rows);
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes all developers for this project.
	 */
	public function clearDevelopers() {
		$sql = "DELETE FROM	ict".ICT_N."_project_developer
			WHERE		projectID = ".$this->projectID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes all access entities for this project.
	 */
	public function clearAccess() {
		$sql = "DELETE FROM	ict".ICT_N."_project_access
			WHERE		projectID = ".$this->projectID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Creates the project row in database table.
	 *
	 * @param	string	$title
	 * @param	string	$description
	 * @param	string	$image
	 * @param	integer	$ownerID
	 * @param	integer	showOrder
	 * @param	array	$additionalFields
	 * 
	 * @return	integer
	 */
	public static function insert($title, $description, $image, $ownerID, $additionalFields = array()) { 
		$keys = $values = '';
		foreach ($additionalFields as $key => $value) {
			$keys .= ','.$key;
			if (is_int($value)) $values .= ",".$value;
			else $values .= ",'".escapeString($value)."'";
		}
		
		$sql = "INSERT INTO	ict".ICT_N."_project
			(
				title,
				description,
				image,
				ownerID,
				showOrder
				".$keys."
			)
			VALUES
			(
				'".escapeString($title)."',
				'".escapeString($description)."',
				'".escapeString($image)."',
				".intval($ownerID).",
				0
				".$values."
			)";
		WCF::getDB()->sendQuery($sql);
		
		return WCF::getDB()->getInsertID();
	}
}
?>