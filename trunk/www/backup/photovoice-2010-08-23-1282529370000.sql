#META_COMMENT=Export of Poko tables
#META_DB_HOST=192.168.1.80
#META_DB_NAME=photovoice
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _definitions VALUES ("45","about","About","1","","0","0","0","acy37:site.cms.common.DefinitionElementMetay4:namey5:titley4:typey4:texty6:dbtypeny5:labely5:Titley10:propertiesoR3R4R1R2R6R7y11:descriptiony27:Heading%20of%20main%20text.y10:showInListi1y15:showInFilteringzy14:showInOrderingzy11:isMultiliney1:0y5:widthy3:400y6:heighty0:y8:minCharsR19y8:maxCharsR19y9:charsListR19y4:modey5:ALLOWy5:regexR19y10:regexErrorR19y16:regexDescriptionR19y20:regexCaseInsensitiveR15y8:requiredR15y9:formatterR19gy5:orderd1R11i1R12zR13zgcR0R1y4:bodyR3y12:richtext-wymR5nR6y4:BodyR8oR3R33R1R32R6R34R9y24:Main%20body%20of%20text.R11i1R12zR13zR16y3:500R18y3:300R29R15y11:allowTablesR15y11:allowImagesy1:1y12:editorStylesR19y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR19gR31d2R11i1R12zR13zgcR0R1y9:flag1_midR3y11:associationR5nR6y11:Left%20FlagR8oR3R46R1R45R6R47R9R19R11i1R12zR13zy5:tabley5:mediay5:fieldy2:idy10:fieldLabelR1y8:fieldSqlR19y11:showAsLabelR15gR31d3R11i1R12zR13zgcR0R1y9:flag2_midR3R46R5nR6y12:Right%20FlagR8oR3R46R1R55R6R56R9R19R11i1R12zR13zR48R49R50R51R52R1R53R19R54R15gR31d4R11i1R12zR13zgcR0R1y12:featured_midR3R46R5nR6y16:Featured%20PhotoR8oR3R46R1R57R6R58R9R19R11i1R12zR13zR48R49R50R51R52R1R53R19R54R15gR31d5R11i1R12zR13zgcR0R1y13:altermate_midR3R46R5nR6y17:Alternate%20PhotoR8oR3R46R1R59R6R60R9R19R11i1R12zR13zR48R49R50R51R52R1R53R19R54R15gR31nR11i1R12zR13zgh","1","0","","","","","","","|ASC");
INSERT INTO _definitions VALUES ("46","Media","","0","media","1","0","1","acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R11i1R12zR13zgcR0R1y10:project_idR3y10:link-valueR5nR6y19:Project%20ID%20LinkR8oR3R16R1R15R6R17R9R10R11zR12zR13zgR14d2R11zR12zR13zgcR0R1y3:urlR3y10:image-fileR5nR6y5:ImageR8oR3R19R1R18R6R20R9R10R11i1R12zR13zy8:requiredy1:0y7:isImagey1:1y7:extListR10y7:extModey5:ALLOWy7:minSizeR10y7:maxSizeR10y10:uploadTypeR24y17:showOnlyLibrariesR10y11:libraryViewR22gR14d3R11i1R12zR13zgcR0R1y10:youtube_idR3y4:textR5nR6y12:YouTube%20IDR8oR3R34R1R33R6R35R9y123:YouTube%20Video%20IDs%20are%2011%20characters%20long.%20Example%3A%20%27-5Ilq3kFxek%27.%20%28Do%20not%20include%20quotes%29R11i1R12zR13zy11:isMultilineR22y5:widthR10y6:heightR10y8:minCharsy2:11y8:maxCharsR41y9:charsListR10y4:modeR27y5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR22R21R22y9:formatterR10gR14d4R11i1R12zR13zgcR0R1R1R3R34R5nR6y4:NameR8oR3R34R1R1R6R50R9R10R11i1R12zR13zR37R22R38y3:400R39R10R40R10R42R10R43R10R44R27R45R10R46R10R47R10R48R22R21R22R49R10gR14d5R11i1R12zR13zgcR0R1y4:bodyR3y12:richtext-wymR5nR6y4:BodyR8oR3R53R1R52R6R54R9y24:Main%20body%20of%20text.R11i1R12zR13zR38y3:500R39y3:200R21R22y11:allowTablesR22y11:allowImagesR24y12:editorStylesR10y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR10gR14d6R11fR12zR13zgcR0R1y11:is_featuredR3y4:boolR5nR6y13:Is%20FeaturedR8oR3R65R1R64R6R66R9y128:A%20random%20image%20will%20be%20chosen%20from%20each%20project%27s%20featured%20photos%20to%20represent%20it%20on%20the%20site.R11i1R12zR13zy9:labelTruey3:Yesy10:labelFalsey2:Noy12:defaultValueR22y14:showHideFieldsR10y13:showHideValueR22gR14d6.5R11i1R12zR13zgcR0R1y10:link_tableR3y7:link-toR5nR6R10R8oR3R76R1R75R6R10R9R10R11i1R12zR13zgR14d7R11fR12zR13zgh","1","0","","","","","","","|ASC");
INSERT INTO _definitions VALUES ("47","Projects","","0","projects","0","0","1","acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R11i1R12zR13zgcR0R1R1R3y4:textR5nR6y4:NameR8oR3R15R1R1R6R16R9R10R11i1R12zR13zy11:isMultiliney1:0y5:widthy3:400y6:heightR10y8:minCharsR10y8:maxCharsR10y9:charsListR10y4:modey5:ALLOWy5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR18y8:requiredR18y9:formatterR10gR14d2R11i1R12zR13zgcR0R1y4:dateR3R33R5nR6y4:DateR8oR3R33R1R33R6R34R9R10R11i1R12zR13zy12:currentOnAddR18y11:restrictMinR18y9:minOffsetR10y11:restrictMaxR18y9:maxOffsetR10R31R18gR14d3R11i1R12zR13zgcR0R1y13:location_nameR3R15R5nR6y8:LocationR8oR3R15R1R40R6R41R9R10R11i1R12zR13zR17R18R19R20R21R10R22R10R23R10R24R10R25R26R27R10R28R10R29R10R30R18R31R18R32R10gR14d4R11i1R12zR13zgcR0R1y12:location_mapR3y8:locationR5nR6y14:Map%20PositionR8oR3R43R1R42R6R44R9R10R11i1R12zR13zy15:defaultLocationy34:33.43144133557529%2C%20103.7109375y10:popupWidthR20y11:popupHeightR20y13:searchAddressy1:1gR14d5R11i1R12zR13zgcR0R1y4:bodyR3y12:richtext-wymR5nR6y4:BodyR8oR3R52R1R51R6R53R9y24:Main%20body%20of%20text.R11i1R12zR13zR19y3:500R21y3:300R31R18y11:allowTablesR18y11:allowImagesR50y12:editorStylesR10y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR10gR14d6R11i1R12zR13zgcR0R1y10:other_infoR3y8:keyvalueR5nR6y12:Other%20InfoR8oR3R64R1R63R6R65R9R10R11i1R12zR13zy7:minRowsR10y7:maxRowsR10y8:keyLabelR16y14:keyIsMultilineR18y8:keyWidthR10y9:keyHeightR10y11:keyMinCharsR10y11:keyMaxCharsR10y12:keyCharsListR10y7:keyNodeR26y8:keyRegexR10y13:keyRegexErrorR10y14:keyDescriptionR10y23:keyRegexCaseInsensitiveR18y11:keyRequiredR18y10:valueLabely5:Valuey16:valueIsMultilineR18y10:valueWidthR10y11:valueHeightR10y13:valueMinCharsR10y13:valueMaxCharsR10y14:valueCharsListR10y9:valueModeR26y10:valueRegexR10y15:valueRegexErrorR10y16:valueDescriptionR10y25:valueRegexCaseInsensitiveR18y13:valueRequiredR18gR14d7R11i1R12zR13zgcR0R1y8:media_idR3y11:associationR5nR6y16:Featured%20MediaR8oR3R96R1R95R6R97R9R10R11i1R12zR13zy5:tabley5:mediay5:fieldR2y10:fieldLabelR1y8:fieldSqlR10y11:showAsLabelR18gR14d8R11i1R12zR13zgcR0R1y6:link_1R3y11:linkdisplayR5nR6y10:Image%20LDR8oR3R105R1R104R6R106R98R99gR14nR11nR12nR13ngh","2","0","","","","","","","|ASC");
INSERT INTO _definitions VALUES ("48","get_involved","","1","","0","0","0","acy37:site.cms.common.DefinitionElementMetay4:namey5:titley4:typey4:texty6:dbtypeny5:labely5:Titley10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzy11:isMultiliney1:0y5:widthy3:400y6:heightR10y8:minCharsR10y8:maxCharsR10y9:charsListR10y4:modey5:ALLOWy5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR15y8:requiredR15y9:formatterR10gy5:orderd1R11i1R12zR13zgcR0R1y4:bodyR3y12:richtext-wymR5nR6y4:BodyR8oR3R32R1R31R6R33R9y30:The%20main%20body%20of%20text.R11i1R12zR13zR16y3:500R18y3:200R28R15y11:allowTablesR15y11:allowImagesy1:1y12:editorStylesR10y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR10gR30d2R11i1R12zR13zgcR0R1y8:meida_idR3y11:associationR5nR6y16:Featured%20MediaR8oR3R45R1R44R6R46R9R10R11i1R12zR13zy5:tabley5:mediay5:fieldy2:idy10:fieldLabelR1y8:fieldSqlR10y11:showAsLabelR15gR30d3R11i1R12zR13zgh","2","0","","","","","","","|ASC");



