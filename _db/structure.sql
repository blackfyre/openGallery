-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2013 at 07:36 PM
-- Server version: 5.5.32-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `art`
--

DROP TABLE IF EXISTS `art`;
CREATE TABLE IF NOT EXISTS `art` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `img` varchar(100) DEFAULT NULL,
  `artist` int(10) DEFAULT NULL COMMENT 'artistId from the artist table',
  `title_hu` varchar(200) DEFAULT NULL COMMENT 'title with lang code',
  `titleSlug_hu` varchar(200) DEFAULT NULL COMMENT 'title slug with lang code',
  `title_en` varchar(200) DEFAULT NULL COMMENT 'title with lang code',
  `titleSlug_en` varchar(200) DEFAULT NULL COMMENT 'title slug with lang code',
  `yearOfProduction` varchar(10) DEFAULT NULL,
  `exactYear` tinyint(4) DEFAULT '1',
  `material` int(11) DEFAULT NULL COMMENT 'Material id from the art_material table',
  `placeOfDisplay` int(11) DEFAULT NULL COMMENT 'Current place of display, ID from art_display table',
  `category` int(11) DEFAULT NULL COMMENT 'Category id from the art_category table',
  `description_hu` text COMMENT 'description with lang code',
  `description_en` text COMMENT 'description with lang code',
  `sizeX` int(11) DEFAULT NULL COMMENT 'width in cm',
  `sizeY` int(11) DEFAULT NULL COMMENT 'height in cm',
  `origComment` text COMMENT 'The original comment tag of the image for reference',
  `addedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'image added to db on',
  `lastMod` datetime DEFAULT NULL COMMENT 'last modification date',
  `moddedBy` int(11) DEFAULT NULL COMMENT 'last modifier',
  `published` tinyint(4) DEFAULT '0' COMMENT 'is it published?',
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artist` (`artist`),
  KEY `material` (`material`),
  KEY `placeOfDisplay` (`placeOfDisplay`),
  KEY `titleSlug` (`titleSlug_hu`),
  KEY `FK_art_art_category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5168 ;

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

DROP TABLE IF EXISTS `artist`;
CREATE TABLE IF NOT EXISTS `artist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(200) DEFAULT NULL,
  `lastName` varchar(200) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `dateOfBirth` smallint(6) DEFAULT NULL,
  `dateOfDeath` smallint(6) DEFAULT NULL,
  `exactBirth` tinyint(4) DEFAULT '1',
  `exactDeath` tinyint(4) DEFAULT '1',
  `placeOfBirth` varchar(200) DEFAULT NULL,
  `placeOfDeath` varchar(200) DEFAULT NULL,
  `bio_en` text,
  `bio_hu` text,
  `school` int(11) DEFAULT NULL,
  `profession` int(11) DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `anonym` tinyint(4) DEFAULT '0',
  `firstNameFirst` tinyint(4) DEFAULT '1',
  `active` tinyint(4) DEFAULT '1',
  `excerpt_hu` text NOT NULL,
  `excerpt_en` text NOT NULL,
  `bioImg` varchar(100) DEFAULT NULL,
  `headerImg` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school` (`school`),
  KEY `profession` (`profession`),
  KEY `period` (`period`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=432 ;

-- --------------------------------------------------------

--
-- Table structure for table `artist_period`
--

DROP TABLE IF EXISTS `artist_period`;
CREATE TABLE IF NOT EXISTS `artist_period` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `periodName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `artist_profession`
--

DROP TABLE IF EXISTS `artist_profession`;
CREATE TABLE IF NOT EXISTS `artist_profession` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `professionName_en` varchar(100) DEFAULT NULL,
  `professionName_hu` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `artist_school`
--

DROP TABLE IF EXISTS `artist_school`;
CREATE TABLE IF NOT EXISTS `artist_school` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `schoolName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `art_category`
--

DROP TABLE IF EXISTS `art_category`;
CREATE TABLE IF NOT EXISTS `art_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `art_display`
--

DROP TABLE IF EXISTS `art_display`;
CREATE TABLE IF NOT EXISTS `art_display` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `displayPlace` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `art_material`
--

DROP TABLE IF EXISTS `art_material`;
CREATE TABLE IF NOT EXISTS `art_material` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `materialName` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `art_type`
--

