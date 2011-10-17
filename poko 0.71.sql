-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 30, 2010 at 03:33 PM
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
(1, 'Web Projects', 'Various web projects we have worked on'),
(2, 'Games', 'Our games!'),
(5, 'Something Else', 'Yet another category!'),
(6, 'Cats', 'Different cats');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `example_images`
--

INSERT INTO `example_images` (`id`, `image`, `link_to`, `link_value`) VALUES
(5, 'c9052c452547b530796144907f38e7ecDesert.jpg', 'example_projects', 5),
(4, '8e8e0527daf5ebde07b139068a1f5e6fChrysanthemum.jpg', 'example_projects', 5),
(6, '4b2203d68a7617ed91873a3297ea153d50229647_39bcd31c01.jpg', 'example_projects', 20),
(7, '356717974247ff287a1f8096c192c111634556562_7de4049b57.jpg', 'example_projects', 20),
(8, 'cc680537107ca491da1c7422abfeceea17200747_f294316218.jpg', 'example_projects', 20),
(9, 'e512415a317e74c91ec353ac63f646c1292626608_b3f9867c07.jpg', 'example_projects', 21),
(10, 'a353515f131b9fc4dc623b3648a2fada3675162262_65d971a898.jpg', 'example_projects', 21);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `example_projects`
--

