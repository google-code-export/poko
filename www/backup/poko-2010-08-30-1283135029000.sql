#META_COMMENT=Poko CMS Base
#META_DB_HOST=192.168.1.80
#META_DB_NAME=poko
#META_TABLES=_definitions,_pages,_settings,_users,_users_groups

DROP TABLE IF EXISTS _definitions;

CREATE TABLE `_definitions` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `isPage` tinyint(1) NOT NULL DEFAULT '0',
  `table` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `showFiltering` tinyint(1) NOT NULL DEFAULT '0',
  `showOrdering` tinyint(1) NOT NULL DEFAULT '0',
  `showInMenu` tinyint(1) NOT NULL DEFAULT '0',
  `elements` text COLLATE latin1_general_ci,
  `order` float DEFAULT NULL,
  `indents` tinyint(4) NOT NULL DEFAULT '0',
  `postCreateSql` text COLLATE latin1_general_ci NOT NULL,
  `postEditSql` text COLLATE latin1_general_ci NOT NULL,
  `postDeleteSql` text COLLATE latin1_general_ci NOT NULL,
  `postProcedure` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `help` text COLLATE latin1_general_ci NOT NULL,
  `help_list` text COLLATE latin1_general_ci NOT NULL,
  `autoOrdering` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `allowCsv` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



DROP TABLE IF EXISTS _pages;

CREATE TABLE `_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `definitionId` int(22) NOT NULL,
  `data` text COLLATE latin1_general_ci,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS _settings;

CREATE TABLE `_settings` (
  `key` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _settings VALUES ("siteView","cy36:site.cms.modules.base.helper.MenuDefy8:headingsaoy4:namey4:Sitey11:isSeperatorfghy5:itemsaoy2:idi38y4:typewy41:site.cms.modules.base.helper.MenuItemTypey4:PAGE:0R2y5:Abouty7:headingR3y6:indentzy12:listChildrenny9:linkChildngoR6i39R7wR8R9:0R2y14:Get%20InvolvedR11R3R12zR13nR14ngoR6i47R7wR8y7:DATASET:0R2y8:ProjectsR11R3R12zR13nR14nghy18:numberOfSeperatorszg");
INSERT INTO _settings VALUES ("googleMapsApiKey","ABQIAAAAPEZwP3fTiAxipcxtf7x-gxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRPwWSQQtyYryiI5S6KBZMsOwuCsw");
INSERT INTO _settings VALUES ("cms","");
INSERT INTO _settings VALUES ("themeCurrent","default");
INSERT INTO _settings VALUES ("cmsTitle","Poko CMS");
INSERT INTO _settings VALUES ("cmsLogo","cmsLogo.png");
INSERT INTO _settings VALUES ("themeStyle","oy15:colorLinkOnDarky9:%2323A2C5y16:colorLinkOnLightR1y23:colorNavigationLinkBgUpR1y25:colorNavigationLinkBgOverR1y24:colorNavigationLinkColory6:%23fffg");
INSERT INTO _settings VALUES ("live","1");
INSERT INTO _settings VALUES ("nonLiveAddress","./coming_soon/");
INSERT INTO _settings VALUES ("emailSettings","");



DROP TABLE IF EXISTS _users;

CREATE TABLE `_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `groups` text COLLATE latin1_general_ci,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _users VALUES ("39","user","1a1dc91c907325c69271ddf0c944bc72","user","pass","cms_editor","2010-07-23 11:10:35","2010-07-23 01:08:34");
INSERT INTO _users VALUES ("40","admin","1a1dc91c907325c69271ddf0c944bc72","Admin","admin","cms_editor,cms_manager,cms_admin","2010-08-30 11:39:29","2010-08-30 11:42:22");



DROP TABLE IF EXISTS _users_groups;

CREATE TABLE `_users_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `isSuper` tinyint(1) NOT NULL DEFAULT '0',
  `stub` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _users_groups VALUES ("1","0","0","cms_editor","CMS Editor","Can edit CMS content.");
INSERT INTO _users_groups VALUES ("2","1","0","cms_manager","CMS Manager","Can edit CMS content as well as adding / removing CMS editors.");
INSERT INTO _users_groups VALUES ("3","1","1","cms_admin","CMS Admin","Can manage Managers. Can edit definitions.");