DROP TABLE IF EXISTS `art_type`;
CREATE TABLE IF NOT EXISTS `art_type` (
  `typeId` int(11) NOT NULL AUTO_INCREMENT,
  `typeName_hu` varchar(100) DEFAULT NULL,
  `typeName_en` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`typeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title_hu` varchar(100) DEFAULT NULL,
  `slug_hu` varchar(100) DEFAULT NULL,
  `metaKey_hu` varchar(160) DEFAULT NULL,
  `metaDesc_hu` varchar(260) DEFAULT NULL,
  `linkAlt_hu` varchar(260) DEFAULT NULL,
  `content_hu` text,
  `title_de` varchar(100) DEFAULT NULL,
  `slug_de` varchar(100) DEFAULT NULL,
  `metaKey_de` varchar(160) DEFAULT NULL,
  `metaDesc_de` varchar(260) DEFAULT NULL,
  `linkAlt_de` varchar(260) DEFAULT NULL,
  `content_de` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_hu` (`slug_hu`),
  UNIQUE KEY `slug_de` (`slug_de`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_fixed`
--

DROP TABLE IF EXISTS `content_fixed`;
CREATE TABLE IF NOT EXISTS `content_fixed` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title_hu` varchar(100) DEFAULT NULL,
  `content_hu` text,
  `title_de` varchar(100) DEFAULT NULL,
  `content_de` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_news`
--

DROP TABLE IF EXISTS `content_news`;
CREATE TABLE IF NOT EXISTS `content_news` (
  `newsId` int(10) NOT NULL AUTO_INCREMENT,
  `isoCode` varchar(3) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `metaKey` varchar(160) DEFAULT NULL,
  `metaDesc` varchar(260) DEFAULT NULL,
  `linkAlt` varchar(260) DEFAULT NULL,
  `content` text,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `addedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`newsId`),
  UNIQUE KEY `slug_hu` (`slug`),
  KEY `isoCode` (`isoCode`),
  KEY `slug` (`slug`,`published`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'A nyelv azonosító száma',
  `isoCode` varchar(3) DEFAULT NULL COMMENT 'A nyelv ISO Kódja (hu, en, sk, ...)',
  `full` varchar(50) DEFAULT NULL COMMENT 'A nyelv megnevezése (Magyar, Angol, ...)',
  `locale` varchar(10) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1' COMMENT 'Az adott nyelv aktív -e',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='A rendszerben elérhető aktív nyelvek' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL,
  `title_hu` varchar(500) DEFAULT NULL,
  `title_en` varchar(500) DEFAULT NULL,
  `target` varchar(500) DEFAULT '_self',
  `link` varchar(500) DEFAULT NULL,
  `alt_hu` varchar(500) DEFAULT NULL,
  `alt_en` varchar(500) DEFAULT NULL,
  `order` int(10) DEFAULT NULL,
  `place` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_elements`
--

DROP TABLE IF EXISTS `menu_elements`;
CREATE TABLE IF NOT EXISTS `menu_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) DEFAULT NULL COMMENT 'A szülő elem azonosítója',
  `positionId` int(11) DEFAULT NULL COMMENT 'Az elem elhelyezkedése (Fő menü, lábléc, stb)',
  `langCode` varchar(3) DEFAULT NULL,
  `linkOrder` int(11) DEFAULT NULL COMMENT 'Sorrend sorszám',
  `linkTarget` varchar(50) DEFAULT NULL COMMENT 'A link célja (_blank, _self,...)',
  `linkText` varchar(50) DEFAULT NULL COMMENT 'A Link megjelenítendő szövege',
  `linkTitle` varchar(50) DEFAULT NULL,
  `linkHref` varchar(200) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parentId` (`parentId`),
  KEY `positionId` (`positionId`),
  KEY `langCode` (`langCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_element_types`
--

DROP TABLE IF EXISTS `menu_element_types`;
CREATE TABLE IF NOT EXISTS `menu_element_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_positions`
--

DROP TABLE IF EXISTS `menu_positions`;
CREATE TABLE IF NOT EXISTS `menu_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `positionName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(10) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_front`
--

DROP TABLE IF EXISTS `users_front`;
CREATE TABLE IF NOT EXISTS `users_front` (
  `fuId` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `passHash` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`fuId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_front_details`
--

DROP TABLE IF EXISTS `users_front_details`;
CREATE TABLE IF NOT EXISTS `users_front_details` (
  `recordId` int(11) NOT NULL AUTO_INCREMENT,
  `fuId` int(11) DEFAULT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `middleName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`recordId`),
  KEY `fuId` (`fuId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_menu`
--

DROP TABLE IF EXISTS `users_menu`;
CREATE TABLE IF NOT EXISTS `users_menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL,
  `title_hu` varchar(500) DEFAULT NULL,
  `title_en` varchar(500) DEFAULT NULL,
  `target` varchar(500) DEFAULT '_self',
  `link` varchar(500) DEFAULT NULL,
  `alt_hu` varchar(500) DEFAULT NULL,
  `alt_en` varchar(500) DEFAULT NULL,
  `order` int(10) DEFAULT NULL,
  `loggedin` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `_log`
--

DROP TABLE IF EXISTS `_log`;
CREATE TABLE IF NOT EXISTS `_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `actionEvent` int(10) DEFAULT NULL,
  `actionTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `actionEvent` (`actionEvent`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_log_error`
--

DROP TABLE IF EXISTS `_log_error`;
CREATE TABLE IF NOT EXISTS `_log_error` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `addedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `trace` text NOT NULL,
  `url` text NOT NULL,
  `post` text NOT NULL,
  `session` text NOT NULL,
  `get` text NOT NULL,
  `server` text NOT NULL,
  `cookie` text NOT NULL,
  `request` text NOT NULL,
  `cData` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=119 ;

-- --------------------------------------------------------

--
-- Table structure for table `_log_events`
--

DROP TABLE IF EXISTS `_log_events`;
CREATE TABLE IF NOT EXISTS `_log_events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventName` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
