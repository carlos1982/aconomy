<?php
/**
 * Listenklasse für ShareEvents
 * @version 0.1 // Einfach abgeleitet
 */

class lShareEvents extends lStandard{
	var $mObjectClass = 'oShareEvent';
	var $mDBTable = 'ShareEvents';
	
	function __construct() {
		parent::Init();
	}
}

?>