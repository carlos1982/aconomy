<?php
/**
 * @abstract Helper zur globalen Verwaltung von Fehlermeldungen für Benutzer. 
 * @version 0.1 Direkt als Extension von hUserMessages implementiert.
 */
class hSuccess extends hUserMessages {
	
	static private $mMessages = array();
	
	/**
	 * Überschreibt die Abgeleitete Klasse und ruft die Elternklasse mit anderen Parametern auf.
	 * @version 0.2	Extended die Parent-Funktion.
	 */
	static function Add($pMessage = '', $pGroup = 'SuccessMessages') {
		parent::Add($pMessage, $pGroup);
	}
	
	static function showMessages($pGroup = 'SuccessMessages', $pCssClass = 'successmessage') {
		return parent::showMessages($pGroup, $pCssClass);
	}
}

?>