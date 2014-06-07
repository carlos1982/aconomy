<?php
/**
 * @abstract Eine Sammlung von Ausgaben die zum Debuggen benutzt werden können.
 * @version 0.1 - Ausgabe der Konstanten
 * @version 0.1.1 - 18.10.2011 mDebugMessages ist hinzugekommen.
 * @version 0.1.2 - 03.11.2011 Dump() implementiert
 * Fehlermeldungscounter: 3
 */

class hDebug {
	
	
	static $mDebugMessages = array();
	
	/**
	 * @abstract Liefert eine HTML-Tabelle mit Werten der Konstanten zurück 
	 */
	static function getConstantTable() {
		
		$constants = get_defined_constants(true);	// Gibt die Konstanten nach Kategorien geordnet zurück.
		
		$ret = '<table><thead><tr><th>ConstantName</th><th>ConstantValue</th></tr></thead><tbody>';
		foreach ($constants['user'] as $ckey => $cval) {		// Gibt nur die Benutzerkonstanten zurück
			$ret .= '<tr><td>'.$ckey.'</td><td>'.$cval.'</td></tr>';
		}
		$ret .= '</tbody></table>';
		return $ret;
	}
	
	
	/**
	 * Wird in index.php gerufen um Debug-Informationen auszugeben.
	 *
	 * @return String mit HTML.
	 */
	static function showDebugInformation() {
		if (DEBUG == false) {
			return;
		}
		$ret = '<div id="debuginformationen">';
		$ret .= '<h2>Konstanten</h2>';
		$ret .= self::getConstantTable();

		$ret .= '<h2>Fehlermeldungen</h2>';
		foreach (self::$mDebugMessages as $key => $message_group) {
			$ret .= '<h3>'.$key.'</h3>';
			$ret .= '<ul>'; 	
			foreach ($message_group as $message) {
				$ret .= '<li>'.$message.'</li>';	
			}
			$ret .= '</ul>';
		}
		$ret .= '</div>';
		return $ret;
	}
	
	static function Add($pMessage = '', $pGroup = 'Std') {
		self::$mDebugMessages[$pGroup][] = time().': '.print_r($pMessage,true);
	} 
    
    public function Dump($obj) {
        if (!(DEBUG)) return false;
        echo '<pre>'."\n";
        var_dump($obj);
        echo '</pre>'."\n";
    }
}

?>