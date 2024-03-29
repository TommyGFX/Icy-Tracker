-- Projects
DROP TABLE IF EXISTS ict1_1_project;
CREATE TABLE ict1_1_project (
  projectID int(10) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  description mediumtext NOT NULL,
  image varchar(255) NOT NULL,
  ownerID int(10) unsigned NOT NULL,
  showOrder int(10) unsigned NOT NULL,
  PRIMARY KEY (projectID),
  UNIQUE KEY title (title),
  KEY showOrder (showOrder)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- Project versions
DROP TABLE IF EXISTS ict1_1_project_version;
CREATE TABLE ict1_1_project_version (
  versionID int(10) unsigned NOT NULL AUTO_INCREMENT,
  projectID int(10) unsigned NOT NULL,
  version varchar(255) NOT NULL,
  published tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (versionID),
  UNIQUE KEY version (projectID,version),
  KEY projectID (projectID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Project developers
DROP TABLE IF EXISTS ict1_1_project_developer;
CREATE TABLE ict1_1_project_developer (
  projectID int(10) NOT NULL,
  entityID int(10) NOT NULL,
  entityType varchar(255) NOT NULL,
  canConfirmIssue tinyint(1) NOT NULL DEFAULT -1,
  canSetPriority tinyint(1) NOT NULL DEFAULT -1,
  canAssignIssue tinyint(1) NOT NULL DEFAULT -1,
  canAddRelation tinyint(1) NOT NULL DEFAULT -1,
  canEditIssue tinyint(1) NOT NULL DEFAULT -1,
  canHideIssue tinyint(1) NOT NULL DEFAULT -1,
  canViewHiddenIssue tinyint(1) NOT NULL DEFAULT -1,
  canCloseIssue tinyint(1) NOT NULL DEFAULT -1,
  canEditComment tinyint(1) NOT NULL DEFAULT -1,
  canDeleteComment tinyint(1) NOT NULL DEFAULT -1,
  PRIMARY KEY (projectID,entityID,entityType)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Project users
DROP TABLE IF EXISTS ict1_1_project_access;
CREATE TABLE ict1_1_project_access (
  projectID int(10) NOT NULL,
  entityID int(10) NOT NULL,
  entityType varchar(255) NOT NULL,
  canViewProject tinyint(1) NOT NULL DEFAULT -1,
  canViewIssues tinyint(1) NOT NULL DEFAULT -1,
  canCreateBug tinyint(1) NOT NULL DEFAULT -1,
  canCreateFeatureRequest tinyint(1) NOT NULL DEFAULT -1,
  canCreateTask tinyint(1) NOT NULL DEFAULT -1,
  canUploadAttachment tinyint(1) NOT NULL DEFAULT -1,
  canReopenOwnIssue tinyint(1) NOT NULL DEFAULT -1,
  canReopenIssue tinyint(1) NOT NULL DEFAULT -1,
  canAddComment tinyint(1) NOT NULL DEFAULT -1,
  canEditOwnComment tinyint(1) NOT NULL DEFAULT -1,
  canDeleteOwnComment tinyint(1) NOT NULL DEFAULT -1,
  PRIMARY KEY (projectID,entityID,entityType)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Issues
DROP TABLE IF EXISTS ict1_1_issue;
CREATE TABLE ict1_1_issue (
  issueID int(10) unsigned NOT NULL AUTO_INCREMENT,
  projectID int(10) unsigned NOT NULL,
  username varchar(255) NOT NULL,
  userID int(10) unsigned NOT NULL,
  agent varchar(255) NOT NULL DEFAULT '',
  agentID int(10) NOT NULL DEFAULT 0,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT 1,
  priority tinyint(1) unsigned NOT NULL,
  solution tinyint(1) unsigned DEFAULT NULL,
  closed tinyint(1) unsigned NOT NULL DEFAULT 0,
  solvedVersionID int(10) unsigned NOT NULL DEFAULT 0,
  hidden tinyint(1) NOT NULL DEFAULT 0,
  `subject` varchar(255) NOT NULL,
  message mediumtext NOT NULL,
  `time` int(10) NOT NULL,
  editor varchar(255) NOT NULL DEFAULT '',
  editorID int(10) unsigned NOT NULL DEFAULT 0,
  lastEditTime int(10) NOT NULL DEFAULT 0,
  attachments smallint(5) NOT NULL DEFAULT 0,
  enableSmilies tinyint(1) NOT NULL DEFAULT 1,
  enableHtml tinyint(1) NOT NULL DEFAULT 0,
  enableBBCodes tinyint(1) NOT NULL DEFAULT 1,
  ipAddress varchar(39) NOT NULL,
  PRIMARY KEY (issueID),
  KEY projectID (projectID),
  KEY closed (projectID,closed),
  KEY solvedVersionID (solvedVersionID),
  FULLTEXT KEY `subject` (`subject`,message)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Issues affected versions
DROP TABLE IF EXISTS ict1_1_issue_version;
CREATE TABLE ict1_1_issue_version (
  issueID int(10) unsigned NOT NULL,
  versionID int(10) unsigned NOT NULL,
  PRIMARY KEY (issueID,versionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Issues relations
DROP TABLE IF EXISTS ict1_1_issue_relation;
CREATE TABLE ict1_1_issue_relation (
  parentID int(10) unsigned NOT NULL,
  relation tinyint(1) unsigned NOT NULL,
  childID int(10) unsigned NOT NULL,
  PRIMARY KEY (parentID,childID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Issues comments
DROP TABLE IF EXISTS ict1_1_issue_comment;
CREATE TABLE ict1_1_issue_comment (
  commentID int(10) unsigned NOT NULL AUTO_INCREMENT,
  issueID int(10) unsigned NOT NULL,
  userID int(10) unsigned NOT NULL,
  hidden tinyint(1) NOT NULL,
  username varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  message mediumtext NOT NULL,
  `time` int(10) NOT NULL,
  attachments smallint(5) NOT NULL,
  enableSmilies tinyint(1) NOT NULL,
  enableHtml tinyint(1) NOT NULL,
  enableBBCodes tinyint(1) NOT NULL,
  ipAddress varchar(39) NOT NULL,
  PRIMARY KEY (commentID),
  KEY issueID (issueID),
  FULLTEXT KEY `subject` (`subject`,message)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
