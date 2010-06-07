-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2010 at 10:58 PM
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `example_categories`
--

INSERT INTO `example_categories` (`id`, `category`, `description`) VALUES
(1, 'web projects', 'dfasdfasdf'),
(2, 'games', 'our games projectsd'),
(5, 'sdfgsdf', 'gsdfgsdfg');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=24 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `example_news`
--

INSERT INTO `example_news` (`id`, `title`, `date`, `content`, `order`, `timestamp`) VALUES
(2, 'helloz', '2009-05-14', '<p>"Good Guys &amp; Bad Guys" Toy Mini Series by Play Imagination in stores late 2009</p>\r\n<p>&nbsp;</p>', 5, '2009-06-22 15:14:06'),
(4, 'hello', '2009-01-01', '<p>this is a test</p>', 4, '2009-06-19 14:45:05'),
(8, 'Game release ', '2009-01-01', '<p>this is a test</p>', 2, '2009-06-19 14:45:53'),
(9, 'test again', '2009-01-01', '<p><img src="../../../../../res/media/galleries/scottys%20images/DSC_4408-2.jpg" height="221" width="333" /></p><p>dfdsfsdfsdf</p><p>asdfasdf</p><p>adfadsfadsf asdfasdf<br />asdfasdfasdf<br />asdfasdf</p>', 1, '2009-10-21 19:56:26'),
(13, 'helloz', '2009-05-14', '<p>"Good Guys &amp; Bad Guys" Toy Mini Series by Play Imagination in stores late 2009</p>\r\n<p>&nbsp;</p>', 5, '2009-06-22 15:14:06'),
(12, 'fbdf', '2009-01-01', '<p>dfbdfbdfbfdb</p>', 3, '2009-06-19 14:45:05'),
(14, 'test againasdfsdfasdf', '2009-01-01', '<p>sdg</p>', 1, '2009-07-28 16:00:06'),
(15, 'test again123', '2009-01-01', '<p>sdg</p>', 1, '2009-07-28 16:00:53'),
(16, 'test again123asdfasdf', '2009-01-01', '<p>sdg</p>', 1, '2009-07-28 16:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `example_projects`
--

CREATE TABLE IF NOT EXISTS `example_projects` (
  `id` int(22) NOT NULL AUTO_INCREMENT,
  `category` int(22) NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `heroimage` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `pdf` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=72 ;

--
-- Dumping data for table `example_projects`
--

INSERT INTO `example_projects` (`id`, `category`, `name`, `heroimage`, `pdf`, `description`, `visible`) VALUES
(5, 1, 'My Websites', '0341de70b8f4e8bc8bb57e1a4ee5b70cTulips.jpg', 'a6b5374d8fe5f1eea88f89883e9fbb86test_pdf.pdf', '<p>This is an example projects... This is an example projects... This is an example projects... This is an example projects... <strong>This is an example projects</strong>... This is an example projects... This is an example projects... This is an example projects... This is an example projects... This is an example projects... This is an example projects... <strong><em><span style="text-decoration: underline;">This is an example projects</span></em>.</strong>..</p>\r\n<p>This is an example projects...</p>\r\n<p>&nbsp;</p>', 0),
(19, 2, 'Scarygirl', 'bab42e0582c56f98340fc8ae430323fb54b33cb5f8083642759315a14c063866305567259_65b43754f1.jpg', '6fe03133dc42b88dc2f7f1d58e6273bcDSC_4408-2.jpg', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `example_projects_services`
--

CREATE TABLE IF NOT EXISTS `example_projects_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=249 ;

--
-- Dumping data for table `example_projects_services`
--

INSERT INTO `example_projects_services` (`id`, `projectId`, `serviceId`) VALUES
(11, 12, 2),
(248, 19, 2),
(14, 13, 1),
(15, 13, 2),
(17, 14, 1),
(151, 15, 1),
(118, 16, 1),
(37, 17, 1),
(156, 18, 1),
(224, 20, 2),
(223, 20, 1),
(234, 54, 1),
(235, 55, 2),
(236, 56, 2),
(237, 57, 2),
(238, 58, 2),
(239, 59, 2),
(241, 60, 2),
(243, 61, 2),
(244, 62, 2),
(247, 71, 2);

-- --------------------------------------------------------

--
-- Table structure for table `example_services`
--

CREATE TABLE IF NOT EXISTS `example_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `example_services`
--

INSERT INTO `example_services` (`id`, `name`, `description`) VALUES
(1, 'design', '<p>good design</p>'),
(2, 'washing', '<p>we can wash too</p>');

-- --------------------------------------------------------

--
-- Table structure for table `_crap`
--

CREATE TABLE IF NOT EXISTS `_crap` (
  `thing` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `_crap`
--

INSERT INTO `_crap` (`thing`) VALUES
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('53 sadfadfasdf'),
('54 asdfasdf'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('62 erdgsdfgsdfg'),
('63 '),
('64 '),
('65 fghdhdfg'),
('66 '),
('67 asdfadsfasdf'),
('68 ewragsrdegsdfgsdf'),
('69 asdfasdfa'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('70 vbnvbnvbn'),
('Old Name: Scarygirl, New Name: Scarygirl'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: My Websites, New Name: My Websites'),
('Old Name: Scarygirl, New Name: Scarygirl');

-- --------------------------------------------------------

--
-- Table structure for table `_definitions`
--

CREATE TABLE IF NOT EXISTS `_definitions` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `_definitions`
--

INSERT INTO `_definitions` (`id`, `name`, `description`, `isPage`, `table`, `showFiltering`, `showOrdering`, `showInMenu`, `elements`, `order`, `indents`, `postCreateSql`, `postEditSql`, `postDeleteSql`, `postProcedure`, `help`, `help_list`, `autoOrdering`) VALUES
(1, 'Test Page', 'test 3', 1, '', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey7:headingy4:typey4:texty6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR13y8:requiredR13gy5:orderd1R9zR10zR11zgcR0R1y7:contentR3y12:richtext-wymR5nR6y14:Page%20ContentR8oR3R28R1R27R6R29y11:descriptionR7R9zR10zR11zR14R7R15R7R25R13gR26d2R9tR10zR11zgcR0R1y6:image2R3y10:image-fileR5nR6R7R8oR3R32R1R31R6R7R30R7R9i1R10zR11zR25R13y7:isImagey1:1y7:extListR7y7:extModeR20y7:minSizeR7y7:maxSizeR7y10:uploadTypeR13y17:showOnlyLibrariesR7y11:libraryViewR13gR26d3R9i1R10zR11zgcR0R1y6:image3R3R32R5nR6R7R8oR3R32R1R42R6R7R30R7R9i1R10zR11zR25R34R33R34R35R7R36R20R37R7R38R7R39R13R40R7R41R13gR26d4R9i1R10zR11zgcR0R1y5:imageR3R32R5nR6R7R8oR3R32R1R43R6R7R30R7R9i1R10zR11zR25R34R33R34R35R7R36R20R37R7R38R7R39R13R40R7R41R13gR26nR9i1R10zR11zgh', 1, 0, '', '', '', '', '', '', '|ASC'),
(2, 'Contact', 'Contact Info', 1, '', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey7:addressy4:typey4:texty6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y11:descriptionR7y10:showInListzy15:showInFilteringzy14:showInOrderingzy11:isMultiliney1:0y5:widthy3:500y6:heighty3:300y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR14y8:requiredR14gy5:orderd1R10zR11zR12zgcR0R1y5:phoneR3R4R5nR6y14:Phone%20numberR8oR3R4R1R30R6R31R9R7R10zR11zR12zR13y1:1R15R16R17R18R19R7R20R7R21R7R22R23R24R7R25R7R26R7R27R14R28R14gR29d1R10zR11zR12zgcR0R1y7:detailsR3y12:richtext-wymR5nR6R7R8oR3R34R1R33R6R7R10zR11zR12zR15R16R17R16R28R14gR29d2R10zR11zR12zgh', 2, 1, '', '', '', '', '', '', ''),
(3, 'About', 'About', 1, '', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey8:bodytexty4:typey8:richtexty6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzy4:modey6:SIMPLEy5:widthR7y6:heightR7y3:cssR7y8:requiredy1:0gy5:orderd1R9zR10zR11fgcR0R1y8:servicesR3y8:keyvalueR5nR6R7R8oR3R21R1R20R6R7y11:descriptiony101:You%20know%20you%20need%20our%20services.%202%0D%0A%0D%0AEveryone%20likes%20our%20services...%20no%3FR9zR10zR11zy8:keyLabely7:servicey14:keyIsMultilineR18y8:keyWidthR7y9:keyHeightR7y11:keyMinCharsR7y11:keyMaxCharsR7y12:keyCharsListR7y7:keyNodey5:ALLOWy8:keyRegexR7y13:keyRegexErrorR7y14:keyDescriptionR7y23:keyRegexCaseInsensitiveR18y11:keyRequiredR18y10:valueLabelR22y16:valueIsMultilineR18y10:valueWidthR7y11:valueHeightR7y13:valueMinCharsR7y13:valueMaxCharsR7y14:valueCharsListR7y9:valueModeR33y10:valueRegexR7y15:valueRegexErrorR7y16:valueDescriptionR7y25:valueRegexCaseInsensitiveR18y13:valueRequiredR18gR19d2R9zR10zR11zgcR0R1y4:moreR3R21R5nR6y4:MoreR8oR3R21R1R52R6R53R22y85:More%20information%20about%20stuff%20..%0D%0A%0D%0AYou%20know%20stuff%20...%20yeah%3FR9i1R10zR11zR24R7R26R18R27R7R28R7R29R7R30R7R31R7R32R33R34R7R35R7R36R7R37R18R38R18R39R7R40R18R41R7R42R7R43R7R44R7R45R7R46R33R47R7R48R7R49R7R50R18R51R18gR19d3R9i1R10zR11zgcR0R1y4:blahR3R21R5nR6y4:BLAHR8oR3R21R1R55R6R56R22y125:sdfasdfasdfasdfasfdajsdfjlajksdf%0D%0A%0D%0Aasdfl%3Bkasjkdfjlasdfjlkajlskdf%0D%0A%0D%0A%0D%0Aas%3Blfdajlsdgjlagjladjlf%3BgdfgR9i1R10zR11zy7:minRowsy1:5y7:maxRowsy2:10R24R7R26R18R27R7R28R7R29R7R30R7R31R7R32R33R34R7R35R7R36R7R37R18R38R18R39R7R40R18R41R7R42R7R43R7R44R7R45R7R46R33R47R7R48R7R49R7R50R18R51R18gR19d4R9i1R10zR11zgcR0R1y4:testR3y7:link-toR5nR6y5:stuffR8oR3R63R1R62R6R64R22R7R9i1R10zR11zgR19d5R9i1R10zR11zgh', 3, 2, '', '', '', '', '', '', ''),
(4, 'News', '', 0, 'example_news', 0, 1, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzgy5:ordernR9zR10fR11zgcR0R1y4:dateR3R13R5nR6R7R8oR3R13R1R13R6R7R9i1R10zR11i1y11:restrictMiny1:0y9:minOffsetR7y11:restrictMaxR15y9:maxOffsetR7y8:requiredR15gR12nR9i1R10zR11i1gcR0R1y5:titleR3y4:textR5nR6R7R8oR3R21R1R20R6R7R9i1R10i1R11i1y11:isMultilineR15y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR15R19R15gR12nR9i1R10i1R11i1gcR0R1y7:contentR3y12:richtext-wymR5nR6R7R8oR3R35R1R34R6R7y11:descriptionR7R9zR10zR11zR23R7R24R7R19R15gR12nR9zR10zR11zgcR0R1R12R3R12R5nR6R7R8oR3R12R1R12R6R7R9i1R10zR11zgR12nR9i1R10zR11zgh', 3, 0, '', '', '', '', '', '', ''),
(6, 'Projects', '', 0, 'example_projects', 1, 1, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R9tR10tR11tgcR0R1y8:categoryR3y11:associationR5nR6y8:CategoryR8oR3R14R1R13R6R15y11:descriptionR7R9i1R10i1R11i1y5:tabley18:example_categoriesy5:fieldR2y10:fieldLabelR13y11:showAsLabely1:1gR12d2R9i1R10i1R11i1y12:showInFiltertgcR0R1R1R3y4:textR5nR6y4:NameR8oR3R24R1R1R6R25R16y87:The%20name%20of%20the%20project.%0D%0A%0D%0ADon%27t%20be%20shy%20to%20embellish%20here.R9i1R10zR11zy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR28y8:requiredR28gR12d3R9i1R10zR11zR23ty11:showInOrdertgcR0R1y9:heroimageR3y10:image-fileR5nR6R7R8oR3R43R1R42R6R7R16R7R9i1R10zR11zR40R28y7:isImageR22y7:extListy15:jpg%2Cpng%2Cgify7:extModeR35y7:minSizey3:100y7:maxSizey4:2000y10:uploadTypeR28y17:showOnlyLibrariesR7y11:libraryViewR28gR12d4R9i1R10zR11zgcR0R1R16R3y16:richtext-tinymceR5nR6R7R8oR3R55R1R16R6R7R16R7R9zR10zR11zR34y8:ADVANCEDR29R7R30R7y3:cssR7R40R28gR12d5R9zR10zR11zgcR0R1y7:visibleR3y4:boolR5nR6y22:Services%20Selected%3FR8oR3R59R1R58R6R60R16R7R9i1R10i1R11i1y9:labelTruey3:Yesy10:labelFalsey2:Noy14:showHideFieldsy20:multilink_1%2Clink_1y13:showHideValueR28gR12d6R9i1R10i1R11i1gcR0R1y11:multilink_1R3y9:multilinkR5nR6y8:ServicesR8oR3R69R1R68R6R70R17y16:example_servicesR19R2R20R1y4:linky25:example_projects_servicesy10:linkField1y9:projectIdy10:linkField2y9:serviceIdgR12d7R9nR10nR11ngcR0R1y6:link_1R3y11:linkdisplayR5nR6y6:ImagesR8oR3R79R1R78R6R80R17y14:example_imagesgR12d8R9nR10nR11ngcR0R1y3:pdfR3R43R5nR6y3:PDFR8oR3R43R1R82R6R83R16R7R9i1R10zR11zR40R28R44R28R45R82R47y4:DENYR48R7R50y4:5000R52R28R53R7R54R28gR12d9R9i1R10zR11zgh', 1, 0, '', '', '', 'site.cms.common.procedures.CategoryProcedure', '', '', '|ASC'),
(5, 'Categories', '', 0, 'example_categories', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListzy15:showInFilteringzy14:showInOrderingzgy5:ordernR9fR10zR11zgcR0R1y8:categoryR3y4:textR5nR6R7R8oR3R14R1R13R6R7R9i1R10zR11zy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR16y8:requiredR16gR12nR9i1R10zR11zgcR0R1y11:descriptionR3R14R5nR6R7R8oR3R14R1R29R6R7R9i1R10zR11zR15R16R17R7R18R7R19R7R20R7R21R7R22R23R24R7R25R7R26R7R27R16R28R16gR12nR9i1R10zR11zgh', 2, 1, '', '', '', '', '', '', ''),
(7, 'Images', '', 0, 'example_images', 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R9i1R10zR11zgcR0R1y5:imageR3y10:image-fileR5nR6R7R8oR3R14R1R13R6R7y11:descriptionR7R9i1R10zR11zy8:requiredy1:0y7:isImagey1:1y7:minSizeR7y7:maxSizeR7gR12d2R9i1R10zR11zgcR0R1y7:link_toR3y7:link-toR5nR6R7R8oR3R23R1R22R6R7R9zR10zR11zgR12d3R9zR10zR11zgcR0R1y10:link_valueR3y10:link-valueR5nR6R7R8oR3R25R1R24R6R7R9zR10zR11zgR12d4R9zR10zR11zgh', 5, 0, '', '', '', '', '', '', ''),
(8, 'Services', '', 0, 'example_services', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely0:y10:propertiesoR3R4R1R2R6R7y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:ordernR9i1R10zR11zgcR0R1R1R3y4:textR5nR6R7R8oR3R13R1R1R6R7R9i1R10zR11zy11:isMultiliney1:0y5:widthR7y6:heightR7y8:minCharsR7y8:maxCharsR7y9:charsListR7y4:modey5:ALLOWy5:regexR7y10:regexErrorR7y16:regexDescriptionR7y20:regexCaseInsensitiveR15y8:requiredR15gR12nR9i1R10zR11zgcR0R1y11:descriptionR3y8:richtextR5nR6R7R8oR3R29R1R28R6R7R9zR10zR11zR21y6:SIMPLER16R7R17R7y3:cssR7R27R15gR12nR9zR10zR11zgh', 4, 0, '', '', '', '', '', '', '');

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
(1, 1, 'oy8:__actiony4:edity7:headingy14:Test%20Headingy7:contenty220:%3Cp%3E%3Cstrong%3EThis%20is%20a%20test%20paragraph%3C%2Fstrong%3E%3C%2Fp%3E%3Col%3E%3Cli%3E%3Cem%3Emore%20content%3C%2Fem%3Eist%20data%3C%2Fli%3E%3Cli%3Emore%20list%20%3C%2Fli%3E%3Cli%3Edata%20here%3C%2Fli%3E%3C%2Fol%3Ey6:image2y49:8e8e0527daf5ebde07b139068a1f5e6fChrysanthemum.jpgy6:image3y42:c9052c452547b530796144907f38e7ecDesert.jpgy5:imagey46:ea11fbed32676c0d807158e460cae1c7Hydrangeas.jpgy8:__submity6:Submitg', '2009-05-14 11:58:32'),
(2, 2, 'oy7:addressy13:2%20Somehtingy5:phoney14:123445%2012345y7:detailsy165:%3Cp%3EYes%20you%20dsofdofod%21%21%21%21%3C%2Fp%3E%3Cp%3EYes%20you%20dsofdofod%21%21%21%21%3C%2Fp%3E%3Cp%3EHello%20world%3C%2Fp%3E%3Cp%3EYou%20know%20it%21%3C%2Fp%3Ey8:__actiony4:edity8:__submity6:Submitg', '2009-05-14 11:58:46'),
(3, 3, 'oy8:__actiony4:edity8:servicesy189:aoy3%3Akeyy12%3Aweb%2520designy5%3Avaluey15%3Awe%2520make%2520webgoR0y5%3AgamesR2y17%3Awe%2520make%2520gamesgoR0y3%3A345R2y8%3A34534534goR0y6%3A345345R2y9%3A345345345goR0y2%3AhiR2y3%3Ayoughy4:morey39:aoy3%3Akeyy4%3Apokoy5%3Avaluey3%3Acmsghy4:blahy215:aoy3%3Akeyy9%3Athis%2520isy5%3Avaluey4%3AdatagoR0R1R2R3goR0R1R2R3goR0R1R2R3goR0y15%3Athis%2520is%2520minR2y13%3Aallwed%2520datagoR0y4%3AmoreR2R3goR0y10%3Aand%2520moreR2y11%3Adata%2520heregoR0y3%3AmaxR2y7%3Aallowedghy8:siteModey4:truey8:__submity6:Submitg', '2009-05-14 11:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `_settings`
--

CREATE TABLE IF NOT EXISTS `_settings` (
  `key` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `_settings`
--

INSERT INTO `_settings` (`key`, `value`) VALUES
('siteView', 'cy36:site.cms.modules.base.helper.MenuDefy8:headingsaoy4:namey4:Mainy11:isSeperatorfgoR2y14:Other%20ThingsR4fgoR2y8:__sep1__R4tghy5:itemsaoy2:idi4y4:typewy41:site.cms.modules.base.helper.MenuItemTypey7:DATASET:0R2y4:Newsy7:headingR3y6:indentzy12:listChildrenny9:linkChildngoR8i6R9wR10R11:0R2y8:ProjectsR13R3R14zR15nR16ngoR8i5R9wR10R11:0R2y10:CategoriesR13R3R14i2R15nR16ngoR8i2R9wR10y4:PAGE:0R2y7:ContactR13R3R14zR15nR16ngoR8i3R9wR10R19:0R2y5:AboutR13R3R14zR15nR16ngoR8i8R9wR10R11:0R2y8:ServicesR13R5R14zR15nR16ngoR8i1R9wR10R19:0R2y11:Test%20PageR13R5R14zR15nR16nghy18:numberOfSeperatorsi1g'),
('cms', ''),
('themeCurrent', 'default'),
('cmsTitle', 'poko cms : putting the poke in o''content managemeat'),
('cmsLogo', 'cmsLogo.png'),
('themeStyle', 'oy15:colorLinkOnDarky9:%2323A2C5y16:colorLinkOnLightR1y23:colorNavigationLinkBgUpy9:%23FEE900y25:colorNavigationLinkBgOverR4y24:colorNavigationLinkColory6:%23000g'),
('live', '1'),
('nonLiveAddress', './coming_soon/');

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
(5, 'super', '1a1dc91c907325c69271ddf0c944bc72', 'Super User', 'email@email.com', 'cms_editor,cms_manager,cms_admin', '2009-03-26 18:51:01', '0000-00-00 00:00:00'),
(34, 'editor', '1a1dc91c907325c69271ddf0c944bc72', 'Editor', 'email@email.com', 'cms_editor', '2009-03-30 18:59:48', '2009-03-30 18:58:07'),
(35, 'manager', '1a1dc91c907325c69271ddf0c944bc72', 'Manager', 'email@email.com', 'cms_manager', '2009-03-30 18:59:59', '2009-03-30 18:58:19'),
(36, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'Admin', 'email@email.com', 'cms_editor,cms_manager,cms_admin', '2009-03-30 19:00:19', '2009-03-30 18:58:40');

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
