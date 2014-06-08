-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Items`
--

CREATE TABLE IF NOT EXISTS `Items` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EncryptID` varchar(200) NOT NULL,
  `User` int(11) NOT NULL,
  `Name` varchar(60) NOT NULL,
  `Description` text NOT NULL,
  `State` enum('New','Slightly Used','Used','Heavily Used','Almost Broken','Broken') NOT NULL,
  `LendForward` enum('true','false') NOT NULL,
  `Created` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EncryptID` (`EncryptID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
