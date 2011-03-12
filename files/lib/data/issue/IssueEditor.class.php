<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Editor class for issues.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.it
 * @subpackage	lib.data.issue
 * @category 	Icy Tracker
 * 
 * @todo		implement all needed methods -- RouL
 */
class IssueEditor extends Issue {
	/**
	 * Creates a new issue.
	 * 
	 * @param	integer						$projectID
	 * @param	integer						$userID
	 * @param	string						$username
	 * @param	integer						$type
	 * @param	string						$subject
	 * @param	string						$message
	 * @param	boolean						$enableSmilies
	 * @param	boolean						$enableHtml
	 * @param	boolean						$enableBBCodes
	 * @param	MessageAttachmentListEditor	$attachments
	 * @param	boolean						$hidden
	 * @param	integer						$status
	 * @param	integer						$priority
	 * @param	string						$agent
	 * @param	integer						$agentID
	 * @param	string						$ipAddress
	 * @param	array						$additionalFields
	 * 
	 * @return	IssueEditor
	 */
	public static function create($projectID, $userID, $username, $type, $subject, $message, $enableSmilies = null, $enableHtml = null, $enableBBCodes = null, $attachments = null, $hidden = null, $status = null, $priority = null, $agent = null, $agentID = null, $ipAddress = null, $additionalFields = array()) {
		// get ip from session
		if ($ipAddress == null) $ipAddress = WCF::getSession()->ipAddress;
		
		// extend additionalFields by some optional fields
		if ($status != null) $additionalFields['status'] = intval($status);
		if ($priority != null) $additionalFields['priority'] = intval($priority);
		if ($agent != null) $additionalFields['agent'] = escapeString($agent);
		if ($agentID != null) $additionalFields['agentID'] = intval($agentID);
		if ($hidden != null) $additionalFields['hidden'] = intval($hidden);
		
		if ($enableSmilies != null) $additionalFields['enableSmilies'] = intval($enableSmilies);
		if ($enableHtml != null) $additionalFields['enableHtml'] = intval($enableHtml);
		if ($enableBBCodes != null) $additionalFields['enableBBCodes'] = intval($enableBBCodes);
		if ($attachments != null) $additionalFields['attachments'] = count($attachments->getAttachments());
		
		// save data
		$issueID = self::insert($projectID, $userID, $username, $type, $priority, $subject, $message, TIME_NOW, $ipAddress, $additionalFields);
		
		// get project
		$issue = new IssueEditor($projectID);
		
		// update attachments
		if ($attachments != null) {
			$attachments->updateContainerID($projectID);
			$attachments->findEmbeddedAttachments($message);
		}
		
		// return new project
		return $project;
	}
	
	/**
	 * Inserts an issue row in the database table.
	 * 
	 * @param	integer	$projectID
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	integer	$type
	 * @param	integer	$priority
	 * @param	string	$subject
	 * @param	string	$message
	 * @param	integer	$time
	 * @param	integer	$ipAddress
	 * @param	array	$additionalFields
	 * 
	 * @return	integer
	 */
	public static function insert($projectID, $userID, $username, $type, $priority, $subject, $message, $time, $ipAddress, $additionalFields = array()) { 
		$keys = $values = '';
		foreach ($additionalFields as $key => $value) {
			$keys .= ','.$key;
			if (is_int($value)) $values .= ",".$value;
			else $values .= ",'".escapeString($value)."'";
		}
		
		$sql = "INSERT INTO	it".IT_N."_issue
			(
				projectID,
				userID,
				username,
				type,
				priority,
				subject,
				message,
				time,
				ipAddress
				".$keys."
			)
			VALUES
			(
				".intval($projectID).",
				".intval($userID).",
				'".escapeString($username)."',
				".intval($type).",
				".intval($priority).",
				'".escapeString($subject)."',
				'".escapeString($message)."',
				".intval($time).",
				'".escapeString($ipAddress)."'
				".$values."
			)";
		WCF::getDB()->sendQuery($sql);
		
		return WCF::getDB()->getInsertID();
	}
	
	/**
	 * Updates the data of this issue.
	 *
	 * @param	array	$fields
	 */
	public function updateData($fields) { 
		$updates = '';
		foreach ($fields as $key => $value) {
			if (!empty($updates)) $updates .= ',';
			$updates .= $key.'=';
			if (is_int($value)) $updates .= $value;
			else $updates .= "'".escapeString($value)."'";
		}
		
		if (!empty($updates)) {
			$sql = "UPDATE	it".IT_N."_issue
				SET	".$updates."
				WHERE	issueID = ".$this->issueID;
			WCF::getDB()->sendQuery($sql);
		}
	}
	
