-- --------------------------------------------------------
-- Host                          :127.0.0.1
-- Server version                :5.5.24-log - MySQL Community Server (GPL)
-- Server OS                     :Win32
-- HeidiSQL Verzió               :7.0.0.4286
-- Created                       :2012-12-27 06:55:00
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for tábla opengallery.art
DROP TABLE IF EXISTS `art`;
CREATE TABLE IF NOT EXISTS `art` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `artist` int(10) DEFAULT NULL,
  `title` text,
  `titleSlug` varchar(100) DEFAULT NULL,
  `yearOfProduction` smallint(6) DEFAULT NULL,
  `exactYear` tinyint(4) DEFAULT '1',
  `material` int(11) DEFAULT NULL,
  `placeOfDisplay` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `description` text,
  `sizeX` int(11) DEFAULT NULL,
  `sizeY` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artist` (`artist`),
  KEY `material` (`material`),
  KEY `placeOfDisplay` (`placeOfDisplay`),
  KEY `titleSlug` (`titleSlug`),
  KEY `FK_art_art_category` (`category`),
  CONSTRAINT `FK_art_artist` FOREIGN KEY (`artist`) REFERENCES `artist` (`id`),
  CONSTRAINT `FK_art_art_category` FOREIGN KEY (`category`) REFERENCES `art_category` (`id`),
  CONSTRAINT `FK_art_art_display` FOREIGN KEY (`placeOfDisplay`) REFERENCES `art_display` (`id`),
  CONSTRAINT `FK_art_art_material` FOREIGN KEY (`material`) REFERENCES `art_material` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.artist
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
  PRIMARY KEY (`id`),
  KEY `school` (`school`),
  KEY `profession` (`profession`),
  KEY `period` (`period`),
  CONSTRAINT `FK_artist_artist_period` FOREIGN KEY (`period`) REFERENCES `artist_period` (`id`),
  CONSTRAINT `FK_artist_artist_profession` FOREIGN KEY (`profession`) REFERENCES `artist_profession` (`id`),
  CONSTRAINT `FK_artist_artist_school` FOREIGN KEY (`school`) REFERENCES `artist_school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.artist_period
DROP TABLE IF EXISTS `artist_period`;
CREATE TABLE IF NOT EXISTS `artist_period` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `periodName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.artist_profession
DROP TABLE IF EXISTS `artist_profession`;
CREATE TABLE IF NOT EXISTS `artist_profession` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `professionName_en` varchar(100) DEFAULT NULL,
  `professionName_hu` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.artist_school
DROP TABLE IF EXISTS `artist_school`;
CREATE TABLE IF NOT EXISTS `artist_school` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `schoolName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.art_category
DROP TABLE IF EXISTS `art_category`;
CREATE TABLE IF NOT EXISTS `art_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.art_display
DROP TABLE IF EXISTS `art_display`;
CREATE TABLE IF NOT EXISTS `art_display` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `displayPlace` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.art_material
DROP TABLE IF EXISTS `art_material`;
CREATE TABLE IF NOT EXISTS `art_material` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `materialName` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.menu
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.static_content
DROP TABLE IF EXISTS `static_content`;
CREATE TABLE IF NOT EXISTS `static_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `slug` varchar(200) DEFAULT NULL,
  `title_hu` text,
  `title_en` text,
  `meta_key_hu` text,
  `meta_key_en` text,
  `meta_desc_hu` text,
  `meta_desc_en` text,
  `content_hu` text,
  `content_en` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.static_content_history
DROP TABLE IF EXISTS `static_content_history`;
CREATE TABLE IF NOT EXISTS `static_content_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `staticID` int(10) DEFAULT '0',
  `uid` int(10) DEFAULT '0',
  `prev` text,
  `eventOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staticID` (`staticID`),
  KEY `uid` (`uid`),
  CONSTRAINT
          `FK_static_content_history_static_content` FOREIGN KEY (`staticID`) REFERENCES `static_content` (`id`),
  CONSTRAINT
          `FK_static_content_history_users` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) DEFAULT NULL,
  `userPass` varchar(200) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery._log
DROP TABLE IF EXISTS `_log`;
CREATE TABLE IF NOT EXISTS `_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `actionEvent` int(10) DEFAULT NULL,
  `actionTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `actionEvent` (`actionEvent`),
  KEY `uid` (`uid`),
  CONSTRAINT `FK__log_users` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `FK__log__log_events` FOREIGN KEY (`actionEvent`) REFERENCES `_log_events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery._log_events
DROP TABLE IF EXISTS `_log_events`;
CREATE TABLE IF NOT EXISTS `_log_events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventName` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for tábla opengallery._text_replace
DROP TABLE IF EXISTS `_text_replace`;
CREATE TABLE IF NOT EXISTS `_text_replace` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `toReplace` varchar(50) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `addedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__text_replace_users` (`creator`),
  CONSTRAINT `FK__text_replace_users` FOREIGN KEY (`creator`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
