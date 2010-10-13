<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
//require_once(IT_DIR.'lib/data/project/Version.class.php');

/**
 * Represents an issue in the tracker.
 *
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @package		info.codingcorner.it
 * @subpackage	lib.data.issue
 * @category 	Icy Tracker
 * @version		$Id$
 * 
 * @todo		implement all needed methods -- RouL
 */
class Issue extends DatabaseObject {
	// issue type constants
	const TYPE_BUG = 1;
	const TYPE_FEATURE_REQUEST = 2;
	const TYPE_TASK = 3;
	
	// issue status constants
	const STATUS_NEW = 1;
	const STATUS_CONFIRMED = 2;
	const STATUS_ACTIVE = 3;
	const STATUS_SOLVED = 4;
	const STATUS_CLOSED = 5; // TODO: better put this in an extra fied in the table. -- RouL
	
	// issue solution constants
	const SOLUTION_SOLVED = 1;
	const SOLUTION_DEFERED = 2;
	const SOLUTION_WONT_SOLVE = 3;
	const SOLUTION_NOT_REPRODUCIBLE = 4;
	const SOLUTION_NOT_A_BUG = 5;
	const SOLUTION_DUPLICATE = 6; // TODO: add relation table. -- RouL
	
	// issue priority constants
	const PRIORITY_CRITICAL = 1;
	const PRIORITY_HIGH = 2;
	const PRIORITY_NORMAL = 3;
	const PRIORITY_LOW = 4;
	const PRIORITY_NO_PRIORITY = 5;
	
	// issue ralation constants
	const RELATION_REQUIREMENT = 1;
	const RELATION_DUPLICATE = 2;
	
	/**
	 * Creates a new Issue object.
	 *
	 * @param	integer	$issueID
	 * @param	array	$row
	 */
	public function __construct($issueID, $row = null) {
		if ($issueID !== null) {
			// TODO: extend this query to get all needed information. -- RouL
			$sql = "SELECT	*
				FROM	it".IT_N."_issue
				WHERE	issueID = ".$issueID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
}
?>