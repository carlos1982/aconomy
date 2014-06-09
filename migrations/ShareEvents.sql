--
-- Tabellenstruktur für Tabelle `ShareEvents`
--

CREATE TABLE IF NOT EXISTS `ShareEvents` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EncryptID` varchar(100) COLLATE utf8_bin NOT NULL,
  `User` int(11) NOT NULL COMMENT 'Requester',
  `Item` int(11) NOT NULL,
  `Message` text COLLATE utf8_bin NOT NULL,
  `MessageReturn` text COLLATE utf8_bin NOT NULL,
  `State` enum('Open','Rejected','Approved','HandedOver','Returned') COLLATE utf8_bin NOT NULL,
  `LendFrom` datetime NOT NULL,
  `LendTil` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;