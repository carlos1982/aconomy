<?php
/**
 * Abstraktion von tSelect für Anreden
 * 
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 */

class tWeekDay extends tSelect{

	var $mValue = array();
	var $mAllowedValues = array('Mo' => 'Montag', 'Tu' => 'Dienstag', 'We' => 'Mittwoch', 'Th' => 'Donnerstag', 'Fr' => 'Freitag', 'Sa' => 'Samstag', 'Su' => 'Sonntag');
	var $mMultiple = false;

	function tSelect($pInitParams = array()) {
		parent::__construct($pInitParams);
		
		$std_allowed_values = array('Mo' => 'Montag', 'Tu' => 'Dienstag', 'We' => 'Mittwoch', 'Th' => 'Donnerstag', 'Fr' => 'Freitag', 'Sa' => 'Samstag', 'Su' => 'Sonntag');
		
		if ((array_key_exists('AllowedValues', $pInitParams)) &&
			(is_array($pInitParams['AllowedValues']))) {
				
				$p_allowed_values = $pInitParams['AllowedValues'];
				
				$is_ok = true;
				foreach ($p_allowed_values as $day_key => $day_desc ) {
					if (($is_ok) && (in_array($key, $std_allowed_values))) {
						// Weiterhin alles ok
					}
					else {
						$is_ok = false;
						hDebug::Add('Falsche Angabe für erlaubte Wochentage');
						break;
					}
				}
			if ($is_ok) $this->mAllowedValues = $p_allowed_values;
			else $this->mAllowedValues = $std_allowed_values;
		}
		else {
			$this->mAllowedValues = $std_allowed_values;
		}
		
	}
}
?>