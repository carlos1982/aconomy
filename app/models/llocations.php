<?php
/**
 * Listenklasse für Orte
 * @version 0.1 // Einfach abgeleitet
 */

class lLocations extends lStandard{
	var $mObjectClass = 'oLocation';
	var $mDBTable = 'Locations';
	
	function lLocations() {
		parent::Init();
	}
}

?>