<?php
/**
 * Abstraktion von tSelect für Länder
 * 
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 */

class tCountry extends tSelect{

	var $mValue = array();
	var $mMultiple = false;

	function tCountry($pInitParams = array()) {
		parent::__construct($pInitParams);
		
		$this->mAllowedValues = array('de' => 'Deutschland', 'fr' => 'Frankreich', 'gr' =>'Griechenland', 'it' => 'Italien');
	
	}
}
?>