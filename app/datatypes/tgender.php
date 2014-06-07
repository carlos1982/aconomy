<?php
/**
 * Abstraktion von tSelect für Anreden
 * 
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 */

class tGender extends tSelect{

	var $mValue = array();
	var $mAllowedValues = array('male' => 'Herr', 'female' => 'Frau', 'none' =>'Nichts davon','bothisok' => 'Mir egal');
	var $mMultiple = false;

	function tSelect($pInitParams = array()) {
		parent::__construct($pInitParams);
		
		$this->$mAllowedValues = array('male' => 'Herr', 'female' => 'Frau', 'none' =>'Nichts davon','bothisok' => 'Mir egal'); 
		
	}
}
?>