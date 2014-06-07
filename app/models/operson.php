<?php
/**
 * @abstract Klassen-Definition für Personen. Employee und Student werden davon abgeleitet.
 * @author Carlos Cota Castro
 * @version 0.1	// Aus Abstraction von Student entstanden.
 * 
 */
class oPerson extends oStandard  {

	var $dNickname;
	var $dForename;
	var $dSurname;
	var $dGender;
	var $dStreet;
	var $dHousenumber;
	var $dZip;
	var $dCity;
	var $dCountry;
	var $dEmail;
	var $dPassword;	
	var $dAdminrole;
    var $dResetPasswordToken;
	
	public function __construct() {
		$this->Init();
    }

    public function Init() {
    	parent::Init();

    	$this->dNickname = new tString(array('Fieldname' => 'Nickname', 'Label' => 'Angezeigter Name', 'Required' => false));
		$this->dForename = new tString(array('Fieldname' => 'Forename', 'Label' => 'Vorname'));
		$this->dSurname = new tString(array('Fieldname' => 'Surname', 'Label' => 'Nachname'));
		$this->dGender = new tGender(array('Fieldname' => 'Gender', 'Label' => 'Geschlecht'));
		$this->dStreet = new tString(array('Fieldname' => 'Street', 'Label' => 'Straße', 'Required' => false));
		$this->dHousenumber = new tString(array('Fieldname' => 'Housenumber', 'Label' => 'Hausnummer', 'Required' => false));
		$this->dZip = new tString(array('Fieldname' => 'Zip', 'Label' => 'PLZ', 'Required' => false));
		$this->dCity = new tString(array('Fieldname' => 'City', 'Label' => 'Stadt', 'Required' => false));
		$this->dCountry = new tCountry(array('Fieldname' => 'Country', 'Label' => 'Land', 'AllowedValues' => array('de' => 'Deutschland', 'fr' => 'Frankreich', 'gr' => 'Griechenland'), 'Required' => false));
		$this->dEmail = new tEmail(array('Fieldname' => 'Email', 'Label' => 'E-Mail'));
		$this->dPassword = new tPassword(array('Fieldname' => 'Password', 'Label' => 'Password', 'Salt' => $this->mDBTable));
		$this->dAdminrole = new tSelect(array('Fieldname' => 'Adminrole', 'setEditable' => false));
        $this->dResetPasswordToken = new tString(array('Fieldname' => 'ResetPasswordToken', 'Label' => 'ResetToken', 'Required' => false, 'Editable' => false));;
    }
    
	public function getSalutation() {
		if ($this->dGender->getValue() == 'male') {
			return __('Hallo Herr {#0}', array($this->dSurname->getValue()));
		}
		elseif ($this->dGender->getValue() == 'female') {
			return __('Hallo Frau {#0}', array($this->dSurname->getValue()));
		}
		elseif ($this->dGender->getValue() == 'bothisok') {
			if(rand(0,1) == 1) {
				return __('Hallo Frau {#0}', array($this->dSurname->getValue()));
			}
			else {
				return __('Hallo Herr {#0}', array($this->dSurname->getValue()));
			}
		}
		else {
			if (!$this->dNickname->isEmpty()) {
				return __('Hallo {#0}', array($this->dNickname->getValue()));
			}
			return __('Hallo {#0}', array($this->dForename->getValue()));
		}
	}

    public function getDisplayName() {
        if (!$this->dNickname->isEmpty()) {
            return $this->dNickname;
        }
        else {
            return $this->dForename . ' ' . $this->dSurname;
        }
    }
}

	
	
?>