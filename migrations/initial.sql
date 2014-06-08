-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 07. Jun 2014 um 19:56
-- Server Version: 5.5.37-0ubuntu0.14.04.1
-- PHP-Version: 5.5.9-1ubuntu4

--
-- Datenbank: `aconomy`
--

-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `Locations`
--

CREATE TABLE IF NOT EXISTS `Locations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EncryptID` varchar(100) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Street` varchar(100) NOT NULL,
  `Housenumber` varchar(20) NOT NULL,
  `Zip` varchar(20) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Country` varchar(3) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Website` varchar(80) NOT NULL,
  `Description` text NOT NULL,
  `Coordinates` varchar(100) NOT NULL,
  `CountMembers` int(11) NOT NULL DEFAULT '0',
  `Banner` varchar(100) NOT NULL,
  `Logo` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EncryptID` (`EncryptID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Memberships`
--

CREATE TABLE IF NOT EXISTS `Memberships` (
  `ID` int(11) NOT NULL,
  `EncryptID` varchar(60) NOT NULL,
  `User` int(11) NOT NULL DEFAULT '0',
  `Location` int(11) NOT NULL DEFAULT '0',
  `Approved` tinyint(4) NOT NULL COMMENT '0=falsch, 1=bestätigt',
  `Godfather` int(11) NOT NULL DEFAULT '0',
  `Godmother` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `User` (`User`,`Location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Wer ist Mitglied in welcher Gruppe';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Sessions`
--

CREATE TABLE IF NOT EXISTS `Sessions` (
  `ID` varchar(300) NOT NULL,
  `Data` longtext NOT NULL,
  `LastUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Speichert Session Informationen in Serialisierter Form';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EncryptID` varchar(200) NOT NULL,
  `Nickname` varchar(30) NOT NULL,
  `Forename` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Gender` enum('male','female','none','bothisokay') NOT NULL,
  `Street` varchar(100) NOT NULL,
  `Housenumber` varchar(50) NOT NULL,
  `Zip` varchar(20) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Country` varchar(3) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Confirmed` tinyint(4) NOT NULL,
  `Adminrole` int(11) NOT NULL DEFAULT '0',
  `ResetPasswordToken` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `EncryptID` (`EncryptID`),
  UNIQUE KEY `Nickname` (`Nickname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

