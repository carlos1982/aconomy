<?php
/**
 * @abstract Helper zur globalen Verwaltung von Meldungen für Benutzer. 
 * 
 */
class hUserMessages {
	
	// Muss in den abgeleiteten Klassen definiert werden.
	static private $mMessages = array();
	
	
	/**
	 * Über die Funktion werden die Fehlermeldungen gesamelt und Gruppiert
	 * @param String $pMessage
	 * @param String $pGroup
	 */
	static function Add($pMessage = '', $pGroup = 'Std') {
		self::$mMessages[$pGroup][$pMessage] = $pMessage;
	}
	
	
	/**
	 * Liefert die Fehlermeldungen als HTML-String zurück.
	 * @todo Parameter $pGroup abfragen und nur bestimmte Sachen zurückgeben. ggf eine getMessages Funktion benutzen.
	 * @param unknown_type $pGroup
	 */
	static function showMessages($pGroup = 'Std', $pCssClass = 'stdmessage') {
		if (!isset(self::$mMessages[$pGroup])) {
			hDebug::Add('#############'.$pGroup);
			return false;
		}
		$str = '<div class="'.$pCssClass.'">';
		$str .= '<ul>';
		
		foreach (self::$mMessages as $message_group => $messages) {
			if ($message_group == $pGroup) {
				foreach ($messages as $message) {
					$str .= '<li>'.$message.'</li>';
				}
			}
		}
		$str .= '</ul>';
		$str .= '</div>';
		return $str;
	}
	
	/**
	 * Liefert true, wenn mMessages keine Fehlermeldungen enthällt.
	 * sonst false
	 */
	static function NoErrors() {
		if(count(self::$mMessages['ErrorMessages']) > 0) {
			return false;
		}
		return true;
	}
}

?>
