<?php
// ict imports
require_once(ICT_DIR.'lib/data/project/Project.class.php');
require_once(ICT_DIR.'lib/data/issue/IssueEditor.class.php');

// wcf imports
require_once(WCF_DIR.'lib/form/MessageForm.class.php'); // TODO: later MessageForm
require_once(WCF_DIR.'lib/data/attachment/MessageAttachmentListEditor.class.php');

/**
 * Shows the issue add form.
 *
 * @author		Markus Bartz
 * @copyright	2011 Markus Bartz
 * @license		Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0) <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package		info.codingcorner.ict
 * @subpackage	form
 * @category 	Icy Tracker
 */
class IssueAddForm extends MessageForm {
	// system
	public $templateName = 'issueAdd';
	public $useCaptcha = 1; //TODO: put this into an option! --RouL
	public $showPoll = false;
	public $showSignatureSetting = false;
	
	/**
	 * project id
	 * 
	 * @var	integer
	 */
	public $projectID = 0;
	
	/**
	 * project object
	 * 
	 * @var	Project
	 */
	public $project = null;
	
	/**
	 * attachment list editor object
	 * 
	 * @var	MessageAttachmentListEditor
	 */
	public $attachmentListEditor = null;
	
	/**
	 * issue type
	 * 
	 * @var	string
	 */
	public $issueType = '';
	
	/**
	 * issue editor object
	 * 
	 * @var	IssueEditor
	 */
	public $issue = null;
	
	/**
	 * list of available versions
	 * 
	 * @var	array
	 */
	public $versionOptions = array();
	
	/**
	 * list of available developers
	 * 
	 * @var	array
	 */
	public $agentOptions = array();
	
	/**
	 * agent object
	 * 
	 * @var	User
	 */
	public $agent = null;
	
	// form parameters
	public $username = '';
	public $versionIDs = array();
	public $agentID = 0;
	public $status = Issue::STATUS_NEW;
	public $priority = ISSUE_DEFAULT_PRIORITY;
	public $solution = 0;
	public $hidden = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['issueType'])) $this->issueType = StringUtil::trim($_REQUEST['issueType']);
		if (array_search($this->issueType, Issue::$validTypes) === false) {
			throw new IllegalLinkException();
		}
		
		if (isset($_REQUEST['projectID'])) $this->projectID = intval($_REQUEST['projectID']);
		$this->project = new Project($this->projectID);
		$this->project->enterProject();
		
		$this->project->checkPermission('canCreate'.StringUtil::firstCharToUpperCase($this->issueType));
		
		$this->messageTable = 'ict'.ICT_N.'_issue';
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->username = WCF::getSession()->username;
		}
		
		// TODO: shall unpublished versions be shown here?! --RouL
		foreach ($this->project->getVersions() as $version) {
			$this->versionOptions[$version->versionID] = $version->version;
		}
		
		$this->agentOptions = $this->project->getDeveloper();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'projectID' => $this->projectID,
			'project' => $this->project,
			'issueType' => $this->issueType,
			'versionOptions' => $this->versionOptions,
			'username' => $this->username,
			'versionIDs' => $this->versionIDs,
			'agentID' => $this->agentID,
			'agentOptions' => $this->agentOptions,
			'status' => $this->status,
			'priority' => $this->priority,
			'solution' => $this->solution,
			'hidden' => $this->hidden,
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (MODULE_ATTACHMENT != 1 || !$this->project->getPermission('canUploadAttachment')) {
			$this->showAttachments = false;
		}
		
		$this->maxTextLength = WCF::getUser()->getPermission('user.tracker.maxTicketLength');
		
		if ($this->attachmentListEditor == null) {
			$this->attachmentListEditor = new MessageAttachmentListEditor(array(), 'ticket', PACKAGE_ID, WCF::getUser()->getPermission('user.tracker.maxAttachmentSize'), WCF::getUser()->getPermission('user.tracker.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.tracker.maxAttachmentCount'));
		}
		
		// show form
		parent::show();
	}
}

?>