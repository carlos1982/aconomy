<?php
/**
 * @abstract Klassen-Definition für Orte Sozialer Interaktion
 * @author Carlos Cota Castro
 * @version 0.1	// Erste definition einer Klasse.
 * 
 */
class oShareEvent extends oStandard  {

	var $mDBTable = 'ShareEvents';	// Aus welcher Datenbank soll ein Datensatz geladen werden?
	
	var $dUser;
	var $dItem;
	var $dMessage;
    var $dMessageReturn;
	var $dState;
	var $dLendFrom;
	var $dLendTil;

	public function __construct() {
        $this->mListFields = array('dUser', 'dItem', 'dMessage', 'dMessageReturn', 'dStatus', 'dLendFrom', 'dLendTil');

        parent::__construct();
        $this->dUser = new tForeignKey(array('Fieldname' => 'Requester', 'Label' => 'Anfrage von', 'Required' => true));
        $this->dItem = new tForeignKey(array('Fieldname' => 'Item', 'Label' => 'Gegenstand', 'Required' => true));
        $this->dMessage = new tString(array('Fieldname' => 'Anfrage', 'Label' => 'Anfragetext'));
        $this->dMessageReturn = new tString(array('Fieldname' => 'Antwort', 'Label' => 'Antworttext'));
        $this->dState = new tSelect(array(
                'Fieldname' => 'State',
                'Label' => 'Status',
                'Editable' => false,
                'Required' => true,
                'AllowedValues' => array(
                    'Open'=>'Anfrage gestellt',
                    'Rejected'=>'Anfrage abgelehnt',
                    'Approved'=>'Anfrage bestätigt',
                    'HandedOver'=>'Bekommen',
                    'Returned'=>'Weitergegeben'
                )
            )
        );

        $this->dLendFrom = new tDateTime(array('Fieldname' => 'Email', 'Label' => 'E-Mail', 'Required' => false));
        $this->dLendTil = new tDateTime(array('Fieldname' => 'Website', 'Label' => 'URL', 'Required' => false));
    }


    public function isClosed() {
        return ($this->dState->getValue() == 'Rejected' || $this->dState->getValue() == 'Returned');
    }
	
}

	
	
?>

