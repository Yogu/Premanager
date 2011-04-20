-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 20. April 2011 um 15:38
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `premanager`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_articles`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryID` int(10) unsigned NOT NULL,
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categoryID` (`categoryID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `6_articles`
--

INSERT INTO `6_articles` (`id`, `categoryID`, `createTime`, `editTime`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `editTimes`, `timestamp`) VALUES
(1, 2, '2010-04-01 16:14:13', '2010-04-01 16:14:13', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-04-01 16:14:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_articlesname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_articlesname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `inUse` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  KEY `articleID` (`id`),
  KEY `name` (`name`),
  KEY `languageID` (`languageID`),
  KEY `inUse` (`inUse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `6_articlesname`
--

INSERT INTO `6_articlesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 1, 'biografie', 0, 0, '2010-04-01 16:18:52'),
(2, 1, 'biography', 0, 0, '2010-04-01 16:18:52');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_articlestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_articlestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `publicRevisionID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `6_articlestranslation`
--

INSERT INTO `6_articlestranslation` (`id`, `languageID`, `name`, `title`, `publicRevisionID`, `timestamp`) VALUES
(1, 1, 'biografie', 'Biografie', 1, '2010-04-01 16:51:26'),
(1, 2, 'biography', 'Biography', 2, '2010-04-01 16:51:33');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_categories`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` int(10) unsigned NOT NULL,
  `indexArticleID` int(10) unsigned NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parentID` (`parentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `6_categories`
--

INSERT INTO `6_categories` (`id`, `parentID`, `indexArticleID`, `createTime`, `editTime`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `editTimes`, `timestamp`) VALUES
(1, 0, 0, '2010-04-01 16:09:16', '2010-04-01 16:09:16', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-04-01 16:12:10'),
(2, 0, 1, '2010-04-01 16:12:55', '2010-04-01 16:12:55', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-04-01 16:19:01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_categoriesname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_categoriesname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `inUse` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  KEY `categoryID` (`id`),
  KEY `name` (`name`),
  KEY `languageID` (`languageID`),
  KEY `inUse` (`inUse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `6_categoriesname`
--

INSERT INTO `6_categoriesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 2, 'über-uns', 0, 0, '2010-04-01 16:14:15'),
(2, 2, 'about-us', 0, 0, '2010-04-01 16:14:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_categoriestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_categoriestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `6_categoriestranslation`
--

INSERT INTO `6_categoriestranslation` (`id`, `languageID`, `name`, `title`, `timestamp`) VALUES
(0, 1, '', 'Startseite', '2010-04-01 16:12:37'),
(0, 2, '', 'Home Page', '2010-04-01 16:12:37'),
(2, 1, 'über-uns', 'Über uns', '2010-04-01 16:14:03'),
(2, 2, 'about-us', 'About Us', '2010-04-01 16:14:03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `6_revisions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:13
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `6_revisions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `articleID` int(10) unsigned NOT NULL,
  `revision` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `createTime` datetime NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `summary` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `languageID` (`languageID`),
  KEY `articleID` (`articleID`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `6_revisions`
--

INSERT INTO `6_revisions` (`id`, `articleID`, `revision`, `languageID`, `createTime`, `creatorID`, `creatorIP`, `text`, `summary`, `timestamp`) VALUES
(1, 1, 1, 1, '2010-04-01 16:41:33', 2, '127.0.0.1', 0x4a7576656e696c652053747564696f73206973742065696e652046696c6d2d437265772c20626573746568656e642061757320647265697a65686e204b696e6465726e20756e64204a7567656e646c696368656e207a7769736368656e206e65756e20756e64203136204a616872656e2e20496d204865726273742032303038204a6168726573206472656874656e207369652065696e656e2066c3bc6e667a65686e6d696ec3bc746967656e20537069656c66696c6d20756e642062656765697374657274656e2064616d69742077656974657265205363686175737069656c65722e204a616e75617220323030392066616e6420646965205072656d6965726520646573207a77656974656e2046696c6d73204461732050757a7a6c65206465722057616973656e2073746174742e204d6f6d656e74616e20617262656974657420646173205465616d20616e2064656d2046696c6d205a776569204765736963687465722c20547261752073636861752077656d212e, 0x42696f67726170687920636f706965642066726f6d20687474703a2f2f7777772e6c617374666d2e64652f6d757369632f4a7576656e696c652b53747564696f732f2b77696b69, '2010-04-01 16:45:08'),
(2, 1, 1, 2, '2010-04-01 16:47:04', 2, '127.0.0.1', 0x4a7576656e696c652053747564696f7320697320612066696c6d2063726577206f6620746869727465656e206368696c6472656e20616e64206a7576656e696c6573206265747765656e206e696e6520616e642031362079656172732e20496e20617574756d6e20323030382c20746865792070726f64756365642061206669667465656e2d6d696e757465206d6f76696520616e64206174747261636b65642066757274686572206163746f72732e20496e204a616e756172792c20323031302c2074686572652077617320746865207072656d69657265206f66207468656972207365636f6e642066696c6d2063616c6c656420224461732050757a7a6c65206465722057616973656e222e20417420746865206d6f6d656e742074686520637265772069732070726f647563696e6720225a776569204765736963687465722c20547261752073636861752077656d2122, 0x436f706965642066726f6d206e756c6c2d70726f6a6563742773206465736372697074696f6e, '2010-04-01 16:47:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_groupright`
--
-- Erzeugt am: 31. Dezember 2010 um 17:10
-- Aktualisiert am: 09. April 2011 um 00:01
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_groupright` (
  `groupID` int(10) unsigned NOT NULL,
  `rightID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`groupID`,`rightID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_groupright`
--

INSERT INTO `premanager_groupright` (`groupID`, `rightID`, `timestamp`) VALUES
(21, 24, '2010-12-31 17:26:54'),
(21, 22, '2010-12-31 17:26:54'),
(3, 32, '2011-04-09 00:01:10'),
(3, 25, '2011-04-09 00:01:10'),
(3, 29, '2011-04-09 00:01:10'),
(3, 26, '2011-04-09 00:01:10'),
(3, 21, '2011-04-09 00:01:10'),
(3, 23, '2011-04-09 00:01:10'),
(3, 22, '2011-04-09 00:01:10'),
(3, 20, '2011-04-09 00:01:10'),
(22, 22, '2010-12-31 17:26:24'),
(22, 24, '2010-12-31 17:26:24'),
(22, 26, '2010-12-31 17:26:24'),
(24, 22, '2010-12-31 17:26:39'),
(24, 24, '2010-12-31 17:26:39'),
(24, 26, '2010-12-31 17:26:39'),
(21, 26, '2010-12-31 17:26:54'),
(3, 19, '2011-04-09 00:01:10'),
(2, 28, '2011-02-08 17:01:32'),
(1, 27, '2011-02-05 16:40:37'),
(2, 27, '2011-02-08 17:01:32'),
(2, 30, '2011-02-08 17:01:32'),
(3, 18, '2011-04-09 00:01:10'),
(3, 31, '2011-04-09 00:01:10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_groups`
--
-- Erzeugt am: 18. Februar 2011 um 23:23
-- Aktualisiert am: 19. Februar 2011 um 00:23
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` int(10) unsigned NOT NULL COMMENT 'project',
  `color` char(6) COLLATE utf8_bin NOT NULL,
  `priority` int(10) unsigned NOT NULL,
  `autoJoin` tinyint(1) NOT NULL DEFAULT '0',
  `loginConfirmationRequired` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `creatorID` int(10) unsigned NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order` (`priority`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `premanager_groups`
--

INSERT INTO `premanager_groups` (`id`, `parentID`, `color`, `priority`, `autoJoin`, `loginConfirmationRequired`, `createTime`, `editTime`, `editTimes`, `creatorID`, `editorID`, `timestamp`) VALUES
(1, 0, '5C5C5C', 1, 0, 1, '2010-02-14 00:41:15', '2011-02-05 15:18:27', 5, 2, 2, '2011-02-05 16:18:27'),
(2, 0, '000000', 1, 1, 0, '2010-02-14 00:42:55', '2010-12-28 21:31:37', 23, 2, 0, '2010-12-28 22:31:37'),
(3, 0, '006600', 10, 0, 1, '2010-03-05 23:55:33', '2011-01-02 14:55:44', 3, 2, 0, '2011-01-02 15:55:44'),
(26, 0, '64002E', 5, 0, 0, '2010-12-29 23:47:46', '2011-01-21 22:17:59', 3, 0, 2, '2011-01-21 23:17:59'),
(23, 117, '64002E', 0, 0, 0, '2010-12-29 23:41:30', '2011-01-21 22:19:24', 1, 0, 2, '2011-01-21 23:19:24'),
(24, 117, '006600', 0, 0, 0, '2010-12-29 23:42:10', '2010-12-29 23:42:10', 0, 0, 0, '2010-12-30 00:42:10'),
(25, 118, '64002E', 0, 0, 0, '2010-12-29 23:43:12', '2011-01-21 22:19:10', 1, 0, 2, '2011-01-21 23:19:10'),
(22, 118, '006000', 0, 0, 0, '2010-12-28 14:51:13', '2010-12-29 23:42:35', 1, 0, 0, '2010-12-30 00:42:35'),
(21, 17, '006600', 0, 0, 0, '2010-12-28 14:50:27', '2010-12-28 14:50:27', 0, 0, 0, '2010-12-28 15:50:27'),
(20, 17, '64002E', 0, 0, 0, '2010-12-28 14:47:22', '2011-01-21 22:19:37', 1, 0, 2, '2011-01-21 23:19:37'),
(27, 17, '1F44FF', 0, 0, 0, '2011-01-02 20:00:37', '2011-01-21 22:20:11', 1, 79, 2, '2011-01-21 23:20:11');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_groupsname`
--
-- Erzeugt am: 28. Dezember 2010 um 13:42
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_groupsname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `inUse` int(10) unsigned NOT NULL DEFAULT '1',
  `languageID` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  KEY `groupID` (`id`),
  KEY `inUse` (`inUse`),
  KEY `languageID` (`languageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=37 ;

--
-- Daten für Tabelle `premanager_groupsname`
--

INSERT INTO `premanager_groupsname` (`nameID`, `id`, `name`, `inUse`, `languageID`, `timestamp`) VALUES
(1, 1, 'gäste', 1, 1, '2010-04-24 01:16:35'),
(2, 1, 'guests', 1, 2, '2010-04-24 01:16:35'),
(3, 2, 'registrierte benutzer', 1, 1, '2010-04-24 01:18:25'),
(4, 2, 'registered users', 1, 2, '2010-04-24 01:18:25'),
(5, 3, 'administratoren', 1, 1, '2010-04-24 01:17:18'),
(6, 3, 'administrators', 1, 2, '2010-04-24 01:17:18'),
(9, 1, 'invités', 1, 3, '2010-04-24 01:16:35'),
(10, 3, 'administrateurs', 1, 3, '2010-04-24 01:17:18'),
(11, 2, 'utilisateurs inscrits', 1, 3, '2010-04-24 01:18:25'),
(33, 26, 'projektmitglieder', 1, 1, '2010-12-30 00:47:46'),
(32, 25, 'projektmitglieder', 1, 1, '2010-12-30 00:43:12'),
(29, 22, 'projektleiter', 1, 1, '2010-12-28 15:51:13'),
(30, 23, 'projektmitglieder', 1, 1, '2010-12-30 00:41:30'),
(31, 24, 'projektleiter', 1, 1, '2010-12-30 00:42:10'),
(28, 21, 'projektleiter', 1, 1, '2010-12-28 15:50:27'),
(27, 20, 'projektmitglieder', 1, 1, '2010-12-28 15:47:22'),
(34, 27, 'schauspieler', 1, 1, '2011-01-02 21:00:37'),
(35, 20, 'project members', 1, 2, '2011-01-21 23:19:37'),
(36, 27, 'actors', 1, 2, '2011-01-21 23:20:11');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_groupstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_groupstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_groupstranslation`
--

INSERT INTO `premanager_groupstranslation` (`id`, `languageID`, `name`, `text`, `title`, `timestamp`) VALUES
(1, 1, 'Gäste', 0x47c3a47374652073696e64206e6963687420616e67656d656c6465746520426573756368657220646965736572205365697465, 'Gast', '2010-02-14 20:08:42'),
(1, 2, 'Guests', 0x477565737473206172652076697369746f72732077686f2068617665206e6f74206c6f6767656420696e, 'Guest', '2010-03-08 21:07:15'),
(2, 1, 'Registrierte Benutzer', 0x44696573652047727570706520666173737420616c6c65204d6974676c6965646572207a7573616d6d656e2c20646965207369636820726567697374726965727420686162656e20756e64207369636820736f6d6974206d697420696872656d2067656865696d656e2050617373776f727420616e6d656c64656e206bc3b66e6e656e2e20446965204d6974676c69656473636861667420696e2064696573657220477275707065207665726c65696874206b65696e65206265736f6e646572656e205265636874652e204c656469676c6963682046756e6b74696f6e656e2c20646965206461732042656e75747a65726b6f6e746f2073656c6273742062657472656666656e2c2077657264656e206461647572636820667265696765736368616c7465742e, 'Registrierter Benutzer', '2010-02-14 20:10:16'),
(2, 2, 'Registered Users', 0x546869732067726f75707320636f7665727320616c6c20726567697374657265642076697369746f72732077686f2063616e206c6f6720696e2077697468207468656972207365637265742070617373776f72642e20546865206d656d62657273686970206f6620746869732067726f757020646f6573206e6f74206772616e7420616e79207370656369616c20726967687473206578636570742074686520726967687420746f206d616e616765207468656972206f776e206163636f756e742e, 'Registered User', '2010-02-14 20:12:48'),
(3, 1, 'Administratoren', 0x41646d696e6973747261746f72656e20736f7267656e2066c3bc72206469652056657266c3bc676261726b65697420756e642046756e6b74696f6e7374c3bc63687469676b656974206465722057656273697465, 'Administrator', '2010-03-05 23:57:11'),
(3, 2, 'Administrators', 0x41646d696e6973747261746f7273206d616b65207468697320776562207369746520776f726b, 'Administrator', '2010-03-05 23:57:11'),
(1, 3, 'Invités', 0x496e766974c3a97320736f6e74206465732076697369746575727320717569206e6520736f6e742070617320636f6e6e656374c3a92e, 'Invité', '2010-04-24 15:10:30'),
(3, 3, 'Administrateurs', 0x41646d696e697374726174657572732073276f636375706520646520636520776562736974652e, 'Administrateur', '2010-04-24 01:17:18'),
(2, 3, 'Utilisateurs inscrits', 0x546869732067726f75707320636f7665727320616c6c20726567697374657265642076697369746f72732077686f2063616e206c6f6720696e2077697468207468656972207365637265742070617373776f72642e20546865206d656d62657273686970206f6620746869732067726f757020646f6573206e6f74206772616e7420616e79207370656369616c20726967687473206578636570742074686520726967687420746f206d616e616765207468656972206f776e206163636f756e742e, 'Utilisateur inscrit', '2010-04-24 01:18:25'),
(26, 1, 'Projektmitglieder', 0x416c6c652042656e75747a65722c2064696520616e2065696e656d204a7576656e696c652d53747564696f732d50726f6a656b74206d697467657769726b7420686162656e2c2073696e6420696e20646965736572204772757070652076657265696e742e, 'Projektmitglied', '2010-12-30 00:47:46'),
(23, 1, 'Projektmitglieder', 0x416c6c65204461727374656c6c65722c2048696e7465726772756e646c6575746520756e6420526567697373657572652073696e6420696e20646965736572204772757070652076657265696e742e, 'Projektmitglied', '2010-12-30 00:41:30'),
(24, 1, 'Projektleiter', 0x4469652050726f6a656b746c6569746572206f7267616e6973696572656e206461732046696c6d70726f6a656b742e, 'Projektleiter', '2010-12-30 00:42:10'),
(25, 1, 'Projektmitglieder', 0x416c6c65204461727374656c6c65722c2048696e7465726772756e646c6575746520756e6420526567697373657572652073696e6420696e20646965736572204772757070652076657265696e742e, 'Projektmitglied', '2010-12-30 00:43:12'),
(27, 1, 'Schauspieler', 0x416c6c652c2064696520696d2046696c6d20225a77656920476573696368746572222065696e65205363686175737069656c6572726f6c6c652065696e67656e6f6d6d656e20686162656e, 'Schauspieler', '2011-01-02 21:00:37'),
(22, 1, 'Projektleiter', 0x4469652050726f6a656b746c6569746572206f7267616e6973696572656e206461732046696c6d70726f6a656b74, 'Projektleiter', '2010-12-30 00:42:35'),
(21, 1, 'Projektleiter', 0x4469652050726f6a656b746c6569746572206f7267616e6973696572656e206461732046696c6d70726f6a656b742e, 'Projektleiter', '2010-12-28 15:50:27'),
(20, 1, 'Projektmitglieder', 0x416c6c65204461727374656c6c65722c2048696e7465726772756e646c6575746520756e6420526567697373657572652073696e6420696e20646965736572204772757070652076657265696e742e, 'Projektmitglied', '2010-12-28 15:47:22'),
(26, 2, 'Project Members', 0x416c6c20686176696e6720776f726b6564206f6e2061204a7576656e696c652053747564696f2070726f6a656374, 'Project Member', '2011-01-21 23:17:25'),
(25, 2, 'Project Members', 0x416c6c206163746f72732c206261636b67726f756e642070656f706c6520616e64206469726563746f7273, 'Project Member', '2011-01-21 23:19:10'),
(23, 2, 'Project Members', 0x416c6c206163746f72732c206261636b67726f756e642070656f706c6520616e64206469726563746f7273, 'Project Member', '2011-01-21 23:19:24'),
(20, 2, 'Project Members', 0x416c6c206163746f72732c206261636b67726f756e642070656f706c6520616e64206469726563746f7273, 'Project Member', '2011-01-21 23:19:37'),
(27, 2, 'Actors', 0x54686f73652077686f20686176652068616420616e206163746f7220726f6c6520696e207468652066696c6d20225a7765692047657369636874657222, 'Actor', '2011-01-21 23:20:11');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_languages`
--
-- Erzeugt am: 18. Februar 2011 um 23:24
-- Aktualisiert am: 19. Februar 2011 um 00:24
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `englishTitle` varchar(255) COLLATE utf8_bin NOT NULL,
  `isDefault` tinyint(1) NOT NULL DEFAULT '0',
  `isInternational` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `creatorID` int(10) unsigned NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `shortDateFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Y-m-d',
  `shortTimeFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'H:i',
  `longDateFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'j F Y',
  `longTimeFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'H:i',
  `dateTimePhraseFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Y-m-d H:i',
  `order` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`name`),
  KEY `isInternational` (`isInternational`),
  KEY `order` (`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `premanager_languages`
--

INSERT INTO `premanager_languages` (`id`, `name`, `title`, `englishTitle`, `isDefault`, `isInternational`, `createTime`, `editTime`, `editTimes`, `creatorID`, `editorID`, `shortDateFormat`, `shortTimeFormat`, `longDateFormat`, `longTimeFormat`, `dateTimePhraseFormat`, `order`, `timestamp`) VALUES
(1, 'de', 'Deutsch', 'German', 1, 0, '2010-02-13 18:23:49', '2010-02-13 18:23:49', 0, 0, 0, 'j.m.Y', 'H:i', '|l, j. F Y|', 'H:i', '|~\\a\\m l, j. F Y, | \\u\\m H:i \\U\\h\\r', 0, '2011-02-16 18:30:55'),
(2, 'en', 'English', 'English', 0, 1, '2010-02-13 21:57:46', '2010-02-13 21:57:46', 0, 2, 2, 'm/d/Y', 'H:i', '|F j, Y|', 'h:i a', '|~\\o\\n l, F jS, Y \\a\\t H:i', 1, '2011-02-16 18:33:49'),
(3, 'fr', 'Français', 'French', 0, 0, '2010-04-07 23:08:47', '2010-04-07 23:08:47', 0, 2, 2, 'j-m-Y H:i', 'H:i', 'l j F Y', 'H:i', 'Y-m-d H:i', 2, '2011-02-16 18:20:32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_log`
--
-- Erzeugt am: 10. Januar 2011 um 19:31
-- Aktualisiert am: 10. Januar 2011 um 20:31
--

CREATE TABLE IF NOT EXISTS `premanager_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `referer` varchar(255) COLLATE utf8_bin NOT NULL,
  `userAgent` text COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `type` enum('error','warning','redirection') COLLATE utf8_bin NOT NULL,
  `createTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creatorID` int(10) unsigned NOT NULL DEFAULT '0',
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `createTime` (`createTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `premanager_log`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_markuplanguages`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 20:10
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_markuplanguages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `premanager_markuplanguages`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_markuplanguagestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_markuplanguagestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_markuplanguagestranslation`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_nodegroup`
--
-- Erzeugt am: 21. Januar 2011 um 21:36
-- Aktualisiert am: 21. Januar 2011 um 23:32
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_nodegroup` (
  `nodeID` int(10) unsigned NOT NULL,
  `groupID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nodeID`,`groupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_nodegroup`
--

INSERT INTO `premanager_nodegroup` (`nodeID`, `groupID`, `timestamp`) VALUES
(92, 3, '2011-01-21 21:52:44'),
(94, 2, '2011-01-21 22:20:03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_nodes`
--
-- Erzeugt am: 18. Februar 2011 um 23:24
-- Aktualisiert am: 02. April 2011 um 23:49
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` int(10) unsigned NOT NULL,
  `projectID` int(10) unsigned NOT NULL,
  `treeID` int(10) unsigned NOT NULL DEFAULT '0',
  `noAccessRestriction` tinyint(1) NOT NULL DEFAULT '1',
  `hasPanel` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editTimes` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parentID` (`parentID`),
  KEY `projectID` (`projectID`),
  KEY `treeID` (`treeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=161 ;

--
-- Daten für Tabelle `premanager_nodes`
--

INSERT INTO `premanager_nodes` (`id`, `parentID`, `projectID`, `treeID`, `noAccessRestriction`, `hasPanel`, `createTime`, `editTime`, `creatorID`, `editorID`, `editTimes`, `timestamp`) VALUES
(90, 94, 0, 24, 1, 0, '2010-06-09 20:21:34', '2011-02-08 16:57:24', 2, 2, 5, '2011-02-08 18:11:00'),
(89, 93, 0, 21, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:16:05', 2, 2, 3, '2011-01-28 21:13:41'),
(88, 92, 0, 19, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:14:28', 2, 2, 2, '2011-01-21 23:14:28'),
(83, 93, 0, 4, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:15:14', 2, 2, 2, '2011-01-21 23:15:14'),
(82, 93, 0, 18, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:15:56', 2, 2, 8, '2011-01-21 23:15:56'),
(81, 93, 0, 15, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:16:39', 2, 2, 2, '2011-01-21 23:16:39'),
(80, 93, 0, 1, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:15:22', 2, 2, 2, '2011-01-21 23:15:22'),
(79, 0, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:21:34', 2, 2, 0, '2010-09-18 00:52:36'),
(84, 93, 0, 22, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:15:33', 2, 2, 2, '2011-02-05 18:04:51'),
(92, 79, 0, 0, 0, 0, '2010-06-09 20:30:08', '2011-01-21 20:52:44', 2, 2, 13, '2011-01-21 21:52:44'),
(93, 79, 0, 0, 1, 0, '2010-06-09 20:30:44', '2011-01-21 22:15:07', 2, 2, 15, '2011-01-21 23:15:07'),
(94, 79, 0, 0, 0, 0, '2010-06-09 20:31:46', '2011-01-21 22:14:44', 2, 2, 9, '2011-01-21 23:14:44'),
(95, 0, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-09 20:34:29', 2, 2, 0, '2010-06-09 22:34:53'),
(91, 92, 0, 16, 1, 0, '2010-06-09 20:21:34', '2011-01-21 22:02:54', 2, 2, 2, '2011-01-21 23:02:54'),
(111, 94, 0, 26, 1, 0, '2010-06-26 19:07:54', '2010-06-26 19:07:54', 2, 2, 0, '2011-02-18 22:27:09'),
(144, 0, 118, 0, 1, 0, '2010-11-14 20:51:53', '2010-11-14 20:51:53', 0, 0, 0, '2010-11-14 21:51:53'),
(139, 0, 117, 0, 1, 0, '2010-11-14 20:06:32', '2010-11-14 20:06:32', 0, 0, 0, '2010-11-14 21:06:32'),
(156, 92, 0, 20, 1, 0, '2011-01-24 17:26:08', '2011-02-08 15:20:24', 2, 2, 1, '2011-02-08 16:20:24'),
(157, 94, 0, 23, 1, 0, '2011-02-08 15:19:24', '2011-02-08 15:20:13', 2, 2, 1, '2011-02-08 16:21:24'),
(158, 94, 0, 25, 1, 0, '2011-02-08 16:57:37', '2011-02-08 16:57:37', 2, 2, 0, '2011-02-16 21:10:55'),
(159, 94, 0, 28, 1, 0, '2011-03-09 14:19:17', '2011-04-02 21:34:56', 2, 2, 1, '2011-04-02 23:34:56'),
(160, 92, 0, 27, 1, 0, '2011-03-09 14:21:42', '2011-03-09 14:21:42', 2, 2, 0, '2011-03-09 15:23:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_nodesname`
--
-- Erzeugt am: 14. November 2010 um 22:10
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_nodesname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `inUse` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  KEY `isInUse` (`inUse`),
  KEY `languageID` (`languageID`),
  KEY `name` (`name`),
  KEY `nodeID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=65 ;

--
-- Daten für Tabelle `premanager_nodesname`
--

INSERT INTO `premanager_nodesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(43, 93, 'mitglieder', 1, 1, '2011-01-15 00:54:35'),
(2, 91, 'projekte', 1, 1, '2010-11-14 22:12:08'),
(3, 89, 'registrierung', 1, 1, '2010-11-14 22:12:08'),
(47, 90, 'login-data', 2, 0, '2011-02-08 16:58:34'),
(5, 92, 'admin', 1, 1, '2011-01-09 15:44:44'),
(46, 94, 'my-account', 2, 1, '2011-01-21 23:14:44'),
(8, 88, 'struktur', 1, 1, '2010-11-14 22:15:43'),
(10, 84, 'passwort-vergessen', 1, 1, '2010-11-14 22:12:08'),
(11, 82, 'wer-ist-online', 1, 1, '2010-11-14 22:14:26'),
(12, 83, 'anmeldung', 1, 1, '2010-11-14 22:14:26'),
(13, 81, 'gruppen', 1, 1, '2010-11-14 22:14:26'),
(14, 80, 'benutzer', 1, 1, '2010-11-14 22:14:26'),
(15, 111, 'avatar', 1, 1, '2010-11-14 22:14:26'),
(45, 88, 'structure', 2, 1, '2011-01-21 23:14:28'),
(44, 91, 'projects', 2, 1, '2011-01-21 23:02:54'),
(30, 94, 'mein-konto', 1, 1, '2011-01-14 22:39:32'),
(35, 90, 'anmeldungsdaten', 1, 0, '2011-02-08 16:58:50'),
(48, 93, 'members', 2, 1, '2011-01-21 23:15:07'),
(49, 83, 'login', 2, 1, '2011-01-21 23:15:14'),
(50, 80, 'users', 2, 1, '2011-01-21 23:15:22'),
(51, 84, 'password-lost', 2, 1, '2011-01-21 23:15:33'),
(52, 89, 'register', 2, 1, '2011-01-21 23:15:41'),
(53, 82, 'who-is-online', 2, 1, '2011-01-21 23:15:56'),
(54, 81, 'groups', 2, 1, '2011-01-21 23:16:39'),
(55, 156, 'stile', 1, 1, '2011-01-24 18:26:08'),
(56, 157, 'passwort-ändern', 1, 1, '2011-02-08 16:19:24'),
(57, 157, 'change-password', 2, 1, '2011-02-08 16:20:13'),
(58, 156, 'styles', 2, 1, '2011-02-08 16:20:24'),
(59, 90, 'edit-account', 2, 1, '2011-02-08 16:58:34'),
(60, 90, 'registrierungsdaten', 1, 0, '2011-02-08 17:57:24'),
(61, 90, 'e-mail-ändern', 1, 1, '2011-02-08 17:57:24'),
(62, 158, 'benutzernamen-ändern', 1, 1, '2011-02-08 17:57:37'),
(63, 160, 'sidebar', 1, 1, '2011-03-09 15:21:42'),
(64, 159, 'sidebar', 1, 1, '2011-04-02 23:34:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_nodestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_nodestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_nodestranslation`
--

INSERT INTO `premanager_nodestranslation` (`id`, `languageID`, `name`, `title`, `timestamp`) VALUES
(93, 1, 'mitglieder', 'Mitglieder', '2010-06-09 22:31:08'),
(91, 1, 'projekte', 'Projekte', '2010-06-09 22:29:34'),
(89, 1, 'registrierung', 'Registrierung', '2010-06-09 22:29:39'),
(90, 1, 'e-mail-ändern', 'E-Mail-Adresse ändern', '2011-02-08 17:57:24'),
(92, 1, 'admin', 'Administration', '2011-01-09 15:44:44'),
(96, 1, 'benutzer', 'Benutzerliste', '2010-06-12 00:25:15'),
(95, 1, '', 'Startseite', '2010-06-09 22:34:53'),
(88, 1, 'struktur', 'Struktur', '2010-06-09 22:29:51'),
(94, 1, 'mein-konto', 'Mein Konto', '2011-01-09 15:48:06'),
(84, 1, 'passwort-vergessen', 'Passwort vergessen', '2010-06-09 22:29:29'),
(82, 1, 'wer-ist-online', 'Wer ist online?', '2010-06-09 22:30:07'),
(83, 1, 'anmeldung', 'Anmeldung', '2010-06-09 22:29:22'),
(81, 1, 'gruppen', 'Gruppen', '2010-06-09 22:29:15'),
(80, 1, 'benutzer', 'Benutzerliste', '2010-06-09 22:29:58'),
(144, 1, '', 'Startseite', '2010-11-22 20:32:58'),
(139, 1, '', 'Startseite', '2010-11-22 20:33:07'),
(79, 1, '', 'Startseite', '2010-11-22 20:32:58'),
(111, 1, 'avatar', 'Avatar', '2010-06-26 21:08:18'),
(88, 2, 'structure', 'Structure', '2011-01-21 23:14:28'),
(91, 2, 'projects', 'Projects', '2011-01-21 23:02:54'),
(94, 2, 'my-account', 'My Account', '2011-01-21 23:14:44'),
(90, 2, 'edit-account', 'Edit Account', '2011-02-08 16:58:34'),
(93, 2, 'members', 'Members', '2011-01-21 23:15:07'),
(83, 2, 'login', 'Login', '2011-01-21 23:15:14'),
(80, 2, 'users', 'Users', '2011-01-21 23:15:22'),
(84, 2, 'password-lost', 'Password Lost', '2011-01-21 23:15:33'),
(89, 2, 'register', 'Register', '2011-01-21 23:16:05'),
(82, 2, 'who-is-online', 'Who is online?', '2011-01-21 23:15:56'),
(81, 2, 'groups', 'Groups', '2011-01-21 23:16:39'),
(156, 1, 'stile', 'Stile', '2011-01-24 18:26:08'),
(157, 1, 'passwort-ändern', 'Passwort ändern', '2011-02-08 16:19:24'),
(157, 2, 'change-password', 'Change Password', '2011-02-08 16:20:13'),
(156, 2, 'styles', 'Styles', '2011-02-08 16:20:24'),
(158, 1, 'benutzernamen-ändern', 'Benutzernamen ändern', '2011-02-08 17:57:37'),
(159, 1, 'sidebar', 'Sidebar', '2011-03-09 15:19:17'),
(160, 1, 'sidebar', 'Sidebar', '2011-03-09 15:21:42');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_options`
--
-- Erzeugt am: 18. Februar 2011 um 23:24
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `type` enum('int','bool','string') COLLATE utf8_bin NOT NULL,
  `minValue` int(11) DEFAULT NULL,
  `maxValue` int(11) DEFAULT NULL,
  `defaultValue` text COLLATE utf8_bin NOT NULL,
  `globalValue` text COLLATE utf8_bin,
  `projectsCanOverwrite` bit(1) NOT NULL DEFAULT b'0',
  `usersCanOverwrite` bit(1) NOT NULL DEFAULT b'0',
  `projectMinValue` int(11) DEFAULT NULL,
  `projectMaxValue` int(11) DEFAULT NULL,
  `userMinValue` int(11) DEFAULT NULL,
  `userMaxValue` int(11) DEFAULT NULL,
  `editTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `editorID` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=14 ;

--
-- Daten für Tabelle `premanager_options`
--

INSERT INTO `premanager_options` (`id`, `pluginID`, `name`, `type`, `minValue`, `maxValue`, `defaultValue`, `globalValue`, `projectsCanOverwrite`, `usersCanOverwrite`, `projectMinValue`, `projectMaxValue`, `userMinValue`, `userMaxValue`, `editTime`, `editTimes`, `editorID`, `timestamp`) VALUES
(1, 0, 'session.length', 'int', 0, NULL, 0x33363030, 0x33363030, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 17:53:54'),
(2, 0, 'cookie.prefix', 'string', NULL, NULL, 0x7072656d616e616765725f, NULL, '0', '0', NULL, NULL, NULL, NULL, '2010-02-15 22:13:30', 0, 2, '2011-02-16 17:53:47'),
(13, 0, 'page-tree.max-child-count', 'int', 1, NULL, 0x35, 0x35, '0', '0', NULL, NULL, NULL, NULL, '2011-04-01 23:10:29', 0, 2, '2011-04-01 23:10:53'),
(5, 0, 'list.items-per-page', 'int', 1, NULL, 0x3230, 0x3230, '0', '1', NULL, NULL, NULL, 100, '2010-03-06 13:59:28', 0, 2, '2011-02-16 17:53:40'),
(6, 0, 'email.from-address', 'string', NULL, NULL, 0x74657374406578616d706c652e6f7267, 0x696e666f40796f67756c61726d2e6465, '0', '0', NULL, NULL, NULL, NULL, '2010-05-07 19:20:20', 0, 2, '2011-02-16 17:53:31'),
(7, 0, 'reset-password.expiration-time', 'int', 0, NULL, 0x313732383030, 0x313732383030, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 17:51:00'),
(8, 0, 'avatar.max-width', 'int', 1, NULL, 0x3830, 0x3830, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 17:52:38'),
(9, 0, 'avatar.max-height', 'int', 1, NULL, 0x3830, 0x3830, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 17:52:18'),
(10, 0, 'login-confirmation.length', 'int', 1, NULL, 0x363030, 0x363030, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 17:52:08'),
(11, 0, 'unconfirmed-email.expiration-time', 'int', 0, NULL, 0x3836343030, 0x3836343030, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 18:35:48'),
(12, 0, 'viewonline.max-session-age', 'int', 0, NULL, 0x333030, 0x333030, '0', '0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '2011-02-16 20:28:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_panelobjects`
--
-- Erzeugt am: 11. März 2011 um 22:11
-- Aktualisiert am: 11. März 2011 um 23:11
--

CREATE TABLE IF NOT EXISTS `premanager_panelobjects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nodeID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned DEFAULT NULL,
  `widgetID` int(10) unsigned NOT NULL,
  `group` int(11) NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `isMinimized` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `nodeID` (`nodeID`),
  KEY `userID` (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `premanager_panelobjects`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_plugins`
--
-- Erzeugt am: 03. November 2010 um 12:51
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `initializerClass` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `backendTreeClass` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `premanager_plugins`
--

INSERT INTO `premanager_plugins` (`id`, `name`, `initializerClass`, `backendTreeClass`, `timestamp`) VALUES
(0, 'Premanager', 'Premanager\\Initializer', 'Premanager\\Pages\\Backend\\BackendPage', '2011-02-18 21:46:03'),
(2, 'Blog', '', '', '2010-02-17 21:30:13'),
(7, 'Premanager.Widgets', 'Premanager\\Widgets\\Initializer', '', '2011-03-11 22:03:48'),
(6, 'Wiki', '', '', '2010-04-01 15:51:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_projectoptions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
--

CREATE TABLE IF NOT EXISTS `premanager_projectoptions` (
  `optionID` int(10) unsigned NOT NULL,
  `projectID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`optionID`,`projectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_projectoptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_projects`
--
-- Erzeugt am: 18. Februar 2011 um 23:25
-- Aktualisiert am: 19. Februar 2011 um 00:25
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=127 ;

--
-- Daten für Tabelle `premanager_projects`
--

INSERT INTO `premanager_projects` (`id`, `name`, `createTime`, `editTime`, `editTimes`, `creatorID`, `editorID`, `timestamp`) VALUES
(17, 'zwei-gesichter', '2010-06-09 20:34:29', '2011-01-28 19:53:26', 6, 2, 2, '2011-01-28 20:53:26'),
(0, '', '2010-11-22 17:04:27', '2011-01-28 19:53:49', 2, 0, 2, '2011-01-28 20:53:49'),
(118, 'das-puzzle-der-waisen', '2010-11-14 20:51:53', '2011-01-28 19:53:40', 4, 0, 2, '2011-01-28 20:53:40'),
(117, 'nightmare', '2010-11-14 20:06:32', '2011-01-28 19:53:33', 6, 0, 2, '2011-01-28 20:53:33');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_projectsname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 28. Januar 2011 um 21:52
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_projectsname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `inUse` tinyint(1) NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  KEY `projectID` (`id`),
  KEY `name` (`name`),
  KEY `inUse` (`inUse`),
  KEY `languageID` (`languageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=50 ;

--
-- Daten für Tabelle `premanager_projectsname`
--

INSERT INTO `premanager_projectsname` (`nameID`, `id`, `name`, `inUse`, `languageID`, `timestamp`) VALUES
(21, 17, 'zwei-gesichter', 1, 1, '2010-11-14 01:45:03'),
(40, 118, 'daspuzzlederwaisen', 0, 1, '2010-11-15 22:46:54'),
(39, 117, 'nightmare-ein-albtraum-wird-wahr', 0, 1, '2010-11-15 22:46:38'),
(38, 118, 'das-puzzle-der-waisen', 1, 1, '2010-11-15 22:46:54'),
(37, 117, 'nightmare', 1, 1, '2010-11-15 22:46:38'),
(49, 0, '', 1, 1, '2011-01-28 20:52:29');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_projectstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 09. März 2011 um 16:00
-- Letzter Check am: 09. März 2011 um 15:00
--

CREATE TABLE IF NOT EXISTS `premanager_projectstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `subTitle` varchar(255) COLLATE utf8_bin NOT NULL,
  `author` varchar(255) COLLATE utf8_bin NOT NULL,
  `copyright` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `keywords` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_projectstranslation`
--

INSERT INTO `premanager_projectstranslation` (`id`, `languageID`, `title`, `subTitle`, `author`, `copyright`, `description`, `keywords`, `timestamp`) VALUES
(17, 1, 'Zwei Gesichter', 'Trau, schau wem!', 'Yogu', '© Yogu, 2009-2010', 0x416e6472656a20777572646520656e7466c3bc687274212045696e20616e6f6e796d657320426577656973766964656f2066c3bc68727420646965207a77656920446574656b746976696e6e656e204b61746a6120756e64204c656e61206175662065696e6520686569c39f6520537075722e204f62776f686c207369652064656e206b696e6469736368656e20416e676562657220c3bc6265726861757074206e69636874206c656964656e206bc3b66e6e656e2c20626c656962742069686e656e206e696368747320616e646572657320c3bc627269672c20616c732064696573656e204b6f6e666c696b74207a7520626567726162656e20756e64206e61636820776569746572656e2048696e77656973656e207a75206661686e64656e2e20446f63682064616e6e206c65726e656e2073696520416e6472656a73207a7765697465732047657369636874206b656e6e656e2e2e2e, 0x4b72696d692c20446574656b746976652c204b6f6e666c696b74652c2053747265696368, '2011-01-28 20:53:26'),
(0, 1, 'Juvenile Studios', '', 'Yogu', '© Yogu, 2007-2010', 0x45696e652047727570706520766f6e206a7567656e646c696368656e2046696c6d656d61636865726e20696d20426f747477617274616c, 0x53747564696f2c2046696c6d656d61636865722c204a7567656e646c6963682c204b696e64, '2011-01-28 20:53:49'),
(118, 1, 'Das Puzzle der Waisen', '', 'Yogu', '© und Yogu, 2007-2010', 0x4e65756e2057616973656e2c20696e207a7765692073696368206b6f6e6b7572c2ad72696572656e64656e2053747261c39f656e62616e64656e2c206765726174656e20696e2065696e656e2044696562737461686c2065696e65732076657274766f6c6c656e204d6573c2ad7365727320756e642065696e65732042616e6b7363686cc3bc7373656c732e20476c65696368c2ad7a65697469672073696e642065696e204a756e6765206465722065696e656e2042616e64652c204d617274696e2c20756e642065696e204dc3a4646368656e2064657220616e646572656e2c204c617572612c206d697465696e616e646572206265667265756e6465742c206bc3b66ec2ad6e656e20616265722064757263682064696520526976616c6974c3a474656e2064657220626569c2ad64656e2042616e64656e206e696368742072696368746967207a7565696e616e6465722066696e64656e2e2045696e207370616e6e656e646573204162656e7465756572206d6974207669656c656e20566572666f6c67756e67656e20756e6420537472656974656e20626567696e6e74202e2e2e, 0x57616973656e2c2053747261c39f656e62616e64656e2c2042616e64656e2c2052616368652c20467265756e64736368616674, '2011-01-28 20:53:40'),
(117, 1, 'Nightmare', 'Ein Albtraum wird wahr', 'Yogu', '© Yogu, 2007-2010', 0x54696e652054726569626d65696572206b616e6e206573206e696368742066617373656e3a204a656d616e642069737420696e20696872205a696d6d65722065696e676562726f6368656e20756e642068617420646965204564656c737465696e652067656b6c6175742c20646965207369652074616773207a75766f7220676566756e64656e206861742e20576572206973742064657220446965623f205a7573616d6d656e206d697420696872656e2047657363687769737465726e2044656e6e696520756e6420436861726c6965206d616368742073696520736963682061756620646965205375636865202e2e2e, 0x4564656c737465696e652c20446965622c204b75727a66696c6d, '2011-01-28 20:53:33');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_rights`
--
-- Erzeugt am: 31. Dezember 2010 um 13:42
-- Aktualisiert am: 08. April 2011 um 23:50
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `scope` enum('organization','projects','both') COLLATE utf8_bin NOT NULL DEFAULT 'both',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`),
  KEY `scope` (`scope`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=33 ;

--
-- Daten für Tabelle `premanager_rights`
--

INSERT INTO `premanager_rights` (`id`, `pluginID`, `name`, `scope`, `timestamp`) VALUES
(21, 0, 'manageProjects', 'organization', '2010-12-31 13:42:53'),
(20, 0, 'editUsers', 'organization', '2010-12-31 13:42:53'),
(12, 2, 'createArticles', 'both', '2010-06-11 14:39:53'),
(13, 2, 'editArticles', 'both', '2010-06-11 15:52:31'),
(14, 2, 'deleteArticles', 'both', '2010-06-11 15:52:31'),
(15, 2, 'publishRevisions', 'both', '2010-06-11 15:52:31'),
(19, 0, 'deleteUsers', 'organization', '2010-12-31 13:42:53'),
(18, 0, 'createUsers', 'organization', '2010-12-31 13:42:53'),
(22, 0, 'manageGroups', 'both', '2010-12-31 13:44:10'),
(23, 0, 'manageGroupMemberships', 'both', '2010-12-31 13:44:10'),
(24, 0, 'manageGroupMembershipsOfProjectMembers', 'projects', '2010-12-31 13:44:10'),
(25, 0, 'structureAdmin', 'organization', '2010-12-31 13:44:10'),
(26, 0, 'manageRights', 'both', '2010-12-31 13:44:10'),
(27, 0, 'register', 'organization', '2011-01-02 17:30:17'),
(28, 0, 'registerWithoutEmail', 'organization', '2011-01-02 17:30:17'),
(29, 0, 'manageStyles', 'organization', '2011-01-24 18:39:17'),
(30, 0, 'changeUserName', 'organization', '2011-02-08 17:00:35'),
(31, 7, 'editDefaultSidebar', 'organization', '2011-03-31 20:52:55'),
(32, 7, 'editUserSidebars', 'organization', '2011-04-08 23:30:52');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_rightstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 08. April 2011 um 23:50
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_rightstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_rightstranslation`
--

INSERT INTO `premanager_rightstranslation` (`id`, `languageID`, `title`, `description`, `timestamp`) VALUES
(28, 1, 'Registrieren (ohne E-Mail-Adresse)', 0x45726c617562742065732c2065696e206e657565732042656e75747a65726b6f6e746f207a752065727374656c6c656e2c206f686e652065696e20452d4d61696c2d4164726573736520616e676562656e207a75206dc3bc7373656e, '2011-01-02 17:31:47'),
(27, 1, 'Registrieren', 0x45726c617562742065732c2065696e206e657565732042656e75747a65726b6f6e746f207a752065727374656c6c656e2c20646173206d69742065696e65722067c3bc6c746967656e20452d4d61696c2d4164726573736520667265696765736368616c7465742077657264656e206d757373, '2011-01-02 17:31:47'),
(26, 1, 'Rechte verwalten', 0x45726c617562742065732c20526563687465207a7520766572676562656e20756e64207a7520656e747a696568656e, '2010-12-31 14:31:39'),
(25, 1, 'Struktur verwalten', 0x45726c617562742065732c206469652053656974656e737472756b7475722065696e65732050726f6a656b747320627a772e20646572204f7267616e69736174696f6e207a75206265617262656974656e, '2010-12-31 14:32:14'),
(21, 1, 'Projekte verwalten', 0x45726c617562742065732c2050726f6a656b7465207a752065727374656c6c656e20756e64207a75206cc3b6736368656e20756e642069687265204e616d656e20756e64204d657461646174656e207a75206265617262656974656e, '2010-12-31 14:31:39'),
(22, 1, 'Gruppen verwalten', 0x45726c617562742065732c204772757070656e207a752065727374656c6c656e2c207a75206265617262656974656e20756e64207a75206cc3b6736368656e, '2010-12-31 14:31:39'),
(24, 1, 'Gruppenmitgliedschaften von Projektmitgliedern verwalten', 0x45726c617562742065732c2042656e75747a657220696e204772757070656e2065696e7a7566c3bc67656e206f64657220646172617573207a7520656e746665726e656e2c20736f6665726e207369652062657265697473204d6974676c6965642065696e657220477275707065206465732073656c62656e2050726f6a656b74732073696e64, '2010-12-31 14:31:39'),
(20, 1, 'Benutzerkonten bearbeiten', 0x45726c617562742065732c2042656e75747a65726e616d656e2c20452d4d61696c2d416472657373656e2c2041766174617265207573772e207a7520c3a46e6465726e, '2010-12-31 14:31:39'),
(23, 1, 'Gruppenmitgliedschaften verwalten', 0x45726c617562742065732c2042656e75747a657220696e204772757070656e2065696e7a7566c3bc67656e206f64657220646172617573207a7520656e746665726e656e, '2010-12-31 14:31:39'),
(12, 1, 'Blog-Artikel erstellen', 0x45726c617562742065732c206e65756520426c6f672d417274696b656c207a752065727374656c6c656e2c206e6963687420616265722c20736965207a7520766572c3b66666656e746c696368656e, '2010-06-11 15:56:43'),
(14, 1, 'Blog-Artikel löschen', 0x45726c617562742065732c20426c6f672d417274696b656c20656e6467c3bc6c746967207a75206cc3b6736368656e, '2010-06-11 15:45:40'),
(13, 1, 'Blog-Artikel bearbeiten', 0x45726c617562742065732c2064656e20496e68616c7420766f6e20426c6f672d417274696b656c6e207a75206265617262656974656e2c206e6963687420616265722c20646965736520566572c3a46e646572756e67656e207a7520766572c3b66666656e746c696368656e, '2010-06-11 15:45:40'),
(15, 1, 'Blog-Artikel veröffentlichen', 0x45726c617562742065732c206e65756520426c6f672d417274696b656c20756e6420c3846e646572756e67656e20616e206578697374696572656e64656e20417274696b656c6e207a7520766572c3b66666656e746c696368656e2c20736f77696520c3a46c746572652056657273696f6e656e207769656465726865727a757374656c6c656e20756e6420417274696b656c207a7520766572737465636b656e, '2010-06-11 15:45:40'),
(18, 1, 'Benutzer erstellen', 0x45726c617562742065732c206e6575652042656e75747a65726b6f6e74656e207a752065727374656c6c656e, '2010-12-31 14:31:39'),
(19, 1, 'Benutzer löschen', 0x45726c617562742065732c2042656e75747a65726b6f6e74656e207a75206cc3b6736368656e, '2010-12-31 14:31:39'),
(29, 1, 'Stile verwalten', 0x45726c617562742065732c205374696c65207a75202864652d29616b746976696572656e20756e642064656e205374616e646172647374696c206175737a7577c3a4686c656e, '2011-01-24 18:39:58'),
(30, 1, 'Eigenen Benutzernamen ändern', 0x45726c617562742065732c2064656e20656967656e656e2042656e75747a65726e616d656e206e6163687472c3a4676c696368207a7520c3a46e6465726e, '2011-02-08 17:01:18'),
(30, 2, 'Change own user name', 0x416c6c6f777320746f206368616e676520746865206f776e2075736572206e616d65, '2011-02-08 17:01:18'),
(31, 1, 'Standard-Sidebar konfigurieren', 0x45726c617562742065732c2064696520536964656261722c2064696520616c6c656e2047c3a47374656e20756e642042656e75747a65726e206f686e6520656967656e65205369646562617220616e67657a6569677420776972642c207a75206265617262656974656e, '2011-03-31 20:56:36'),
(32, 1, 'Sidebars anderer Benutzer konfigurieren', 0x45726c617562742065732c2064696520536964656261727320616c6c65722042656e75747a657220616e7a7570617373656e206f646572207a7572c3bc636b7a757365747a656e, '2011-04-08 23:33:34');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_sessions`
--
-- Erzeugt am: 02. Januar 2011 um 16:00
-- Aktualisiert am: 20. April 2011 um 17:25
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `startTime` datetime NOT NULL,
  `lastRequestTime` datetime NOT NULL,
  `key` char(64) COLLATE utf8_bin NOT NULL,
  `ip` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `userAgent` text COLLATE utf8_bin NOT NULL,
  `secondaryPasswordUsed` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL,
  `projectID` int(10) unsigned NOT NULL,
  `isFirstRequest` tinyint(1) NOT NULL,
  `confirmationExpirationTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `archivedSessionID` (`userID`,`lastRequestTime`),
  KEY `hidden` (`hidden`),
  KEY `projectID` (`projectID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=121 ;

--
-- Daten für Tabelle `premanager_sessions`
--

INSERT INTO `premanager_sessions` (`id`, `userID`, `startTime`, `lastRequestTime`, `key`, `ip`, `userAgent`, `secondaryPasswordUsed`, `hidden`, `projectID`, `isFirstRequest`, `confirmationExpirationTime`, `timestamp`) VALUES
(120, 2, '2011-04-20 15:21:38', '2011-04-20 15:36:58', '0d1f65df6b3d3317ecb36da90fc57e4052c2d72d9e6a5ef02a20170b55daa12b', '127.0.0.1', 0x4d6f7a696c6c612f352e30202857696e646f7773204e5420362e313b20574f5736343b2072763a322e302e3129204765636b6f2f32303130303130312046697265666f782f342e302e31, 0, 0, 0, 0, '0000-00-00 00:00:00', '2011-04-20 17:36:58');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_strings`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_strings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=681 ;

--
-- Daten für Tabelle `premanager_strings`
--

INSERT INTO `premanager_strings` (`id`, `pluginID`, `name`, `timestamp`) VALUES
(1, 2, 'articlesList', '2010-02-27 17:42:44'),
(182, 0, 'goToUpperPage', '2010-10-07 21:05:16'),
(3, 0, 'pageNotFoundMessage', '2010-10-07 21:05:16'),
(4, 0, 'pageNotFoundNoRefererMessage', '2010-10-07 21:05:16'),
(5, 0, 'pageNotFoundInternalRefererMessage', '2010-10-07 21:05:16'),
(6, 0, 'pageNotFoundExternalRefererMessage', '2010-10-07 21:05:16'),
(7, 0, 'goToHomepage', '2010-10-07 21:05:16'),
(8, 0, 'accessDenied', '2010-10-07 21:05:16'),
(9, 0, 'accessDeniedMessage', '2010-10-07 21:05:16'),
(10, 0, 'viewonline', '2010-10-07 21:05:16'),
(11, 0, 'viewonlineDetailLinkTitle', '2010-10-07 21:05:16'),
(12, 0, 'loginDetailLinkTitle', '2010-10-07 21:05:16'),
(13, 0, 'loginSidebarUserLabel', '2010-10-07 21:05:16'),
(14, 0, 'loginSidebarPasswordLabel', '2010-10-07 21:05:16'),
(15, 0, 'widgetLoginButton', '2010-10-07 21:05:16'),
(16, 0, 'loginSidebarTitle', '2010-10-07 21:05:16'),
(24, 0, 'loginButton', '2010-10-07 21:05:16'),
(18, 0, 'loggedInAs', '2010-10-07 21:05:16'),
(19, 0, 'widgetLogoutButton', '2010-10-07 21:05:16'),
(20, 0, 'widgetMyLabel', '2010-10-07 21:05:16'),
(21, 0, 'widgetMyProfileLabel', '2010-10-07 21:05:16'),
(23, 0, 'myLinkTitle', '2010-10-07 21:05:16'),
(25, 0, 'loginUserLabel', '2010-10-07 21:05:16'),
(26, 0, 'loginPasswordLabel', '2010-10-07 21:05:16'),
(27, 0, 'loginTitle', '2010-10-07 21:05:16'),
(28, 0, 'loginFailedTitle', '2010-10-07 21:05:16'),
(29, 0, 'loginFailedGlobalMessage', '2010-10-07 21:05:16'),
(30, 0, 'loginFailedMessage', '2010-10-07 21:05:16'),
(31, 0, 'loginFailedPasswordLostMessage', '2010-10-07 21:05:16'),
(32, 0, 'loginFailedPasswordLostLinkText', '2010-10-07 21:05:16'),
(33, 0, 'loginFailedRetryLogin', '2010-10-07 21:05:16'),
(34, 0, 'loginSuccessfulGlobalMessage', '2010-10-07 21:05:16'),
(35, 0, 'viewonlineEmpty', '2010-10-07 21:05:16'),
(36, 0, 'viewonlineMessage', '2010-10-07 21:05:16'),
(37, 0, 'viewonlineUser', '2010-10-07 21:05:16'),
(38, 0, 'viewonlineLastRequest', '2010-10-07 21:05:16'),
(39, 0, 'viewonlineLocation', '2010-10-07 21:05:16'),
(79, 0, 'yesterday', '2010-10-07 21:05:16'),
(41, 0, 'dateMonday', '2010-10-07 21:05:16'),
(42, 0, 'dateTuesday', '2010-10-07 21:05:16'),
(43, 0, 'dateWednesday', '2010-10-07 21:05:16'),
(44, 0, 'dateThursday', '2010-10-07 21:05:16'),
(45, 0, 'dateFriday', '2010-10-07 21:05:16'),
(46, 0, 'dateSaturday', '2010-10-07 21:05:16'),
(47, 0, 'dateSunday', '2010-10-07 21:05:16'),
(48, 0, 'dateMon', '2010-10-07 21:05:16'),
(49, 0, 'dateTue', '2010-10-07 21:05:16'),
(50, 0, 'dateWed', '2010-10-07 21:05:16'),
(51, 0, 'dateThu', '2010-10-07 21:05:16'),
(52, 0, 'dateFri', '2010-10-07 21:05:16'),
(53, 0, 'dateSat', '2010-10-07 21:05:16'),
(54, 0, 'dateSun', '2010-10-07 21:05:16'),
(55, 0, 'dateJanuary', '2010-10-07 21:05:16'),
(56, 0, 'dateFebruary', '2010-10-07 21:05:16'),
(57, 0, 'dateMarch', '2010-10-07 21:05:16'),
(58, 0, 'dateApril', '2010-10-07 21:05:16'),
(59, 0, 'dateMay', '2010-10-07 21:05:16'),
(60, 0, 'dateJune', '2010-10-07 21:05:16'),
(61, 0, 'dateJuly', '2010-10-07 21:05:16'),
(62, 0, 'dateAugust', '2010-10-07 21:05:16'),
(63, 0, 'dateSeptember', '2010-10-07 21:05:16'),
(64, 0, 'dateOctober', '2010-10-07 21:05:16'),
(65, 0, 'dateNovember', '2010-10-07 21:05:16'),
(66, 0, 'dateDecember', '2010-10-07 21:05:16'),
(67, 0, 'dateJan', '2010-10-07 21:05:16'),
(68, 0, 'dateFeb', '2010-10-07 21:05:16'),
(69, 0, 'dateMar', '2010-10-07 21:05:16'),
(70, 0, 'dateApr', '2010-10-07 21:05:16'),
(71, 0, 'dateMayShort', '2010-10-07 21:05:16'),
(72, 0, 'dateJun', '2010-10-07 21:05:16'),
(73, 0, 'dateJul', '2010-10-07 21:05:16'),
(74, 0, 'dateAug', '2010-10-07 21:05:16'),
(75, 0, 'dateSep', '2010-10-07 21:05:16'),
(76, 0, 'dateOct', '2010-10-07 21:05:16'),
(77, 0, 'dateNov', '2010-10-07 21:05:16'),
(78, 0, 'dateDec', '2010-10-07 21:05:16'),
(80, 0, 'today', '2010-10-07 21:05:16'),
(81, 0, 'tomorrow', '2010-10-07 21:05:16'),
(82, 0, 'viewonlineLastRequestMask', '2010-10-07 21:05:16'),
(130, 0, 'userListEmpty', '2010-10-07 21:05:16'),
(84, 0, 'dateSecondsAgoLong', '2010-10-07 21:05:16'),
(172, 0, 'editGroupDescription', '2010-10-07 21:05:16'),
(86, 0, 'dateMinutesAgoLong', '2010-10-07 21:05:16'),
(171, 0, 'editGroup', '2010-10-07 21:05:16'),
(88, 0, 'dateHoursAgoLong', '2010-10-07 21:05:16'),
(170, 0, 'addGroupDescription', '2010-10-07 21:05:16'),
(90, 0, 'dateDaysAgoLong', '2010-10-07 21:05:16'),
(169, 0, 'addGroup', '2010-10-07 21:05:16'),
(92, 0, 'dateMonthsAgoLong', '2010-10-07 21:05:16'),
(168, 0, 'deleteUserDescription', '2010-10-07 21:05:16'),
(94, 0, 'dateYearsAgoLong', '2010-10-07 21:05:16'),
(129, 0, 'userListMessage', '2010-10-07 21:05:16'),
(96, 0, 'dateSecondsAgoShort', '2010-10-07 21:05:16'),
(167, 0, 'editUserDescription', '2010-10-07 21:05:16'),
(98, 0, 'dateMinutesAgoShort', '2010-10-07 21:05:16'),
(166, 0, 'addUserDescription', '2010-10-07 21:05:16'),
(100, 0, 'dateHoursAgoShort', '2010-10-07 21:05:16'),
(165, 0, 'deleteUser', '2010-10-07 21:05:16'),
(102, 0, 'dateDaysAgoShort', '2010-10-07 21:05:16'),
(164, 0, 'editUser', '2010-10-07 21:05:16'),
(104, 0, 'dateMonthsAgoShort', '2010-10-07 21:05:16'),
(163, 0, 'addUser', '2010-10-07 21:05:16'),
(106, 0, 'dateYearsAgoShort', '2010-10-07 21:05:16'),
(128, 0, 'users', '2010-10-07 21:05:16'),
(127, 0, 'up', '2010-10-07 21:05:16'),
(126, 0, 'home', '2010-10-07 21:05:16'),
(125, 0, 'canonical', '2010-10-07 21:05:16'),
(124, 0, 'literalGap', '2010-10-07 21:05:16'),
(123, 0, 'pageBack', '2010-10-07 21:05:16'),
(122, 0, 'pageForward', '2010-10-07 21:05:16'),
(121, 0, 'pageX', '2010-10-07 21:05:16'),
(120, 0, 'avatarOf', '2010-10-07 21:05:16'),
(136, 0, 'literalNone', '2010-10-07 21:05:16'),
(119, 0, 'dateOneSecondAgoLong', '2010-10-07 21:05:16'),
(131, 0, 'userListName', '2010-10-07 21:05:16'),
(132, 0, 'userRegistrationTime', '2010-10-07 21:05:16'),
(133, 0, 'userLastLoginTime', '2010-10-07 21:05:16'),
(134, 0, 'userDoesNotExist', '2010-10-07 21:05:16'),
(135, 2, 'articleDoesNotExist', '2010-03-07 00:42:52'),
(137, 0, 'loginHidden', '2010-10-07 21:05:16'),
(138, 0, 'guest', '2010-10-07 21:05:16'),
(139, 0, 'xGuests', '2010-10-07 21:05:16'),
(142, 0, 'userName', '2010-10-07 21:05:16'),
(143, 0, 'userTitle', '2010-10-07 21:05:16'),
(144, 0, 'titleDivider', '2010-10-07 21:05:16'),
(145, 0, 'avatar', '2010-10-07 21:05:16'),
(149, 0, 'isGroupLeaderAppendix', '2010-10-07 21:05:16'),
(148, 0, 'userGroupList', '2010-10-07 21:05:16'),
(150, 0, 'groups', '2010-10-07 21:05:16'),
(151, 0, 'groupListEmpty', '2010-10-07 21:05:16'),
(152, 0, 'groupMemberCount', '2010-10-07 21:05:16'),
(153, 0, 'groupDoesNotExist', '2010-10-07 21:05:16'),
(154, 0, 'groupName', '2010-10-07 21:05:16'),
(155, 0, 'groupTitle', '2010-10-07 21:05:16'),
(156, 0, 'groupText', '2010-10-07 21:05:16'),
(157, 0, 'brackets', '2010-10-07 21:05:16'),
(158, 0, 'groupMemberList', '2010-10-07 21:05:16'),
(159, 0, 'pageXOfY', '2010-10-07 21:05:16'),
(160, 0, 'label', '2010-10-07 21:05:16'),
(161, 0, 'groupLeaderHeader', '2010-10-07 21:05:16'),
(162, 0, 'groupMemberHeader', '2010-10-07 21:05:16'),
(173, 0, 'deleteGroup', '2010-10-07 21:05:16'),
(174, 0, 'deleteGroupDescription', '2010-10-07 21:05:16'),
(175, 0, 'gotoGroups', '2010-10-07 21:05:16'),
(176, 0, 'gotoGroupsDescription', '2010-10-07 21:05:16'),
(177, 0, 'gotoUsers', '2010-10-07 21:05:16'),
(178, 0, 'gotoUsersDescription', '2010-10-07 21:05:16'),
(179, 0, 'defaultPage', '2010-10-07 21:05:16'),
(180, 0, 'pageNotFound', '2010-10-07 21:05:16'),
(183, 0, 'structureMessage', '2010-10-07 21:05:16'),
(184, 0, 'structureEmpty', '2010-10-07 21:05:16'),
(185, 0, 'structureNameColumn', '2010-10-07 21:05:16'),
(186, 0, 'structureTitleColumn', '2010-10-07 21:05:16'),
(187, 0, 'structureRootNodeName', '2010-10-07 21:05:16'),
(188, 0, 'editNode', '2010-10-07 21:05:16'),
(189, 0, 'deleteNode', '2010-10-07 21:05:16'),
(190, 0, 'editNodeDescription', '2010-10-07 21:05:16'),
(191, 0, 'deleteNodeDescription', '2010-10-07 21:05:16'),
(192, 0, 'moveNode', '2010-10-07 21:05:16'),
(193, 0, 'moveNodeDescription', '2010-10-07 21:05:16'),
(194, 0, 'addNode', '2010-10-07 21:05:16'),
(195, 0, 'addNodeDescription', '2010-10-07 21:05:16'),
(196, 0, 'deleteNodeMessage', '2010-10-07 21:05:16'),
(197, 0, 'confirmation', '2010-10-07 21:05:16'),
(198, 0, 'confirmationMessage', '2010-10-07 21:05:16'),
(199, 0, 'nodeNameLabel', '2010-10-07 21:05:16'),
(200, 0, 'nodeTitleLabel', '2010-10-07 21:05:16'),
(201, 0, 'nodeNameDescription', '2010-10-07 21:05:16'),
(202, 0, 'nodeTitleDescription', '2010-10-07 21:05:16'),
(203, 0, 'submitButton', '2010-10-07 21:05:16'),
(204, 0, 'addNodeButton', '2010-10-07 21:05:16'),
(205, 0, 'editNodeButton', '2010-10-07 21:05:16'),
(211, 0, 'deleteNodeTitle', '2010-10-07 21:05:16'),
(210, 0, 'moveNodeTitle', '2010-10-07 21:05:16'),
(208, 0, 'editNodeTitle', '2010-10-07 21:05:16'),
(209, 0, 'addNodeTitle', '2010-10-07 21:05:16'),
(213, 0, 'noNodeNameInputtedError', '2010-10-07 21:05:16'),
(214, 0, 'noNodeTitleInputtedError', '2010-10-07 21:05:16'),
(215, 0, 'nodeNameAlreadyExistsError', '2010-10-07 21:05:16'),
(216, 0, 'confirmationConfirmButton', '2010-10-07 21:05:16'),
(217, 0, 'confirmationCancelButton', '2010-10-07 21:05:16'),
(218, 0, 'structureTitle', '2010-10-07 21:05:16'),
(220, 0, 'moveNodeMessage', '2010-10-07 21:05:16'),
(221, 0, 'insertNodeHere', '2010-10-07 21:05:16'),
(222, 0, 'insertNodeHereDescription', '2010-10-07 21:05:16'),
(223, 0, 'moveTargetDoesNotExistError', '2010-10-07 21:05:16'),
(224, 0, 'moveTargetIsChildError', '2010-10-07 21:05:16'),
(225, 0, 'nodeNameAlreadyExistsInTargetError', '2010-10-07 21:05:16'),
(226, 0, 'userNameDescription', '2010-10-07 21:05:16'),
(227, 0, 'isBotLabel', '2010-10-07 21:05:16'),
(228, 0, 'isBotShortLabel', '2010-10-07 21:05:16'),
(229, 0, 'isBotDescription', '2010-10-07 21:05:16'),
(230, 0, 'botIdentifier', '2010-10-07 21:05:16'),
(231, 0, 'botIdentifierDescription', '2010-10-07 21:05:16'),
(232, 0, 'passwordLabel', '2010-10-07 21:05:16'),
(233, 0, 'passwordDescription', '2010-10-07 21:05:16'),
(234, 0, 'passwordConfirmationLabel', '2010-10-07 21:05:16'),
(235, 0, 'passwordConfirmationDescription', '2010-10-07 21:05:16'),
(236, 0, 'noNodeUserNameInputtedError', '2010-10-07 21:05:16'),
(237, 0, 'registrationEmailLabel', '2010-10-07 21:05:16'),
(238, 0, 'registrationEmailDescription', '2010-10-07 21:05:16'),
(239, 0, 'invalidEmailAddressError', '2010-10-07 21:05:16'),
(240, 0, 'noRegistrationEmailInputtedError', '2010-10-07 21:05:16'),
(241, 0, 'userNameAlreadyExistsError', '2010-10-07 21:05:16'),
(242, 0, 'noBotIdentifierInputtedError', '2010-10-07 21:05:16'),
(243, 0, 'invalidBotIdentifierInputtedError', '2010-10-07 21:05:16'),
(244, 0, 'noPasswordInputtedError', '2010-10-07 21:05:16'),
(245, 0, 'noPasswordConfirmationInputtedError', '2010-10-07 21:05:16'),
(246, 0, 'passwordConfirmationInvalidError', '2010-10-07 21:05:16'),
(247, 0, 'nameContainsSlashError', '2010-10-07 21:05:16'),
(248, 0, 'deleteUserMessage', '2010-10-07 21:05:16'),
(503, 0, 'invalidPMLInputtedError', '2010-10-07 21:05:16'),
(250, 0, 'groupNameDescription', '2010-10-07 21:05:16'),
(253, 0, 'groupColor', '2010-10-07 21:05:16'),
(252, 0, 'groupTitleDescription', '2010-10-07 21:05:16'),
(254, 0, 'groupColorDescription', '2010-10-07 21:05:16'),
(255, 0, 'groupTextDescription', '2010-10-07 21:05:16'),
(256, 0, 'groupAutoJoinDescription', '2011-01-02 15:29:03'),
(257, 0, 'groupAutoJoinLabel', '2011-01-02 15:29:31'),
(258, 0, 'deleteGroupMessage', '2010-10-07 21:05:16'),
(259, 0, 'noGroupNameInputtedError', '2010-10-07 21:05:16'),
(260, 0, 'groupNameAlreadyExistsError', '2010-10-07 21:05:16'),
(261, 0, 'noGroupTitleInputtedError', '2010-10-07 21:05:16'),
(262, 0, 'noGroupColorInputtedError', '2010-10-07 21:05:16'),
(263, 0, 'invalidGroupColorInputtedError', '2010-10-07 21:05:16'),
(264, 0, 'noGroupTextInputtedError', '2010-10-07 21:05:16'),
(265, 0, 'deleteGuestErrorMessage', '2010-10-07 21:05:16'),
(266, 0, 'error', '2010-10-07 21:05:16'),
(267, 0, 'goToGuestAccount', '2010-10-07 21:05:16'),
(268, 0, 'userJoinGroup', '2010-10-07 21:05:16'),
(269, 0, 'userJoinGroupDescription', '2010-10-07 21:05:16'),
(270, 0, 'userJoinGroupMessage', '2010-10-07 21:05:16'),
(271, 0, 'userJoinGroupEmptyMessage', '2010-10-07 21:05:16'),
(272, 0, 'groupDoesNotExistError', '2010-10-07 21:05:16'),
(273, 0, 'userJoinAlreadyMemberError', '2010-10-07 21:05:16'),
(274, 0, 'userLeaveGroup', '2010-10-07 21:05:16'),
(275, 0, 'userLeaveGroupDescription', '2010-10-07 21:05:16'),
(276, 0, 'goToUser', '2010-10-07 21:05:16'),
(277, 0, 'userMembershipMissingError', '2010-10-07 21:05:16'),
(278, 0, 'promoteUser', '2010-10-07 21:05:16'),
(279, 0, 'promoteUserDescription', '2010-10-07 21:05:16'),
(280, 0, 'demoteUser', '2010-10-07 21:05:16'),
(281, 0, 'demoteUserDescription', '2010-10-07 21:05:16'),
(282, 0, 'defaultUserTitle', '2010-10-07 21:05:16'),
(283, 0, 'groupPriority', '2010-10-07 21:05:16'),
(284, 0, 'groupPriorityDescription', '2010-10-07 21:05:16'),
(285, 0, 'invalidGroupPriorityInputtedError', '2010-10-07 21:05:16'),
(286, 0, 'editGroupRights', '2010-10-07 21:05:16'),
(287, 0, 'editGroupRightsDescription', '2010-10-07 21:05:16'),
(288, 0, 'viewUserRights', '2010-10-07 21:05:16'),
(289, 0, 'viewUserRightsDescription', '2010-10-07 21:05:16'),
(290, 0, 'viewUserRightsMessage', '2010-10-07 21:05:16'),
(291, 0, 'viewUserRightsEmptyMessage', '2010-10-07 21:05:16'),
(292, 0, 'userRightColumn', '2010-10-07 21:05:16'),
(293, 0, 'userRightSourceColumn', '2010-10-07 21:05:16'),
(294, 0, 'inputErrorMessage', '2010-10-07 21:05:16'),
(296, 0, 'lockGroup', '2010-10-07 21:05:16'),
(297, 0, 'lockGroupDescription', '2010-10-07 21:05:16'),
(298, 0, 'unlockGroup', '2010-10-07 21:05:16'),
(299, 0, 'unlockGroupDescription', '2010-10-07 21:05:16'),
(300, 0, 'lockGroupMessage', '2010-10-07 21:05:16'),
(301, 0, 'unlockGroupMessage', '2010-10-07 21:05:16'),
(302, 0, 'userJoinGroupAccessDeniedError', '2010-10-07 21:05:16'),
(303, 0, 'registerAccessDeniedMessage', '2010-10-07 21:05:16'),
(304, 0, 'registerMessage', '2010-10-07 21:05:16'),
(305, 0, 'registerWithoutEmailMessage', '2010-10-07 21:05:16'),
(306, 0, 'registerUserLabel', '2010-10-07 21:05:16'),
(307, 0, 'registerUserDescription', '2010-10-07 21:05:16'),
(308, 0, 'registerPasswordLabel', '2010-10-07 21:05:16'),
(309, 0, 'registerPasswordDescription', '2010-10-07 21:05:16'),
(310, 0, 'registerPasswordConfirmationLabel', '2010-10-07 21:05:16'),
(311, 0, 'registerPasswordConfirmationDescription', '2010-10-07 21:05:16'),
(312, 0, 'registerEmailLabel', '2010-10-07 21:05:16'),
(313, 0, 'registerEmailDescription', '2010-10-07 21:05:16'),
(314, 0, 'registerOptionalEmailDescription', '2010-10-07 21:05:16'),
(315, 0, 'registerEmailConfirmationLabel', '2010-10-07 21:05:16'),
(316, 0, 'registerEmailConfirmationDescription', '2010-10-07 21:05:16'),
(317, 0, 'registerOptionalEmailConfirmationDescription', '2010-10-07 21:05:16'),
(318, 0, 'registerButton', '2010-10-07 21:05:16'),
(319, 0, 'noUserNameInputtedError', '2010-10-07 21:05:16'),
(320, 0, 'noEmailConfirmationInputtedError', '2010-10-07 21:05:16'),
(321, 0, 'emailConfirmationInvalidError', '2010-10-07 21:05:16'),
(322, 0, 'userStatusLabel', '2010-10-07 21:05:16'),
(323, 0, 'userStatusDescription', '2010-10-07 21:05:16'),
(324, 0, 'userStatusEnabled', '2010-10-07 21:05:16'),
(325, 0, 'userStatusDisabled', '2010-10-07 21:05:16'),
(326, 0, 'userStatusWaitForEmail', '2010-10-07 21:05:16'),
(327, 0, 'userHasUnconfirmedEmailInfo', '2010-10-07 21:05:16'),
(328, 0, 'resetUnconfirmedEmailButton', '2010-10-07 21:05:16'),
(329, 0, 'confirmUnconfirmedEmailButton', '2010-10-07 21:05:16'),
(330, 0, 'userAccountActivationEmailMessage', '2010-10-07 21:05:16'),
(331, 0, 'userAccountActivationEmailPlainMessage', '2010-10-07 21:05:16'),
(332, 0, 'systemErrorMessage', '2010-10-07 21:05:16'),
(333, 0, 'userAccountActivationEmailFailedErrorMessage', '2010-10-07 21:05:16'),
(334, 0, 'userAccountActivationEmailSentMessage', '2010-10-07 21:05:16'),
(335, 0, 'userAccountWithEmailCreatedMessage', '2010-10-07 21:05:16'),
(336, 0, 'userAccountCreatedMessage', '2010-10-07 21:05:16'),
(337, 0, 'registerEmailConfirmationEmailFailedMessage', '2010-10-07 21:05:16'),
(338, 0, 'userEmailConfirmationEmailMessage', '2010-10-07 21:05:16'),
(339, 0, 'userEmailConfirmationEmailPlainMessage', '2010-10-07 21:05:16'),
(341, 0, 'emailAddressConfirmedMessage', '2010-10-07 21:05:16'),
(342, 0, 'emailAddressConfirmedWithWaitForEmailMessage', '2010-10-07 21:05:16'),
(343, 0, 'confirmEmailInvalidKeySpecifiedError', '2010-10-07 21:05:16'),
(344, 0, 'userMeJoinGroupEmptyMessage', '2010-10-07 21:05:16'),
(345, 0, 'passwordLostMessage', '2010-10-07 21:05:16'),
(346, 0, 'passwordLostUserLabel', '2010-10-07 21:05:16'),
(347, 0, 'passwordLostUserDescription', '2010-10-07 21:05:16'),
(348, 0, 'passwordLostEmailLabel', '2010-10-07 21:05:16'),
(349, 0, 'passwordLostEmailDescription', '2010-10-07 21:05:16'),
(350, 0, 'passwordLostEmailDoesNotMatchError', '2010-10-07 21:05:16'),
(351, 0, 'userEmailConfirmationOnAccountCreationEmailMessage', '2010-10-07 21:05:16'),
(352, 0, 'userEmailConfirmationOnAccountCreationEmailPlainMessage', '2010-10-07 21:05:16'),
(353, 0, 'userAccountActivationEmailTitle', '2010-10-07 21:05:16'),
(354, 0, 'userEmailConfirmationOnAccountCreationEmailTitle', '2010-10-07 21:05:16'),
(355, 0, 'userEmailConfirmationEmailTitle', '2010-10-07 21:05:16'),
(356, 0, 'passwordLostEmailMessage', '2010-10-07 21:05:16'),
(357, 0, 'passwordLostEmailPlainMessage', '2010-10-07 21:05:16'),
(358, 0, 'passwordLostEmailFailedErrorMessage', '2010-10-07 21:05:16'),
(359, 0, 'passwordLostSucceededMessage', '2010-10-07 21:05:16'),
(360, 0, 'secondaryPasswordUsedGlobalMessage', '2010-10-07 21:05:16'),
(361, 0, 'loginRegisterTip', '2010-10-07 21:05:16'),
(362, 0, 'loginRegisterTipLinkText', '2010-10-07 21:05:16'),
(363, 0, 'registerLoginTip', '2010-10-07 21:05:16'),
(364, 0, 'registerLoginTipLinkText', '2010-10-07 21:05:16'),
(365, 0, 'loginFailedWaitForEmailMessage', '2010-10-07 21:05:16'),
(366, 0, 'loginFailedAccountDisabledMessage', '2010-10-07 21:05:16'),
(367, 0, 'registrationDataNameLabel', '2010-10-07 21:05:16'),
(368, 0, 'registrationDataNameDescription', '2010-10-07 21:05:16'),
(369, 0, 'emailConfirmationDescription', '2010-10-07 21:05:16'),
(370, 0, 'changePasswordDescription', '2010-10-07 21:05:16'),
(371, 0, 'changePasswordConfirmationDescription', '2010-10-07 21:05:16'),
(372, 0, 'registrationDataNotLoggedInError', '2010-10-07 21:05:16'),
(373, 0, 'emailConfirmationEmailFailedMessage', '2010-10-07 21:05:16'),
(374, 0, 'registrationDataSavedMessage', '2010-10-07 21:05:16'),
(375, 0, 'passwordChangedMessage', '2010-10-07 21:05:16'),
(376, 0, 'unconfirmedEmailChangedMessage', '2010-10-07 21:05:16'),
(377, 0, 'goBack', '2010-10-07 21:05:16'),
(378, 0, 'nodeNeighbors', '2010-10-07 21:05:16'),
(379, 0, 'nodeNeighborsDescription', '2010-10-07 21:05:16'),
(380, 0, 'nodeNeighborsMessage', '2010-10-07 21:05:16'),
(381, 0, 'nodeNeighborsEmptyMessage', '2010-10-07 21:05:16'),
(382, 0, 'nodeFreeTreesEmptyMessage', '2010-10-07 21:05:16'),
(383, 0, 'nodeNeighborsListTitle', '2010-10-07 21:05:16'),
(384, 0, 'nodeFreeTreesListTitle', '2010-10-07 21:05:16'),
(385, 0, 'nodeNeighborsTitle', '2010-10-07 21:05:16'),
(386, 0, 'nodeNeighborMoveUp', '2010-10-07 21:05:16'),
(387, 0, 'nodeNeighborMoveUpDescription', '2010-10-07 21:05:16'),
(388, 0, 'nodeNeighborMoveDown', '2010-10-07 21:05:16'),
(389, 0, 'nodeNeighborMoveDownDescription', '2010-10-07 21:05:16'),
(390, 0, 'nodeNeighborMakePrimary', '2010-10-07 21:05:16'),
(391, 0, 'nodeNeighborMakePrimaryDescription', '2010-10-07 21:05:16'),
(392, 0, 'nodeNeighborMakePrimaryActiveDescription', '2010-10-07 21:05:16'),
(393, 0, 'nodeNeighborRemove', '2010-10-07 21:05:16'),
(394, 0, 'nodeNeighborRemoveDescription', '2010-10-07 21:05:16'),
(395, 0, 'nodeAddNeighborDescription', '2010-10-07 21:05:16'),
(396, 0, 'nodeNeighborMakePrimaryDisabledDescription', '2010-10-07 21:05:16'),
(397, 0, 'nodePermissions', '2010-10-07 21:05:16'),
(398, 0, 'nodePermissionsDescription', '2010-10-07 21:05:16'),
(399, 0, 'nodePermissionsTitle', '2010-10-07 21:05:16'),
(400, 0, 'nodePermissionsNoAccessRestrictionMessage', '2010-10-07 21:05:16'),
(401, 0, 'nodePermissionsAccessRestrictionMessage', '2010-10-07 21:05:16'),
(402, 0, 'nodeAccessRestrictionButton', '2010-10-07 21:05:16'),
(403, 0, 'nodeNoAccessRestrictionButton', '2010-10-07 21:05:16'),
(404, 0, 'nodePermissionsRemoveGroup', '2010-10-07 21:05:16'),
(405, 0, 'nodePermissionsRemoveGroupDescription', '2010-10-07 21:05:16'),
(406, 0, 'nodePermissionsAddGroupDescription', '2010-10-07 21:05:16'),
(407, 0, 'nodePermissionsGroupListTitle', '2010-10-07 21:05:16'),
(408, 0, 'nodePermissionsFreeGroupListTitle', '2010-10-07 21:05:16'),
(409, 0, 'nodePermissionsGroupListEmptyMessage', '2010-10-07 21:05:16'),
(410, 0, 'nodePermissionsFreeGroupListEmptyMessage', '2010-10-07 21:05:16'),
(411, 0, 'addProject', '2010-10-07 21:05:16'),
(412, 0, 'addProjectDescription', '2010-10-07 21:05:16'),
(413, 0, 'projectListEmptyMessage', '2010-10-07 21:05:16'),
(414, 0, 'editProject', '2010-10-07 21:05:16'),
(415, 0, 'editProjectDescription', '2010-10-07 21:05:16'),
(416, 0, 'deleteProject', '2010-10-07 21:05:16'),
(417, 0, 'deleteProjectDescription', '2010-10-07 21:05:16'),
(418, 0, 'projectIsOrganization', '2010-10-07 21:05:16'),
(419, 0, 'projectTitle', '2010-10-07 21:05:16'),
(420, 0, 'projectAuthor', '2010-10-07 21:05:16'),
(421, 0, 'projectDescription', '2010-10-07 21:05:16'),
(422, 0, 'projectKeywords', '2010-10-07 21:05:16'),
(423, 0, 'projectName', '2010-10-07 21:05:16'),
(424, 0, 'projectNameDescription', '2010-10-07 21:05:16'),
(425, 0, 'projectTitleDescription', '2010-10-07 21:05:16'),
(426, 0, 'projectSubTitle', '2010-10-07 21:05:16'),
(427, 0, 'projectSubTitleDescription', '2010-10-07 21:05:16'),
(428, 0, 'projectAuthorDescription', '2010-10-07 21:05:16'),
(429, 0, 'projectCopyright', '2010-10-07 21:05:16'),
(430, 0, 'projectCopyrightDescription', '2010-10-07 21:05:16'),
(431, 0, 'projectDescriptionDescription', '2010-10-07 21:05:16'),
(432, 0, 'projectKeywordsDescription', '2010-10-07 21:05:16'),
(433, 0, 'noProjectNameInputtedError', '2010-10-07 21:05:16'),
(434, 0, 'projectNameAlreadyExistsError', '2010-10-07 21:05:16'),
(435, 0, 'noProjectTitleInputtedError', '2010-10-07 21:05:16'),
(436, 0, 'noProjectAuthorInputtedError', '2010-10-07 21:05:16'),
(437, 0, 'noProjectDescriptionInputtedError', '2010-10-07 21:05:16'),
(438, 0, 'noProjectCopyrightInputtedError', '2010-10-07 21:05:16'),
(439, 0, 'projectNameInvalidError', '2010-10-07 21:05:16'),
(440, 0, 'deleteProjectMessage', '2010-10-07 21:05:16'),
(441, 0, 'gotoNodeDescription', '2010-10-07 21:05:16'),
(442, 0, 'gotoNode', '2010-10-07 21:05:16'),
(443, 0, 'deleteTreeNodeError', '2010-10-07 21:05:16'),
(446, 0, 'nodeContentTitle', '2010-10-07 21:05:16'),
(444, 0, 'moveTargetNotChangedError', '2010-10-07 21:05:16'),
(445, 0, 'moveIntoTreeNodeError', '2010-10-07 21:05:16'),
(447, 0, 'treeNodeDescription', '2010-10-07 21:05:16'),
(448, 0, 'commonNodeDescription', '2010-10-07 21:05:16'),
(449, 0, 'gotoProject', '2010-10-07 21:05:16'),
(450, 0, 'gotoProjectDescription', '2010-10-07 21:05:16'),
(451, 0, 'deleteOrganizationError', '2010-10-07 21:05:16'),
(452, 2, 'addArticle', '2010-06-11 16:06:47'),
(453, 2, 'addArticleDescription', '2010-06-11 16:13:16'),
(454, 0, 'previewButton', '2010-10-07 21:05:16'),
(455, 2, 'articleTitle', '2010-06-11 18:26:59'),
(456, 2, 'articleTitleDescription', '2010-06-11 18:26:59'),
(457, 2, 'articleText', '2010-06-11 18:24:35'),
(458, 2, 'articleTextDescription', '2010-06-11 18:24:35'),
(459, 2, 'noArticleTitleInputtedError', '2010-06-11 18:46:50'),
(460, 2, 'noArticleTextInputtedError', '2010-06-11 18:46:42'),
(461, 2, 'summaryLabel', '2010-06-11 18:58:22'),
(462, 2, 'summaryDescription', '2010-06-11 18:58:22'),
(463, 2, 'summaryCreatedDescription', '2010-06-11 19:01:26'),
(464, 2, 'autoSummaryArticleCreated', '2010-06-11 19:01:56'),
(465, 2, 'articleListEmptyMessage', '2010-06-11 19:19:43'),
(466, 2, 'articleRevisions', '2010-06-11 19:33:20'),
(467, 2, 'articleRevisionsDescription', '2010-06-11 19:33:20'),
(468, 2, 'editArticle', '2010-06-11 19:35:08'),
(469, 2, 'editArticleDescription', '2010-06-11 19:35:08'),
(470, 2, 'articleRevisionsEmptyMessage', '2010-06-11 20:04:21'),
(471, 2, 'articleRevisionsMessage', '2010-06-11 20:11:06'),
(472, 2, 'viewRevisionDescription', '2010-06-11 20:11:10'),
(473, 2, 'revisionColumn', '2010-06-11 20:18:17'),
(474, 2, 'revisionTimeColumn', '2010-06-11 20:18:17'),
(475, 2, 'revisionCreatorColumn', '2010-06-11 20:18:33'),
(476, 2, 'revisionSummaryColumn', '2010-06-11 20:18:33'),
(482, 2, 'revisionBlockNoRevisionsMessage', '2010-06-11 21:04:39'),
(479, 2, 'revisionBlockTitle', '2010-06-11 20:46:06'),
(480, 2, 'revisionBlockNoPublishedRevisionMessage', '2010-06-11 20:53:28'),
(481, 2, 'revisionBlockOldRevisionMessage', '2010-06-11 20:53:28'),
(483, 2, 'revisionBlockNoPublishedRevisionRevisionSpecifiedMessage', '2010-06-11 21:05:45'),
(484, 2, 'revisionBlockNewRevisionMessage', '2010-06-11 21:06:11'),
(485, 2, 'gotoPublishedRevision', '2010-06-11 21:06:54'),
(486, 2, 'specifiedRevision', '2010-06-11 21:09:30'),
(487, 2, 'publishedRevision', '2010-06-11 21:09:30'),
(488, 2, 'lastRevision', '2010-06-11 21:09:59'),
(489, 2, 'publishRevision', '2010-06-11 22:47:56'),
(490, 2, 'publishRevisionDescription', '2010-06-11 22:47:56'),
(491, 2, 'publishRevisionMessage', '2010-06-11 23:09:41'),
(492, 2, 'publishRevisionArticleHiddenMessage', '2010-06-11 23:12:01'),
(493, 2, 'revisionBlockPublishedRevisionMessage', '2010-06-11 23:17:57'),
(494, 2, 'editRevisionBlockTitle', '2010-06-11 23:36:00'),
(495, 2, 'hideArticle', '2010-06-11 23:58:07'),
(496, 2, 'hideArticleDescription', '2010-06-11 23:58:07'),
(497, 2, 'hideArticleMessage', '2010-06-12 00:02:25'),
(498, 2, 'publishRevisionActiveDescription', '2010-06-12 00:04:48'),
(499, 2, 'deleteArticle', '2010-06-12 13:34:33'),
(500, 2, 'deleteArticleDescription', '2010-06-12 13:34:33'),
(501, 2, 'deleteArticleMessage', '2010-06-12 13:52:46'),
(502, 2, 'preview', '2010-06-18 19:49:17'),
(504, 0, 'pmlTitle', '2010-10-07 21:05:16'),
(505, 0, 'gotoChangeOwnAvatar', '2010-10-07 21:05:16'),
(506, 0, 'canOnlyChangeOwnAvatar', '2010-10-07 21:05:16'),
(507, 0, 'changeAvatarMessageOwnExisting', '2010-10-07 21:05:16'),
(508, 0, 'changeAvatarMessageForeignExisting', '2010-10-07 21:05:16'),
(509, 0, 'changeAvatarMessageOwnEmpty', '2010-10-07 21:05:16'),
(510, 0, 'changeAvatarMessageForeignEmpty', '2010-10-07 21:05:16'),
(511, 0, 'currentAvatar', '2010-10-07 21:05:16'),
(512, 0, 'currentAvatarDescription', '2010-10-07 21:05:16'),
(513, 0, 'selectAvatarExisting', '2010-10-07 21:05:16'),
(514, 0, 'selectAvatarEmpty', '2010-10-07 21:05:16'),
(515, 0, 'selectAvatarExistingDescription', '2010-10-07 21:05:16'),
(516, 0, 'selectAvatarEmptyDescription', '2010-10-07 21:05:16'),
(517, 0, 'changeAvatarButtonsLabel', '2010-10-07 21:05:16'),
(518, 0, 'changeAvatarButton', '2010-10-07 21:05:16'),
(519, 0, 'deleteAvatarButton', '2010-10-07 21:05:16'),
(520, 0, 'changeAvatarNoFileSentError', '2010-10-07 21:05:16'),
(521, 0, 'pictureFileTypeNotSupportedError', '2010-10-07 21:05:16'),
(522, 0, 'changeAvatar', '2010-10-07 21:05:16'),
(523, 0, 'changeAvatarDescription', '2010-10-07 21:05:16'),
(524, 0, 'nodeCreatePanel', '2010-10-07 21:05:16'),
(525, 0, 'nodeRemovePanel', '2010-10-07 21:05:16'),
(526, 0, 'panelNodeDescription', '2010-10-07 21:05:16'),
(527, 0, 'nodeCreatePanelMessage', '2010-10-07 21:05:16'),
(528, 0, 'nodeRemovePanelMessage', '2010-10-07 21:05:16'),
(529, 0, 'dateInXSecondsLong', '2010-10-07 21:05:16'),
(530, 0, 'dateInXMinutesLong', '2010-10-07 21:05:16'),
(531, 0, 'dateInXHoursLong', '2010-10-07 21:05:16'),
(532, 0, 'dateInXDaysLong', '2010-10-07 21:05:16'),
(533, 0, 'dateInXMonthsLong', '2010-10-07 21:05:16'),
(534, 0, 'dateInXYearsLong', '2010-10-07 21:05:16'),
(535, 0, 'dateInXSecondsShort', '2010-10-07 21:05:16'),
(536, 0, 'dateInXMinutesShort', '2010-10-07 21:05:16'),
(537, 0, 'dateInXHoursShort', '2010-10-07 21:05:16'),
(538, 0, 'dateInXDaysShort', '2010-10-07 21:05:16'),
(539, 0, 'dateInXMonthsShort', '2010-10-07 21:05:16'),
(540, 0, 'dateInXYearsShort', '2010-10-07 21:05:16'),
(541, 0, 'dateSecondsLong', '2010-10-07 21:05:16'),
(542, 0, 'dateMinutesLong', '2010-10-07 21:05:16'),
(543, 0, 'dateHoursLong', '2010-10-07 21:05:16'),
(544, 0, 'dateDaysLong', '2010-10-07 21:05:16'),
(545, 0, 'dateMonthsLong', '2010-10-07 21:05:16'),
(546, 0, 'dateYearsLong', '2010-10-07 21:05:16'),
(547, 0, 'dateSecondsShort', '2010-10-07 21:05:16'),
(548, 0, 'dateMinutesShort', '2010-10-07 21:05:16'),
(549, 0, 'dateHoursShort', '2010-10-07 21:05:16'),
(550, 0, 'dateDaysShort', '2010-10-07 21:05:16'),
(551, 0, 'dateMonthsShort', '2010-10-07 21:05:16'),
(552, 0, 'dateYearsShort', '2010-10-07 21:05:16'),
(553, 0, 'loginAlreadyLoggedIn', '2010-10-07 21:05:16'),
(554, 0, 'logoutButton', '2010-10-07 21:05:16'),
(555, 0, 'backToReferer', '2010-10-07 21:05:16'),
(556, 0, 'loginSuccessful', '2010-10-07 21:05:16'),
(557, 0, 'logoutSuccessful', '2010-10-07 21:05:16'),
(558, 0, 'theLogin', '2010-10-07 21:05:16'),
(559, 0, 'theLogout', '2010-10-07 21:05:16'),
(560, 0, 'groupListMessage', '2010-10-07 21:05:16'),
(561, 0, 'groupListName', '2010-10-07 21:05:16'),
(562, 0, 'groupListMemberCount', '2010-10-07 21:05:16'),
(563, 0, 'groupMemberCountLabel', '2010-10-07 21:05:16'),
(564, 0, 'backendPageTitle', '2010-11-03 13:34:07'),
(565, 0, 'projects', '2010-11-13 21:15:20'),
(566, 0, 'projectListMessage', '2010-11-13 21:16:30'),
(567, 0, 'projectGroupsTitle', '2010-11-22 18:45:18'),
(568, 0, 'projectGroupsMessage', '2010-11-22 18:48:52'),
(569, 0, 'organizationGroupsMessage', '2010-11-22 18:49:19'),
(570, 0, 'organizationGroupsEmptyMessage', '2010-11-22 18:50:14'),
(571, 0, 'projectGroupsEmptyMessage', '2010-11-22 18:50:33'),
(572, 0, 'groupProject', '2010-11-22 19:20:47'),
(573, 0, 'groupProjectNotFoundError', '2010-11-26 19:12:37'),
(574, 0, 'addGroupProjectListMessage', '2010-12-28 13:15:28'),
(575, 0, 'enableUserLabel', '2010-12-28 20:49:04'),
(576, 0, 'enableUserDescription', '2010-12-28 20:50:09'),
(577, 0, 'userJoinNoGroupSelectedError', '2010-12-29 23:40:14'),
(578, 0, 'editGroupRightsMessage', '2010-12-31 14:25:23'),
(579, 0, 'groupLoginConfirmationRequiredLabel', '2011-01-02 15:34:37'),
(580, 0, 'groupLoginConfirmationRequiredDescription', '2011-01-02 15:35:10'),
(581, 0, 'loginConfirmationPageTitle', '2011-01-02 16:56:30'),
(582, 0, 'loginConfirmationMessage', '2011-01-02 16:58:15'),
(583, 0, 'confirmLogin', '2011-01-02 18:44:49'),
(584, 0, 'loginConfirmationErrorMessage', '2011-01-02 19:09:58'),
(585, 0, 'loginConfirmationFinishedMessage', '2011-01-02 19:10:17'),
(586, 0, 'loginConfirmationFinishedIframeMessage', '2011-01-02 19:10:56'),
(587, 0, 'loggedOutLoginTitle', '2011-01-02 20:11:48'),
(588, 0, 'addGroupProjectListEmptyMessage', '2011-01-02 21:26:31'),
(589, 0, 'userJoinDeniedError', '2011-01-02 21:48:37'),
(590, 0, 'deleteOwnUserError', '2011-01-03 16:10:48'),
(591, 0, 'deleteOwnUserDescription', '2011-01-03 16:19:51'),
(592, 0, 'now', '2011-01-06 16:40:15'),
(594, 0, 'invalidStructureNodeName', '2011-01-09 15:02:38'),
(595, 0, 'addTreeNodeChildError', '2011-01-09 18:20:38'),
(596, 0, 'browseStructureNodesTitle', '2011-01-09 18:45:20'),
(599, 0, 'nodePermissionsMessage', '2011-01-21 19:06:34'),
(598, 0, 'upperStructureNode', '2011-01-09 19:06:00'),
(600, 0, 'everyone', '2011-01-21 21:48:19'),
(601, 0, 'styles', '2011-01-24 18:55:33'),
(602, 0, 'styleTitle', '2011-01-24 18:55:38'),
(603, 0, 'styleAuthor', '2011-01-24 18:55:41'),
(604, 0, 'styleDescription', '2011-01-24 18:55:46'),
(605, 0, 'styleIsEnabledColumn', '2011-01-24 18:55:53'),
(606, 0, 'styleIsDefaultColumn', '2011-01-24 18:56:00'),
(607, 0, 'stylesMessage', '2011-01-24 19:02:19'),
(608, 0, 'saveButton', '2011-01-24 19:21:13'),
(609, 0, 'noStyleEnabledError', '2011-01-24 20:18:34'),
(610, 0, 'defaultStyleDisabledError', '2011-01-24 20:20:23'),
(611, 0, 'selectStyleButton', '2011-01-28 18:40:00'),
(612, 0, 'selectStyleButtonTitle', '2011-01-28 18:40:57'),
(614, 0, 'passwordLostNoEmailInputtedError', '2011-02-05 22:54:26'),
(615, 0, 'passwordLostEmailNotFoundError', '2011-02-05 17:48:46'),
(616, 0, 'passwordLostEmailTitle', '2011-02-05 18:11:34'),
(617, 0, 'passwordLostSecondMessage', '2011-02-05 22:58:34'),
(618, 0, 'passwordLostUserDisabledError', '2011-02-05 23:53:55'),
(619, 0, 'passwordLostKeyUserDisabledError', '2011-02-06 00:02:54'),
(620, 0, 'passwordLostInvalidKeyError', '2011-02-06 00:03:31'),
(621, 0, 'passwordLostThirdMessage', '2011-02-06 00:09:47'),
(622, 0, 'emailAddressAlreadyInUseError', '2011-02-06 00:29:38'),
(623, 0, 'noCurrentPasswordInputtetError', '2011-02-08 16:21:55'),
(626, 0, 'guestChangesPasswordMessage', '2011-02-08 16:46:46'),
(627, 0, 'changePasswordMessage', '2011-02-08 16:48:46'),
(628, 0, 'currentPasswordLabel', '2011-02-08 16:49:02'),
(629, 0, 'currentPasswordDescription', '2011-02-08 16:49:13'),
(630, 0, 'wrongCurrentPasswordInputtetError', '2011-02-08 16:51:45'),
(631, 0, 'changedEmailToUnconfirmedMessage', '2011-02-08 18:06:55'),
(632, 0, 'guestChangesEmailMessage', '2011-02-08 18:08:42'),
(633, 0, 'cantRemoveEmailError', '2011-02-08 18:09:18'),
(634, 0, 'emailNotChangedMessage', '2011-02-16 18:03:31'),
(635, 0, 'todayPhrase', '2011-02-16 18:24:04'),
(636, 0, 'yesterdayPhrase', '2011-02-16 18:24:09'),
(637, 0, 'tomorrowPhrase', '2011-02-16 18:24:14'),
(638, 0, 'emailConfirmedAccountActivatedMessage', '2011-02-16 20:12:03'),
(639, 0, 'emailConfirmedMessage', '2011-02-16 20:13:10'),
(640, 0, 'guestChangesUserNameMessage', '2011-02-16 21:09:34'),
(641, 0, 'changeUserNameLabel', '2011-02-16 21:12:41'),
(642, 0, 'changeUserNameDescription', '2011-02-16 21:12:48'),
(643, 0, 'changeUserNameMessage', '2011-02-16 21:17:09'),
(644, 0, 'userNameChangedMessage', '2011-02-16 21:19:26'),
(645, 0, 'changeOrRemoveEmailMessage', '2011-02-16 21:22:37'),
(646, 0, 'changeEmailMessage', '2011-02-16 21:22:51'),
(647, 0, 'registrationEmailOptionalMessage', '2011-02-16 21:45:14'),
(648, 0, 'registrationMessage', '2011-02-16 21:46:13'),
(649, 0, 'userAvatarPageTitle', '2011-02-17 20:43:38'),
(650, 0, 'changeAvatarFileTooLargeError', '2011-02-18 22:08:25'),
(651, 0, 'guestChangesAvatarMessage', '2011-02-18 22:30:59'),
(652, 7, 'widgetClassesTitle', '2011-04-02 23:24:19'),
(653, 7, 'sidebarAdminMessage', '2011-03-09 15:30:51'),
(654, 7, 'viewonlineWidgetTitle', '2011-03-09 15:49:22'),
(655, 7, 'noUserOnline', '2011-03-11 23:07:09'),
(656, 7, 'loginWidgetLoginButton', '2011-03-12 17:01:55'),
(657, 7, 'loginWidgetUserLabel', '2011-03-12 17:02:01'),
(658, 7, 'loginWidgetPasswordLabel', '2011-03-12 17:02:09'),
(659, 7, 'loginWidgetLoggedInAs', '2011-03-12 17:07:25'),
(660, 7, 'loginWidgetLogoutButton', '2011-03-12 17:13:59'),
(661, 7, 'addWidgetButton', '2011-03-31 19:59:26'),
(662, 7, 'removeWidgetButton', '2011-03-31 21:13:54'),
(663, 7, 'moveWidgetUpButton', '2011-03-31 21:15:19'),
(664, 7, 'moveWidgetDownButton', '2011-03-31 21:15:25'),
(665, 7, 'subpagesWidgetNoSubpagesMessage', '2011-04-01 23:24:56'),
(666, 7, 'clockWidgetDateFormat', '2011-04-01 23:42:48'),
(669, 7, 'guestEditsSidebarMessage', '2011-04-02 23:31:02'),
(668, 7, 'sidebarAdmin', '2011-04-02 23:23:00'),
(670, 7, 'mySidebar', '2011-04-02 23:31:32'),
(671, 7, 'mySidebarMessage', '2011-04-02 23:32:54'),
(672, 7, 'resetMySidebarMessage', '2011-04-03 22:20:00'),
(673, 7, 'resetSidebarButton', '2011-04-09 00:10:00'),
(679, 7, 'resetSidebarMessage', '2011-04-09 00:10:39'),
(674, 7, 'resetMySidebarConfirmation', '2011-04-03 22:23:54'),
(675, 7, 'userSidebar', '2011-04-08 23:25:13'),
(676, 7, 'userSidebarDescription', '2011-04-08 23:25:13'),
(677, 7, 'editingGuestsSidebarError', '2011-04-09 00:07:31'),
(678, 7, 'resetSidebarConfirmation', '2011-04-09 00:32:54'),
(680, 7, 'sidebarMessage', '2011-04-09 00:33:48');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_stringstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_stringstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_stringstranslation`
--

INSERT INTO `premanager_stringstranslation` (`id`, `languageID`, `value`, `timestamp`) VALUES
(1, 1, 0x417274696b656cc3bc6265727369636874, '2010-02-27 17:43:18'),
(1, 2, 0x41727469636c6573, '2010-02-27 17:43:27'),
(8, 1, 0x5a7567726966662076657277656967657274, '2010-02-27 17:50:47'),
(8, 2, 0x4163636573732064656e696564, '2010-02-27 17:51:02'),
(9, 1, 0x44752062697374206e6963687420626572656368746967742c206469657365204f7065726174696f6e2064757263687a7566c3bc6872656e20627a772e206469657365205365697465206175667a75727566656e2e, '2010-02-27 17:51:52'),
(9, 2, 0x596f7520617265206e6f7420616c6c6f77656420746f207669657720746869732070616765206f7220746f2065786563757465207468697320616374696f6e2e, '2010-02-27 17:52:05'),
(7, 1, 0x5a7572c3bc636b207a75722053746172747365697465, '2010-02-27 17:52:18'),
(7, 2, 0x6261636b20746f20686f6d652070616765, '2010-02-27 17:52:30'),
(2, 1, 0x5365697465206e6963687420676566756e64656e, '2010-02-27 17:52:52'),
(2, 2, 0x50616765206e6f7420666f756e64, '2010-02-27 17:53:02'),
(6, 1, 0x46616c6c7320647520766f6e2065696e657220616e646572656e205365697465206869657268696e207665726c696e6b7420777572646573742c206b6f6e74616b74696572652062697474652064656e20426574726569626572206469657365722053656974652e20446572204c696e6b20736f6c6c746520616e676570617373742077657264656e2e, '2010-02-27 17:53:27'),
(6, 2, 0x496620796f7520636c69636b65642061206c696e6b2066726f6d20616e6f746865722077656220736974652c20706c6561736520636f6e746163742074686569722061646d696e6973747261746f722e20546865792073686f756c6420636f727265637420746865206c696e6b2e, '2010-02-27 17:54:13'),
(5, 1, 0x46616c6c732064752065696e656e204c696e6b20616e67656b6c69636b7420686173742c206465722061756620646965736572205365697465207761722c2068616e64656c7420657320736963682068c3b6636873747761687273636865696e6c69636820756d2065696e656e204665686c6572206469657365722057656273656974652e204b6f6e74616b74696572652064616e6e20626974746520646173205465616d2c2064616d6974207769722064696573656e204665686c657220736f207363686e656c6c20776965206dc3b6676c696368207a75206b6f72726967696572656e206bc3b66e6e656e2e, '2010-02-27 17:55:04'),
(5, 2, 0x496620796f7520636c69636b65642061206c696e6b2066726f6d207468697320776562207369746520796f7520666f756e642061206d697374616b652e20506c6561736520636f6e7461637420757320736f2074686174207765206172652061626c6520746f2066697820746869732070726f626c656d20617320736f6f6e20617320706f737369626c652e, '2010-02-27 17:56:11'),
(3, 1, 0x456e74736368756c646967756e672c2064696520616e6765666f726465727465205365697465207775726465206c6569646572206e6963687420676566756e64656e2e, '2010-02-27 18:02:23'),
(3, 2, 0x536f7272792c2074686973207061676520646f6573206e6f742065786973742e, '2010-02-27 17:58:58'),
(4, 1, 0x57656e6e206475206469652041647265737365206d616e75656c6c2065696e6765676562656e20686173742c20c3bc6265727072c3bc6665207369652062697474652061756620646965206b6f7272656b7465205363687265696277656973652e204265616368746520617563682c2064617373207a7769736368656e2047726fc39f2d20756e64204b6c65696e73636872656962756e6720756e746572736368696564656e20776972642e, '2010-02-27 18:00:18'),
(4, 2, 0x506c6561736520636865636b20746865206164647265737320666f72207370656c6c696e67206d697374616b65732e, '2010-02-27 18:01:19'),
(10, 1, 0x57657220697374206f6e6c696e653f, '2010-03-03 21:41:30'),
(10, 2, 0x57686f206973206f6e6c696e653f, '2010-03-03 21:41:42'),
(11, 1, 0x4b6c69636b6520686965722c20756d206d6568722044657461696c73207a7520657266616872656e, '2010-03-03 21:57:00'),
(11, 2, 0x436c69636b206865726520746f20766965772064657461696c73, '2010-03-03 21:57:18'),
(12, 1, 0x4b6c69636b6520686965722c20756d207a752065696e656d206772c3b6c39f6572656e20416e6d656c6465666f726d756c6172206d697420776569746572656e204f7074696f6e656e207a752067656c616e67656e, '2010-03-04 18:38:46'),
(12, 2, 0x436c69636b206865726520746f207669657720616e20656e6c6172676564206c6f67696e20666f726d20776974682066757274686572206f7074696f6e73, '2010-03-04 18:38:46'),
(15, 1, 0x416e6d656c64656e, '2010-03-04 18:39:22'),
(15, 2, 0x4c6f67696e, '2010-03-04 18:39:22'),
(14, 1, 0x50617373776f7274, '2010-03-10 18:29:19'),
(14, 2, 0x50617373776f7264, '2010-03-10 18:29:29'),
(13, 1, 0x42656e75747a6572, '2010-03-10 18:29:33'),
(13, 2, 0x55736572, '2010-03-10 18:29:37'),
(16, 1, 0x416e6d656c64756e67, '2010-03-04 18:40:27'),
(16, 2, 0x4c6f67696e, '2010-03-04 18:40:27'),
(23, 1, 0x4b6c69636b6520686965722c20756d207a756d2070657273c3b66e6c696368656e2042657265696368207a752067656c616e67656e, '2010-03-04 21:05:58'),
(23, 2, 0x436c69636b206865726520746f20656e74657220706572736f6e616c20636f6e74726f6c2070616e656c, '2010-03-04 21:06:08'),
(18, 1, 0x416e67656d656c64657420616c73207b757365724e616d652068746d6c7d, '2010-05-22 13:43:42'),
(18, 2, 0x4c6f6767656420696e206173207b757365724e616d652068746d6c7d, '2010-03-05 22:06:17'),
(19, 1, 0x41626d656c64656e, '2010-03-04 21:03:51'),
(19, 2, 0x4c6f676f7574, '2010-03-04 21:04:03'),
(20, 1, 0x50657273c3b66e6c69636865722042657265696368, '2010-03-04 21:04:39'),
(20, 2, 0x506572736f6e616c20436f6e74726f6c2050616e656c, '2010-03-04 21:04:39'),
(21, 1, 0x4d65696e2050726f66696c, '2010-03-04 21:05:13'),
(21, 2, 0x4d792050726f66696c65, '2010-03-04 21:05:13'),
(24, 1, 0x416e6d656c64656e, '2010-03-04 21:18:49'),
(24, 2, 0x4c6f67696e, '2010-03-04 21:18:49'),
(25, 1, 0x42656e75747a65726e616d65, '2010-03-10 18:29:45'),
(25, 2, 0x55736572204e616d65, '2010-03-10 18:29:49'),
(26, 1, 0x50617373776f7274, '2010-03-10 18:29:56'),
(26, 2, 0x50617373776f7264, '2010-03-10 18:30:01'),
(27, 1, 0x416e6d656c64756e67, '2010-03-04 21:28:44'),
(27, 2, 0x4c6f67696e, '2010-03-04 21:28:44'),
(29, 1, 0x44696520416e6d656c64756e6720697374206665686c67657363686c6167656e2e20426974746520c3bc6265727072c3bc66652042656e75747a65726e616d6520756e642050617373776f72742e, '2010-03-04 21:35:36'),
(29, 2, 0x536f7272792c2062757420796f7572206c6f672d696e206461746120697320696e636f72726563742e20506c6561736520636865636b2075736572206e616d6520616e642070617373776f72642e, '2010-03-04 21:35:36'),
(28, 1, 0x416e6d656c64756e67206665686c67657363686c6167656e, '2010-03-04 21:36:08'),
(28, 2, 0x4c6f67696e206661696c6564, '2010-03-04 21:36:08'),
(30, 1, 0x4475206b6f6e6e74657374206e6963687420696d2053797374656d20616e67656d656c6465742077657264656e2e20426974746520c3bc6265727072c3bc66652064656e2065696e6765676562656e656e2042656e75747a65726e616d656e20756e64206465696e2050617373776f72742e, '2010-03-04 21:47:14'),
(30, 2, 0x57652077657265206e6f742061626c6520746f206c6f6720796f7520696e2e20506c6561736520636865636b2075736572206e616d6520616e642070617373776f72642e, '2010-03-04 21:47:14'),
(31, 1, 0x48617374206475206465696e2050617373776f72742076657267657373656e3f204b65696e2050726f626c656d2c20776972206bc3b66e6e656e206469722065696e206e65756573207a7573636869636b656e2e, '2010-03-04 21:49:53'),
(31, 2, 0x4861766520796f7520666f72676f7474656e20796f752070617373776f72643f204e6f2070726f626c656d2c2077652063616e2073656e6420796f752061206e6577206f6e652e, '2010-03-04 21:49:53'),
(32, 1, 0x466f6c67652064696573656d204c696e6b2c20756d2065696e206e657565732050617373776f7274207a752062656b6f6d6d656e2e, '2010-03-04 21:50:17'),
(32, 2, 0x466f6c6c6f772074686973206c696e6b20746f206765742061206e65772070617373776f72642e, '2010-03-04 21:50:17'),
(34, 1, 0x44752077757264657374206572666f6c67726569636820616e67656d656c6465742e, '2010-03-05 16:27:20'),
(33, 1, 0x4dc3b66368746573742064752065732065726e6575742076657273756368656e3f, '2010-03-04 21:54:01'),
(33, 2, 0x446f20796f752077616e7420746f2074727920697420616761696e3f, '2010-03-04 21:54:01'),
(34, 2, 0x596f752068617665206265656e206c6f6767656420696e207375636365737366756c6c792e, '2010-03-05 16:43:53'),
(35, 1, 0x4d6f6d656e74616e20697374206b65696e20726567697374726965727465722042656e75747a6572206175662064696573657220576562736974652e, '2011-02-16 21:25:18'),
(35, 2, 0x417420746865206d6f6d656e742c207468657265206973206e6f20726567697374657265642075736572206f6e2074686973207765622073697465, '2011-02-16 21:25:40'),
(36, 1, 0x4469657365205365697465207a65696774206469722c2077656c636865207265676973747269657274656e2042656e75747a657220696e2064656e206c65747a74656e207b74696d655370616e7d204d696e7574656e206f6e6c696e6520776172656e2e, '2011-02-16 21:26:19'),
(36, 2, 0x4865726520796f752063616e207365652077686963682075736572732077657265206f6e6c696e6520696e207468652070617374207b74696d655370616e7d206d696e757465732e, '2010-03-05 16:52:10'),
(38, 1, 0x4c65747a746520416b7469766974c3a474, '2010-03-05 17:05:03'),
(38, 2, 0x4c61737420616374696f6e, '2010-03-05 17:05:03'),
(39, 1, 0x416b7475656c6c65205365697465, '2010-03-05 17:05:49'),
(39, 2, 0x43757272656e74206c6f636174696f6e, '2010-03-05 17:05:49'),
(37, 1, 0x42656e75747a6572, '2010-03-05 17:06:14'),
(37, 2, 0x55736572, '2010-03-05 17:06:14'),
(41, 1, 0x4d6f6e746167, '2010-03-05 22:51:48'),
(42, 1, 0x4469656e73746167, '2010-03-05 22:51:48'),
(43, 1, 0x4d697474776f6368, '2010-03-05 22:51:48'),
(44, 1, 0x446f6e6e657273746167, '2010-03-05 22:51:48'),
(45, 1, 0x46726569746167, '2010-03-05 22:51:48'),
(46, 1, 0x53616d73746167, '2010-03-05 22:51:48'),
(47, 1, 0x536f6e6e746167, '2010-03-05 22:51:48'),
(48, 1, 0x4d6f, '2010-03-05 22:51:48'),
(49, 1, 0x4469, '2010-03-05 22:51:48'),
(50, 1, 0x4d69, '2010-03-05 22:51:48'),
(51, 1, 0x446f, '2010-03-05 22:51:48'),
(52, 1, 0x4672, '2010-03-05 22:51:48'),
(53, 1, 0x5361, '2010-03-05 22:51:48'),
(54, 1, 0x536f, '2010-03-05 22:51:48'),
(55, 1, 0x4a616e756172, '2010-03-05 22:51:48'),
(56, 1, 0x46656272756172, '2010-03-05 22:51:48'),
(57, 1, 0x4dc3a4727a, '2010-03-05 22:51:48'),
(58, 1, 0x417072696c, '2010-03-05 22:51:48'),
(59, 1, 0x4d6169, '2010-03-05 22:51:48'),
(60, 1, 0x4a756e69, '2010-03-05 22:51:48'),
(61, 1, 0x4a756c69, '2010-03-05 22:51:48'),
(62, 1, 0x417567757374, '2010-03-05 22:51:48'),
(63, 1, 0x53657074656d626572, '2010-03-05 22:51:48'),
(64, 1, 0x4f6b746f626572, '2010-03-05 22:51:48'),
(65, 1, 0x4e6f76656d626572, '2010-03-05 22:51:48'),
(66, 1, 0x44657a656d626572, '2010-03-05 22:51:48'),
(67, 1, 0x4a616e, '2010-03-05 22:51:48'),
(68, 1, 0x466562, '2010-03-05 22:51:48'),
(69, 1, 0x4dc3a472, '2010-03-05 22:51:48'),
(70, 1, 0x417072, '2010-03-05 22:51:48'),
(71, 1, 0x4d6169, '2010-03-05 22:51:48'),
(72, 1, 0x4a756e, '2010-03-05 22:51:48'),
(73, 1, 0x4a756c, '2010-03-05 22:51:48'),
(74, 1, 0x417567, '2010-03-05 22:51:48'),
(75, 1, 0x536570, '2010-03-05 22:51:48'),
(76, 1, 0x4f6b74, '2010-03-05 22:51:48'),
(77, 1, 0x4e6f76, '2010-03-05 22:51:48'),
(78, 1, 0x44657a, '2010-03-05 22:51:48'),
(41, 2, 0x4d6f6e646179, '2010-03-05 22:52:01'),
(42, 2, 0x54756573646179, '2010-03-05 22:52:01'),
(43, 2, 0x5765646e6573646179, '2010-03-05 22:52:01'),
(44, 2, 0x5468757273646179, '2010-03-05 22:52:01'),
(45, 2, 0x467269646179, '2010-03-05 22:52:01'),
(46, 2, 0x5361747572646179, '2010-03-05 22:52:01'),
(47, 2, 0x53756e646179, '2010-03-05 22:52:01'),
(48, 2, 0x4d6f6e, '2010-03-05 22:52:01'),
(49, 2, 0x547565, '2010-03-05 22:52:01'),
(50, 2, 0x576564, '2010-03-05 22:52:01'),
(51, 2, 0x546875, '2010-03-05 22:52:01'),
(52, 2, 0x467269, '2010-03-05 22:52:01'),
(53, 2, 0x536174, '2010-03-05 22:52:01'),
(54, 2, 0x53756e, '2010-03-05 22:52:01'),
(55, 2, 0x4a616e75617279, '2010-03-05 22:52:01'),
(56, 2, 0x4665627275617279, '2010-03-05 22:52:01'),
(57, 2, 0x4d61726368, '2010-03-05 22:52:01'),
(58, 2, 0x417072696c, '2010-03-05 22:52:01'),
(59, 2, 0x4d6179, '2010-03-05 22:52:01'),
(60, 2, 0x4a756e69, '2010-03-05 22:52:01'),
(61, 2, 0x4a756c79, '2010-03-05 22:52:01'),
(62, 2, 0x417567757374, '2010-03-05 22:52:01'),
(63, 2, 0x53657074656d626572, '2010-03-05 22:52:01'),
(64, 2, 0x4f63746f626572, '2010-03-05 22:52:01'),
(65, 2, 0x4e6f76656d626572, '2010-03-05 22:52:01'),
(66, 2, 0x446563656d626572, '2010-03-05 22:52:01'),
(67, 2, 0x4a616e, '2010-03-05 22:52:01'),
(68, 2, 0x466562, '2010-03-05 22:52:01'),
(69, 2, 0x4d6172, '2010-03-05 22:52:01'),
(70, 2, 0x417072, '2010-03-05 22:52:01'),
(71, 2, 0x4d6179, '2010-03-05 22:52:01'),
(72, 2, 0x4a756e, '2010-03-05 22:52:01'),
(73, 2, 0x4a756c, '2010-03-05 22:52:01'),
(74, 2, 0x417567, '2010-03-05 22:52:01'),
(75, 2, 0x536570, '2010-03-05 22:52:01'),
(76, 2, 0x4f6374, '2010-03-05 22:52:01'),
(77, 2, 0x4e6f76, '2010-03-05 22:52:01'),
(78, 2, 0x446563, '2010-03-05 22:52:01'),
(79, 1, 0x4765737465726e, '2010-03-05 23:25:45'),
(79, 2, 0x596573746572646179, '2010-03-05 23:25:45'),
(80, 1, 0x4865757465, '2010-03-05 23:28:23'),
(80, 2, 0x546f646179, '2010-03-05 23:28:47'),
(81, 1, 0x4d6f7267656e, '2010-03-05 23:29:04'),
(81, 2, 0x546f6d6f72726f77, '2010-03-05 23:29:04'),
(82, 1, 0x7b74696d65206c6f6e674461746554696d657d, '2010-03-05 23:38:45'),
(82, 2, 0x7b74696d65206c6f6e674461746554696d657d, '2010-03-05 23:38:51'),
(119, 1, 0x766f7220312053656b756e6465, '2010-03-06 00:17:01'),
(84, 1, 0x766f72207b6e756d7d2053656b756e64657b276e206966286e756d213d31297d, '2010-03-10 20:06:39'),
(167, 2, 0x53686f7773206120666f726d20776869636820616c6c6f777320796f7520746f206564697420746869732075736572, '2010-03-10 20:31:38'),
(86, 1, 0x766f72207b6e756d7d204d696e7574657b276e206966286e756d213d31297d, '2010-03-10 20:06:39'),
(88, 1, 0x766f72207b6e756d7d205374756e64657b276e206966286e756d213d31297d, '2010-03-10 20:06:39'),
(90, 1, 0x766f72207b6e756d7d205461677b27656e206966286e756d213d31297d, '2010-03-10 20:06:39'),
(92, 1, 0x766f72207b6e756d7d204d6f6e61747b27656e206966286e756d213d31297d, '2010-03-10 20:06:39'),
(167, 1, 0xc39666666e65742065696e20466f726d756c61722c206d69742064656d206469657365722042656e75747a657220626561726265697465742077657264656e206b616e6e, '2010-03-10 20:31:38'),
(94, 1, 0x766f72207b6e756d7d204a6168727b27656e206966286e756d213d31297d, '2010-03-10 20:06:39'),
(96, 1, 0x7b6e756d7d2053656b, '2010-03-06 00:17:01'),
(98, 1, 0x7b6e756d7d204d696e, '2010-03-06 00:17:01'),
(100, 1, 0x7b6e756d7d20537464, '2010-03-06 00:17:01'),
(166, 2, 0x53686f7773206120666f726d20776869636820616c6c6f777320796f7520746f206372656174652061206e65772075736572, '2010-03-10 20:31:38'),
(102, 1, 0x7b6e756d7d205461677b2765206966286e756d213d31297d, '2010-03-10 20:06:39'),
(104, 1, 0x7b6e756d7d204d6f6e61747b2765206966286e756d213d31297d, '2010-03-10 20:06:39'),
(106, 1, 0x7b6e756d7d204a6168727b2765206966286e756d213d31297d, '2010-03-10 20:07:33'),
(119, 2, 0x31207365636f6e642061676f, '2010-03-06 00:17:01'),
(84, 2, 0x7b6e756d7d207365636f6e647b2773206966286e756d213d31297d2061676f, '2010-03-10 20:07:33'),
(86, 2, 0x7b6e756d7d206d696e7574657b2773206966286e756d213d31297d2061676f, '2010-03-10 20:07:33'),
(166, 1, 0xc39666666e65742065696e20466f726d756c61722c206d69742064656d2065696e206e657565722042656e75747a65722065727374656c6c742077657264656e206b616e6e, '2010-03-10 20:31:38'),
(88, 2, 0x7b6e756d7d20686f75727b2773206966286e756d213d31297d2061676f, '2010-03-10 20:07:33'),
(165, 2, 0x44656c657465, '2010-03-10 20:29:33'),
(90, 2, 0x7b6e756d7d206461797b2773206966286e756d213d31297d2061676f, '2010-03-10 20:07:33'),
(165, 1, 0x4cc3b6736368656e, '2010-03-10 20:29:33'),
(92, 2, 0x7b6e756d7d206d6f6e74687b2773206966286e756d213d31297d2061676f, '2010-03-10 20:07:33'),
(164, 2, 0x45646974, '2010-03-10 20:29:33'),
(94, 2, 0x7b6e756d7d20796561727b2773206966286e756d213d31297d2061676f, '2010-03-10 20:07:33'),
(96, 2, 0x7b6e756d7d20736563, '2010-03-06 00:17:01'),
(164, 1, 0x4265617262656974656e, '2010-03-10 20:29:33'),
(98, 2, 0x7b6e756d7d206d696e, '2010-03-06 00:17:01'),
(100, 2, 0x7b6e756d7d20686f75727b2773206966286e756d213d31297d, '2010-03-10 20:07:33'),
(163, 2, 0x4164642055736572, '2010-03-10 20:25:29'),
(102, 2, 0x7b6e756d7d206461797b2773206966286e756d213d31297d, '2010-03-10 20:07:33'),
(104, 2, 0x7b6e756d7d206d6f6e74687b2773206966286e756d213d31297d, '2010-03-10 20:07:33'),
(163, 1, 0x42656e75747a65722068696e7a7566c3bc67656e, '2010-03-10 20:25:29'),
(106, 2, 0x7b6e756d7d20796561727b2773206966286e756d213d31297d, '2010-03-10 20:07:33'),
(120, 1, 0x41766174617220766f6e207b757365724e616d652068746d6c7d, '2010-03-06 00:57:28'),
(120, 2, 0x417661746172206f66207b757365724e616d652068746d6c7d, '2010-03-06 00:57:28'),
(121, 1, 0x5365697465207b706167657d, '2010-03-06 19:03:40'),
(121, 2, 0x50616765207b706167657d, '2010-03-06 19:03:40'),
(122, 1, 0x576569746572, '2010-03-06 19:05:02'),
(122, 2, 0x4e657874, '2010-03-06 19:05:02'),
(123, 1, 0x5a7572c3bc636b, '2010-03-06 19:05:02'),
(123, 2, 0x4261636b, '2010-03-06 19:05:02'),
(124, 1, 0x2e2e2e, '2010-03-06 23:04:50'),
(124, 2, 0x2e2e2e, '2010-03-06 23:04:50'),
(125, 1, 0x45696e646575746967657220526573736f757263656e62657a656963686e6572, '2010-03-06 23:19:46'),
(125, 2, 0x556e69717565205265736f75726365204964656e746966696572, '2010-03-06 23:19:46'),
(126, 1, 0x53746172747365697465, '2010-03-06 23:19:46'),
(126, 2, 0x486f6d65, '2010-03-06 23:19:46'),
(127, 1, 0x41756677c3a4727473, '2010-03-06 23:19:46'),
(127, 2, 0x5570, '2010-03-06 23:19:46'),
(128, 1, 0x42656e75747a6572, '2010-03-07 00:27:59'),
(128, 2, 0x5573657273, '2010-03-07 00:24:09'),
(129, 1, 0x417566206469657365722053656974652073696e6420616c6c65207265676973747269657274656e2042656e75747a657220756e642064617320476173746b6f6e746f2061756667656c69737465742e, '2011-02-16 21:24:44'),
(130, 1, 0x5a75727a6569742073696e64206b65696e652042656e75747a65726b6f6e74656e20616e67656c6567742e, '2010-03-07 00:35:16'),
(130, 2, 0x546865726520617265206e6f2075736572206163636f756e747320617420746865206d6f6d656e742e, '2010-03-07 00:35:16'),
(131, 1, 0x42656e75747a65726b6f6e74656e, '2010-03-07 00:39:30'),
(131, 2, 0x55736572204163636f756e7473, '2010-03-07 00:39:30'),
(132, 1, 0x4d6974676c6965642073656974, '2010-03-07 00:39:30'),
(132, 2, 0x526567697374726174696f6e2044617465, '2010-03-07 00:39:30'),
(133, 1, 0x4c65747a746520416e6d656c64756e67, '2010-03-07 00:39:30'),
(133, 2, 0x4c617374204c6f67696e, '2010-03-07 00:39:30'),
(134, 1, 0x4469657365722042656e75747a657220657869737469657274206e696368742e, '2010-03-07 00:43:26'),
(134, 2, 0x54686973207573657220646f6573206e6f742065786973742e, '2010-03-07 00:44:04'),
(135, 1, 0x44696573657220417274696b656c20657869737469657274206e696368742e, '2010-03-07 00:43:50'),
(135, 2, 0x546869732061727469636c6520646f6573206e6f742065786973742e, '2010-03-07 00:43:50'),
(136, 1, 0xe28094, '2010-03-07 00:53:39'),
(136, 2, 0xe28094, '2010-03-07 00:53:39'),
(137, 1, 0x4f6e6c696e652d53746174757320766572737465636b656e, '2010-03-07 16:35:51'),
(137, 2, 0x48696465206d79206c6f67696e20737461747573, '2010-03-07 16:35:53'),
(138, 1, 0x47617374, '2010-03-07 16:48:33'),
(138, 2, 0x4775657374, '2010-03-07 16:48:33'),
(139, 1, 0x7b636f756e747d207b274761737420696628636f756e743d3d31297d7b2747c3a473746520696628636f756e74213d31297d, '2010-03-07 17:18:21'),
(139, 2, 0x7b636f756e747d2047756573747b277320696628636f756e74213d31297d, '2010-03-10 20:46:39'),
(169, 1, 0x4772757070652068696e7a7566c3bc67656e, '2010-03-10 20:51:05'),
(142, 1, 0x42656e75747a65726e616d65, '2010-03-07 17:42:11'),
(142, 2, 0x55736572204e616d65, '2010-03-07 17:42:11'),
(143, 1, 0x546974656c, '2010-03-07 17:42:11'),
(143, 2, 0x5469746c65, '2010-03-07 17:42:11'),
(144, 1, 0x20e2809320, '2010-03-07 17:46:33'),
(144, 2, 0x20e2809320, '2010-03-07 17:46:36'),
(145, 1, 0x417661746172, '2010-03-08 19:45:16'),
(145, 2, 0x417661746172, '2010-03-08 19:46:04'),
(148, 1, 0x4d6974676c69656473636861667420696e204772757070656e, '2010-03-08 20:15:27'),
(148, 2, 0x47726f7570206d656d62657273686970, '2010-03-08 20:15:27'),
(149, 1, 0x4772757070656e6c6569746572, '2010-03-08 21:24:39'),
(149, 2, 0x4c6561646572, '2010-03-08 21:24:31'),
(150, 1, 0x42656e75747a65726772757070656e, '2010-03-08 21:10:20'),
(150, 2, 0x557365722047726f757073, '2010-03-08 21:10:20'),
(151, 1, 0x45732077757264656e206e6f6368206b65696e652042656e75747a65726772757070656e2065727374656c6c742e, '2010-03-08 21:15:03'),
(151, 2, 0x546865726520617265206e6f20757365722067726f757073207965742e, '2010-03-08 21:15:03'),
(152, 1, 0x7b636f756e747d207b274d6974676c69656420696628636f756e743d31297d7b274d6974676c696564657220696628636f756e74213d31297d, '2010-03-08 21:23:51'),
(152, 2, 0x7b636f756e747d207b274d656d62657220696628636f756e743d31297d7b274d656d6265727320696628636f756e74213d31297d, '2010-03-08 21:24:00'),
(153, 1, 0x44696573652042656e75747a657267727570706520657869737469657274206e696368742e, '2010-03-08 21:32:32'),
(153, 2, 0x5468697320757365722067726f757020646f6573206e6f742065786973742e, '2010-03-08 21:32:32'),
(154, 1, 0x4772757070656e6e616d65, '2010-03-08 21:32:32'),
(154, 2, 0x47726f7570204e616d65, '2010-03-08 21:32:32'),
(155, 1, 0x4d6974676c6965646572746974656c, '2010-03-08 21:32:32'),
(155, 2, 0x4d656d6265722773205469746c65, '2010-03-08 21:32:32'),
(156, 1, 0x426573636872656962756e67, '2010-03-08 21:32:32'),
(156, 2, 0x4465736372697074696f6e, '2010-03-08 21:32:32'),
(157, 1, 0x287b636f6e74656e747d29, '2010-03-10 18:26:19'),
(157, 2, 0x287b636f6e74656e747d29, '2010-03-10 18:26:19'),
(158, 1, 0x4d6974676c6965646572206469657365722042656e75747a6572677275707065, '2010-03-10 18:26:19'),
(158, 2, 0x4d656d62657273206f6620746869732067726f7570, '2010-03-10 18:26:19'),
(159, 1, 0x5365697465207b706167657d20766f6e207b636f756e747d, '2010-03-10 18:26:19'),
(159, 2, 0x50616765207b706167657d206f66207b636f756e747d, '2010-03-10 18:26:19'),
(160, 1, 0x7b6c6162656c7d3a, '2010-03-10 18:28:08'),
(160, 2, 0x7b6c6162656c7d3a, '2010-03-10 18:28:08'),
(161, 1, 0x4772757070656e6c6569746572, '2010-03-10 18:41:59'),
(161, 2, 0x47726f7570204c656164657273, '2010-03-10 18:41:59'),
(162, 1, 0x4772757070656e6d6974676c6965646572, '2010-03-10 18:42:13'),
(162, 2, 0x47726f7570204d656d62657273, '2010-03-10 18:42:13'),
(168, 1, 0x4cc3b6736368742064696573656e2042656e75747a6572, '2010-03-10 20:31:38'),
(168, 2, 0x44656c6574657320746869732075736572, '2010-03-10 20:42:52'),
(169, 2, 0x4164642047726f7570, '2010-03-10 20:51:05'),
(170, 1, 0xc39666666e65742065696e20466f726d756c61722c206d69742064656d2065696e65206e6575652042656e75747a65726772757070652065727374656c6c742077657264656e206b616e6e, '2010-03-10 20:51:05'),
(170, 2, 0x53686f7773206120666f726d20776869636820616c6c6f777320796f7520746f206372656174652061206e657720757365722067726f7570, '2010-03-10 20:51:05'),
(171, 1, 0x4265617262656974656e, '2010-03-10 20:51:05'),
(171, 2, 0x45646974, '2010-03-10 20:51:05'),
(172, 1, 0xc39666666e65742065696e20466f726d756c61722c206d69742064656d2064696573652042656e75747a657267727570706520626561726265697465742077657264656e206b616e6e, '2010-03-10 20:51:05'),
(172, 2, 0x53686f7773206120666f726d20776869636820616c6c6f777320796f7520746f2065646974207468697320757365722067726f7570, '2010-03-10 20:51:05'),
(173, 1, 0x4cc3b6736368656e, '2010-03-10 20:51:05'),
(173, 2, 0x44656c657465, '2010-03-10 20:51:05'),
(174, 1, 0x4cc3b6736368742064696573652042656e75747a6572677275707065, '2010-03-10 20:51:05'),
(174, 2, 0x44656c65746573207468697320757365722067726f7570, '2010-03-10 20:51:05'),
(175, 1, 0x42656e75747a65726772757070656e, '2010-03-10 20:54:27'),
(175, 2, 0x557365722047726f757073, '2010-03-10 20:54:27'),
(176, 1, 0x5a6569677420646965204c69737465206465722042656e75747a65726772757070656e, '2010-03-10 20:54:27'),
(176, 2, 0x53686f777320746865206c697374206f6620757365722067726f757073, '2010-03-10 20:54:27'),
(177, 1, 0x42656e75747a65726c69737465, '2010-03-10 20:54:27'),
(177, 2, 0x5573657273, '2010-03-10 20:54:27'),
(178, 1, 0x5a6569677420646965204c69737465206465722042656e75747a6572, '2010-03-10 20:54:27'),
(178, 2, 0x53686f777320746865206c697374206f66207573657273, '2010-03-10 20:54:27'),
(179, 1, 0x44696573652053656974652076657266c3bc677420c3bc626572206b65696e656e20496e68616c742e, '2010-03-29 20:32:43'),
(179, 2, 0x54686973207061676520697320656d7074792e, '2010-03-31 23:14:35'),
(180, 1, 0x5365697465206e6963687420676566756e64656e, '2010-03-31 20:18:53'),
(180, 2, 0x50616765204e6f7420466f756e64, '2010-03-31 20:18:53'),
(182, 1, 0x5a757220c3bc62657267656f72646e6574656e2c206578697374696572656e64656e205365697465207765636873656c6e, '2010-03-31 20:32:55'),
(182, 2, 0x4261636b20746f206578697374696e672070616765, '2010-03-31 23:15:20'),
(183, 2, 0x546869732069732074686520737472756374757265206f6620757365722d646566696e65642070616765732e20486f766520616e206974656d20746f20766965772075736566756c20746f6f6c7320746f206368616e676520746865207374727563747572652e, '2010-04-07 00:41:01'),
(184, 1, 0x4269736865722077757264656e206e6f6368206b65696e652062656e75747a6572646566696e69657274656e2053656974656e2065727374656c6c742e, '2010-04-01 18:33:45'),
(184, 2, 0x546865726520617265206e6f20757365722d646566696e65642070616765732c207965742e, '2010-04-01 18:33:45'),
(185, 1, 0x53656974656e6e616d65, '2010-04-01 18:39:27'),
(185, 2, 0x50616765204e616d65, '2010-04-01 18:39:27'),
(186, 1, 0x416e67657a656967746572204e616d65, '2010-04-01 18:39:27'),
(186, 2, 0x446973706c6179204e616d65, '2010-04-01 18:39:27'),
(187, 1, 0x285374617274736569746529, '2010-04-01 19:09:26'),
(187, 2, 0x28486f6d65205061676529, '2010-04-01 19:09:26'),
(190, 1, 0xc39666666e65742065696e20466f726d756c61722c20696e2064656d20456967656e736368616674656e2064696573657220536569746520626561726265697465742077657264656e206bc3b66e6e656e, '2010-04-01 21:23:16'),
(188, 1, 0x4265617262656974656e, '2010-04-01 21:00:30'),
(188, 2, 0x45646974, '2010-04-01 21:00:30'),
(189, 1, 0x4cc3b6736368656e, '2010-04-01 21:00:44'),
(189, 2, 0x44656c657465, '2010-04-01 21:00:44'),
(190, 2, 0x53686f7773206120666f726d20696e207468617420796f752063616e20656469742070726f70657274696573206f6620746869732070616765, '2010-04-01 21:23:16'),
(191, 1, 0x4cc3b673636874206469657365205365697465, '2010-04-01 21:23:59'),
(191, 2, 0x44656c6574657320746869732070616765, '2010-04-01 21:23:59'),
(192, 1, 0x566572736368696562656e, '2010-04-01 21:52:21'),
(192, 2, 0x4d6f7665, '2010-04-01 21:52:21'),
(193, 1, 0xc39666666e65742065696e20466f726d756c61722c206d69742064656d206469657365205365697465207665727363686f62656e2077657264656e206b616e6e, '2010-04-01 21:53:13'),
(193, 2, 0x53686f7773206120666f726d207468617420616c6c6f777320796f7520746f206d6f766520746869732070616765, '2010-04-01 21:53:13'),
(194, 1, 0x53656974652068696e7a7566c3bc67656e, '2010-04-01 21:58:08'),
(194, 2, 0x4164642070616765, '2010-04-01 21:58:08'),
(195, 1, 0x45727374656c6c742065696e65206e6575652053656974652c206469652064696573657220536569746520756e74657267656f72646e657420697374, '2010-04-01 21:59:53'),
(195, 2, 0x437265617465732061206e65772073756270616765, '2010-04-01 22:01:01'),
(196, 1, 0x4dc3b663687465737420647520646965205365697465203c6120687265663d222e2f7b75726c7d223e7b7469746c652068746d6c7d3c2f613e203c623e73616d7420696872657220756e74657267656f72646e6574656e2053656974656e3c2f623e207769726b6c696368206cc3b6736368656e3f20446965736520416b74696f6e206b616e6e206e696368742072c3bc636b67c3a46e6769672067656d616368742077657264656e2e, '2010-04-01 22:18:08'),
(196, 2, 0x41726520796f75207375726520796f752077616e7420746f2064656c657465203c6120687265663d222e2f7b75726c7d223e7b7469746c652068746d6c7d3c2f613e203c623e616e6420616c6c206974732073756270616765733c2f623e3f205468697320616374696f6e2063616e6e6f7420626520756e646f6e652e, '2010-04-01 22:18:24'),
(197, 1, 0x42657374c3a4746967656e, '2010-04-01 22:23:31'),
(197, 2, 0x436f6e6669726d, '2010-04-01 22:23:31'),
(198, 1, 0x4dc3b663687465737420647520646965736520416b74696f6e207769726b6c69636820647572636866c3bc6872656e3f, '2010-04-01 22:24:17'),
(198, 2, 0x41726520796f75207375726520796f752077616e7420746f20646f207468697320616374696f6e3f, '2010-04-01 22:24:17'),
(199, 1, 0x4e616d65, '2010-04-01 22:28:29'),
(199, 2, 0x4e616d65, '2010-04-01 22:28:29'),
(200, 1, 0x416e7a656967656e616d65, '2010-04-01 22:28:49'),
(200, 2, 0x446973706c6179204e616d65, '2010-04-01 22:28:49'),
(201, 1, 0x446965736572204e616d65207769726420696e206465722055524c2076657277656e6465742077657264656e2e, '2010-04-01 22:29:42'),
(201, 2, 0x54686973206e616d652077696c6c206265207573656420696e207468652075726c2e, '2010-04-01 22:29:42'),
(202, 1, 0x446965736572204e616d65207769726420696e2064657220546974656c7a65696c65206465732042726f777365727320736f77696520696e20646572204e617669676174696f6e20616e67657a656967742077657264656e2e, '2010-04-01 22:30:38'),
(202, 2, 0x54686973206e616d652077696c6c20626520646973706c6179656420696e207469746c6520626172206f66207468652062726f7773657220616e6420696e206e617669676174696f6e206669656c64732e, '2010-04-01 22:30:38'),
(203, 1, 0x416273636869636b656e, '2010-04-01 22:31:28'),
(203, 2, 0x5375626d6974, '2010-04-01 22:31:28'),
(204, 1, 0x416273656e64656e, '2010-04-01 22:33:50'),
(204, 2, 0x5375626d6974, '2010-04-01 22:33:50'),
(205, 1, 0x53706569636865726e, '2010-04-01 22:34:04'),
(205, 2, 0x53617665, '2010-04-01 22:34:04'),
(206, 1, 0x4265617262656974656e, '2010-04-01 22:40:27'),
(206, 2, 0x45646974, '2010-04-01 22:40:27'),
(207, 1, 0x4cc3b6736368656e, '2010-04-01 22:41:00'),
(207, 2, 0x44656c657465, '2010-04-01 22:41:00'),
(208, 1, 0x53656974656e656967656e736368616674656e206265617262656974656e, '2010-04-01 22:42:46'),
(208, 2, 0x4564697420506167652050726f70657274696573, '2010-04-01 22:42:46'),
(209, 1, 0x53656974652068696e7a7566c3bc67656e, '2010-04-06 22:44:51'),
(209, 2, 0x4164642050616765, '2010-04-06 22:44:51'),
(210, 1, 0x536569746520766572736368696562656e, '2010-04-06 21:57:28'),
(210, 2, 0x4d6f76652050616765, '2010-04-06 21:57:28'),
(211, 1, 0x5365697465206cc3b6736368656e, '2010-04-06 21:57:49'),
(211, 2, 0x44656c6574652050616765, '2010-04-06 21:57:49'),
(213, 1, 0x4269747465206769622065696e656e204e616d656e2065696e2e, '2010-04-06 23:32:00'),
(213, 2, 0x506c6561736520656e7465722061206e616d652e, '2010-04-06 23:15:10'),
(214, 1, 0x4269747465206769622065696e656e20416e7a656967656e616d656e2065696e2e, '2010-04-06 23:31:49'),
(214, 2, 0x506c6561736520656e746572206120646973706c6179206e616d652e, '2010-04-06 23:15:28'),
(215, 1, 0x4175662064657220c3bc62657267656f72646e6574656e2053656974652065786973746965727420626572656974732065696e2045696e74726167206d69742064696573656d204e616d656e2e, '2010-04-22 21:01:00'),
(215, 2, 0x506172656e74207061676520616c726561647920686173206120737562706167652063616c6c656420736f2e, '2010-04-22 21:01:05'),
(216, 1, 0x466f727466616872656e, '2010-04-07 00:04:32'),
(216, 2, 0x436f6e6669726d, '2010-04-07 00:04:32'),
(217, 1, 0x41626272656368656e, '2010-04-07 00:04:32'),
(217, 2, 0x43616e63656c, '2010-04-07 00:04:32'),
(218, 1, 0x53656974656e737472756b747572, '2010-04-07 00:38:44'),
(218, 2, 0x5061676520537472756374757265, '2010-04-07 00:38:44'),
(183, 1, 0x4e616368666f6c67656e64207369656873742064752064696520537472756b74757220616c6c65722062656e75747a6572646566696e69657274656e2053656974656e2e204661687265206d697420646572204d61757320c3bc6265722065696e656e2045696e747261672c2064616d6974205765726b7a657567652065696e6765626c656e6465742077657264656e2c206d69742064656e656e2064696520537472756b74757220626561726265697465742077657264656e206b616e6e2e, '2010-04-07 22:59:00'),
(8, 3, 0x416363c3a873206e6f6e206175746f726973c3a9, '2010-04-10 20:05:03'),
(220, 1, 0x556e74656e20736965687374206475206469652053656974656e737472756b7475722e20466168726520c3bc6265722065696e656e2045696e747261672c20756d2064696520536368616c74666cc3a46368652022486965722065696e66c3bc67656e222065696e7a75626c656e64656e2e2057656e6e20647520617566206469657365206b6c69636b73742c20776972642064696520616b7475656c6c6520536569746520283c623e7b7469746c652068746d6c7d3c2f623e292064657220676577c3a4686c74656e20756e74657267656f72646e65742e3c2f703e0d0a0d0a3c703e3c7374726f6e673e48696e776569733a3c2f7374726f6e673e20457320697374206e69636874206dc3b6676c6963682c206469652052656968656e666f6c676520766f6e2053656974656e207a7520766572c3a46e6465726e2e20496d204e6f726d616c66616c6c2077657264656e20676c6569636872616e676967652045696e7472c3a46765202845696e7472c3a46765206d69742064657273656c62656e20c3bc62657267656f72646e6574656e2053656974652920616c7068616265746973636820736f7274696572742e3c2f703e0d0a0d0a3c703e3c7374726f6e673e41636874756e673a3c2f7374726f6e673e2057656e6e2065696e65205365697465207665727363686f62656e20776972642c2073696e6420e2809420616e6465727320616c73206265696d20556d62656e656e6e656e20e2809420616c6c6520616c74656e204c696e6b73202855524c7329206e69636874206d6568722067c3bc6c7469672e20447520736f6c6c74657374206461686572207665726d656964656e2c206f6674207665726c696e6b74652053656974656e20776965207a2e422e2064657220426c6f67206f646572206469652047616c65726965207a7520766572736368696562656e2e3c2f703e0d0a0d0a3c703e3c7374726f6e673e4c6567656e64653a3c2f7374726f6e673e20446965207a7520766572736368696562656e64652053656974652069737420626c617520756e7465726c6567743b206469652053656974652c20696e20646965207665727363686f62656e2077657264656e20736f6c6c20286f6465722064696520616b7475656c6c20c3bc62657267656f72646e6574652053656974652c2066616c6c73206e6f6368206b65696e205a69656c20617573676577c3a4686c74207775726465292c206f72616e67652e, '2011-01-15 00:21:22'),
(220, 2, 0x42656c6f7720796f7520736565207468652070616765207374727563747572652e20486f76657220616e20656e74727920746f20766965772022496e7365727420686572652220627574746f6e2e20436c69636b207468697320627574746f6e20746f206d616b65207468652063757272656e74207061676520287b7469746c652068746d6c7d292061207375622d70616765206f66207468652073656c6563746564206f6e652e3c2f703e0d0a0d0a3c703e4e6f74653a204368616e67696e67206f72646572206f66207061676573206973206e6f7420706f737369626c652e20557375616c6c792c2070616765732077697468207468652073616d65207375706572696f7220706167652061726520736f7274656420616c7068616265746963616c6c792e, '2010-04-25 13:02:03'),
(221, 1, 0x486965722065696e66c3bc67656e, '2010-04-22 15:18:28'),
(221, 2, 0x496e736572742068657265, '2010-04-22 15:18:28'),
(222, 1, 0x4f72646e65742064696520616b7475656c6c652053656974652064696573657220756e746572, '2010-04-22 15:19:52'),
(222, 2, 0x4d616b6573207468652063757272656e7420706167652061207375622d70616765206f662074686973206f6e65, '2010-04-22 15:19:52'),
(223, 1, 0x4469652053656974652c20696e206465207665727363686f62656e2077657264656e20736f6c6c74652c20657869737469657274206e696368742e, '2010-04-22 17:43:56'),
(223, 2, 0x546865207061676520696e20776869636820796f7520747269656420746f20696e7365727420646f6573206e6f742065786973742e, '2010-04-22 17:43:56'),
(224, 1, 0x446965205365697465206b616e6e206e6963687420696e2065696e652069687220756e74657267656f72646e657465205365697465206f64657220696e20736963682073656c627374207665727363686f62656e2077657264656e2e, '2010-04-22 18:03:31'),
(224, 2, 0x596f752063616e206e6f74206d6f76652061207061676520696e746f206f6e65206f6620697473207375622d7061676573206f7220696e746f2069742073656c662e, '2010-04-22 18:03:31'),
(225, 1, 0x496e20646572205a69656c73656974652065786973746965727420626572656974732065696e6520676c656963686e616d6967652053656974652e, '2010-04-22 18:06:59'),
(225, 2, 0x546172676574207061676520616c7265616479206861732061207375622d706167652063616c6c656420736f2e, '2010-04-22 18:06:59'),
(226, 1, 0x47696220686965722064656e204e616d656e206465732042656e75747a6572732065696e2e2045722064617266206e6f6368206e6963687420766f7268616e64656e207365696e2e, '2010-04-23 16:33:47'),
(226, 2, 0x456e7465722074686520757365722773206e616d6520686572652e204974206d757374206e6f74206265207573656420616c72656164792e, '2010-04-23 16:33:47'),
(227, 1, 0x42656e75747a6572206973742065696e20426f74, '2010-04-23 21:49:16'),
(227, 2, 0x55736572206973206120626f74, '2010-04-23 21:48:24'),
(228, 1, 0x426f742d4b6f6e66696775726174696f6e, '2010-04-23 21:49:14'),
(228, 2, 0x426f7420436f6e66696775726174696f6e, '2010-04-23 21:48:17'),
(229, 1, 0x57656e6e206469657365732046656c6420617573676577c3a4686c74206973742c206b616e6e2065696e20426f74206175746f6d6174697363682065726b616e6e7420756e642064696573656d2042656e75747a65726b6f6e746f207a75676577696573656e2077657264656e2e20426f747320686162656e206b65696e2050617373776f72742e, '2010-04-23 16:50:58'),
(229, 2, 0x436865636b207468697320626f7820746f206964656e7469667920626f747320616e642061737369676e207468656d206175746f6d61746963616c6c7920746f20746869732075736572206163636f756e742e20426f747320646f6e2774206861766520612070617373776f72642e, '2010-04-23 16:50:58'),
(230, 1, 0x426f742d45726b656e6e756e67, '2010-04-23 16:52:01'),
(230, 2, 0x426f74204964656e746966696572, '2010-04-23 16:52:01'),
(231, 1, 0x47696220686965722065696e656e20726567756cc3a472656e20417573647275636b2065696e2c20646572206175662064656e20557365722d4167656e742064657320426f7473207a757472696666742e204d6f6469666965722077657264656e206e6963687420756e7465727374c3bc747a7420756e642064616865722077657264656e2061756368206b65696e652044656c696d696e697465722062656ec3b6746967742e205a7769736368656e2047726fc39f2d20756e64204b6c65696e73636872656962756e672077697264206e6963687420756e746572736368696564656e2e, '2010-04-23 16:57:44'),
(231, 2, 0x456e746572206120726567756c61722065787072657373696f6e2074686174206d6174636865732074686520757365722d6167656e74206f662074686520626f742e204d6f6469666965727320617265206e6f7420737570706f727465742c207468657265666f72652064656c696d697465727320617265206e6f74206e6563636573736172792e205468652065787072657373696f6e20697320636173652d696e73656e7369746976652e, '2010-04-23 16:57:44'),
(232, 1, 0x50617373776f7274, '2010-04-23 17:07:42'),
(232, 2, 0x50617373776f7264, '2010-04-23 17:07:42'),
(233, 1, 0x4769622068696572206461732067656865696d652050617373776f72742065696e2c206461737320626569206a6564657220416e6d656c64756e672062656ec3b67469677420776972642e, '2010-04-23 17:08:23'),
(233, 2, 0x456e7465722061207365637265742070617373776f72642e2049742077696c6c206265206e656564656420666f722065616368206c6f67696e2e, '2010-04-23 17:08:23'),
(234, 1, 0x50617373776f72742062657374c3a4746967656e, '2010-04-23 17:09:43'),
(234, 2, 0x436f6e6669726d2070617373776f7264, '2010-04-23 17:09:43'),
(235, 1, 0x4769622068696572206e6f63686d616c732064617320676c65696368652050617373776f727420776965206f62656e2065696e2e, '2010-04-23 17:10:17'),
(235, 2, 0x52652d656e746572207468652073616d652070617373776f72642061732061626f76652e, '2010-04-23 17:10:17'),
(236, 1, 0x4269747465206769622065696e656e2042656e75747a65726e616d656e2065696e2e, '2010-04-23 21:14:20'),
(236, 2, 0x506c6561736520656e74657220612075736572206e616d652e, '2010-04-23 21:14:20'),
(237, 1, 0x452d4d61696c2d41647265737365, '2010-04-23 21:20:00'),
(237, 2, 0x452d6d61696c2061646472657373, '2010-04-23 21:20:00'),
(238, 1, 0x416e20646965736520452d4d61696c2d416472657373652077657264656e207a2e422e2050617373776f72742d56657267657373656e2d452d4d61696c73206f6465722042656e61636872696368746967756e67656e2076657273636869636b742e205369652077697264206e697267656e647320c3b66666656e746c69636820616e67657a656967742e, '2010-04-23 21:22:37'),
(238, 2, 0x466f72206578616d706c652c2070617373776f72642d6c6f73742d652d6d61696c73206f72206e6f74696669636174696f6e73206172652073656e6420746f207468697320616464726573732e2049742077696c6c206e657665722062652073686f776e20696e207075626c69632e, '2010-04-23 21:22:37'),
(239, 1, 0x447520686173742065696e6520756e67c3bc6c7469676520452d4d61696c2d416472657373652065696e6765676562656e2e, '2010-04-23 21:36:00'),
(239, 2, 0x596f7520656e746572656420616e20696e76616c696420652d6d61696c20616464726573732e, '2010-04-23 21:36:00'),
(240, 1, 0x4269747465206769622065696e6520452d4d61696c2d416472657373652065696e2e, '2010-04-23 21:39:49'),
(240, 2, 0x506c6561736520656e74657220616e20652d6d61696c20616464726573732e, '2010-04-23 21:39:49'),
(241, 1, 0x45732065786973746965727420626572656974732065696e2042656e75747a6572206d69742064696573656d204e616d656e2e, '2010-04-23 21:42:40'),
(241, 2, 0x546865726520697320616c7265616479206120757365722063616c6c656420736f2e, '2010-04-23 21:42:40'),
(242, 1, 0x4269747465206769622065696e6520426f742d45726b656e6e756e672065696e2c206f646572206465616b74697669657265202242656e75747a6572206973742065696e20426f74222e, '2010-04-23 21:56:31'),
(242, 2, 0x506c6561736520656e746572206120626f74206964656e746966696572206f7220646573656c656374202255736572206973206120626f7422, '2010-04-23 21:56:31'),
(243, 1, 0x4469652065696e6765676562656e6520426f742d45726b656e6e756e6720697374206b65696e2067c3bc6c746967657220726567756cc3a472657220417573647275636b202848696e776569733a2044656c696d697465727320756e64204d6f646966696572732064c3bc7266656e206e6963687420616e6765676562656e2077657264656e292e, '2010-04-23 21:58:27'),
(243, 2, 0x596f7520696e70757474656420616e20696e76616c696420626f74206964656e74696669657220284e6f74653a2064656c696d697465727320616e64206d6f6469666965727320617265206e6f7420737570706f72746564292e, '2010-04-23 21:58:27'),
(244, 1, 0x4269747465206769622065696e2050617373776f72742065696e2e, '2010-04-23 22:19:57'),
(244, 2, 0x506c6561736520656e74657220612070617373776f72642e, '2010-04-23 22:19:57'),
(245, 1, 0x426974746520776965646572686f6c65206461732050617373776f727420696d2046656c64202250617373776f72742062657374c3a4746967656e222e, '2010-04-23 22:20:41'),
(245, 2, 0x506c656173652072652d656e74657220796f75722070617373776f726420696e2022436f6e6669726d2070617373776f726422206669656c642e, '2010-04-23 22:20:41'),
(246, 1, 0x4469652062656964656e2065696e6765676562656e205061737377c3b672746572207374696d6d656e206e6963687420c3bc62657265696e2e, '2010-04-23 22:21:34'),
(246, 2, 0x5468652070617373776f7264732061726520646966666572656e742e, '2010-04-23 22:21:34'),
(247, 1, 0x4465722065696e6765676562656e65204e616d6520656e7468c3a46c74206e696368742065726c6175627465205a65696368656e20287a2e422e2053636872c3a4677374726963686520282f29292e, '2010-06-06 16:54:28'),
(247, 2, 0x54686520696e707574746564206e616d6520636f6e7461696e7320666f7262696464656e20636861726163746572732028652e672e20736c617368657320282f29292e, '2010-06-06 16:54:28'),
(248, 1, 0x4dc3b66368746573742064752064656e2042656e75747a6572203c6120687265663d222e2f7b75726c7d223e7b6e616d652068746d6c7d3c2f613e207769726b6c696368206cc3b6736368656e3f20446965736520416b74696f6e206b616e6e206e696368742072c3bc636b67c3a46e6769672067656d616368742077657264656e2e, '2010-04-23 23:22:26'),
(248, 2, 0x41726520796f75207375726520796f752077616e7420746f2064656c657465203c6120687265663d222e2f7b75726c7d223e7b6e616d652068746d6c7d3c2f613e3f205468697320616374696f6e2063616e6e6f7420626520756e646f6e652e, '2010-04-23 23:22:26'),
(503, 1, 0x4475206861737420756e67c3bc6c7469676520446174656e2066c3bc722065696e2046656c64206162676573636869636b742c2066c3bc722064617320504d4c2d446174656e2065727761727465742077657264656e2e207b274665686c65723a206966286d657373616765297d207b6d6573736167657d, '2010-06-19 20:01:39'),
(250, 1, 0x47696220686965722065696e656e2065696e646575746967656e204e616d656e2066c3bc72206469652042656e75747a65726772757070652065696e2e, '2010-04-23 23:54:38'),
(250, 2, 0x456e746572206120756e69717565206e616d6520666f7220746869732067726f75702e, '2010-04-23 23:54:38'),
(252, 1, 0x4772757070656e6d6974676c696564657220657268616c74656e2064696573656e20546974656c2e20426569737069656c3a204772757070656e6e616d6520696d2053696e67756c61722e, '2010-04-24 00:00:12'),
(252, 2, 0x47726f7570206d656d62657273206765742074686973207469746c652e204578616d706c653a2067726f7570206e616d6520696e2073696e67756c6172, '2010-04-24 00:00:12'),
(253, 1, 0x4661726265, '2010-04-24 00:02:34'),
(253, 2, 0x436f6c6f72, '2010-04-24 00:02:34'),
(254, 1, 0x446965204661726265207769726420696d206865786164657a696d616c656e20466f726d61742052524747424220616e6765676562656e2e, '2010-04-24 00:03:04'),
(254, 2, 0x456e7465722074686520636f6c6f7220696e2068657861646563696d616c20666f726d6174205252474742422e, '2010-04-24 00:03:04'),
(255, 1, 0x426573636872656962652068696572206b75727a2c2077617320646965204772757070656e6d6974676c69656465722067656d65696e73616d20686162656e206f646572207761732064696573652047727570706520736f6e7374206175737a656963686e65742e, '2010-04-24 00:05:53'),
(255, 2, 0x4465736372696265207768617420746865206d656d62657273206861766520696e20636f6d6d6f6e206f722077686174277320656c7365207468617420636861726163746572697a657320746869732067726f75702e, '2010-04-24 00:05:53'),
(256, 1, 0x416c6c65207a756bc3bc6e66746967207265676973747269657274656e2042656e75747a657220736f6c6c656e206175746f6d61746973636820646965736572204772757070652062656974726574656e, '2010-04-24 00:08:55'),
(256, 2, 0x416c6c20696e207468652066757475726520726567697374657265642075736572732077696c6c206175746f6d61746963616c6c79206a6f696e20746869732067726f7570, '2010-04-24 00:08:55'),
(257, 1, 0x4175746f6d6174697363682062656974726574656e, '2010-04-24 00:09:18'),
(257, 2, 0x4175746f2d4a6f696e, '2010-04-24 00:09:18'),
(258, 1, 0x4dc3b6636874657374206475206469652042656e75747a6572677275707065203c6120687265663d222e2f7b75726c7d223e7b6e616d652068746d6c7d3c2f613e2c20646965207a75676568c3b6726967656e204772757070656e6d6974676c696564736368616674656e20756e64203c623e616c6c652064616d69742076657262756e64656e205265636874653c2f623e207769726b6c696368206cc3b6736368656e3f20446965736520416b74696f6e206b616e6e206e696368742072c3bc636b67c3a46e6769672067656d616368742077657264656e2e3c2f703e0d0a0d0a3c703e3c623e41636874756e673a3c2f623e2057656e6e20647520696d204265677269666620626973742c2065696e6520477275707065207a75206cc3b6736368656e2c206469652064697220646173205265636874207a75722042656e75747a657276657277616c74756e672065727465696c742c20776972737420647520646965736573205265636874206dc3b6676c69636865727765697365207665726c696572656e21, '2010-04-24 00:20:15'),
(258, 2, 0x41726520796f75207375726520796f752077616e7420746f2064656c657465203c6120687265663d222e2f7b75726c7d223e7b6e616d652068746d6c7d3c2f613e20757365722067726f75702c206974732067726f7570206d656d6265727368697020616e64203c623e616c6c206c696e6b6564207269676874733c2f623e3f205468697320616374696f6e2063616e6e6f7420626520756e646f6e652e3c2f703e0d0a0d0a3c703e3c623e5761726e696e673a3c2f623e20496620796f75206172652064656c6574696e6720612067726f7570207468617420676976657320796f7520746865207269676874206f662061646d696e697374726174652075736572732c20796f75206d69676874206c6f6f73652074686973207269676874213c2f703e, '2010-04-24 00:20:15'),
(259, 1, 0x4269747465206769622065696e656e204772757070656e6e616d656e2065696e2e, '2010-04-24 00:37:45'),
(259, 2, 0x506c6561736520656e74657220612067726f7570206e616d652e, '2010-04-24 00:37:45'),
(260, 1, 0x45732065786973746965727420626572656974732065696e652042656e75747a6572677275707065206d69742064696573656d204e616d656e2e, '2010-04-24 00:38:30'),
(260, 2, 0x546865726520697320616c7265616479206120757365722067726f757020776974682074686973206e616d652e, '2010-04-24 00:38:30'),
(261, 1, 0x4269747465206769622065696e656e204d6974676c6965646572746974656c2065696e2e, '2010-04-24 00:39:55'),
(261, 2, 0x506c6561736520656e7465722061206d656d626572207469746c652e, '2010-04-24 00:39:55'),
(262, 1, 0x4269747465206769622065696e65204772757070656e66617262652065696e2e, '2010-04-24 00:40:48'),
(262, 2, 0x506c6561736520656e746572206120636f6c6f722e, '2010-04-24 00:40:48'),
(263, 1, 0x447520686173742065696e6520756e67c3bc6c746967652046617262616e676162652067656d616368742e, '2010-04-24 00:41:59'),
(263, 2, 0x596f7520696e70757474656420616e20696e76616c696420636f6c6f722e, '2010-04-24 00:41:59'),
(264, 1, 0x4269747465206769622065696e6520426573636872656962756e672065696e2e, '2010-04-24 00:43:00'),
(264, 2, 0x506c6561736520656e7465722061206465736372697074696f6e2e, '2010-04-24 00:43:00'),
(265, 1, 0x457320697374206e69636874206dc3b6676c6963682c206461732042656e75747a65726b6f6e746f2064657320476173746573207a75206cc3b6736368656e2e, '2010-04-24 00:52:26'),
(265, 2, 0x49742773206e6f7420706f737369626c6520746f2072656d6f76652074686520677565737427732075736572206163636f756e742e, '2010-04-24 00:52:26'),
(266, 1, 0x4665686c6572, '2010-04-24 00:54:37'),
(266, 2, 0x4572726f72, '2010-04-24 00:54:37'),
(267, 1, 0x5a7572c3bc636b207a756d20476173746b6f6e746f, '2010-04-24 00:56:59'),
(267, 2, 0x4261636b20746f2067756573742773206163636f756e74, '2010-04-24 00:56:59'),
(138, 3, 0x496e766974c3a9, '2010-05-24 20:05:51'),
(268, 1, 0x4772757070652062656974726574656e, '2010-04-25 12:51:18'),
(268, 2, 0x4a6f696e2047726f7570, '2010-04-25 12:51:18'),
(269, 1, 0x46c3bc67742064696573656e2042656e75747a657220696e2065696e652042656e75747a65726772757070652065696e, '2010-04-25 12:53:39'),
(269, 2, 0x416464732074686973207573657220746f206120757365722067726f7570, '2010-04-25 12:53:39'),
(270, 1, 0x57c3a4686c65206175732064656e20756e74656e2073746568656e64656e2042656e75747a65726772757070656e206469656a656e6967656e206175732c2064656e656e206465722042656e75747a6572207b6e616d652068746d6c7d2062656974726574656e20736f6c6c2e, '2010-12-28 23:51:51'),
(271, 1, 0x4469657365722042656e75747a6572206973742062657265697473204d6974676c69656420696e20616c6c656e204772757070656e2c206469652064752076657277616c74656e206b616e6e73742e, '2010-04-25 13:10:19'),
(578, 1, 0x57c3a4686c652064696520526563687465206175732c20646965204d6974676c6965646572206469657365722047727570706520657268616c74656e20736f6c6c656e2e, '2010-12-31 14:11:53'),
(271, 2, 0x7b6e616d652068746d6c7d20697320616c7265616479206d656d626572206f6620616c6c2067726f75707320796f752063616e206d616e6167652e, '2010-04-25 13:10:19'),
(272, 1, 0x457320657869737469657274206b65696e652042656e75747a6572677275707065206d69742064696573656d204e616d656e2e, '2010-04-25 13:27:05'),
(272, 2, 0x5468657265206973206e6f20757365722067726f75702063616c6c6564206c696b6520746861742e, '2010-04-25 13:27:05'),
(273, 1, 0x4465722042656e75747a6572206973742062657265697473204d6974676c6965642064657220477275707065203c623e7b67726f75704e616d652068746d6c7d3c2f623e206465732050726f6a656b7473203c623e7b70726f6a6563745469746c652068746d6c7d3c2f623e2e, '2010-12-30 00:15:48'),
(577, 1, 0x42697474652077c3a4686c652065696e65206f646572206d656872657265204772757070656e206175732c2064656e656e206465722042656e75747a65722062656974726574656e20736f6c6c2e, '2010-12-29 23:40:14'),
(274, 1, 0x5665726c617373656e, '2010-04-25 13:38:24'),
(274, 2, 0x4c65617665, '2010-04-25 13:38:24'),
(275, 1, 0x456e746665726e742064696573656e2042656e75747a657220617573206469657365722042656e75747a6572677275707065, '2010-04-25 13:39:43'),
(275, 2, 0x52656d6f766573207468652075736572206f7574206f66207468697320757365722067726f7570, '2010-04-25 13:39:43'),
(276, 1, 0x5a7572c3bc636b207a756d2042656e75747a6572, '2010-04-25 13:57:53'),
(276, 2, 0x4261636b20746f2075736572, '2010-04-25 13:57:53'),
(277, 1, 0x4465722042656e75747a657220697374206e6963687420286d65687229204d6974676c696564206469657365722042656e75747a65726772757070652e, '2010-04-25 13:59:19'),
(277, 2, 0x5468652075736572206973206e6f742061206d656d626572206f6620746869732067726f75702e, '2010-04-25 13:59:19'),
(278, 1, 0x5a756d204772757070656e6c65697465722065726e656e6e656e, '2010-04-25 14:10:52'),
(278, 2, 0x4e6f6d696e617465206173204c6561646572, '2010-04-25 14:10:52'),
(279, 1, 0x45726e656e6e7420646965736573204d6974676c696564207a756d204772757070656e6c65697465722064696573657220477275707065, '2010-04-25 14:11:43'),
(279, 2, 0x4e6f6d696e617465732074686973206d656d626572206173206c6561646572206f6620746869732067726f7570, '2010-04-25 14:11:43'),
(280, 1, 0x4c656974756e6720656e747a696568656e, '2010-04-25 14:12:30'),
(280, 2, 0x44656d6f7465, '2010-04-25 14:12:30'),
(281, 1, 0x456e747a696568742064696573656d204d6974676c69656420646965204c656974756e6720c3bc6265722064696520477275707065, '2010-04-25 14:12:59'),
(281, 2, 0x44656d6f74657320746869732075736572, '2010-04-25 14:12:59'),
(282, 1, 0x42656e75747a6572, '2010-04-25 16:46:19'),
(282, 2, 0x55736572, '2010-04-25 16:46:19'),
(283, 1, 0x5072696f726974c3a474, '2010-04-25 17:02:08'),
(283, 2, 0x5072696f72697479, '2010-04-25 17:02:08'),
(284, 1, 0x44696520477275707065206d6974206465722068c3b663687374656e205072696f726974c3a4742062657374696d6d742064656e20546974656c2065696e65732042656e75747a6572732e, '2010-04-25 17:03:58'),
(284, 2, 0x5468652067726f75702077697468207468652068696768657374207072696f726974792073657473206120757365722773207469746c65, '2010-04-25 17:03:58');
INSERT INTO `premanager_stringstranslation` (`id`, `languageID`, `value`, `timestamp`) VALUES
(285, 1, 0x446965205072696f726974c3a474206d7573732065696e65206e69636874206e65676174697665205a61686c207365696e206f646572206c6565722067656c617373656e2077657264656e2e, '2010-04-25 17:07:01'),
(285, 2, 0x5072696f72697479206d7573742062652061206e6f6e6e65676174697665206e756d626572206f72206c65667420656d7074792e, '2010-04-25 17:07:01'),
(286, 1, 0x526563687465, '2010-04-26 19:12:26'),
(286, 2, 0x526967687473, '2010-04-26 19:12:26'),
(287, 1, 0x56657277616c74657420646965205265636874652c20646965204d6974676c6965646572206469657365722047727570706520657268616c74656e, '2010-04-26 19:13:15'),
(287, 2, 0x4d616e616765207269676874732074686174206d656d62657273206f6620746869732067726f757020676574, '2010-04-26 19:13:15'),
(288, 1, 0x526563687465, '2010-04-26 21:14:34'),
(288, 2, 0x526967687473, '2010-04-26 21:14:34'),
(289, 1, 0x5a656967742065696e65204c697374652064657220526563687465206469657365732042656e75747a65727320616e, '2010-04-26 21:15:00'),
(289, 2, 0x53686f77732061206c697374206f6620746869732075736572277320726967687473, '2010-04-26 21:15:00'),
(290, 1, 0x48696572207369656873742064752065696e65204c6973746520646572205265636874652c20c3bc62657220646965206469657365722042656e75747a65722076657266c3bc67742e20536965206572676562656e20736963682061757320616c6c656e205265636874656e2c206469652064656e204772757070656e207a7567656f72646e65742073696e642c20696e2064656e656e206469657365722042656e75747a6572204d6974676c696564206973742e3c2f703e0d0a0d0a3c703e426561636874652c2064617373206475206e7572206469652052656368746520736965687374206465722050726f6a656b7465207369656873742c20646572656e205265636874652064752076657277616c74656e206b616e6e73742e3c2f703e0d0a0d0a3c703e426561726265697465204772757070656e7265636874652c2066c3bc67652064656e2042656e75747a657220696e2077656974657265204772757070656e2065696e206f64657220656e746665726e652069686e20617573204772757070656e2c20756d207365696e6520526563687465207a7520626565696e666c757373656e2e, '2011-01-02 22:25:27'),
(590, 1, 0x4475206b616e6e7374206465696e20656967656e65732042656e75747a65726b6f6e746f206e69636874206cc3b6736368656e2e204d656c6465206469636820756e7465722065696e656d20616e646572656e2042656e75747a65726e616d656e20616e2c20756d2064696573656e2042656e75747a6572207a75206cc3b6736368656e2e, '2011-01-03 16:10:48'),
(291, 1, 0x4469657365722042656e75747a65722076657266c3bc6774206d6f6d656e74616e20c3bc626572206b65696e65205265636874652e3c2f703e0d0a0d0a3c703e426561636874652c2064617373206475206e7572206469652052656368746520736965687374206465722050726f6a656b7465207369656873742c20646572656e205265636874652064752076657277616c74656e206b616e6e73742e3c2f703e0d0a0d0a3c703e426561726265697465204772757070656e726563687465206f6465722066c3bc67652064656e2042656e75747a657220696e2077656974657265204772757070656e2065696e2c20756d2069686d20526563687465207a757a75737072656368656e2e, '2011-01-02 22:25:50'),
(292, 1, 0x526563687465207b2766c3bc722064696520676573616d7465204f7267616e69736174696f6e272069662870726f6a65637449443d3d3029297d7b2766c3bc72206461732050726f6a656b74203c623e272069662870726f6a6563744944213d30297d7b70726f6a6563745469746c652069662870726f6a6563744944213d30297d7b273c2f623e272069662870726f6a6563744944213d3027297d, '2010-12-31 18:29:31'),
(293, 1, 0x5665726c696568656e20766f6e, '2010-04-26 21:26:35'),
(293, 2, 0x4772616e746564206279, '2010-04-26 21:26:35'),
(294, 1, 0x447520686173742076657273756368742c2065696e6520756e67c3bc6c74696765205365697465206175667a75727566656e2c2065696e6520756e67c3bc6c7469676520416b74696f6e2064757263687a7566c3bc6872656e206f64657220756e67c3bc6c746967652045696e676162656e20676574c3a4746967742e, '2010-04-28 17:37:20'),
(294, 2, 0x596f7520747269656420746f207669657720616e20696e76616c696420706167652c20646f20616e20696e76616c696420636f6d6d616e64206f7220796f757220696e70757473206172652077726f6e672e, '2010-04-28 17:37:20'),
(296, 1, 0x5370657272656e, '2010-04-29 16:16:12'),
(296, 2, 0x4c6f636b, '2010-04-29 16:16:12'),
(297, 1, 0x456e747a696568742064656e204772757070656e6c65697465726e206469652052656368746520c3bc62657220646965736520477275707065, '2010-04-29 16:21:11'),
(298, 1, 0x456e747370657272656e, '2010-04-29 16:21:24'),
(298, 2, 0x556e6c6f636b, '2010-04-29 16:21:24'),
(299, 1, 0x476962742064656e204772757070656e6c65697465726e206469652052656368746520616e2064696573657220477275707065207a7572c3bc636b, '2010-04-29 16:21:58'),
(299, 2, 0x4769766573206261636b207468652072696768747320617420746869732067726f75707320746f207468652067726f7570206c656164657273, '2010-04-29 16:21:58'),
(300, 1, 0x4dc3b66368746573742064752064656e204772757070656e6c65697465726e207769726b6c696368206469652052656368746520c3bc6265722064696573652047727570706520656e747a696568656e3f204469652047727570706520776972642064616e616368206e757220766f6e2042656e75747a65726e206265617262656974657420756e6420656e747370657272742077657264656e206bc3b66e6e656e2c2064696520c3bc626572205265636874207a756d205370657272656e20766f6e204772757070656e2076657266c3bc67656e2e, '2010-04-29 16:31:49'),
(300, 2, 0x41726520796f75207375726520796f752077616e7420746f206c6f636b20746869732067726f757020736f20746861742067726f7570206c6561646572732077696c6c206e6f7420626520616c6c6f77656420746f2065646974206f7220756e6c6f636b20746869732067726f75703f204f6e6c792074686f73652075736572732077686f20686176652074686520226c6f636b2067726f757073222072696768742077696c6c2062652061626c6520746f20756e6c6f636b20746869732067726f75702e, '2010-04-29 16:31:49'),
(301, 1, 0x4dc3b66368746573742064752064656e204772757070656e6c65697465726e206469652052656368746520c3bc62657220646965736520477275707065207a7572c3bc636b676562656e3f, '2010-04-29 16:44:23'),
(301, 2, 0x446f20796f752077616e7420746f2067697665206261636b207468652072696768747320617420746869732067726f757020746f207468652067726f7570206c6561646572733f, '2010-04-29 16:44:23'),
(302, 1, 0x44752062697374206e6963687420626572656368746967742c20646965736520477275707065207a752076657277616c74656e2e, '2010-04-30 15:27:46'),
(302, 2, 0x596f7520617265206e6f7420616c6c6f77656420746f206d616e61676520746869732067726f75702e, '2010-04-30 15:27:46'),
(303, 1, 0x44752068617374206e69636874206461732052656368742c2065696e2042656e75747a65726b6f6e746f207a7520696e7374616c6c696572656e2e, '2010-05-08 18:45:52'),
(303, 2, 0x596f7520617265206e6f7420616c6c6f77656420746f2063726561746520616e206163636f756e742e, '2010-05-08 18:45:52'),
(304, 1, 0x48696572206b616e6e73742064752065696e206e657565732042656e75747a65726b6f6e746f2066c3bc72206469636820616e6c6567656e2e3c2f703e0d0a0d0a3c703e446166c3bc722062656ec3b674696773742064752065696e20452d4d61696c2d506f7374666163682c2064656e6e206475206d757373742065696e6520452d4d61696c2d416472657373652065696e676562656e2c206469652062657374c3a4746967742077657264656e206d7573732e3c2f703e0d0a0d0a3c703e4175c39f657264656d206b616e6e73742064752065696e656e2042656e75747a65726e616d656e2077c3a4686c656e2c2064657220616c6c657264696e6773206e6f6368206e696368742076657277656e646574207365696e20646172662c20756e642065696e2050617373776f72742065696e676562656e2c206461737320626569206a6564657220416e6d656c64756e672062656ec3b6746967742077657264656e20776972642e, '2010-05-08 18:53:40'),
(304, 2, 0x4865726520796f752063616e2072656769737465722061206e65772075736572206163636f756e742e3c2f703e0d0a0d0a3c703e596f75206e65656420616e20652d6d61696c2061646472657373206265636175736520796f75206861766520746f20656e74657220616e6420636f6e6669726d206f6e652e3c2f703e0d0a0d0a3c703e496e206164646974696f6e2c20796f75206861766520746f2063686f6f736520612075736572206e616d652074686174206d757374206e6f74206265206578697374696e67207965742c20616e6420612070617373776f72642077686963682077696c6c206265206e656564656420666f722065616368206c6f67696e2e, '2010-05-08 18:53:58'),
(305, 1, 0x48696572206b616e6e73742064752065696e206e657565732042656e75747a65726b6f6e746f2066c3bc72206469636820616e6c6567656e2e3c2f703e0d0a0d0a3c703e446166c3bc72206d757373742064752065696e656e2042656e75747a65726e616d656e2077c3a4686c656e2c20646572206e6f6368206e696368742076657277656e646574207365696e20646172662c20756e642065696e2050617373776f72742065696e676562656e2c206461737320626569206a6564657220416e6d656c64756e672062656ec3b6746967742077657264656e20776972642e, '2010-05-08 18:55:24'),
(305, 2, 0x4865726520796f752063616e2072656769737465722061206e65772075736572206163636f756e742e3c2f703e0d0a0d0a3c703e596f75206861766520746f2063686f6f736520612075736572206e616d652074686174206d757374206e6f74206265206578697374696e67207965742c20616e6420612070617373776f72642077686963682077696c6c206265206e656564656420666f722065616368206c6f67696e2e, '2010-05-08 18:55:24'),
(306, 1, 0x42656e75747a65726e616d65, '2010-05-08 19:03:44'),
(306, 2, 0x55736572204e616d65, '2010-05-08 19:04:32'),
(307, 1, 0x47696220686965722065696e656e2042656e75747a65726e616d656e2065696e2e2057656e6e2064752064696573657320466f726d756c617220616273636869636b742c20776972642067657072c3bc66742c206f62206572206e6f63682066726569206973742e, '2010-05-08 19:10:57'),
(307, 2, 0x456e74657220612075736572206e616d6520686572652e20416674657220686176696e672073656e742074686520666f726d2c20796f752077696c6c20626520696e666f726d65642077686561746572206974207374696c6c20697320667265652e, '2010-05-08 19:10:57'),
(308, 1, 0x50617373776f7274, '2010-05-08 19:16:32'),
(308, 2, 0x50617373776f7264, '2010-05-08 19:16:32'),
(309, 1, 0x4769622068696572206465696e2067656865696d65732050617373776f72742065696e2e20457320736f6c6c746520736f776f686c204275636873746162656e20616c732061756368205a61686c656e20656e7468616c74656e20756e64206d696e64657374656e73207365636873205374656c6c656e206c616e67207365696e2e20426561636874652c2064617373207a7769736368656e2047726fc39f2d20756e64204b6c65696e73636872656962756e6720756e746572736368696564656e20776972642e, '2010-05-08 19:18:12'),
(309, 2, 0x456e74657220796f7572207365637265742070617373776f727420686572652e2049742073686f756c6420636f6e7461696e20626f7468206e756d6265727320616e64206c65747465727320616e64206e6f742062652073686f72746572207468616e2073697820636861726163746572732e204e6f74652074686174206974277320636173652073656e7369746976652e, '2010-05-08 19:18:12'),
(310, 1, 0x50617373776f72742062657374c3a4746967656e, '2010-05-08 19:18:47'),
(310, 2, 0x436f6e6669726d2070617373776f7264, '2010-05-08 19:18:47'),
(311, 1, 0x47696220686965722064617373656c62652050617373776f7274206e6f63686d616c2065696e2e, '2010-05-08 19:19:07'),
(311, 2, 0x52652d656e746572207468652073616d652070617373776f726420686572652e, '2010-05-08 19:19:07'),
(312, 1, 0x452d4d61696c2d41647265737365, '2010-05-08 19:23:47'),
(312, 2, 0x452d6d61696c2061646472657373, '2010-05-08 19:23:47'),
(313, 1, 0x42697474652067696220686965722065696e6520452d4d61696c2d41646472657373652065696e2c2061756620646572656e20506f737465696e67616e67206475205a75677269666620686173742e, '2011-02-16 20:31:08'),
(313, 2, 0x506c6561736520656e74657220796f757220652d6d61696c20616464726573732e, '2010-05-08 19:24:47'),
(314, 1, 0x57656e6e20452d4d61696c7320766f6e2064696573657220536569746520656d7066616e67656e206bc3b66e6e656e206dc3b66368746573742c20776965207a2e422e2066c3bc72206469652046756e6b74696f6e202671756f743b50617373776f72742076657267657373656e2671756f743b2c206769622068696572206465696e6520452d4d61696c2d416472657373652065696e2e, '2011-01-28 22:05:19'),
(314, 2, 0x496620796f752077616e7420746f207265636569766520652d6d61696c73206f66207468697320736974652c20652e672e20666f72202671756f743b70617373776f7264206c6f73742671756f743b2066756e6374696f6e2c20656e74657220697420686572652e, '2010-05-08 19:26:45'),
(315, 1, 0x452d4d61696c2d41646472657373652062657374c3a4746967656e, '2010-05-08 19:27:14'),
(315, 2, 0x436f6e6669726d20452d6d61696c2061646472657373, '2010-05-08 19:27:14'),
(316, 1, 0x47696220686965722064696520452d4d61696c2d4164647265737365206e6f63686d616c2065696e2e, '2010-05-08 19:27:37'),
(316, 2, 0x52652d656e7465722074686520652d6d61696c20616464726573732e, '2010-05-08 19:27:37'),
(317, 1, 0x57656e6e206475206f62656e2065696e6520452d4d61696c2d416472657373652065696e6765676562656e20686173742c20776965646572686f6c6520646965736520686965722e, '2010-05-08 19:28:18'),
(317, 2, 0x496620796f7520696e70757474656420616e20652d6d61696c20616464726573732061626f76652c2072652d656e74657220697420686572652e, '2010-05-08 19:28:18'),
(318, 1, 0x52656769737472696572656e, '2010-05-08 19:39:17'),
(318, 2, 0x5265676973746572, '2010-05-08 19:39:17'),
(319, 1, 0x4269747465206769622065696e656e2042656e75747a65726e616d656e2065696e2e, '2010-05-12 21:31:16'),
(319, 2, 0x506c6561736520656e74657220612075736572206e616d652e, '2010-05-12 21:31:16'),
(320, 1, 0x426974746520776965646572686f6c652064696520452d4d61696c2d4164726573736520696d2046656c642022452d4d61696c2d416472657373652062657374c3a4746967656e222e, '2010-05-12 21:46:25'),
(320, 2, 0x506c656173652072652d656e74657220796f757220656d61696c206164647265737320696e2022436f6e6669726d20652d6d61696c206164647265737322206669656c642e, '2010-05-12 21:47:07'),
(321, 1, 0x4469652062656964656e2065696e6765676562656e20452d4d61696c2d416472657373656e207374696d6d656e206e6963687420c3bc62657265696e2e, '2010-05-12 21:46:55'),
(321, 2, 0x54686520652d6d61696c206164647265737365732061726520646966666572656e742e, '2010-05-12 21:46:55'),
(322, 1, 0x537461747573, '2010-05-17 20:36:17'),
(322, 2, 0x5374617465, '2010-05-17 20:36:17'),
(323, 1, 0x57c3a4686c652068696572206175732c206f62206469657365732042656e75747a65726b6f6e746f20616b74697669657274207365696e20736f6c6c2e204d69742065696e656d206465616b74697669657274656e204b6f6e746f206b616e6e206d616e2073696368206e6963687420616e6d656c64656e2e, '2010-05-17 20:38:23'),
(323, 2, 0x456e61626c65206f722064697361626c6520746869732075736572206163636f756e7420686572652e204f6e652063616e206e6f74206c6f6720696e207769746820612064697361626c6564206163636f756e742e, '2010-05-17 20:38:23'),
(324, 1, 0x416b74697669657274, '2010-05-17 20:39:00'),
(324, 2, 0x456e61626c6564, '2010-05-17 20:39:00'),
(325, 1, 0x4465616b74697669657274, '2010-05-17 20:39:10'),
(325, 2, 0x44697361626c6564, '2010-05-17 20:39:10'),
(326, 1, 0x4465616b746976696572743b20616b746976696572656e2c20736f62616c6420452d4d61696c2d416472657373652062657374c3a474696774, '2010-05-17 20:39:46'),
(326, 2, 0x44697361626c65643b20656e61626c65207768656e20652d6d61696c206164647265737320697320636f6e6669726d6564, '2010-05-17 20:39:46'),
(327, 1, 0x466f6c67656e646520452d4d61696c2d416472657373652077757264652066c3bc722064696573656e2042656e75747a65722065696e676574726167656e2c2061626572206e6f6368206e696368742062657374c3a4746967743a203c6120687265663d226d61696c746f3a7b656d61696c2075726c7d223e7b656d61696c2068746d6c7d3c2f613e2e, '2010-05-17 21:09:03'),
(327, 2, 0x466f6c6c6f77696e6720652d6d61696c20616464726573732069732073746f72656420666f72207468697320757365722c20627574206e6f742079657420636f6e6669726d65643a203c6120687265663d226d61696c746f3a7b656d61696c2075726c7d223e7b656d61696c2068746d6c7d3c2f613e2e, '2010-05-17 21:09:03'),
(328, 1, 0x452d4d61696c2d4164726573736520656e746665726e656e, '2010-05-17 21:12:28'),
(328, 2, 0x52656d6f766520652d6d61696c2061646472657373, '2010-05-17 21:12:28'),
(329, 1, 0x42657374c3a4746967656e, '2010-05-17 21:12:46'),
(329, 2, 0x436f6e6669726d, '2010-05-17 21:12:46'),
(330, 1, 0x4865727a6c6963682057696c6c6b6f6d6d656e20626569207b6f7267616e697a6174696f6e5469746c652068746d6c7d2c207b757365724e616d652068746d6c7d213c2f703e0d0a0d0a3c703e4265766f722064752064696368206d6974206465696e656e2052656769737472696572756e6773646174656e20616e6d656c64656e206b616e6e73742c206d75737374206465696e6520452d4d61696c2d416472657373652062657374c3a4746967742077657264656e2e204b6c69636b6520646166c3bc722065696e66616368206175662064656e20666f6c67656e64656e204c696e6b3a3c2f703e0d0a0d0a3c703e3c6120687265663d227b6c696e6b55524c2068746d6c7d223e7b6c696e6b55524c2068746d6c7d3c2f613e3c2f703e0d0a0d0a3c703e57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e3c2f703e0d0a0d0a3c703e5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e6721, '2010-05-21 17:21:40'),
(331, 1, 0x4865727a6c6963682057696c6c6b6f6d6d656e20626569207b6f7267616e697a6174696f6e5469746c657d2c207b757365724e616d657d210d0a0d0a4265766f722064752064696368206d6974206465696e656e2052656769737472696572756e6773646174656e20616e6d656c64656e206b616e6e73742c206d75737374206465696e6520452d4d61696c2d416472657373652062657374c3a4746967742077657264656e2e204b6c69636b6520646166c3bc722065696e66616368206175662064656e20666f6c67656e64656e204c696e6b3a0d0a0d0a7b6c696e6b55524c7d0d0a0d0a57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e0d0a0d0a5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e6721, '2010-05-21 15:36:27'),
(332, 1, 0x45696e2053797374656d6665686c65722069737420617566676574726574656e2e20446965206c65747a746520416b74696f6e207775726465206dc3b6676c69636865727765697365206e69636874206b6f7272656b7420617573676566c3bc6872742e3c2f703e0d0a0d0a3c703e4469652041646d696e697374726174696f6e20777572646520c3bc6265722064696573656e20566f7266616c6c20696e666f726d696572742e2057656e6e2064752046726167656e20686173742c206b6f6e74616b74696572652062697474652064696573652e, '2010-05-21 16:15:20'),
(332, 2, 0x412073797374656d206572726f72206f6363757265642e20546865206c61737420616374696f6e206d6179206e6f742062652066696e6973686564207375636365737366756c6c792e3c2f703e0d0a0d0a3c703e41646d696e6973747261746f72732068617665206265656e20696e666f726d65642061626f7574207468697320696e636964656e742e20466f72206675727468657220696e666f726d6174696f6e2c20706c6561736520636f6e74616374207468656d2e, '2010-05-21 16:15:20'),
(333, 1, 0x4573206973742065696e2050726f626c656d206265696d2056657273656e64656e2064657220452d4d61696c2d4164726573736520617566676574726574656e2c206469652064656e20416b746976696572756e6773636f64652066c3bc72206465696e2042656e75747a65726b6f6e746f20656e7468616c74656e20736f6c6c74652e2042697474652077656e6465206469636820616e206469652041646d696e697374726174696f6e2e, '2010-05-21 17:23:22'),
(334, 1, 0x5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e67213c2f703e0d0a0d0a3c703e4265766f722064752064696368206d6974206465696e656e2052656769737472696572756e6773646174656e20616e6d656c64656e206b616e6e73742c206d757373206465696e6520452d4d61696c2d416472657373652062657374c3a4746967742077657264656e2e20496e2064656e206ec3a463687374656e204d696e7574656e20736f6c6c746573742064752065696e6520452d4d61696c20657268616c74656e2c20696e206465722064696520776569746572206ec3b6746967656e2053636872697474652065726cc3a475746572742073696e642e204269747465207072c3bc666520617563682064656e204f72646e65722066c3bc72205370616d2d56657264616368742c2066616c6c7320647520696e6e657268616c6220646572206ec3a463687374656e204d696e7574656e206b65696e6520452d4d61696c20657268616c74656e20736f6c6c746573742e, '2010-05-21 17:28:24'),
(335, 1, 0x5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e67213c2f703e0d0a0d0a3c703e4475206b616e6e73742064696368206a65747a74206d6974206465696e656e2052656769737472696572756e6773646174656e20616e6d656c64656e2e2056657277656e64652064617a752065696e666163682064617320756e74656e2073746568656e646520466f726d756c61722e3c2f703e0d0a0d0a3c703e4465696e6520452d4d61696c2d41647265737365207775726465206e6f6368206e696368742062657374c3a47469677420756e64206b616e6e206461686572206e6f6368206e69636874207a2e422e2066c3bc72206469652050617373776f72742d76657267657373656e2d46756e6b74696f6e2076657277656e6465742077657264656e2e20496e2064656e206ec3a463687374656e204d696e7574656e20736f6c6c746573742064752065696e6520452d4d61696c20657268616c74656e2c20696e2064657220646965206ec3b6746967656e2053636872697474652065726cc3a475746572742073696e642c20756d2064696520452d4d61696c2d41647265737365207a752062657374c3a4746967656e2e204269747465207072c3bc666520617563682064656e204f72646e65722066c3bc72205370616d2d56657264616368742c2066616c6c7320647520696e6e657268616c6220646572206ec3a463687374656e204d696e7574656e206b65696e6520452d4d61696c20657268616c74656e20736f6c6c746573742e, '2010-05-22 22:00:23'),
(336, 1, 0x5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e67213c2f703e0d0a0d0a3c703e4475206b616e6e73742064696368206a65747a74206d6974206465696e656e2052656769737472696572756e6773646174656e20616e6d656c64656e2e2056657277656e64652064617a752065696e666163682064617320756e74656e2073746568656e646520466f726d756c61722e3c2f703e, '2010-05-21 21:25:04'),
(337, 1, 0x5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e67213c2f703e0d0a0d0a3c703e4475206b616e6e73742064696368206a65747a74206d6974206465696e656e2052656769737472696572756e6773646174656e20616e6d656c64656e2e2056657277656e64652064617a752065696e666163682064617320756e74656e2073746568656e646520466f726d756c61722e3c2f703e0d0a0d0a3c703e4573206973742065696e2050726f626c656d206265696d2056657273656e64656e2064657220452d4d61696c2d4164726573736520617566676574726574656e2c206469652064656e2042657374c3a4746967756e6773636f64652066c3bc72206465696e6520452d4d61696c2d4164726573736520656e7468616c74656e20736f6c6c74652e204d697420452d4d61696c2d56657273616e642076657262756e64656e652046756e6b74696f6e656e2c20776965207a2e422e202250617373776f72742076657267657373656e222c2073696e64206461686572206e696368742076657266c3bc676261722e2042697474652077656e6465206469636820616e206469652041646d696e697374726174696f6e2c20756d206469657365732050726f626c656d207a75206265686562656e2e, '2010-05-21 22:19:34'),
(338, 1, 0x48616c6c6f207b757365724e616d652068746d6c7d213c2f703e0d0a0d0a3c703e4475206861737420676572616465206465696e6520452d4d61696c2d41647265737365206765c3a46e646572742e2044616d69742064696520c3846e646572756e67207769726b73616d20776972642c206d75737320736965206e756e2062657374c3a4746967742077657264656e2e204b6c69636b6520646166c3bc722065696e66616368206175662064656e20666f6c67656e64656e204c696e6b3a3c2f703e0d0a0d0a3c703e3c6120687265663d227b6c696e6b55524c2068746d6c7d223e7b6c696e6b55524c2068746d6c7d3c2f613e3c2f703e0d0a0d0a3c703e57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e, '2010-05-22 16:16:38'),
(351, 1, 0x4865727a6c6963682057696c6c6b6f6d6d656e20626569207b6f7267616e697a6174696f6e5469746c652068746d6c7d2c207b757365724e616d652068746d6c7d213c2f703e0d0a0d0a3c703e44616d6974206465696e6520452d4d61696c2d416472657373652076657277656e6465742077657264656e206b616e6e2c206d757373207369652062657374c3a4746967742077657264656e2e204b6c69636b6520646166c3bc722065696e66616368206175662064656e20666f6c67656e64656e204c696e6b3a3c2f703e0d0a0d0a3c703e3c6120687265663d227b6c696e6b55524c2068746d6c7d223e7b6c696e6b55524c2068746d6c7d3c2f613e3c2f703e0d0a0d0a3c703e57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e3c2f703e0d0a0d0a3c703e5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e6721, '2010-05-22 16:21:39'),
(339, 1, 0x48616c6c6f207b757365724e616d657d210d0a0d0a4475206861737420676572616465206465696e6520452d4d61696c2d41647265737365206765c3a46e646572742e2044616d69742064696520c3846e646572756e67207769726b73616d20776972642c206d75737320736965206e756e2062657374c3a4746967742077657264656e2e204b6c69636b6520646166c3bc722065696e66616368206175662064656e20666f6c67656e64656e204c696e6b3a0d0a0d0a7b6c696e6b55524c7d0d0a0d0a57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e0d0a0d0a5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e6721, '2010-05-22 16:19:18'),
(341, 1, 0x4465696e6520452d4d61696c2d41647265737365207775726465206572666f6c6772656963682062657374c3a4746967742e3c2f703e0d0a0d0a3c703e4162206a65747a742077697273742064752046756e6b74696f6e656e20647572636866c3bc6872656e206bc3b66e6e656e2c2066c3bc72206469652065696e6520452d4d61696c2d416472657373652062656ec3b67469677420776972642c20776965207a2e422e206469652050617373776f72742d56657267657373656e2d46756e6b74696f6e2e, '2010-05-21 23:08:28'),
(342, 1, 0x4465696e6520452d4d61696c2d41647265737365207775726465206572666f6c6772656963682062657374c3a4746967742e3c2f703e0d0a0d0a3c703e4475206b616e6e73742064696368206a65747a74206d69742064656e2052656769737472696572756e6773646174656e20616e6d656c64656e2e204e75747a6520646166c3bc722065696e666163682064617320756e74656e2073746568656e646520466f726d756c61722e, '2010-05-21 23:08:19'),
(343, 1, 0x44696520416b746976696572756e67732d416472657373652c20646965206475206175666765727566656e20686173742c2069737420656e74776564657220756e67c3bc6c746967206f64657220616267656c617566656e2e, '2010-05-22 00:23:50'),
(344, 1, 0x45732067696274206b65696e65204772757070652c20696e20646572206475206e6f6368206e69636874204d6974676c69656420626973742c206465722064752062656974726574656e206b616e6e73742e, '2010-05-22 13:42:45'),
(345, 1, 0x57656e6e206475206465696e2050617373776f7274206f646572206465696e656e2042656e75747a65726e616d656e2076657267657373656e20686173742c2069737420646173206b65696e2050726f626c656d2e2047696220686965722065696e666163682064696520452d4d61696c2d416472657373652065696e2c2064696520647520626569206465722052656769737472696572756e6720616e6765676562656e20686173742c20756e64206b6c69636b6520617566203c693e416273656e64656e3c2f693e2e20447520657268c3a46c7473742064616e6e2065696e6520452d4d61696c206d69742065696e656d204c696e6b2c20c3bc6265722064656e206475206461732050617373776f727420c3a46e6465726e20756e64206469636820616e6d656c64656e206b616e6e73742e3c2f703e0d0a0d0a3c703e426974746520626561636874652c20646173732064696573652046756e6b74696f6e206e75722076657266c3bc67626172206973742c2077656e6e2064752065696e6520452d4d61696c2d4164726573736520626569206465722052656769737472696572756e6720616e6765676562656e206f64657220736965207370c3a47465722068696e7a75676566c3bc677420686173742e2057656974657268696e206d7573732064696520452d4d61696c2d4164726573736520626572656974732062657374c3a47469677420776f7264656e207365696e2e, '2011-02-05 18:07:29'),
(346, 1, 0x42656e75747a65726e616d65, '2010-05-22 15:33:57'),
(347, 1, 0x4769622068696572206465696e656e2042656e75747a65726e616d656e2065696e2e, '2010-05-22 15:33:57'),
(348, 1, 0x452d4d61696c2d41647265737365, '2010-05-22 15:37:51'),
(349, 1, 0x47696220686965722064696520452d4d61696c2d416472657373652065696e2c2064696520647520626569206465722052656769737472696572756e672076657277656e64657420686173742e, '2010-05-22 15:37:51'),
(350, 1, 0x4469652065696e6765676562656e6520452d4d61696c2d41647265737365207374696d6d74206e69636874206d6974206465722066c3bc722064696573656e2042656e75747a65722065696e676574726167656e656e20c3bc62657265696e2c206f6465722066c3bc722064656e2042656e75747a6572207775726465206b65696e6520452d4d61696c2d4164726573736520616e6765676562656e2e, '2010-05-22 15:48:43'),
(352, 1, 0x4865727a6c6963682057696c6c6b6f6d6d656e20626569207b6f7267616e697a6174696f6e5469746c657d2c207b757365724e616d657d210d0a0d0a44616d6974206465696e6520452d4d61696c2d416472657373652076657277656e6465742077657264656e206b616e6e2c206d757373207369652062657374c3a4746967742077657264656e2e204b6c69636b6520646166c3bc722065696e66616368206175662064656e20666f6c67656e64656e204c696e6b3a0d0a0d0a7b6c696e6b55524c7d0d0a0d0a57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e0d0a0d0a5669656c656e2044616e6b2066c3bc72206469652052656769737472696572756e6721, '2011-01-29 17:41:02'),
(614, 1, 0x426974746520676562652064696520452d4d61696c2d416472657373652065696e2e, '2011-02-05 17:40:16'),
(353, 1, 0x4865727a6c6963682057696c6c6b6f6d6d656e20626569207b6f7267616e697a6174696f6e5469746c652068746d6c7d21, '2010-05-22 21:56:22'),
(354, 1, 0x4865727a6c6963682057696c6c6b6f6d6d656e20626569207b6f7267616e697a6174696f6e5469746c652068746d6c7d21, '2010-05-22 21:56:38'),
(355, 1, 0x42657374c3a4746967756e672064657220452d4d61696c2d41647265737365, '2010-05-22 22:00:28'),
(356, 1, 0x48616c6c6f207b757365724e616d652068746d6c7d2c3c2f703e0d0a0d0a3c703e4469652046756e6b74696f6e202671756f743b50617373776f72742076657267657373656e2671756f743b207775726465206d6974206465696e656d2042656e75747a65726b6f6e746f206475726368676566c3bc6872742e3c2f703e0d0a0d0a3c703e57656e6e20647520646173206e696368742077617273742c206b616e6e737420647520646965736520452d4d61696c2065696e666163682069676e6f72696572656e2e204d656c64652064696368206265696d206ec3a463687374656e204d616c2065696e666163682067616e7a206e6f726d616c20616e2e3c2f703e0d0a0d0a3c703e4b6c69636b65206175662064656e20666f6c67656e64656e204c696e6b2c20756d2065696e206e657565732050617373776f72742065696e7a75676562656e3a3c2f703e0d0a0d0a3c703e3c6120687265663d227b6c696e6b55524c7d223e7b6c696e6b55524c2068746d6c7d3c2f613e3c2f703e0d0a0d0a3c703e57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e, '2011-02-17 21:28:38'),
(357, 1, 0x48616c6c6f207b757365724e616d657d2c0d0a0d0a4469652046756e6b74696f6e202250617373776f72742076657267657373656e22207775726465206d6974206465696e656d2042656e75747a65726b6f6e746f206475726368676566c3bc6872742e0d0a0d0a57656e6e20647520646173206e696368742077617273742c206b616e6e737420647520646965736520452d4d61696c2065696e666163682069676e6f72696572656e2e204d656c64652064696368206265696d206ec3a463687374656e204d616c2065696e666163682067616e7a206e6f726d616c20616e2e0d0a0d0a4b6c69636b65206175662064656e20666f6c67656e64656e204c696e6b2c20756d2065696e206e657565732050617373776f72742065696e7a75676562656e3a0d0a0d0a7b6c696e6b55524c2068746d6c7d0d0a0d0a57656e6e2064752050726f626c656d65206265696d20c3b666666e656e20646573204c696e6b7320686173742c206d61726b6965726520646965206f62656e73746568656e646520416472657373652c206b6f70696572652073696520696e20646965205a7769736368656e61626c61676520756e642066c3bc67652073696520696e20646572204164726573737a65696c65206465696e65732042726f77736572732065696e2e, '2011-02-05 18:12:18'),
(358, 1, 0x426564617565726c6963686572776569736520697374206265696d2056657273656e64656e2064657220452d4d61696c2065696e2050726f626c656d20617566676574726574656e2c20736f64617373206469652050617373776f72742d76657267657373656e2d46756e6b74696f6e206e69636874206475726368676566c3bc6872742077657264656e206b6f6e6e74652e205769722062697474656e2c2064696573207a7520656e74736368756c646967656e20756e64207369636820616e206469652041646d696e697374726174696f6e207a752077656e64656e2e, '2011-02-05 18:03:26'),
(359, 1, 0x496e2064656e206ec3a463687374656e204d696e7574656e20736f6c6c746573742064752065696e6520452d4d61696c20657268616c74656e2c20696e206465722064696520776569746572656e20536368726974746520626573636872696562656e2073696e642e204269747465207072c3bc666520617563682064656e204f72646e65722066c3bc72205370616d2d56657264616368742c2066616c6c7320647520696e6e657268616c6220646572206ec3a463687374656e204d696e7574656e206b65696e6520452d4d61696c20657268616c74656e20736f6c6c746573742e, '2011-02-05 18:01:30'),
(360, 1, 0x4475206d75737374206465696e2050617373776f727420c3a46e6465726e2c207765696c206573207365696e652047c3bc6c7469676b656974207665726c6f72656e2068617421203c6120687265663d227b75726c2068746d6c7d223e4b6c69636b6520686965722c20756d206461732050617373776f7274207a7520c3a46e6465726e2e3c2f613e, '2010-05-22 22:33:08'),
(361, 1, 0x44752068617374206e6f6368206b65696e2042656e75747a65726b6f6e746f3f, '2010-05-22 22:53:37'),
(362, 1, 0x4b6c69636b6520686965722c20756d2065696e6573207a752065727374656c6c656e2e, '2010-05-22 22:53:37'),
(363, 1, 0x4475206861737420626572656974732065696e2042656e75747a65726b6f6e746f3f, '2010-05-22 22:59:15'),
(364, 1, 0x44616e6e206b6c69636b6520686965722c20756d206469636820616e7a756d656c64656e2e, '2010-05-22 22:59:15'),
(365, 1, 0x4475206b6f6e6e74657374206e6963687420616e67656d656c6465742077657264656e2c207765696c206465696e6520452d4d61696c2d41647265737365206e6f6368206e696368742062657374c3a4746967742077757264652e, '2010-05-23 23:43:06'),
(366, 1, 0x4475206b6f6e6e74657374206e6963687420616e67656d656c6465742077657264656e2c207765696c206465696e2042656e75747a65726b6f6e746f206465616b746976696572742077757264652e, '2010-05-23 23:43:40'),
(367, 1, 0x42656e75747a65726e616d65, '2010-05-24 18:48:58'),
(368, 1, 0x57656e6e206475206465696e656e2042656e75747a65726e616d656e20c3a46e6465726e206dc3b66368746573742c206769622069686e20686965722065696e2e, '2010-05-24 18:48:58'),
(369, 1, 0x57656e6e2064752064696520452d4d61696c2d41647265737365206765c3a46e6465727420686173742c206769622073696520686965722065726e6575742065696e2e, '2010-05-24 18:51:22'),
(370, 1, 0x57656e6e206475206461732050617373776f727420c3a46e6465726e206dc3b66368746573742c20676962206869657220646173206e6575652050617373776f72742065696e2e, '2010-05-24 19:41:11'),
(371, 1, 0x57656e6e206475206461732050617373776f727420c3a46e6465726e206dc3b66368746573742c20676962206869657220646173206e6575652050617373776f72742065726e6575742065696e2e, '2010-05-24 19:41:11'),
(372, 1, 0x4e757220616e67656d656c646574652042656e75747a6572206bc3b66e6e656e2052656769737472696572756e6773646174656e206265617262656974656e2e, '2010-05-24 21:30:05'),
(373, 1, 0x426564617565726c6963686572776569736520697374206265696d2056657273656e64656e2064657220452d4d61696c2065696e2050726f626c656d20617566676574726574656e2c20736f6461737320646965206e65756520452d4d61696c2d41647265737365206e696368742062657374c3a4746967742077657264656e206b616e6e2e205769722062697474656e2c2064696573207a7520656e74736368756c646967656e20756e64207369636820616e206469652041646d696e697374726174696f6e207a752077656e64656e2e, '2011-02-08 18:04:54'),
(374, 1, 0x44696520c3846e646572756e67656e20616e206465696e656e2052656769737472696572756e6773646174656e2077757264656e2067657370656963686572742e, '2010-05-24 22:26:00'),
(375, 1, 0x4465696e2050617373776f7274207775726465206765c3a46e646572742e2057656e6e206475206469636820646173206ec3a46368737465204d616c20616e6d656c646573742c206d7573737420647520646173206562656e2065696e6765676562656e652050617373776f72742062656e75747a656e2e, '2011-02-08 16:53:13'),
(376, 1, 0x446965206e65756520452d4d61696c2d41647265737365207775726465206e6f6368206e696368742062657374c3a4746967742e20536f6c616e676520776972642064696520c3846e646572756e67206465696e657220452d4d61696c2d41647265737365207769726b756e67736c6f7320626c656962656e2e20496e2064656e206ec3a463687374656e204d696e7574656e20736f6c6c746573742064752065696e6520452d4d61696c20657268616c74656e2c20696e2064657220646965206ec3b6746967656e2053636872697474652065726cc3a475746572742073696e642c20756d2064696520452d4d61696c2d41647265737365207a752062657374c3a4746967656e2e204269747465207072c3bc666520617563682064656e204f72646e65722066c3bc72205370616d2d56657264616368742c2066616c6c7320647520696e6e657268616c6220646572206ec3a463687374656e204d696e7574656e206b65696e6520452d4d61696c20657268616c74656e20736f6c6c746573742e, '2010-05-24 22:28:51'),
(377, 1, 0x5a7572c3bc636b, '2010-05-24 22:30:18'),
(377, 2, 0x4261636b, '2010-05-24 22:30:18'),
(378, 1, 0x5665726b6ec3bc70667465204d6f64756c65, '2010-05-26 19:54:56'),
(379, 1, 0x5665726b6ec3bc706674206469657365205365697465206d6974204d6f64756c656e2c2064696520496e68616c74652066c3bc7220736965206265726569747374656c6c656e, '2010-05-26 19:34:29'),
(380, 1, 0x48696572206b616e6e737420647520646965205365697465202671756f743b7b7469746c652068746d6c7d2671756f743b206d6974204d6f64756c656e207665726b6ec3bc7066656e2c2064696520496e68616c7465206265746569747374656c6c656e2e204469657365204d6f64756c652077657264656e20766f6e20506c7567696e732072656769737472696572742c20736f62616c6420646965736520696e7374616c6c696572742077657264656e2e3c2f703e0d0a0d0a3c703e496d2065727374656e2046656c642073696e6420646965204d6f64756c652061756667656c69737465742c206469652062657265697473206d697420646965736572205365697465207665726b6ec3bc7066742073696e642e20496e64656d2064752073696520756e74657265696e616e6465722076657273636869656273742c206c6567737420647520666573742c20696e2077656c636865722052656968656e666f6c676520646965204d6f64756c65206e61636820496e68616c74656e206162676566726167742077657264656e2e2044617320737069656c742064616e6e2065696e6520526f6c6c652c2077656e6e206d656872657265204d6f64756c6520756e74657267656f72646e6574652053656974656e206d69742064656d73656c62656e204e616d656e207a75722056657266c3bc67756e67207374656c6c656e2e20496e2064696573656d2046616c6c2062656b6f6d6d7420646173206f626572737465204d6f64756c20566f7272616e672e20417563682062656920646572205761686c206465732053656974656e696e68616c74732077697264206469652052616e676f72646e756e672062656163687465742e3c2f703e0d0a0d0a3c703e57656e6e2064617320676c6569636865204d6f64756c20617566206d6568726572656e2053656974656e207665726b6ec3bc70667420776972642c20657273636865696e742064696520536368616c74666cc3a4636865202671756f743b5072696dc3a47265205665726b6ec3bc7066756e672065696e7269636874656e2671756f743b2e204b6c69636b65206461726175662c2077656e6e2064696520616b7475656c6c65205365697465206175666765727566656e2077657264656e20736f6c6c2c20736f62616c642065696e204c696e6b2061756620646965736573204d6f64756c207a656967742e3c2f703e0d0a0d0a3c703e496d20756e746572656e2046656c642073696e6420646965204d6f64756c652061756667656c69737465742c2064696520766f6e20646965736572205365697465206e6f6368206e696368742076657277656e64657420776f7264656e2073696e642e204b6c69636b652065696e6573206461766f6e20616e2c20756d2065732068696e7a757a7566c3bc67656e2e, '2010-05-26 21:57:06'),
(381, 1, 0x446965736520536569746520777572646520626973686572206d6974206b65696e656d204d6f64756c207665726b6ec3bc7066742e, '2010-05-26 20:02:59'),
(382, 1, 0x416c6c652076657266c3bc67626172656e204d6f64756c652073696e642062657265697473206d697420646965736572205365697465207665726b6ec3bc7066742e, '2010-05-26 20:02:59'),
(383, 1, 0x5665726b6ec3bc70667465204d6f64756c65, '2010-05-26 20:03:31'),
(384, 1, 0x56657266c3bc6762617265204d6f64756c65, '2010-05-26 20:03:31'),
(385, 1, 0x5365697465206d697420496e68616c74656e207665726b6ec3bc7066656e, '2010-05-26 20:18:57'),
(386, 1, 0x4e616368206f62656e, '2010-05-26 21:55:05'),
(387, 1, 0x56657273636869656274206469657365204d6f64756c7265666572656e7a206e616368206f62656e20756e64207665726c656968742069687220646164757263682065696e652068c3b668657265205072696f726974c3a4742062656920646572204175737761686c20766f6e20496e68616c74656e20756e6420756e74657267656f72646e6574656e2053656974656e, '2010-05-26 21:55:05'),
(388, 1, 0x4e61636820756e74656e, '2010-05-26 21:55:58'),
(389, 1, 0x56657273636869656274206469657365204d6f64756c7265666572656e7a206e61636820756e74656e20756e64207665726d696e6465727420646164757263682069687265205072696f726974c3a4742062656920646572204175737761686c20766f6e20496e68616c74656e20756e6420756e74657267656f72646e6574656e2053656974656e, '2010-05-26 21:55:58'),
(390, 1, 0x5072696dc3a47265205665726b6ec3bc7066756e672065696e7269636874656e, '2010-05-26 21:57:52'),
(391, 1, 0x4b6c69636b6520686965722c2077656e6e2064696520616b7475656c6c65205365697465206175666765727566656e2077657264656e20736f6c6c2c2077656e6e2061756620646965736573204d6f64756c2076657277696573656e2077697264, '2010-05-26 21:57:52'),
(392, 1, 0x44696520616b7475656c6c652053656974652077697264206175666765727566656e2c2077656e6e2061756620646965736573204d6f64756c2076657277696573656e20776972642e2057c3a4686c652065696e6520616e646572652053656974652061757320756e64206b6c69636b6520646f72742061756620646965736520536368616c74666cc3a46368652c20756d2065696e6520616e64657265205072696dc3a4727665726b6ec3bc7066756e67206175737a7577c3a4686c656e, '2010-05-26 21:59:13'),
(393, 1, 0x456e746665726e656e, '2010-05-26 22:00:37'),
(394, 1, 0x456e746665726e742064696573656e204d6f64756c7665727765697320766f6e206469657365722053656974652c20736f64617373206b65696e6520496e68616c746520646573204d6f64756c73206d656872206175662064696573657220536569746520616e67657a656967742077657264656e, '2010-05-26 22:00:37'),
(395, 1, 0x4b6c69636b6520686965722c20756d20646965736573204d6f64756c206d69742064657220616b7475656c6c656e205365697465207a75207665726b6ec3bc7066656e2e, '2010-05-26 22:13:03'),
(396, 1, 0x44696573652046756e6b74696f6e20697374206e696368742076657266c3bc676261722c20646120646173204d6f64756c206175737363686c6965c39f6c696368206d697420646965736572205365697465207665726b6ec3bc70667420697374, '2010-05-26 23:21:06'),
(397, 1, 0x426572656368746967756e67656e, '2010-05-28 21:10:09'),
(398, 1, 0x4c65677420666573742c2077657220646965736520536569746520616e736568656e2064617266, '2010-05-28 21:10:09'),
(399, 1, 0x426572656368746967756e67656e20646572205365697465, '2010-05-28 21:17:26'),
(400, 1, 0x48696572206b616e6e737420647520666573746c6567656e2c2077657220646965736520536569746520616e736568656e20646172662e3c2f703e0d0a0d0a3c703e4d6f6d656e74616e206b616e6e206a6564657220426573756368657220646965736520536569746520617566727566656e2e204b6c69636b65206175662064696520666f6c67656e646520536368616c74666cc3a46368652c2077656e6e2064752073656c62737420416e7a6569676572656368746520766572676562656e2077696c6c73742e3c2f703e0d0a0d0a3c703e3c623e48696e776569733a3c2f623e2057656e6e2065696e652064657220c3bc62657267656f72646e6574656e2053656974656e20766f6e2065696e656d204265737563686572206e69636874206175666765727566656e2077657264656e206b616e6e2c2073696e6420616c6c6520756e74657267656f72646e6574656e2053656974656e206562656e66616c6c73206e69636874206175667275666261722e, '2010-05-29 00:41:20'),
(401, 1, 0x48696572206b616e6e737420647520666573746c6567656e2c2077657220646965736520536569746520616e736568656e20646172662e3c2f703e0d0a0d0a3c703e556e74656e207369656873742064752065696e65204c69737465206d6974204772757070656e2c2064696520416e7a656967657265636874652066c3bc7220646965736520536569746520657268616c74656e20686162656e2e204d6974676c6965646572206469657365722042656e75747a65726772757070656e206bc3b66e6e656e20646965736520536569746520616e736568656e2e3c2f703e0d0a0d0a3c703e57656e6e2064696573652053656974652066c3bc7220616c6c65204265737563686572207369636874626172207365696e20736f6c6c2c206b6c69636b65206175662064696520666f6c67656e646520536368616c74666cc3a46368652e0d0a0d0a3c703e3c623e48696e776569733a3c2f623e2057656e6e2065696e652064657220c3bc62657267656f72646e6574656e2053656974656e20766f6e2065696e656d204265737563686572206e69636874206175666765727566656e2077657264656e206b616e6e2c2073696e6420616c6c6520756e74657267656f72646e6574656e2053656974656e206562656e66616c6c73206e69636874206175667275666261722e, '2010-05-29 00:41:20'),
(402, 1, 0x416e7a65696765726563687465206b6f6e6669677572696572656e, '2010-05-28 22:33:59'),
(403, 1, 0x416e7a656967657265636874652066c3bc72206a6564656e2066726569676562656e, '2010-05-28 22:33:59'),
(404, 1, 0x456e746665726e656e, '2010-05-29 00:24:21'),
(405, 1, 0x456e747a6965687420646965736572204772757070652064696520416e7a656967657265636874652066c3bc72206469657365205365697465, '2010-05-29 00:24:21'),
(406, 1, 0x48696572206b6c69636b656e2c20756d206469657365722047727570706520416e7a65696765726563687465207a75207665726c656968656e, '2010-05-29 00:24:56'),
(407, 1, 0x4772757070656e206d697420416e7a656967657265636874656e, '2010-05-29 00:29:07'),
(408, 1, 0x4772757070656e206f686e6520416e7a65696765726563687465, '2010-05-29 00:29:07'),
(409, 1, 0x42697368657220686174206b65696e652047727570706520416e7a65696765726563687465, '2010-05-29 00:30:37'),
(410, 1, 0x416c6c65204772757070656e20686162656e206265726569747320416e7a6569676572656368746520657268616c74656e2e, '2010-05-29 00:30:37'),
(411, 1, 0x50726f6a656b742065727374656c6c656e, '2010-06-05 23:39:43'),
(412, 1, 0x5a656967742065696e20466f726d756c61722c206d69742064656d2065696e206e657565732050726f6a656b742068696e7a75676566c3bc67742077657264656e206b616e6e, '2010-06-05 23:39:43'),
(413, 1, 0x45732077757264656e206e6f6368206b65696e652050726f6a656b74652065727374656c6c742e, '2010-06-05 23:40:04'),
(414, 1, 0x4265617262656974656e, '2010-06-05 23:43:40'),
(415, 1, 0x5a656967742065696e20466f726d756c61722c20696e2064656d206465722050726f6a656b74746974656c20756e6420616e64657265204d6574612d496e666f726d6174696f6e656e20626561726265697465742077657264656e206bc3b66e6e656e, '2010-06-05 23:43:58'),
(416, 1, 0x4cc3b6736368656e, '2010-06-05 23:44:25'),
(417, 1, 0x4cc3b673636874206469657365732050726f6a656b74, '2010-06-05 23:44:25'),
(418, 1, 0x50736575646f2d50726f6a656b7420646572204f7267616e69736174696f6e, '2010-06-10 15:50:47'),
(419, 1, 0x50726f6a656b74746974656c, '2010-06-06 00:12:45'),
(420, 1, 0x4175746f72202f204175746f72656e, '2010-06-06 00:12:45'),
(421, 1, 0x426573636872656962756e67, '2010-06-06 00:13:19'),
(422, 1, 0x537469636877c3b672746572, '2010-06-06 00:13:19'),
(423, 1, 0x50726f6a656b746e616d65, '2010-06-06 16:16:41'),
(424, 1, 0x45696e2065696e64657574696765722c206b75727a6572204e616d652c20646572206e7572204275636873746162656e2c205a61686c656e20756e642c206d69742045696e73636872c3a46e6b756e67656e2c2042696e64657374726963686520656e7468c3a46c742e204b616e6e206e6963687420c3bc6265727365747a742077657264656e, '2010-06-06 16:17:46'),
(425, 1, 0x44657220616e67657a656967746520546974656c206465732050726f6a656b7473, '2010-06-06 16:17:32'),
(426, 1, 0x556e746572746974656c, '2010-06-06 16:18:36'),
(427, 1, 0x45696e206f7074696f6e616c657220556e746572746974656c206465732050726f6a656b7473, '2010-06-06 16:18:36'),
(428, 1, 0x506572736f6e206f64657220506572736f6e656e2c206469652066c3bc722064656e20496e68616c742064657220536569746520766572616e74776f72746c69636820697374202f2073696e64, '2010-06-06 16:20:00'),
(429, 1, 0x436f70797269676874, '2010-06-06 16:22:23'),
(430, 1, 0x5265636874737472c3a46765722064657320496e68616c7473206465722050726f6a656b7473656974656e20756e6420446174756d73616e676162656e, '2010-06-06 16:22:23'),
(431, 1, 0x45696e206f646572207a7765692053c3a4747a652c2064696520646173205468656d61206465732050726f6a656b7473207a7573616d6d656e66617373656e, '2010-06-06 16:23:01'),
(432, 1, 0x4d6974204b6f6d6d61732067657472656e6e746520537469636877c3b6727465722c20646965206d69742064696573656d2050726f6a656b74207665726b6ec3bc7066742077657264656e, '2010-06-06 16:23:41'),
(433, 1, 0x4269747465206769622065696e656e2050726f6a656b746e616d656e2065696e2e, '2010-06-06 17:39:33'),
(434, 1, 0x45732065786973746965727420626572656974732065696e2050726f6a656b74206d69742064696573656d204e616d656e2e, '2010-06-06 17:39:33'),
(435, 1, 0x4269747465206769622065696e656e2050726f6a656b74746974656c2065696e2e, '2010-06-06 17:42:11'),
(436, 1, 0x4461732046656c64202671756f743b4175746f72202f204175746f72656e2671756f743b2064617266206e69636874206c65657267656c617373656e2077657264656e2e, '2010-06-06 17:42:11'),
(437, 1, 0x4269747465206769622065696e656e20426573636872656962756e672065696e2e, '2010-06-06 17:41:07'),
(438, 1, 0x4269747465206769622065696e6520436f707972696768742d416e676162652065696e2e, '2010-06-06 17:42:01'),
(439, 1, 0x4465722050726f6a656b746e616d652064617266206e7572204275636873746162656e202841e280935a292c205a61686c656e20756e642042696e64657374726963686520656e7468616c74656e20756e64206d757373206d69742065696e656d204275636873746162656e206f6465722065696e6572205a61686c20626567696e6e656e20756e6420656e64656e2e, '2010-06-06 17:55:32'),
(440, 1, 0x4dc3b6636874657374206475206461732050726f6a656b74203c6120687265663d227b75726c2068746d6c7d223e7b7469746c652068746d6c7d3c2f613e207769726b6c696368206cc3b6736368656e3f3c2f703e0d0a0d0a3c703e3c623e5761726e756e673a3c2f623e2057656e6e20647520666f727466c3a4687273742c2077657264656e206175636820616c6c6520496e68616c74652c206469652066c3bc72206469657365732050726f6a656b742067657370656963686572742077757264656e2c2067656cc3b67363687421, '2010-06-06 18:39:46'),
(441, 1, 0x5765636873656c74207a7520646965736572205365697465, '2010-06-09 19:06:35'),
(442, 1, 0x416e7a656967656e, '2010-06-09 19:06:35'),
(443, 1, 0x4469657365205365697465206b616e6e206e696368742067656cc3b6736368742077657264656e2c20646120736965206f6465722065696e6520696872657220556e74657273656974656e206d69742065696e656d204d6f64756c207665726b6ec3bc706674206973742e, '2010-07-01 15:41:35'),
(444, 1, 0x44696573652053656974652069737420626572656974732064696520c3bc62657267656f72646e6574652053656974652e, '2010-06-09 20:49:55'),
(445, 1, 0x45696e652053656974652c20646965206d69742065696e656d204d6f64756c207665726b6ec3bc706674206973742c206b616e6e206b65696e6520556e74657273656974656e20656e7468616c74656e2e, '2010-06-09 21:03:57'),
(446, 1, 0x496e68616c74, '2010-06-09 21:38:53'),
(447, 1, 0x446965736520536569746520697374206d69742064656d204d6f64756c203c623e7b706c7567696e2068746d6c7d2e7b636c6173732068746d6c7d3c2f623e207665726b6ec3bc7066742e204461732062656465757465742c20646173732064696520496e68616c74652064696573657220536569746520756e64206968726520756e74657267656f72646e6574656e2053656974656e20766f6e2064696573656d204d6f64756c2062657374696d6d742077657264656e2e3c2f703e0d0a0d0a3c703e556d206d65687220c3bc62657220646173204d6f64756c207a7520657266616872656e2c20737563686520696e2064657220446f6b756d656e746174696f6e2064657320506c7567696e73203c623e7b706c7567696e206e616d657d3c2f623e2e, '2010-06-09 21:38:53'),
(448, 1, 0x44696573206973742065696e65205365697465206f686e6520496e68616c742e205769726420736965206175666765727566656e2c2077657264656e206469652053656974656e2c206469652069687220756e74657267656f72646e65742073696e642c2061756667656c69737465742e, '2010-06-09 21:41:43'),
(449, 1, 0x50726f6a656b7420617566727566656e, '2010-06-09 22:12:30'),
(450, 1, 0xc39666666e6574206469652050726f6a656b747365697465, '2010-06-09 22:12:30'),
(451, 1, 0x4461732050726f6a656b7420646572204f7267616e69736174696f6e206b616e6e206e696368742067656cc3b6736368742077657264656e2e, '2010-06-10 15:50:13'),
(452, 1, 0x56657266617373656e, '2010-06-11 19:20:41'),
(453, 1, 0x5a656967742065696e20466f726d756c61722c206d69742064656d2065696e206e6575657220426c6f672d417274696b656c2065727374656c6c742077657264656e206b616e6e, '2010-06-11 16:11:52'),
(454, 1, 0x566f727363686175, '2010-06-11 18:22:34'),
(455, 1, 0x546974656c, '2010-06-11 18:25:00'),
(456, 1, 0x466173736520686965722064656e20496e68616c742064657320417274696b656c73206b75727a207a7573616d6d656e2e, '2010-06-11 18:25:00'),
(457, 1, 0x54657874, '2010-06-11 18:25:43');
INSERT INTO `premanager_stringstranslation` (`id`, `languageID`, `value`, `timestamp`) VALUES
(458, 1, 0x47696220686965722064656e20496e68616c742064657320417274696b656c732065696e2e, '2010-06-11 18:25:43'),
(459, 1, 0x4269747465206769622065696e656e20546974656c2065696e2e, '2010-06-11 18:45:38'),
(460, 1, 0x44752068617374206b65696e656e20546578742065696e6765676562656e2e, '2010-06-11 18:45:38'),
(461, 1, 0x5a7573616d6d656e66617373756e67, '2010-06-11 18:58:48'),
(462, 1, 0x48696572206b616e6e737420647520646f6b756d656e74696572656e2c2077656c63686520c3846e646572756e67656e20647520766f7267656e6f6d6d656e20686173742e, '2010-06-11 18:58:48'),
(463, 1, 0x48696572206b616e6e73742064752064656e20496e68616c7420646572205365697465206b75727a207a7573616d6d656e66617373656e2e, '2010-06-11 19:01:40'),
(464, 1, 0x44657220417274696b656c2077757264652065727374656c6c74, '2010-06-11 19:12:32'),
(465, 1, 0x44657220426c6f6720697374206c6565722e, '2010-06-11 19:20:00'),
(466, 1, 0x56657273696f6e656e, '2010-06-11 19:33:57'),
(467, 1, 0x5a656967742065696e65204c69737465206d697420616c6c656e2056657273696f6e656e2c206469652066c3bc722064696573656e20417274696b656c2067657370656963686572742077757264656e, '2010-06-11 19:33:57'),
(468, 1, 0x4265617262656974656e, '2010-06-11 19:35:27'),
(469, 1, 0x45726dc3b6676c696368742065732c2065696e65206e6575652056657273696f6e2064696573657320417274696b656c73207a752073636872656962656e, '2010-06-11 19:35:27'),
(470, 1, 0x44696573657220417274696b656c207775726465206e6f6368206e6963687420696e206465696e65205370726163686520c3bc6265727365747a742e, '2010-06-11 20:04:46'),
(471, 1, 0x48696572207369656873742064752065696e65204c6973746520616c6c65722056657273696f6e656e2c2064696520696e206465696e657220537072616368652066c3bc722064696573656e20417274696b656c2067657370656963686572742077757264656e2e3c2f703e0d0a0d0a3c703e4a65646573204d616c2c2077656e6e2064657220417274696b656c206265617262656974657420776972642c20776972642065696e652056657273696f6e20616e67656c6567742e204469652056657273696f6e2c206469652047c3a4737465207a7520736568656e2062656b6f6d6d656e2c2077697264202671756f743b766572c3b66666656e746c69636874652056657273696f6e2671756f743b2067656e616e6e7420756e642069737420696e20646965736572204c6973746520686572766f726765686f62656e2e3c2f703e0d0a0d0a3c703e4b6c69636b65206175662064617320446174756d2c20756d2064656e20546578742065696e65722056657273696f6e20616e7a75736568656e2e20446f72742066696e64657374206475206175636820646965204dc3b6676c6963686b6569742c2064696573652056657273696f6e20616c732042617369732066c3bc722065696e65206e6575652056657273696f6e207a752076657277656e64656e2e, '2010-06-11 20:09:34'),
(472, 1, 0x4b6c69636b6520686965722c20756d2064656e2054657874206469657365722056657273696f6e207a7520736568656e, '2010-06-11 20:11:28'),
(473, 1, 0x23, '2010-06-11 20:18:55'),
(474, 1, 0x4765737065696368657274, '2010-06-11 20:18:55'),
(475, 1, 0x566572666173736572, '2010-06-11 20:19:09'),
(476, 1, 0x5a7573616d6d656e66617373756e67, '2010-06-11 20:19:09'),
(479, 1, 0x56657273696f6e7367657363686963687465, '2010-06-11 21:03:44'),
(480, 1, 0x44752062657472616368746573742065696e656e20417274696b656c2c20646572206e6f6368206e6963687420766572c3b66666656e746c696368742077757264652e20446965206e65757374652056657273696f6e207769726420616e67657a656967742e, '2010-06-11 21:03:44'),
(481, 1, 0x44696573206973742065696e6520616c74652056657273696f6e2064696573657320417274696b656c732e, '2010-06-11 21:05:34'),
(482, 1, 0x44752062657472616368746573742065696e656e20417274696b656c2c20646572206e6f6368206e6963687420696e206465696e65205370726163686520c3bc6265727365747a742077757264652e204265617262656974652069686e2c20756d2069686e207a7520c3bc6265727365747a656e2c206f6465722077656368736c652064696520537072616368652e, '2010-06-11 21:05:34'),
(483, 1, 0x44752062657472616368746573742065696e656e20417274696b656c2c20646572206e6f6368206e6963687420766572c3b66666656e746c696368742077757264652e, '2010-06-11 21:05:58'),
(484, 1, 0x44752062657472616368746573742065696e65206e6f6368206e6963687420766572c3b66666656e746c69636874652056657273696f6e2e, '2010-06-11 21:06:47'),
(485, 1, 0x4b6c69636b6520686965722c20756d207a757220766572c3b66666656e746c69636874656e2056657273696f6e207a752067656c616e67656e2e, '2010-06-11 21:07:12'),
(486, 1, 0x416e67657a65696774652056657273696f6e, '2010-06-11 21:09:46'),
(487, 1, 0x566572c3b66666656e746c69636874652056657273696f6e, '2010-06-11 21:09:46'),
(488, 1, 0x4e65757374652056657273696f6e, '2010-06-11 21:09:53'),
(489, 1, 0x566572c3b66666656e746c696368656e, '2010-06-11 22:48:35'),
(490, 1, 0x566572c3b66666656e746c696368742064696573652056657273696f6e2064657320417274696b656c732c20736f646173732047c3a473746520646965736520736568656e206bc3b66e6e656e, '2010-06-11 22:48:35'),
(491, 1, 0x4dc3b66368746573742064752056657273696f6e203c623e7b7265766973696f6e7d3c2f623e20766572c3b66666656e746c696368656e3f3c2f703e0d0a0d0a3c703e44696520616b7475656c6c20766572c3b66666656e746c69636874652056657273696f6e2077697264206461647572636820766f722047c3a47374656e20766572737465636b742e, '2010-06-11 23:11:25'),
(492, 1, 0x4dc3b66368746573742064696573656e20417274696b656c206d69742056657273696f6e203c623e7b7265766973696f6e7d3c2f623e20766572c3b66666656e746c696368656e3f3c2f703e, '2010-06-11 23:11:25'),
(493, 1, 0x44752062657472616368746573742064696520766572c3b66666656e746c69636874652056657273696f6e2e, '2010-06-11 23:18:14'),
(494, 1, 0x56657273696f6e20646573204261736973746578746573, '2010-06-11 23:37:00'),
(495, 1, 0x566572737465636b656e, '2010-06-11 23:59:18'),
(496, 1, 0x566572737465636b742064696573656e20417274696b656c20766f722047c3a47374656e20756e64206d616368742064616d6974206469652046756e6b74696f6e202671756f743b566572c3b66666656e746c696368656e2671756f743b2072c3bc636b67c3a46e676967, '2010-06-11 23:59:18'),
(497, 1, 0x4dc3b66368746573742064752064696573656e20417274696b656c20766f722047c3a47374656e20766572737465636b656e3f3c2f703e0d0a0d0a3c703e457220776972642064616e6e206e7572206e6f636820766f6e2042656e75747a65726e20616e6765736568656e2077657264656e206bc3b66e6e656e2c2064696520646173205265636874207a756d204265617262656974656e20766f6e20417274696b656c6e20686162656e2e, '2010-06-12 00:03:14'),
(498, 1, 0x44696573652056657273696f6e20697374206d6f6d656e74616e20766572c3b66666656e746c69636874, '2010-06-12 00:04:59'),
(499, 1, 0x4cc3b6736368656e, '2010-06-12 13:35:02'),
(500, 1, 0x4cc3b6736368742064696573656e20417274696b656c20756e6420616c6c65206672c3bc686572656e2056657273696f6e656e20756e77696465727275666c696368, '2010-06-12 13:35:02'),
(501, 1, 0x4dc3b66368746573742064752064696573656e20417274696b656c207769726b6c69636820656e6467c3bc6c746967206cc3b6736368656e3f2057656e6e20647520666f727466c3a4687273742c2077657264656e206175636820616c6c6520616c74656e2056657273696f6e656e2064696573657320417274696b656c732067656cc3b6736368742e3c2f703e0d0a0d0a3c703e57656e6e2064657220417274696b656c206e7572206175736765626c656e6465742077657264656e20736f6c6c2c206b616e6e73742064752061756368206469652046756e6b74696f6e202671756f743b566572737465636b656e2671756f743b2076657277656e64656e2e, '2010-06-12 13:53:50'),
(502, 1, 0x566f727363686175, '2010-06-18 19:49:32'),
(504, 1, 0x504d4c, '2010-06-20 20:37:59'),
(505, 1, 0x456967656e656e204176617461722061757377c3a4686c656e, '2010-06-26 21:39:46'),
(506, 1, 0x4475206b616e6e7374206e7572206465696e656e20656967656e656e204176617461722c206e696368742064656e20766f6e20616e646572656e2042656e75747a65726e2c20c3a46e6465726e2e, '2010-06-26 21:47:50'),
(507, 1, 0x45696e20417661746172206973742065696e206b6c65696e65732042696c642c20646173206175662064656d2050726f66696c20756e64206f6674206e6562656e2064656d2042656e75747a65726e616d656e20616e67657a6569677420776972642e3c2f703e0d0a0d0a3c703e48696572206b616e6e7374206475206465696e656e2041766174617220c3a46e6465726e2e204b6c69636b652064617a7520756e746572202671756f743b4e65756572204176617461722671756f743b20617566202671756f743b447572636873756368656e2e2e2e2671756f743b2c2077c3a4686c652065696e652042696c6464617465692061757320756e642062657374c3a4746967652064616e6e206d69742064657220536368616c74666cc3a4636865202671756f743b41766174617220686f63686c6164656e2671756f743b2e3c2f703e0d0a0d0a3c703e45732077657264656e2064697665727365204461746569747970656e20756e7465727374c3bc747a742c20646172756e746572204a50454720756e6420504e472e20536f6c6c74652064657220617573676577c3a4686c746520417661746172207a752067726fc39f207365696e2c2077697264206572206175746f6d617469736368207665726b6c65696e6572742e3c2f703e0d0a0d0a3c703e3c623e48696e776569733a3c2f623e204dc3b6676c69636865727765697365206d757373206465722042726f777365722d43616368652067656c656572742077657264656e2c2077656e6e2065696e206e657565722041766174617220686f636867656c6164656e2077757264652e, '2010-06-27 21:52:44'),
(508, 1, 0x45696e20417661746172206973742065696e206b6c65696e65732042696c642c20646173206175662064656d2050726f66696c20756e64206f6674206e6562656e2064656d2042656e75747a65726e616d656e20616e67657a6569677420776972642e3c2f703e0d0a0d0a3c703e48696572206b616e6e73742064752064656e20417661746172206465732042656e75747a657273202671756f743b7b757365724e616d652068746d6c7d2671756f743b20c3a46e6465726e2e204b6c69636b652064617a7520756e746572202671756f743b4e65756572204176617461722671756f743b20617566202671756f743b447572636873756368656e2e2e2e2671756f743b2c2077c3a4686c652065696e652042696c6464617465692061757320756e642062657374c3a4746967652064616e6e206d69742064657220536368616c74666cc3a4636865202671756f743b41766174617220686f63686c6164656e2671756f743b2e3c2f703e0d0a0d0a3c703e45732077657264656e2064697665727365204461746569747970656e20756e7465727374c3bc747a742c20646172756e746572204a50454720756e6420504e472e20536f6c6c74652064657220617573676577c3a4686c746520417661746172207a752067726fc39f207365696e2c2077697264206572206175746f6d617469736368207665726b6c65696e6572742e3c2f703e0d0a0d0a3c703e3c623e48696e776569733a3c2f623e204dc3b6676c69636865727765697365206d757373206465722042726f777365722d43616368652067656c656572742077657264656e2c2077656e6e2065696e206e657565722041766174617220686f636867656c6164656e2077757264652e, '2010-06-27 21:52:44'),
(509, 1, 0x45696e20417661746172206973742065696e206b6c65696e65732042696c642c2064617320617566206465696e656d2050726f66696c20756e64206f6674206e6562656e206465696e656d2042656e75747a65726e616d656e20616e67657a6569677420776972642e3c2f703e0d0a0d0a3c703e48696572206b616e6e73742064752065696e656e2041766174617220686f63686c6164656e2e204b6c69636b652064617a7520756e746572202671756f743b4176617461722671756f743b20617566202671756f743b447572636873756368656e2e2e2e2671756f743b2c2077c3a4686c652065696e652042696c6464617465692061757320756e642062657374c3a4746967652064616e6e206d69742064657220536368616c74666cc3a4636865202671756f743b41766174617220686f63686c6164656e2671756f743b2e3c2f703e0d0a0d0a3c703e45732077657264656e2064697665727365204461746569747970656e20756e7465727374c3bc747a742c20646172756e746572204a50454720756e6420504e472e20536f6c6c74652064657220617573676577c3a4686c746520417661746172207a752067726fc39f207365696e2c2077697264206572206175746f6d617469736368207665726b6c65696e6572742e3c2f703e0d0a0d0a3c703e3c623e48696e776569733a3c2f623e204dc3b6676c69636865727765697365206d757373206465722042726f777365722d43616368652067656c656572742077657264656e2c2077656e6e2065696e2041766174617220686f636867656c6164656e2077757264652e, '2010-06-27 21:52:44'),
(510, 1, 0x45696e20417661746172206973742065696e206b6c65696e65732042696c642c20646173206175662064656d2050726f66696c20756e64206f6674206e6562656e2064656d2042656e75747a65726e616d656e20616e67657a6569677420776972642e3c2f703e0d0a0d0a3c703e48696572206b616e6e73742064752065696e656e204176617461722066c3bc722064656e2042656e75747a6572202671756f743b7b757365724e616d652068746d6c7d2671756f743b20686f63686c6164656e2e204b6c69636b652064617a7520756e746572202671756f743b4176617461722671756f743b20617566202671756f743b447572636873756368656e2e2e2e2671756f743b2c2077c3a4686c652065696e652042696c6464617465692061757320756e642062657374c3a4746967652064616e6e206d69742064657220536368616c74666cc3a4636865202671756f743b41766174617220686f63686c6164656e2671756f743b2e3c2f703e0d0a0d0a3c703e45732077657264656e2064697665727365204461746569747970656e20756e7465727374c3bc747a742c20646172756e746572204a50454720756e6420504e472e20536f6c6c74652064657220617573676577c3a4686c746520417661746172207a752067726fc39f207365696e2c2077697264206572206175746f6d617469736368207665726b6c65696e6572742e3c2f703e0d0a0d0a3c703e3c623e48696e776569733a3c2f623e204dc3b6676c69636865727765697365206d757373206465722042726f777365722d43616368652067656c656572742077657264656e2c2077656e6e2065696e2041766174617220686f636867656c6164656e2077757264652e, '2010-06-27 21:52:44'),
(511, 1, 0x416b7475656c6c657220417661746172, '2010-06-27 14:20:14'),
(512, 1, 0x4469657365732042696c64206973742064657220616b7475656c6c6572204176617461722e, '2010-06-27 14:20:14'),
(513, 1, 0x4e6575657220417661746172, '2010-06-27 14:40:19'),
(514, 1, 0x417661746172, '2010-06-27 14:40:19'),
(515, 1, 0x4b6c69636b6520617566202671756f743b447572636873756368656e2e2e2e2671756f743b2c20756d2065696e652042696c646461746569206175737a7577c3a4686c656e2e, '2010-06-27 14:48:29'),
(516, 1, 0x4b6c69636b6520617566202671756f743b447572636873756368656e2e2e2e2671756f743b2c20756d2065696e652042696c646461746569206175737a7577c3a4686c656e2e, '2010-06-27 14:48:29'),
(517, 1, 0x4f7065726174696f6e656e, '2010-06-27 15:28:59'),
(518, 1, 0x41766174617220686f63686c6164656e, '2010-06-27 15:34:40'),
(519, 1, 0x417661746172206cc3b6736368656e, '2010-06-27 15:34:40'),
(520, 1, 0x44752068617374206b65696e6520446174656920617573676577c3a4686c742e, '2010-06-27 15:53:19'),
(521, 1, 0x44696520686f636867656c6164656e6520446174656920697374206b65696e652067c3bc6c746967652042696c6464617465692c206f6465722064617320466f726d61742077697264206e6963687420756e7465727374c3bc747a742e, '2010-06-27 15:55:48'),
(522, 1, 0x417661746172, '2010-06-27 21:45:28'),
(523, 1, 0x42696574657420646965204dc3b6676c6963686b6569742c2064656e20417661746172206469657365732042656e75747a657273207a7520c3a46e6465726e, '2010-06-27 21:45:28'),
(524, 1, 0x537461747464657373656e206469652053656974652073656c6273742067657374616c74656e, '2010-07-01 15:07:59'),
(525, 1, 0x537461747464657373656e2065696e652065696e6661636865204c6973746520616e7a656967656e, '2010-07-01 15:07:59'),
(526, 1, 0x44696573206973742065696e652053656974652c20646572656e204461727374656c6c756e6720667265692067657374616c7465742077657264656e206b616e6e2e3c2f703e0d0a0d0a3c703e556d20736965207a75206265617262656974656e2c207374656c6c65207369636865722c2064617373204a61766153637269707420696e206465696e656d2042726f7773657220616b746976696572742069737420756e642072756665206469652053656974652064616e6e20287a2e422e20c3bc6265722064696520536368616c74666cc3a4636865202671756f743b416e7a656967656e2671756f743b20696e206465722053796d626f6c6c656973746520616e2e, '2010-07-01 15:10:59'),
(527, 1, 0x4dc3b663687465737420647520616e73746174742065696e6572204175666c697374756e672064657220556e74657273656974656e2065696e652073656c6273742067657374616c74657465204461727374656c6c756e672066c3bc722064696573652053656974652061757377c3a4686c656e3f, '2010-07-01 15:48:31'),
(528, 1, 0x4dc3b66368746573742064752c20646173732064696573652053656974652065696e66616368206968726520556e74657273656974656e206175666c69737465743f3c703e0d0a0d0a3c703e44696520616b7475656c6c65204b6f6e66696775726174696f6e2077697264206461626569206e696368742067656cc3b6736368742c20736f6e6465726e206e757220766572737465636b742e2057656e6e206475206469652073656c6273742067657374616c74657465204461727374656c6c756e67207769656465722061757377c3a4686c73742c2077697264207369652077696564657268657267657374656c6c742e, '2010-07-01 15:48:31'),
(535, 1, 0x7b6e756d7d2053656b, '2010-09-10 18:17:07'),
(536, 1, 0x7b6e756d7d204d696e, '2010-09-10 18:17:07'),
(537, 1, 0x7b6e756d7d20537464, '2010-09-10 18:17:07'),
(538, 1, 0x7b6e756d7d205461677b2765206966286e756d213d31297d27, '2010-09-10 18:17:07'),
(539, 1, 0x7b6e756d7d204d6f6e61747b2765206966286e756d213d31297d, '2010-09-10 18:17:07'),
(540, 1, 0x7b6e756d7d204a6168727b2765206966286e756d213d31297d, '2010-09-10 18:17:07'),
(534, 1, 0x696e207b6e756d7d204a6168727b27656e206966286e756d213d31297d, '2010-09-10 18:17:07'),
(533, 1, 0x696e207b6e756d7d204d6f6e61747b27656e206966286e756d213d31297d, '2010-09-10 18:17:07'),
(532, 1, 0x696e207b6e756d7d205461677b27656e206966286e756d213d31297d, '2010-09-10 18:17:07'),
(531, 1, 0x696e207b6e756d7d205374756e64657b276e206966286e756d213d31297d, '2010-09-10 18:17:07'),
(530, 1, 0x696e207b6e756d7d204d696e7574657b276e206966286e756d213d31297d, '2010-09-10 18:17:07'),
(529, 1, 0x696e207b6e756d7d2053656b756e64657b276e206966286e756d213d31297d, '2010-09-10 18:17:07'),
(541, 1, 0x7b6e756d7d2053656b756e64657b276e206966286e756d213d31297d, '2010-09-10 21:21:35'),
(542, 1, 0x7b6e756d7d204d696e7574657b276e206966286e756d213d31297d, '2010-11-22 18:54:56'),
(543, 1, 0x7b6e756d7d205374756e64657b5c276e206966286e756d213d31297d, '2010-09-10 21:25:42'),
(544, 1, 0x7b6e756d7d205461677b2765206966286e756d213d31297d, '2010-11-22 18:54:56'),
(545, 1, 0x7b6e756d7d204d6f6e61747b2765206966286e756d213d31297d, '2010-11-22 18:54:56'),
(546, 1, 0x7b6e756d7d204a6168727b2765206966286e756d213d31297d, '2010-11-22 18:54:56'),
(547, 1, 0x7b6e756d7d2053656b, '2010-09-10 21:26:28'),
(548, 1, 0x7b6e756d7d204d696e, '2010-09-10 21:26:36'),
(549, 1, 0x7b6e756d7d20537464, '2010-09-10 21:26:44'),
(550, 1, 0x7b6e756d7d205461677b2765206966286e756d213d31297d, '2010-11-22 18:54:56'),
(551, 1, 0x7b6e756d7d204d6f6e61747b2765206966286e756d213d31297d, '2010-11-22 18:54:56'),
(552, 1, 0x7b6e756d7d204a6168727b2765206966286e756d213d31297d, '2010-11-22 18:54:56'),
(553, 1, 0x44752062697374206d6f6d656e74616e20616c73203c623e7b757365724e616d652068746d6c7d3c2f623e20616e67656d656c6465742e2057656e6e206475206469636820756e7465722065696e656d20616e646572656e204e616d656e20616e6d656c64656e2077696c6c73742c206d757373742064752064696368207a75657273742061626d656c64656e2e, '2011-01-02 22:27:43'),
(554, 1, 0x41626d656c64656e, '2010-09-26 18:19:16'),
(555, 1, 0x5a7572c3bc636b207a7572207a756c65747a742062657375636874656e205365697465, '2010-09-26 19:24:01'),
(556, 1, 0x44752062697374206a65747a7420616c73203c623e7b757365724e616d652068746d6c7d3c2f623e20616e67656d656c6465742e, '2011-01-02 22:28:08'),
(558, 1, 0x416e6d656c64756e67, '2010-10-06 21:30:40'),
(557, 1, 0x44752062697374206a65747a7420616267656d656c6465742e, '2010-09-26 19:20:37'),
(559, 1, 0x41626d656c64756e67, '2010-10-06 21:30:46'),
(560, 1, 0x486965722073696e64206469652042656e75747a65726772757070656e20616c6c65722050726f6a656b74652061756667656c69737465742e204b6c69636b65206175662065696e656e2050726f6a656b74746974656c2c20756d206e757220646965204772757070656e20616e7a757a656967656e2c20646965207a752064696573656d2050726f6a656b7420676568c3b672656e2e, '2010-11-22 18:48:01'),
(561, 1, 0x4772757070656e6e616d65, '2010-10-06 21:43:25'),
(562, 1, 0x416e7a61686c20646572204d6974676c6965646572, '2010-10-06 21:43:31'),
(563, 1, 0x416e7a61686c20646572204d6974676c6965646572, '2010-10-06 21:54:36'),
(564, 1, 0x284261636b656e642d536569746529, '2010-11-03 13:34:07'),
(565, 1, 0x50726f6a656b7465, '2010-11-13 21:15:20'),
(566, 1, 0x41756620646965736572205365697465206bc3b66e6e656e206469652050726f6a656b74652076657277616c7465742077657264656e2e, '2010-11-13 21:16:06'),
(567, 1, 0x42656e75747a65726772757070656e20766f6e207b70726f6a6563745469746c657d, '2010-11-22 18:45:18'),
(568, 1, 0x486965722073696e64206469652042656e75747a65726772757070656e206465732050726f6a656b7473202671756f743b7b70726f6a6563745469746c652068746d6c7d2671756f743b2061756667656c69737465742e, '2010-11-22 18:58:57'),
(569, 1, 0x486965722073696e64206469652042656e75747a65726772757070656e2061756667656c69737465742c20646965206b65696e656d2050726f6a656b742c20736f6e6465726e20646572204f7267616e69736174696f6e207a7567656f72646e65742077757264656e2e, '2010-11-22 18:49:19'),
(570, 1, 0x45732077757264656e206e6f6368206b65696e652042656e75747a65726772757070656e2066c3bc7220646965204f7267616e69736174696f6e2065727374656c6c742e, '2010-11-22 18:50:14'),
(571, 1, 0x46c3bc72206461732050726f6a656b74202671756f743b7b70726f6a6563745469746c652068746d6c7d2671756f743b2077757264656e206e6f6368206b65696e652042656e75747a65726772757070656e2065727374656c6c742e, '2010-11-22 18:58:57'),
(572, 1, 0x50726f6a656b74, '2010-11-22 19:20:47'),
(573, 1, 0x42697474652077c3a4686c652065696e2050726f6a656b742066c3bc722064696520477275707065206175732e, '2010-11-26 19:12:37'),
(574, 1, 0x556d2065696e652042656e75747a6572677275707065207a752065727374656c6c656e2c2077c3a4686c65207a756ec3a463687374206461732050726f6a656b74206175732c2066c3bc7220646173206469652047727570652065727374656c6c742077657264656e20736f6c6c2e, '2010-12-28 13:15:28'),
(575, 1, 0x42656e75747a65726b6f6e746f20616b746976696572656e, '2010-12-28 20:49:04'),
(576, 1, 0x4e757220616b74697669657274652042656e75747a65726b6f6e74656e206bc3b66e6e656e2066c3bc722064696520416e6d656c64756e672076657277656e6465742077657264656e2e, '2010-12-28 20:50:09'),
(579, 1, 0x42657374c3a4746967756e672064657220416e6d656c64756e67206572666f726465726c696368, '2011-01-02 15:34:37'),
(580, 1, 0x4672c3a467742064656e2042656e75747a65722065726e657574206e616368207365696e656d2050617373776f72742c2077656e6e20617566205265636874652064696573657220477275707065207a7567656772696666656e2077657264656e20736f6c6c2e204469656e7420616c73207a7573c3a4747a6c69636865722053636875747a20766f722042657472c3bc6765726e2e, '2011-01-02 15:36:33'),
(581, 1, 0x42657374c3a4746967756e672064657220416e6d656c64756e67, '2011-01-02 16:52:43'),
(582, 1, 0x556d2064696520416b74696f6e20647572636866c3bc6872656e207a75206bc3b66e6e656e2c206d75737374206475206465696e65204964656e746974c3a4742062657374c3a4746967656e20756e642064617a75206465696e2050617373776f72742065696e676562656e2e3c2f703e3c703e3c7374726f6e673e41636874756e673a3c2f7374726f6e673e2047656265206465696e2050617373776f7274206e75722065696e2c2077656e6e206475207769726b6c6963682065696e6520416b74696f6e20647572636866c3bc6872656e20776f6c6c746573742e, '2011-01-02 16:58:15'),
(583, 1, 0x416e6d656c64756e672062657374c3a4746967656e, '2011-01-02 18:44:49'),
(584, 1, 0x4461732065696e6765676562656e652050617373776f7274206973742066616c7363682e, '2011-01-02 19:09:58'),
(585, 1, 0x44696520416e6d656c64756e67207775726465206572666f6c6772656963682062657374c3a4746967742e, '2011-01-02 19:10:17'),
(586, 1, 0x44696520416e6d656c64756e67207775726465206572666f6c6772656963682062657374c3a4746967742e3c2f703e3c703e42656e75747a6520646965204e65752d4c6164656e2d46756e6b74696f6e206465696e65732042726f777365727320286d65697374656e737420c3bc626572206469652054617374652046352065727265696368626172292c20756d20666f72747a7566616872656e2e, '2011-01-02 19:10:56'),
(587, 1, 0x45726e65757420616e6d656c64656e3f, '2011-01-02 20:11:48'),
(588, 1, 0x44752076657266c3bc677374206e6963687420c3bc626572206461732052656368742c2042656e75747a65726772757070656e207a752065727374656c6c656e2e, '2011-01-02 21:26:31'),
(589, 1, 0x44752076657266c3bc677374206e6963687420c3bc626572206461732052656368742c2064696573656e2042656e75747a6572207a75722047727570706520203c623e7b67726f75704e616d652068746d6c7d3c2f623e206465732050726f6a656b7473203c623e7b70726f6a6563745469746c652068746d6c7d3c2f623e2068696e7a757a7566c3bc67656e2e, '2011-01-02 21:48:37'),
(591, 1, 0x4475206b616e6e7374206465696e20656967656e65732042656e75747a65726b6f6e746f206e69636874206cc3b6736368656e2e, '2011-01-03 16:19:51'),
(592, 1, 0x4a65747a74, '2011-01-06 16:40:15'),
(592, 2, 0x6e6f77, '2011-01-06 16:43:31'),
(594, 1, 0x446572204e616d652064617266207765646572206d69742065696e656d20506c75737a65696368656e20282b2920626567696e6e656e2c206e6f63682053636872c3a4677374726963686520282f2920656e7468616c74656e2e, '2011-01-09 15:02:38'),
(595, 1, 0x45696e652053656974652c20646965206d69742065696e656d204d6f64756c207665726b6ec3bc706674206973742c206b616e6e206b65696e6520556e74657273656974656e20656e7468616c74656e2e, '2011-01-09 18:20:38'),
(596, 1, 0xc39c6265722d20756e6420756e74657267656f72646e6574652053656974656e, '2011-01-09 18:45:20'),
(598, 1, 0xc39c62657267656f72646e65746520536569746520287b7469746c652068746d6c7d29, '2011-01-09 20:04:56'),
(599, 1, 0x48696572206b616e6e737420647520666573746c6567656e2c2077657220646965736520536569746520616e736568656e20646172662e204b616e6e2065696e2042656e75747a65722065696e6520c3bc62657267656f72646e657465205365697465206e6963687420617566727566656e2c206973742069686d20646572205a7567726966662061756620616c6c6520756e74657267656f72646e6574656e2053656974656e20696e206a6564656d2046616c6c2076657277656872742e3c2f703e20203c703e57c3a4686c6520646965204772757070656e206175732c20646572656e204d6974676c69656465722064696520536569746520617566727566656e206bc3b66e6e656e20736f6c6c656e2e2057656e6e2064696520477275707065202671756f743b4a656465722671756f743b20617573676577c3a4686c74206973742c2067696274206573206b65696e6520416e7a65696765626573636872c3a46e6b756e67656e2e3c2f703e20203c703e3c7374726f6e673e48696e776569733a3c2f7374726f6e673e204a656465722c206465722064696520537472756b747572206265617262656974656e206b616e6e2028616c736f206175636820647529206b616e6e20756e616268c3a46e67696720766f6e2064656e20426572656368746967756e67656e20616c6c652053656974656e20617566727566656e2e204461647572636820776972642076657268696e646572742c20646173732077696368746967652061646d696e69737472617469766520426572656963686520c3bc6265726861757074206e69636874206d656872207a7567726569666261722073696e642e3c2f703e0d0a0d0a3c703e3c7374726f6e673e42656469656e756e677368696e776569733a3c2f7374726f6e673e2048616c74652064696520537472672d54617374652067656472c3bc636b742c2077c3a46872656e64206475206175662045696e7472c3a46765206b6c69636b73742c20756d206d656872657265204772757070656e206175737a7577c3a4686c656e2c206f6465722064696520556d736368616c742d54617374652c20756d2065696e656e2042657265696368206175737a7577c3a4686c656e2e, '2011-01-21 21:53:45'),
(600, 1, 0x4a65646572, '2011-01-21 21:48:19'),
(601, 1, 0x5374696c65, '2011-01-24 18:55:33'),
(602, 1, 0x546974656c, '2011-01-24 18:55:38'),
(603, 1, 0x4175746f72, '2011-01-24 18:55:41'),
(604, 1, 0x426573636872656962756e67, '2011-01-24 18:55:46'),
(605, 1, 0x416b74697669657274, '2011-01-24 18:55:53'),
(606, 1, 0x5374616e64617264, '2011-01-24 18:56:00'),
(607, 1, 0x417566206469657365722053656974652073696e6420616c6c65205374696c652061756667656c69737465742c20646965207a75722056657266c3bc67756e672073746568656e2e20496e7374616c6c69657265207765697465726520506c7567696e732c20646965205374696c6520656e7468616c74656e2c20756d20646965204175737761686c207a75207665726772c3b6c39f65726e2e3c2f703e3c703e4475206b616e6e737420646965205374696c652065696e7a656c6e20616b746976696572656e206f646572206465616b746976696572656e2e205265676973747269657274652042656e75747a6572206bc3b66e6e656e20736963682065696e656e2064657220616b74697669657274656e205374696c652061757373756368656e2e2042656e75747a65722c20646965206b65696e656e205374696c20617573676577c3a4686c7420686162656e20756e642047c3a473746520736568656e2064696520536569746520696d205374616e646172642d5374696c2e, '2011-01-24 19:02:19'),
(608, 1, 0x53706569636865726e, '2011-01-24 19:21:13'),
(609, 1, 0x4d696e64657374656e732065696e205374696c206d75737320616b74697669657274207365696e2e, '2011-01-24 20:18:34'),
(610, 1, 0x446572205374616e646172642d5374696c206d75737320616b74697669657274207365696e2e, '2011-01-24 20:20:23'),
(611, 1, 0x56657277656e64656e, '2011-01-28 18:40:00'),
(612, 1, 0x48696572206b6c69636b656e2c20756d2064696573656e205374696c2066c3bc72206465696e2042656e75747a65726b6f6e746f207a752076657277656e64656e2e20416e646572652042656e75747a6572206f6465722047c3a473746520626574726966667420646965736520416b74696f6e206e696368742e, '2011-01-28 18:40:57'),
(615, 1, 0x46c3bc72206469652065696e676562656e6520452d4d61696c2d41647265737365207775726465206b65696e2042656e75747a65726b6f6e746f20676566756e64656e2e20426974746520626561636874652c206461737320452d4d61696c2d416472657373656e2c20646965206e6f6368206e696368742062657374c3a4746967742077757264656e2c206e696368742076657277656e6465742077657264656e206bc3b66e6e656e2e, '2011-02-05 17:48:46'),
(616, 1, 0x50617373776f72742076657267657373656e, '2011-02-05 18:11:34'),
(617, 1, 0x447520686173742064696368206e756e20616c73206465722042657369747a6572206465732042656e75747a65726b6f6e746f73203c623e7b757365724e616d652068746d6c7d3c2f623e20617573676577696573656e2e204e756e206b616e6e73742064752065696e206e657565732050617373776f72742065696e676562656e2c206d69742064656d206475206469636820696e205a756b756e667420616e6d656c64656e206b616e6e73742e, '2011-02-05 22:58:34'),
(618, 1, 0x4461732042656e75747a65726b6f6e746f2c207a752064656d206469652065696e6765676562656e6520452d4d61696c2d4164726573736520676568c3b672742c20697374206465616b746976696572742e204461686572206b616e6e206469652046756e6b74696f6e202671756f743b50617373776f72742076657267657373656e2671756f743b206175662064696573656e2042656e75747a6572206e6963687420616e676577656e6465742077657264656e2e, '2011-02-05 23:53:55'),
(619, 1, 0x4465696e2042656e75747a65726b6f6e746f207775726465207a7769736368656e7a6569746c6963682067657370657272742e204475206b616e6e7374206461686572207765646572206465696e2050617373776f727420c3a46e6465726e2c206e6f6368206469636820616e6d656c64656e2e2046c3bc72207765697465726520496e666f726d6174696f6e656e206b6f6e74616b7469657265206269747465206469652041646d696e697374726174696f6e2e, '2011-02-06 00:02:54'),
(620, 1, 0x446572204c696e6b2069737420656e74776564657220756e67c3bc6c746967206f64657220616267656c617566656e2e, '2011-02-06 00:03:31'),
(621, 1, 0x4461732050617373776f7274207775726465206572666f6c677265696368206765c3a46e646572742e20496e205a756b756e667420776972737420647520646963682064616d697420616e6d656c64656e206bc3b66e6e656e2e2046c3bc72206469657365205369747a756e672077757264657374206475206175746f6d61746973636820616e67656d656c6465742e, '2011-02-06 00:09:47'),
(622, 1, 0x446965736520452d4d61696c2d41647265737365207775726465207363686f6e2066c3bc722065696e20616e64657265732042656e75747a65726b6f6e746f2076657277656e6465742e, '2011-02-06 00:29:38'),
(623, 1, 0x426974746520676962206465696e20616b7475656c6c65732050617373776f72742065696e2e, '2011-02-08 16:50:55'),
(627, 1, 0x41756620646965736572205365697465206b616e6e7374206475206461732050617373776f72742066c3bc72206465696e2042656e75747a65726b6f6e746f20c3a46e6465726e2e20446166c3bc72206d75737374206475207a756ec3a463687374206465696e20616b7475656c6c65732050617373776f72742065696e676562656e2e2057656e6e20647520646173206e69636874206d65687220776569c39f742c2062656e75747a652062697474652064617320466f726d756c6172203c6120687265663d222e2f7b70617373776f72644c6f737455524c2068746d6c7d223e50617373776f72742076657267657373656e3c2f613e2e, '2011-02-08 16:48:46'),
(626, 1, 0x4475206b616e6e7374206465696e2050617373776f7274206e6963687420c3a46e6465726e2c206461206475206e6963687420616e67656d656c64657420626973742e, '2011-02-08 16:46:46'),
(628, 1, 0x416b7475656c6c65732050617373776f7274, '2011-02-08 16:49:02'),
(629, 1, 0x4769622068696572206461732050617373776f72742065696e2c206d69742064656d20647520646963682062697368657220616e67656d656c64657420686173742e, '2011-02-08 16:49:13'),
(630, 1, 0x44617320616b7475656c6c652050617373776f72742c206461732064752065696e6765676562656e20686173742c206973742066616c7363682e, '2011-02-08 16:51:45'),
(631, 1, 0x4475206861737420646965736520452d4d61696c2d41647265737365207363686f6e2065696e6d616c2065696e6765676562656e2c2061626572206e6f6368206e696368742062657374c3a4746967742e2057656e6e206475206469652042657374c3a4746967756e67732d452d4d61696c206e6963687420657268616c74656e20686173742c206b616e6e73742064752064696520c3846e646572756e67206465722041647265737365207b65787069726174696f6e54696d65206461746554696d655068726173657d2065726e65757420647572636866c3bc6872656e2e, '2011-02-16 18:22:20'),
(632, 1, 0x4475206b616e6e7374206465696e6520452d4d61696c2d41647265737365206e6963687420c3a46e6465726e2c206461206475206e6963687420616e67656d656c64657420626973742e, '2011-02-08 18:08:42'),
(633, 1, 0x447520646172667374206465696e6520452d4d61696c2d4164726573736520207a77617220c3a46e6465726e2c206a65646f6368206e6963687420656e746665726e656e2e, '2011-02-08 18:09:18'),
(634, 1, 0x44696520452d4d61696c2d416472657373652c206469652064752065696e6765676562656e20686173742c20697374206265726569747320616c73206465696e6520452d4d61696c2d416472657373652072656769737472696572742e, '2011-02-16 18:03:31'),
(635, 1, 0x6865757465, '2011-02-16 18:24:04'),
(636, 1, 0x6765737465726e, '2011-02-16 18:24:09'),
(637, 1, 0x6d6f7267656e, '2011-02-16 18:24:14'),
(638, 1, 0x44696520452d4d61696c2d41647265737365207775726465206572666f6c6772656963682062657374c3a47469677420756e642064616d6974206465696e2042656e75747a65726b6f6e746f20616b746976696572742e204475206b616e6e7374206469636820616220736f666f7274206d69742064656d2042656e75747a65726e616d656e20756e642064656d2050617373776f72642c2064696520647520626569206465696e65722052656769737472696572756e6720616e6765676562656e20686173742c20616e6d656c64656e2e2042656e75747a652064617320756e74656e2073746568656e646520466f726d756c61722c2077656e6e206475206469636820736f666f727420616d656c64656e2077696c6c73742e, '2011-02-16 20:12:03'),
(639, 1, 0x44696520452d4d61696c2d41647265737365203c6120687265663d226d61696c746f3a7b656d61696c2068746d6c7d2220636c6173733d22656d61696c223e7b656d61696c2068746d6c7d3c2f613e207775726465206572666f6c6772656963682062657374c3a4746967742e2046756e6b74696f6e656e20776965207a2e422e202671756f743b50617373776f72742076657267657373656e2671756f743b2077657264656e206162206a65747a7420617566206469657365204164726573736520617573676566c3bc6872742e, '2011-02-16 20:13:10'),
(640, 1, 0x556d206465696e656e2042656e75747a65726e616d656e207a7520c3a46e6465726e2c206d757373742064752064696368207a756ec3a46368737420616e6d656c64656e2e, '2011-02-16 21:09:34'),
(641, 1, 0x4e65756572204e616d65, '2011-02-16 21:15:46'),
(642, 1, 0x47696220686965722064656e206e6575656e2042656e75747a65726e616d656e2065696e2e, '2011-02-16 21:12:48'),
(643, 1, 0x57656e6e206475206465696e656e20656967656e656e2042656e75747a65726e616d656e20c3a46e6465726e206dc3b66368746573742c2067696220686965722064656e206e6575656e204e616d656e2065696e20756e64206b6c69636b6520617566202671756f743b416273656e64656e2671756f743b, '2011-02-16 21:17:09'),
(644, 1, 0x4465696e2042656e75747a65726e616d65207775726465206765c3a46e646572742e204d656c6465206469636820646173206ec3a46368737465206d616c206d69742064656d206e6575656e2042656e75747a65726e616d656e20616e2e, '2011-02-16 21:19:26'),
(645, 1, 0x48696572206b616e6e73742064752065696e6520452d4d61696c2d4164726573736520c3a46e6465726e206f64657220656e746665726e656e2e2057656e6e2064752064696573657320466f726d756c6172206d69742065696e6572206e6575656e2041647265737365206162676573636869636b7420686173742c2062656b6f6d6d73742064752065696e6520452d4d61696c206d69742065696e656d20496e7465726e65746c696e6b2e204b6c69636b65206175662064696573656e204c696e6b2c20756d207a7520626577656973656e2c20646173732064752064696520452d4d61696c20657268616c74656e206861737420756e642064696520452d4d61696c2d416472657373652064697220676568c3b672742e, '2011-02-16 21:23:29'),
(646, 1, 0x48696572206b616e6e73742064752065696e6520452d4d61696c2d4164726573736520c3a46e6465726e2e2057656e6e2064752064696573657320466f726d756c6172206d69742065696e6572206e6575656e2041647265737365206162676573636869636b7420686173742c2062656b6f6d6d73742064752065696e6520452d4d61696c206d69742065696e656d20496e7465726e65746c696e6b2e204b6c69636b65206175662064696573656e204c696e6b2c20756d207a7520626577656973656e2c20646173732064752064696520452d4d61696c20657268616c74656e206861737420756e642064696520452d4d61696c2d416472657373652064697220676568c3b672742e, '2011-02-16 21:23:38'),
(647, 1, 0x556d2065696e2042656e75747a65726b6f6e746f20207a752065727374656c6c656e2c206d7573737420647520646972206c656469676c6963682065696e656e206e6f6368206e696368742076657277656e646574656e2042656e75747a65726e616d656e20756e642065696e2067656865696d65732050617373776f72742061757364656e6b656e2e20446172c3bc6265722068696e617573206b616e6e73742064752065696e6520452d4d61696c2d4164726573736520616e676562656e2c20756d207a2e422e206465696e2050617373776f7274207a7572c3bc636b7365747a656e207a75206bc3b66e6e656e2c2066616c6c732064752065732076657267657373656e20736f6c6c746573742e, '2011-02-16 21:46:43'),
(648, 1, 0x556d2065696e2042656e75747a65726b6f6e746f20207a752065727374656c6c656e2c206d7573737420647520646972206c656469676c6963682065696e656e206e6f6368206e696368742076657277656e646574656e2042656e75747a65726e616d656e20756e642065696e2067656865696d65732050617373776f72742061757364656e6b656e20736f7769652065696e6520452d4d61696c2d4164726573736520616e676562656e2c2061756620646572656e20506f737465696e67616e67206475207a756772656966656e206b616e6e73742e, '2011-02-16 21:46:13'),
(649, 1, 0x417661746172, '2011-02-17 20:43:38'),
(650, 1, 0x4469652044617465692c2064696520647520686f63686c6164656e20776f6c6c746573742c20697374207a752067726fc39f2e20446965206d6178696d616c652044617465696772c3b6c39f652062657472c3a46774207b6d617853697a652068746d6c7d2e, '2011-02-18 22:08:25'),
(651, 1, 0x556d206465696e656e20417661746172207a7520c3a46e6465726e2c206d757373742064752064696368207a756ec3a46368737420616e6d656c64656e2e, '2011-02-18 22:30:59'),
(652, 1, 0x56657266c3bc67626172652057696467657473, '2011-04-02 22:48:16'),
(653, 1, 0x41756620646965736572205365697465206b616e6e7374206475206469652053696465626172206265617262656974656e2c206469652047c3a47374656e20756e6420616c6c656e2042656e75747a65726e2c20646965206b65696e6520656967656e65205369646562617220686162656e2c20616e67657a6569677420776972642e204a65206e616368204b6f6e66696775726174696f6e206bc3b66e6e656e207265676973747269657274652042656e75747a6572206469657365205369646562617220c3bc62657273636872656974656e20756e64206968726520656967656e652067657374616c74656e2e3c2f703e3c703e446965205369646562617220626573746568742061757320736f2067656e616e6e74656e203c693e576964676574733c2f693e2e204c696e6b732073696568737420647520616c6c652076657266c3bc67626172656e2c2072656368747320616c6c6520696e2064696520536964656261722065696e676562617574656e20576964676574732e20556d2065696e205769646765742061757320646572206c696e6b656e205370616c746520696e2064696520536964656261722065696e7a7566c3bc67656e2c20626573746568656e64652057696467657473207a7520656e746665726e656e206f646572207a7520766572736368696562656e2c2062656e75747a652064696520656e74737072656368656e64656e20536368616c74666cc3a46368656e2e, '2011-03-09 15:30:51'),
(654, 1, 0x57657220697374206f6e6c696e653f, '2011-03-09 15:49:22'),
(655, 1, 0x4d6f6d656e74616e20697374206b65696e20726567697374726965727465722042656e75747a657220617566206469657365722053656974652e, '2011-03-11 23:07:09'),
(656, 1, 0x416e6d656c64656e, '2011-03-12 17:01:55'),
(657, 1, 0x42656e75747a6572, '2011-03-12 17:02:01'),
(658, 1, 0x50617373776f7274, '2011-03-12 17:02:09'),
(659, 1, 0x416e67656d656c64657420616c73203c623e7b757365724e616d652068746d6c7d3c2f623e, '2011-03-12 17:15:23'),
(660, 1, 0x41626d656c64656e, '2011-03-12 17:09:51'),
(661, 1, 0x48696e7a7566c3bc67656e, '2011-03-31 19:59:26'),
(662, 1, 0x456e746665726e656e, '2011-03-31 21:13:54'),
(663, 1, 0x486f6368, '2011-03-31 21:15:19'),
(664, 1, 0x52756e746572, '2011-03-31 21:15:25'),
(665, 1, 0x44696573652053656974652076657266c3bc677420c3bc626572206b65696e6520756e74657267656f72646e6574656e2053656974656e2e, '2011-04-01 23:24:56'),
(666, 1, 0x442c206e2e204d, '2011-04-01 23:42:48'),
(669, 1, 0x556d20646965205369646562617220616e7a7570617373656e2c206d757373742064752064696368207a756ec3a46368737420616e6d656c64656e2e, '2011-04-02 23:31:02'),
(668, 1, 0x5374616e646172642d53696465626172, '2011-04-02 23:23:00'),
(670, 1, 0x5369646562617220616e70617373656e, '2011-04-02 23:31:32'),
(671, 1, 0x48696572206b616e6e7374206475206469652053696465626172206e616368206465696e656e2057c3bc6e736368656e20616e70617373656e2e3c2f703e3c703e556e74656e2073696568737420647520616c6c652076657266c3bc6762617265203c693e576964676574733c2f693e2c2064696520647520706572204b6e6f7066647275636b20696e2064696520536964656261722065696e626175656e206b616e6e73742e2044696520696e2064696520536964656261722065696e676562617574656e20576964676574732076657266c3bc67656e20c3bc62657220536368616c74666cc3a46368656e207a756d20566572736368696562656e20756e64204cc3b6736368656e2e, '2011-04-02 23:32:54'),
(672, 1, 0x4475206b616e6e73742064696520c3846e646572756e67656e2c2064696520647520616e20646572205369646562617220766f7267656e6f6d6d656e20686173742c2061756368207a7572c3bc636b6e65686d656e20756e64207a7572205374616e646172642d53696465626172207a7572c3bc636b6b656872656e2e, '2011-04-03 22:08:37'),
(673, 1, 0x53696465626172207a7572c3bc636b7365747a656e, '2011-04-03 22:08:48'),
(674, 1, 0x4dc3b663687465737420647520616c6c6520c3846e646572756e67656e2c2064696520647520616e20646572205369646562617220766f7267656e6f6d6d656e20686173742c207a7572c3bc636b6e65686d656e20756e6420646965205374616e646172642d53696465626172207769656465726865727374656c6c656e3f, '2011-04-03 22:23:32'),
(675, 1, 0x53696465626172, '2011-04-08 22:57:38'),
(676, 1, 0x50617373742064696520536964656261722066c3bc722064696573656e2042656e75747a657220616e, '2011-04-08 22:57:56'),
(677, 1, 0x46c3bc722064656e2047617374206b616e6e206b65696e6520656967656e652053696465626172206b6f6e6669677572696572742077657264656e2e2047c3a473746520736568656e20696d6d657220646965205374616e646172642d536964656261722e, '2011-04-09 00:07:31'),
(678, 1, 0x4dc3b663687465737420647520616c6c6520c3846e646572756e67656e2c2064696520616e20646572205369646562617220766f7267656e6f6d6d656e2077757264656e2028766f6e2064697220756e6420766f6d2042656e75747a65722073656c627374292c207a7572c3bc636b6e65686d656e20756e6420646965205374616e646172642d53696465626172207769656465726865727374656c6c656e3f, '2011-04-09 00:08:37'),
(679, 1, 0x4475206b616e6e73742064696520c3846e646572756e67656e2c2064696520616e20646572205369646562617220766f7267656e6f6d6d656e2077757264656e2028766f6e2064697220756e6420766f6d2042656e75747a65722073656c627374292c2061756368207a7572c3bc636b6e65686d656e20756e64207a7572205374616e646172642d53696465626172207a7572c3bc636b6b656872656e2e, '2011-04-09 00:10:39'),
(680, 1, 0x48696572206b616e6e7374206475206469652053696465626172206465732042656e75747a657273203c623e7b757365724e616d657d3c2f623e20616e70617373656e2e3c2f703e3c703e556e74656e2073696568737420647520616c6c652076657266c3bc6762617265203c693e576964676574733c2f693e2c2064696520647520706572204b6e6f7066647275636b20696e2064696520536964656261722065696e626175656e206b616e6e73742e2044696520696e2064696520536964656261722065696e676562617574656e20576964676574732076657266c3bc67656e20c3bc62657220536368616c74666cc3a46368656e207a756d20566572736368696562656e20756e64204cc3b6736368656e2e, '2011-04-09 00:11:47');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_styles`
--
-- Erzeugt am: 24. Januar 2011 um 19:05
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `isDefault` tinyint(1) NOT NULL,
  `isEnabled` tinyint(1) NOT NULL DEFAULT '1',
  `path` varchar(255) COLLATE utf8_bin NOT NULL,
  `author` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `isEnabled` (`isEnabled`),
  KEY `pluginID` (`pluginID`),
  KEY `isDefault` (`isDefault`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `premanager_styles`
--

INSERT INTO `premanager_styles` (`id`, `pluginID`, `isDefault`, `isEnabled`, `path`, `author`, `timestamp`) VALUES
(1, 0, 1, 1, 'styles/classic/style.xml', 'Jan Melcher', '2011-01-28 20:47:08'),
(2, 0, 0, 1, 'styles/yogularm/style.xml', 'Jan Melcher', '2011-01-28 20:35:52');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_stylestranslation`
--
-- Erzeugt am: 24. Januar 2011 um 19:05
-- Aktualisiert am: 24. Januar 2011 um 20:05
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_stylestranslation` (
  `id` int(11) NOT NULL,
  `languageID` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_stylestranslation`
--

INSERT INTO `premanager_stylestranslation` (`id`, `languageID`, `title`, `description`, `timestamp`) VALUES
(1, 1, 'Klassischer Stil', 0x576569c39f6520426cc3b6636b652061756620626c6175656d2048696e7465726772756e642e205363687761727a6520536368726966742e, '2011-01-23 16:37:29'),
(2, 1, 'Yogularm', 0x476573636877756e67656e65204e617669676174696f6e736c656973746520c3bc6265722064656e206f626572656e20756e64206c696e6b656e2052616e642e205363687761727a6520536368726966742061756620776569c39f656d2048696e7465726772756e642e, '2011-01-23 16:37:29');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_trees`
--
-- Erzeugt am: 22. November 2010 um 19:31
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_trees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `scope` enum('organization','projects','both') COLLATE utf8_bin NOT NULL DEFAULT 'both',
  `key` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`),
  KEY `scope` (`scope`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=29 ;

--
-- Daten für Tabelle `premanager_trees`
--

INSERT INTO `premanager_trees` (`id`, `pluginID`, `class`, `scope`, `key`, `timestamp`) VALUES
(1, 0, 'Premanager\\Pages\\UsersPage', 'organization', 'users', '2010-11-22 19:32:15'),
(4, 0, 'Premanager\\Pages\\LoginPage', 'organization', 'login', '2010-11-22 19:32:15'),
(15, 0, 'Premanager\\Pages\\GroupsPage', 'organization', 'groups', '2010-11-22 19:32:15'),
(16, 0, 'Premanager\\Pages\\ProjectsPage', 'organization', 'projects', '2010-11-22 19:32:15'),
(18, 0, 'Premanager\\Pages\\ViewonlinePage', 'organization', 'viewonline', '2011-01-03 17:22:41'),
(19, 0, 'Premanager\\Pages\\StructureOverviewPage', 'both', 'structure', '2011-01-07 13:44:47'),
(20, 0, 'Premanager\\Pages\\StylesPage', 'organization', 'styles', '2011-01-24 18:25:47'),
(21, 0, 'Premanager\\Pages\\RegisterPage', 'organization', 'register', '2011-01-28 21:12:00'),
(22, 0, 'Premanager\\Pages\\PasswordLostPage', 'organization', 'password-lost', '2011-02-05 17:35:52'),
(23, 0, 'Premanager\\Pages\\ChangePasswordPage', 'organization', 'change-password', '2011-02-08 16:18:58'),
(24, 0, 'Premanager\\Pages\\ChangeEmailPage', 'organization', 'change-email', '2011-02-08 18:10:35'),
(25, 0, 'Premanager\\Pages\\ChangeUserNamePage', 'organization', 'chagne-user-name', '2011-02-16 21:10:31'),
(26, 0, 'Premanager\\Pages\\ChangeAvatarPage', 'organization', 'change-avatar', '2011-02-18 22:26:43'),
(27, 7, 'Premanager\\Widgets\\Pages\\SidebarAdminPage', 'organization', 'sidebar-admin', '2011-03-09 15:22:02'),
(28, 7, 'Premanager\\Widgets\\Pages\\MySidebarPage', 'organization', 'my-sidebar', '2011-04-02 23:33:19');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_usergroup`
--
-- Erzeugt am: 28. Dezember 2010 um 22:29
-- Aktualisiert am: 09. März 2011 um 16:00
-- Letzter Check am: 09. März 2011 um 15:00
--

CREATE TABLE IF NOT EXISTS `premanager_usergroup` (
  `userID` int(10) unsigned NOT NULL,
  `groupID` int(10) unsigned NOT NULL,
  `joinTime` datetime NOT NULL,
  `joinIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`,`groupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_usergroup`
--

INSERT INTO `premanager_usergroup` (`userID`, `groupID`, `joinTime`, `joinIP`, `timestamp`) VALUES
(0, 1, '2010-04-28 19:49:28', '127.0.0.1', '2010-04-28 19:49:52'),
(2, 2, '2010-04-25 14:03:27', '127.0.0.1', '2010-04-25 16:18:18'),
(2, 26, '2010-12-29 23:48:05', '127.0.0.1', '2010-12-30 00:48:05'),
(2, 3, '2010-12-31 12:18:55', '127.0.0.1', '2010-12-31 13:18:55'),
(2, 22, '2010-12-31 12:16:44', '127.0.0.1', '2010-12-31 13:16:44'),
(2, 21, '2010-12-29 23:21:31', '127.0.0.1', '2010-12-30 00:21:31'),
(2, 20, '2011-01-02 21:03:42', '127.0.0.1', '2011-01-02 22:03:42'),
(2, 24, '2010-12-29 23:43:39', '127.0.0.1', '2010-12-30 00:43:39'),
(2, 23, '2010-12-29 23:43:39', '127.0.0.1', '2010-12-30 00:43:39'),
(79, 2, '2010-12-29 23:48:56', '127.0.0.1', '2010-12-30 00:48:56'),
(79, 26, '2010-12-29 23:49:17', '127.0.0.1', '2010-12-30 00:49:17'),
(79, 20, '2010-12-29 23:49:17', '127.0.0.1', '2010-12-30 00:49:17'),
(2, 25, '2011-01-02 17:22:23', '127.0.0.1', '2011-01-02 18:22:23'),
(79, 21, '2010-12-31 17:55:27', '127.0.0.1', '2010-12-31 18:55:27'),
(128, 2, '2011-02-16 19:22:45', '127.0.0.1', '2011-02-16 20:22:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_useroptions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 07. Oktober 2010 um 20:11
--

CREATE TABLE IF NOT EXISTS `premanager_useroptions` (
  `optionID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`optionID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_useroptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_users`
--
-- Erzeugt am: 05. Februar 2011 um 17:01
-- Aktualisiert am: 20. April 2011 um 17:25
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `registrationTime` datetime NOT NULL,
  `registrationIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastLoginTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastVisibleLoginTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastLoginIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `resetPasswordKey` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `resetPasswordStartTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `resetPasswordIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `color` char(6) COLLATE utf8_bin NOT NULL DEFAULT '000000',
  `email` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `unconfirmedEmail` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `unconfirmedEmailStartTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `unconfirmedEmailKey` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `status` enum('disabled','waitForEmail','enabled') COLLATE utf8_bin NOT NULL,
  `hasPersonalSidebar` int(1) NOT NULL DEFAULT '0',
  `hasAvatar` tinyint(1) NOT NULL DEFAULT '0',
  `avatarMIME` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `styleID` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `status` (`status`),
  KEY `resetPasswordKey` (`resetPasswordKey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=129 ;

--
-- Daten für Tabelle `premanager_users`
--

INSERT INTO `premanager_users` (`id`, `name`, `registrationTime`, `registrationIP`, `lastLoginTime`, `lastVisibleLoginTime`, `lastLoginIP`, `password`, `resetPasswordKey`, `resetPasswordStartTime`, `resetPasswordIP`, `color`, `email`, `unconfirmedEmail`, `unconfirmedEmailStartTime`, `unconfirmedEmailKey`, `status`, `hasPersonalSidebar`, `hasAvatar`, `avatarMIME`, `styleID`, `timestamp`) VALUES
(0, 'Guest', '2010-02-13 18:25:43', '127.0.0.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '0000-00-00 00:00:00', '', '5C5C5C', '', '', '0000-00-00 00:00:00', '', 'enabled', 0, 1, 'image/png', 0, '2011-02-18 22:20:01'),
(2, 'Yogu', '2010-02-13 18:27:13', '127.0.0.1', '2011-04-20 15:21:38', '2011-04-20 15:21:38', '127.0.0.1', 'abf342b4aa81567e3b3d05629961a1598111470658e69d7fce7bc841413cff98', '', '0000-00-00 00:00:00', '', '006600', 'yogu@example.com', '', '0000-00-00 00:00:00', '', 'enabled', 0, 1, 'image/png', 2, '2011-04-20 17:21:38'),
(128, 'Test-User', '2011-02-16 19:22:45', '127.0.0.1', '2011-02-16 19:23:01', '2011-02-16 19:23:01', '127.0.0.1', 'abf342b4aa81567e3b3d05629961a1598111470658e69d7fce7bc841413cff98', '', '0000-00-00 00:00:00', '', '000000', 'test@user.com', '', '0000-00-00 00:00:00', '', 'enabled', 0, 0, '', 0, '2011-02-16 20:23:01'),
(79, 'Markus', '2010-12-29 23:48:56', '127.0.0.1', '2011-04-03 20:35:46', '2011-04-03 20:35:46', '127.0.0.1', 'abf342b4aa81567e3b3d05629961a1598111470658e69d7fce7bc841413cff98', '', '0000-00-00 00:00:00', '', '64002E', 'markus@example.com', '', '0000-00-00 00:00:00', '', 'enabled', 0, 0, '', 0, '2011-04-03 22:35:46');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_usersname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 09. März 2011 um 16:00
-- Letzter Check am: 09. März 2011 um 15:00
--

CREATE TABLE IF NOT EXISTS `premanager_usersname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `inUse` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  UNIQUE KEY `name` (`name`),
  KEY `userID` (`id`),
  KEY `languageID` (`languageID`),
  KEY `inUse` (`inUse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=134 ;

--
-- Daten für Tabelle `premanager_usersname`
--

INSERT INTO `premanager_usersname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 2, 'jan', 1, 0, '2011-01-28 20:56:02'),
(2, 2, 'yogu', 1, 1, '2011-02-16 21:20:07'),
(4, 0, 'guest', 2, 1, '2010-05-24 19:58:52'),
(5, 0, 'anonymous', 0, 0, '2010-04-23 22:31:51'),
(74, 0, 'invité', 3, 1, '2010-05-24 21:13:47'),
(73, 0, 'gast', 1, 1, '2010-05-24 21:15:08'),
(83, 79, 'markus', 1, 1, '2010-12-30 00:48:56'),
(133, 2, 'yogu2', 1, 0, '2011-02-16 21:20:07'),
(132, 128, 'test-user', 1, 1, '2011-02-16 20:22:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_userstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:11
-- Aktualisiert am: 09. März 2011 um 16:00
-- Letzter Check am: 09. März 2011 um 15:00
--

CREATE TABLE IF NOT EXISTS `premanager_userstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_userstranslation`
--

INSERT INTO `premanager_userstranslation` (`id`, `languageID`, `title`, `timestamp`) VALUES
(0, 1, 'Gast', '2011-02-05 16:18:27'),
(79, 1, 'Projektmitglied', '2011-02-05 16:18:27'),
(79, 2, 'Project Member', '2011-02-05 16:18:27'),
(2, 1, 'Administrator', '2011-02-05 16:18:27'),
(2, 2, 'Administrator', '2011-02-05 16:18:27'),
(0, 2, 'Guest', '2011-02-05 16:18:27'),
(2, 3, 'Administrateur', '2011-02-05 16:18:27'),
(79, 3, 'Project Member', '2011-02-05 16:18:27'),
(0, 3, 'Invité', '2011-02-05 16:18:27'),
(128, 1, 'Registrierter Benutzer', '2011-02-16 20:22:45'),
(128, 2, 'Registered User', '2011-02-16 20:22:45'),
(128, 3, 'Utilisateur inscrit', '2011-02-16 20:22:45');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_widgets_widgetclasses`
--
-- Erzeugt am: 18. Februar 2011 um 23:07
-- Aktualisiert am: 01. April 2011 um 23:36
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_widgets_widgetclasses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `premanager_widgets_widgetclasses`
--

INSERT INTO `premanager_widgets_widgetclasses` (`id`, `pluginID`, `class`, `timestamp`) VALUES
(1, 7, 'Premanager\\Widgets\\ViewonlineWidget', '2011-03-09 15:16:45'),
(2, 7, 'Premanager\\Widgets\\LoginWidget', '2011-03-12 17:02:33'),
(3, 7, 'Premanager\\Widgets\\ProjectsWidget', '2011-04-01 23:03:36'),
(4, 7, 'Premanager\\Widgets\\SubpagesWidget', '2011-04-01 23:17:29'),
(5, 7, 'Premanager\\Widgets\\ClockWidget', '2011-04-01 23:29:07');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_widgets_widgetclassestranslation`
--
-- Erzeugt am: 18. Februar 2011 um 23:06
-- Aktualisiert am: 01. April 2011 um 23:36
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_widgets_widgetclassestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_widgets_widgetclassestranslation`
--

INSERT INTO `premanager_widgets_widgetclassestranslation` (`id`, `languageID`, `title`, `description`, `timestamp`) VALUES
(1, 1, 'Wer ist online?', 0x5a656967742065696e65204c69737465206465722042656e75747a65722c206469652067657261646520616e67656d656c6465742073696e64, '2011-03-09 15:17:24'),
(2, 1, 'Anmeldung', 0x42696574657420646965204dc3b6676c6963686b6569742c207369636820616e2d206f6465722061627a756d656c64656e, '2011-03-12 17:02:54'),
(3, 1, 'Projekte', 0x5a656967742065696e65204c69737465206465722050726f6a656b7465, '2011-04-01 23:03:56'),
(4, 1, 'Untergeordnete Seiten', 0x5a656967742065696e65204c69737465206465722053656974656e2c206469652064696573657220536569746520756e74657267656f72646e65742073696e64, '2011-04-01 23:18:46'),
(5, 1, 'Uhr', 0x5a656967742064696520616b7475656c6c65205568727a65697420756e642064617320446174756d20616e, '2011-04-01 23:29:31');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_widgets_widgetcollections`
--
-- Erzeugt am: 03. April 2011 um 21:25
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_widgets_widgetcollections` (
  `userID` int(10) unsigned NOT NULL,
  `nodeID` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`,`nodeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_widgets_widgetcollections`
--

INSERT INTO `premanager_widgets_widgetcollections` (`userID`, `nodeID`, `timestamp`) VALUES
(2, 0, '2011-04-03 22:34:33'),
(128, 0, '2011-04-09 00:36:10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_widgets_widgetoptions`
--
-- Erzeugt am: 18. Februar 2011 um 23:11
-- Aktualisiert am: 19. Februar 2011 um 00:11
--

CREATE TABLE IF NOT EXISTS `premanager_widgets_widgetoptions` (
  `id` int(10) unsigned NOT NULL,
  `widgetClassID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `defalutValue` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widgetClassID` (`widgetClassID`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_widgets_widgetoptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_widgets_widgetoptionvalues`
--
-- Erzeugt am: 18. Februar 2011 um 23:10
-- Aktualisiert am: 19. Februar 2011 um 00:10
--

CREATE TABLE IF NOT EXISTS `premanager_widgets_widgetoptionvalues` (
  `optionID` int(10) unsigned NOT NULL,
  `widgetID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`optionID`,`widgetID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `premanager_widgets_widgetoptionvalues`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_widgets_widgets`
--
-- Erzeugt am: 03. April 2011 um 21:25
-- Aktualisiert am: 20. April 2011 um 17:26
-- Letzter Check am: 20. April 2011 um 17:26
--

CREATE TABLE IF NOT EXISTS `premanager_widgets_widgets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `widgetClassID` int(10) unsigned NOT NULL,
  `nodeID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `column` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned NOT NULL,
  `isMinimized` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editTimes` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `nodeID` (`nodeID`,`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=101 ;

--
-- Daten für Tabelle `premanager_widgets_widgets`
--

INSERT INTO `premanager_widgets_widgets` (`id`, `widgetClassID`, `nodeID`, `userID`, `column`, `order`, `isMinimized`, `createTime`, `editTime`, `creatorID`, `editorID`, `editTimes`, `timestamp`) VALUES
(84, 1, 0, 0, 0, 1, 0, '2011-04-03 20:36:53', '2011-04-03 20:36:53', 2, 2, 0, '2011-04-03 22:36:53'),
(40, 2, 0, 0, 0, 0, 0, '2011-04-02 22:09:19', '2011-04-02 22:09:19', 2, 2, 0, '2011-04-03 00:11:43'),
(90, 4, 0, 79, 0, 2, 0, '2011-04-08 22:04:22', '2011-04-08 22:04:22', 2, 2, 0, '2011-04-09 00:04:22'),
(89, 1, 0, 79, 0, 1, 0, '2011-04-08 22:04:22', '2011-04-08 22:04:22', 2, 2, 0, '2011-04-09 00:04:22'),
(88, 2, 0, 79, 0, 0, 0, '2011-04-08 22:04:22', '2011-04-08 22:04:22', 2, 2, 0, '2011-04-09 00:04:22'),
(80, 5, 0, 2, 0, 0, 0, '2011-04-03 20:34:33', '2011-04-03 20:34:33', 2, 2, 0, '2011-04-03 22:34:41'),
(79, 1, 0, 2, 0, 2, 0, '2011-04-03 20:34:33', '2011-04-03 20:34:33', 2, 2, 0, '2011-04-03 22:34:38'),
(78, 2, 0, 2, 0, 1, 0, '2011-04-03 20:34:33', '2011-04-03 20:34:33', 2, 2, 0, '2011-04-03 22:34:41'),
(86, 4, 0, 0, 0, 2, 0, '2011-04-08 21:43:07', '2011-04-08 21:43:07', 2, 2, 0, '2011-04-08 23:43:07'),
(91, 1, 0, 79, 0, 3, 0, '2011-04-08 22:04:22', '2011-04-08 22:04:22', 2, 2, 0, '2011-04-09 00:04:22'),
(100, 4, 0, 128, 0, 2, 0, '2011-04-08 22:36:10', '2011-04-08 22:36:10', 2, 2, 0, '2011-04-09 00:36:10'),
(99, 1, 0, 128, 0, 1, 0, '2011-04-08 22:36:10', '2011-04-08 22:36:10', 2, 2, 0, '2011-04-09 00:36:10'),
(98, 2, 0, 128, 0, 0, 0, '2011-04-08 22:36:10', '2011-04-08 22:36:10', 2, 2, 0, '2011-04-09 00:36:10');
