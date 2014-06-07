<?php
/**
* @abstract Ein Helfer der zum einlesen von Parametern dienen soll.
* @author Carlos Cota Castro
* @version 0.1
*/
class hParams {
    
    /**
    *   @abstract Entfernt "." Punkt und "/" Slash aus Parametern, die in Datei-Pfade eingebene werden sollen.
    *  Andernfalls könnte es passieren, dass Leute auf einem schlecht gesicherten Server
    *  config-Dateien einbinden.
    */
    static function StripPathSymbols($pParam) {
        $pParam = str_replace('.','', $pParam); // Punkt entfernen
        $pParam = str_replace('/','', $pParam); // Slash entfernen
        //$pParam = str_replace('\\','', $pParam); // Unter Windows zeile entkommentieren
        return $pParam;
    }
    
    static function EscapeHtmlTags($pString) {
    	$pString = str_replace('>', '&gt;', $pString);
    	$pString = str_replace('<', '&lt;', $pString);
    	return $pString;
    }
    
	static function UnEscapeHtmlTags($pString) {
    	$pString = str_replace('&gt;', '>', $pString);
    	$pString = str_replace('&lt;', '<', $pString);
    	return $pString;
    }
        
}
?>