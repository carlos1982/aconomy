<?php
/**
 * @abstract Klassen-Definition für Zugehörigkeit zu gewissen Communities
 * @author Carlos Cota Castro
 * @version 0.1
 * 
 */
class oMembership extends oStandard  {

	var $mDBTable = 'Memberships';	// Aus welcher Datenbank soll ein Datensatz geladen werden?
	
	var $dUser;
	var $dLocation;
	var $dApproved;
	var $dGodfather;
	var $dGodmother;
	
	public function __construct() {
        $this->mListFields = array('dUser', 'dLocation', 'dApproved');
		$this->Init();
    }

    public function Init() {
    	parent::Init();

        $this->dUser = new tForeignKey(	array(
            'Fieldname' => 'User',
            'Label' => 'Benutzer',
            'ForeignTable' => 'Users',
            'ForeignFields' => array('Nickname')
        ));
        $this->dLocation = new tForeignKey(	array(
            'Fieldname' => 'Location',
            'Label' => 'Location',
            'ForeignTable' => 'Locations',
            'ForeignFields' => array('Name')
        ));
        $this->dApproved = new tSelect( array (
            'Fieldname' => 'Approved',
            'Editable' => true,
            'Hidden' => true,
            'AllowedValues' => array('0' => 'Nicht bestätigt', '1' => 'Bestätigt')
        ));
        $this->dGodfather = new tForeignKey(	array(
            'Fieldname' => 'Godfather',
            'Label' => 'Akzeptiert von',
            'ForeignTable' => 'Users',
            'ForeignFields' => array('Forename', 'Surname'),
            'Required' => false
        ));
        $this->dGodmother = new tForeignKey(	array(
            'Fieldname' => 'Godmother',
            'Label' => 'Akzeptiert von',
            'ForeignTable' => 'Users',
            'ForeignFields' => array('Forename', 'Surname'),
            'Required' => false
        ));


								
	}
	
}

	
	
?>

