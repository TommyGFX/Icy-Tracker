<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');

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
		
		$this->removeShowOrder();
	}
	
	/**
	 * Deletes the projects with the given IDs.
	 * 
	 * @param	string	$projectIDs
	 */
	public static function deleteData($projectIDs) {
		$projectIDs = implode(',', ArrayUtil::toIntegerArray(explode(',', $projectIDs)));
		
		// delete developers
		$sql = "DELETE FROM ict".ICT_N."_project_developer
			WHERE projectID IN(".$projectIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		// TODO: implement issue & version cleanup -- RouL
		
		// delete projects
		$sql = "DELETE FROM ict".ICT_N."_project
			WHERE projectID IN(".$projectIDs.")";
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Removes a projects showOrder from project order.
	 * 
	 * @todo	put this into ProjectEditor::deleteData()
	 */
	public function removeShowOrder() {
		// unshift projects
		$sql = "UPDATE	ict".ICT_N."_project
			SET	showOrder = showOrder - 1
			WHERE 	showOrder > ".$this->showOrder;
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
				WHERE	showOrder >= ".$showOrder;
			WCF::getDB()->sendQuery($sql);
		}
		
		// get final showOrder
		$sql = "SELECT 	IFNULL(MAX(showOrder), 0) + 1 AS showOrder
			FROM	ict".ICT_N."_project
			".($showOrder ? "WHERE showOrder <= ".$showOrder : '');
		$row = WCF::getDB()->getFirstRow($sql);
		$showOrder = $row['showOrder'];
		
		// save showOrder
		$sql = "UPDATE	ict".ICT_N."_project
			SET		showOrder = ".$showOrder."
			WHERE	projectID = ".$this->projectID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Adds developers to this project.
	 * 
	 * @param	array	$userIDs
	 */
	public function addDevelopers($userIDs) {
		if (count($userIDs)) {
			$userIDs = ArrayUtil::toIntegerArray($userIDs);
			
			$sql = "INSERT INTO	ict".ICT_N."_project_developer (
					projectID,
					userID
				) VALUES (".$this->projectID.", ".implode("), (".$this->projectID.", ", $userIDs).")";
			WCF::getDB()->sendQuery($sql);
		}
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