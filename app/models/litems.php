<?php
/**
 * @abstract Listclass for items that user can lend
 * @version 0.1 // Einfach abgeleitet
 */

class lItems extends lStandard {
	var $mObjectClass = 'oItem';
	var $mDBTable = 'Items';
	
	function __construct() {
		parent::__construct();
	}
}

?>