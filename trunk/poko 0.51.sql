-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 18, 2009 at 12:55 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `poko`
--

-- --------------------------------------------------------

--
-- Table structure for table `example_categories`
--

CREATE TABLE IF NOT EXISTS `example_categories` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `example_categories`
--

INSERT INTO `example_categories` (`id`, `category`, `description`) VALUES
(1, 'web projects', ''),
(2, 'games', 'our games projectsd');

-- --------------------------------------------------------

--
-- Table structure for table `example_images`
--

CREATE TABLE IF NOT EXISTS `example_images` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `link_to` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `link_value` int(22) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `example_images`
--

INSERT INTO `example_images` (`id`, `image`, `link_to`, `link_value`) VALUES
(5, '3868fd9f742f3589462f82a0628f609b346512198_0aebf172c5.jpg', 'example_projects', 5),
(4, '545b832a1546114e187107ec04392e4f305567259_65b43754f1.jpg', 'example_projects', 5);

-- --------------------------------------------------------

--
-- Table structure for table `example_news`
--

CREATE TABLE IF NOT EXISTS `example_news` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date` date NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `order` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `example_news`
--

INSERT INTO `example_news` (`id`, `title`, `date`, `content`, `order`, `timestamp`) VALUES
(2, 'hello', '2009-05-14', '<p>"Good Guys &amp; Bad Guys" Toy Mini Series by Play Imagination in stores late 2009</p>\r\n<p>&nbsp;</p>', 7, '2009-05-14 22:05:48'),
(4, 'hello', '2009-01-01', '<p>this is a test</p>', 6, '2009-05-14 16:17:51'),
(8, 'Game release ', '2009-01-01', '<p>this is a test</p>', 3, '2009-05-14 16:18:05'),
(9, 'test again', '2009-01-01', '<p>sdg</p>', 4, '2009-05-14 16:18:12'),
(10, 'fbdf', '2009-01-01', '<p>dfbdfbdfbfdb</p>', 5, '2009-05-14 16:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `example_projects`
--

CREATE TABLE IF NOT EXISTS `example_projects` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `category` int(22) NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `heroimage` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `example_projects`
--

INSERT INTO `example_projects` (`id`, `category`, `name`, `heroimage`, `description`, `visible`) VALUES
(5, 1, 'My Websites', '87ac6304cbb6fade6c7d19382da6a90b225128134_d664a1c398.jpg', '<p>This is an example projects</p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `example_projects_services`
--

CREATE TABLE IF NOT EXISTS `example_projects_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `example_projects_services`
--

