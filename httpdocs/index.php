<?php
/**
 * 
 * @abstract Das Hauptprogramm alles läuft hierrüber. Ablauf im Groben:
 * 		1. Configuration laden.
 * 		2. _autoload() Definieren.
 * 		3. Session initialisieren + Daten-Laden 
 * 		4. Routing
 * 			-> Controller laden
 * 			-> Action laden
 * 			-> View ausgeben
 * 		5. Session-Daten speichern
 * 		6. Debug-Informationen im Debug-Modus
 * 
 * 
 * @version 0.1	Zunüchst werden nur GET-Parameter Controller und Action ausgelesen.
 * @author Carlos Cota Castro
 */
$zeitmessung = microtime();
include_once('config.php');

/**
 * ########################################################################
 *  __autoload
 *  @abstract Zum laden von Klassen ohne vorherige Definition von Require.
 */
function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $class_name = str_replace('.','',$class_name);
    $class_name = str_replace('\/','',$class_name);
    if (
        (substr($class_name, 0, 1) == 'o') || // Modell soll geladen werden
        (substr($class_name, 0, 1) == 'l')  // Listenklasse soll geladen werden
    ) {
        require_once MODEL_PATH.$class_name . '.php';
    }
    elseif (substr($class_name, 0, 1) == 'h') { // Helper soll geladen werden
        require_once HELPER_PATH.$class_name . '.php';
    }
    elseif (substr($class_name, 0, 1) == 't') { // Datentyp soll geladen werden
        require_once DATATYPE_PATH.$class_name . '.php';
    }
	elseif (substr($class_name, 0, 1) == 'i') { // Interface soll geladen werden
        require_once INTERFACE_PATH.$class_name . '.php';
    }
}

/**
 * ########################################################################
 * MySQL-Verbindung wird hergestellt.
 */

hMySQL::Init();



/**
*   Globale Funktionen für Ausgaben.
*   Muss nach der Session geladen werden.
*/
include_once(HELPER_PATH.'hshortcuts.php');

/**
 * ########################################################################
 * Session wird gestartet. Wichtig: Noch vor erster Ausgabe.
 */
hSession::Init();

/**
#############################################################################
 Routing --------------------------------------------------
#############################################################################
*/

hRouter::InitRouting();
hRouter::Debug();
try {
	
	hEngine::Controller();

	hEngine::Action();
	





/**
 * #############################################################################
 * Ausgabe
 * #############################################################################
 */

	hEngine::View();

} catch (Exception $e) {
	/**
	 * Mach nichts. Hauptsache Debug geht noch
	 */
}


/**
 * #############################################################################
 * Ausgabe ENDE
 * #############################################################################
 */


/**
 * #############################################################################
 * Session speichern 
 *  
 */
hSession::Save();


/** #############################################################################
 * Debug-Informationen ausgeben
 * #############################################################################
 */
if ((hRouter::getFormat() != 'file')
 //&& (hRouter::getFormat() != 'ajax')
){
	echo hDebug::showDebugInformation();
}
if(DEBUG) {
echo '<hr />'.(microtime() - $zeitmessung);
}


?>