<?php
// it imports
require_once(IT_DIR.'lib/data/project/Project.class.php');

/**
 * ProjectEditor provides functions to edit the data of a project.
 * 
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	lib.data
 * @category 	Icy Tracker
 * @version		$Id$
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
				FROM	it".IT_N."_project
				WHERE	projectID = ".$projectID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}
	
	/**
	 * Deletes this project.
	 */
	public function delete() {
		// empty project
		// TODO: implement ticket & version cleanup
		
		// delete project
		$sql = "DELETE FROM	it".IT_N."_project
			WHERE		projectID = ".$this->projectID;
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
	public function update($title = null, $description = null, $image = null, $ownerID = null, $showOrder = null, $additionalFields = array()) {
		$fields = array();
		if ($title !== null) $fields['title'] = $title;
		if ($description !== null) $fields['description'] = $description;
		if ($image !== null) $fields['image'] = $image;
		if ($ownerID !== null) $fields['ownerID'] = $ownerID;
		if ($showOrder !== null) $fields['showOrder'] = $showOrder;
		
		$this->updateData(array_merge($fields, $additionalFields));
	}
	
	/**
	 * Updates the data of a project.
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
			$sql = "UPDATE	it".IT_N."_project
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
		$projectID = self::insert($title, $description, $image, $ownerID, $showOrder, $additionalFields);
		
		// get project
		$project = new ProjectEditor($projectID, null, null, false);
		
		// return new project
		return $project;
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
	public static function insert($title, $description, $image, $ownerID, $showOrder, $additionalFields = array()) { 
		$keys = $values = '';
		foreach ($additionalFields as $key => $value) {
			$keys .= ','.$key;
			if (is_int($value)) $values .= ",".$value;
			else $values .= ",'".escapeString($value)."'";
		}
		
		$sql = "INSERT INTO	it".IT_N."_project
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
				".intval($showOrder)."
				".$values."
			)";
		WCF::getDB()->sendQuery($sql);
		return WCF::getDB()->getInsertID();
	}
}
?>