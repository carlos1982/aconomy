<?php
/**
 * @abstract Klassen-Definition für Studenten
 * @author Carlos Cota Castro
 * @version 0.1	// Erste definition einer Klasse. Hier soll die Funktionalität der Standard-Klassen oStandard und lStandard überprüft werden.
 * 
 */
class oUser extends oPerson  {

	var $mDBTable = 'Users';	// Aus welcher Datenbank soll ein Datensatz geladen werden?
	
	public function oUser() {
		$this->Init();
		$this->mListFields = array('dNickname','dForename','dSurname');
    }

    public function Init() {
    	parent::Init();
		$this->dConfirmed = new tSelect(		array('Fieldname' => 'Confirmed',
													  'AllowedValues' => array('0' => 'Nicht bestätigt', '1' => 'Bestätigt'),
                                                      'Editable' => false
												)
								);
	}

}

	
	
?>