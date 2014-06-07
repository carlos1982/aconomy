<?php
/**
 * @abstract Helper zur globalen Verwaltung von Fehlermeldungen für Benutzer. 
 * @version 0.1	Sammelt die Daten in static Variable
 * @version 0.2	Sammelt die Daten gruppiert
 * @version 0.3 AddFormError ermöglicht das Sammelt direkt für das jeweilige Eingabe-Feld.
 * @version 0.4 Als Extension von hUserMessages implementiert.
 */
class hError extends hUserMessages {
	
	static private $mMessages = array();
	
	/**
	 * 	Zum Sammeln von Fehlermeldungen direkt für ein bestimmtes Eingabe-Feld
	 */
	static function AddFormError($pMessage = '', $pInputFieldName = '') {
		if ($pInputFieldName == '') {	// Dann benutz das selbe verhalten wie für Add()
			self::Add($pMessage);
		}
		else {
			self::Add('Ein Fehler ist aufgetreten.');	// Allgemeine Ausgabe wird in die Standard-Error-Liste geschrieben.
			self::Add($pMessage,$pInputFieldName);	// Spezielle Fehlermeldung wird nun für das Eingabe-Feld gesammelt.
		}
	}	
	
	static function Add($pMessage = '', $pGroup = 'ErrorMessages') {
		parent::Add($pMessage, $pGroup);
	}
	
	/**
	 * Überschreibt die Parent-Funktion
	 * @version 0.2	Extended die Parent-Funktion.
	 */
	static function showMessages($pGroup = 'ErrorMessages', $pCssClass = 'errormessage') {
		return parent::showMessages($pGroup, $pCssClass);
	}
	

}

?>