	/**
	 * Updates this issue
	 * 
	 * @param	string						$editor
	 * @param	integer						$editorID
	 * @param	string						$subject
	 * @param	string						$message
	 * @param	boolean						$enableSmilies
	 * @param	boolean						$enableHtml
	 * @param	boolean						$enableBBCodes
	 * @param	MessageAttachmentListEditor	$attachments
	 * @param	integer						$status
	 * @param	integer						$priority
	 * @param	integer						$solution
	 * @param	boolean						$hidden
	 * @param	array						$additionalFields
	 */
	public function update($editor, $editorID, $subject = null, $message = null, $enableSmilies = null, $enableHtml = null, $enableBBCodes = null, $attachments = null, $status = null, $priority = null, $solution = null, $hidden = null, $additionalFields = array()) {
		$additionalFields['editor'] = $editor;
		$additionalFields['editorID'] = intval($editorID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		
		if ($subject != null) $additionalFields['editor'] = $subject;
		if ($message != null) $additionalFields['editor'] = $message;
		if ($status != null) $additionalFields['status'] = intval($status);
		if ($priority != null) $additionalFields['priority'] = intval($priority);
		if ($solution != null) $additionalFields['solution'] = intval($solution);
		if ($hidden != null) $additionalFields['hidden'] = intval($hidden);
		
		if ($enableSmilies != null) $additionalFields['enableSmilies'] = intval($enableSmilies);
		if ($enableHtml != null) $additionalFields['enableHtml'] = intval($enableHtml);
		if ($enableBBCodes != null) $additionalFields['enableBBCodes'] = intval($enableBBCodes);
		if ($attachments != null) $additionalFields['attachments'] = count($attachments->getAttachments());
		
		// save data
		$this->updateData($additionalFields);
		
		// update attachments
		if ($attachments != null) {
			if ($message != null) {
				$attachments->findEmbeddedAttachments($message);
			}
		}
	}
	
	/**
	 * Moves this issue to a new project.
	 * 
	 * @param	integer	$projectID
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function moveToProject($projectID, $userID, $username, $additionalFields = array()) {
		$additionalFields['projectID'] = intval($projectID);
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($additionalFields);
	}
	
	/**
	 * Closes this issue.
	 * 
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function close($userID, $username, $additionalFields = array()) {
		$additionalFields['closed'] = 1;
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($additionalFields);
	}
	
	/**
	 * Opens this issue.
	 * 
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function open($userID, $username, $additionalFields = array()) {
		$additionalFields['closed'] = 0;
		$additionalFields['status'] = Issue::STATUS_REOPENED;
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($fields);
	}
	
	/**
	 * Updates the priority of this issue.
	 * 
	 * @param	integer	$priority
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function updatePriority($priority, $userID, $username, $additionalFields = array()) {
		$additionalFields['priority'] = intval($priority);
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($fields);
	}
	
	/**
	 * Updates the status of this issue. Updates also solution and closed-status if given.
	 * 
	 * @param	integer	$status
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	integer	$solution
	 * @param	boolean	$closed
	 * @param	array	$additionalFields
	 */
	public function updateStatus($status, $userID, $username, $solution = null, $closed = null, $additionalFields = array()) {
		$additionalFields['status'] = intval($status);
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		if ($solution != null) $fields['solution'] = intval($solution);
		if ($closed != null) $fields['closed'] = intval($closed);
		$this->updateData($fields);
	}
	
	/**
	 * Update the solution of this issue.
	 * 
	 * @param	integer	$solution
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function updateSolution($solution, $userID, $username, $additionalFields = array()) {
		$additionalFields['solution'] = intval($status);
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($fields);
	}
	
	/**
	 * Hides this issue.
	 * 
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function hide($userID, $username, $additionalFields = array()) {
		$additionalFields['hidden'] = 1;
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($fields);
	}
	
	/**
	 * Un-hide this issue.
	 * 
	 * @param	integer	$userID
	 * @param	string	$username
	 * @param	array	$additionalFields
	 */
	public function unhide($userID, $username, $additionalFields = array()) {
		$additionalFields['hidden'] = 0;
		$additionalFields['editor'] = $username;
		$additionalFields['editorID'] = intval($userID);
		$additionalFields['lastEditTime'] = TIME_NOW;
		$this->updateData($fields);
	}
	
	/**
	 * Deletes this issue.
	 */
	public function delete() {
		self::deleteData($this->issueID);
	}
	
	/**
	 * Deletes the issues with the given IDs.
	 * 
	 * @param	string	$issueIDs
	 */
	public static function deleteData($issueIDs) {
		$issueIDs = implode(',', ArrayUtil::toIntegerArray(explode(',', $issueIDs)));
		
		// delete issue relations
		$sql = "DELETE FROM it".IT_N."_issue_relation
			WHERE parentID IN(".$issueIDs.")
			OR childID IN(".$issueIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		// delete version relations
		$sql = "DELETE FROM it".IT_N."_project_dependency 
			WHERE issueID IN(".$issueIDs.")";
		WCF::getDB()->sendQuery($sql);
		
		//TODO: delete comments --RouL
		
		// delete issues
		$sql = "DELETE FROM it".IT_N."_issue 
			WHERE issueID IN(".$issueIDs.")";
		WCF::getDB()->sendQuery($sql);
	}
}

?>