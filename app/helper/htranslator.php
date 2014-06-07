<?php
/**
*   @abstract   
*   @version 0.1    // Nur ein Dummy.
*   @author Carlos Cota Castro
**/

class hTranslator{

    /**
    *   Wenn gewünscht, dann kann man hier eine Übersetzung implementieren.
    *   @version 0.1    Nur ein Dummy. Lediglich die Ersetzungen der Platzhalter werden gemacht.
    *   @param  $string  // Kann Platzhalter enthalten der Form {#0}, {#1}. die Zahl ist der Index im Array mit Werten, mit denen die Platzhalter ersetzt werden sollen.
    *   @param  $array  // Hier werden die Werte angegeben, durch die Platzhalter ersetzt werden
    */
	
	static $mReplacementParams = array();
	
	/**
	 * Derzeit ohne Übersetzung. 
	 * @version 0.1
	 * @param string $pStr
	 * @param array $pParams
	 * shortcut __()
	 * 
	 */
    static function TranslateToOutputLanguage($pStr, $pParams = ''){
        if (is_array($pParams)) {
        	// Idee übernommen von Michael Bruser von R&B Online OHG, Langenfeld
        	// Ich habe lediglich die Semantik verbessert, sowie statt einer globalen Variable, die Static-Variable benutzt
        	self::$mReplacementParams = $pParams;
            $pStr = preg_replace_callback("(\{\#(\d{1})\})", "TranslatorParameterReplacer", $pStr);
        }
        return $pStr;
    }


}

/**
 * 
 * Callback-Methode zum ersetzen von Platzhaltern
 * @param unknown_type $pTreffer
 */
function TranslatorParameterReplacer($pTreffer) {
  	return hTranslator::$mReplacementParams[$pTreffer[1]];
}


?>