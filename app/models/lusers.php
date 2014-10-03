<?php
/**
 * Listenklasse für Benutzer
 * @version 0.1 // Einfach abgeleitet
 */

class lUsers extends lStandard{
	var $mObjectClass = 'oUser';
	var $mDBTable = 'Users';
	
	function __construct() {
		parent::__construct();
	}
}

?>