DROP TABLE IF EXISTS _pages;

CREATE TABLE `_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `definitionId` int(22) NOT NULL,
  `data` text COLLATE latin1_general_ci,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _pages VALUES ("38","45","oy8:__actiony4:edity5:titley18:About%20PhotoVoicey4:bodyy1655:%3Cp%3EVestibulum%20ante%20tortor%2C%20aliquam%20ac%20dapibus%20sed%2C%20mattis%20non%20lacus.%20Ut%20vestibulum%20tortor%20at%20libero%20consequat%20a%20ornare%20turpis%20scelerisque.%20Cras%20quam%20metus%2C%20luctus%20in%20ultrices%20quis%2C%20tincidunt%20non%20massa.%20Pellentesque%20mauris%20ipsum%2C%20rutrum%20et%20dapibus%20a%2C%20cursus%20et%20nulla.%20Nulla%20vitae%20mi%20sit%20amet%20nisl%20semper%20ornare.%20Morbi%20et%20sapien%20diam.%20Vestibulum%20bibendum%20porttitor%20lacus%2C%20sed%20rhoncus%20urna%20feugiat%20sed.%20Mauris%20eu%20venenatis%20massa.%20Fusce%20diam%20metus%2C%20suscipit%20id%20tempus%20ac%2C%20congue%20ut%20dui.%3C%2Fp%3E%3Cp%3ELorem%20ipsum%20dolor%20sit%20amet%2C%20consectetur%20adipiscing%20elit.%20Nulla%20tristique%20dictum%20posuere.%20Nullam%20mattis%20dui%20in%20sapien%20facilisis%20vehicula.%20Vestibulum%20ante%20tortor%2C%20aliquam%20ac%20dapibus%20sed%2C%20mattis%20non%20lacus.%20Ut%20vestibulum%20tortor%20at%20libero%20consequat%20a%20ornare%20turpis%20scelerisque.%20Cras%20quam%20metus%2C%20luctus%20in%20ultrices%20quis%2C%20tincidunt%20non%20massa.%20Pellentesque%20mauris%20ipsum%2C%20rutrum%20et%20dapibus%20a%2C%20cursus%20et%20nulla.%20Nulla%20vitae%20mi%20sit%20amet%20nisl%20semper%20ornare.%20Morbi%20et%20sapien%20diam.%20Vestibulum%20bibendum%20porttitor%20lacus%2C%20sed%20rhoncus%20urna%20feugiat%20sed.%20Mauris%20eu%20venenatis%20massa.%20Fusce%20diam%20metus%2C%20suscipit%20id%20tempus%20ac%2C%20congue%20ut%20dui.%20Lorem%20ipsum%20dolor%20sit%20amet%2C%20consectetur%20adipiscing%20elit.%20Sed%20rutrum%20orci%20in%20leo%20tempor%20id%20ornare%20eros%20tincidunt.%20%3C%2Fp%3Ey9:flag1_midy1:2y9:flag2_midy1:3y12:featured_midy1:1y13:altermate_midy2:13y8:__submity6:Submitg","2010-07-14 12:52:41");
INSERT INTO _pages VALUES ("39","48","oy8:__actiony4:edity5:titley17:Get%20Involved%21y4:bodyy629:%3Cp%3EIf%20you%20are%20interested%20in%20photo-stories%20or%20photo-voice%20you%20can%20go%20to%20this%20webpage%20and%20get%20more%20infomation%20about%20how%20it%20all%20works%20%3Ca%20href%3D%22www.photovoice.org%22%3Ewww.photovoice.org%3C%2Fa%3E.%3C%2Fp%3E%3Cp%3EIf%20you%20would%20like%20to%20find%20out%20more%20about%20Photo%20Story%20Mob%20or%20want%20to%20know%20how%20you%20can%20get%20your%20deadly%20project%20up%20the%20Photo%20Story%20Mob%20webpage%20please%20contact%20us%20at%20%3Ca%20href%3D%22photostorymob%40gmail.com%22%3Ephotostorymob%40gmail.com%3C%2Fa%3E%20and%20we%27d%20be%20happy%20to%20help.%3C%2Fp%3Ey8:meida_idy1:4y8:__submity6:Submitg","2010-07-26 12:25:18");



DROP TABLE IF EXISTS _settings;

CREATE TABLE `_settings` (
  `key` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _settings VALUES ("siteView","cy36:site.cms.modules.base.helper.MenuDefy8:headingsaoy4:namey4:Sitey11:isSeperatorfghy5:itemsaoy2:idi38y4:typewy41:site.cms.modules.base.helper.MenuItemTypey4:PAGE:0R2y5:Abouty7:headingR3y6:indentzy12:listChildrenny9:linkChildngoR6i39R7wR8R9:0R2y14:Get%20InvolvedR11R3R12zR13nR14ngoR6i47R7wR8y7:DATASET:0R2y8:ProjectsR11R3R12zR13nR14nghy18:numberOfSeperatorszg");
INSERT INTO _settings VALUES ("googleMapsApiKey","ABQIAAAAPEZwP3fTiAxipcxtf7x-gxTIzS3TpMVv93gZWyLVquGFTpgCshTvtMBxHi_ekj0z5rXTGyd3cWyCXg");
INSERT INTO _settings VALUES ("cms","");
INSERT INTO _settings VALUES ("themeCurrent","default");
INSERT INTO _settings VALUES ("cmsTitle","Photo Story Mob: Content Management System");
INSERT INTO _settings VALUES ("cmsLogo","cmsLogo.png");
INSERT INTO _settings VALUES ("themeStyle","oy15:colorLinkOnDarky9:%2323A2C5y16:colorLinkOnLightR1y23:colorNavigationLinkBgUpR1y25:colorNavigationLinkBgOverR1y24:colorNavigationLinkColory6:%23fffg");
INSERT INTO _settings VALUES ("live","1");
INSERT INTO _settings VALUES ("nonLiveAddress","./coming_soon/");



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
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO _users VALUES ("36","tmp","b951e7c61b078198f5a2a5b07c6aafb6","Tarwin","email@email.com","cms_editor,cms_manager,cms_admin","2009-03-30 19:00:19","2009-03-30 18:58:40");
INSERT INTO _users VALUES ("39","user","1a1dc91c907325c69271ddf0c944bc72","user","pass","cms_editor","2010-07-23 11:10:35","2010-07-23 01:08:34");



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



