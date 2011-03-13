<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Outputs an XML document with a list of access entities
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	acp.page
 * @category 	Icy Tracker
 * 
  * @todo		add group support, create a separate package for this stuff --RouL
*/
class AccessEntitiesPage extends AbstractPage {
	public $query;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['q'])) {
			$query = $_REQUEST['q'];
			if (CHARSET != 'UTF-8') {
				$query = StringUtil::convertEncoding('UTF-8', CHARSET, $query);
			}
			
			$this->query = ArrayUtil::trim(explode(',', $query));
		}
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
				
		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="'.CHARSET.'"?>'."\n";
		echo '<entities>';
		
		if (count($this->query)) {
			$names = implode("','", array_map('escapeString', $this->query));
			$sql = "SELECT		username AS entityName, userID AS entityID
				FROM		wcf".WCF_N."_user
				WHERE		username IN ('".$names."')
				ORDER BY	entityName";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				echo '<entity type="user" id="'.$row['entityID'].'">';
				echo '<![CDATA['.StringUtil::escapeCDATA($row['entityName']).']]>';
				echo '</entity>';
			}
		}
		echo '</entities>';
		exit;
	}
}
?>