<?php
/**
 * Listenklasse für Benutzer
 * @version 0.1 // Einfach abgeleitet
 */

class lSessions extends lStandard{
	var $mObjectClass = 'oSession';
	var $mDBTable = 'Sessions';
	
	function __construct() {
		parent::__construct();
	}
}

?>