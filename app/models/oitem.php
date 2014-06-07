<?php
/**
 * @abstract Klassen-Definition für Zugehörigkeit zu gewissen Communities
 * @author Carlos Cota Castro
 * @version 0.1
 * 
 */
class oItem extends oStandard  {

	var $mDBTable = 'Items';	// Aus welcher Datenbank soll ein Datensatz geladen werden?
	
	var $dUser;
	var $dName;
	var $dDescription;
	var $dCondition;
	var $dLendForward;
    var $dCreated;
	
	public function __construct() {
        $this->mListFields = array('dName', 'dLendForward');
		$this->Init();
    }

    public function Init() {
    	parent::Init();

        $this->dUser = new tForeignKey(	array(
            'Fieldname' => 'User',
            'Label' => 'Eigentümer',
            'Editable' => false,
            'ForeignTable' => 'Users',
            'ForeignFields' => array('Nickname')
        ));
        $this->dName = new tString(	array(
            'Fieldname' => 'Name',
            'Label' => 'Artikelbezeichnung'
        ));
        $this->dDescription = new tText( array(
            'Fieldname' => 'Description',
            'Label' => 'Beschreibung'
        ));
        $this->dCondition = new tSelect( array (
            'Label' => 'Zustand',
            'Fieldname' => 'State',
            'Required' => true,
            'AllowedValues' => array(
                'New' => 'Neu',
                'Slightly Used' => 'Leicht benutzt',
                'Used' => 'Benutzt',
                'Heavily Used' => 'Viel benutzt',
                'Almost Broken' => 'Fast kaputt',
                'Broken' => 'Kaputt (benötigt Reparatur um benutzt zu werden)'
            )
        ));
        $this->dLendForward = new tSelect(	array(
            'Fieldname' => 'LendForward',
            'Label' => 'Kann der Artikel weitergegeben werden?',
            'Required' => true,
            'AllowedValues' => array(
                'true' => 'Kann weitergegeben werden',
                'Slightly Used' => 'Nein, bitte nachher zurück an mich'
            )
        ));
        $this->dCreated = new tDateTime( array(
            'Fieldname' => 'Created',
            'Editable' => false,
        ));
								
	}
	
}

	
	
?>

