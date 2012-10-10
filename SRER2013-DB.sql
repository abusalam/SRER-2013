CREATE TABLE `SRER_FieldNames` (
  `FieldName` varchar(20) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_Form6` (
  `RowID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `SlNo` int(10) DEFAULT NULL,
  `PartID` int(10) DEFAULT NULL,
  `ReceiptDate` varchar(10) DEFAULT NULL,
  `AppName` varchar(255) DEFAULT NULL,
  `RelationshipName` varchar(255) DEFAULT NULL,
  `Relationship` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_Form6A` (
  `RowID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `PartID` int(10) DEFAULT NULL,
  `ReceiptDate` varchar(10) DEFAULT NULL,
  `AppName` varchar(255) DEFAULT NULL,
  `RelationshipName` varchar(255) DEFAULT NULL,
  `Relationship` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `SlNo` int(10) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_Form7` (
  `RowID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `PartID` int(10) DEFAULT NULL,
  `ReceiptDate` varchar(10) DEFAULT NULL,
  `ObjectorName` varchar(255) DEFAULT NULL,
  `PartNo` varchar(255) DEFAULT NULL,
  `SerialNoInPart` int(10) DEFAULT NULL,
  `DelPersonName` varchar(255) DEFAULT NULL,
  `ObjectReason` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `SlNo` int(10) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_Form8` (
  `RowID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `PartID` int(10) DEFAULT NULL,
  `ReceiptDate` varchar(10) DEFAULT NULL,
  `AppName` varchar(255) DEFAULT NULL,
  `RelationshipName` varchar(255) DEFAULT NULL,
  `Relationship` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `SlNo` int(10) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_Form8A` (
  `RowID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `PartID` int(10) DEFAULT NULL,
  `ReceiptDate` varchar(10) DEFAULT NULL,
  `AppName` varchar(255) DEFAULT NULL,
  `RelationshipName` varchar(255) DEFAULT NULL,
  `Relationship` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `SlNo` int(10) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_logs` (
  `LogID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `SessionID` varchar(20) DEFAULT NULL,
  `IP` varchar(15) DEFAULT NULL,
  `Referrer` longtext,
  `UserAgent` longtext,
  `UserID` varchar(20) DEFAULT NULL,
  `URL` longtext,
  `Action` longtext,
  `Method` varchar(10) DEFAULT NULL,
  `URI` longtext,
  `AccessTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LogID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_PartMap` (
  `PartID` int(10) DEFAULT NULL,
  `PartMapID` int(10) DEFAULT NULL,
  `PartNo` varchar(255) DEFAULT NULL,
  `PartName` varchar(255) DEFAULT NULL,
  `ACNo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `SRER_Users` (
  `UserID` varchar(255) DEFAULT NULL,
  `UserName` varchar(255) DEFAULT NULL,
  `UserPass` varchar(255) DEFAULT NULL,
  `PartMapID` int(10) NOT NULL,
  `Remarks` varchar(255) DEFAULT NULL,
  `LoginCount` int(10) DEFAULT '0',
  `LastLoginTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PartMapID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `visitors` (
  `ip` tinytext,
  `vtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vpage` tinytext,
  `referrer` tinytext,
  `uagent` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;