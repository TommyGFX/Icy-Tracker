-- SVN-ID: $Id$

-- Projects
DROP TABLE IF EXISTS it1_1_project;
CREATE TABLE it1_1_project (
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
DROP TABLE IF EXISTS it1_1_project_version;
CREATE TABLE it1_1_project_version (
  versionID int(10) unsigned NOT NULL AUTO_INCREMENT,
  projectID int(10) unsigned NOT NULL,
  version varchar(255) NOT NULL,
  published tinyint(1) NOT NULL,
  PRIMARY KEY (versionID),
  UNIQUE KEY version (projectID,version),
  KEY projectID (projectID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Tickets
DROP TABLE IF EXISTS it1_1_ticket;
CREATE TABLE it1_1_ticket (
  ticketID int(10) unsigned NOT NULL AUTO_INCREMENT,
  projectID int(10) unsigned NOT NULL,
  userID int(10) unsigned NOT NULL,
  agentID int(10) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  priority tinyint(1) unsigned NOT NULL,
  solution tinyint(1) unsigned NOT NULL,
  solvedVersionID int(10) unsigned NOT NULL,
  hidden tinyint(1) NOT NULL,
  username varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  message mediumtext NOT NULL,
  `time` int(10) NOT NULL,
  editor varchar(255) NOT NULL,
  editorID int(10) unsigned NOT NULL,
  lastEditTime int(10) NOT NULL,
  attachments smallint(5) NOT NULL,
  enableSmilies tinyint(1) NOT NULL,
  enableHtml tinyint(1) NOT NULL,
  enableBBCodes tinyint(1) NOT NULL,
  ipAddress varchar(15) NOT NULL,
  PRIMARY KEY (ticketID),
  KEY projectID (projectID),
  FULLTEXT KEY `subject` (`subject`,message)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Ticket affected versions
DROP TABLE IF EXISTS it1_1_ticket_version;
CREATE TABLE it1_1_ticket_version (
  ticketID int(10) unsigned NOT NULL,
  versionID int(10) unsigned NOT NULL,
  PRIMARY KEY (ticketID,versionID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Ticket comments
DROP TABLE IF EXISTS it1_1_ticket_comment;
CREATE TABLE it1_1_ticket_comment (
  commentID int(10) unsigned NOT NULL AUTO_INCREMENT,
  ticketID int(10) unsigned NOT NULL,
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
  ipAddress varchar(15) NOT NULL,
  PRIMARY KEY (commentID),
  KEY ticketID (ticketID),
  FULLTEXT KEY `subject` (`subject`,message)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
