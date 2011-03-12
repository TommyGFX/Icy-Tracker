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
		
		$sql = "INSERT INTO	wcr".WCR_N."_project
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
}
?>