INSERT INTO `example_projects_services` (`id`, `projectId`, `serviceId`) VALUES
(10, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `example_services`
--

CREATE TABLE IF NOT EXISTS `example_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `example_services`
--

INSERT INTO `example_services` (`id`, `name`, `description`) VALUES
(1, 'design', '<p>good design</p>'),
(2, 'washing', '<p>we can wash too</p>');

-- --------------------------------------------------------

--
-- Table structure for table `_definitions`
--

CREATE TABLE IF NOT EXISTS `_definitions` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `primaryKey` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `isPage` tinyint(1) NOT NULL DEFAULT '0',
  `table` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `showFiltering` tinyint(1) NOT NULL DEFAULT '0',
  `showOrdering` tinyint(1) NOT NULL DEFAULT '0',
  `showInMenu` tinyint(1) NOT NULL DEFAULT '0',
  `elements` text COLLATE latin1_general_ci,
  `order` float DEFAULT NULL,
  `indents` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `_definitions`
--

INSERT INTO `_definitions` (`id`, `name`, `description`, `primaryKey`, `isPage`, `table`, `showFiltering`, `showOrdering`, `showInMenu`, `elements`, `order`, `indents`) VALUES
(1, 'Test Page', 'test 3', '', 1, '', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey7:headingy4:typey4:texty6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR13y8:requiredR13gy5:orderd1R9zR10zR11zgcR0R1y7:contentR3y8:richtextR5nR6y14:Page%20ContentR8oR3R28R1R27R6R29R9zR10zR11zR19y6:SIMPLER14R7R15R7y3:cssR7R25R13gR26d2R9zR10zR11zgcR0R1y5:imageR3R32R5nR6R7R8oR3R32R1R32R6R7R9zR10zR11zR25R13gR26d3R9zR10zR11zgh', 1, 0),
(2, 'contact', '', '', 1, '', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey7:addressy4:typey4:texty6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR13y8:requiredR13gy5:orderd1R9zR10zR11zgcR0R1y5:phoneR3R4R5nR6y14:Phone%20numberR8oR3R4R1R27R6R28R9zR10zR11zR12R13R14R7R15R7R16R7R17R7R18R7R19R20R21R7R22R7R23R7R24R13R25R13gR26d1R9zR10zR11zgcR0R1y7:detailsR3y8:richtextR5nR6R7R8oR3R30R1R29R6R7R9zR10zR11zR19y6:SIMPLER14R7R15R7y3:cssR7R25R13gR26d2R9zR10zR11zgh', 2, 0),
(3, 'about', '', '', 1, '', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey8:bodytexty4:typey8:richtexty6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzy4:modey6:SIMPLEy5:widthR7y6:heightR7y3:cssR7y8:requiredy1:0gy5:orderd1R9zR10zR11zgcR0R1y8:servicesR3y8:keyvalueR5nR6R7R8oR3R21R1R20R6R7R9zR10zR11zy8:keyLabely7:servicey14:keyIsMultilineR18y8:keyWidthR7y9:keyHeightR7y11:keyMinCharsR7y11:keyMaxCharsR7y12:keyCharsListR7y7:keyNodey5:ALLOWy8:keyRegexR7y13:keyRegexErrorR7y14:keyDescriptionR7y23:keyRegexCaseInsensitiveR18y11:keyRequiredR18y10:valueLabely11:descriptiony16:valueIsMultilineR18y10:valueWidthR7y11:valueHeightR7y13:valueMinCharsR7y13:valueMaxCharsR7y14:valueCharsListR7y9:valueModeR31y10:valueRegexR7y15:valueRegexErrorR7y16:valueDescriptionR7y25:valueRegexCaseInsensitiveR18y13:valueRequiredR18gR19d2R9zR10zR11zgh', 3, 0),
(4, 'News', '', '', 0, 'example_news', 0, 1, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzgy5:ordernR9zR10zR11zgcR0R1y4:dateR3R13R5nR6R7R8oR3R13R1R13R6R7R9i1R10zR11i1y11:restrictMiny1:0y9:minOffsetR7y11:restrictMaxR15y9:maxOffsetR7y8:requiredR15gR12nR9i1R10zR11i1gcR0R1y5:titleR3y4:textR5nR6R7R8oR3R21R1R20R6R7R9i1R10i1R11i1y11:isMultilineR15y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR15R19R15gR12nR9i1R10i1R11i1gcR0R1y7:contentR3y8:richtextR5nR6R7R8oR3R35R1R34R6R7R9zR10zR11zR28y6:SIMPLER23R7R24R7y3:cssR7R19R15gR12nR9zR10zR11zgh', 3, 0),
(6, 'Projects', '', '', 0, 'example_projects', 1, 1, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R9tR10tR11tgcR0R1y8:categoryR3y11:associationR5nR6R7R8oR3R14R1R13R6R7R9i1R10i1R11i1y5:tabley18:example_categoriesy5:fieldR2y10:fieldLabelR13y11:showAsLabely1:1gR12d2R9tR10tR11ty12:showInFiltertgcR0R1R1R3y4:textR5nR6R7R8oR3R22R1R1R6R7R9i1R10zR11zy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR24y8:requiredR24gR12d3R9i1R10zR11zR21ty11:showInOrdertgcR0R1y9:heroimageR3y5:imageR5nR6R7R8oR3R39R1R38R6R7R9i1R10zR11zR36R24gR12d4R9i1R10zR11zgcR0R1y11:descriptionR3y8:richtextR5nR6R7R8oR3R41R1R40R6R7R9zR10zR11zR30y6:SIMPLER25R7R26R7y3:cssR7R36R24gR12d5R9zR10zR11zgcR0R1y11:multilink_1R3y9:multilinkR5nR6y8:ServicesR8oR3R45R1R44R6R46R15y16:example_servicesR17R2R18R1y4:linky25:example_projects_servicesy10:linkField1y9:projectIdy10:linkField2y9:serviceIdgR12d6R9nR10nR11ngcR0R1y6:link_1R3y11:linkdisplayR5nR6y6:ImagesR8oR3R55R1R54R6R56R15y14:example_imagesgR12d7R9nR10nR11ngcR0R1y7:visibleR3y4:boolR5nR6R7R8oR3R59R1R58R6R7R9i1R10zR11zy9:labelTrueR7y10:labelFalseR7gR12nR9i1R10zR11zgh', 1, 0),
(5, 'Categories', '', '', 0, 'example_categories', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzgy5:ordernR9zR10zR11zgcR0R1y8:categoryR3y4:textR5nR6R7R8oR3R14R1R13R6R7R9i1R10zR11zy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR16y8:requiredR16gR12nR9i1R10zR11zgcR0R1y11:descriptionR3R14R5nR6R7R8oR3R14R1R29R6R7R9i1R10zR11zR15R16R17R7R18R7R19R7R20R7R21R7R22R23R24R7R25R7R26R7R27R16R28R16gR12nR9i1R10zR11zgh', 2, 1),
(7, 'Images', '', '', 0, 'example_images', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R9i1R10zR11zgcR0R1y5:imageR3R13R5nR6R7R8oR3R13R1R13R6R7R9i1R10zR11zy8:requiredy1:0gR12d2R9i1R10zR11zgcR0R1y7:link_toR3y7:link-toR5nR6R7R8oR3R17R1R16R6R7R9zR10zR11zgR12d3R9zR10zR11zgcR0R1y10:link_valueR3y10:link-valueR5nR6R7R8oR3R19R1R18R6R7R9zR10zR11zgR12d4R9zR10zR11zgh', 5, 0),
(8, 'Services', '', '', 0, 'example_services', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:ordernR9i1R10zR11zgcR0R1R1R3y4:textR5nR6R7R8oR3R13R1R1R6R7R9i1R10zR11zy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR15y8:requiredR15gR12nR9i1R10zR11zgcR0R1y11:descriptionR3y8:richtextR5nR6R7R8oR3R29R1R28R6R7R9zR10zR11zR21y6:SIMPLER16R7R17R7y3:cssR7R27R15gR12nR9zR10zR11zgh', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `_pages`
--

CREATE TABLE IF NOT EXISTS `_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `definitionId` int(22) NOT NULL,
  `data` text COLLATE latin1_general_ci,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `_pages`
--

INSERT INTO `_pages` (`id`, `definitionId`, `data`, `updated`) VALUES
(1, 1, 'oy7:headingy14:Test%20Headingy7:contenty468:%3Cp%3EThis%20is%20the%20content%20of%20the%20test%20page.%20you%20can%20style%20it%20a%20little%3C%2Fp%3E%0D%0A%3Cp%3E%3Cspan%20style%3D%22text-decoration%3A%20underline%3B%22%3E%3Cem%3E%3Cstrong%3Elike%20this%3C%2Fstrong%3E%3C%2Fem%3E%3C%2Fspan%3E%3C%2Fp%3E%0D%0A%3Cp%3EYou%20can%20also%20set%20the%20WYSIWYG%20editor%20to%20have%20full%20options..%20this%20is%20the%20simple%20mode%20however.%20%3Cem%3E%3Cstrong%3E%3Cbr%20%2F%3E%3C%2Fstrong%3E%3C%2Fem%3E%3C%2Fp%3Ey5:imagey56:08ca1aeb33aa9fac369c64c4b2eac815225128134_d664a1c398.jpgy8:__actiony4:edity8:__submity6:Submitg', '2009-05-14 11:58:32'),
(2, 2, 'oy7:addressy13:2%20Somehtingy5:phoney14:123445%2012345y7:detailsy50:%3Cp%3Ethese%20are%20the%20details%20csc%3C%2Fp%3Ey8:__actiony4:edity8:__submity6:Submitg', '2009-05-14 11:58:46'),
(3, 3, 'oy8:bodytexty61:%3Cp%3EWe%20make%20websites%2C%20you%20might%20also%3C%2Fp%3Ey8:servicesy110:aoy3%3Akeyy12%3Aweb%2520designy5%3Avaluey15%3Awe%2520make%2520webgoR0y5%3AgamesR2y17%3Awe%2520make%2520gamesghy8:__actiony4:edity8:__submity6:Submitg', '2009-05-14 11:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `_users`
--

CREATE TABLE IF NOT EXISTS `_users` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=38 ;

--
-- Dumping data for table `_users`
--

INSERT INTO `_users` (`id`, `username`, `password`, `name`, `email`, `groups`, `updated`, `added`) VALUES
(5, 'super', '1a1dc91c907325c69271ddf0c944bc72', 'Super User', 'email@email.com', 'cms_admin,cms_manager,cms_editor', '2009-03-26 18:51:01', '0000-00-00 00:00:00'),
(34, 'editor', '1a1dc91c907325c69271ddf0c944bc72', 'Editor', 'email@email.com', 'cms_editor', '2009-03-30 18:59:48', '2009-03-30 18:58:07'),
(35, 'manager', '1a1dc91c907325c69271ddf0c944bc72', 'Manager', 'email@email.com', 'cms_manager', '2009-03-30 18:59:59', '2009-03-30 18:58:19'),
(36, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'Admin', 'email@email.com', 'cms_admin', '2009-03-30 19:00:19', '2009-03-30 18:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `_users_groups`
--

CREATE TABLE IF NOT EXISTS `_users_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `isSuper` tinyint(1) NOT NULL DEFAULT '0',
  `stub` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `_users_groups`
--

INSERT INTO `_users_groups` (`id`, `isAdmin`, `isSuper`, `stub`, `name`, `description`) VALUES
(1, 0, 0, 'cms_editor', 'CMS Editor', 'Can edit CMS content.'),
(2, 1, 0, 'cms_manager', 'CMS Manager', 'Can edit CMS content as well as adding / removing CMS editors.'),
(3, 1, 1, 'cms_admin', 'CMS Admin', 'Can manage Managers. Can edit definitions.');
