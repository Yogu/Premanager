-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 03. November 2010 um 23:54
-- Server Version: 5.1.41
-- PHP-Version: 5.3.1

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
-- Tabellenstruktur für Tabelle `premanager_0_groupright`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_groupright` (
  `groupID` int(10) unsigned NOT NULL,
  `rightID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`groupID`,`rightID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_groupright`:
--   `groupID`
--       `premanager_0_groups` -> `id`
--   `rightID`
--       `premanager_0_rights` -> `id`
--

--
-- Daten für Tabelle `premanager_0_groupright`
--

INSERT INTO `premanager_0_groupright` (`groupID`, `rightID`, `timestamp`) VALUES
(6, 1, '2010-06-27 14:37:27'),
(2, 16, '2010-06-26 21:26:48'),
(6, 11, '2010-06-27 14:37:27'),
(6, 2, '2010-06-27 14:37:27'),
(6, 7, '2010-06-27 14:37:27'),
(6, 5, '2010-06-27 14:37:27'),
(6, 3, '2010-06-27 14:37:27'),
(3, 6, '2010-06-27 14:37:34'),
(2, 8, '2010-06-26 21:26:48'),
(1, 8, '2010-05-08 18:41:54'),
(2, 9, '2010-06-26 21:26:48'),
(2, 2, '2010-06-26 21:26:48'),
(2, 10, '2010-06-26 21:26:48'),
(3, 4, '2010-06-27 14:37:34'),
(6, 17, '2010-06-27 14:37:27'),
(11, 12, '2010-06-11 15:53:13'),
(11, 13, '2010-06-11 15:53:13'),
(12, 12, '2010-06-11 15:54:08'),
(12, 13, '2010-06-11 15:54:08'),
(12, 14, '2010-06-11 15:54:08'),
(12, 15, '2010-06-11 15:54:08');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_groups`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `color` char(6) COLLATE utf8_bin NOT NULL,
  `priority` int(10) unsigned NOT NULL,
  `autoJoin` tinyint(1) NOT NULL DEFAULT '0',
  `isLocked` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order` (`priority`),
  KEY `isFixed` (`isLocked`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- RELATIONEN DER TABELLE `premanager_0_groups`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_0_groups`
--

INSERT INTO `premanager_0_groups` (`id`, `color`, `priority`, `autoJoin`, `isLocked`, `createTime`, `editTime`, `editTimes`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `timestamp`) VALUES
(1, '5C5C5C', 0, 0, 0, '2010-02-14 00:41:15', '2010-04-24 15:10:06', 3, 2, '127.0.0.1', 2, '127.0.0.1', '2010-05-23 23:59:45'),
(2, '000000', 1, 1, 0, '2010-02-14 00:42:55', '2010-04-24 01:18:01', 1, 2, '127.0.0.1', 2, '127.0.0.1', '2010-05-23 23:59:37'),
(3, '006600', 10, 0, 1, '2010-03-05 23:55:33', '2010-04-24 01:16:54', 1, 2, '127.0.0.1', 2, '127.0.0.1', '2010-04-29 16:45:54'),
(6, '339933', 9, 0, 1, '2010-04-26 21:05:08', '2010-05-12 21:51:26', 6, 2, '127.0.0.1', 2, '127.0.0.1', '2010-05-12 21:51:26'),
(11, '000080', 0, 0, 0, '2010-06-11 13:48:24', '2010-07-04 10:28:52', 2, 2, '127.0.0.1', 2, '127.0.0.1', '2010-07-04 12:28:28'),
(12, 'a04000', 0, 0, 0, '2010-06-11 13:53:56', '2010-06-11 13:57:12', 3, 2, '127.0.0.1', 2, '127.0.0.1', '2010-06-11 15:57:12');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_groupsname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_groupsname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `inUse` int(10) unsigned NOT NULL DEFAULT '1',
  `languageID` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  UNIQUE KEY `name` (`name`),
  KEY `groupID` (`id`),
  KEY `inUse` (`inUse`),
  KEY `languageID` (`languageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=22 ;

--
-- RELATIONEN DER TABELLE `premanager_0_groupsname`:
--   `id`
--       `premanager_0_groups` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_groupsname`
--

INSERT INTO `premanager_0_groupsname` (`nameID`, `id`, `name`, `inUse`, `languageID`, `timestamp`) VALUES
(1, 1, 'gäste', 1, 1, '2010-04-24 01:16:11'),
(2, 1, 'guests', 1, 2, '2010-04-24 01:16:11'),
(3, 2, 'registrierte benutzer', 1, 1, '2010-04-24 01:18:01'),
(4, 2, 'registered users', 1, 2, '2010-04-24 01:18:01'),
(5, 3, 'administratoren', 1, 1, '2010-04-24 01:16:54'),
(6, 3, 'administrators', 1, 2, '2010-04-24 01:16:54'),
(9, 1, 'invités', 1, 3, '2010-04-24 01:16:11'),
(10, 3, 'administrateurs', 1, 3, '2010-04-24 01:16:54'),
(11, 2, 'utilisateurs inscrits', 1, 3, '2010-04-24 01:18:01'),
(13, 6, 'co-admins', 1, 1, '2010-04-26 21:05:08'),
(18, 4, 'daspuzzlederwaisen', 0, 1, '2010-06-06 18:17:04'),
(19, 4, 'das-puzzle-der-waisen2', 1, 1, '2010-06-06 18:17:04'),
(20, 11, 'redakteure', 1, 1, '2010-06-11 15:48:24'),
(21, 12, 'moderatoren', 1, 1, '2010-06-11 15:53:56');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_groupstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
--

CREATE TABLE IF NOT EXISTS `premanager_0_groupstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_groupstranslation`:
--   `id`
--       `premanager_0_groups` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_groupstranslation`
--

INSERT INTO `premanager_0_groupstranslation` (`id`, `languageID`, `name`, `text`, `title`, `timestamp`) VALUES
(1, 1, 'Gäste', 'Gäste sind nicht angemeldete Besucher dieser Seite', 'Gast', '2010-02-14 20:08:18'),
(1, 2, 'Guests', 'Guests are visitors who have not logged in', 'Guest', '2010-03-08 21:06:51'),
(2, 1, 'Registrierte Benutzer', 'Diese Gruppe fasst alle Mitglieder zusammen, die sich registriert haben und sich somit mit ihrem geheimen Passwort anmelden können. Die Mitgliedschaft in dieser Gruppe verleiht keine besonderen Rechte. Lediglich Funktionen, die das Benutzerkonto selbst betreffen, werden dadurch freigeschaltet.', 'Registrierter Benutzer', '2010-02-14 20:09:52'),
(2, 2, 'Registered Users', 'This groups covers all registered visitors who can log in with their secret password. The membership of this group does not grant any special rights except the right to manage their own account.', 'Registered User', '2010-02-14 20:12:24'),
(3, 1, 'Administratoren', 'Administratoren sorgen für die Verfügbarkeit und Funktionstüchtigkeit der Website', 'Administrator', '2010-03-05 23:56:47'),
(3, 2, 'Administrators', 'Administrators make this web site work', 'Administrator', '2010-03-05 23:56:47'),
(1, 3, 'Invités', 'Invités sont des visiteurs qui ne sont pas connecté.', 'Invité', '2010-04-24 15:10:06'),
(3, 3, 'Administrateurs', 'Administrateurs s''occupe de ce website.', 'Administrateur', '2010-04-24 01:16:54'),
(2, 3, 'Utilisateurs inscrits', 'This groups covers all registered visitors who can log in with their secret password. The membership of this group does not grant any special rights except the right to manage their own account.', 'Utilisateur inscrit', '2010-04-24 01:18:01'),
(6, 1, 'Co-Admins', 'Co-Admins kümmern sich um die Seite und sind den Administratoren untergeordnet.', 'Co-Admin', '2010-04-26 21:05:08'),
(4, 1, 'das-puzzle-der-waisen2', '', 'Das Puzzle der Waisen', '2010-06-06 18:17:04'),
(11, 1, 'Redakteure', 'Redakteure können Blog-Einträge verfassen.', 'Redakteur', '2010-06-11 15:48:24'),
(12, 1, 'Moderatoren', 'Moderatoren können Blog-Einträge veröffentlichen und löschen.', 'Moderator', '2010-06-11 15:53:56');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_languages`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_languages` (
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
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `shortDateTimeFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Y-m-d H:i',
  `shortDateFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Y-m-d',
  `shortTimeFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'H:i',
  `longDateTimeFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'j F Y H:i',
  `longDateFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'j F Y',
  `longTimeFormat` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'H:i',
  `order` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`name`),
  KEY `isInternational` (`isInternational`),
  KEY `order` (`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- RELATIONEN DER TABELLE `premanager_0_languages`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_0_languages`
--

INSERT INTO `premanager_0_languages` (`id`, `name`, `title`, `englishTitle`, `isDefault`, `isInternational`, `createTime`, `editTime`, `editTimes`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `shortDateTimeFormat`, `shortDateFormat`, `shortTimeFormat`, `longDateTimeFormat`, `longDateFormat`, `longTimeFormat`, `order`, `timestamp`) VALUES
(1, 'de', 'Deutsch', 'German', 1, 0, '2010-02-13 18:23:49', '2010-02-13 18:23:49', 0, 0, '127.0.0.1', 0, '127.0.0.1', 'j.m.Y H:i', 'j.m.Y', 'H:i', '|l, j. F Y|, H:i', '|l, j. F Y|', 'H:i', 0, '2010-03-05 23:06:50'),
(2, 'en', 'English', 'English', 0, 1, '2010-02-13 21:57:46', '2010-02-13 21:57:46', 0, 2, '127.0.0.1', 2, '127.0.0.1', 'm/d/Y H:i', 'm/d/Y', 'H:i', '|F n, Y|, h:i a', '|F n, Y|', 'h:i a', 1, '2010-04-10 22:00:16'),
(3, 'fr', 'Français', 'French', 0, 0, '2010-04-07 23:08:47', '2010-04-07 23:08:47', 0, 2, '127.0.0.1', 2, '127.0.0.1', 'j-m-Y', 'j-m-Y H:i', 'H:i', 'l j F Y H:i', 'l j F Y', 'H:i', 2, '2010-04-10 22:00:19');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_log`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_log` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=299 ;

--
-- RELATIONEN DER TABELLE `premanager_0_log`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_0_log`
--

INSERT INTO `premanager_0_log` (`id`, `url`, `referer`, `userAgent`, `text`, `type`, `createTime`, `creatorID`, `creatorIP`, `timestamp`) VALUES
(1, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Blog\\scripts\\Article.php on line 125 and defined', 'warning', '2010-06-12 11:54:00', 2, '127.0.0.1', '2010-06-12 13:54:00'),
(2, 'http://de.rack.localhost/blog/ein-neuer-blog?delete', 'http://de.rack.localhost/blog/ein-neuer-blog?delete', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Errorneous query (Query: DELETE FROM rack_premanager_revisions WHERE articleID = ''3''; Mysql Error: Table ''rack.rack_premanager_revisions'' doesn''t exist)', 'error', '2010-06-12 12:32:56', 2, '127.0.0.1', '2010-06-12 14:32:56'),
(3, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Uncaught exception (Class: Dwoo_Compilation_Exception; Message: Compilation error at line 47 in &quot;file:D:\\Programmierung\\webspace\\own\\rack\\plugins/Blog/templates/articleAdd.tpl&quot; : Parse error in &quot;staticURLPrefix=escape({$Config_staticURLPrefix})};&lt;/script&gt;\r\n		&lt;script type=&quot;text/javascript&quot; src=&quot;{$Config_staticPrefix}Premanager/scripts/prototype.js&quot;&gt;&lt;/script&gt;\r\n		&lt;script type=&quot;text/javascript&quot; src=&quot;{$Config_staticPrefix}Premanager/scripts/tools.js&quot;&gt;&lt;/script&gt;\r\n		&lt;script type=&quot;text/javascript&quot; src=&quot;{$Config_staticPrefix}Premanager/scripts/window.js&quot;&gt;&lt;/script&gt;\r\n			&lt;script type=&quot;text/javascript&quot; src=&quot;{$Config_staticURLPrefix}Premanager/scripts/wysiwyg.js&quot;&gt;&lt;/script&gt;\r\n	&lt;/head&gt;\r\n	\r\n	&lt;body lang=&quot;{$Lang_code}&quot;&gt;\r\n		&lt;div id=&quot;header&quot;&gt;\r\n			&lt;div id=&quot;organization-title&quot;&gt;\r\n				&lt;span class=&quot;text&quot;&gt;&lt;a href=&quot;http://{urlTemplate(null, null, '''')}&quot;&gt;{html $Org_title}&lt;/a&gt;&lt;/span&gt;\r\n			&lt;/div&gt;\r\n			\r\n			{if $Prj_id != 0}\r\n				&lt;div id=&quot;project-title&quot;&gt;\r\n					&lt;span class=&quot;text&quot;&gt;&lt;a href=&quot;./&quot;&gt;{html $Prj_title}&lt;/a&gt;&lt;/span&gt;\r\n				&lt;/div&gt;\r\n			{/if}\r\n		&lt;/div&gt;\r\n\r\n		&lt;div class=&quot;navigation&quot;&gt;\r\n			&lt;ul class=&quot;location&quot;&gt;\r\n				&lt;li&gt;&lt;a href=&quot;http://{urlTemplate(null, null, '''')}&quot;&gt;{html $Org_title}&lt;/a&gt;&lt;/li&gt;\r\n				{if $Prj_id != 0}\r\n					&lt;li&gt;&lt;a href=&quot;./&quot;&gt;{html $Prj_title}&lt;/a&gt;&lt;/li&gt;\r\n				{/if}\r\n				{$Premanager_navigationList}\r\n			&lt;/ul&gt;\r\n			\r\n			{if $List_pageCount &gt; 1}\r\n				&lt;div class=&quot;pagination&quot;&gt;\r\n					{if $List_page &gt; 1}\r\n						&lt;a href=&quot;./{if $List_page-1 &gt; 1}{$List_baseURL}{$List_page-1}{else}{$List_firstPageURL}{/if}&quot; class=&quot;page-back&quot;&gt;{string Premanager pageBack}&lt;/a&gt;\r\n					{/if}\r\n					\r\n					&lt;span class=&quot;pages&quot;&gt;\r\n						{if $List_page &gt; 1}&lt;a href=&quot;./{$List_firstPageURL}&quot;&gt;1&lt;/a&gt;{/if}\r\n						{if $List_page &gt; 2}&lt;a href=&quot;./{$List_baseURL}2&quot;&gt;2&lt;/a&gt;{/if}\r\n						{if $List_page &gt; 3}&lt;a href=&quot;./{$List_baseURL}3&quot;&gt;3&lt;/a&gt;{/if}\r\n						\r\n						{if $List_page &gt; 5}&lt;span class=&quot;gap&quot;&gt;{string Premanager literalGap}&lt;/span&gt;{/if}\r\n\r\n            {if $List_page &gt; 5 &amp;&amp; $List_page == $List_pageCount}&lt;a href=&quot;./{$List_baseURL}{$List_page-2}&quot;&gt;{$List_page-2}&lt;/a&gt;{/if} \r\n						{if $List_page &gt; 4}&lt;a href=&quot;./{$List_baseURL}{$List_page-1}&quot;&gt;{$List_page-1}&lt;/a&gt;{/if}\r\n						\r\n						&lt;span class=&quot;current&quot;&gt;{$List_page}&lt;/span&gt;\r\n						\r\n						{if $List_page &lt; $List_pageCount-3}&lt;a href=&quot;./{$List_baseURL}{$List_page+1}&quot;&gt;{$List_page+1}{/if}\r\n						\r\n						{if $List_page &lt; $List_pageCount-4}{string Premanager literalGap}&lt;/span&gt;{/if}\r\n						\r\n						{if $List_page &lt; $List_pageCount-2}&lt;a href=&quot;./{$List_baseURL}{$List_pageCount-2}&quot;&gt;{$List_pageCount-2}&lt;/a&gt;{/if}\r\n						{if $List_page &lt; $List_pageCount-1}&lt;a href=&quot;./{$List_baseURL}{$List_pageCount-1}&quot;&gt;{$List_pageCount-1}&lt;/a&gt;{/if}\r\n						{if $List_page &lt; $List_pageCount}&lt;a href=&quot;./{$List_baseURL}{$List_pageCount}&quot;&gt;{$List_pageCount}&lt;/a&gt;{/if}\r\n					&lt;/span&gt;\r\n\r\n					{if $List_page &lt; $List_pageCount}\r\n						&lt;a href=&quot;./{$List_baseURL}{$List_page+1}&quot; class=&quot;page-forward&quot;&gt;{string Premanager pageForward}&lt;/a&gt;\r\n					{/if}\r\n				&lt;/div&gt;			\r\n			{/if}\r\n		&lt;/div&gt;\r\n		\r\n			&lt;ul class=&quot;toolbar&quot;&gt;\r\n		&lt;li&gt;&lt;a href=&quot;{$Client_internalRequestURL}?add&quot; class=&quot;tool-add-article active&quot; title=&quot;{string Blog addArticleDescription}&quot;&gt;{string Blog addArticle}&lt;/a&gt;&lt;/li&gt;\r\n	&lt;/ul&gt;	\r\n\r\n		&lt;div id=&quot;content&quot;{if $Premanager_sidebar &amp;&amp; $Premanager_navigationTree} class=&quot;with-sidebar with-navigation-tree&quot;{elseif $Premanager_sidebar} class=&quot;with-sidebar&quot;{elseif $Premanager_navigationTree} class=&quot;with-navigation-tree&quot;{/if}&gt;\r\n			{$Premanager_globalArea}\r\n		\r\n			{if $Premanager_debug &amp;&amp; $Premanager_log}\r\n				&lt;dl class=&quot;block&quot; id=&quot;log&quot;&gt;\r\n					&lt;dt&gt;Log&lt;/dt&gt;\r\n					&lt;dd&gt;\r\n						&lt;ul class=&quot;list&quot;&gt;{$Premanager_log}&lt;/ul&gt;\r\n					&lt;/dd&gt;\r\n				&lt;/dl&gt;\r\n			{/if}\r\n		                 \r\n			{$Premanager_beforeContent}     \r\n			\r\n		\r\n			&lt;dl class=&quot;block&quot; id=&quot;main-block&quot;&gt;\r\n				&lt;dt&gt;{html $Nde_standAloneTitle}&lt;/dt&gt;\r\n				&lt;dd&gt;\r\n						&lt;form action=&quot;{html $Client_requestURL}&quot; method=&quot;post&quot;&gt;\r\n		{if $Blog_Article_inputErrors}\r\n			&lt;ul class=&quot;input-errors&quot;&gt;\r\n				{$Blog_Article_inputErrors}	\r\n			&lt;/ul&gt;\r\n		{/if}   \r\n	\r\n		&lt;fieldset class=&quot;inputs&quot;&gt;\r\n			&lt;dl&gt;\r\n				&lt;dt&gt;&lt;label for=&quot;Blog_Article_title&quot;&gt;{string Premanager label array(label=string(Blog articleTitle))}&lt;/label&gt;&lt;/dt&gt;\r\n				&lt;dd&gt;\r\n					&lt;input type=&quot;text&quot; name=&quot;Blog_Article_title&quot; id=&quot;Blog_Article_title&quot; value=&quot;{html $Blog_Article_formTitle}&quot; class=&quot;fullsize{if $Blog_Article_title_incorrect} error{/if}&quot; /&gt;\r\n					&lt;p&gt;{string Blog articleTitleDescription}&lt;/p&gt;\r\n				&lt;/dd&gt;\r\n			&lt;/dl&gt;\r\n			       \r\n			&lt;dl&gt;\r\n				&lt;dt&gt;&lt;label for=&quot;Blog_Article_title&quot;&gt;{string Premanager label array(label=string(Blog articleText))}&lt;/label&gt;&lt;/dt&gt;\r\n				&lt;dd&gt;\r\n					&lt;textarea id=&quot;Blog_Article_text&quot; name=&quot;Blog_Article_text&quot; class=&quot;wysiwyg fullsize{if $Blog_Article_text_incorrect} error{/if}&quot; cols=&quot;80&quot; rows=&quot;5&quot; &gt;{html $Blog_Article_text}&lt;/textarea&gt;\r\n					&lt;p&gt;{string Blog articleTextDescription}&lt;/p&gt;\r\n				&lt;/dd&gt;\r\n			&lt;/dl&gt;     \r\n			\r\n			&lt;dl&gt;\r\n				&lt;dt&gt;&lt;label for=&quot;Blog_Article_summary&quot;&gt;{string Premanager label array(label=string(Blog summaryLabel))}&lt;/label&gt;&lt;/dt&gt;\r\n				&lt;dd&gt;\r\n					&lt;input type=&quot;text&quot; name=&quot;Blog_Article_summary&quot; id=&quot;Blog_Article_summary&quot; value=&quot;{html $Blog_Article_summary}&quot; class=&quot;fullsize{if $Blog_Article_summary_incorrect} error{/if}&quot; /&gt;\r\n					&lt;p&gt;{if $Blog_Article_mode == ''add''}{string Blog summaryCreatedDescription}{else}{string Blog summaryDescription}{/if}&lt;/p&gt;\r\n				&lt;/dd&gt;\r\n			&lt;/dl&gt;\r\n		&lt;/fieldset&gt;\r\n		\r\n		&lt;fieldset class=&quot;buttons&quot;&gt;\r\n			&lt;input type=&quot;submit&quot; name=&quot;Blog_Article_submit&quot; class=&quot;main&quot; value=&quot;{string Premanager submitButton}&quot; /&gt;\r\n			&lt;input type=&quot;submit&quot; name=&quot;Blog_Article_preview&quot; value=&quot;{string Premanager previewButton}&quot; /&gt;\r\n		&lt;/fieldset&gt;\r\n	&lt;/form&gt;				\r\n				&lt;/dd&gt;\r\n			&lt;/dl&gt;\r\n			\r\n					\r\n			{$Premanager_afterContent}		\r\n		&lt;/div&gt;\r\n		\r\n		{if $Premanager_navigationTree}\r\n			&lt;ul id=&quot;navigation-tree&quot;&gt;\r\n				{$Premanager_navigationTree}		\r\n			&lt;/ul&gt;\r\n		{/if}\r\n		\r\n		{if $Premanager_sidebar}\r\n			&lt;div id=&quot;sidebar&quot;&gt;\r\n				{$Premanager_sidebar}\r\n			&lt;/div&gt;\r\n		{/if} \r\n	&lt;/body&gt;\r\n&lt;/html&gt;\r\n&quot;; File: D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\thirdparty\\dwoo\\Dwoo\\Compiler.php:1403)', 'error', '2010-06-12 12:56:02', 2, '127.0.0.1', '2010-06-12 14:56:02'),
(4, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Blog\\scripts\\Article.php on line 127 and defined', 'warning', '2010-06-12 13:30:32', 2, '127.0.0.1', '2010-06-12 15:30:32'),
(5, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?rev=1', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?revisions', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: require_once(D:\\Programmierung\\webspace\\own\\rack\\plugins/Premanager/scripts/PML) [function.require-once]: failed to open stream: No such file or directory', 'warning', '2010-06-12 17:05:26', 0, '127.0.0.1', '2010-06-12 19:05:26'),
(6, 'http://de.rack.localhost/blog/blog-funktion-im-aufbau', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?rev=1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for PMLNode::getInnerXML(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php on line 300 and defined', 'warning', '2010-06-12 17:21:48', 0, '127.0.0.1', '2010-06-12 19:21:48'),
(7, 'http://de.rack.localhost/blog/blog-funktion-im-aufbau', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?rev=1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for PMLNode::getInnerXML(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php on line 300 and defined', 'warning', '2010-06-12 17:21:48', 0, '127.0.0.1', '2010-06-12 19:21:48'),
(8, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Blog\\scripts\\Article.php on line 131 and defined', 'warning', '2010-06-18 16:11:17', 2, '127.0.0.1', '2010-06-18 18:11:17'),
(9, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-18 16:23:26', 2, '127.0.0.1', '2010-06-18 18:23:26'),
(10, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-18 16:23:26', 2, '127.0.0.1', '2010-06-18 18:23:26'),
(11, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-18 16:23:26', 2, '127.0.0.1', '2010-06-18 18:23:26'),
(12, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-18 16:48:16', 2, '127.0.0.1', '2010-06-18 18:48:16'),
(13, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php:69)', 'warning', '2010-06-18 16:48:16', 2, '127.0.0.1', '2010-06-18 18:48:16'),
(14, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php:69)', 'warning', '2010-06-18 16:48:16', 2, '127.0.0.1', '2010-06-18 18:48:16'),
(15, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php:69)', 'warning', '2010-06-18 16:48:16', 2, '127.0.0.1', '2010-06-18 18:48:16'),
(16, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for Premanager::log(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php on line 286 and defined', 'warning', '2010-06-18 17:08:57', 2, '127.0.0.1', '2010-06-18 19:08:57'),
(17, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for Premanager::log(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php on line 286 and defined', 'warning', '2010-06-18 17:09:35', 2, '127.0.0.1', '2010-06-18 19:09:35'),
(18, 'http://de.rack.localhost/blog/test', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for Premanager::log(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PML.php on line 286 and defined', 'warning', '2010-06-18 17:09:35', 2, '127.0.0.1', '2010-06-18 19:09:35'),
(19, 'http://de.rack.localhost/blog/und-so-gehts?edit&rev=2', 'http://de.rack.localhost/blog/und-so-gehts?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Accessed string translation does not exist: Blog.preview', 'error', '2010-06-18 17:48:14', 2, '127.0.0.1', '2010-06-18 19:48:14'),
(20, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Blog\\scripts\\Article.php on line 131 and defined', 'warning', '2010-06-18 18:06:01', 2, '127.0.0.1', '2010-06-18 20:06:01'),
(21, 'http://de.rack.localhost/blog/blog-funktion-im-aufbau', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Widget class not found: Premanager_LoginWidget', 'error', '2010-06-18 22:13:54', 0, '127.0.0.1', '2010-06-19 00:13:54'),
(22, 'http://de.rack.localhost/blog/blog-funktion-im-aufbau', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Widget class not found: Premanager_LoginWidget', 'error', '2010-06-18 22:14:32', 0, '127.0.0.1', '2010-06-19 00:14:32'),
(23, 'http://de.rack.localhost/', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Errorneous query (Query: INSERT INTO rack_premanager_sessions (userID, startTime, lastRequestTime, cookie, ip, userAgent, secondaryPasswordUsed, hidden, projectID) VALUES (''0'', NOW(), NOW(), ''573826e0fa9f9ef1ee0e086990203dd47fa73a462bf89a7788d0a81878a688b2'', ''127.0.0.1'', ''Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)'', 0, 0, ''0''); Mysql Error: Field ''isFirstRequest'' doesn''t have a default value)', 'error', '2010-06-19 09:34:00', 0, '127.0.0.1', '2010-06-19 11:34:00'),
(24, 'http://de.rack.localhost/', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Errorneous query (Query: INSERT INTO rack_premanager_sessions (userID, startTime, lastRequestTime, cookie, ip, userAgent, secondaryPasswordUsed, hidden, projectID) VALUES (''0'', NOW(), NOW(), ''c5c12c584d39c6076a0fba535d90de497c8d7db951f652ca8fdbf80231a1ce00'', ''127.0.0.1'', ''Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)'', 0, 0, ''0''); Mysql Error: Field ''isFirstRequest'' doesn''t have a default value)', 'error', '2010-06-19 09:36:18', 0, '127.0.0.1', '2010-06-19 11:36:18'),
(25, 'http://de.rack.localhost/', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Errorneous query (Query: INSERT INTO rack_premanager_sessions (userID, startTime, lastRequestTime, cookie, ip, userAgent, secondaryPasswordUsed, hidden, projectID) VALUES (''0'', NOW(), NOW(), ''e7aedcf6f8a87feb2b973cbef45dcf36c4cebfb48ac457fbff4bd6fcaa34a068'', ''127.0.0.1'', ''Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)'', 0, 0, ''0''); Mysql Error: Field ''isFirstRequest'' doesn''t have a default value)', 'error', '2010-06-19 09:36:32', 0, '127.0.0.1', '2010-06-19 11:36:32'),
(26, 'http://de.rack.localhost/', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Widget class not found: Premanager_LoginWidget', 'error', '2010-06-19 09:38:36', 0, '127.0.0.1', '2010-06-19 11:38:36'),
(27, 'http://de.rack.localhost/', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Widget class not found: Premanager_LoginWidget', 'error', '2010-06-19 09:44:09', 0, '127.0.0.1', '2010-06-19 11:44:09'),
(28, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Blog\\scripts\\Article.php on line 131 and defined', 'warning', '2010-06-19 10:04:34', 2, '127.0.0.1', '2010-06-19 12:04:34'),
(29, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::loadXML() [domdocument.loadxml]: Extra content at the end of the document in Entity, line: 2', 'warning', '2010-06-19 16:37:47', 2, '127.0.0.1', '2010-06-19 18:37:47'),
(30, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\PML.php:325)', 'warning', '2010-06-19 16:37:47', 2, '127.0.0.1', '2010-06-19 18:37:47'),
(31, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\PML.php:325)', 'warning', '2010-06-19 16:37:47', 2, '127.0.0.1', '2010-06-19 18:37:47'),
(32, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\PML.php:325)', 'warning', '2010-06-19 16:37:47', 2, '127.0.0.1', '2010-06-19 18:37:47'),
(33, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::loadXML() [domdocument.loadxml]: Extra content at the end of the document in Entity, line: 2', 'warning', '2010-06-19 16:38:02', 2, '127.0.0.1', '2010-06-19 18:38:02'),
(34, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::loadXML() [domdocument.loadxml]: Extra content at the end of the document in Entity, line: 2', 'warning', '2010-06-19 16:39:00', 2, '127.0.0.1', '2010-06-19 18:39:00'),
(35, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 1: parser error : StartTag: invalid element name', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(36, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: ', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(37, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]:  ^', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(38, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 1: parser error : Extra content at the end of the document', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(39, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: ', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(40, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]:  ^', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(41, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Failed to parse the XML resource ''in_memory_buffer''.', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(42, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 16:41:00', 2, '127.0.0.1', '2010-06-19 18:41:00'),
(43, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''p'': Missing child element(s). Expected is ( a ).', 'warning', '2010-06-19 17:06:55', 2, '127.0.0.1', '2010-06-19 19:06:55'),
(44, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''p'': This element is not expected.', 'warning', '2010-06-19 17:06:55', 2, '127.0.0.1', '2010-06-19 19:06:55'),
(45, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : error parsing attribute name', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(46, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(47, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(48, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : attributes construct error', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(49, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(50, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(51, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : Couldn''t find end of Start Tag element line 35', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(52, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(53, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(54, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Failed to parse the XML resource ''in_memory_buffer''.', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(55, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:39:45', 2, '127.0.0.1', '2010-06-19 19:39:45'),
(56, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : error parsing attribute name', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(57, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(58, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(59, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : attributes construct error', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(60, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(61, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(62, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : Couldn''t find end of Start Tag element line 35', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(63, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(64, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(65, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Failed to parse the XML resource ''in_memory_buffer''.', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(66, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:40:17', 2, '127.0.0.1', '2010-06-19 19:40:17'),
(67, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : error parsing attribute name', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(68, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(69, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(70, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : attributes construct error', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(71, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(72, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(73, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : Couldn''t find end of Start Tag element line 35', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(74, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(75, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                      ^', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(76, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Failed to parse the XML resource ''in_memory_buffer''.', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(77, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:40:50', 2, '127.0.0.1', '2010-06-19 19:40:50'),
(78, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : error parsing attribute name', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(79, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(80, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                       ^', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(81, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : attributes construct error', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(82, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(83, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                       ^', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(84, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : Couldn''t find end of Start Tag element line 35', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(85, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(86, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                       ^', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(87, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Failed to parse the XML resource ''in_memory_buffer''.', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(88, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:40:59', 2, '127.0.0.1', '2010-06-19 19:40:59'),
(89, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : error parsing attribute name', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(90, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(91, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                       ^', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(92, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : attributes construct error', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(93, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(94, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                       ^', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(95, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Entity: line 35: parser error : Couldn''t find end of Start Tag element line 35', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(96, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(97, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: 	                                       ^', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(98, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Failed to parse the XML resource ''in_memory_buffer''.', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(99, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:41:02', 2, '127.0.0.1', '2010-06-19 19:41:02'),
(100, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}choice'': The attribute ''minOccours'' is not allowed.', 'warning', '2010-06-19 17:41:15', 2, '127.0.0.1', '2010-06-19 19:41:15'),
(101, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}choice'': The attribute ''maxOccours'' is not allowed.', 'warning', '2010-06-19 17:41:15', 2, '127.0.0.1', '2010-06-19 19:41:15');
INSERT INTO `premanager_0_log` (`id`, `url`, `referer`, `userAgent`, `text`, `type`, `createTime`, `creatorID`, `creatorIP`, `timestamp`) VALUES
(102, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:41:15', 2, '127.0.0.1', '2010-06-19 19:41:15'),
(103, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:41:15', 2, '127.0.0.1', '2010-06-19 19:41:15'),
(104, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''type'': The value ''xsd:string'' of simple type ''xs:QName'' has no corresponding namespace declaration in scope.', 'warning', '2010-06-19 17:41:15', 2, '127.0.0.1', '2010-06-19 19:41:15'),
(105, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:41:15', 2, '127.0.0.1', '2010-06-19 19:41:15'),
(106, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}choice'': The attribute ''minOccours'' is not allowed.', 'warning', '2010-06-19 17:42:02', 2, '127.0.0.1', '2010-06-19 19:42:02'),
(107, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}choice'': The attribute ''maxOccours'' is not allowed.', 'warning', '2010-06-19 17:42:02', 2, '127.0.0.1', '2010-06-19 19:42:02'),
(108, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:42:02', 2, '127.0.0.1', '2010-06-19 19:42:02'),
(109, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:42:02', 2, '127.0.0.1', '2010-06-19 19:42:02'),
(110, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''type'': The value ''xsd:string'' of simple type ''xs:QName'' has no corresponding namespace declaration in scope.', 'warning', '2010-06-19 17:42:02', 2, '127.0.0.1', '2010-06-19 19:42:02'),
(111, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:42:02', 2, '127.0.0.1', '2010-06-19 19:42:02'),
(112, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}choice'': The attribute ''minOccours'' is not allowed.', 'warning', '2010-06-19 17:42:05', 2, '127.0.0.1', '2010-06-19 19:42:05'),
(113, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}choice'': The attribute ''maxOccours'' is not allowed.', 'warning', '2010-06-19 17:42:05', 2, '127.0.0.1', '2010-06-19 19:42:05'),
(114, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:42:05', 2, '127.0.0.1', '2010-06-19 19:42:05'),
(115, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:42:05', 2, '127.0.0.1', '2010-06-19 19:42:05'),
(116, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''type'': The value ''xsd:string'' of simple type ''xs:QName'' has no corresponding namespace declaration in scope.', 'warning', '2010-06-19 17:42:05', 2, '127.0.0.1', '2010-06-19 19:42:05'),
(117, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidateSource() [domdocument.schemavalidatesource]: Invalid Schema', 'warning', '2010-06-19 17:42:05', 2, '127.0.0.1', '2010-06-19 19:42:05'),
(118, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: I/O warning : failed to load external entity "file:///D:/Programmierung/webspace/own/includes/pml.xsd"', 'warning', '2010-06-19 17:43:45', 2, '127.0.0.1', '2010-06-19 19:43:45'),
(119, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Failed to locate the main schema resource at ''D:\\Programmierung\\webspace\\own\\includes\\pml.xsd''.', 'warning', '2010-06-19 17:43:45', 2, '127.0.0.1', '2010-06-19 19:43:45'),
(120, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 17:43:45', 2, '127.0.0.1', '2010-06-19 19:43:45'),
(121, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: I/O warning : failed to load external entity "file:///D:/Programmierung/webspace/own/includes/pml.xsd"', 'warning', '2010-06-19 17:44:01', 2, '127.0.0.1', '2010-06-19 19:44:01'),
(122, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Failed to locate the main schema resource at ''D:\\Programmierung\\webspace\\own\\includes\\pml.xsd''.', 'warning', '2010-06-19 17:44:01', 2, '127.0.0.1', '2010-06-19 19:44:01'),
(123, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 17:44:01', 2, '127.0.0.1', '2010-06-19 19:44:01'),
(124, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: file:///D:/Programmierung/webspace/own/rack/plugins/Premanager/core/../includes/pml.xsd:35: parser error : error parsing attribute name', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(125, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 	', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(126, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 	                                       ^', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(127, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: file:///D:/Programmierung/webspace/own/rack/plugins/Premanager/core/../includes/pml.xsd:35: parser error : attributes construct error', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(128, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 	', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(129, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 	                                       ^', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(130, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: file:///D:/Programmierung/webspace/own/rack/plugins/Premanager/core/../includes/pml.xsd:35: parser error : Couldn''t find end of Start Tag element line 35', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(131, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 	', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(132, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 	                                       ^', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(133, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Failed to parse the XML resource ''D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core/../includes/pml.xsd''.', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(134, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 17:46:33', 2, '127.0.0.1', '2010-06-19 19:46:33'),
(135, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:46:53', 2, '127.0.0.1', '2010-06-19 19:46:53'),
(136, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''minOccours'': Only the attributes ''minOccurs'', ''maxOccurs'' and ''id'' are allowed in addition to ''ref''.', 'warning', '2010-06-19 17:46:53', 2, '127.0.0.1', '2010-06-19 19:46:53'),
(137, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''type'': The value ''xsd:string'' of simple type ''xs:QName'' has no corresponding namespace declaration in scope.', 'warning', '2010-06-19 17:46:53', 2, '127.0.0.1', '2010-06-19 19:46:53'),
(138, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 17:46:53', 2, '127.0.0.1', '2010-06-19 19:46:53'),
(139, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''type'': The value ''xsd:string'' of simple type ''xs:QName'' has no corresponding namespace declaration in scope.', 'warning', '2010-06-19 17:47:40', 2, '127.0.0.1', '2010-06-19 19:47:40'),
(140, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 17:47:40', 2, '127.0.0.1', '2010-06-19 19:47:40'),
(141, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''ref'': The QName value ''ul'' does not resolve to a(n) element declaration.', 'warning', '2010-06-19 17:48:30', 2, '127.0.0.1', '2010-06-19 19:48:30'),
(142, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''ref'': The QName value ''ol'' does not resolve to a(n) element declaration.', 'warning', '2010-06-19 17:48:30', 2, '127.0.0.1', '2010-06-19 19:48:30'),
(143, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 17:48:30', 2, '127.0.0.1', '2010-06-19 19:48:30'),
(144, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''p'': Missing child element(s). Expected is ( a ).', 'warning', '2010-06-19 17:49:02', 2, '127.0.0.1', '2010-06-19 19:49:02'),
(145, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''p'': Missing child element(s). Expected is ( a ).', 'warning', '2010-06-19 17:49:26', 2, '127.0.0.1', '2010-06-19 19:49:26'),
(146, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::loadXML() [domdocument.loadxml]: expected ''>'' in Entity, line: 2', 'warning', '2010-06-19 17:50:06', 2, '127.0.0.1', '2010-06-19 19:50:06'),
(147, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'': This element is not expected.', 'warning', '2010-06-19 17:51:00', 2, '127.0.0.1', '2010-06-19 19:51:00'),
(148, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''pml'': Character content other than whitespace is not allowed because the content type is ''element-only''.', 'warning', '2010-06-19 17:51:08', 2, '127.0.0.1', '2010-06-19 19:51:08'),
(149, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''p'': This element is not expected. Expected is ( a ).', 'warning', '2010-06-19 17:51:24', 2, '127.0.0.1', '2010-06-19 19:51:24'),
(150, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'': This element is not expected.', 'warning', '2010-06-19 17:52:02', 2, '127.0.0.1', '2010-06-19 19:52:02'),
(151, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''li'': Character content other than whitespace is not allowed because the content type is ''element-only''.', 'warning', '2010-06-19 17:52:30', 2, '127.0.0.1', '2010-06-19 19:52:30'),
(152, 'http://de.rack.localhost/blog?add', 'http://de.rack.localhost/blog?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::loadXML() [domdocument.loadxml]: Opening and ending tag mismatch: h1 line 3 and h in Entity, line: 3', 'warning', '2010-06-19 17:52:48', 2, '127.0.0.1', '2010-06-19 19:52:48'),
(153, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''br'': This element is not expected. Expected is ( a ).', 'warning', '2010-06-19 18:05:37', 2, '127.0.0.1', '2010-06-19 20:05:37'),
(154, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:13:07', 2, '127.0.0.1', '2010-06-19 20:13:07'),
(155, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: internal: no atom generated', 'warning', '2010-06-19 18:13:23', 2, '127.0.0.1', '2010-06-19 20:13:23'),
(156, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: genrate transition: atom == NULL', 'warning', '2010-06-19 18:13:23', 2, '127.0.0.1', '2010-06-19 20:13:23'),
(157, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: xmlFAParseRegExp: extra characters', 'warning', '2010-06-19 18:13:23', 2, '127.0.0.1', '2010-06-19 20:13:23'),
(158, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}pattern'': The value ''^(http|https|ftp)\\:\\/\\/(([0-9a-z][0-9a-z-]*[0-9a-z]\\.)+([a-z]{2,6})(\\/(.*))?)$'' of the facet ''pattern'' is not a valid regular expression.', 'warning', '2010-06-19 18:13:23', 2, '127.0.0.1', '2010-06-19 20:13:23'),
(159, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:13:23', 2, '127.0.0.1', '2010-06-19 20:13:23'),
(160, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: internal: no atom generated', 'warning', '2010-06-19 18:14:02', 2, '127.0.0.1', '2010-06-19 20:14:02'),
(161, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: genrate transition: atom == NULL', 'warning', '2010-06-19 18:14:02', 2, '127.0.0.1', '2010-06-19 20:14:02'),
(162, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: xmlFAParseRegExp: extra characters', 'warning', '2010-06-19 18:14:02', 2, '127.0.0.1', '2010-06-19 20:14:02'),
(163, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}pattern'': The value ''^(http|https|ftp)\\://(([0-9a-z][0-9a-z-]*[0-9a-z]\\.)+([a-z]{2,6})(/(.*))?)$'' of the facet ''pattern'' is not a valid regular expression.', 'warning', '2010-06-19 18:14:02', 2, '127.0.0.1', '2010-06-19 20:14:02'),
(164, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:14:02', 2, '127.0.0.1', '2010-06-19 20:14:02'),
(165, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: internal: no atom generated', 'warning', '2010-06-19 18:14:55', 2, '127.0.0.1', '2010-06-19 20:14:55'),
(166, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: genrate transition: atom == NULL', 'warning', '2010-06-19 18:14:55', 2, '127.0.0.1', '2010-06-19 20:14:55'),
(167, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: failed to compile: xmlFAParseRegExp: extra characters', 'warning', '2010-06-19 18:14:55', 2, '127.0.0.1', '2010-06-19 20:14:55'),
(168, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}pattern'': The value ''/^(http|https|ftp)\\:\\/\\/(([0-9a-z][0-9a-z-]*[0-9a-z]\\.)+([a-z]{2,6})(/(.*))?)$/'' of the facet ''pattern'' is not a valid regular expression.', 'warning', '2010-06-19 18:14:55', 2, '127.0.0.1', '2010-06-19 20:14:55'),
(169, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:14:55', 2, '127.0.0.1', '2010-06-19 20:14:55'),
(170, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''br'': This element is not expected. Expected is ( a ).', 'warning', '2010-06-19 18:16:04', 2, '127.0.0.1', '2010-06-19 20:16:04'),
(171, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''{http://www.w3.org/2001/XMLSchema}element'', attribute ''ref'': The QName value ''br'' does not resolve to a(n) element declaration.', 'warning', '2010-06-19 18:18:08', 2, '127.0.0.1', '2010-06-19 20:18:08'),
(172, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:18:08', 2, '127.0.0.1', '2010-06-19 20:18:08'),
(173, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:18:33', 2, '127.0.0.1', '2010-06-19 20:18:33'),
(174, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 		', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(175, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 		                              ^', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(176, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: file:///D:/Programmierung/webspace/own/rack/plugins/Premanager/core/../includes/pml.xsd:58: parser error : attributes construct error', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(177, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 		', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(178, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 		                              ^', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(179, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: file:///D:/Programmierung/webspace/own/rack/plugins/Premanager/core/../includes/pml.xsd:58: parser error : Couldn''t find end of Start Tag attribute line 58', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(180, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 		', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(181, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: 		                              ^', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(182, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Failed to parse the XML resource ''D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core/../includes/pml.xsd''.', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(183, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:20:45', 2, '127.0.0.1', '2010-06-19 20:20:45'),
(184, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Invalid Schema', 'warning', '2010-06-19 18:21:11', 2, '127.0.0.1', '2010-06-19 20:21:11'),
(185, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'', attribute ''href'': ''http://www.juvenile-studios.de'' is not a valid value of the atomic type ''url''.', 'warning', '2010-06-19 18:22:18', 2, '127.0.0.1', '2010-06-19 20:22:18'),
(186, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'', attribute ''href'': ''http://www.juvenile-studios.de'' is not a valid value of the atomic type ''url''.', 'warning', '2010-06-19 18:22:51', 2, '127.0.0.1', '2010-06-19 20:22:51'),
(187, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'', attribute ''href'': ''http://www.juvenile-studios.de'' is not a valid value of the atomic type ''url''.', 'warning', '2010-06-19 18:23:07', 2, '127.0.0.1', '2010-06-19 20:23:07'),
(188, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'', attribute ''href'': ''http://www.juvenile-studios.de'' is not a valid value of the atomic type ''url''.', 'warning', '2010-06-19 18:23:30', 2, '127.0.0.1', '2010-06-19 20:23:30'),
(189, 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'http://de.rack.localhost/blog/der-erste-wysiwyg-artikel?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''a'', attribute ''href'': ''http://-www.juvenile-studios.de'' is not a valid value of the atomic type ''url''.', 'warning', '2010-06-19 18:24:09', 2, '127.0.0.1', '2010-06-19 20:24:09'),
(190, 'http://de.rack.localhost/blog/ein-test?edit&rev=2', 'http://de.rack.localhost/blog/ein-test?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''p'': This element is not expected. Expected is one of ( a, br ).', 'warning', '2010-06-19 18:25:00', 2, '127.0.0.1', '2010-06-19 20:25:00'),
(191, 'http://de.rack.localhost/blog/ein-test?edit&rev=2', 'http://de.rack.localhost/blog/ein-test?edit&rev=2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Warning: DOMDocument::schemaValidate() [domdocument.schemavalidate]: Element ''p'': This element is not expected. Expected is one of ( a, br ).', 'warning', '2010-06-19 18:25:04', 2, '127.0.0.1', '2010-06-19 20:25:04'),
(192, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Can''t connect to MySQL server on ''localhost'' (10061))', 'error', '2010-06-24 11:35:27', 0, '127.0.0.1', '2010-06-24 13:35:27'),
(193, 'http://de.rack.localhost/mein-konto/avatar?user', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Strings::get(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ChangeAvatar.php on line 23 and defined', 'warning', '2010-06-26 19:40:30', 2, '127.0.0.1', '2010-06-26 21:40:30'),
(194, 'http://de.rack.localhost/mein-konto/avatar?user', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Accessed string translation does not exist: userDoesNotExist.', 'error', '2010-06-26 19:40:30', 2, '127.0.0.1', '2010-06-26 21:40:30'),
(195, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Could not select UTC+0 timezone (Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '':00'' at line 1)', 'error', '2010-06-27 11:34:19', 0, '127.0.0.1', '2010-06-27 13:34:40'),
(196, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Could not select UTC+0 timezone (Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '':00'' at line 1)', 'error', '2010-06-27 11:34:23', 0, '127.0.0.1', '2010-06-27 13:34:40'),
(197, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Could not select UTC+0 timezone (Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '':30'' at line 1)', 'error', '2010-06-27 11:34:33', 0, '127.0.0.1', '2010-06-27 13:34:40'),
(198, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Could not select UTC+0 timezone (Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '':30'' at line 1)', 'error', '2010-06-27 11:34:34', 0, '127.0.0.1', '2010-06-27 13:34:40'),
(199, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Could not select UTC+0 timezone (Mysql Error: Unknown or incorrect time zone: ''+0'')', 'error', '2010-06-27 11:35:08', 0, '127.0.0.1', '2010-06-27 13:34:51'),
(200, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Accessed string translation does not exist (String Name: Premanager.changeAvatarMessageOwnEmpty)', 'error', '2010-06-27 11:47:32', 2, '127.0.0.1', '2010-06-27 13:47:08'),
(201, 'http://de.rack.localhost/mein-konto/avatar?user=Gast', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Accessed string translation does not exist (String Name: Premanager.selectAvatarEmpty)', 'error', '2010-06-27 12:38:36', 2, '127.0.0.1', '2010-06-27 14:38:12'),
(202, 'http://de.rack.localhost/mein-konto/avatar', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Accessed string translation does not exist (String Name: Premanager.changeAvatarButton)', 'error', '2010-06-27 13:33:51', 2, '127.0.0.1', '2010-06-27 15:33:27'),
(203, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:04', 2, '127.0.0.1', '2010-06-27 19:53:40'),
(204, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 3 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:04', 2, '127.0.0.1', '2010-06-27 19:53:40'),
(205, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-27 17:54:04', 2, '127.0.0.1', '2010-06-27 19:53:40'),
(206, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:08', 2, '127.0.0.1', '2010-06-27 19:53:44'),
(207, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 3 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:08', 2, '127.0.0.1', '2010-06-27 19:53:44'),
(208, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-27 17:54:08', 2, '127.0.0.1', '2010-06-27 19:53:44'),
(209, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:11', 2, '127.0.0.1', '2010-06-27 19:53:47'),
(210, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 3 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:11', 2, '127.0.0.1', '2010-06-27 19:53:47'),
(211, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-27 17:54:11', 2, '127.0.0.1', '2010-06-27 19:53:47'),
(212, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:36', 2, '127.0.0.1', '2010-06-27 19:54:12'),
(213, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 3 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:54:36', 2, '127.0.0.1', '2010-06-27 19:54:12'),
(214, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-06-27 17:54:36', 2, '127.0.0.1', '2010-06-27 19:54:12'),
(215, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:55:05', 2, '127.0.0.1', '2010-06-27 19:54:41');
INSERT INTO `premanager_0_log` (`id`, `url`, `referer`, `userAgent`, `text`, `type`, `createTime`, `creatorID`, `creatorIP`, `timestamp`) VALUES
(216, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 3 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:55:05', 2, '127.0.0.1', '2010-06-27 19:54:41'),
(217, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:55:09', 2, '127.0.0.1', '2010-06-27 19:54:45'),
(218, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 3 for Picture::loadFromFile(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\User.php on line 347 and defined', 'warning', '2010-06-27 17:55:09', 2, '127.0.0.1', '2010-06-27 19:54:45'),
(219, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:24:32', 2, '127.0.0.1', '2010-06-27 20:24:08'),
(220, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for User::dbDeleteAvatar(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ChangeAvatar.php on line 62 and defined', 'warning', '2010-06-27 18:25:29', 2, '127.0.0.1', '2010-06-27 20:25:05'),
(221, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:25:46', 2, '127.0.0.1', '2010-06-27 20:25:22'),
(222, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:25:52', 2, '127.0.0.1', '2010-06-27 20:25:28'),
(223, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:25:57', 2, '127.0.0.1', '2010-06-27 20:25:33'),
(224, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:26:00', 2, '127.0.0.1', '2010-06-27 20:25:36'),
(225, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:26:02', 2, '127.0.0.1', '2010-06-27 20:25:38'),
(226, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:26:03', 2, '127.0.0.1', '2010-06-27 20:25:39'),
(227, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:26:04', 2, '127.0.0.1', '2010-06-27 20:25:40'),
(228, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Cannot modify header information - headers already sent by (output started at D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\PNGPicture.php:14)', 'warning', '2010-06-27 18:26:10', 2, '127.0.0.1', '2010-06-27 20:25:46'),
(229, 'http://de.rack.localhost/mein-konto/avatar', 'http://de.rack.localhost/mein-konto/avatar', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for User::dbDeleteAvatar(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ChangeAvatar.php on line 62 and defined', 'warning', '2010-06-27 19:39:06', 2, '127.0.0.1', '2010-06-27 21:38:42'),
(230, 'http://de.rack.localhost/mein-konto/avatar?user=Jan', 'http://de.rack.localhost/mein-konto/avatar?user=Jan', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.4) Gecko/20100611 Firefox/3.6.4 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for User::dbDeleteAvatar(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ChangeAvatar.php on line 62 and defined', 'warning', '2010-06-27 20:03:06', 2, '127.0.0.1', '2010-06-27 22:02:42'),
(231, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''rack''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:08:11', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(232, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''rack''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:08:15', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(233, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''rack''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:09:23', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(234, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Unknown MySQL server host ''localhost_'' (11001))', 'error', '2010-06-28 19:09:36', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(235, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Unknown MySQL server host ''localhost_'' (11001))', 'error', '2010-06-28 19:09:38', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(236, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''rack''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:09:52', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(237, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Can''t connect to MySQL server on ''www.google.com'' (10107))', 'error', '2010-06-28 19:10:03', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(238, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''ODBC''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:10:25', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(239, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''root''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:10:31', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(240, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''ODBC''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:15:58', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(241, '', '', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''root''@''localhost'' (using password: YES))', 'error', '2010-06-28 19:16:10', 0, '127.0.0.1', '2010-06-28 21:15:53'),
(242, '', 'http://de.rack.localhost/blog', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''ODBC''@''localhost'' (using password: NO))', 'error', '2010-06-28 19:16:47', 0, '127.0.0.1', '2010-06-28 21:16:29'),
(243, '', 'http://de.rack.localhost/blog', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Access denied for user ''ODBC''@''localhost'' (using password: NO))', 'error', '2010-06-28 19:17:21', 0, '127.0.0.1', '2010-06-28 21:17:50'),
(244, 'http://de.rack.localhost/admin/struktur/_/blog', 'http://de.rack.localhost/admin/struktur/_?create-panel', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT plugin.name AS plugin, tree.class, FROM rack_premanager_trees AS tree INNER JOIN rack_premanager_plugins AS plugin ON plugin.pluginID = tree.pluginID WHERE tree.treeID = ''6''; Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''FROM rack_premanager_trees AS tree INNER JOIN rack_premanager_plugins AS plugin '' at line 1)', 'error', '2010-07-01 13:15:36', 2, '127.0.0.1', '2010-07-01 15:15:12'),
(245, 'http://de.rack.localhost/admin/struktur/_/admin?delete', 'http://de.rack.localhost/admin/struktur/_/admin', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 1 for Node::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\Node.php on line 673 and defined', 'warning', '2010-07-01 13:42:00', 2, '127.0.0.1', '2010-07-01 15:41:36'),
(246, 'http://de.rack.localhost/admin/struktur/_/admin?delete', 'http://de.rack.localhost/admin/struktur/_/admin', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-07-01 13:42:00', 2, '127.0.0.1', '2010-07-01 15:41:36'),
(247, 'http://de.rack.localhost/admin/struktur/_/admin?delete', 'http://de.rack.localhost/admin/struktur/_/admin', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Invalid argument supplied for foreach()', 'warning', '2010-07-01 13:42:18', 2, '127.0.0.1', '2010-07-01 15:41:54'),
(248, 'http://de.rack.localhost/admin/struktur/_/admin?delete', 'http://de.rack.localhost/admin/struktur/_/admin', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: mysql_free_result() expects parameter 1 to be resource, null given', 'warning', '2010-07-01 13:43:32', 2, '127.0.0.1', '2010-07-01 15:43:08'),
(249, 'http://de.rack.localhost/admin', 'http://de.rack.localhost/admin/struktur/_', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT node.nodeID, translation.name, translation.title, node.noAccessRestriction, node.treeID, node.hasTree FROM rack_premanager_nodes AS node LEFT JOIN rack_premanager_nodestranslation AS translation ON node.nodeID = translation.nodeID WHERE (CASE translation.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) = (SELECT MAX(CASE translationTemp.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) FROM rack_premanager_nodes AS translationTempItem LEFT JOIN rack_premanager_nodestranslation AS translationTemp ON translationTempItem.nodeID = translationTemp.nodeID WHERE node.nodeID = translationTempItem.nodeID ) AND  node.parentID = ''92'' AND node.nodeID != ''92'' GROUP BY node.nodeID ORDER BY translation.name ASC; Mysql Error: Unknown column ''node.hasTree'' in ''field list'')', 'error', '2010-07-01 13:53:18', 2, '127.0.0.1', '2010-07-01 15:52:54'),
(250, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:11:06', 2, '127.0.0.1', '2010-07-03 19:10:42'),
(251, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:11:06', 2, '127.0.0.1', '2010-07-03 19:10:42'),
(252, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:11:45', 2, '127.0.0.1', '2010-07-03 19:11:21'),
(253, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:11:45', 2, '127.0.0.1', '2010-07-03 19:11:21'),
(254, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:12:20', 2, '127.0.0.1', '2010-07-03 19:11:56'),
(255, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:12:20', 2, '127.0.0.1', '2010-07-03 19:11:56'),
(256, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:12:33', 2, '127.0.0.1', '2010-07-03 19:12:09'),
(257, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Premanager_ProjectNode::__construct(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\scripts\\ProjectsNode.php on line 51 and defined', 'warning', '2010-07-03 17:12:33', 2, '127.0.0.1', '2010-07-03 19:12:09'),
(258, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: mysql_real_escape_string() expects parameter 1 to be string, object given', 'warning', '2010-07-03 17:21:14', 2, '127.0.0.1', '2010-07-03 19:20:50'),
(259, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', '', 'error', '2010-07-03 17:21:14', 2, '127.0.0.1', '2010-07-03 19:20:50'),
(260, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:24:40', 2, '127.0.0.1', '2010-07-03 19:24:16'),
(261, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:18', 2, '127.0.0.1', '2010-07-03 19:25:54'),
(262, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:19', 2, '127.0.0.1', '2010-07-03 19:25:55'),
(263, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:20', 2, '127.0.0.1', '2010-07-03 19:25:56'),
(264, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:21', 2, '127.0.0.1', '2010-07-03 19:25:57'),
(265, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:21', 2, '127.0.0.1', '2010-07-03 19:25:57'),
(266, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:22', 2, '127.0.0.1', '2010-07-03 19:25:58'),
(267, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:27', 2, '127.0.0.1', '2010-07-03 19:26:03'),
(268, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:29', 2, '127.0.0.1', '2010-07-03 19:26:05'),
(269, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Event ''assignToOutput'' can not be raised because it was not added', 'error', '2010-07-03 17:26:29', 2, '127.0.0.1', '2010-07-03 19:26:05'),
(270, '', 'http://de.rack.localhost/admin/projekte/ajl%C3%B6ksfdsld%C3%B6jflsd%C3%B6/sdgljdslf', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Can''t connect to MySQL server on ''localhost'' (10061))', 'error', '2010-07-03 19:50:27', 0, '127.0.0.1', '2010-07-03 21:50:04'),
(271, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte/', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT project.projectID FROM rack_premanager_projects AS project LEFT JOIN rack_premanager_projectstranslation AS translation ON project.projectID = translation.projectID WHERE (CASE translation.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) = (SELECT MAX(CASE translationTemp.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) FROM rack_premanager_projects AS translationTempItem LEFT JOIN rack_premanager_projectstranslation AS translationTemp ON translationTempItem.projectID = translationTemp.projectID WHERE project.projectID = translationTempItem.projectID ) ORDER BY project.projectID != 0 DESC, translation.title ASC LIMIT , 5; Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' 5'' at line 1)', 'error', '2010-07-03 21:43:01', 2, '127.0.0.1', '2010-07-03 23:42:37'),
(272, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT project.projectID FROM rack_premanager_projects AS project LEFT JOIN rack_premanager_projectstranslation AS translation ON project.projectID = translation.projectID WHERE (CASE translation.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) = (SELECT MAX(CASE translationTemp.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) FROM rack_premanager_projects AS translationTempItem LEFT JOIN rack_premanager_projectstranslation AS translationTemp ON translationTempItem.projectID = translationTemp.projectID WHERE project.projectID = translationTempItem.projectID ) ORDER BY project.projectID == 0 DESC, translation.title ASC LIMIT 0, 20; Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''== 0 DESC, translation.title ASC LIMIT 0, 20'' at line 1)', 'error', '2010-07-03 21:45:52', 2, '127.0.0.1', '2010-07-03 23:45:28'),
(273, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT project.projectID FROM rack_premanager_projects AS project LEFT JOIN rack_premanager_projectstranslation AS translation ON project.projectID = translation.projectID WHERE (CASE translation.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) = (SELECT MAX(CASE translationTemp.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) FROM rack_premanager_projects AS translationTempItem LEFT JOIN rack_premanager_projectstranslation AS translationTemp ON translationTempItem.projectID = translationTemp.projectID WHERE project.projectID = translationTempItem.projectID ) ORDER BY project.projectID == 0 DESC, translation.title ASC LIMIT 0, 20; Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''== 0 DESC, translation.title ASC LIMIT 0, 20'' at line 1)', 'error', '2010-07-03 21:45:57', 2, '127.0.0.1', '2010-07-03 23:45:33'),
(274, 'http://de.rack.localhost/admin/projekte', 'http://de.rack.localhost/admin/projekte?edit', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT project.projectID FROM rack_premanager_projects AS project LEFT JOIN rack_premanager_projectstranslation AS translation ON project.projectID = translation.projectID WHERE (CASE translation.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) = (SELECT MAX(CASE translationTemp.languageID WHEN 1 THEN 2 WHEN 2 THEN 1 ELSE 0 END) FROM rack_premanager_projects AS translationTempItem LEFT JOIN rack_premanager_projectstranslation AS translationTemp ON translationTempItem.projectID = translationTemp.projectID WHERE project.projectID = translationTempItem.projectID ) ORDER BY project.projectID == 0 DESC, translation.title ASC LIMIT 0, 20; Mysql Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''== 0 DESC, translation.title ASC LIMIT 0, 20'' at line 1)', 'error', '2010-07-03 21:46:06', 2, '127.0.0.1', '2010-07-03 23:45:42'),
(275, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: require_once(Project.php) [function.require-once]: failed to open stream: No such file or directory', 'warning', '2010-07-03 22:15:16', 2, '127.0.0.1', '2010-07-04 00:14:52'),
(276, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: UPDATE rack_premanager_sessions SET lastRequestTime = NOW(), projectID = ''-1'' WHERE cookie = ''675a445069ec265db7cdb8190e0eeb0df8712ecb880db970bb3e9984a30dd932''; Mysql Error: Out of range value for column ''projectID'' at row 1)', 'error', '2010-07-03 22:16:15', 2, '127.0.0.1', '2010-07-04 00:15:51'),
(277, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: INSERT INTO rack_premanager_projectsname (projectID, name, languageID, inUse) VALUES (''-1'', ''b'', ''1'', ''1''); Mysql Error: Out of range value for column ''projectID'' at row 1)', 'error', '2010-07-03 22:17:10', 2, '127.0.0.1', '2010-07-04 00:16:46'),
(278, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: INSERT INTO rack_premanager_nodes (projectID, treeID, createTime, creatorID, creatorIP, editTime, editorID, editorIP, editTimes, parentID) VALUES (''19'', ''0'', NOW(), ''2'', ''127.0.0.1'', NOW(), ''2'', ''127.0.0.1'', ''0'', ''0''); Mysql Error: Field ''hasPanel'' doesn''t have a default value)', 'error', '2010-07-03 22:19:23', 2, '127.0.0.1', '2010-07-04 00:18:59'),
(279, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(280, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(281, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(282, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(283, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(284, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(285, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(286, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(287, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(288, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(289, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(290, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(291, 'http://de.rack.localhost/admin/projekte?add', 'http://de.rack.localhost/admin/projekte?add', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Warning: Missing argument 2 for Module::getAvailableName(), called in D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\core\\Project.php on line 187 and defined', 'warning', '2010-07-03 22:20:06', 2, '127.0.0.1', '2010-07-04 00:19:42'),
(292, 'http://de.rack.localhost/admin/struktur', 'http://de.rack.localhost/admin/projekte/homepage-ag', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Node tree class file not found (Class Name: Premanager_StructureNode)', 'error', '2010-07-04 10:24:02', 2, '127.0.0.1', '2010-07-04 12:23:38'),
(293, 'http://de.rack.localhost/', 'http://de.rack.localhost/mitglieder/anmeldung', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Errorneous query (Query: SELECT panelObject.objectID FROM rack_premanager_panelobjects AS panelObject WHERE panelObject.nodeID = ''79'' AND IS_NULL(panelObject.userID) ORDER BY panelObject.group ASC, panelObject.order ASC; Mysql Error: FUNCTION rack.IS_NULL does not exist)', 'error', '2010-07-04 11:11:59', 2, '127.0.0.1', '2010-07-04 13:11:35'),
(294, 'http://de.rack.localhost/', 'http://de.rack.localhost/mitglieder/anmeldung', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Widget class not found:  (Class Name: ViewonlineWidget)', 'error', '2010-07-04 11:12:33', 2, '127.0.0.1', '2010-07-04 13:12:09'),
(295, 'http://de.rack.localhost/', 'http://de.rack.localhost/mitglieder/anmeldung', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Widget class not found:  (Class Name: _ViewonlineWidget)', 'error', '2010-07-04 11:13:02', 2, '127.0.0.1', '2010-07-04 13:12:38'),
(296, 'http://de.rack.localhost/', 'http://de.rack.localhost/mitglieder/anmeldung', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Uncaught exception (Class: Dwoo_Compilation_Exception; Message: Compilation error at line 148 in &quot;file:D:\\Programmierung\\webspace\\own\\rack\\plugins/Premanager/templates/panelView.tpl&quot; : Invalid method name : $count(UserNode_pane; File: D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\thirdparty\\dwoo\\Dwoo\\Compiler.php:2233)', 'error', '2010-07-04 11:13:15', 2, '127.0.0.1', '2010-07-04 13:12:51'),
(297, '', 'http://de.rack.localhost/mitglieder/anmeldung', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Could not connect to database (Mysql Error: Can''t connect to MySQL server on ''localhost'' (10061))', 'error', '2010-07-04 14:55:49', 0, '127.0.0.1', '2010-07-04 16:55:31'),
(298, 'http://de.rack.localhost/', 'http://de.rack.localhost/', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 (.NET CLR 3.5.30729)', 'Uncaught exception (Class: Dwoo_Compilation_Exception; Message: Compilation error at line 164 in &quot;file:D:\\Programmierung\\webspace\\own\\rack\\plugins/Premanager/templates/panelView.tpl&quot; : Invalid method name : $count($UserNode_pan; File: D:\\Programmierung\\webspace\\own\\rack\\plugins\\Premanager\\thirdparty\\dwoo\\Dwoo\\Compiler.php:2233)', 'error', '2010-07-04 15:10:10', 2, '127.0.0.1', '2010-07-04 17:09:46');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_markuplanguages`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_markuplanguages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `premanager_0_markuplanguages`:
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_markuplanguages`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_markuplanguagestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_markuplanguagestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_markuplanguagestranslation`:
--   `id`
--       `premanager_0_markuplanguages` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_markuplanguagestranslation`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_nodegroup`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_nodegroup` (
  `nodeID` int(10) unsigned NOT NULL,
  `groupID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nodeID`,`groupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_nodegroup`:
--   `groupID`
--       `premanager_0_groups` -> `id`
--   `nodeID`
--       `premanager_0_nodes` -> `id`
--

--
-- Daten für Tabelle `premanager_0_nodegroup`
--

INSERT INTO `premanager_0_nodegroup` (`nodeID`, `groupID`, `timestamp`) VALUES
(8, 3, '2010-05-29 00:37:39'),
(8, 6, '2010-05-29 00:37:41'),
(30, 2, '2010-05-29 00:46:29'),
(92, 3, '2010-06-09 22:30:14'),
(94, 2, '2010-06-09 22:31:56');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_nodes`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` int(10) unsigned NOT NULL,
  `projectID` int(10) unsigned NOT NULL,
  `treeID` int(10) unsigned NOT NULL DEFAULT '0',
  `noAccessRestriction` tinyint(1) NOT NULL DEFAULT '1',
  `hasPanel` tinyint(1) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editTimes` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parentID` (`parentID`),
  KEY `projectID` (`projectID`),
  KEY `treeID` (`treeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=112 ;

--
-- RELATIONEN DER TABELLE `premanager_0_nodes`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--   `parentID`
--       `premanager_0_nodes` -> `id`
--   `projectID`
--       `premanager_0_projects` -> `id`
--   `treeID`
--       `premanager_0_trees` -> `id`
--

--
-- Daten für Tabelle `premanager_0_nodes`
--

INSERT INTO `premanager_0_nodes` (`id`, `parentID`, `projectID`, `treeID`, `noAccessRestriction`, `hasPanel`, `createTime`, `editTime`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `editTimes`, `timestamp`) VALUES
(90, 94, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:22', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(89, 93, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:15', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(88, 92, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:27', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(97, 108, 17, 15, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:25', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 21:43:55'),
(83, 93, 0, 4, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:28:58', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-06-09 22:30:55'),
(82, 93, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:43', 2, '127.0.0.1', 2, '127.0.0.1', 2, '2010-10-06 19:24:39'),
(81, 93, 0, 15, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:28:51', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 21:44:31'),
(80, 93, 0, 1, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:34', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-06-09 22:31:05'),
(79, 0, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:21:34', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-09-18 00:52:12'),
(84, 93, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:05', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(92, 79, 0, 0, 0, 0, '2010-06-09 20:30:08', '2010-06-09 20:30:08', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-06-09 22:30:12'),
(93, 79, 0, 0, 1, 0, '2010-06-09 20:30:44', '2010-06-09 20:30:44', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-07-01 15:50:04'),
(94, 79, 0, 0, 0, 0, '2010-06-09 20:31:46', '2010-06-09 20:31:46', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-06-09 22:31:54'),
(95, 0, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-09 20:34:29', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-06-09 22:34:29'),
(96, 108, 17, 1, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:51', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-06-12 00:24:51'),
(110, 95, 17, 0, 1, 0, '2010-06-11 22:21:55', '2010-06-11 22:21:55', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-06-12 00:21:55'),
(109, 95, 17, 0, 1, 0, '2010-06-11 22:21:41', '2010-06-11 22:21:41', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-06-12 00:21:41'),
(108, 95, 17, 0, 1, 0, '2010-06-11 22:21:32', '2010-06-11 22:21:32', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-06-12 00:21:32'),
(107, 110, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:06', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(106, 109, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:23:56', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(105, 108, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:46', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(104, 110, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:14', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(100, 108, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:38', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(99, 108, 17, 4, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:31', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-06-12 00:24:31'),
(98, 108, 17, 0, 1, 0, '2010-06-09 20:34:29', '2010-06-11 22:24:57', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(91, 92, 0, 0, 1, 0, '2010-06-09 20:21:34', '2010-06-09 20:29:10', 2, '127.0.0.1', 2, '127.0.0.1', 1, '2010-10-06 19:24:39'),
(111, 94, 0, 0, 1, 0, '2010-06-26 19:07:54', '2010-06-26 19:07:54', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-10-06 19:24:39');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_nodesname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_nodesname` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=303 ;

--
-- RELATIONEN DER TABELLE `premanager_0_nodesname`:
--   `id`
--       `premanager_0_nodes` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_nodesname`
--

INSERT INTO `premanager_0_nodesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(289, 96, 'base-users', 1, 0, '2010-06-12 00:24:51'),
(264, 94, 'mein-konto', 1, 1, '2010-06-09 22:31:46'),
(256, 93, 'mitglieder', 1, 1, '2010-06-09 22:30:44'),
(282, 97, 'base-groups', 1, 0, '2010-06-12 00:24:25'),
(290, 98, 'base-viewonline', 1, 0, '2010-06-12 00:24:57'),
(257, 83, 'anmeldung', 1, 1, '2010-06-09 22:30:55'),
(260, 84, 'passwort-vergessen', 1, 1, '2010-06-09 22:31:17'),
(254, 88, 'struktur', 1, 1, '2010-06-09 22:30:27'),
(261, 89, 'registrierung', 1, 1, '2010-06-09 22:31:22'),
(265, 90, 'anmeldungsdaten', 1, 1, '2010-06-09 22:32:04'),
(255, 91, 'projekte', 1, 1, '2010-06-09 22:30:34'),
(283, 99, 'base-loginform', 1, 0, '2010-06-12 00:24:31'),
(259, 81, 'gruppen', 1, 1, '2010-06-09 22:31:11'),
(218, 62, '', 1, 1, '2010-06-09 22:05:54'),
(284, 100, 'base-passwordlost', 1, 0, '2010-06-12 00:24:38'),
(219, 67, 'base-users', 1, 1, '2010-06-09 22:09:41'),
(220, 68, 'base-groups', 1, 1, '2010-06-09 22:09:41'),
(221, 69, 'base-viewonline', 1, 1, '2010-06-09 22:09:41'),
(222, 70, 'base-loginform', 1, 1, '2010-06-09 22:09:41'),
(223, 71, 'base-passwordlost', 1, 1, '2010-06-09 22:09:41'),
(227, 75, 'base-structure', 1, 1, '2010-06-09 22:09:41'),
(228, 76, 'base-register', 1, 1, '2010-06-09 22:09:41'),
(229, 77, 'base-registrationdata', 1, 1, '2010-06-09 22:09:41'),
(230, 78, 'base-projects', 1, 1, '2010-06-09 22:09:41'),
(258, 80, 'benutzer', 1, 1, '2010-06-09 22:31:05'),
(262, 82, 'wer-ist-online', 1, 1, '2010-06-09 22:31:27'),
(253, 92, 'admin', 1, 1, '2010-06-09 22:30:08'),
(288, 104, 'base-structure', 1, 0, '2010-06-12 00:24:14'),
(285, 105, 'base-register', 1, 0, '2010-06-12 00:24:46'),
(286, 106, 'base-registrationdata', 1, 0, '2010-06-12 00:23:56'),
(287, 107, 'base-projects', 1, 0, '2010-06-12 00:24:06'),
(279, 108, 'mitglieder', 1, 1, '2010-06-12 00:21:32'),
(280, 109, 'mein-konto', 1, 1, '2010-06-12 00:21:41'),
(281, 110, 'admin', 1, 1, '2010-06-12 00:21:55'),
(293, 106, 'anmeldungsdaten', 1, 1, '2010-06-12 00:23:56'),
(294, 107, 'projekte', 1, 1, '2010-06-12 00:24:06'),
(295, 104, 'struktur', 1, 1, '2010-06-12 00:24:14'),
(296, 97, 'gruppen', 1, 1, '2010-06-12 00:24:25'),
(297, 99, 'anmeldung', 1, 1, '2010-06-12 00:24:31'),
(298, 100, 'passwort-vergessen', 1, 1, '2010-06-12 00:24:38'),
(299, 105, 'registrierugn', 1, 1, '2010-06-12 00:24:46'),
(300, 96, 'benutzer', 1, 1, '2010-06-12 00:24:51'),
(301, 98, 'wer-ist-online', 1, 1, '2010-06-12 00:24:57'),
(302, 111, 'avatar', 1, 1, '2010-06-26 21:07:54');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_nodestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
--

CREATE TABLE IF NOT EXISTS `premanager_0_nodestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_nodestranslation`:
--   `id`
--       `premanager_0_nodes` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_nodestranslation`
--

INSERT INTO `premanager_0_nodestranslation` (`id`, `languageID`, `name`, `title`, `timestamp`) VALUES
(100, 1, 'passwort-vergessen', 'Passwort vergessen', '2010-06-12 00:24:38'),
(99, 1, 'anmeldung', 'Anmeldung', '2010-06-12 00:24:31'),
(98, 1, 'wer-ist-online', 'Wer ist online?', '2010-06-12 00:24:57'),
(97, 1, 'gruppen', 'Gruppen', '2010-06-12 00:24:25'),
(93, 1, 'mitglieder', 'Mitglieder', '2010-06-09 22:30:44'),
(91, 1, 'projekte', 'Projekte', '2010-06-09 22:29:10'),
(89, 1, 'registrierung', 'Registrierung', '2010-06-09 22:29:15'),
(90, 1, 'anmeldungsdaten', 'Anmeldungsdaten', '2010-06-09 22:29:22'),
(92, 1, 'admin', 'Administration', '2010-06-09 22:30:08'),
(96, 1, 'benutzer', 'Benutzerliste', '2010-06-12 00:24:51'),
(13, 3, 'oublié-mot-de-passe', 'oublié mot de passe', '2010-04-21 22:19:17'),
(95, 1, '', 'Startseite', '2010-06-09 22:34:29'),
(88, 1, 'struktur', 'Struktur', '2010-06-09 22:29:27'),
(86, 1, 'info', 'Informationen', '2010-06-09 22:32:24'),
(94, 1, 'mein-konto', 'Mein Konto', '2010-06-09 22:31:46'),
(84, 1, 'passwort-vergessen', 'Passwort vergessen', '2010-06-09 22:29:05'),
(82, 1, 'wer-ist-online', 'Wer ist online?', '2010-06-09 22:29:43'),
(83, 1, 'anmeldung', 'Anmeldung', '2010-06-09 22:28:58'),
(81, 1, 'gruppen', 'Gruppen', '2010-06-09 22:28:51'),
(80, 1, 'benutzer', 'Benutzerliste', '2010-06-09 22:29:34'),
(79, 1, '', 'Startseite', '2010-06-09 22:21:34'),
(50, 1, '', 'Startseite', '2010-06-09 22:05:54'),
(51, 1, '0', 'Base.Users', '2010-06-09 22:05:54'),
(59, 1, '0', 'Base.Structure', '2010-06-09 22:05:54'),
(55, 1, '0', 'Base.PasswordLost', '2010-06-09 22:05:54'),
(54, 1, '0', 'Base.LoginForm', '2010-06-09 22:05:54'),
(53, 1, '0', 'Base.Viewonline', '2010-06-09 22:05:54'),
(52, 1, '0', 'Base.Groups', '2010-06-09 22:05:54'),
(60, 1, '0', 'Base.Register', '2010-06-09 22:05:54'),
(61, 1, '0', 'Base.RegistrationData', '2010-06-09 22:05:54'),
(62, 1, '0', 'Base.Projects', '2010-06-09 22:05:54'),
(63, 1, '', 'Startseite', '2010-06-09 22:07:49'),
(64, 1, '', 'Startseite', '2010-06-09 22:08:04'),
(65, 1, '', 'Startseite', '2010-06-09 22:08:31'),
(66, 1, '', 'Startseite', '2010-06-09 22:09:41'),
(67, 1, 'base-users', 'Base.Users', '2010-06-09 22:09:41'),
(68, 1, 'base-groups', 'Base.Groups', '2010-06-09 22:09:41'),
(69, 1, 'base-viewonline', 'Base.Viewonline', '2010-06-09 22:09:41'),
(70, 1, 'base-loginform', 'Base.LoginForm', '2010-06-09 22:09:41'),
(71, 1, 'base-passwordlost', 'Base.PasswordLost', '2010-06-09 22:09:41'),
(75, 1, 'base-structure', 'Base.Structure', '2010-06-09 22:09:41'),
(76, 1, 'base-register', 'Base.Register', '2010-06-09 22:09:41'),
(77, 1, 'base-registrationdata', 'Base.RegistrationData', '2010-06-09 22:09:41'),
(78, 1, 'base-projects', 'Base.Projects', '2010-06-09 22:09:41'),
(104, 1, 'struktur', 'Struktur', '2010-06-12 00:24:14'),
(105, 1, 'registrierugn', 'Registrierung', '2010-06-12 00:24:46'),
(106, 1, 'anmeldungsdaten', 'Anmeldungsdaten', '2010-06-12 00:23:56'),
(107, 1, 'projekte', 'Projekte', '2010-06-12 00:24:06'),
(108, 1, 'mitglieder', 'Mitglieder', '2010-06-12 00:21:32'),
(109, 1, 'mein-konto', 'Mein Konto', '2010-06-12 00:21:41'),
(110, 1, 'admin', 'Administration', '2010-06-12 00:21:55'),
(111, 1, 'avatar', 'Avatar', '2010-06-26 21:07:54');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_options`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 20:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_options` (
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
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL DEFAULT '0',
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

--
-- RELATIONEN DER TABELLE `premanager_0_options`:
--   `editorID`
--       `premanager_0_users` -> `id`
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_options`
--

INSERT INTO `premanager_0_options` (`id`, `pluginID`, `name`, `type`, `minValue`, `maxValue`, `defaultValue`, `globalValue`, `projectsCanOverwrite`, `usersCanOverwrite`, `projectMinValue`, `projectMaxValue`, `userMinValue`, `userMaxValue`, `editTime`, `editTimes`, `editorID`, `editorIP`, `timestamp`) VALUES
(1, 0, 'sessionLength', 'int', 0, NULL, '3600', '3600', b'0', b'0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '', '2010-10-07 21:07:32'),
(2, 0, 'cookiePrefix', 'string', NULL, NULL, 'premanager_', NULL, b'0', b'0', NULL, NULL, NULL, NULL, '2010-02-15 22:13:30', 0, 2, '127.0.0.1', '2010-10-07 21:07:32'),
(4, 0, 'viewonlineLength', 'int', 0, NULL, '300', '300', b'0', b'0', NULL, NULL, NULL, NULL, '2010-03-03 21:35:44', 0, 2, '127.0.0.1', '2010-10-07 21:07:32'),
(5, 0, 'itemsPerPage', 'int', 1, NULL, '20', '20', b'0', b'1', NULL, NULL, NULL, 100, '2010-03-06 13:59:28', 0, 2, '127.0.0.1', '2010-10-07 21:07:32'),
(6, 0, 'email', 'string', NULL, NULL, 'test@example.org', 'info@yogularm.de', b'0', b'0', NULL, NULL, NULL, NULL, '2010-05-07 19:20:20', 0, 2, '127.0.0.1', '2010-10-07 21:07:32'),
(7, 0, 'passwordLostPasswordExpirationTime', 'int', 0, NULL, '172800', '172800', b'0', b'0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '', '2010-10-07 21:07:32'),
(8, 0, 'avatarWidth', 'int', 1, NULL, '80', '80', b'0', b'0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '', '2010-10-07 21:07:32'),
(9, 0, 'avatarHeight', 'int', 1, NULL, '80', '80', b'0', b'0', NULL, NULL, NULL, NULL, '0000-00-00 00:00:00', 0, 0, '', '2010-10-07 21:07:32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_panelobjects`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_panelobjects` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- RELATIONEN DER TABELLE `premanager_0_panelobjects`:
--   `nodeID`
--       `premanager_0_nodes` -> `id`
--   `userID`
--       `premanager_0_users` -> `id`
--   `widgetID`
--       `premanager_0_widgets` -> `id`
--

--
-- Daten für Tabelle `premanager_0_panelobjects`
--

INSERT INTO `premanager_0_panelobjects` (`id`, `nodeID`, `userID`, `widgetID`, `group`, `order`, `isMinimized`, `timestamp`) VALUES
(1, 79, NULL, 1, 0, 0, 0, '2010-07-04 12:42:41'),
(2, 79, NULL, 2, 1, 0, 0, '2010-07-04 12:42:41');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_plugins`
--
-- Erzeugt am: 03. November 2010 um 12:51
-- Aktualisiert am: 03. November 2010 um 23:07
--

CREATE TABLE IF NOT EXISTS `premanager_0_plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `initializerClass` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `backendTreeClass` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `premanager_0_plugins`
--

INSERT INTO `premanager_0_plugins` (`id`, `name`, `initializerClass`, `backendTreeClass`, `timestamp`) VALUES
(0, 'Premanager', '', 'Premanager\\Pages\\Backend\\BackendPage', '2010-11-03 23:07:40'),
(2, 'Blog', '', '', '2010-02-17 21:29:49'),
(5, 'Creativity', '', '', '2010-03-31 22:24:55'),
(6, 'Wiki', '', '', '2010-04-01 15:50:51');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_projectoptions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_projectoptions` (
  `optionID` int(10) unsigned NOT NULL,
  `projectID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`optionID`,`projectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_projectoptions`:
--   `optionID`
--       `premanager_0_options` -> `id`
--   `projectID`
--       `premanager_0_projects` -> `id`
--

--
-- Daten für Tabelle `premanager_0_projectoptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_projects`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 20:42
--

CREATE TABLE IF NOT EXISTS `premanager_0_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=91 ;

--
-- RELATIONEN DER TABELLE `premanager_0_projects`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_0_projects`
--

INSERT INTO `premanager_0_projects` (`id`, `name`, `createTime`, `editTime`, `editTimes`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `timestamp`) VALUES
(17, 'homepage-ag', '2010-06-09 20:34:29', '2010-07-03 22:12:56', 5, 2, '127.0.0.1', 2, '127.0.0.1', '2010-07-04 00:12:32'),
(0, '', '2010-07-03 22:17:10', '2010-07-03 22:17:10', 0, 2, '127.0.0.1', 2, '127.0.0.1', '2010-10-07 21:30:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_projectsname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_projectsname` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=24 ;

--
-- RELATIONEN DER TABELLE `premanager_0_projectsname`:
--   `id`
--       `premanager_0_projectstranslation` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_projectsname`
--

INSERT INTO `premanager_0_projectsname` (`nameID`, `id`, `name`, `inUse`, `languageID`, `timestamp`) VALUES
(21, 17, 'zwei-gesichter', 0, 1, '2010-06-12 00:21:08'),
(22, 17, 'homepage-ag', 1, 1, '2010-07-04 00:12:32'),
(20, 0, '', 1, 1, '2010-06-09 22:27:46'),
(19, 16, 'juvenile-studios-3', 1, 1, '2010-06-09 22:21:34'),
(23, 17, 'homepage-ag2', 0, 1, '2010-07-04 00:12:32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_projectstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_projectstranslation` (
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
-- RELATIONEN DER TABELLE `premanager_0_projectstranslation`:
--   `id`
--       `premanager_0_projects` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_projectstranslation`
--

INSERT INTO `premanager_0_projectstranslation` (`id`, `languageID`, `title`, `subTitle`, `author`, `copyright`, `description`, `keywords`, `timestamp`) VALUES
(17, 1, 'Homepage-AG', '', 'Philipp da Silva, Paul Hartmann, Marc Jegliewski, Marco Kinkel, Jan Melcher, Philipp Schmitt', '© Philipp da Silva, Paul Hartmann, Marc Jegliewski, Marco Kinkel, Jan Melcher, Philipp Schmitt, 2009-2010', 'Die Homepage-AG kümmert sich um die offizielle Homepage des HCG Beilsteins und moderiert die Projektseite', 'Homepage, Internetseite, Design, Moderation', '2010-07-04 00:12:25'),
(0, 1, 'HCG-Projekte', '', 'Das HCG Beilstein', '© Herzog-Christoph-Gymnasium Beilstein', 'Eine Plattform, auf der Arbeitsgemeinschaften und andere Projekte des Herzog-Christoph-Gymnasiums Beilstein präsentiert und verwaltet werden', 'Beilstein, Gymnasium, HCG, AG, Arbeitsgemeinschaft, Projekt', '2010-06-10 21:37:11');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_rights`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 20:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_rights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=18 ;

--
-- RELATIONEN DER TABELLE `premanager_0_rights`:
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_rights`
--

INSERT INTO `premanager_0_rights` (`id`, `pluginID`, `name`, `timestamp`) VALUES
(1, 0, 'manageUsers', '2010-10-07 21:04:44'),
(2, 0, 'createGroups', '2010-10-07 21:04:44'),
(3, 0, 'moderateGroups', '2010-10-07 21:04:44'),
(4, 0, 'lockGroups', '2010-10-07 21:04:44'),
(5, 0, 'structureAdmin', '2010-10-07 21:04:44'),
(6, 0, 'manageRights', '2010-10-07 21:04:44'),
(7, 0, 'setGroupPriority', '2010-10-07 21:04:44'),
(8, 0, 'register', '2010-10-07 21:04:44'),
(9, 0, 'registerWithoutEmail', '2010-10-07 21:04:44'),
(10, 0, 'changeUserName', '2010-10-07 21:04:44'),
(11, 0, 'manageProjects', '2010-10-07 21:04:44'),
(12, 2, 'createArticles', '2010-06-11 14:39:29'),
(13, 2, 'editArticles', '2010-06-11 15:52:07'),
(14, 2, 'deleteArticles', '2010-06-11 15:52:07'),
(15, 2, 'publishRevisions', '2010-06-11 15:52:07'),
(16, 0, 'changeOwnAvatar', '2010-10-07 21:04:44'),
(17, 0, 'changeForeignAvatars', '2010-10-07 21:04:44');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_rightstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
--

CREATE TABLE IF NOT EXISTS `premanager_0_rightstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_rightstranslation`:
--   `id`
--       `premanager_0_rights` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_rightstranslation`
--

INSERT INTO `premanager_0_rightstranslation` (`id`, `languageID`, `title`, `description`, `timestamp`) VALUES
(2, 1, 'Gruppen erstellen', 'Erlaubt es, neue Gruppen erstellen können (eigene Gruppen können bearbeitet und gelöscht werden).', '2010-04-26 18:19:37'),
(2, 2, 'Create groups', 'Allows to create new groups (own groups can be edited and deleted).', '2010-04-26 18:19:37'),
(4, 1, 'Gruppen sperren', 'Erlaubt es, Gruppengründern und -leitern die Rechte an ihren Gruppen zu entziehen und gesperrte Gruppen selbst bearbeiten und freigeben zu können.', '2010-04-29 16:44:57'),
(4, 2, 'Lock groups', 'Allows to revoke group founders'' and group leaders'' rights at their own groups and allows to edit and release locked groups.', '2010-04-29 16:17:41'),
(1, 1, 'Benutzer verwalten', 'Erlaubt es, Benutzer zu erstellen, zu bearbeiten und zu löschen', '2010-04-26 18:29:24'),
(1, 2, 'Manage users', 'Allows to create, edit and delete users', '2010-04-26 18:29:24'),
(3, 1, 'Gruppen moderieren', 'Bietet Gründerrechte an allen nicht fixierten (in Kombination mit "Gruppen fixieren" auch an fixierten) Gruppen.', '2010-04-26 18:32:59'),
(3, 2, 'Moderate groups', 'Give founder''s rights to all non-fixed groups (combined with "Fix groups" right, to all fixed groups, too).', '2010-04-26 18:32:59'),
(5, 1, 'Seitenstruktur bearbeiten', 'Erlaubt es, die Seitenstruktur zu bearbeiten.', '2010-04-26 18:33:53'),
(5, 2, 'Edit page structure', 'Allows to edit page structure.', '2010-04-26 18:33:53'),
(6, 1, 'Rechte verwalten', 'Erlaubt es, anderen Benutzern Rechte zu erteilen und zu entziehen.', '2010-04-26 21:07:13'),
(6, 2, 'Manage rights', 'Allows giving and taking rights.', '2010-04-26 21:07:45'),
(7, 1, 'Gruppenpriorität festlegen', 'Erlaubt es, eigene Gruppen in das Titelsystem einzugliedern, das Titel und Farbe von Benutzern festlegt.', '2010-04-29 16:53:20'),
(7, 2, 'Set Group Priority', 'Allows to insert own groups into the title system that defines color and title of users.', '2010-04-29 16:53:20'),
(8, 1, 'Registrieren', 'Erlaubt es, ein neues Benutzerkonto zu erstellen, bei dem eine E-Mail-Adresse angegeben und bestätigt werden muss', '2010-05-08 18:39:47'),
(8, 2, 'Register', 'Allows creating a new account; e-mail address must be inputted and confirmed', '2010-05-08 18:39:47'),
(9, 1, 'Registrieren (ohne E-Mail)', 'Erlaubt es, ein Benutzerkonto zu erstellen, bei dem keine E-Mail-Adresse angegeben werden muss', '2010-05-08 18:41:01'),
(9, 2, 'Register (without e-mail)', 'Allows creating a new account; e-mail address must not be inputted', '2010-05-08 18:41:01'),
(10, 1, 'Benutzernamen ändern', 'Erlaubt es, den eigenen Benutzernamen nachträglich zu ändern', '2010-05-24 18:45:03'),
(11, 1, 'Projekte verwalten', 'Erlaubt es, Projekte zu erstellen, zu bearbeiten, zu löschen und bietet dieselben Rechte an allen Projekten, die Projektleiter über ihre eigenen Projekte haben', '2010-06-06 16:00:38'),
(12, 1, 'Blog-Artikel erstellen', 'Erlaubt es, neue Blog-Artikel zu erstellen, nicht aber, sie zu veröffentlichen', '2010-06-11 15:56:19'),
(14, 1, 'Blog-Artikel löschen', 'Erlaubt es, Blog-Artikel endgültig zu löschen', '2010-06-11 15:45:16'),
(13, 1, 'Blog-Artikel bearbeiten', 'Erlaubt es, den Inhalt von Blog-Artikeln zu bearbeiten, nicht aber, diese Veränderungen zu veröffentlichen', '2010-06-11 15:45:16'),
(15, 1, 'Blog-Artikel veröffentlichen', 'Erlaubt es, neue Blog-Artikel und Änderungen an existierenden Artikeln zu veröffentlichen, sowie ältere Versionen wiederherzustellen und Artikel zu verstecken', '2010-06-11 15:45:16'),
(16, 1, 'Eigenen Avatar auswählen', 'Erlaubt es, einen eigenen Avatar hochzuladen.', '2010-06-26 21:22:00'),
(17, 1, 'Avatare anderer Benutzer auswählen', 'Erlaubt es, den Avatar von Benutzern, die verwaltet werden können, zu ändern.', '2010-06-26 21:22:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_sessions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 23. Oktober 2010 um 23:28
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_sessions` (
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
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `archivedSessionID` (`userID`,`lastRequestTime`),
  KEY `hidden` (`hidden`),
  KEY `projectID` (`projectID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- RELATIONEN DER TABELLE `premanager_0_sessions`:
--   `projectID`
--       `premanager_0_projects` -> `id`
--   `userID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_0_sessions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_sidebar`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_sidebar` (
  `userID` int(11) NOT NULL,
  `widgetID` int(11) NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`,`widgetID`),
  KEY `order` (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_sidebar`:
--   `userID`
--       `premanager_0_users` -> `id`
--   `widgetID`
--       `premanager_0_widgets` -> `id`
--

--
-- Daten für Tabelle `premanager_0_sidebar`
--

INSERT INTO `premanager_0_sidebar` (`userID`, `widgetID`, `order`, `timestamp`) VALUES
(0, 1, 1, '2010-03-04 18:41:10'),
(0, 2, 0, '2010-03-04 18:41:13');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_strings`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 03. November 2010 um 13:33
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_strings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=565 ;

--
-- RELATIONEN DER TABELLE `premanager_0_strings`:
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_strings`
--

INSERT INTO `premanager_0_strings` (`id`, `pluginID`, `name`, `timestamp`) VALUES
(1, 2, 'articlesList', '2010-02-27 17:42:20'),
(182, 0, 'goToUpperPage', '2010-10-07 21:04:52'),
(3, 0, 'pageNotFoundMessage', '2010-10-07 21:04:52'),
(4, 0, 'pageNotFoundNoRefererMessage', '2010-10-07 21:04:52'),
(5, 0, 'pageNotFoundInternalRefererMessage', '2010-10-07 21:04:52'),
(6, 0, 'pageNotFoundExternalRefererMessage', '2010-10-07 21:04:52'),
(7, 0, 'goToHomepage', '2010-10-07 21:04:52'),
(8, 0, 'accessDenied', '2010-10-07 21:04:52'),
(9, 0, 'accessDeniedMessage', '2010-10-07 21:04:52'),
(10, 0, 'viewonline', '2010-10-07 21:04:52'),
(11, 0, 'viewonlineDetailLinkTitle', '2010-10-07 21:04:52'),
(12, 0, 'loginDetailLinkTitle', '2010-10-07 21:04:52'),
(13, 0, 'loginSidebarUserLabel', '2010-10-07 21:04:52'),
(14, 0, 'loginSidebarPasswordLabel', '2010-10-07 21:04:52'),
(15, 0, 'widgetLoginButton', '2010-10-07 21:04:52'),
(16, 0, 'loginSidebarTitle', '2010-10-07 21:04:52'),
(24, 0, 'loginButton', '2010-10-07 21:04:52'),
(18, 0, 'loggedInAs', '2010-10-07 21:04:52'),
(19, 0, 'widgetLogoutButton', '2010-10-07 21:04:52'),
(20, 0, 'widgetMyLabel', '2010-10-07 21:04:52'),
(21, 0, 'widgetMyProfileLabel', '2010-10-07 21:04:52'),
(23, 0, 'myLinkTitle', '2010-10-07 21:04:52'),
(25, 0, 'loginUserLabel', '2010-10-07 21:04:52'),
(26, 0, 'loginPasswordLabel', '2010-10-07 21:04:52'),
(27, 0, 'loginTitle', '2010-10-07 21:04:52'),
(28, 0, 'loginFailedTitle', '2010-10-07 21:04:52'),
(29, 0, 'loginFailedGlobalMessage', '2010-10-07 21:04:52'),
(30, 0, 'loginFailedMessage', '2010-10-07 21:04:52'),
(31, 0, 'loginFailedPasswordLostMessage', '2010-10-07 21:04:52'),
(32, 0, 'loginFailedPasswordLostLinkText', '2010-10-07 21:04:52'),
(33, 0, 'loginFailedRetryLogin', '2010-10-07 21:04:52'),
(34, 0, 'loginSuccessfulGlobalMessage', '2010-10-07 21:04:52'),
(35, 0, 'viewonlineEmpty', '2010-10-07 21:04:52'),
(36, 0, 'viewonlineMessage', '2010-10-07 21:04:52'),
(37, 0, 'viewonlineUser', '2010-10-07 21:04:52'),
(38, 0, 'viewonlineLastRequest', '2010-10-07 21:04:52'),
(39, 0, 'viewonlineLocation', '2010-10-07 21:04:52'),
(79, 0, 'yesterday', '2010-10-07 21:04:52'),
(41, 0, 'dateMonday', '2010-10-07 21:04:52'),
(42, 0, 'dateTuesday', '2010-10-07 21:04:52'),
(43, 0, 'dateWednesday', '2010-10-07 21:04:52'),
(44, 0, 'dateThursday', '2010-10-07 21:04:52'),
(45, 0, 'dateFriday', '2010-10-07 21:04:52'),
(46, 0, 'dateSaturday', '2010-10-07 21:04:52'),
(47, 0, 'dateSunday', '2010-10-07 21:04:52'),
(48, 0, 'dateMon', '2010-10-07 21:04:52'),
(49, 0, 'dateTue', '2010-10-07 21:04:52'),
(50, 0, 'dateWed', '2010-10-07 21:04:52'),
(51, 0, 'dateThu', '2010-10-07 21:04:52'),
(52, 0, 'dateFri', '2010-10-07 21:04:52'),
(53, 0, 'dateSat', '2010-10-07 21:04:52'),
(54, 0, 'dateSun', '2010-10-07 21:04:52'),
(55, 0, 'dateJanuary', '2010-10-07 21:04:52'),
(56, 0, 'dateFebruary', '2010-10-07 21:04:52'),
(57, 0, 'dateMarch', '2010-10-07 21:04:52'),
(58, 0, 'dateApril', '2010-10-07 21:04:52'),
(59, 0, 'dateMay', '2010-10-07 21:04:52'),
(60, 0, 'dateJune', '2010-10-07 21:04:52'),
(61, 0, 'dateJuly', '2010-10-07 21:04:52'),
(62, 0, 'dateAugust', '2010-10-07 21:04:52'),
(63, 0, 'dateSeptember', '2010-10-07 21:04:52'),
(64, 0, 'dateOctober', '2010-10-07 21:04:52'),
(65, 0, 'dateNovember', '2010-10-07 21:04:52'),
(66, 0, 'dateDecember', '2010-10-07 21:04:52'),
(67, 0, 'dateJan', '2010-10-07 21:04:52'),
(68, 0, 'dateFeb', '2010-10-07 21:04:52'),
(69, 0, 'dateMar', '2010-10-07 21:04:52'),
(70, 0, 'dateApr', '2010-10-07 21:04:52'),
(71, 0, 'dateMayShort', '2010-10-07 21:04:52'),
(72, 0, 'dateJun', '2010-10-07 21:04:52'),
(73, 0, 'dateJul', '2010-10-07 21:04:52'),
(74, 0, 'dateAug', '2010-10-07 21:04:52'),
(75, 0, 'dateSep', '2010-10-07 21:04:52'),
(76, 0, 'dateOct', '2010-10-07 21:04:52'),
(77, 0, 'dateNov', '2010-10-07 21:04:52'),
(78, 0, 'dateDec', '2010-10-07 21:04:52'),
(80, 0, 'today', '2010-10-07 21:04:52'),
(81, 0, 'tomorrow', '2010-10-07 21:04:52'),
(82, 0, 'viewonlineLastRequestMask', '2010-10-07 21:04:52'),
(130, 0, 'userListEmpty', '2010-10-07 21:04:52'),
(84, 0, 'dateSecondsAgoLong', '2010-10-07 21:04:52'),
(172, 0, 'editGroupDescription', '2010-10-07 21:04:52'),
(86, 0, 'dateMinutesAgoLong', '2010-10-07 21:04:52'),
(171, 0, 'editGroup', '2010-10-07 21:04:52'),
(88, 0, 'dateHoursAgoLong', '2010-10-07 21:04:52'),
(170, 0, 'addGroupDescription', '2010-10-07 21:04:52'),
(90, 0, 'dateDaysAgoLong', '2010-10-07 21:04:52'),
(169, 0, 'addGroup', '2010-10-07 21:04:52'),
(92, 0, 'dateMonthsAgoLong', '2010-10-07 21:04:52'),
(168, 0, 'deleteUserDescription', '2010-10-07 21:04:52'),
(94, 0, 'dateYearsAgoLong', '2010-10-07 21:04:52'),
(129, 0, 'userListMessage', '2010-10-07 21:04:52'),
(96, 0, 'dateSecondsAgoShort', '2010-10-07 21:04:52'),
(167, 0, 'editUserDescription', '2010-10-07 21:04:52'),
(98, 0, 'dateMinutesAgoShort', '2010-10-07 21:04:52'),
(166, 0, 'addUserDescription', '2010-10-07 21:04:52'),
(100, 0, 'dateHoursAgoShort', '2010-10-07 21:04:52'),
(165, 0, 'deleteUser', '2010-10-07 21:04:52'),
(102, 0, 'dateDaysAgoShort', '2010-10-07 21:04:52'),
(164, 0, 'editUser', '2010-10-07 21:04:52'),
(104, 0, 'dateMonthsAgoShort', '2010-10-07 21:04:52'),
(163, 0, 'addUser', '2010-10-07 21:04:52'),
(106, 0, 'dateYearsAgoShort', '2010-10-07 21:04:52'),
(128, 0, 'users', '2010-10-07 21:04:52'),
(127, 0, 'up', '2010-10-07 21:04:52'),
(126, 0, 'home', '2010-10-07 21:04:52'),
(125, 0, 'canonical', '2010-10-07 21:04:52'),
(124, 0, 'literalGap', '2010-10-07 21:04:52'),
(123, 0, 'pageBack', '2010-10-07 21:04:52'),
(122, 0, 'pageForward', '2010-10-07 21:04:52'),
(121, 0, 'pageX', '2010-10-07 21:04:52'),
(120, 0, 'avatarOf', '2010-10-07 21:04:52'),
(136, 0, 'literalNone', '2010-10-07 21:04:52'),
(119, 0, 'dateOneSecondAgoLong', '2010-10-07 21:04:52'),
(131, 0, 'userListName', '2010-10-07 21:04:52'),
(132, 0, 'userRegistrationTime', '2010-10-07 21:04:52'),
(133, 0, 'userLastLoginTime', '2010-10-07 21:04:52'),
(134, 0, 'userDoesNotExist', '2010-10-07 21:04:52'),
(135, 2, 'articleDoesNotExist', '2010-03-07 00:42:28'),
(137, 0, 'loginHidden', '2010-10-07 21:04:52'),
(138, 0, 'guest', '2010-10-07 21:04:52'),
(139, 0, 'xGuests', '2010-10-07 21:04:52'),
(142, 0, 'userName', '2010-10-07 21:04:52'),
(143, 0, 'userTitle', '2010-10-07 21:04:52'),
(144, 0, 'titleDivider', '2010-10-07 21:04:52'),
(145, 0, 'avatar', '2010-10-07 21:04:52'),
(149, 0, 'isGroupLeaderAppendix', '2010-10-07 21:04:52'),
(148, 0, 'userGroupList', '2010-10-07 21:04:52'),
(150, 0, 'groups', '2010-10-07 21:04:52'),
(151, 0, 'groupListEmpty', '2010-10-07 21:04:52'),
(152, 0, 'groupMemberCount', '2010-10-07 21:04:52'),
(153, 0, 'groupDoesNotExist', '2010-10-07 21:04:52'),
(154, 0, 'groupName', '2010-10-07 21:04:52'),
(155, 0, 'groupTitle', '2010-10-07 21:04:52'),
(156, 0, 'groupText', '2010-10-07 21:04:52'),
(157, 0, 'brackets', '2010-10-07 21:04:52'),
(158, 0, 'groupMemberList', '2010-10-07 21:04:52'),
(159, 0, 'pageXOfY', '2010-10-07 21:04:52'),
(160, 0, 'label', '2010-10-07 21:04:52'),
(161, 0, 'groupLeaderHeader', '2010-10-07 21:04:52'),
(162, 0, 'groupMemberHeader', '2010-10-07 21:04:52'),
(173, 0, 'deleteGroup', '2010-10-07 21:04:52'),
(174, 0, 'deleteGroupDescription', '2010-10-07 21:04:52'),
(175, 0, 'gotoGroups', '2010-10-07 21:04:52'),
(176, 0, 'gotoGroupsDescription', '2010-10-07 21:04:52'),
(177, 0, 'gotoUsers', '2010-10-07 21:04:52'),
(178, 0, 'gotoUsersDescription', '2010-10-07 21:04:52'),
(179, 0, 'defaultPage', '2010-10-07 21:04:52'),
(180, 0, 'pageNotFound', '2010-10-07 21:04:52'),
(183, 0, 'structureMessage', '2010-10-07 21:04:52'),
(184, 0, 'structureEmpty', '2010-10-07 21:04:52'),
(185, 0, 'structureNameColumn', '2010-10-07 21:04:52'),
(186, 0, 'structureTitleColumn', '2010-10-07 21:04:52'),
(187, 0, 'structureRootNodeName', '2010-10-07 21:04:52'),
(188, 0, 'editNode', '2010-10-07 21:04:52'),
(189, 0, 'deleteNode', '2010-10-07 21:04:52'),
(190, 0, 'editNodeDescription', '2010-10-07 21:04:52'),
(191, 0, 'deleteNodeDescription', '2010-10-07 21:04:52'),
(192, 0, 'moveNode', '2010-10-07 21:04:52'),
(193, 0, 'moveNodeDescription', '2010-10-07 21:04:52'),
(194, 0, 'addNode', '2010-10-07 21:04:52'),
(195, 0, 'addNodeDescription', '2010-10-07 21:04:52'),
(196, 0, 'deleteNodeMessage', '2010-10-07 21:04:52'),
(197, 0, 'confirmation', '2010-10-07 21:04:52'),
(198, 0, 'confirmationMessage', '2010-10-07 21:04:52'),
(199, 0, 'nodeNameLabel', '2010-10-07 21:04:52'),
(200, 0, 'nodeTitleLabel', '2010-10-07 21:04:52'),
(201, 0, 'nodeNameDescription', '2010-10-07 21:04:52'),
(202, 0, 'nodeTitleDescription', '2010-10-07 21:04:52'),
(203, 0, 'submitButton', '2010-10-07 21:04:52'),
(204, 0, 'addNodeButton', '2010-10-07 21:04:52'),
(205, 0, 'editNodeButton', '2010-10-07 21:04:52'),
(211, 0, 'deleteNodeTitle', '2010-10-07 21:04:52'),
(210, 0, 'moveNodeTitle', '2010-10-07 21:04:52'),
(208, 0, 'editNodeTitle', '2010-10-07 21:04:52'),
(209, 0, 'addNodeTitle', '2010-10-07 21:04:52'),
(213, 0, 'noNodeNameInputtedError', '2010-10-07 21:04:52'),
(214, 0, 'noNodeTitleInputtedError', '2010-10-07 21:04:52'),
(215, 0, 'nodeNameAlreadyExistsError', '2010-10-07 21:04:52'),
(216, 0, 'confirmationConfirmButton', '2010-10-07 21:04:52'),
(217, 0, 'confirmationCancelButton', '2010-10-07 21:04:52'),
(218, 0, 'structureTitle', '2010-10-07 21:04:52'),
(220, 0, 'moveNodeMessage', '2010-10-07 21:04:52'),
(221, 0, 'insertNodeHere', '2010-10-07 21:04:52'),
(222, 0, 'insertNodeHereDescription', '2010-10-07 21:04:52'),
(223, 0, 'moveTargetDoesNotExistError', '2010-10-07 21:04:52'),
(224, 0, 'moveTargetIsChildError', '2010-10-07 21:04:52'),
(225, 0, 'nodeNameAlreadyExistsInTargetError', '2010-10-07 21:04:52'),
(226, 0, 'userNameDescription', '2010-10-07 21:04:52'),
(227, 0, 'isBotLabel', '2010-10-07 21:04:52'),
(228, 0, 'isBotShortLabel', '2010-10-07 21:04:52'),
(229, 0, 'isBotDescription', '2010-10-07 21:04:52'),
(230, 0, 'botIdentifier', '2010-10-07 21:04:52'),
(231, 0, 'botIdentifierDescription', '2010-10-07 21:04:52'),
(232, 0, 'passwordLabel', '2010-10-07 21:04:52'),
(233, 0, 'passwordDescription', '2010-10-07 21:04:52'),
(234, 0, 'passwordConfirmationLabel', '2010-10-07 21:04:52'),
(235, 0, 'passwordConfirmationDescription', '2010-10-07 21:04:52'),
(236, 0, 'noNodeUserNameInputtedError', '2010-10-07 21:04:52'),
(237, 0, 'registrationEmailLabel', '2010-10-07 21:04:52'),
(238, 0, 'registrationEmailDescription', '2010-10-07 21:04:52'),
(239, 0, 'invalidEmailAddressError', '2010-10-07 21:04:52'),
(240, 0, 'noRegistrationEmailInputtedError', '2010-10-07 21:04:52'),
(241, 0, 'userNameAlreadyExistsError', '2010-10-07 21:04:52'),
(242, 0, 'noBotIdentifierInputtedError', '2010-10-07 21:04:52'),
(243, 0, 'invalidBotIdentifierInputtedError', '2010-10-07 21:04:52'),
(244, 0, 'noPasswordInputtedError', '2010-10-07 21:04:52'),
(245, 0, 'noPasswordConfirmationInputtedError', '2010-10-07 21:04:52'),
(246, 0, 'passwordConfirmationInvalidError', '2010-10-07 21:04:52'),
(247, 0, 'nameContainsSlashError', '2010-10-07 21:04:52'),
(248, 0, 'deleteUserMessage', '2010-10-07 21:04:52'),
(503, 0, 'invalidPMLInputtedError', '2010-10-07 21:04:52'),
(250, 0, 'groupNameDescription', '2010-10-07 21:04:52'),
(253, 0, 'groupColor', '2010-10-07 21:04:52'),
(252, 0, 'groupTitleDescription', '2010-10-07 21:04:52'),
(254, 0, 'groupColorDescription', '2010-10-07 21:04:52'),
(255, 0, 'groupTextDescription', '2010-10-07 21:04:52'),
(256, 0, 'groupAutoJoinLabel', '2010-10-07 21:04:52'),
(257, 0, 'groupAutoJoinShortLabel', '2010-10-07 21:04:52'),
(258, 0, 'deleteGroupMessage', '2010-10-07 21:04:52'),
(259, 0, 'noGroupNameInputtedError', '2010-10-07 21:04:52'),
(260, 0, 'groupNameAlreadyExistsError', '2010-10-07 21:04:52'),
(261, 0, 'noGroupTitleInputtedError', '2010-10-07 21:04:52'),
(262, 0, 'noGroupColorInputtedError', '2010-10-07 21:04:52'),
(263, 0, 'invalidGroupColorInputtedError', '2010-10-07 21:04:52'),
(264, 0, 'noGroupTextInputtedError', '2010-10-07 21:04:52'),
(265, 0, 'deleteGuestErrorMessage', '2010-10-07 21:04:52'),
(266, 0, 'error', '2010-10-07 21:04:52'),
(267, 0, 'goToGuestAccount', '2010-10-07 21:04:52'),
(268, 0, 'userJoinGroup', '2010-10-07 21:04:52'),
(269, 0, 'userJoinGroupDescription', '2010-10-07 21:04:52'),
(270, 0, 'userJoinGroupMessage', '2010-10-07 21:04:52'),
(271, 0, 'userJoinGroupEmptyMessage', '2010-10-07 21:04:52'),
(272, 0, 'groupDoesNotExistError', '2010-10-07 21:04:52'),
(273, 0, 'userJoinAlreadyMemberError', '2010-10-07 21:04:52'),
(274, 0, 'userLeaveGroup', '2010-10-07 21:04:52'),
(275, 0, 'userLeaveGroupDescription', '2010-10-07 21:04:52'),
(276, 0, 'goToUser', '2010-10-07 21:04:52'),
(277, 0, 'userMembershipMissingError', '2010-10-07 21:04:52'),
(278, 0, 'promoteUser', '2010-10-07 21:04:52'),
(279, 0, 'promoteUserDescription', '2010-10-07 21:04:52'),
(280, 0, 'demoteUser', '2010-10-07 21:04:52'),
(281, 0, 'demoteUserDescription', '2010-10-07 21:04:52'),
(282, 0, 'defaultUserTitle', '2010-10-07 21:04:52'),
(283, 0, 'groupPriority', '2010-10-07 21:04:52'),
(284, 0, 'groupPriorityDescription', '2010-10-07 21:04:52'),
(285, 0, 'invalidGroupPriorityInputtedError', '2010-10-07 21:04:52'),
(286, 0, 'editGroupRights', '2010-10-07 21:04:52'),
(287, 0, 'editGroupRightsDescription', '2010-10-07 21:04:52'),
(288, 0, 'viewUserRights', '2010-10-07 21:04:52'),
(289, 0, 'viewUserRightsDescription', '2010-10-07 21:04:52'),
(290, 0, 'viewUserRightsMessage', '2010-10-07 21:04:52'),
(291, 0, 'viewUserRightsEmptyMessage', '2010-10-07 21:04:52'),
(292, 0, 'userRightColumn', '2010-10-07 21:04:52'),
(293, 0, 'userRightSourceColumn', '2010-10-07 21:04:52'),
(294, 0, 'inputErrorMessage', '2010-10-07 21:04:52'),
(296, 0, 'lockGroup', '2010-10-07 21:04:52'),
(297, 0, 'lockGroupDescription', '2010-10-07 21:04:52'),
(298, 0, 'unlockGroup', '2010-10-07 21:04:52'),
(299, 0, 'unlockGroupDescription', '2010-10-07 21:04:52'),
(300, 0, 'lockGroupMessage', '2010-10-07 21:04:52'),
(301, 0, 'unlockGroupMessage', '2010-10-07 21:04:52'),
(302, 0, 'userJoinGroupAccessDeniedError', '2010-10-07 21:04:52'),
(303, 0, 'registerAccessDeniedMessage', '2010-10-07 21:04:52'),
(304, 0, 'registerMessage', '2010-10-07 21:04:52'),
(305, 0, 'registerWithoutEmailMessage', '2010-10-07 21:04:52'),
(306, 0, 'registerUserLabel', '2010-10-07 21:04:52'),
(307, 0, 'registerUserDescription', '2010-10-07 21:04:52'),
(308, 0, 'registerPasswordLabel', '2010-10-07 21:04:52'),
(309, 0, 'registerPasswordDescription', '2010-10-07 21:04:52'),
(310, 0, 'registerPasswordConfirmationLabel', '2010-10-07 21:04:52'),
(311, 0, 'registerPasswordConfirmationDescription', '2010-10-07 21:04:52'),
(312, 0, 'registerEmailLabel', '2010-10-07 21:04:52'),
(313, 0, 'registerEmailDescription', '2010-10-07 21:04:52'),
(314, 0, 'registerOptionalEmailDescription', '2010-10-07 21:04:52'),
(315, 0, 'registerEmailConfirmationLabel', '2010-10-07 21:04:52'),
(316, 0, 'registerEmailConfirmationDescription', '2010-10-07 21:04:52'),
(317, 0, 'registerOptionalEmailConfirmationDescription', '2010-10-07 21:04:52'),
(318, 0, 'registerButton', '2010-10-07 21:04:52'),
(319, 0, 'noUserNameInputtedError', '2010-10-07 21:04:52'),
(320, 0, 'noEmailConfirmationInputtedError', '2010-10-07 21:04:52'),
(321, 0, 'emailConfirmationInvalidError', '2010-10-07 21:04:52'),
(322, 0, 'userStatusLabel', '2010-10-07 21:04:52'),
(323, 0, 'userStatusDescription', '2010-10-07 21:04:52'),
(324, 0, 'userStatusEnabled', '2010-10-07 21:04:52'),
(325, 0, 'userStatusDisabled', '2010-10-07 21:04:52'),
(326, 0, 'userStatusWaitForEmail', '2010-10-07 21:04:52'),
(327, 0, 'userHasUnconfirmedEmailInfo', '2010-10-07 21:04:52'),
(328, 0, 'resetUnconfirmedEmailButton', '2010-10-07 21:04:52'),
(329, 0, 'confirmUnconfirmedEmailButton', '2010-10-07 21:04:52'),
(330, 0, 'userAccountActivationEmailMessage', '2010-10-07 21:04:52'),
(331, 0, 'userAccountActivationEmailPlainMessage', '2010-10-07 21:04:52'),
(332, 0, 'systemErrorMessage', '2010-10-07 21:04:52'),
(333, 0, 'userAccountActivationEmailFailedErrorMessage', '2010-10-07 21:04:52'),
(334, 0, 'userAccountActivationEmailSentMessage', '2010-10-07 21:04:52'),
(335, 0, 'userAccountWithEmailCreatedMessage', '2010-10-07 21:04:52'),
(336, 0, 'userAccountCreatedMessage', '2010-10-07 21:04:52'),
(337, 0, 'registerEmailConfirmationEmailFailedMessage', '2010-10-07 21:04:52'),
(338, 0, 'userEmailConfirmationEmailMessage', '2010-10-07 21:04:52'),
(339, 0, 'userEmailConfirmationEmailPlainMessage', '2010-10-07 21:04:52'),
(341, 0, 'emailAddressConfirmedMessage', '2010-10-07 21:04:52'),
(342, 0, 'emailAddressConfirmedWithWaitForEmailMessage', '2010-10-07 21:04:52'),
(343, 0, 'confirmEmailInvalidKeySpecifiedError', '2010-10-07 21:04:52'),
(344, 0, 'userMeJoinGroupEmptyMessage', '2010-10-07 21:04:52'),
(345, 0, 'passwordLostMessage', '2010-10-07 21:04:52'),
(346, 0, 'passwordLostUserLabel', '2010-10-07 21:04:52'),
(347, 0, 'passwordLostUserDescription', '2010-10-07 21:04:52'),
(348, 0, 'passwordLostEmailLabel', '2010-10-07 21:04:52'),
(349, 0, 'passwordLostEmailDescription', '2010-10-07 21:04:52'),
(350, 0, 'passwordLostEmailDoesNotMatchError', '2010-10-07 21:04:52'),
(351, 0, 'userEmailConfirmationOnAccountCreationEmailMessage', '2010-10-07 21:04:52'),
(352, 0, 'userEmailConfirmationOnAccountCreationEmailPlainMessage', '2010-10-07 21:04:52'),
(353, 0, 'userAccountActivationEmailTitle', '2010-10-07 21:04:52'),
(354, 0, 'userEmailConfirmationOnAccountCreationEmailTitle', '2010-10-07 21:04:52'),
(355, 0, 'userEmailConfirmationEmailTitle', '2010-10-07 21:04:52'),
(356, 0, 'passwordLostEmailMessage', '2010-10-07 21:04:52'),
(357, 0, 'passwordLostEmailPlainMessage', '2010-10-07 21:04:52'),
(358, 0, 'passwordLostEmailFailedErrorMessage', '2010-10-07 21:04:52'),
(359, 0, 'passwordLostSucceededMessage', '2010-10-07 21:04:52'),
(360, 0, 'secondaryPasswordUsedGlobalMessage', '2010-10-07 21:04:52'),
(361, 0, 'loginRegisterTip', '2010-10-07 21:04:52'),
(362, 0, 'loginRegisterTipLinkText', '2010-10-07 21:04:52'),
(363, 0, 'registerLoginTip', '2010-10-07 21:04:52'),
(364, 0, 'registerLoginTipLinkText', '2010-10-07 21:04:52'),
(365, 0, 'loginFailedWaitForEmailMessage', '2010-10-07 21:04:52'),
(366, 0, 'loginFailedAccountDisabledMessage', '2010-10-07 21:04:52'),
(367, 0, 'registrationDataNameLabel', '2010-10-07 21:04:52'),
(368, 0, 'registrationDataNameDescription', '2010-10-07 21:04:52'),
(369, 0, 'emailConfirmationDescription', '2010-10-07 21:04:52'),
(370, 0, 'changePasswordDescription', '2010-10-07 21:04:52'),
(371, 0, 'changePasswordConfirmationDescription', '2010-10-07 21:04:52'),
(372, 0, 'registrationDataNotLoggedInError', '2010-10-07 21:04:52'),
(373, 0, 'emailConfirmationEmailFailedMessage', '2010-10-07 21:04:52'),
(374, 0, 'registrationDataSavedMessage', '2010-10-07 21:04:52'),
(375, 0, 'passwordChangedMessage', '2010-10-07 21:04:52'),
(376, 0, 'unconfirmedEmailChangedMessage', '2010-10-07 21:04:52'),
(377, 0, 'goBack', '2010-10-07 21:04:52'),
(378, 0, 'nodeNeighbors', '2010-10-07 21:04:52'),
(379, 0, 'nodeNeighborsDescription', '2010-10-07 21:04:52'),
(380, 0, 'nodeNeighborsMessage', '2010-10-07 21:04:52'),
(381, 0, 'nodeNeighborsEmptyMessage', '2010-10-07 21:04:52'),
(382, 0, 'nodeFreeTreesEmptyMessage', '2010-10-07 21:04:52'),
(383, 0, 'nodeNeighborsListTitle', '2010-10-07 21:04:52'),
(384, 0, 'nodeFreeTreesListTitle', '2010-10-07 21:04:52'),
(385, 0, 'nodeNeighborsTitle', '2010-10-07 21:04:52'),
(386, 0, 'nodeNeighborMoveUp', '2010-10-07 21:04:52'),
(387, 0, 'nodeNeighborMoveUpDescription', '2010-10-07 21:04:52'),
(388, 0, 'nodeNeighborMoveDown', '2010-10-07 21:04:52'),
(389, 0, 'nodeNeighborMoveDownDescription', '2010-10-07 21:04:52'),
(390, 0, 'nodeNeighborMakePrimary', '2010-10-07 21:04:52'),
(391, 0, 'nodeNeighborMakePrimaryDescription', '2010-10-07 21:04:52'),
(392, 0, 'nodeNeighborMakePrimaryActiveDescription', '2010-10-07 21:04:52'),
(393, 0, 'nodeNeighborRemove', '2010-10-07 21:04:52'),
(394, 0, 'nodeNeighborRemoveDescription', '2010-10-07 21:04:52'),
(395, 0, 'nodeAddNeighborDescription', '2010-10-07 21:04:52'),
(396, 0, 'nodeNeighborMakePrimaryDisabledDescription', '2010-10-07 21:04:52'),
(397, 0, 'nodePermissions', '2010-10-07 21:04:52'),
(398, 0, 'nodePermissionsDescription', '2010-10-07 21:04:52'),
(399, 0, 'nodePermissionsTitle', '2010-10-07 21:04:52'),
(400, 0, 'nodePermissionsNoAccessRestrictionMessage', '2010-10-07 21:04:52'),
(401, 0, 'nodePermissionsAccessRestrictionMessage', '2010-10-07 21:04:52'),
(402, 0, 'nodeAccessRestrictionButton', '2010-10-07 21:04:52'),
(403, 0, 'nodeNoAccessRestrictionButton', '2010-10-07 21:04:52'),
(404, 0, 'nodePermissionsRemoveGroup', '2010-10-07 21:04:52'),
(405, 0, 'nodePermissionsRemoveGroupDescription', '2010-10-07 21:04:52'),
(406, 0, 'nodePermissionsAddGroupDescription', '2010-10-07 21:04:52'),
(407, 0, 'nodePermissionsGroupListTitle', '2010-10-07 21:04:52'),
(408, 0, 'nodePermissionsFreeGroupListTitle', '2010-10-07 21:04:52'),
(409, 0, 'nodePermissionsGroupListEmptyMessage', '2010-10-07 21:04:52'),
(410, 0, 'nodePermissionsFreeGroupListEmptyMessage', '2010-10-07 21:04:52'),
(411, 0, 'addProject', '2010-10-07 21:04:52'),
(412, 0, 'addProjectDescription', '2010-10-07 21:04:52'),
(413, 0, 'projectListEmptyMessage', '2010-10-07 21:04:52'),
(414, 0, 'editProject', '2010-10-07 21:04:52'),
(415, 0, 'editProjectDescription', '2010-10-07 21:04:52'),
(416, 0, 'deleteProject', '2010-10-07 21:04:52'),
(417, 0, 'deleteProjectDescription', '2010-10-07 21:04:52'),
(418, 0, 'projectIsOrganization', '2010-10-07 21:04:52'),
(419, 0, 'projectTitle', '2010-10-07 21:04:52'),
(420, 0, 'projectAuthor', '2010-10-07 21:04:52'),
(421, 0, 'projectDescription', '2010-10-07 21:04:52'),
(422, 0, 'projectKeywords', '2010-10-07 21:04:52'),
(423, 0, 'projectName', '2010-10-07 21:04:52'),
(424, 0, 'projectNameDescription', '2010-10-07 21:04:52'),
(425, 0, 'projectTitleDescription', '2010-10-07 21:04:52'),
(426, 0, 'projectSubTitle', '2010-10-07 21:04:52'),
(427, 0, 'projectSubTitleDescription', '2010-10-07 21:04:52'),
(428, 0, 'projectAuthorDescription', '2010-10-07 21:04:52'),
(429, 0, 'projectCopyright', '2010-10-07 21:04:52'),
(430, 0, 'projectCopyrightDescription', '2010-10-07 21:04:52'),
(431, 0, 'projectDescriptionDescription', '2010-10-07 21:04:52'),
(432, 0, 'projectKeywordsDescription', '2010-10-07 21:04:52'),
(433, 0, 'noProjectNameInputtedError', '2010-10-07 21:04:52'),
(434, 0, 'projectNameAlreadyExistsError', '2010-10-07 21:04:52'),
(435, 0, 'noProjectTitleInputtedError', '2010-10-07 21:04:52'),
(436, 0, 'noProjectAuthorInputtedError', '2010-10-07 21:04:52'),
(437, 0, 'noProjectDescriptionInputtedError', '2010-10-07 21:04:52'),
(438, 0, 'noProjectCopyrightInputtedError', '2010-10-07 21:04:52'),
(439, 0, 'projectNameInvalidError', '2010-10-07 21:04:52'),
(440, 0, 'deleteProjectMessage', '2010-10-07 21:04:52'),
(441, 0, 'gotoNodeDescription', '2010-10-07 21:04:52'),
(442, 0, 'gotoNode', '2010-10-07 21:04:52'),
(443, 0, 'deleteTreeNodeError', '2010-10-07 21:04:52'),
(446, 0, 'nodeContentTitle', '2010-10-07 21:04:52'),
(444, 0, 'moveTargetNotChangedError', '2010-10-07 21:04:52'),
(445, 0, 'moveIntoTreeNodeError', '2010-10-07 21:04:52'),
(447, 0, 'treeNodeDescription', '2010-10-07 21:04:52'),
(448, 0, 'commonNodeDescription', '2010-10-07 21:04:52'),
(449, 0, 'gotoProject', '2010-10-07 21:04:52'),
(450, 0, 'gotoProjectDescription', '2010-10-07 21:04:52'),
(451, 0, 'deleteOrganizationError', '2010-10-07 21:04:52'),
(452, 2, 'addArticle', '2010-06-11 16:06:23'),
(453, 2, 'addArticleDescription', '2010-06-11 16:12:52'),
(454, 0, 'previewButton', '2010-10-07 21:04:52'),
(455, 2, 'articleTitle', '2010-06-11 18:26:35'),
(456, 2, 'articleTitleDescription', '2010-06-11 18:26:35'),
(457, 2, 'articleText', '2010-06-11 18:24:11'),
(458, 2, 'articleTextDescription', '2010-06-11 18:24:11'),
(459, 2, 'noArticleTitleInputtedError', '2010-06-11 18:46:26'),
(460, 2, 'noArticleTextInputtedError', '2010-06-11 18:46:18'),
(461, 2, 'summaryLabel', '2010-06-11 18:57:58'),
(462, 2, 'summaryDescription', '2010-06-11 18:57:58'),
(463, 2, 'summaryCreatedDescription', '2010-06-11 19:01:02'),
(464, 2, 'autoSummaryArticleCreated', '2010-06-11 19:01:32'),
(465, 2, 'articleListEmptyMessage', '2010-06-11 19:19:19'),
(466, 2, 'articleRevisions', '2010-06-11 19:32:56'),
(467, 2, 'articleRevisionsDescription', '2010-06-11 19:32:56'),
(468, 2, 'editArticle', '2010-06-11 19:34:44'),
(469, 2, 'editArticleDescription', '2010-06-11 19:34:44'),
(470, 2, 'articleRevisionsEmptyMessage', '2010-06-11 20:03:57'),
(471, 2, 'articleRevisionsMessage', '2010-06-11 20:10:42'),
(472, 2, 'viewRevisionDescription', '2010-06-11 20:10:46'),
(473, 2, 'revisionColumn', '2010-06-11 20:17:53'),
(474, 2, 'revisionTimeColumn', '2010-06-11 20:17:53'),
(475, 2, 'revisionCreatorColumn', '2010-06-11 20:18:09'),
(476, 2, 'revisionSummaryColumn', '2010-06-11 20:18:09'),
(482, 2, 'revisionBlockNoRevisionsMessage', '2010-06-11 21:04:15'),
(479, 2, 'revisionBlockTitle', '2010-06-11 20:45:42'),
(480, 2, 'revisionBlockNoPublishedRevisionMessage', '2010-06-11 20:53:04'),
(481, 2, 'revisionBlockOldRevisionMessage', '2010-06-11 20:53:04'),
(483, 2, 'revisionBlockNoPublishedRevisionRevisionSpecifiedMessage', '2010-06-11 21:05:21'),
(484, 2, 'revisionBlockNewRevisionMessage', '2010-06-11 21:05:47'),
(485, 2, 'gotoPublishedRevision', '2010-06-11 21:06:30'),
(486, 2, 'specifiedRevision', '2010-06-11 21:09:06'),
(487, 2, 'publishedRevision', '2010-06-11 21:09:06'),
(488, 2, 'lastRevision', '2010-06-11 21:09:35'),
(489, 2, 'publishRevision', '2010-06-11 22:47:32'),
(490, 2, 'publishRevisionDescription', '2010-06-11 22:47:32'),
(491, 2, 'publishRevisionMessage', '2010-06-11 23:09:17'),
(492, 2, 'publishRevisionArticleHiddenMessage', '2010-06-11 23:11:37'),
(493, 2, 'revisionBlockPublishedRevisionMessage', '2010-06-11 23:17:33'),
(494, 2, 'editRevisionBlockTitle', '2010-06-11 23:35:36'),
(495, 2, 'hideArticle', '2010-06-11 23:57:43'),
(496, 2, 'hideArticleDescription', '2010-06-11 23:57:43'),
(497, 2, 'hideArticleMessage', '2010-06-12 00:02:01'),
(498, 2, 'publishRevisionActiveDescription', '2010-06-12 00:04:24'),
(499, 2, 'deleteArticle', '2010-06-12 13:34:09'),
(500, 2, 'deleteArticleDescription', '2010-06-12 13:34:09'),
(501, 2, 'deleteArticleMessage', '2010-06-12 13:52:22'),
(502, 2, 'preview', '2010-06-18 19:48:53'),
(504, 0, 'pmlTitle', '2010-10-07 21:04:52'),
(505, 0, 'gotoChangeOwnAvatar', '2010-10-07 21:04:52'),
(506, 0, 'canOnlyChangeOwnAvatar', '2010-10-07 21:04:52'),
(507, 0, 'changeAvatarMessageOwnExisting', '2010-10-07 21:04:52'),
(508, 0, 'changeAvatarMessageForeignExisting', '2010-10-07 21:04:52'),
(509, 0, 'changeAvatarMessageOwnEmpty', '2010-10-07 21:04:52'),
(510, 0, 'changeAvatarMessageForeignEmpty', '2010-10-07 21:04:52'),
(511, 0, 'currentAvatar', '2010-10-07 21:04:52'),
(512, 0, 'currentAvatarDescription', '2010-10-07 21:04:52'),
(513, 0, 'selectAvatarExisting', '2010-10-07 21:04:52'),
(514, 0, 'selectAvatarEmpty', '2010-10-07 21:04:52'),
(515, 0, 'selectAvatarExistingDescription', '2010-10-07 21:04:52'),
(516, 0, 'selectAvatarEmptyDescription', '2010-10-07 21:04:52'),
(517, 0, 'changeAvatarButtonsLabel', '2010-10-07 21:04:52'),
(518, 0, 'changeAvatarButton', '2010-10-07 21:04:52'),
(519, 0, 'deleteAvatarButton', '2010-10-07 21:04:52'),
(520, 0, 'changeAvatarNoFileSentError', '2010-10-07 21:04:52'),
(521, 0, 'pictureFileTypeNotSupportedError', '2010-10-07 21:04:52'),
(522, 0, 'changeAvatar', '2010-10-07 21:04:52'),
(523, 0, 'changeAvatarDescription', '2010-10-07 21:04:52'),
(524, 0, 'nodeCreatePanel', '2010-10-07 21:04:52'),
(525, 0, 'nodeRemovePanel', '2010-10-07 21:04:52'),
(526, 0, 'panelNodeDescription', '2010-10-07 21:04:52'),
(527, 0, 'nodeCreatePanelMessage', '2010-10-07 21:04:52'),
(528, 0, 'nodeRemovePanelMessage', '2010-10-07 21:04:52'),
(529, 0, 'dateInXSecondsLong', '2010-10-07 21:04:52'),
(530, 0, 'dateInXMinutesLong', '2010-10-07 21:04:52'),
(531, 0, 'dateInXHoursLong', '2010-10-07 21:04:52'),
(532, 0, 'dateInXDaysLong', '2010-10-07 21:04:52'),
(533, 0, 'dateInXMonthsLong', '2010-10-07 21:04:52'),
(534, 0, 'dateInXYearsLong', '2010-10-07 21:04:52'),
(535, 0, 'dateInXSecondsShort', '2010-10-07 21:04:52'),
(536, 0, 'dateInXMinutesShort', '2010-10-07 21:04:52'),
(537, 0, 'dateInXHoursShort', '2010-10-07 21:04:52'),
(538, 0, 'dateInXDaysShort', '2010-10-07 21:04:52'),
(539, 0, 'dateInXMonthsShort', '2010-10-07 21:04:52'),
(540, 0, 'dateInXYearsShort', '2010-10-07 21:04:52'),
(541, 0, 'dateSecondsLong', '2010-10-07 21:04:52'),
(542, 0, 'dateMinutesLong', '2010-10-07 21:04:52'),
(543, 0, 'dateHoursLong', '2010-10-07 21:04:52'),
(544, 0, 'dateDaysLong', '2010-10-07 21:04:52'),
(545, 0, 'dateMonthsLong', '2010-10-07 21:04:52'),
(546, 0, 'dateYearsLong', '2010-10-07 21:04:52'),
(547, 0, 'dateSecondsShort', '2010-10-07 21:04:52'),
(548, 0, 'dateMinutesShort', '2010-10-07 21:04:52'),
(549, 0, 'dateHoursShort', '2010-10-07 21:04:52'),
(550, 0, 'dateDaysShort', '2010-10-07 21:04:52'),
(551, 0, 'dateMonthsShort', '2010-10-07 21:04:52'),
(552, 0, 'dateYearsShort', '2010-10-07 21:04:52'),
(553, 0, 'loginAlreadyLoggedIn', '2010-10-07 21:04:52'),
(554, 0, 'logoutButton', '2010-10-07 21:04:52'),
(555, 0, 'backToReferer', '2010-10-07 21:04:52'),
(556, 0, 'loginSuccessful', '2010-10-07 21:04:52'),
(557, 0, 'logoutSuccessful', '2010-10-07 21:04:52'),
(558, 0, 'theLogin', '2010-10-07 21:04:52'),
(559, 0, 'theLogout', '2010-10-07 21:04:52'),
(560, 0, 'groupListMessage', '2010-10-07 21:04:52'),
(561, 0, 'groupListName', '2010-10-07 21:04:52'),
(562, 0, 'groupListMemberCount', '2010-10-07 21:04:52'),
(563, 0, 'groupMemberCountLabel', '2010-10-07 21:04:52'),
(564, 0, 'backendPageTitle', '2010-11-03 13:33:43');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_stringstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 03. November 2010 um 13:33
--

CREATE TABLE IF NOT EXISTS `premanager_0_stringstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_stringstranslation`:
--   `id`
--       `premanager_0_strings` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_stringstranslation`
--

INSERT INTO `premanager_0_stringstranslation` (`id`, `languageID`, `value`, `timestamp`) VALUES
(1, 1, 'Artikelübersicht', '2010-02-27 17:42:54'),
(1, 2, 'Articles', '2010-02-27 17:43:03'),
(8, 1, 'Zugriff verweigert', '2010-02-27 17:50:23'),
(8, 2, 'Access denied', '2010-02-27 17:50:38'),
(9, 1, 'Du bist nicht berechtigt, diese Operation durchzuführen bzw. diese Seite aufzurufen.', '2010-02-27 17:51:28'),
(9, 2, 'You are not allowed to view this page or to execute this action.', '2010-02-27 17:51:41'),
(7, 1, 'Zurück zur Startseite', '2010-02-27 17:51:54'),
(7, 2, 'back to home page', '2010-02-27 17:52:06'),
(2, 1, 'Seite nicht gefunden', '2010-02-27 17:52:28'),
(2, 2, 'Page not found', '2010-02-27 17:52:38'),
(6, 1, 'Falls du von einer anderen Seite hierhin verlinkt wurdest, kontaktiere bitte den Betreiber dieser Seite. Der Link sollte angepasst werden.', '2010-02-27 17:53:03'),
(6, 2, 'If you clicked a link from another web site, please contact their administrator. They should correct the link.', '2010-02-27 17:53:49'),
(5, 1, 'Falls du einen Link angeklickt hast, der auf dieser Seite war, handelt es sich höchstwahrscheinlich um einen Fehler dieser Webseite. Kontaktiere dann bitte das Team, damit wir diesen Fehler so schnell wie möglich zu korrigieren können.', '2010-02-27 17:54:40'),
(5, 2, 'If you clicked a link from this web site you found a mistake. Please contact us so that we are able to fix this problem as soon as possible.', '2010-02-27 17:55:47'),
(3, 1, 'Entschuldigung, die angeforderte Seite wurde leider nicht gefunden.', '2010-02-27 18:01:59'),
(3, 2, 'Sorry, this page does not exist.', '2010-02-27 17:58:34'),
(4, 1, 'Wenn du die Adresse manuell eingegeben hast, überprüfe sie bitte auf die korrekte Schreibweise. Beachte auch, dass zwischen Groß- und Kleinschreibung unterschieden wird.', '2010-02-27 17:59:54'),
(4, 2, 'Please check the address for spelling mistakes.', '2010-02-27 18:00:55'),
(10, 1, 'Wer ist online?', '2010-03-03 21:41:06'),
(10, 2, 'Who is online?', '2010-03-03 21:41:18'),
(11, 1, 'Klicke hier, um mehr Details zu erfahren', '2010-03-03 21:56:36'),
(11, 2, 'Click here to view details', '2010-03-03 21:56:54'),
(12, 1, 'Klicke hier, um zu einem größeren Anmeldeformular mit weiteren Optionen zu gelangen', '2010-03-04 18:38:22'),
(12, 2, 'Click here to view an enlarged login form with further options', '2010-03-04 18:38:22'),
(15, 1, 'Anmelden', '2010-03-04 18:38:58'),
(15, 2, 'Login', '2010-03-04 18:38:58'),
(14, 1, 'Passwort', '2010-03-10 18:28:55'),
(14, 2, 'Password', '2010-03-10 18:29:05'),
(13, 1, 'Benutzer', '2010-03-10 18:29:09'),
(13, 2, 'User', '2010-03-10 18:29:13'),
(16, 1, 'Anmeldung', '2010-03-04 18:40:03'),
(16, 2, 'Login', '2010-03-04 18:40:03'),
(23, 1, 'Klicke hier, um zum persönlichen Bereich zu gelangen', '2010-03-04 21:05:34'),
(23, 2, 'Click here to enter personal control panel', '2010-03-04 21:05:44'),
(18, 1, 'Angemeldet als {userName html}', '2010-05-22 13:43:18'),
(18, 2, 'Logged in as {userName html}', '2010-03-05 22:05:53'),
(19, 1, 'Abmelden', '2010-03-04 21:03:27'),
(19, 2, 'Logout', '2010-03-04 21:03:39'),
(20, 1, 'Persönlicher Bereich', '2010-03-04 21:04:15'),
(20, 2, 'Personal Control Panel', '2010-03-04 21:04:15'),
(21, 1, 'Mein Profil', '2010-03-04 21:04:49'),
(21, 2, 'My Profile', '2010-03-04 21:04:49'),
(24, 1, 'Anmelden', '2010-03-04 21:18:25'),
(24, 2, 'Login', '2010-03-04 21:18:25'),
(25, 1, 'Benutzername', '2010-03-10 18:29:21'),
(25, 2, 'User Name', '2010-03-10 18:29:25'),
(26, 1, 'Passwort', '2010-03-10 18:29:32'),
(26, 2, 'Password', '2010-03-10 18:29:37'),
(27, 1, 'Anmeldung', '2010-03-04 21:28:20'),
(27, 2, 'Login', '2010-03-04 21:28:20'),
(29, 1, 'Die Anmeldung ist fehlgeschlagen. Bitte überprüfe Benutzername und Passwort.', '2010-03-04 21:35:12'),
(29, 2, 'Sorry, but your log-in data is incorrect. Please check user name and password.', '2010-03-04 21:35:12'),
(28, 1, 'Anmeldung fehlgeschlagen', '2010-03-04 21:35:44'),
(28, 2, 'Login failed', '2010-03-04 21:35:44'),
(30, 1, 'Du konntest nicht im System angemeldet werden. Bitte überprüfe den eingegebenen Benutzernamen und dein Passwort.', '2010-03-04 21:46:50'),
(30, 2, 'We were not able to log you in. Please check user name and password.', '2010-03-04 21:46:50'),
(31, 1, 'Hast du dein Passwort vergessen? Kein Problem, wir können dir ein neues zuschicken.', '2010-03-04 21:49:29'),
(31, 2, 'Have you forgotten you password? No problem, we can send you a new one.', '2010-03-04 21:49:29'),
(32, 1, 'Folge diesem Link, um ein neues Passwort zu bekommen.', '2010-03-04 21:49:53'),
(32, 2, 'Follow this link to get a new password.', '2010-03-04 21:49:53'),
(34, 1, 'Du wurdest erfolgreich angemeldet.', '2010-03-05 16:26:56'),
(33, 1, 'Möchtest du es erneut versuchen?', '2010-03-04 21:53:37'),
(33, 2, 'Do you want to try it again?', '2010-03-04 21:53:37'),
(34, 2, 'You have been logged in successfully.', '2010-03-05 16:43:29'),
(35, 1, 'Momentan ist kein Besucher auf dieser Website.', '2010-03-05 16:48:21'),
(35, 2, 'At the moment, there is no visitor of this web site', '2010-03-05 16:48:21'),
(36, 1, 'Diese Seite zeigt dir, welche Mitglieder in den letzten {timeSpan} Minuten online waren.', '2010-03-05 16:51:46'),
(36, 2, 'Here you can see which users were online in the past {timeSpan} minutes.', '2010-03-05 16:51:46'),
(38, 1, 'Letzte Aktivität', '2010-03-05 17:04:39'),
(38, 2, 'Last action', '2010-03-05 17:04:39'),
(39, 1, 'Aktuelle Seite', '2010-03-05 17:05:25'),
(39, 2, 'Current location', '2010-03-05 17:05:25'),
(37, 1, 'Benutzer', '2010-03-05 17:05:50'),
(37, 2, 'User', '2010-03-05 17:05:50'),
(41, 1, 'Montag', '2010-03-05 22:51:24'),
(42, 1, 'Dienstag', '2010-03-05 22:51:24'),
(43, 1, 'Mittwoch', '2010-03-05 22:51:24'),
(44, 1, 'Donnerstag', '2010-03-05 22:51:24'),
(45, 1, 'Freitag', '2010-03-05 22:51:24'),
(46, 1, 'Samstag', '2010-03-05 22:51:24'),
(47, 1, 'Sonntag', '2010-03-05 22:51:24'),
(48, 1, 'Mo', '2010-03-05 22:51:24'),
(49, 1, 'Di', '2010-03-05 22:51:24'),
(50, 1, 'Mi', '2010-03-05 22:51:24'),
(51, 1, 'Do', '2010-03-05 22:51:24'),
(52, 1, 'Fr', '2010-03-05 22:51:24'),
(53, 1, 'Sa', '2010-03-05 22:51:24'),
(54, 1, 'So', '2010-03-05 22:51:24'),
(55, 1, 'Januar', '2010-03-05 22:51:24'),
(56, 1, 'Februar', '2010-03-05 22:51:24'),
(57, 1, 'März', '2010-03-05 22:51:24'),
(58, 1, 'April', '2010-03-05 22:51:24'),
(59, 1, 'Mai', '2010-03-05 22:51:24'),
(60, 1, 'Juni', '2010-03-05 22:51:24'),
(61, 1, 'Juli', '2010-03-05 22:51:24'),
(62, 1, 'August', '2010-03-05 22:51:24'),
(63, 1, 'September', '2010-03-05 22:51:24'),
(64, 1, 'Oktober', '2010-03-05 22:51:24'),
(65, 1, 'November', '2010-03-05 22:51:24'),
(66, 1, 'Dezember', '2010-03-05 22:51:24'),
(67, 1, 'Jan', '2010-03-05 22:51:24'),
(68, 1, 'Feb', '2010-03-05 22:51:24'),
(69, 1, 'Mär', '2010-03-05 22:51:24'),
(70, 1, 'Apr', '2010-03-05 22:51:24'),
(71, 1, 'Mai', '2010-03-05 22:51:24'),
(72, 1, 'Jun', '2010-03-05 22:51:24'),
(73, 1, 'Jul', '2010-03-05 22:51:24'),
(74, 1, 'Aug', '2010-03-05 22:51:24'),
(75, 1, 'Sep', '2010-03-05 22:51:24'),
(76, 1, 'Okt', '2010-03-05 22:51:24'),
(77, 1, 'Nov', '2010-03-05 22:51:24'),
(78, 1, 'Dez', '2010-03-05 22:51:24'),
(41, 2, 'Monday', '2010-03-05 22:51:37'),
(42, 2, 'Tuesday', '2010-03-05 22:51:37'),
(43, 2, 'Wednesday', '2010-03-05 22:51:37'),
(44, 2, 'Thursday', '2010-03-05 22:51:37'),
(45, 2, 'Friday', '2010-03-05 22:51:37'),
(46, 2, 'Saturday', '2010-03-05 22:51:37'),
(47, 2, 'Sunday', '2010-03-05 22:51:37'),
(48, 2, 'Mon', '2010-03-05 22:51:37'),
(49, 2, 'Tue', '2010-03-05 22:51:37'),
(50, 2, 'Wed', '2010-03-05 22:51:37'),
(51, 2, 'Thu', '2010-03-05 22:51:37'),
(52, 2, 'Fri', '2010-03-05 22:51:37'),
(53, 2, 'Sat', '2010-03-05 22:51:37'),
(54, 2, 'Sun', '2010-03-05 22:51:37'),
(55, 2, 'January', '2010-03-05 22:51:37'),
(56, 2, 'February', '2010-03-05 22:51:37'),
(57, 2, 'March', '2010-03-05 22:51:37'),
(58, 2, 'April', '2010-03-05 22:51:37'),
(59, 2, 'May', '2010-03-05 22:51:37'),
(60, 2, 'Juni', '2010-03-05 22:51:37'),
(61, 2, 'July', '2010-03-05 22:51:37'),
(62, 2, 'August', '2010-03-05 22:51:37'),
(63, 2, 'September', '2010-03-05 22:51:37'),
(64, 2, 'October', '2010-03-05 22:51:37'),
(65, 2, 'November', '2010-03-05 22:51:37'),
(66, 2, 'December', '2010-03-05 22:51:37'),
(67, 2, 'Jan', '2010-03-05 22:51:37'),
(68, 2, 'Feb', '2010-03-05 22:51:37'),
(69, 2, 'Mar', '2010-03-05 22:51:37'),
(70, 2, 'Apr', '2010-03-05 22:51:37'),
(71, 2, 'May', '2010-03-05 22:51:37'),
(72, 2, 'Jun', '2010-03-05 22:51:37'),
(73, 2, 'Jul', '2010-03-05 22:51:37'),
(74, 2, 'Aug', '2010-03-05 22:51:37'),
(75, 2, 'Sep', '2010-03-05 22:51:37'),
(76, 2, 'Oct', '2010-03-05 22:51:37'),
(77, 2, 'Nov', '2010-03-05 22:51:37'),
(78, 2, 'Dec', '2010-03-05 22:51:37'),
(79, 1, 'Gestern', '2010-03-05 23:25:21'),
(79, 2, 'Yesterday', '2010-03-05 23:25:21'),
(80, 1, 'Heute', '2010-03-05 23:27:59'),
(80, 2, 'Today', '2010-03-05 23:28:23'),
(81, 1, 'Morgen', '2010-03-05 23:28:40'),
(81, 2, 'Tomorrow', '2010-03-05 23:28:40'),
(82, 1, '{time longDateTime}', '2010-03-05 23:38:21'),
(82, 2, '{time longDateTime}', '2010-03-05 23:38:27'),
(119, 1, 'vor 1 Sekunde', '2010-03-06 00:16:37'),
(84, 1, 'vor {num} Sekunde{''n if(num!=1)}', '2010-03-10 20:06:15'),
(167, 2, 'Shows a form which allows you to edit this user', '2010-03-10 20:31:14'),
(86, 1, 'vor {num} Minute{''n if(num!=1)}', '2010-03-10 20:06:15'),
(88, 1, 'vor {num} Stunde{''n if(num!=1)}', '2010-03-10 20:06:15'),
(90, 1, 'vor {num} Tag{''en if(num!=1)}', '2010-03-10 20:06:15'),
(92, 1, 'vor {num} Monat{''en if(num!=1)}', '2010-03-10 20:06:15'),
(167, 1, 'Öffnet ein Formular, mit dem dieser Benutzer bearbeitet werden kann', '2010-03-10 20:31:14'),
(94, 1, 'vor {num} Jahr{''en if(num!=1)}', '2010-03-10 20:06:15'),
(96, 1, '{num} Sek', '2010-03-06 00:16:37'),
(98, 1, '{num} Min', '2010-03-06 00:16:37'),
(100, 1, '{num} Std', '2010-03-06 00:16:37'),
(166, 2, 'Shows a form which allows you to create a new user', '2010-03-10 20:31:14'),
(102, 1, '{num} Tag{''e if(num!=1)}', '2010-03-10 20:06:15'),
(104, 1, '{num} Monat{''e if(num!=1)}', '2010-03-10 20:06:15'),
(106, 1, '{num} Jahr{''e if(num!=1)}', '2010-03-10 20:07:09'),
(119, 2, '1 second ago', '2010-03-06 00:16:37'),
(84, 2, '{num} second{''s if(num!=1)} ago', '2010-03-10 20:07:09'),
(86, 2, '{num} minute{''s if(num!=1)} ago', '2010-03-10 20:07:09'),
(166, 1, 'Öffnet ein Formular, mit dem ein neuer Benutzer erstellt werden kann', '2010-03-10 20:31:14'),
(88, 2, '{num} hour{''s if(num!=1)} ago', '2010-03-10 20:07:09'),
(165, 2, 'Delete', '2010-03-10 20:29:09'),
(90, 2, '{num} day{''s if(num!=1)} ago', '2010-03-10 20:07:09'),
(165, 1, 'Löschen', '2010-03-10 20:29:09'),
(92, 2, '{num} month{''s if(num!=1)} ago', '2010-03-10 20:07:09'),
(164, 2, 'Edit', '2010-03-10 20:29:09'),
(94, 2, '{num} year{''s if(num!=1)} ago', '2010-03-10 20:07:09'),
(96, 2, '{num} sec', '2010-03-06 00:16:37'),
(164, 1, 'Bearbeiten', '2010-03-10 20:29:09'),
(98, 2, '{num} min', '2010-03-06 00:16:37'),
(100, 2, '{num} hour{''s if(num!=1)}', '2010-03-10 20:07:09'),
(163, 2, 'Add User', '2010-03-10 20:25:05'),
(102, 2, '{num} day{''s if(num!=1)}', '2010-03-10 20:07:09'),
(104, 2, '{num} month{''s if(num!=1)}', '2010-03-10 20:07:09'),
(163, 1, 'Benutzer hinzufügen', '2010-03-10 20:25:05'),
(106, 2, '{num} year{''s if(num!=1)}', '2010-03-10 20:07:09'),
(120, 1, 'Avatar von {userName html}', '2010-03-06 00:57:04'),
(120, 2, 'Avatar of {userName html}', '2010-03-06 00:57:04'),
(121, 1, 'Seite {page}', '2010-03-06 19:03:16'),
(121, 2, 'Page {page}', '2010-03-06 19:03:16'),
(122, 1, 'Weiter', '2010-03-06 19:04:38'),
(122, 2, 'Next', '2010-03-06 19:04:38'),
(123, 1, 'Zurück', '2010-03-06 19:04:38'),
(123, 2, 'Back', '2010-03-06 19:04:38'),
(124, 1, '...', '2010-03-06 23:04:26'),
(124, 2, '...', '2010-03-06 23:04:26'),
(125, 1, 'Eindeutiger Ressourcenbezeichner', '2010-03-06 23:19:22'),
(125, 2, 'Unique Resource Identifier', '2010-03-06 23:19:22'),
(126, 1, 'Startseite', '2010-03-06 23:19:22'),
(126, 2, 'Home', '2010-03-06 23:19:22'),
(127, 1, 'Aufwärts', '2010-03-06 23:19:22'),
(127, 2, 'Up', '2010-03-06 23:19:22'),
(128, 1, 'Benutzer', '2010-03-07 00:27:35'),
(128, 2, 'Users', '2010-03-07 00:23:45'),
(129, 1, 'Auf dieser Seite sind alle registrierten Benutzer, automatisierten Bots und das Gastkonto aufgelistet.', '2010-03-07 00:34:52'),
(130, 1, 'Zurzeit sind keine Benutzerkonten angelegt.', '2010-03-07 00:34:52'),
(129, 2, 'Here you see all registered users, automated bots and the guest account.', '2010-03-07 00:34:52'),
(130, 2, 'There are no user accounts at the moment.', '2010-03-07 00:34:52'),
(131, 1, 'Benutzerkonten', '2010-03-07 00:39:06'),
(131, 2, 'User Accounts', '2010-03-07 00:39:06'),
(132, 1, 'Mitglied seit', '2010-03-07 00:39:06'),
(132, 2, 'Registration Date', '2010-03-07 00:39:06'),
(133, 1, 'Letzte Anmeldung', '2010-03-07 00:39:06'),
(133, 2, 'Last Login', '2010-03-07 00:39:06'),
(134, 1, 'Dieser Benutzer existiert nicht.', '2010-03-07 00:43:02'),
(134, 2, 'This user does not exist.', '2010-03-07 00:43:40'),
(135, 1, 'Dieser Artikel existiert nicht.', '2010-03-07 00:43:26'),
(135, 2, 'This article does not exist.', '2010-03-07 00:43:26'),
(136, 1, '—', '2010-03-07 00:53:15'),
(136, 2, '—', '2010-03-07 00:53:15'),
(137, 1, 'Online-Status verstecken', '2010-03-07 16:35:27'),
(137, 2, 'Hide my login status', '2010-03-07 16:35:29'),
(138, 1, 'Gast', '2010-03-07 16:48:09'),
(138, 2, 'Guest', '2010-03-07 16:48:09'),
(139, 1, '{count} {''Gast if(count==1)}{''Gäste if(count!=1)}', '2010-03-07 17:17:57'),
(139, 2, '{count} Guest{''s if(count!=1)}', '2010-03-10 20:46:15'),
(169, 1, 'Gruppe hinzufügen', '2010-03-10 20:50:41'),
(142, 1, 'Benutzername', '2010-03-07 17:41:47'),
(142, 2, 'User Name', '2010-03-07 17:41:47'),
(143, 1, 'Titel', '2010-03-07 17:41:47'),
(143, 2, 'Title', '2010-03-07 17:41:47'),
(144, 1, ' – ', '2010-03-07 17:46:09'),
(144, 2, ' – ', '2010-03-07 17:46:12'),
(145, 1, 'Avatar', '2010-03-08 19:44:52'),
(145, 2, 'Avatar', '2010-03-08 19:45:40'),
(148, 1, 'Mitgliedschaft in Gruppen', '2010-03-08 20:15:03'),
(148, 2, 'Group membership', '2010-03-08 20:15:03'),
(149, 1, 'Gruppenleiter', '2010-03-08 21:24:15'),
(149, 2, 'Leader', '2010-03-08 21:24:07'),
(150, 1, 'Benutzergruppen', '2010-03-08 21:09:56'),
(150, 2, 'User Groups', '2010-03-08 21:09:56'),
(151, 1, 'Es wurden noch keine Benutzergruppen erstellt.', '2010-03-08 21:14:39'),
(151, 2, 'There are no user groups yet.', '2010-03-08 21:14:39'),
(152, 1, '{count} {''Mitglied if(count=1)}{''Mitglieder if(count!=1)}', '2010-03-08 21:23:27'),
(152, 2, '{count} {''Member if(count=1)}{''Members if(count!=1)}', '2010-03-08 21:23:36'),
(153, 1, 'Diese Benutzergruppe existiert nicht.', '2010-03-08 21:32:08'),
(153, 2, 'This user group does not exist.', '2010-03-08 21:32:08'),
(154, 1, 'Gruppenname', '2010-03-08 21:32:08'),
(154, 2, 'Group Name', '2010-03-08 21:32:08'),
(155, 1, 'Mitgliedertitel', '2010-03-08 21:32:08'),
(155, 2, 'Member''s Title', '2010-03-08 21:32:08'),
(156, 1, 'Beschreibung', '2010-03-08 21:32:08'),
(156, 2, 'Description', '2010-03-08 21:32:08'),
(157, 1, '({content})', '2010-03-10 18:25:55'),
(157, 2, '({content})', '2010-03-10 18:25:55'),
(158, 1, 'Mitglieder dieser Benutzergruppe', '2010-03-10 18:25:55'),
(158, 2, 'Members of this group', '2010-03-10 18:25:55'),
(159, 1, 'Seite {page} von {count}', '2010-03-10 18:25:55'),
(159, 2, 'Page {page} of {count}', '2010-03-10 18:25:55'),
(160, 1, '{label}:', '2010-03-10 18:27:44'),
(160, 2, '{label}:', '2010-03-10 18:27:44'),
(161, 1, 'Gruppenleiter', '2010-03-10 18:41:35'),
(161, 2, 'Group Leaders', '2010-03-10 18:41:35'),
(162, 1, 'Gruppenmitglieder', '2010-03-10 18:41:49'),
(162, 2, 'Group Members', '2010-03-10 18:41:49'),
(168, 1, 'Löscht diesen Benutzer', '2010-03-10 20:31:14'),
(168, 2, 'Deletes this user', '2010-03-10 20:42:28'),
(169, 2, 'Add Group', '2010-03-10 20:50:41'),
(170, 1, 'Öffnet ein Formular, mit dem eine neue Benutzergruppe erstellt werden kann', '2010-03-10 20:50:41'),
(170, 2, 'Shows a form which allows you to create a new user group', '2010-03-10 20:50:41'),
(171, 1, 'Bearbeiten', '2010-03-10 20:50:41'),
(171, 2, 'Edit', '2010-03-10 20:50:41'),
(172, 1, 'Öffnet ein Formular, mit dem diese Benutzergruppe bearbeitet werden kann', '2010-03-10 20:50:41'),
(172, 2, 'Shows a form which allows you to edit this user group', '2010-03-10 20:50:41'),
(173, 1, 'Löschen', '2010-03-10 20:50:41'),
(173, 2, 'Delete', '2010-03-10 20:50:41'),
(174, 1, 'Löscht diese Benutzergruppe', '2010-03-10 20:50:41'),
(174, 2, 'Deletes this user group', '2010-03-10 20:50:41'),
(175, 1, 'Benutzergruppen', '2010-03-10 20:54:03'),
(175, 2, 'User Groups', '2010-03-10 20:54:03'),
(176, 1, 'Zeigt die Liste der Benutzergruppen', '2010-03-10 20:54:03'),
(176, 2, 'Shows the list of user groups', '2010-03-10 20:54:03'),
(177, 1, 'Benutzerliste', '2010-03-10 20:54:03'),
(177, 2, 'Users', '2010-03-10 20:54:03'),
(178, 1, 'Zeigt die Liste der Benutzer', '2010-03-10 20:54:03'),
(178, 2, 'Shows the list of users', '2010-03-10 20:54:03'),
(179, 1, 'Diese Seite verfügt über keinen Inhalt.', '2010-03-29 20:32:19'),
(179, 2, 'This page is empty.', '2010-03-31 23:14:11'),
(180, 1, 'Seite nicht gefunden', '2010-03-31 20:18:29'),
(180, 2, 'Page Not Found', '2010-03-31 20:18:29'),
(182, 1, 'Zur übergeordneten, existierenden Seite wechseln', '2010-03-31 20:32:31'),
(182, 2, 'Back to existing page', '2010-03-31 23:14:56'),
(183, 2, 'This is the structure of user-defined pages. Hove an item to view useful tools to change the structure.', '2010-04-07 00:40:37'),
(184, 1, 'Bisher wurden noch keine benutzerdefinierten Seiten erstellt.', '2010-04-01 18:33:21'),
(184, 2, 'There are no user-defined pages, yet.', '2010-04-01 18:33:21'),
(185, 1, 'Seitenname', '2010-04-01 18:39:03'),
(185, 2, 'Page Name', '2010-04-01 18:39:03'),
(186, 1, 'Angezeigter Name', '2010-04-01 18:39:03'),
(186, 2, 'Display Name', '2010-04-01 18:39:03'),
(187, 1, '(Startseite)', '2010-04-01 19:09:02'),
(187, 2, '(Home Page)', '2010-04-01 19:09:02'),
(190, 1, 'Öffnet ein Formular, in dem Eigenschaften dieser Seite bearbeitet werden können', '2010-04-01 21:22:52'),
(188, 1, 'Bearbeiten', '2010-04-01 21:00:06'),
(188, 2, 'Edit', '2010-04-01 21:00:06'),
(189, 1, 'Löschen', '2010-04-01 21:00:20'),
(189, 2, 'Delete', '2010-04-01 21:00:20'),
(190, 2, 'Shows a form in that you can edit properties of this page', '2010-04-01 21:22:52'),
(191, 1, 'Löscht diese Seite', '2010-04-01 21:23:35'),
(191, 2, 'Deletes this page', '2010-04-01 21:23:35'),
(192, 1, 'Verschieben', '2010-04-01 21:51:57'),
(192, 2, 'Move', '2010-04-01 21:51:57'),
(193, 1, 'Öffnet ein Formular, mit dem diese Seite verschoben werden kann', '2010-04-01 21:52:49'),
(193, 2, 'Shows a form that allows you to move this page', '2010-04-01 21:52:49'),
(194, 1, 'Seite hinzufügen', '2010-04-01 21:57:44'),
(194, 2, 'Add page', '2010-04-01 21:57:44'),
(195, 1, 'Erstellt eine neue Seite, die dieser Seite untergeordnet ist', '2010-04-01 21:59:29'),
(195, 2, 'Creates a new subpage', '2010-04-01 22:00:37'),
(196, 1, 'Möchtest du die Seite <a href="./{url}">{title html}</a> <b>samt ihrer untergeordneten Seiten</b> wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.', '2010-04-01 22:17:44'),
(196, 2, 'Are you sure you want to delete <a href="./{url}">{title html}</a> <b>and all its subpages</b>? This action cannot be undone.', '2010-04-01 22:18:00'),
(197, 1, 'Bestätigen', '2010-04-01 22:23:07'),
(197, 2, 'Confirm', '2010-04-01 22:23:07'),
(198, 1, 'Möchtest du diese Aktion wirklich durchführen?', '2010-04-01 22:23:53'),
(198, 2, 'Are you sure you want to do this action?', '2010-04-01 22:23:53'),
(199, 1, 'Name', '2010-04-01 22:28:05'),
(199, 2, 'Name', '2010-04-01 22:28:05'),
(200, 1, 'Anzeigename', '2010-04-01 22:28:25'),
(200, 2, 'Display Name', '2010-04-01 22:28:25'),
(201, 1, 'Dieser Name wird in der URL verwendet werden.', '2010-04-01 22:29:18'),
(201, 2, 'This name will be used in the url.', '2010-04-01 22:29:18'),
(202, 1, 'Dieser Name wird in der Titelzeile des Browsers sowie in der Navigation angezeigt werden.', '2010-04-01 22:30:14'),
(202, 2, 'This name will be displayed in title bar of the browser and in navigation fields.', '2010-04-01 22:30:14'),
(203, 1, 'Abschicken', '2010-04-01 22:31:04'),
(203, 2, 'Submit', '2010-04-01 22:31:04'),
(204, 1, 'Absenden', '2010-04-01 22:33:26'),
(204, 2, 'Submit', '2010-04-01 22:33:26'),
(205, 1, 'Speichern', '2010-04-01 22:33:40'),
(205, 2, 'Save', '2010-04-01 22:33:40'),
(206, 1, 'Bearbeiten', '2010-04-01 22:40:03'),
(206, 2, 'Edit', '2010-04-01 22:40:03'),
(207, 1, 'Löschen', '2010-04-01 22:40:36'),
(207, 2, 'Delete', '2010-04-01 22:40:36'),
(208, 1, 'Seiteneigenschaften bearbeiten', '2010-04-01 22:42:22'),
(208, 2, 'Edit Page Properties', '2010-04-01 22:42:22'),
(209, 1, 'Seite hinzufügen', '2010-04-06 22:44:27'),
(209, 2, 'Add Page', '2010-04-06 22:44:27'),
(210, 1, 'Seite verschieben', '2010-04-06 21:57:04'),
(210, 2, 'Move Page', '2010-04-06 21:57:04'),
(211, 1, 'Seite löschen', '2010-04-06 21:57:25'),
(211, 2, 'Delete Page', '2010-04-06 21:57:25'),
(213, 1, 'Bitte gib einen Namen ein.', '2010-04-06 23:31:36'),
(213, 2, 'Please enter a name.', '2010-04-06 23:14:46'),
(214, 1, 'Bitte gib einen Anzeigenamen ein.', '2010-04-06 23:31:25'),
(214, 2, 'Please enter a display name.', '2010-04-06 23:15:04'),
(215, 1, 'Auf der übergeordneten Seite existiert bereits ein Eintrag mit diesem Namen.', '2010-04-22 21:00:36'),
(215, 2, 'Parent page already has a subpage called so.', '2010-04-22 21:00:41'),
(216, 1, 'Fortfahren', '2010-04-07 00:04:08'),
(216, 2, 'Confirm', '2010-04-07 00:04:08'),
(217, 1, 'Abbrechen', '2010-04-07 00:04:08'),
(217, 2, 'Cancel', '2010-04-07 00:04:08'),
(218, 1, 'Seitenstruktur', '2010-04-07 00:38:20'),
(218, 2, 'Page Structure', '2010-04-07 00:38:20'),
(183, 1, 'Nachfolgend siehst du die Struktur aller benutzerdefinierten Seiten. Fahre mit der Maus über einen Eintrag, damit Werkzeuge eingeblendet werden, mit denen die Struktur bearbeitet werden kann.', '2010-04-07 22:58:36'),
(8, 3, 'Accès non autorisé', '2010-04-10 20:04:39'),
(220, 1, 'Unten siehst du die Seitenstruktur. Fahre über einen Eintrag, um die Schaltfläche "Hier einfügen" einzublenden. Wenn du auf diese klickst, wird die aktuelle Seite ({title html}) der gewählten untergeordnet.</p>\r\n\r\n<p>Hinweis: Es ist nicht möglich, die Reihenfolge von Seiten zu verändern. Im Normalfall werden gleichrangige Einträge (Einträge mit derselben übergeordneten Seite) alphabetisch sortiert.\r\n', '2010-04-25 13:01:25'),
(220, 2, 'Below you see the page structure. Hover an entry to view "Insert here" button. Click this button to make the current page ({title html}) a sub-page of the selected one.</p>\r\n\r\n<p>Note: Changing order of pages is not possible. Usually, pages with the same superior page are sorted alphabetically.', '2010-04-25 13:01:39'),
(221, 1, 'Hier einfügen', '2010-04-22 15:18:04'),
(221, 2, 'Insert here', '2010-04-22 15:18:04'),
(222, 1, 'Ordnet die aktuelle Seite dieser unter', '2010-04-22 15:19:28'),
(222, 2, 'Makes the current page a sub-page of this one', '2010-04-22 15:19:28'),
(223, 1, 'Die Seite, in de verschoben werden sollte, existiert nicht.', '2010-04-22 17:43:32'),
(223, 2, 'The page in which you tried to insert does not exist.', '2010-04-22 17:43:32'),
(224, 1, 'Die Seite kann nicht in eine ihr untergeordnete Seite oder in sich selbst verschoben werden.', '2010-04-22 18:03:07'),
(224, 2, 'You can not move a page into one of its sub-pages or into it self.', '2010-04-22 18:03:07'),
(225, 1, 'In der Zielseite existiert bereits eine gleichnamige Seite.', '2010-04-22 18:06:35'),
(225, 2, 'Target page already has a sub-page called so.', '2010-04-22 18:06:35'),
(226, 1, 'Gib hier den Namen des Benutzers ein. Er darf noch nicht vorhanden sein.', '2010-04-23 16:33:23'),
(226, 2, 'Enter the user''s name here. It must not be used already.', '2010-04-23 16:33:23'),
(227, 1, 'Benutzer ist ein Bot', '2010-04-23 21:48:52'),
(227, 2, 'User is a bot', '2010-04-23 21:48:00'),
(228, 1, 'Bot-Konfiguration', '2010-04-23 21:48:50'),
(228, 2, 'Bot Configuration', '2010-04-23 21:47:53'),
(229, 1, 'Wenn dieses Feld ausgewählt ist, kann ein Bot automatisch erkannt und diesem Benutzerkonto zugewiesen werden. Bots haben kein Passwort.', '2010-04-23 16:50:34'),
(229, 2, 'Check this box to identify bots and assign them automatically to this user account. Bots don''t have a password.', '2010-04-23 16:50:34'),
(230, 1, 'Bot-Erkennung', '2010-04-23 16:51:37'),
(230, 2, 'Bot Identifier', '2010-04-23 16:51:37'),
(231, 1, 'Gib hier einen regulären Ausdruck ein, der auf den User-Agent des Bots zutrifft. Modifier werden nicht unterstützt und daher werden auch keine Deliminiter benötigt. Zwischen Groß- und Kleinschreibung wird nicht unterschieden.', '2010-04-23 16:57:20'),
(231, 2, 'Enter a regular expression that matches the user-agent of the bot. Modifiers are not supportet, therefore delimiters are not neccessary. The expression is case-insensitive.', '2010-04-23 16:57:20'),
(232, 1, 'Passwort', '2010-04-23 17:07:18'),
(232, 2, 'Password', '2010-04-23 17:07:18'),
(233, 1, 'Gib hier das geheime Passwort ein, dass bei jeder Anmeldung benötigt wird.', '2010-04-23 17:07:59'),
(233, 2, 'Enter a secret password. It will be needed for each login.', '2010-04-23 17:07:59'),
(234, 1, 'Passwort bestätigen', '2010-04-23 17:09:19'),
(234, 2, 'Confirm password', '2010-04-23 17:09:19'),
(235, 1, 'Gib hier nochmals das gleiche Passwort wie oben ein.', '2010-04-23 17:09:53'),
(235, 2, 'Re-enter the same password as above.', '2010-04-23 17:09:53'),
(236, 1, 'Bitte gib einen Benutzernamen ein.', '2010-04-23 21:13:56'),
(236, 2, 'Please enter a user name.', '2010-04-23 21:13:56'),
(237, 1, 'E-Mail-Adresse', '2010-04-23 21:19:36'),
(237, 2, 'E-mail address', '2010-04-23 21:19:36'),
(238, 1, 'An diese E-Mail-Adresse werden z.B. Passwort-Vergessen-E-Mails oder Benachrichtigungen verschickt. Sie wird nirgends öffentlich angezeigt.', '2010-04-23 21:22:13'),
(238, 2, 'For example, password-lost-e-mails or notifications are send to this address. It will never be shown in public.', '2010-04-23 21:22:13'),
(239, 1, 'Du hast eine ungültige E-Mail-Adresse eingegeben.', '2010-04-23 21:35:36'),
(239, 2, 'You entered an invalid e-mail address.', '2010-04-23 21:35:36'),
(240, 1, 'Bitte gib eine E-Mail-Adresse ein.', '2010-04-23 21:39:25'),
(240, 2, 'Please enter an e-mail address.', '2010-04-23 21:39:25'),
(241, 1, 'Es existiert bereits ein Benutzer mit diesem Namen.', '2010-04-23 21:42:16'),
(241, 2, 'There is already a user called so.', '2010-04-23 21:42:16'),
(242, 1, 'Bitte gib eine Bot-Erkennung ein, oder deaktiviere "Benutzer ist ein Bot".', '2010-04-23 21:56:07'),
(242, 2, 'Please enter a bot identifier or deselect "User is a bot"', '2010-04-23 21:56:07'),
(243, 1, 'Die eingegebene Bot-Erkennung ist kein gültiger regulärer Ausdruck (Hinweis: Delimiters und Modifiers dürfen nicht angegeben werden).', '2010-04-23 21:58:03'),
(243, 2, 'You inputted an invalid bot identifier (Note: delimiters and modifiers are not supported).', '2010-04-23 21:58:03'),
(244, 1, 'Bitte gib ein Passwort ein.', '2010-04-23 22:19:33'),
(244, 2, 'Please enter a password.', '2010-04-23 22:19:33'),
(245, 1, 'Bitte wiederhole das Passwort im Feld "Passwort bestätigen".', '2010-04-23 22:20:17'),
(245, 2, 'Please re-enter your password in "Confirm password" field.', '2010-04-23 22:20:17'),
(246, 1, 'Die beiden eingegeben Passwörter stimmen nicht überein.', '2010-04-23 22:21:10'),
(246, 2, 'The passwords are different.', '2010-04-23 22:21:10'),
(247, 1, 'Der eingegebene Name enthält nicht erlaubte Zeichen (z.B. Schrägstriche (/)).', '2010-06-06 16:54:04'),
(247, 2, 'The inputted name contains forbidden characters (e.g. slashes (/)).', '2010-06-06 16:54:04'),
(248, 1, 'Möchtest du den Benutzer <a href="./{url}">{name html}</a> wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.', '2010-04-23 23:22:02'),
(248, 2, 'Are you sure you want to delete <a href="./{url}">{name html}</a>? This action cannot be undone.', '2010-04-23 23:22:02'),
(503, 1, 'Du hast ungültige Daten für ein Feld abgeschickt, für das PML-Daten erwartet werden. {''Fehler: if(message)} {message}', '2010-06-19 20:01:15'),
(250, 1, 'Gib hier einen eindeutigen Namen für die Benutzergruppe ein.', '2010-04-23 23:54:14'),
(250, 2, 'Enter a unique name for this group.', '2010-04-23 23:54:14'),
(252, 1, 'Gruppenmitglieder erhalten diesen Titel. Beispiel: Gruppenname im Singular.', '2010-04-23 23:59:48'),
(252, 2, 'Group members get this title. Example: group name in singular', '2010-04-23 23:59:48'),
(253, 1, 'Farbe', '2010-04-24 00:02:10'),
(253, 2, 'Color', '2010-04-24 00:02:10'),
(254, 1, 'Die Farbe wird im hexadezimalen Format RRGGBB angegeben.', '2010-04-24 00:02:40'),
(254, 2, 'Enter the color in hexadecimal format RRGGBB.', '2010-04-24 00:02:40'),
(255, 1, 'Beschreibe hier kurz, was die Gruppenmitglieder gemeinsam haben oder was diese Gruppe sonst auszeichnet.', '2010-04-24 00:05:29'),
(255, 2, 'Describe what the members have in common or what''s else that characterizes this group.', '2010-04-24 00:05:29'),
(256, 1, 'Alle zukünftig registrierten Benutzer sollen automatisch dieser Gruppe beitreten', '2010-04-24 00:08:31'),
(256, 2, 'All in the future registered users will automatically join this group', '2010-04-24 00:08:31'),
(257, 1, 'Automatisch beitreten', '2010-04-24 00:08:54'),
(257, 2, 'Auto-Join', '2010-04-24 00:08:54'),
(258, 1, 'Möchtest du die Benutzergruppe <a href="./{url}">{name html}</a>, die zugehörigen Gruppenmitgliedschaften und <b>alle damit verbunden Rechte</b> wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.</p>\r\n\r\n<p><b>Achtung:</b> Wenn du im Begriff bist, eine Gruppe zu löschen, die dir das Recht zur Benutzerverwaltung erteilt, wirst du dieses Recht möglicherweise verlieren!', '2010-04-24 00:19:51'),
(258, 2, 'Are you sure you want to delete <a href="./{url}">{name html}</a> user group, its group membership and <b>all linked rights</b>? This action cannot be undone.</p>\r\n\r\n<p><b>Warning:</b> If you are deleting a group that gives you the right of administrate users, you might loose this right!</p>', '2010-04-24 00:19:51'),
(259, 1, 'Bitte gib einen Gruppennamen ein.', '2010-04-24 00:37:21'),
(259, 2, 'Please enter a group name.', '2010-04-24 00:37:21'),
(260, 1, 'Es existiert bereits eine Benutzergruppe mit diesem Namen.', '2010-04-24 00:38:06'),
(260, 2, 'There is already a user group with this name.', '2010-04-24 00:38:06'),
(261, 1, 'Bitte gib einen Mitgliedertitel ein.', '2010-04-24 00:39:31'),
(261, 2, 'Please enter a member title.', '2010-04-24 00:39:31'),
(262, 1, 'Bitte gib eine Gruppenfarbe ein.', '2010-04-24 00:40:24'),
(262, 2, 'Please enter a color.', '2010-04-24 00:40:24'),
(263, 1, 'Du hast eine ungültige Farbangabe gemacht.', '2010-04-24 00:41:35'),
(263, 2, 'You inputted an invalid color.', '2010-04-24 00:41:35'),
(264, 1, 'Bitte gib eine Beschreibung ein.', '2010-04-24 00:42:36'),
(264, 2, 'Please enter a description.', '2010-04-24 00:42:36'),
(265, 1, 'Es ist nicht möglich, das Benutzerkonto des Gastes zu löschen.', '2010-04-24 00:52:02'),
(265, 2, 'It''s not possible to remove the guest''s user account.', '2010-04-24 00:52:02'),
(266, 1, 'Fehler', '2010-04-24 00:54:13'),
(266, 2, 'Error', '2010-04-24 00:54:13'),
(267, 1, 'Zurück zum Gastkonto', '2010-04-24 00:56:35'),
(267, 2, 'Back to guest''s account', '2010-04-24 00:56:35'),
(138, 3, 'Invité', '2010-05-24 20:05:27'),
(268, 1, 'Gruppe beitreten', '2010-04-25 12:50:54'),
(268, 2, 'Join Group', '2010-04-25 12:50:54'),
(269, 1, 'Fügt diesen Benutzer in eine Benutzergruppe ein', '2010-04-25 12:53:15'),
(269, 2, 'Adds this user to a user group', '2010-04-25 12:53:15'),
(270, 1, 'Unten sind alle Benutzergruppen, die du verwalten kannst, und in denen {name html} noch nicht Mitglied ist, aufgelistet. Klicke auf einen Eintrag, um {name html} dieser Gruppe hinzuzufügen.', '2010-04-25 13:08:22'),
(271, 1, 'Dieser Benutzer ist bereits Mitglied in allen Gruppen, die du verwalten kannst.', '2010-04-25 13:09:55'),
(270, 2, 'Below you see all groups you are allowed to manage in which {name html} is not a member. Click an item to add {name html} to this group.', '2010-04-25 13:09:07'),
(271, 2, '{name html} is already member of all groups you can manage.', '2010-04-25 13:09:55'),
(272, 1, 'Es existiert keine Benutzergruppe mit diesem Namen.', '2010-04-25 13:26:41'),
(272, 2, 'There is no user group called like that.', '2010-04-25 13:26:41'),
(273, 1, 'Der Benutzer ist bereits Mitglied dieser Benutzergruppe.', '2010-04-25 13:30:34'),
(273, 2, 'The user is already a member of this group.', '2010-04-25 13:30:34'),
(274, 1, 'Verlassen', '2010-04-25 13:38:00'),
(274, 2, 'Leave', '2010-04-25 13:38:00'),
(275, 1, 'Entfernt diesen Benutzer aus dieser Benutzergruppe', '2010-04-25 13:39:19'),
(275, 2, 'Removes the user out of this user group', '2010-04-25 13:39:19'),
(276, 1, 'Zurück zum Benutzer', '2010-04-25 13:57:29'),
(276, 2, 'Back to user', '2010-04-25 13:57:29'),
(277, 1, 'Der Benutzer ist nicht (mehr) Mitglied dieser Benutzergruppe.', '2010-04-25 13:58:55'),
(277, 2, 'The user is not a member of this group.', '2010-04-25 13:58:55'),
(278, 1, 'Zum Gruppenleiter ernennen', '2010-04-25 14:10:28'),
(278, 2, 'Nominate as Leader', '2010-04-25 14:10:28'),
(279, 1, 'Ernennt dieses Mitglied zum Gruppenleiter dieser Gruppe', '2010-04-25 14:11:19'),
(279, 2, 'Nominates this member as leader of this group', '2010-04-25 14:11:19'),
(280, 1, 'Leitung entziehen', '2010-04-25 14:12:06'),
(280, 2, 'Demote', '2010-04-25 14:12:06'),
(281, 1, 'Entzieht diesem Mitglied die Leitung über die Gruppe', '2010-04-25 14:12:35'),
(281, 2, 'Demotes this user', '2010-04-25 14:12:35'),
(282, 1, 'Benutzer', '2010-04-25 16:45:55'),
(282, 2, 'User', '2010-04-25 16:45:55'),
(283, 1, 'Priorität', '2010-04-25 17:01:44'),
(283, 2, 'Priority', '2010-04-25 17:01:44'),
(284, 1, 'Die Gruppe mit der höchsten Priorität bestimmt den Titel eines Benutzers.', '2010-04-25 17:03:34'),
(284, 2, 'The group with the highest priority sets a user''s title', '2010-04-25 17:03:34'),
(285, 1, 'Die Priorität muss eine nicht negative Zahl sein oder leer gelassen werden.', '2010-04-25 17:06:37'),
(285, 2, 'Priority must be a nonnegative number or left empty.', '2010-04-25 17:06:37'),
(286, 1, 'Rechte', '2010-04-26 19:12:02'),
(286, 2, 'Rights', '2010-04-26 19:12:02'),
(287, 1, 'Verwaltet die Rechte, die Mitglieder dieser Gruppe erhalten', '2010-04-26 19:12:51'),
(287, 2, 'Manage rights that members of this group get', '2010-04-26 19:12:51'),
(288, 1, 'Rechte', '2010-04-26 21:14:10'),
(288, 2, 'Rights', '2010-04-26 21:14:10'),
(289, 1, 'Zeigt eine Liste der Rechte dieses Benutzers an', '2010-04-26 21:14:36'),
(289, 2, 'Shows a list of this user''s rights', '2010-04-26 21:14:36'),
(290, 1, 'Hier siehst du eine Liste der Rechte, über die dieser Benutzer verfügt. Sie ergeben sich aus allen Rechten, die den Gruppen zugeordnet sind, in denen dieser Benutzer Mitglied ist.</p>\r\n\r\n<p>Bearbeite Gruppenrechte, füge den Benutzer in weitere Gruppen ein oder entferne ihn aus Gruppen, um seine Rechte zu beeinflussen.', '2010-04-26 21:21:03'),
(290, 2, 'Here you see all rights of this user. They are the rights of all groups of that this user is a member.</p>\r\n\r\n<p>To change this user''s rights, edit group rights, add this user to further groups or remove it out of groups.', '2010-04-26 21:21:03'),
(291, 1, 'Dieser Benutzer verfügt momentan über keine Rechte.</p>\r\n\r\n<p>Bearbeite Gruppenrechte oder füge den Benutzer in weitere Gruppen ein, um ihm Rechte zuzusprechen.', '2010-04-26 21:23:17'),
(291, 2, 'This user does not have any rights.</p>\r\n\r\n<p>To add rights, change group rights or add this user to further groups.', '2010-04-26 21:23:30'),
(292, 1, 'Recht', '2010-04-26 21:25:11'),
(292, 2, 'Right', '2010-04-26 21:25:11'),
(293, 1, 'Verliehen von', '2010-04-26 21:26:11'),
(293, 2, 'Granted by', '2010-04-26 21:26:11'),
(294, 1, 'Du hast versucht, eine ungültige Seite aufzurufen, eine ungültige Aktion durchzuführen oder ungültige Eingaben getätigt.', '2010-04-28 17:36:56'),
(294, 2, 'You tried to view an invalid page, do an invalid command or your inputs are wrong.', '2010-04-28 17:36:56'),
(296, 1, 'Sperren', '2010-04-29 16:15:48'),
(296, 2, 'Lock', '2010-04-29 16:15:48'),
(297, 1, 'Entzieht den Gruppenleitern die Rechte über diese Gruppe', '2010-04-29 16:20:47'),
(297, 2, 'Revokes group founders'' and group leaders'' rights at this group', '2010-04-29 16:20:47'),
(298, 1, 'Entsperren', '2010-04-29 16:21:00'),
(298, 2, 'Unlock', '2010-04-29 16:21:00'),
(299, 1, 'Gibt den Gruppenleitern die Rechte an dieser Gruppe zurück', '2010-04-29 16:21:34'),
(299, 2, 'Gives back the rights at this groups to the group leaders', '2010-04-29 16:21:34'),
(300, 1, 'Möchtest du den Gruppenleitern wirklich die Rechte über diese Gruppe entziehen? Die Gruppe wird danach nur von Benutzern bearbeitet und entsperrt werden können, die über Recht zum Sperren von Gruppen verfügen.', '2010-04-29 16:31:25'),
(300, 2, 'Are you sure you want to lock this group so that group leaders will not be allowed to edit or unlock this group? Only those users who have the "lock groups" right will be able to unlock this group.', '2010-04-29 16:31:25'),
(301, 1, 'Möchtest du den Gruppenleitern die Rechte über diese Gruppe zurückgeben?', '2010-04-29 16:43:59'),
(301, 2, 'Do you want to give back the rights at this group to the group leaders?', '2010-04-29 16:43:59'),
(302, 1, 'Du bist nicht berechtigt, diese Gruppe zu verwalten.', '2010-04-30 15:27:22'),
(302, 2, 'You are not allowed to manage this group.', '2010-04-30 15:27:22'),
(303, 1, 'Du hast nicht das Recht, ein Benutzerkonto zu installieren.', '2010-05-08 18:45:28'),
(303, 2, 'You are not allowed to create an account.', '2010-05-08 18:45:28'),
(304, 1, 'Hier kannst du ein neues Benutzerkonto für dich anlegen.</p>\r\n\r\n<p>Dafür benötigst du ein E-Mail-Postfach, denn du musst eine E-Mail-Adresse eingeben, die bestätigt werden muss.</p>\r\n\r\n<p>Außerdem kannst du einen Benutzernamen wählen, der allerdings noch nicht verwendet sein darf, und ein Passwort eingeben, dass bei jeder Anmeldung benötigt werden wird.', '2010-05-08 18:53:16'),
(304, 2, 'Here you can register a new user account.</p>\r\n\r\n<p>You need an e-mail address because you have to enter and confirm one.</p>\r\n\r\n<p>In addition, you have to choose a user name that must not be existing yet, and a password which will be needed for each login.', '2010-05-08 18:53:34'),
(305, 1, 'Hier kannst du ein neues Benutzerkonto für dich anlegen.</p>\r\n\r\n<p>Dafür musst du einen Benutzernamen wählen, der noch nicht verwendet sein darf, und ein Passwort eingeben, dass bei jeder Anmeldung benötigt werden wird.', '2010-05-08 18:55:00'),
(305, 2, 'Here you can register a new user account.</p>\r\n\r\n<p>You have to choose a user name that must not be existing yet, and a password which will be needed for each login.', '2010-05-08 18:55:00'),
(306, 1, 'Benutzername', '2010-05-08 19:03:20'),
(306, 2, 'User Name', '2010-05-08 19:04:08'),
(307, 1, 'Gib hier einen Benutzernamen ein. Wenn du dieses Formular abschickt, wird geprüft, ob er noch frei ist.', '2010-05-08 19:10:33'),
(307, 2, 'Enter a user name here. After having sent the form, you will be informed wheater it still is free.', '2010-05-08 19:10:33'),
(308, 1, 'Passwort', '2010-05-08 19:16:08'),
(308, 2, 'Password', '2010-05-08 19:16:08'),
(309, 1, 'Gib hier dein geheimes Passwort ein. Es sollte sowohl Buchstaben als auch Zahlen enthalten und mindestens sechs Stellen lang sein. Beachte, dass zwischen Groß- und Kleinschreibung unterschieden wird.', '2010-05-08 19:17:48'),
(309, 2, 'Enter your secret passwort here. It should contain both numbers and letters and not be shorter than six characters. Note that it''s case sensitive.', '2010-05-08 19:17:48'),
(310, 1, 'Passwort bestätigen', '2010-05-08 19:18:23'),
(310, 2, 'Confirm password', '2010-05-08 19:18:23'),
(311, 1, 'Gib hier dasselbe Passwort nochmal ein.', '2010-05-08 19:18:43'),
(311, 2, 'Re-enter the same password here.', '2010-05-08 19:18:43'),
(312, 1, 'E-Mail-Adresse', '2010-05-08 19:23:23'),
(312, 2, 'E-mail address', '2010-05-08 19:23:23'),
(313, 1, 'Bitte gib hier eine E-Mail-Addresse ein, auf deren Posteingang du zugriff hast.', '2010-05-08 19:24:23'),
(313, 2, 'Please enter your e-mail address.', '2010-05-08 19:24:23'),
(314, 1, 'Wenn E-Mails von dieser Seite empfangen können möchtest, wie z.B. für die Funktion &quot;Passwort vergessen&quot;, gib diese hier ein.', '2010-05-08 19:26:21'),
(314, 2, 'If you want to receive e-mails of this site, e.g. for &quot;password lost&quot; function, enter it here.', '2010-05-08 19:26:21'),
(315, 1, 'E-Mail-Addresse bestätigen', '2010-05-08 19:26:50'),
(315, 2, 'Confirm E-mail address', '2010-05-08 19:26:50'),
(316, 1, 'Gib hier die E-Mail-Addresse nochmal ein.', '2010-05-08 19:27:13'),
(316, 2, 'Re-enter the e-mail address.', '2010-05-08 19:27:13'),
(317, 1, 'Wenn du oben eine E-Mail-Adresse eingegeben hast, wiederhole diese hier.', '2010-05-08 19:27:54'),
(317, 2, 'If you inputted an e-mail address above, re-enter it here.', '2010-05-08 19:27:54'),
(318, 1, 'Registrieren', '2010-05-08 19:38:53'),
(318, 2, 'Register', '2010-05-08 19:38:53'),
(319, 1, 'Bitte gib einen Benutzernamen ein.', '2010-05-12 21:30:52'),
(319, 2, 'Please enter a user name.', '2010-05-12 21:30:52'),
(320, 1, 'Bitte wiederhole die E-Mail-Adresse im Feld "E-Mail-Adresse bestätigen".', '2010-05-12 21:46:01'),
(320, 2, 'Please re-enter your email address in "Confirm e-mail address" field.', '2010-05-12 21:46:43'),
(321, 1, 'Die beiden eingegeben E-Mail-Adressen stimmen nicht überein.', '2010-05-12 21:46:31'),
(321, 2, 'The e-mail addresses are different.', '2010-05-12 21:46:31'),
(322, 1, 'Status', '2010-05-17 20:35:53'),
(322, 2, 'State', '2010-05-17 20:35:53'),
(323, 1, 'Wähle hier aus, ob dieses Benutzerkonto aktiviert sein soll. Mit einem deaktivierten Konto kann man sich nicht anmelden.', '2010-05-17 20:37:59'),
(323, 2, 'Enable or disable this user account here. One can not log in with a disabled account.', '2010-05-17 20:37:59'),
(324, 1, 'Aktiviert', '2010-05-17 20:38:36'),
(324, 2, 'Enabled', '2010-05-17 20:38:36'),
(325, 1, 'Deaktiviert', '2010-05-17 20:38:46'),
(325, 2, 'Disabled', '2010-05-17 20:38:46'),
(326, 1, 'Deaktiviert; aktivieren, sobald E-Mail-Adresse bestätigt', '2010-05-17 20:39:22'),
(326, 2, 'Disabled; enable when e-mail address is confirmed', '2010-05-17 20:39:22'),
(327, 1, 'Folgende E-Mail-Adresse wurde für diesen Benutzer eingetragen, aber noch nicht bestätigt: <a href="mailto:{email url}">{email html}</a>.', '2010-05-17 21:08:39'),
(327, 2, 'Following e-mail address is stored for this user, but not yet confirmed: <a href="mailto:{email url}">{email html}</a>.', '2010-05-17 21:08:39'),
(328, 1, 'E-Mail-Adresse entfernen', '2010-05-17 21:12:04'),
(328, 2, 'Remove e-mail address', '2010-05-17 21:12:04'),
(329, 1, 'Bestätigen', '2010-05-17 21:12:22'),
(329, 2, 'Confirm', '2010-05-17 21:12:22'),
(330, 1, 'Herzlich Willkommen bei {organizationTitle html}, {userName html}!</p>\r\n\r\n<p>Bevor du dich mit deinen Registrierungsdaten anmelden kannst, musst deine E-Mail-Adresse bestätigt werden. Klicke dafür einfach auf den folgenden Link:</p>\r\n\r\n<p><a href="{linkURL html}">{linkURL html}</a></p>\r\n\r\n<p>Wenn du Probleme beim öffnen des Links hast, markiere die obenstehende Adresse, kopiere sie in die Zwischenablage und füge sie in der Adresszeile deines Browsers ein.</p>\r\n\r\n<p>Vielen Dank für die Registrierung!', '2010-05-21 17:21:16'),
(331, 1, 'Herzlich Willkommen bei {organizationTitle}, {userName}!\r\n\r\nBevor du dich mit deinen Registrierungsdaten anmelden kannst, musst deine E-Mail-Adresse bestätigt werden. Klicke dafür einfach auf den folgenden Link:\r\n\r\n{linkURL}\r\n\r\nWenn du Probleme beim öffnen des Links hast, markiere die obenstehende Adresse, kopiere sie in die Zwischenablage und füge sie in der Adresszeile deines Browsers ein.\r\n\r\nVielen Dank für die Registrierung!', '2010-05-21 15:36:03'),
(332, 1, 'Ein Systemfehler ist aufgetreten. Die letzte Aktion wurde möglicherweise nicht korrekt ausgeführt.</p>\r\n\r\n<p>Die Administration wurde über diesen Vorfall informiert. Wenn du Fragen hast, kontaktiere bitte diese.', '2010-05-21 16:14:56'),
(332, 2, 'A system error occured. The last action may not be finished successfully.</p>\r\n\r\n<p>Administrators have been informed about this incident. For further information, please contact them.', '2010-05-21 16:14:56'),
(333, 1, 'Es ist ein Problem beim Versenden der E-Mail-Adresse aufgetreten, die den Aktivierungscode für dein Benutzerkonto enthalten sollte. Bitte wende dich an die Administration.', '2010-05-21 17:22:58'),
(334, 1, 'Vielen Dank für die Registrierung!</p>\r\n\r\n<p>Bevor du dich mit deinen Registrierungsdaten anmelden kannst, muss deine E-Mail-Adresse bestätigt werden. In den nächsten Minuten solltest du eine E-Mail erhalten, in der die weiter nötigen Schritte erläutert sind. Bitte prüfe auch den Ordner für Spam-Verdacht, falls du innerhalb der nächsten Minuten keine E-Mail erhalten solltest.', '2010-05-21 17:28:00'),
(335, 1, 'Vielen Dank für die Registrierung!</p>\r\n\r\n<p>Du kannst dich jetzt mit deinen Registrierungsdaten anmelden. Verwende dazu einfach das unten stehende Formular.</p>\r\n\r\n<p>Deine E-Mail-Adresse wurde noch nicht bestätigt und kann daher noch nicht z.B. für die Passwort-vergessen-Funktion verwendet werden. In den nächsten Minuten solltest du eine E-Mail erhalten, in der die nötigen Schritte erläutert sind, um die E-Mail-Adresse zu bestätigen. Bitte prüfe auch den Ordner für Spam-Verdacht, falls du innerhalb der nächsten Minuten keine E-Mail erhalten solltest.', '2010-05-22 21:59:59'),
(336, 1, 'Vielen Dank für die Registrierung!</p>\r\n\r\n<p>Du kannst dich jetzt mit deinen Registrierungsdaten anmelden. Verwende dazu einfach das unten stehende Formular.</p>', '2010-05-21 21:24:40'),
(337, 1, 'Vielen Dank für die Registrierung!</p>\r\n\r\n<p>Du kannst dich jetzt mit deinen Registrierungsdaten anmelden. Verwende dazu einfach das unten stehende Formular.</p>\r\n\r\n<p>Es ist ein Problem beim Versenden der E-Mail-Adresse aufgetreten, die den Bestätigungscode für deine E-Mail-Adresse enthalten sollte. Mit E-Mail-Versand verbundene Funktionen, wie z.B. "Passwort vergessen", sind daher nicht verfügbar. Bitte wende dich an die Administration, um dieses Problem zu beheben.', '2010-05-21 22:19:10'),
(338, 1, 'Hallo {userName html}!</p>\r\n\r\n<p>Du hast gerade deine E-Mail-Adresse geändert. Damit die Änderung wirksam wird, muss sie nun bestätigt werden. Klicke dafür einfach auf den folgenden Link:</p>\r\n\r\n<p><a href="{linkURL html}">{linkURL html}</a></p>\r\n\r\n<p>Wenn du Probleme beim öffnen des Links hast, markiere die obenstehende Adresse, kopiere sie in die Zwischenablage und füge sie in der Adresszeile deines Browsers ein.', '2010-05-22 16:16:14'),
(351, 1, 'Herzlich Willkommen bei {organizationTitle html}, {userName html}!</p>\r\n\r\n<p>Damit deine E-Mail-Adresse verwendet werden kann, muss sie bestätigt werden. Klicke dafür einfach auf den folgenden Link:</p>\r\n\r\n<p><a href="{linkURL html}">{linkURL html}</a></p>\r\n\r\n<p>Wenn du Probleme beim öffnen des Links hast, markiere die obenstehende Adresse, kopiere sie in die Zwischenablage und füge sie in der Adresszeile deines Browsers ein.</p>\r\n\r\n<p>Vielen Dank für die Registrierung!', '2010-05-22 16:21:15'),
(339, 1, 'Hallo {userName}!\r\n\r\nDu hast gerade deine E-Mail-Adresse geändert. Damit die Änderung wirksam wird, muss sie nun bestätigt werden. Klicke dafür einfach auf den folgenden Link:\r\n\r\n{linkURL}\r\n\r\nWenn du Probleme beim öffnen des Links hast, markiere die obenstehende Adresse, kopiere sie in die Zwischenablage und füge sie in der Adresszeile deines Browsers ein.\r\n\r\nVielen Dank für die Registrierung!', '2010-05-22 16:18:54'),
(341, 1, 'Deine E-Mail-Adresse wurde erfolgreich bestätigt.</p>\r\n\r\n<p>Ab jetzt wirst du Funktionen durchführen können, für die eine E-Mail-Adresse benötigt wird, wie z.B. die Passwort-Vergessen-Funktion.', '2010-05-21 23:08:04'),
(342, 1, 'Deine E-Mail-Adresse wurde erfolgreich bestätigt.</p>\r\n\r\n<p>Du kannst dich jetzt mit den Registrierungsdaten anmelden. Nutze dafür einfach das unten stehende Formular.', '2010-05-21 23:07:55'),
(343, 1, 'Die Aktivierungs-Adresse, die du aufgerufen hast, ist entweder ungültig oder abgelaufen.', '2010-05-22 00:23:26'),
(344, 1, 'Es gibt keine Gruppe, in der du noch nicht Mitglied bist, der du beitreten kannst.', '2010-05-22 13:42:21'),
(345, 1, 'Wenn du dein Passwort vergessen hast, ist das kein Problem. Gib einfach deinen Benutzernamen und deine E-Mail-Adresse ein, und klicke auf Absenden. Du bekommst dann ein neues Passwort per E-Mail zugeschickt.', '2010-05-22 15:31:34'),
(346, 1, 'Benutzername', '2010-05-22 15:33:33'),
(347, 1, 'Gib hier deinen Benutzernamen ein.', '2010-05-22 15:33:33'),
(348, 1, 'E-Mail-Adresse', '2010-05-22 15:37:27'),
(349, 1, 'Gib hier die E-Mail-Adresse ein, die du bei der Registrierung verwendet hast.', '2010-05-22 15:37:27'),
(350, 1, 'Die eingegebene E-Mail-Adresse stimmt nicht mit der für diesen Benutzer eingetragenen überein, oder für den Benutzer wurde keine E-Mail-Adresse angegeben.', '2010-05-22 15:48:19'),
(352, 1, 'Hallo {userName html}!</p>\r\n\r\n<p>Du hast gerade deine E-Mail-Adresse geändert. Damit die Änderung wirksam wird, muss sie nun bestätigt werden. Klicke dafür einfach auf den folgenden Link:</p>\r\n\r\n<p><a href="{linkURL html}">{linkURL html}</a></p>\r\n\r\n<p>Wenn du Probleme beim öffnen des Links hast, markiere die obenstehende Adresse, kopiere sie in die Zwischenablage und füge sie in der Adresszeile deines Browsers ein.</p>\r\n\r\n<p>Vielen Dank für die Registrierung!</p>', '2010-05-22 21:54:10'),
(353, 1, 'Herzlich Willkommen bei {organizationTitle html}!', '2010-05-22 21:55:58'),
(354, 1, 'Herzlich Willkommen bei {organizationTitle html}!', '2010-05-22 21:56:14'),
(355, 1, 'Bestätigung der E-Mail-Adresse', '2010-05-22 22:00:04'),
(356, 1, 'Die Funktion "Password vergessen" wurde mit deinem Benutzerkonto durchgeführt.</p>\r\n\r\n<p>Wenn du das nicht warst, kannst du diese E-Mail einfach ignorieren. Melde dich beim nächsten Mal einfach ganz normal an.</p>\r\n\r\n<p>Du kannst dich jetzt mit folgendem Passwort anmelden:</p>\r\n\r\n<p><b>{password html}</b></p>\r\n\r\n<p><b>Achtung:</b> Dieses Passwort verliert nach der nächsten Anmeldung seine Gültigkeit. Ändere also dein Passwort, sobald du dich damit angemeldet hast.', '2010-05-22 22:05:53');
INSERT INTO `premanager_0_stringstranslation` (`id`, `languageID`, `value`, `timestamp`) VALUES
(357, 1, 'Die Funktion "Password vergessen" wurde mit deinem Benutzerkonto durchgeführt.\r\n\r\nWenn du das nicht warst, kannst du diese E-Mail einfach ignorieren. Melde dich beim nächsten Mal einfach ganz normal an.\r\n\r\nDu kannst dich jetzt mit folgendem Passwort anmelden:\r\n\r\n{password html}\r\n\r\nAchtung: Dieses Passwort verliert nach der nächsten Anmeldung seine Gültigkeit. Ändere also dein Passwort, sobald du dich damit angemeldet hast.', '2010-05-22 22:06:15'),
(358, 1, 'Beim Versenden der E-Mail, die das neue Passwort enthalten sollte, ist ein Problem aufgetreten. Bitte wende dich an die Administration.', '2010-05-22 22:09:42'),
(359, 1, 'In den nächsten Minuten solltest du eine E-Mail erhalten, in der das neue Passwort enthalten ist. Bitte prüfe auch den Ordner für Spam-Verdacht, falls du innerhalb der nächsten Minuten keine E-Mail erhalten solltest.', '2010-05-22 22:12:38'),
(360, 1, 'Du musst dein Passwort ändern, weil es seine Gültigkeit verloren hat! <a href="{url html}">Klicke hier, um das Passwort zu ändern.</a>', '2010-05-22 22:32:44'),
(361, 1, 'Du hast noch kein Benutzerkonto?', '2010-05-22 22:53:13'),
(362, 1, 'Klicke hier, um eines zu erstellen.', '2010-05-22 22:53:13'),
(363, 1, 'Du hast bereits ein Benutzerkonto?', '2010-05-22 22:58:51'),
(364, 1, 'Dann klicke hier, um dich anzumelden.', '2010-05-22 22:58:51'),
(365, 1, 'Du konntest nicht angemeldet werden, weil deine E-Mail-Adresse noch nicht bestätigt wurde.', '2010-05-23 23:42:42'),
(366, 1, 'Du konntest nicht angemeldet werden, weil dein Benutzerkonto deaktiviert wurde.', '2010-05-23 23:43:16'),
(367, 1, 'Benutzername', '2010-05-24 18:48:34'),
(368, 1, 'Wenn du deinen Benutzernamen ändern möchtest, gib ihn hier ein.', '2010-05-24 18:48:34'),
(369, 1, 'Wenn du die E-Mail-Adresse geändert hast, gib sie hier erneut ein.', '2010-05-24 18:50:58'),
(370, 1, 'Wenn du das Passwort ändern möchtest, gib hier das neue Passwort ein.', '2010-05-24 19:40:47'),
(371, 1, 'Wenn du das Passwort ändern möchtest, gib hier das neue Passwort erneut ein.', '2010-05-24 19:40:47'),
(372, 1, 'Nur angemeldete Benutzer können Registrierungsdaten bearbeiten.', '2010-05-24 21:29:41'),
(373, 1, 'Es ist ein Problem beim Versenden der E-Mail-Adresse aufgetreten, die den Aktivierungscode für eingegebene E-Mail-Adresse. Bitte wende dich an die Administration.', '2010-05-24 22:22:18'),
(374, 1, 'Die Änderungen an deinen Registrierungsdaten wurden gespeichert.', '2010-05-24 22:25:36'),
(375, 1, 'Dein Passwort wurde geändert. Wenn du dich das nächste Mal anmeldet, musst du das eben eingegebene Passwort benutezn.', '2010-05-24 22:25:36'),
(376, 1, 'Die neue E-Mail-Adresse wurde noch nicht bestätigt. Solange wird die Änderung deiner E-Mail-Adresse wirkungslos bleiben. In den nächsten Minuten solltest du eine E-Mail erhalten, in der die nötigen Schritte erläutert sind, um die E-Mail-Adresse zu bestätigen. Bitte prüfe auch den Ordner für Spam-Verdacht, falls du innerhalb der nächsten Minuten keine E-Mail erhalten solltest.', '2010-05-24 22:28:27'),
(377, 1, 'Zurück', '2010-05-24 22:29:54'),
(377, 2, 'Back', '2010-05-24 22:29:54'),
(378, 1, 'Verknüpfte Module', '2010-05-26 19:54:32'),
(379, 1, 'Verknüpft diese Seite mit Modulen, die Inhalte für sie bereitstellen', '2010-05-26 19:34:05'),
(380, 1, 'Hier kannst du die Seite &quot;{title html}&quot; mit Modulen verknüpfen, die Inhalte beteitstellen. Diese Module werden von Plugins registriert, sobald diese installiert werden.</p>\r\n\r\n<p>Im ersten Feld sind die Module aufgelistet, die bereits mit dieser Seite verknüpft sind. Indem du sie untereinander verschiebst, legst du fest, in welcher Reihenfolge die Module nach Inhalten abgefragt werden. Das spielt dann eine Rolle, wenn mehrere Module untergeordnete Seiten mit demselben Namen zur Verfügung stellen. In diesem Fall bekommt das oberste Modul Vorrang. Auch bei der Wahl des Seiteninhalts wird die Rangordnung beachtet.</p>\r\n\r\n<p>Wenn das gleiche Modul auf mehreren Seiten verknüpft wird, erscheint die Schaltfläche &quot;Primäre Verknüpfung einrichten&quot;. Klicke darauf, wenn die aktuelle Seite aufgerufen werden soll, sobald ein Link auf dieses Modul zeigt.</p>\r\n\r\n<p>Im unteren Feld sind die Module aufgelistet, die von dieser Seite noch nicht verwendet worden sind. Klicke eines davon an, um es hinzuzufügen.', '2010-05-26 21:56:42'),
(381, 1, 'Diese Seite wurde bisher mit keinem Modul verknüpft.', '2010-05-26 20:02:35'),
(382, 1, 'Alle verfügbaren Module sind bereits mit dieser Seite verknüpft.', '2010-05-26 20:02:35'),
(383, 1, 'Verknüpfte Module', '2010-05-26 20:03:07'),
(384, 1, 'Verfügbare Module', '2010-05-26 20:03:07'),
(385, 1, 'Seite mit Inhalten verknüpfen', '2010-05-26 20:18:33'),
(386, 1, 'Nach oben', '2010-05-26 21:54:41'),
(387, 1, 'Verschiebt diese Modulreferenz nach oben und verleiht ihr dadurch eine höhere Priorität bei der Auswahl von Inhalten und untergeordneten Seiten', '2010-05-26 21:54:41'),
(388, 1, 'Nach unten', '2010-05-26 21:55:34'),
(389, 1, 'Verschiebt diese Modulreferenz nach unten und vermindert dadurch ihre Priorität bei der Auswahl von Inhalten und untergeordneten Seiten', '2010-05-26 21:55:34'),
(390, 1, 'Primäre Verknüpfung einrichten', '2010-05-26 21:57:28'),
(391, 1, 'Klicke hier, wenn die aktuelle Seite aufgerufen werden soll, wenn auf dieses Modul verwiesen wird', '2010-05-26 21:57:28'),
(392, 1, 'Die aktuelle Seite wird aufgerufen, wenn auf dieses Modul verwiesen wird. Wähle eine andere Seite aus und klicke dort auf diese Schaltfläche, um eine andere Primärverknüpfung auszuwählen', '2010-05-26 21:58:49'),
(393, 1, 'Entfernen', '2010-05-26 22:00:13'),
(394, 1, 'Entfernt diesen Modulverweis von dieser Seite, sodass keine Inhalte des Moduls mehr auf dieser Seite angezeigt werden', '2010-05-26 22:00:13'),
(395, 1, 'Klicke hier, um dieses Modul mit der aktuellen Seite zu verknüpfen.', '2010-05-26 22:12:39'),
(396, 1, 'Diese Funktion ist nicht verfügbar, da das Modul ausschließlich mit dieser Seite verknüpft ist', '2010-05-26 23:20:42'),
(397, 1, 'Berechtigungen', '2010-05-28 21:09:45'),
(398, 1, 'Legt fest, wer diese Seite ansehen darf', '2010-05-28 21:09:45'),
(399, 1, 'Berechtigungen der Seite', '2010-05-28 21:17:02'),
(400, 1, 'Hier kannst du festlegen, wer diese Seite ansehen darf.</p>\r\n\r\n<p>Momentan kann jeder Besucher diese Seite aufrufen. Klicke auf die folgende Schaltfläche, wenn du selbst Anzeigerechte vergeben willst.</p>\r\n\r\n<p><b>Hinweis:</b> Wenn eine der übergeordneten Seiten von einem Besucher nicht aufgerufen werden kann, sind alle untergeordneten Seiten ebenfalls nicht aufrufbar.', '2010-05-29 00:40:56'),
(401, 1, 'Hier kannst du festlegen, wer diese Seite ansehen darf.</p>\r\n\r\n<p>Unten siehst du eine Liste mit Gruppen, die Anzeigerechte für diese Seite erhalten haben. Mitglieder dieser Benutzergruppen können diese Seite ansehen.</p>\r\n\r\n<p>Wenn diese Seite für alle Besucher sichtbar sein soll, klicke auf die folgende Schaltfläche.\r\n\r\n<p><b>Hinweis:</b> Wenn eine der übergeordneten Seiten von einem Besucher nicht aufgerufen werden kann, sind alle untergeordneten Seiten ebenfalls nicht aufrufbar.', '2010-05-29 00:40:56'),
(402, 1, 'Anzeigerechte konfigurieren', '2010-05-28 22:33:35'),
(403, 1, 'Anzeigerechte für jeden freigeben', '2010-05-28 22:33:35'),
(404, 1, 'Entfernen', '2010-05-29 00:23:57'),
(405, 1, 'Entzieht dieser Gruppe die Anzeigerechte für diese Seite', '2010-05-29 00:23:57'),
(406, 1, 'Hier klicken, um dieser Gruppe Anzeigerechte zu verleihen', '2010-05-29 00:24:32'),
(407, 1, 'Gruppen mit Anzeigerechten', '2010-05-29 00:28:43'),
(408, 1, 'Gruppen ohne Anzeigerechte', '2010-05-29 00:28:43'),
(409, 1, 'Bisher hat keine Gruppe Anzeigerechte', '2010-05-29 00:30:13'),
(410, 1, 'Alle Gruppen haben bereits Anzeigerechte erhalten.', '2010-05-29 00:30:13'),
(411, 1, 'Projekt erstellen', '2010-06-05 23:39:19'),
(412, 1, 'Zeigt ein Formular, mit dem ein neues Projekt hinzugefügt werden kann', '2010-06-05 23:39:19'),
(413, 1, 'Es wurden noch keine Projekte erstellt.', '2010-06-05 23:39:40'),
(414, 1, 'Bearbeiten', '2010-06-05 23:43:16'),
(415, 1, 'Zeigt ein Formular, in dem der Projekttitel und andere Meta-Informationen bearbeitet werden können', '2010-06-05 23:43:34'),
(416, 1, 'Löschen', '2010-06-05 23:44:01'),
(417, 1, 'Löscht dieses Projekt', '2010-06-05 23:44:01'),
(418, 1, 'Pseudo-Projekt der Organisation', '2010-06-10 15:50:23'),
(419, 1, 'Projekttitel', '2010-06-06 00:12:21'),
(420, 1, 'Autor / Autoren', '2010-06-06 00:12:21'),
(421, 1, 'Beschreibung', '2010-06-06 00:12:55'),
(422, 1, 'Stichwörter', '2010-06-06 00:12:55'),
(423, 1, 'Projektname', '2010-06-06 16:16:17'),
(424, 1, 'Ein eindeutiger, kurzer Name, der nur Buchstaben, Zahlen und, mit Einschränkungen, Bindestriche enthält. Kann nicht übersetzt werden', '2010-06-06 16:17:22'),
(425, 1, 'Der angezeigte Titel des Projekts', '2010-06-06 16:17:08'),
(426, 1, 'Untertitel', '2010-06-06 16:18:12'),
(427, 1, 'Ein optionaler Untertitel des Projekts', '2010-06-06 16:18:12'),
(428, 1, 'Person oder Personen, die für den Inhalt der Seite verantwortlich ist / sind', '2010-06-06 16:19:36'),
(429, 1, 'Copyright', '2010-06-06 16:21:59'),
(430, 1, 'Rechtsträger des Inhalts der Projektseiten und Datumsangaben', '2010-06-06 16:21:59'),
(431, 1, 'Ein oder zwei Sätze, die das Thema des Projekts zusammenfassen', '2010-06-06 16:22:37'),
(432, 1, 'Mit Kommas getrennte Stichwörter, die mit diesem Projekt verknüpft werden', '2010-06-06 16:23:17'),
(433, 1, 'Bitte gib einen Projektnamen ein.', '2010-06-06 17:39:09'),
(434, 1, 'Es existiert bereits ein Projekt mit diesem Namen.', '2010-06-06 17:39:09'),
(435, 1, 'Bitte gib einen Projekttitel ein.', '2010-06-06 17:41:47'),
(436, 1, 'Das Feld &quot;Autor / Autoren&quot; darf nicht leergelassen werden.', '2010-06-06 17:41:47'),
(437, 1, 'Bitte gib einen Beschreibung ein.', '2010-06-06 17:40:43'),
(438, 1, 'Bitte gib eine Copyright-Angabe ein.', '2010-06-06 17:41:37'),
(439, 1, 'Der Projektname darf nur Buchstaben (A–Z), Zahlen und Bindestriche enthalten und muss mit einem Buchstaben oder einer Zahl beginnen und enden.', '2010-06-06 17:55:08'),
(440, 1, 'Möchtest du das Projekt <a href="{url html}">{title html}</a> wirklich löschen?</p>\r\n\r\n<p><b>Warnung:</b> Wenn du fortfährst, werden auch alle Inhalte, die für dieses Projekt gespeichert wurden, gelöscht!', '2010-06-06 18:39:22'),
(441, 1, 'Wechselt zu dieser Seite', '2010-06-09 19:06:11'),
(442, 1, 'Anzeigen', '2010-06-09 19:06:11'),
(443, 1, 'Diese Seite kann nicht gelöscht werden, da sie oder eine ihrer Unterseiten mit einem Modul verknüpft ist.', '2010-07-01 15:41:11'),
(444, 1, 'Diese Seite ist bereits die übergeordnete Seite.', '2010-06-09 20:49:31'),
(445, 1, 'Eine Seite, die mit einem Modul verknüpft ist, kann keine Unterseiten enthalten.', '2010-06-09 21:03:33'),
(446, 1, 'Inhalt', '2010-06-09 21:38:29'),
(447, 1, 'Diese Seite ist mit dem Modul <b>{plugin html}.{class html}</b> verknüpft. Das bedeutet, dass die Inhalte dieser Seite und ihre untergeordneten Seiten von diesem Modul bestimmt werden.</p>\r\n\r\n<p>Um mehr über das Modul zu erfahren, suche in der Dokumentation des Plugins <b>{plugin name}</b>.', '2010-06-09 21:38:29'),
(448, 1, 'Dies ist eine Seite ohne Inhalt. Wird sie aufgerufen, werden die Seiten, die ihr untergeordnet sind, aufgelistet.', '2010-06-09 21:41:19'),
(449, 1, 'Projekt aufrufen', '2010-06-09 22:12:06'),
(450, 1, 'Öffnet die Projektseite', '2010-06-09 22:12:06'),
(451, 1, 'Das Projekt der Organisation kann nicht gelöscht werden.', '2010-06-10 15:49:49'),
(452, 1, 'Verfassen', '2010-06-11 19:20:17'),
(453, 1, 'Zeigt ein Formular, mit dem ein neuer Blog-Artikel erstellt werden kann', '2010-06-11 16:11:28'),
(454, 1, 'Vorschau', '2010-06-11 18:22:10'),
(455, 1, 'Titel', '2010-06-11 18:24:36'),
(456, 1, 'Fasse hier den Inhalt des Artikels kurz zusammen.', '2010-06-11 18:24:36'),
(457, 1, 'Text', '2010-06-11 18:25:19'),
(458, 1, 'Gib hier den Inhalt des Artikels ein.', '2010-06-11 18:25:19'),
(459, 1, 'Bitte gib einen Titel ein.', '2010-06-11 18:45:14'),
(460, 1, 'Du hast keinen Text eingegeben.', '2010-06-11 18:45:14'),
(461, 1, 'Zusammenfassung', '2010-06-11 18:58:24'),
(462, 1, 'Hier kannst du dokumentieren, welche Änderungen du vorgenommen hast.', '2010-06-11 18:58:24'),
(463, 1, 'Hier kannst du den Inhalt der Seite kurz zusammenfassen.', '2010-06-11 19:01:16'),
(464, 1, 'Der Artikel wurde erstellt', '2010-06-11 19:12:08'),
(465, 1, 'Der Blog ist leer.', '2010-06-11 19:19:36'),
(466, 1, 'Versionen', '2010-06-11 19:33:33'),
(467, 1, 'Zeigt eine Liste mit allen Versionen, die für diesen Artikel gespeichert wurden', '2010-06-11 19:33:33'),
(468, 1, 'Bearbeiten', '2010-06-11 19:35:03'),
(469, 1, 'Ermöglicht es, eine neue Version dieses Artikels zu schreiben', '2010-06-11 19:35:03'),
(470, 1, 'Dieser Artikel wurde noch nicht in deine Sprache übersetzt.', '2010-06-11 20:04:22'),
(471, 1, 'Hier siehst du eine Liste aller Versionen, die in deiner Sprache für diesen Artikel gespeichert wurden.</p>\r\n\r\n<p>Jedes Mal, wenn der Artikel bearbeitet wird, wird eine Version angelegt. Die Version, die Gäste zu sehen bekommen, wird &quot;veröffentlichte Version&quot; genannt und ist in dieser Liste hervorgehoben.</p>\r\n\r\n<p>Klicke auf das Datum, um den Text einer Version anzusehen. Dort findest du auch die Möglichkeit, diese Version als Basis für eine neue Version zu verwenden.', '2010-06-11 20:09:10'),
(472, 1, 'Klicke hier, um den Text dieser Version zu sehen', '2010-06-11 20:11:04'),
(473, 1, '#', '2010-06-11 20:18:31'),
(474, 1, 'Gespeichert', '2010-06-11 20:18:31'),
(475, 1, 'Verfasser', '2010-06-11 20:18:45'),
(476, 1, 'Zusammenfassung', '2010-06-11 20:18:45'),
(479, 1, 'Versionsgeschichte', '2010-06-11 21:03:20'),
(480, 1, 'Du betrachtest einen Artikel, der noch nicht veröffentlicht wurde. Die neuste Version wird angezeigt.', '2010-06-11 21:03:20'),
(481, 1, 'Dies ist eine alte Version dieses Artikels.', '2010-06-11 21:05:10'),
(482, 1, 'Du betrachtest einen Artikel, der noch nicht in deine Sprache übersetzt wurde. Bearbeite ihn, um ihn zu übersetzen, oder wechsle die Sprache.', '2010-06-11 21:05:10'),
(483, 1, 'Du betrachtest einen Artikel, der noch nicht veröffentlicht wurde.', '2010-06-11 21:05:34'),
(484, 1, 'Du betrachtest eine noch nicht veröffentlichte Version.', '2010-06-11 21:06:23'),
(485, 1, 'Klicke hier, um zur veröffentlichten Version zu gelangen.', '2010-06-11 21:06:48'),
(486, 1, 'Angezeigte Version', '2010-06-11 21:09:22'),
(487, 1, 'Veröffentlichte Version', '2010-06-11 21:09:22'),
(488, 1, 'Neuste Version', '2010-06-11 21:09:29'),
(489, 1, 'Veröffentlichen', '2010-06-11 22:48:11'),
(490, 1, 'Veröffentlicht diese Version des Artikels, sodass Gäste diese sehen können', '2010-06-11 22:48:11'),
(491, 1, 'Möchtest du Version <b>{revision}</b> veröffentlichen?</p>\r\n\r\n<p>Die aktuell veröffentlichte Version wird dadurch vor Gästen versteckt.', '2010-06-11 23:11:01'),
(492, 1, 'Möchtest diesen Artikel mit Version <b>{revision}</b> veröffentlichen?</p>', '2010-06-11 23:11:01'),
(493, 1, 'Du betrachtest die veröffentlichte Version.', '2010-06-11 23:17:50'),
(494, 1, 'Version des Basistextes', '2010-06-11 23:36:36'),
(495, 1, 'Verstecken', '2010-06-11 23:58:54'),
(496, 1, 'Versteckt diesen Artikel vor Gästen und macht damit die Funktion &quot;Veröffentlichen&quot; rückgängig', '2010-06-11 23:58:54'),
(497, 1, 'Möchtest du diesen Artikel vor Gästen verstecken?</p>\r\n\r\n<p>Er wird dann nur noch von Benutzern angesehen werden können, die das Recht zum Bearbeiten von Artikeln haben.', '2010-06-12 00:02:50'),
(498, 1, 'Diese Version ist momentan veröffentlicht', '2010-06-12 00:04:35'),
(499, 1, 'Löschen', '2010-06-12 13:34:38'),
(500, 1, 'Löscht diesen Artikel und alle früheren Versionen unwiderruflich', '2010-06-12 13:34:38'),
(501, 1, 'Möchtest du diesen Artikel wirklich endgültig löschen? Wenn du fortfährst, werden auch alle alten Versionen dieses Artikels gelöscht.</p>\r\n\r\n<p>Wenn der Artikel nur ausgeblendet werden soll, kannst du auch die Funktion &quot;Verstecken&quot; verwenden.', '2010-06-12 13:53:26'),
(502, 1, 'Vorschau', '2010-06-18 19:49:08'),
(504, 1, 'PML', '2010-06-20 20:37:35'),
(505, 1, 'Eigenen Avatar auswählen', '2010-06-26 21:39:22'),
(506, 1, 'Du kannst nur deinen eigenen Avatar, nicht den von anderen Benutzern, ändern.', '2010-06-26 21:47:26'),
(507, 1, 'Ein Avatar ist ein kleines Bild, das auf dem Profil und oft neben dem Benutzernamen angezeigt wird.</p>\r\n\r\n<p>Hier kannst du deinen Avatar ändern. Klicke dazu unter &quot;Neuer Avatar&quot; auf &quot;Durchsuchen...&quot;, wähle eine Bilddatei aus und bestätige dann mit der Schaltfläche &quot;Avatar hochladen&quot;.</p>\r\n\r\n<p>Es werden diverse Dateitypen unterstützt, darunter JPEG und PNG. Sollte der ausgewählte Avatar zu groß sein, wird er automatisch verkleinert.</p>\r\n\r\n<p><b>Hinweis:</b> Möglicherweise muss der Browser-Cache geleert werden, wenn ein neuer Avatar hochgeladen wurde.', '2010-06-27 21:52:20'),
(508, 1, 'Ein Avatar ist ein kleines Bild, das auf dem Profil und oft neben dem Benutzernamen angezeigt wird.</p>\r\n\r\n<p>Hier kannst du den Avatar des Benutzers &quot;{userName html}&quot; ändern. Klicke dazu unter &quot;Neuer Avatar&quot; auf &quot;Durchsuchen...&quot;, wähle eine Bilddatei aus und bestätige dann mit der Schaltfläche &quot;Avatar hochladen&quot;.</p>\r\n\r\n<p>Es werden diverse Dateitypen unterstützt, darunter JPEG und PNG. Sollte der ausgewählte Avatar zu groß sein, wird er automatisch verkleinert.</p>\r\n\r\n<p><b>Hinweis:</b> Möglicherweise muss der Browser-Cache geleert werden, wenn ein neuer Avatar hochgeladen wurde.', '2010-06-27 21:52:20'),
(509, 1, 'Ein Avatar ist ein kleines Bild, das auf deinem Profil und oft neben deinem Benutzernamen angezeigt wird.</p>\r\n\r\n<p>Hier kannst du einen Avatar hochladen. Klicke dazu unter &quot;Avatar&quot; auf &quot;Durchsuchen...&quot;, wähle eine Bilddatei aus und bestätige dann mit der Schaltfläche &quot;Avatar hochladen&quot;.</p>\r\n\r\n<p>Es werden diverse Dateitypen unterstützt, darunter JPEG und PNG. Sollte der ausgewählte Avatar zu groß sein, wird er automatisch verkleinert.</p>\r\n\r\n<p><b>Hinweis:</b> Möglicherweise muss der Browser-Cache geleert werden, wenn ein Avatar hochgeladen wurde.', '2010-06-27 21:52:20'),
(510, 1, 'Ein Avatar ist ein kleines Bild, das auf dem Profil und oft neben dem Benutzernamen angezeigt wird.</p>\r\n\r\n<p>Hier kannst du einen Avatar für den Benutzer &quot;{userName html}&quot; hochladen. Klicke dazu unter &quot;Avatar&quot; auf &quot;Durchsuchen...&quot;, wähle eine Bilddatei aus und bestätige dann mit der Schaltfläche &quot;Avatar hochladen&quot;.</p>\r\n\r\n<p>Es werden diverse Dateitypen unterstützt, darunter JPEG und PNG. Sollte der ausgewählte Avatar zu groß sein, wird er automatisch verkleinert.</p>\r\n\r\n<p><b>Hinweis:</b> Möglicherweise muss der Browser-Cache geleert werden, wenn ein Avatar hochgeladen wurde.', '2010-06-27 21:52:20'),
(511, 1, 'Aktueller Avatar', '2010-06-27 14:19:50'),
(512, 1, 'Dieses Bild ist der aktueller Avatar.', '2010-06-27 14:19:50'),
(513, 1, 'Neuer Avatar', '2010-06-27 14:39:55'),
(514, 1, 'Avatar', '2010-06-27 14:39:55'),
(515, 1, 'Klicke auf &quot;Durchsuchen...&quot;, um eine Bilddatei auszuwählen.', '2010-06-27 14:48:05'),
(516, 1, 'Klicke auf &quot;Durchsuchen...&quot;, um eine Bilddatei auszuwählen.', '2010-06-27 14:48:05'),
(517, 1, 'Operationen', '2010-06-27 15:28:35'),
(518, 1, 'Avatar hochladen', '2010-06-27 15:34:16'),
(519, 1, 'Avatar löschen', '2010-06-27 15:34:16'),
(520, 1, 'Du hast keine Datei ausgewählt.', '2010-06-27 15:52:55'),
(521, 1, 'Die hochgeladene Datei ist keine gültige Bilddatei, oder das Format wird nicht unterstützt.', '2010-06-27 15:55:24'),
(522, 1, 'Avatar', '2010-06-27 21:45:04'),
(523, 1, 'Bietet die Möglichkeit, den Avatar dieses Benutzers zu ändern', '2010-06-27 21:45:04'),
(524, 1, 'Stattdessen die Seite selbst gestalten', '2010-07-01 15:07:35'),
(525, 1, 'Stattdessen eine einfache Liste anzeigen', '2010-07-01 15:07:35'),
(526, 1, 'Dies ist eine Seite, deren Darstellung frei gestaltet werden kann.</p>\r\n\r\n<p>Um sie zu bearbeiten, stelle sicher, dass JavaScript in deinem Browser aktiviert ist und rufe die Seite dann (z.B. über die Schaltfläche &quot;Anzeigen&quot; in der Symbolleiste an.', '2010-07-01 15:10:35'),
(527, 1, 'Möchtest du anstatt einer Auflistung der Unterseiten eine selbst gestaltete Darstellung für diese Seite auswählen?', '2010-07-01 15:48:07'),
(528, 1, 'Möchtest du, dass diese Seite einfach ihre Unterseiten auflistet?<p>\r\n\r\n<p>Die aktuelle Konfiguration wird dabei nicht gelöscht, sondern nur versteckt. Wenn du die selbst gestaltete Darstellung wieder auswählst, wird sie wiederhergestellt.', '2010-07-01 15:48:07'),
(535, 1, '{num} Sek', '2010-09-10 18:16:43'),
(536, 1, '{num} Min', '2010-09-10 18:16:43'),
(537, 1, '{num} Std', '2010-09-10 18:16:43'),
(538, 1, '{num} Tag{''e if(num!=1)}''', '2010-09-10 18:16:43'),
(539, 1, '{num} Monat{''e if(num!=1)}', '2010-09-10 18:16:43'),
(540, 1, '{num} Jahr{''e if(num!=1)}', '2010-09-10 18:16:43'),
(534, 1, 'in {num} Jahr{''en if(num!=1)}', '2010-09-10 18:16:43'),
(533, 1, 'in {num} Monat{''en if(num!=1)}', '2010-09-10 18:16:43'),
(532, 1, 'in {num} Tag{''en if(num!=1)}', '2010-09-10 18:16:43'),
(531, 1, 'in {num} Stunde{''n if(num!=1)}', '2010-09-10 18:16:43'),
(530, 1, 'in {num} Minute{''n if(num!=1)}', '2010-09-10 18:16:43'),
(529, 1, 'in {num} Sekunde{''n if(num!=1)}', '2010-09-10 18:16:43'),
(541, 1, '{num} Sekunde{''n if(num!=1)}', '2010-09-10 21:21:11'),
(542, 1, '{num} Minute{\\''n if(num!=1)}', '2010-09-10 21:25:08'),
(543, 1, '{num} Stunde{\\''n if(num!=1)}', '2010-09-10 21:25:18'),
(544, 1, '{num} Tag{\\''e if(num!=1)}', '2010-09-10 21:25:28'),
(545, 1, '{num} Monat{\\''e if(num!=1)}', '2010-09-10 21:25:36'),
(546, 1, '{num} Jahr{\\''e if(num!=1)}', '2010-09-10 21:25:43'),
(547, 1, '{num} Sek', '2010-09-10 21:26:04'),
(548, 1, '{num} Min', '2010-09-10 21:26:12'),
(549, 1, '{num} Std', '2010-09-10 21:26:20'),
(550, 1, '{num} Tag{\\''e if(num!=1)}', '2010-09-10 21:26:28'),
(551, 1, '{num} Monat{\\''e if(num!=1)}', '2010-09-10 21:26:34'),
(552, 1, '{num} Jahr{\\''e if(num!=1)}', '2010-09-10 21:26:42'),
(553, 1, 'Du bist momentan als {userName html} angemeldet. Wenn du dich unter einem anderen Namen anmelden willst, musst du dich zuerst abmelden.', '2010-09-26 18:16:55'),
(554, 1, 'Abmelden', '2010-09-26 18:18:52'),
(555, 1, 'Zurück zur zuletzt besuchten Seite', '2010-09-26 19:23:37'),
(556, 1, 'Du bist jetzt als "{userName html}" angemeldet.', '2010-09-26 18:40:57'),
(558, 1, 'Anmeldung', '2010-10-06 21:30:16'),
(557, 1, 'Du bist jetzt abgemeldet.', '2010-09-26 19:20:13'),
(559, 1, 'Abmeldung', '2010-10-06 21:30:22'),
(560, 1, 'Hier sind alle Benutzergruppen aufgelistet.', '2010-10-06 21:39:36'),
(561, 1, 'Gruppenname', '2010-10-06 21:43:01'),
(562, 1, 'Anzahl der Mitglieder', '2010-10-06 21:43:07'),
(563, 1, 'Anzahl der Mitglieder', '2010-10-06 21:54:12'),
(564, 1, '(Backend-Seite)', '2010-11-03 13:33:43');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_styles`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `isDefault` tinyint(1) NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`,`isDefault`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- RELATIONEN DER TABELLE `premanager_0_styles`:
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_styles`
--

INSERT INTO `premanager_0_styles` (`id`, `pluginID`, `isDefault`, `class`, `timestamp`) VALUES
(1, 1, 1, 'Premanager\\Styles\\ClassicStyle', '2010-10-07 19:42:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_trees`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 20:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_trees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

--
-- RELATIONEN DER TABELLE `premanager_0_trees`:
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_trees`
--

INSERT INTO `premanager_0_trees` (`id`, `pluginID`, `class`, `timestamp`) VALUES
(1, 0, 'Premanager\\Pages\\UsersPage', '2010-10-07 21:05:06'),
(4, 0, 'Premanager\\Pages\\LoginPage', '2010-10-07 21:05:06'),
(15, 0, 'Premanager\\Pages\\GroupsPage', '2010-10-07 21:05:06');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_usergroup`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_usergroup` (
  `userID` int(10) unsigned NOT NULL,
  `groupID` int(10) unsigned NOT NULL,
  `isLeader` tinyint(1) NOT NULL DEFAULT '0',
  `joinTime` datetime NOT NULL,
  `joinIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`,`groupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_usergroup`:
--   `groupID`
--       `premanager_0_groups` -> `id`
--   `userID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_0_usergroup`
--

INSERT INTO `premanager_0_usergroup` (`userID`, `groupID`, `isLeader`, `joinTime`, `joinIP`, `timestamp`) VALUES
(0, 1, 0, '2010-04-28 19:49:28', '127.0.0.1', '2010-04-28 19:49:28'),
(2, 2, 1, '2010-04-25 14:03:27', '127.0.0.1', '2010-04-25 16:17:54'),
(2, 6, 1, '2010-04-26 21:36:03', '127.0.0.1', '2010-04-28 21:52:34'),
(2, 3, 1, '2010-04-28 21:29:02', '127.0.0.1', '2010-04-28 21:52:33'),
(2, 11, 1, '2010-06-11 13:48:24', '127.0.0.1', '2010-06-11 15:48:24'),
(2, 12, 1, '2010-06-11 13:53:56', '127.0.0.1', '2010-06-11 15:53:56'),
(70, 2, 0, '2010-06-11 22:48:03', '93.192.60.138', '2010-06-11 22:48:03'),
(70, 11, 0, '2010-06-11 22:49:45', '93.192.17.163', '2010-06-11 22:49:45'),
(70, 12, 0, '2010-06-11 22:49:48', '93.192.17.163', '2010-06-11 22:49:48');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_useroptions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_useroptions` (
  `optionID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`optionID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_useroptions`:
--   `optionID`
--       `premanager_0_options` -> `id`
--

--
-- Daten für Tabelle `premanager_0_useroptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_users`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 10. Oktober 2010 um 21:24
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `registrationTime` datetime NOT NULL,
  `registrationIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastLoginTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastVisibleLoginTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastLoginIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `secondaryPassword` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `secondaryPasswordStartTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `secondaryPasswordExpirationTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `secondaryPasswordStartIP` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `color` char(6) COLLATE utf8_bin NOT NULL DEFAULT '000000',
  `email` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `unconfirmedEmail` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `unconfirmedEmailStartTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `unconfirmedEmailKey` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `status` enum('disabled','waitForEmail','enabled') COLLATE utf8_bin NOT NULL,
  `isBot` tinyint(1) NOT NULL DEFAULT '0',
  `botIdentifier` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `hasPersonalSidebar` int(1) NOT NULL DEFAULT '0',
  `hasAvatar` tinyint(1) NOT NULL DEFAULT '0',
  `avatarMIME` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=71 ;

--
-- Daten für Tabelle `premanager_0_users`
--

INSERT INTO `premanager_0_users` (`id`, `name`, `registrationTime`, `registrationIP`, `lastLoginTime`, `lastVisibleLoginTime`, `lastLoginIP`, `password`, `secondaryPassword`, `secondaryPasswordStartTime`, `secondaryPasswordExpirationTime`, `secondaryPasswordStartIP`, `color`, `email`, `unconfirmedEmail`, `unconfirmedEmailStartTime`, `unconfirmedEmailKey`, `status`, `isBot`, `botIdentifier`, `hasPersonalSidebar`, `hasAvatar`, `avatarMIME`, `timestamp`) VALUES
(0, 'Guest', '2010-02-13 18:25:43', '127.0.0.1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', 'd46c3c951f16f75d18c2a05ed7481f0714ddad92e7192024549c69385a3dc394', '2010-05-22 22:23:05', '2010-05-24 22:23:05', '127.0.0.1', '5C5C5C', '', '', '0000-00-00 00:00:00', '', 'enabled', 0, '', 0, 1, 'image/png', '2010-10-07 21:29:57'),
(2, 'Jan', '2010-02-13 18:27:13', '127.0.0.1', '2010-10-10 20:05:43', '2010-10-10 20:05:43', '127.0.0.1', 'abf342b4aa81567e3b3d05629961a1598111470658e69d7fce7bc841413cff98', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '006600', 'info@yogularm.de', '', '2010-05-24 21:19:05', '4PfhJMFZ', 'enabled', 0, '', 0, 1, 'image/png', '2010-10-10 22:05:19'),
(70, 'Marc', '2010-06-11 22:48:03', '93.192.60.138', '2010-06-11 22:56:40', '2010-06-11 22:56:40', '93.192.60.138', '92f50201d5d704933e7ec802d7b5425e0a7a831af3bf5eff77e7c745e02f1913', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '000000', 'starwarsfan32@web.de', '', '0000-00-00 00:00:00', '', 'enabled', 0, '', 0, 0, '', '2010-07-04 00:12:19');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_usersname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_usersname` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=75 ;

--
-- RELATIONEN DER TABELLE `premanager_0_usersname`:
--   `id`
--       `premanager_0_users` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_usersname`
--

INSERT INTO `premanager_0_usersname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 2, 'jan', 1, 1, '2010-05-24 19:50:20'),
(2, 2, 'yogu', 0, 0, '2010-04-23 22:31:27'),
(4, 0, 'guest', 2, 1, '2010-05-24 19:58:28'),
(5, 0, 'anonymous', 0, 0, '2010-04-23 22:31:27'),
(74, 0, 'invité', 3, 1, '2010-05-24 21:13:23'),
(21, 70, 'marc', 1, 1, '2010-06-12 13:42:56'),
(73, 0, 'gast', 1, 1, '2010-05-24 21:14:44');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_userstranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_userstranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_0_userstranslation`:
--   `id`
--       `premanager_0_users` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_0_userstranslation`
--

INSERT INTO `premanager_0_userstranslation` (`id`, `languageID`, `title`, `timestamp`) VALUES
(0, 1, 'Gast', '2010-07-04 12:28:28'),
(6, 1, '', '2010-04-23 22:42:05'),
(9, 1, '', '2010-04-23 22:43:59'),
(12, 1, '', '2010-04-23 22:47:22'),
(18, 1, '', '2010-04-25 16:20:31'),
(0, 2, 'Gast', '2010-07-04 12:28:28'),
(2, 1, 'Administrator', '2010-07-04 12:28:28'),
(2, 2, 'Administrator', '2010-07-04 12:28:28'),
(70, 1, 'Registrierter Benutzer', '2010-07-04 12:28:28'),
(70, 2, 'Registrierter Benutzer', '2010-07-04 12:28:28'),
(70, 3, 'Registrierter Benutzer', '2010-07-04 12:28:28'),
(0, 3, 'Gast', '2010-07-04 12:28:28'),
(2, 3, 'Administrator', '2010-07-04 12:28:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_0_widgets`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_0_widgets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pluginID` int(10) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pluginID` (`pluginID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `premanager_0_widgets`:
--   `pluginID`
--       `premanager_0_plugins` -> `id`
--

--
-- Daten für Tabelle `premanager_0_widgets`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_2_articles`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_2_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createTime` datetime NOT NULL,
  `editTime` datetime NOT NULL,
  `editTimes` int(10) unsigned NOT NULL,
  `creatorID` int(10) unsigned NOT NULL,
  `creatorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `editorID` int(10) unsigned NOT NULL,
  `editorIP` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `createTime` (`createTime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- RELATIONEN DER TABELLE `premanager_2_articles`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_2_articles`
--

INSERT INTO `premanager_2_articles` (`id`, `createTime`, `editTime`, `editTimes`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `timestamp`) VALUES
(1, '2010-06-11 17:20:47', '2010-06-18 17:55:39', 15, 2, '127.0.0.1', 2, '127.0.0.1', '2010-06-18 19:55:39'),
(2, '2010-06-11 22:31:56', '2010-06-18 17:42:18', 11, 2, '127.0.0.1', 2, '127.0.0.1', '2010-06-18 19:42:18'),
(4, '2010-06-12 13:30:32', '2010-06-18 17:29:48', 3, 2, '127.0.0.1', 2, '127.0.0.1', '2010-06-18 19:29:48'),
(6, '2010-06-18 18:06:01', '2010-06-18 18:06:34', 2, 2, '127.0.0.1', 2, '127.0.0.1', '2010-06-18 20:06:34'),
(7, '2010-06-19 10:04:34', '2010-06-19 10:05:03', 3, 2, '127.0.0.1', 2, '127.0.0.1', '2010-06-19 12:05:03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_2_articlesname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_2_articlesname` (
  `nameID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `inUse` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nameID`),
  UNIQUE KEY `name` (`name`),
  KEY `articleID` (`id`),
  KEY `langaugeID` (`languageID`),
  KEY `inUse` (`inUse`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- RELATIONEN DER TABELLE `premanager_2_articlesname`:
--   `id`
--       `premanager_2_articles` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_2_articlesname`
--

INSERT INTO `premanager_2_articlesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 1, 'blog-funktion-im-aufbau', 1, 1, '2010-06-11 19:20:47'),
(2, 2, 'und-so-gehts', 1, 1, '2010-06-12 00:31:56'),
(4, 4, 'der-erste-wysiwyg-artikel', 1, 1, '2010-06-12 15:30:32'),
(6, 6, 'premanager-markup-language-integriert', 1, 1, '2010-06-18 20:06:01'),
(7, 7, 'ein-test', 1, 1, '2010-06-19 12:04:34');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_2_articlestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_2_articlestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `publishedRevisionID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_2_articlestranslation`:
--   `languageID`
--       `premanager_0_languages` -> `id`
--   `publishedRevisionID`
--       `premanager_2_revisions` -> `id`
--

--
-- Daten für Tabelle `premanager_2_articlestranslation`
--

INSERT INTO `premanager_2_articlestranslation` (`id`, `languageID`, `publishedRevisionID`, `name`, `title`, `timestamp`) VALUES
(1, 1, 3, 'blog-funktion-im-aufbau', 'Blog-Funktion im Aufbau', '2010-06-12 00:16:43'),
(2, 1, 11, 'und-so-gehts', 'Und so geht''s', '2010-06-18 19:42:18'),
(4, 1, 0, 'der-erste-wysiwyg-artikel', 'Der Erste Wysiwyg-Artikel', '2010-06-12 15:30:32'),
(6, 1, 13, 'premanager-markup-language-integriert', 'Premanager Markup Language integriert', '2010-06-18 20:06:34'),
(7, 1, 0, 'ein-test', 'Ein Test', '2010-06-19 12:04:34');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_2_revisions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
--

CREATE TABLE IF NOT EXISTS `premanager_2_revisions` (
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
  UNIQUE KEY `articleRevisionLanguage` (`articleID`,`revision`,`languageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

--
-- RELATIONEN DER TABELLE `premanager_2_revisions`:
--   `articleID`
--       `premanager_2_articles` -> `id`
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_2_revisions`
--

INSERT INTO `premanager_2_revisions` (`id`, `articleID`, `revision`, `languageID`, `createTime`, `creatorID`, `creatorIP`, `text`, `summary`, `timestamp`) VALUES
(1, 1, 1, 1, '2010-06-11 17:20:47', 2, '127.0.0.1', 'Heyho!\r\n\r\nDie Blog-Verfassen-Funktion ist jetzt fast fertig, das Bearbeiten kommt auch noch.\r\n\r\nGrüße', 'Der Artikel wurde erstellt', '2010-06-11 19:20:47'),
(2, 1, 2, 1, '2010-06-11 20:25:16', 2, '127.0.0.1', 'Heyho!\r\n\r\nDie Blog-Verfassen-Funktion ist jetzt fast fertig, das Bearbeiten kommt auch noch.\r\n\r\nGrüße\r\n\r\nPS: Jetzt kommt auch noch das Bearbeiten! :-)', 'Hinweis zur Bearbeitungs-Funktion hinzugefügt', '2010-06-11 22:25:16'),
(3, 1, 3, 1, '2010-06-11 20:26:37', 2, '127.0.0.1', 'Heyho!\r\n\r\nDie Blog-Verfassen-Funktion ist jetzt fast fertig, das Bearbeiten kommt auch noch.\r\n\r\nGrüße\r\n\r\nPS: Jetzt kommt auch noch das Bearbeiten! :-)\r\n\r\nPPS: Die Bearbeitungs-Funktion klappt sogar.', 'Information zur Funktionstüchtigkeit der Bearbeitungs-Funktion', '2010-06-11 22:26:37'),
(4, 2, 1, 1, '2010-06-11 22:31:56', 2, '127.0.0.1', 'Hallo,\r\n\r\nich möchte hier kurz erklären, wie der Blog im allgemeinen Funktioniert.\r\n\r\nÜber die Schaltfläche "Verfassen" in der Blogübersicht kannst du einen neuen Artikel erstellen. Artikel haben einen Titel und einen Text; und beides kann übersetzt werden.\r\n\r\nSchickst du das Formular ab, ist der Artikel vorerst nur für Redakteure sichtbar. Über die Schaltfläche "Bearbeiten" kann er verändert werden. Das besondere: Die alte Version bleibt bestehen. Mit dem Knopf "Versionen" siehst du eine Liste aller Versionen. Durch Anklicken des Datums wird die jeweilige Version angezeigt.\r\n\r\nUm den Artikel Gästen sichtbar zu machen, muss eine Version veröffentlicht werden. Um das tun zu können, muss man Moderator sein. Dann findet sich in der Versionsansicht eine Schaltfläche zum Veröffentlichen einer bestimmten Version (fahre mit der Maus über den Listeneintrag, um den Button sichtbar zu machen [muss man hier häufig tun]). Außerdem kann in der Artikelansicht die aktuelle Version veröffentlicht werden (Knopf ganz oben).\r\n\r\nWenn eine Version veröffentlicht wurde, ist es möglich, weitere, nicht veröffentlichte, Versionen zu erstellen, hier und da etwas rumzubasteln. Veröffentlicht werden diese Änderungen wie oben beschrieben.\r\n\r\nMöchte man irgendwann einen veröffentlichten Artikel ganz aus dem Netz nehmen, sodass er nur noch für Redakteure sichtbar ist, verwendet man die Schaltfäche "Verstecken". Das ist keine Zauberei; die Funktion gibt einfach an, dass keine Version mehr öffentlich ist. Und deshalb wird der Artikel für Gäste gar nicht angezeigt.\r\n\r\nDas war''s soweit, Funktionen folgen :-)\r\n\r\nDanke für''s Lesen und Weiterbilden,\r\nJan', 'Der Artikel wurde erstellt', '2010-06-12 00:31:56'),
(7, 1, 4, 1, '2010-06-12 13:01:42', 2, '127.0.0.1', '<p>\r\n	Heyho!\r\n</p>\r\n<p>\r\n	Die Blog-Verfassen-Funktion ist jetzt fast fertig, das Bearbeiten kommt auch noch.\r\n</p>\r\n<p>\r\n	Grüße\r\n</p>\r\n<p>\r\n	PS: Jetzt kommt auch noch das Bearbeiten! :-)\r\n</p>\r\n<p>\r\n	PPS: Die Bearbeitungs-Funktion klappt sogar.\r\n</p>\r\n<p>\r\n	PPPS: Jetzt mit Wysiwyg-Editor :-)\r\n</p>', 'An XML-Format angepasst', '2010-06-12 15:01:42'),
(8, 4, 1, 1, '2010-06-12 13:30:32', 2, '127.0.0.1', '<p>\r\n	Hallo!\r\n</p>\r\n<p>\r\n	Ich schreibe hier gerade meinen ersten Wysiwyg-Artikel. Das heißt, ich benutze einen Editor, in dem ich formatieren kann, wie man es von Textverarbeitungsprogrammen wie Microsoft Word oder OpenOffice.org Writer gewöhnt ist.\r\n</p>\r\n<p>\r\n	Nachfolgend die bisherigen Formatierungsmöglichkeiten:\r\n</p>\r\n<ul>\r\n	<li>\r\n		<p>\r\n			Überschriften\r\n		</p>\r\n	</li>\r\n	<li>\r\n		<p>\r\n			Listen\r\n		</p>\r\n	</li>\r\n	<li>\r\n		<p>\r\n			Links\r\n		</p>\r\n	</li>\r\n</ul>\r\n<h2>\r\n	Überschriften\r\n</h2>\r\n<p>\r\n	Es gibt drei Typen von Überschriften, in drei verschiedenen Größen.\r\n</p>\r\n<h2>\r\n	Listen\r\n</h2>\r\n<p>\r\n	Du kannst zwischen Aufzählungen (mit Punkten) und nummerierten Listen (mit Zahlen) wählen.\r\n</p>\r\n<h2>\r\n	Links\r\n</h2>\r\n<p>\r\n	Bisher sind nur Verweise auf externe Seiten möglich. Beispiel: \r\n	<a href="http://www.juvenile-studios.de">\r\n		 Juvenile Studios\r\n	</a>\r\n</p>\r\n<p>\r\n	Das war''s soweit, weitere Funktionen folgen.\r\n</p>\r\n<p>\r\n	Grüße,\r\n	<br />\r\n	Jan\r\n</p>', 'Der Artikel wurde erstellt', '2010-06-12 15:30:32'),
(12, 1, 5, 1, '2010-06-18 17:55:39', 2, '127.0.0.1', '<p>Heyho!</p>\r\n<p>Die Blog-Verfassen-Funktion ist jetzt fast fertig, das Bearbeiten kommt auch noch.</p>\r\n<p>Grüße</p>\r\n<p>PS: Jetzt kommt auch noch das Bearbeiten! :-)</p>\r\n<p>PPS: Die Bearbeitungs-Funktion klappt sogar.</p>\r\n<p>PPPS: Jetzt mit Wysiwyg-Editor :-)</p>', 'Erneut abgeschickt', '2010-06-18 19:55:39'),
(10, 4, 2, 1, '2010-06-18 17:29:48', 2, '127.0.0.1', '<p>Hallo!</p>\r\n<p>Ich schreibe hier gerade meinen ersten Wysiwyg-Artikel. Das heißt, ich benutze einen Editor, in dem ich formatieren kann, wie man es von Textverarbeitungsprogrammen wie Microsoft Word oder OpenOffice.org Writer gewöhnt ist.</p>\r\n<p>Nachfolgend die bisherigen Formatierungsmöglichkeiten:</p>\r\n<ul>\r\n	<li>\r\n		<p>Überschriften</p>\r\n	</li>\r\n	<li>\r\n		<p>Listen</p>\r\n	</li>\r\n	<li>\r\n		<p>Links</p>\r\n	</li>\r\n</ul>\r\n<h2>\r\nÜberschriften</h2>\r\n<p>Es gibt drei Typen von Überschriften, in drei verschiedenen Größen.</p>\r\n<h2>\r\nListen</h2>\r\n<p>Du kannst zwischen Aufzählungen (mit Punkten) und nummerierten Listen (mit Zahlen) wählen.</p>\r\n<h2>\r\nLinks</h2>\r\n<p>Bisher sind nur Verweise auf externe Seiten möglich. Beispiel: <a href="http://www.juvenile-studios.de"> Juvenile Studios</a></p>\r\n<p>Das war''s soweit, weitere Funktionen folgen.</p>\r\n<p>Grüße, <br /> Jan</p>', 'Erneut abgeschickt', '2010-06-18 19:29:48'),
(11, 2, 2, 1, '2010-06-18 17:42:06', 2, '127.0.0.1', '<p>Hallo,</p>\r\n<p>ich möchte hier kurz erklären, wie der Blog im allgemeinen Funktioniert.</p>\r\n<p>Über die Schaltfläche &quot;Verfassen&quot; in der Blogübersicht kannst du einen neuen Artikel erstellen. Artikel haben einen Titel und einen Text; und beides kann übersetzt werden.</p>\r\n<p>Schickst du das Formular ab, ist der Artikel vorerst nur für Redakteure sichtbar. Über die Schaltfläche &quot;Bearbeiten&quot; kann er verändert werden. Das besondere: Die alte Version bleibt bestehen. Mit dem Knopf &quot;Versionen&quot; siehst du eine Liste aller Versionen. Durch Anklicken des Datums wird die jeweilige Version angezeigt.</p>\r\n<p>Um den Artikel Gästen sichtbar zu machen, muss eine Version veröffentlicht werden. Um das tun zu können, muss man Moderator sein. Dann findet sich in der Versionsansicht eine Schaltfläche zum Veröffentlichen einer bestimmten Version (fahre mit der Maus über den Listeneintrag, um den Button sichtbar zu machen [muss man hier häufig tun]). Außerdem kann in der Artikelansicht die aktuelle Version veröffentlicht werden (Knopf ganz oben).</p>\r\n<p>Wenn eine Version veröffentlicht wurde, ist es möglich, weitere, nicht veröffentlichte, Versionen zu erstellen, hier und da etwas rumzubasteln. Veröffentlicht werden diese Änderungen wie oben beschrieben.</p>\r\n<p>Möchte man irgendwann einen veröffentlichten Artikel ganz aus dem Netz nehmen, sodass er nur noch für Redakteure sichtbar ist, verwendet man die Schaltfäche &quot;Verstecken&quot;. Das ist keine Zauberei; die Funktion gibt einfach an, dass keine Version mehr öffentlich ist. Und deshalb wird der Artikel für Gäste gar nicht angezeigt.</p>\r\n<p>Das war''s soweit, Funktionen folgen :-)</p>\r\n<p>Danke für''s Lesen und Weiterbilden,<br />Jan</p>', 'Konvertiert in PML', '2010-06-18 19:42:06'),
(13, 6, 1, 1, '2010-06-18 18:06:01', 2, '127.0.0.1', '<p>Hallo,</p>\r\n<p>soeben habe ich den aktuellen Stand der Premanager Markup Language eingebaut. Dabei handelt es sich um eine auf <a href="http://de.wikipedia.org/wiki/Extensible_Markup_Language">XML</a> basierende Sprache, in der formatierte Texte gespeichert werden. Im <a href="http://letsrack.juvenile-studios.de/de/blog/der-erste-wysiwyg-artikel">WYSIWYG-Editor</a> war sie schon in Verwendung, jetzt aber wird sie vollends unterstützt, denn der im PML-Format gespeicherte Text wird nun  beim Anzeigen  in <a href="http://de.wikipedia.org/wiki/Hypertext_Markup_Language">HTML</a>-Code umgewandelt, den jeder Browser versteht.</p>\r\n<p>Grüße,<br />Jan</p>', 'Der Artikel wurde erstellt', '2010-06-18 20:06:01'),
(14, 7, 1, 1, '2010-06-19 10:04:34', 2, '127.0.0.1', 'Ohne Javascript', 'Der Artikel wurde erstellt', '2010-06-19 12:04:34'),
(15, 7, 2, 1, '2010-06-19 10:05:03', 2, '127.0.0.1', 'Ohne Javascript<p><p>a</p></p>', '', '2010-06-19 12:05:03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_5_creativepages`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_5_creativepages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nodeID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nodeID` (`nodeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- RELATIONEN DER TABELLE `premanager_5_creativepages`:
--   `nodeID`
--       `premanager_0_nodes` -> `id`
--

--
-- Daten für Tabelle `premanager_5_creativepages`
--

INSERT INTO `premanager_5_creativepages` (`id`, `nodeID`, `timestamp`) VALUES
(1, 0, '2010-03-31 22:53:40');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_5_creativepagestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
--

CREATE TABLE IF NOT EXISTS `premanager_5_creativepagestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_5_creativepagestranslation`:
--   `id`
--       `premanager_5_creativepages` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_5_creativepagestranslation`
--

INSERT INTO `premanager_5_creativepagestranslation` (`id`, `languageID`, `text`, `timestamp`) VALUES
(1, 1, '<p>Herzlich Willkommen bei <b>Rack</b>, dem momentanen Entwicklungsstand der Organisationssoftware <b>Premanager</b>!</p>\r\n\r\n<p>Da die Navigation gerade noch in Arbeit ist, sind die einzelnen Kategorien noch nicht richtig verlinkt. Benutze deshalb die folgende Liste, um diese Website zu erkunden.</p>\r\n\r\n<ul>\r\n<li><p><a href="./mitglieder/benutzer">Benutzerliste</a></p></li>\r\n<li><p><a href="./mitglieder/gruppen">Gruppenliste</a></p></li>\r\n<li><p><a href="./mitglieder/wer-ist-online">Wer ist online?</a></p></li>\r\n<li><p><a href="./anmeldung">Anmeldeformular</a></p></li>\r\n<li><p><a href="./blog">Blog</a></p></li>\r\n<li><p><a href="./admin/struktur">Seitenstruktur</a></p></li>\r\n</ul>\r\n\r\n<p>Du kannst auch jederzeit auf Englisch umschalten, indem du das <i>de</i> in der Adresszeile durch ein <i>en</i> austauschst.</p>\r\n\r\n<p>Genauso ist es möglich, ein Projekt auszuwählen: Füge dafür <i>/zwei-gesichter</i> direkt nach dem Servernamen ein.</p>\r\n\r\n<p>Ich wünsche dir viel Spaß beim Erkunden der bisherigen Funktionen von Premanager 1.0. Wenn du noch fragen hast, kontaktiere mich bitte <a href="mailto:info@yogularm.de">E-Mail</a>. Danke!</p>', '2010-05-23 00:49:56'),
(1, 2, '<p>Welcome to <b>Rack</b>, the current development snapshot of organization software <b>Premanager</b>!</p>\r\n\r\n<p>Because the navigation is still in progress, you can use the following list to reach some more content.</p>\r\n\r\n<ul>\r\n<li><p><a href="./members/users">User List</a></p></li>\r\n<li><p><a href="./members/groups">Group List</a></p></li>\r\n<li><p><a href="./members/who-is-online">Who is online?</a></p></li>\r\n<li><p><a href="./login">Login</a></p></li>\r\n<li><p><a href="./blog">Blog</a></p></li>\r\n<li><p><a href="./admin/structure">Structure</a></p></li>\r\n</ul>\r\n\r\n<p>You can always switch to German by replacing <i>en</i> in address bar by <i>de</i>.</p>\r\n\r\n<p>It is also possible to select a project: Insert <i>/zwei-gesichter</i> directly after server name.</p>\r\n\r\n<p>Happy discovering new features of Premanager 1.0. Feel free to contact my via <a href="mailto:info@yogularm.de">e-mail</a>. Thank you!</p>', '2010-05-23 00:50:10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_articles`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_articles` (
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
-- RELATIONEN DER TABELLE `premanager_6_articles`:
--   `categoryID`
--       `premanager_6_categories` -> `id`
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--

--
-- Daten für Tabelle `premanager_6_articles`
--

INSERT INTO `premanager_6_articles` (`id`, `categoryID`, `createTime`, `editTime`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `editTimes`, `timestamp`) VALUES
(1, 2, '2010-04-01 16:14:13', '2010-04-01 16:14:13', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-04-01 16:14:13');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_articlesname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_articlesname` (
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
-- RELATIONEN DER TABELLE `premanager_6_articlesname`:
--   `id`
--       `premanager_6_articles` -> `id`
--

--
-- Daten für Tabelle `premanager_6_articlesname`
--

INSERT INTO `premanager_6_articlesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 1, 'biografie', 0, 0, '2010-04-01 16:18:28'),
(2, 1, 'biography', 0, 0, '2010-04-01 16:18:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_articlestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_articlestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `publicRevisionID` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_6_articlestranslation`:
--   `id`
--       `premanager_6_articles` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--   `publicRevisionID`
--       `premanager_6_revisions` -> `id`
--

--
-- Daten für Tabelle `premanager_6_articlestranslation`
--

INSERT INTO `premanager_6_articlestranslation` (`id`, `languageID`, `name`, `title`, `publicRevisionID`, `timestamp`) VALUES
(1, 1, 'biografie', 'Biografie', 1, '2010-04-01 16:51:02'),
(1, 2, 'biography', 'Biography', 2, '2010-04-01 16:51:09');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_categories`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_categories` (
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
-- RELATIONEN DER TABELLE `premanager_6_categories`:
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `editorID`
--       `premanager_0_users` -> `id`
--   `indexArticleID`
--       `premanager_6_articles` -> `id`
--   `parentID`
--       `premanager_6_categories` -> `id`
--

--
-- Daten für Tabelle `premanager_6_categories`
--

INSERT INTO `premanager_6_categories` (`id`, `parentID`, `indexArticleID`, `createTime`, `editTime`, `creatorID`, `creatorIP`, `editorID`, `editorIP`, `editTimes`, `timestamp`) VALUES
(1, 0, 0, '2010-04-01 16:09:16', '2010-04-01 16:09:16', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-04-01 16:11:46'),
(2, 0, 1, '2010-04-01 16:12:55', '2010-04-01 16:12:55', 2, '127.0.0.1', 2, '127.0.0.1', 0, '2010-04-01 16:18:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_categoriesname`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_categoriesname` (
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
-- RELATIONEN DER TABELLE `premanager_6_categoriesname`:
--   `id`
--       `premanager_6_categories` -> `id`
--

--
-- Daten für Tabelle `premanager_6_categoriesname`
--

INSERT INTO `premanager_6_categoriesname` (`nameID`, `id`, `name`, `languageID`, `inUse`, `timestamp`) VALUES
(1, 2, 'über-uns', 0, 0, '2010-04-01 16:13:51'),
(2, 2, 'about-us', 0, 0, '2010-04-01 16:13:51');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_categoriestranslation`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_categoriestranslation` (
  `id` int(10) unsigned NOT NULL,
  `languageID` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`languageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONEN DER TABELLE `premanager_6_categoriestranslation`:
--   `id`
--       `premanager_6_categories` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_6_categoriestranslation`
--

INSERT INTO `premanager_6_categoriestranslation` (`id`, `languageID`, `name`, `title`, `timestamp`) VALUES
(0, 1, '', 'Startseite', '2010-04-01 16:12:13'),
(0, 2, '', 'Home Page', '2010-04-01 16:12:13'),
(2, 1, 'über-uns', 'Über uns', '2010-04-01 16:13:39'),
(2, 2, 'about-us', 'About Us', '2010-04-01 16:13:39');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `premanager_6_revisions`
--
-- Erzeugt am: 07. Oktober 2010 um 20:10
-- Aktualisiert am: 07. Oktober 2010 um 19:12
-- Letzter Check am: 07. Oktober 2010 um 20:10
--

CREATE TABLE IF NOT EXISTS `premanager_6_revisions` (
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
-- RELATIONEN DER TABELLE `premanager_6_revisions`:
--   `articleID`
--       `premanager_6_articles` -> `id`
--   `creatorID`
--       `premanager_0_users` -> `id`
--   `languageID`
--       `premanager_0_languages` -> `id`
--

--
-- Daten für Tabelle `premanager_6_revisions`
--

INSERT INTO `premanager_6_revisions` (`id`, `articleID`, `revision`, `languageID`, `createTime`, `creatorID`, `creatorIP`, `text`, `summary`, `timestamp`) VALUES
(1, 1, 1, 1, '2010-04-01 16:41:33', 2, '127.0.0.1', 'Juvenile Studios ist eine Film-Crew, bestehend aus dreizehn Kindern und Jugendlichen zwischen neun und 16 Jahren. Im Herbst 2008 Jahres drehten sie einen fünfzehnminütigen Spielfilm und begeisterten damit weitere Schauspieler. Januar 2009 fand die Premiere des zweiten Films Das Puzzle der Waisen statt. Momentan arbeitet das Team an dem Film Zwei Gesichter, Trau schau wem!.', 'Biography copied from http://www.lastfm.de/music/Juvenile+Studios/+wiki', '2010-04-01 16:44:44'),
(2, 1, 1, 2, '2010-04-01 16:47:04', 2, '127.0.0.1', 'Juvenile Studios is a film crew of thirteen children and juveniles between nine and 16 years. In autumn 2008, they produced a fifteen-minute movie and attracked further actors. In January, 2010, there was the premiere of their second film called "Das Puzzle der Waisen". At the moment the crew is producing "Zwei Gesichter, Trau schau wem!"', 'Copied from null-project''s description', '2010-04-01 16:47:04');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