INSERT INTO `example_projects` (`id`, `category`, `name`, `heroimage`, `pdf`, `description`, `visible`) VALUES
(5, 1, 'My Websites', '0341de70b8f4e8bc8bb57e1a4ee5b70cTulips.jpg', 'a6b5374d8fe5f1eea88f89883e9fbb86test_pdf.pdf', '<p>This is an example projects... This is an example projects... This is an example projects... This is an example projects... <strong>This is an example projects</strong>... This is an example projects... This is an example projects... This is an example projects... This is an example projects... This is an example projects... This is an example projects... <strong><em>This is an example projects</span></em>.</strong>..</p><p>This is an example projects...</p>', 1),
(19, 2, 'Scarygirl', '8e8e0527daf5ebde07b139068a1f5e6fChrysanthemum.jpg', '6fe03133dc42b88dc2f7f1d58e6273bcDSC_4408-2.jpg', '<p>This is a brief project description using the <em>What You Mean Editor!</em></p>', 1),
(20, 6, 'Fluffy', '85c62a889fe07e028b2996f98abe7199385650640_04f8406599.jpg', '', '<br /><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque accumsan, nibh at viverra pulvinar, tortor dolor scelerisque urna, vel ultrices dui sapien id tortor. Vestibulum nulla metus, lacinia sed elementum ut, porttitor sed diam. Praesent bibendum iaculis interdum. Sed a diam sit amet sapien pharetra aliquet nec non neque. Nunc tortor tellus, bibendum quis scelerisque nec, posuere quis diam. Nullam orci arcu, auctor et tristique ac, malesuada et leo. Donec arcu elit, pharetra et pharetra in, gravida et dolor. Sed tempus metus ac lacus imperdiet tempor. Ut dapibus, quam vel sagittis viverra, ligula eros placerat diam, ut cursus tellus sapien eu elit. Etiam non augue purus. </p>', 1),
(21, 5, 'Food Project', '2ca7bae7789d6818d36b790b489454ca4166220739_e9d4144ed6.jpg', '', '<p>Different foods</p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `example_projects_services`
--

CREATE TABLE IF NOT EXISTS `example_projects_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectId` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=251 ;

--
-- Dumping data for table `example_projects_services`
--

INSERT INTO `example_projects_services` (`id`, `projectId`, `serviceId`) VALUES
(248, 19, 2),
(223, 20, 2),
(250, 21, 1),
(249, 20, 3);

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
(1, 'Design', '<p>Good design</p>'),
(2, 'Washing', '<p>We can wash too</p>'),
(3, 'House Sitting', '<p>Free house sitting service!</p>');

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
  `allowCsv` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=61 ;

--
-- Dumping data for table `_definitions`
--

INSERT INTO `_definitions` (`id`, `name`, `description`, `isPage`, `table`, `showFiltering`, `showOrdering`, `showInMenu`, `elements`, `order`, `indents`, `postCreateSql`, `postEditSql`, `postDeleteSql`, `postProcedure`, `help`, `help_list`, `autoOrdering`, `allowCsv`) VALUES
(58, 'example_projects', '', 0, 'example_projects', 1, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey6:link_1y4:typey11:linkdisplayy6:dbtypeny5:labely6:Imagesy10:propertiesoR3R4R1R2R6R7y5:tabley14:example_imagesgy5:orderny10:showInListny15:showInFilteringny14:showInOrderingngcR0R1y2:idR3y9:read-onlyR5nR6y2:IDR8oR3R16R1R15R6R17y11:descriptiony0:R12i1R13zR14zgR11nR12i1R13zR14zgcR0R1y8:categoryR3y11:associationR5nR6y8:CategoryR8oR3R21R1R20R6R22R18R19R12i1R13zR14zR9y18:example_categoriesy5:fieldR15y10:fieldLabelR20y8:fieldSqlR19y11:showAsLabely1:0gR11nR12i1R13tR14zgcR0R1R1R3y4:textR5nR6y4:NameR8oR3R29R1R1R6R30R18R19R12i1R13zR14zy11:isMultilineR28y5:widthy3:300y6:heightR19y8:minCharsR19y8:maxCharsR19y9:charsListR19y4:modey5:ALLOWy5:regexR19y10:regexErrorR19y16:regexDescriptionR19y20:regexCaseInsensitiveR28y8:requiredR28y9:formatterR19gR11nR12i1R13zR14zgcR0R1y9:heroimageR3y10:image-fileR5nR6y12:Hero%20ImageR8oR3R47R1R46R6R48R18R19R12i1R13zR14zR44R28y7:isImagey1:1y7:extListR19y7:extModeR39y7:minSizeR19y7:maxSizeR19y10:uploadTypeR50y17:showOnlyLibrariesR19y11:libraryViewR28gR11nR12i1R13zR14zgcR0R1y3:pdfR3R29R5nR6y3:PDFR8oR3R29R1R58R6R59R18R19R12i1R13zR14zR31R28R32R33R34R19R35R19R36R19R37R19R38R39R40R19R41R19R42R19R43R28R44R28R45R19gR11nR12i1R13zR14zgcR0R1R18R3y12:richtext-wymR5nR6y11:DescriptionR8oR3R60R1R18R6R61R18R19R12i1R13zR14zR32y3:400R34y3:200R44R28y11:allowTablesR28y11:allowImagesR50y12:editorStylesR19y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR19gR11nR12i1R13zR14zgcR0R1y7:visibleR3y4:boolR5nR6y12:Is%20VisibleR8oR3R71R1R70R6R72R18R19R12i1R13zR14zy9:labelTruey3:Yesy10:labelFalsey2:Noy12:defaultValueR28y14:showHideFieldsR19y13:showHideValueR28gR11nR12i1R13zR14zgh', 4, 0, '', '', '', '', '', '', '|ASC', 1),
(59, 'example_projects_services', '', 0, 'example_projects_services', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:ordernR11i1R12zR13zgcR0R1y9:projectIdR3y11:associationR5nR6y12:Project%20IDR8oR3R16R1R15R6R17R9R10R11i1R12zR13zy5:tabley16:example_projectsy5:fieldR2y10:fieldLabelR1y8:fieldSqlR10y11:showAsLabely1:0gR14nR11i1R12zR13zgcR0R1y9:serviceIdR3R16R5nR6y12:Service%20IDR8oR3R16R1R25R6R26R9R10R11i1R12zR13zR18y16:example_servicesR20R2R21R1R22R10R23R24gR14nR11i1R12zR13zgh', 5, 0, '', '', '', '', '', '', '|ASC', 0),
(60, 'example_services', '', 0, 'example_services', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:ordernR11i1R12zR13zgcR0R1R1R3y4:textR5nR6y4:NameR8oR3R15R1R1R6R16R9R10R11i1R12zR13zy11:isMultiliney1:0y5:widthR10y6:heightR10y8:minCharsR10y8:maxCharsR10y9:charsListR10y4:modey5:ALLOWy5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR18y8:requiredR18y9:formatterR10gR14nR11i1R12zR13zgcR0R1R9R3y12:richtext-wymR5nR6y11:DescriptionR8oR3R32R1R9R6R33R9R10R11i1R12zR13zR19y3:400R20y3:200R30R18y11:allowTablesR18y11:allowImagesy1:1y12:editorStylesR10y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR10gR14nR11i1R12zR13zgh', 6, 0, '', '', '', '', '', '', '|ASC', 0),
(54, 'Test Page', '', 1, NULL, 0, 0, 0, 'acy37:site.cms.common.DefinitionElementMetay4:namey5:imagey4:typey10:image-filey6:dbtypeny5:labely5:Imagey10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzy8:requiredy1:0y7:isImagey1:1y7:extListR10y7:extModey5:ALLOWy7:minSizeR10y7:maxSizeR10y10:uploadTypeR17y17:showOnlyLibrariesR10y11:libraryViewR15gy5:orderd1R11i1R12zR13zgcR0R1y7:headingR3y4:textR5nR6y7:HeadingR8oR3R28R1R27R6R29R9R10R11i1R12zR13zy11:isMultilineR15y5:widthy3:300y6:heightR10y8:minCharsR10y8:maxCharsR10y9:charsListR10y4:modeR20y5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR15R14R15y9:formatterR10gR26d2R11i1R12zR13zgcR0R1y7:contentR3y12:richtext-wymR5nR6y7:ContentR8oR3R44R1R43R6R45R9R10R11i1R12zR13zR31y3:400R33y3:200R14R15y11:allowTablesR15y11:allowImagesR17y12:editorStylesR10y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR10gR26d3R11i1R12zR13zgh', 1, 0, '', '', '', '', '', '', '|ASC', 0),
(55, 'example_categories', '', 0, 'example_categories', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:ordernR11i1R12zR13zgcR0R1R9R3y4:textR5nR6y11:DescriptionR8oR3R15R1R9R6R16R9R10R11i1R12zR13zy11:isMultiliney1:0y5:widthy3:300y6:heightR10y8:minCharsR10y8:maxCharsR10y9:charsListR10y4:modey5:ALLOWy5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR18y8:requiredR18y9:formatterR10gR14nR11i1R12zR13zgcR0R1y8:categoryR3R15R5nR6y8:CategoryR8oR3R15R1R33R6R34R9R10R11i1R12zR13zR17R18R19R20R21R10R22R10R23R10R24R10R25R26R27R10R28R10R29R10R30R18R31R18R32R10gR14nR11i1R12zR13zgh', 1, 0, '', '', '', '', '', '', '|ASC', 0),
(56, 'example_images', '', 0, 'example_images', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:orderd1R11i1R12zR13zgcR0R1y5:imageR3y10:image-fileR5nR6y5:ImageR8oR3R16R1R15R6R17R9R10R11i1R12zR13zy8:requiredy1:0y7:isImagey1:1y7:extListR10y7:extModey5:ALLOWy7:minSizeR10y7:maxSizeR10y10:uploadTypeR21y17:showOnlyLibrariesR10y11:libraryViewR19gR14d2R11i1R12zR13zgcR0R1y7:link_toR3y7:link-toR5nR6R10R8oR3R31R1R30R6R10R9R10R11i1R12zR13zgR14nR11i1R12zR13zgcR0R1y10:link_valueR3y10:link-valueR5nR6R10R8oR3R33R1R32R6R10R9R10R11i1R12zR13zgR14nR11i1R12zR13zgh', 2, 0, '', '', '', '', '', '', '|ASC', 0),
(57, 'example_news', '', 0, 'example_news', 0, 0, 1, 'acy37:site.cms.common.DefinitionElementMetay4:namey2:idy4:typey9:read-onlyy6:dbtypeny5:labely2:IDy10:propertiesoR3R4R1R2R6R7y11:descriptiony0:y10:showInListi1y15:showInFilteringzy14:showInOrderingzgy5:ordernR11i1R12zR13zgcR0R1y5:titleR3y4:textR5nR6y5:TitleR8oR3R16R1R15R6R17R9R10R11i1R12zR13zy11:isMultiliney1:0y5:widthy3:300y6:heightR10y8:minCharsR10y8:maxCharsR10y9:charsListR10y4:modey5:ALLOWy5:regexR10y10:regexErrorR10y16:regexDescriptionR10y20:regexCaseInsensitiveR19y8:requiredR19y9:formatterR10gR14nR11i1R12zR13zgcR0R1y4:dateR3R34R5nR6y4:DateR8oR3R34R1R34R6R35R9R10R11i1R12zR13zy12:currentOnAddR19y11:restrictMinR19y9:minOffsetR10y11:restrictMaxR19y9:maxOffsetR10R32R19R26y4:DATEgR14nR11i1R12zR13zgcR0R1y7:contentR3y12:richtext-wymR5nR6y7:ContentR8oR3R43R1R42R6R44R9R10R11i1R12zR13zR20y3:400R22y3:200R32R19y11:allowTablesR19y11:allowImagesy1:1y12:editorStylesR10y15:containersItemsy234:%7B%27name%27%3A%20%27P%27%2C%20%27title%27%3A%20%27Paragraph%27%2C%20%27css%27%3A%20%27wym_containers_p%27%7D%2C%20%0D%0A%7B%27name%27%3A%20%27H1%27%2C%20%27title%27%3A%20%27Heading_1%27%2C%20%27css%27%3A%20%27wym_containers_h1%27%7Dy12:classesItemsR10gR14nR11i1R12zR13zgcR0R1R14R3y6:numberR5nR6y5:OrderR8oR3R54R1R14R6R55R9R10R11i1R12zR13zy3:minR10y3:maxR10y5:isInty5:FloatR32R19gR14nR11i1R12zR13zgcR0R1y9:timestampR3R34R5nR6y9:TimestampR8oR3R34R1R60R6R61R9R10R11i1R12zR13zR36R19R37R19R38R10R39R19R40R10R32R19R26y8:DATETIMEgR14nR11i1R12zR13zgh', 3, 0, '', '', '', '', '', '', '|ASC', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=46 ;

--
-- Dumping data for table `_pages`
--

INSERT INTO `_pages` (`id`, `definitionId`, `data`, `updated`) VALUES
(45, 54, 'oy8:__actiony4:edity5:imagey42:c9052c452547b530796144907f38e7ecDesert.jpgy7:headingy24:Test%20Page%20Heading%21y7:contenty146:%3Cp%3EHere%20is%20some%20content%20from%20the%20%3Cstrong%3EWYM%3C%2Fstrong%3E%20%3Cem%3E%28What%20You%20Mean%29%3C%2Fem%3E%20editor%21%3C%2Fp%3Ey8:__submity6:Submitg', '2010-08-23 15:26:43');

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
('siteView', 'cy36:site.cms.modules.base.helper.MenuDefy8:headingsaoy4:namey14:Example%20Datay11:isSeperatorfgoR2y15:Example%20PagesR4fghy5:itemsaoy2:idi55y4:typewy41:site.cms.modules.base.helper.MenuItemTypey7:DATASET:0R2y10:Categoriesy7:headingR3y6:indentzy12:listChildrenny9:linkChildngoR7i57R8wR9R10:0R2y4:NewsR12R3R13zR14nR15ngoR7i58R8wR9R10:0R2y8:ProjectsR12R3R13zR14nR15ngoR7i60R8wR9R10:0R2y8:ServicesR12R3R13zR14nR15ngoR7i59R8wR9R10:0R2y25:Projects%20%2F%20ServicesR12R3R13zR14nR15ngoR7i56R8wR9R10:0R2y6:ImagesR12R3R13zR14nR15ngoR7i45R8wR9y4:PAGE:0R2y11:Test%20PageR12R5R13zR14nR15nghy18:numberOfSeperatorszg'),
('googleMapsApiKey', 'ABQIAAAAPEZwP3fTiAxipcxtf7x-gxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRPwWSQQtyYryiI5S6KBZMsOwuCsw'),
('cms', ''),
('themeCurrent', 'default'),
('cmsTitle', 'Poko CMS'),
('cmsLogo', 'cmsLogo.png'),
('themeStyle', 'oy15:colorLinkOnDarky9:%2323A2C5y16:colorLinkOnLightR1y23:colorNavigationLinkBgUpR1y25:colorNavigationLinkBgOverR1y24:colorNavigationLinkColory6:%23fffg'),
('live', '1'),
('nonLiveAddress', './coming_soon/'),
('emailSettings', 'oy9:userTabley18:example_categoriesy7:idFieldy2:idy10:emailFieldy8:categoryy9:nameFieldy11:descriptiony6:submity0:g');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=41 ;

--
-- Dumping data for table `_users`
--

INSERT INTO `_users` (`id`, `username`, `password`, `name`, `email`, `groups`, `updated`, `added`) VALUES
(39, 'user', '1a1dc91c907325c69271ddf0c944bc72', 'User', 'pass', 'cms_editor', '2010-07-23 11:10:35', '2010-07-23 01:08:34'),
(40, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'Admin', 'admin', 'cms_editor,cms_manager,cms_admin', '2010-08-30 11:39:29', '2010-08-30 11:42:22');

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
