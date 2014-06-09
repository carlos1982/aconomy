<?php
/**
 * @abstract Klassen-Definition für Orte Sozialer Interaktion
 * @author Carlos Cota Castro
 * @version 0.1	// Erste definition einer Klasse.
 * 
 */
class oLocation extends oStandard  {

	var $mDBTable = 'Locations';	// Aus welcher Datenbank soll ein Datensatz geladen werden?
	
	var $dName = '';
	var $dStreet = '';	
	var $dHousenumber = '';	
    var $dZip = '';
	var $dCity = '';
	var $dCountry = '';
	var $dEmail = '';
	var $dWebsite = '';
	var $dDescription = '';
	var $dCoordinates = '';
    var $dCountMembers = 0;
	var $dBanner = '';
	var $dLogo = '';

    var $mMemberships = null;
	
	public function __construct() {
        $this->mListFields = array('dName', 'dCountMembers', 'dStreet', 'dHousenumber', 'dCity', 'dWebsite');
		$this->Init();
    }

    public function Init() {
    	parent::Init();
		
		$this->dName = new tString(		array(  'Fieldname' => 'Name',		
												'Label' => 'Name'
										)
								);
		$this->dStreet = new tString(array('Fieldname' => 'Street', 'Label' => 'Straße'));
		$this->dHousenumber = new tString(array('Fieldname' => 'Housenumber', 'Label' => 'Hausnummer'));
		$this->dZip = new tString(array('Fieldname' => 'Zip', 'Label' => 'PLZ'));
		$this->dCity = new tString(array('Fieldname' => 'City', 'Label' => 'Stadt'));
		$this->dCountry = new tCountry(array('Fieldname' => 'Country', 'Label' => 'Land'));
		$this->dEmail = new tEmail(array('Fieldname' => 'Email', 'Label' => 'E-Mail', 'Required' => false));
		$this->dWebsite = new tUrl(array('Fieldname' => 'Website', 'Label' => 'URL', 'Required' => false));
		$this->dDescription = new tText(	array(  'Fieldname' => 'Description',
												'Label' => 'Beschreibung',
												'Required' => false
										)
		);
		$this->dCoordinates = new tString(array('Fieldname' => 'Coordinates', 'Label' => 'Koordinaten', 'Required' => false));

        $this->dCountMembers = new tInteger(array(
            'Fieldname' => 'CountMembers',
            'Editable' => false,
        ));

        $this->dBanner = new tFile(	array(  'Fieldname' => 'Banner',
												'Label' => 'Banner hochladen',
												'Required' => false,
												'Owner' => &$this
									)
		);
		$this->dLogo = new tFile(	array(  'Fieldname' => 'Logo',
											'Label' => 'Logo hochladen',
											'Required' => false,
											'Owner' => &$this
									)
		);

								
	}

    public function getAdress() {
        return $this->dName->getValue().', '.$this->dStreet->getValue().' '. $this->dHousenumber->getValue(). ', '.$this->dZip->getValue().' '.$this->dCity->getValue();
    }

    /**
     * @description Wenn der aktuelle User Mitglied dieser Gruppe ist, dann true, sonst false
     * @parameter $pUserId  Int Standardmäßig wird der aktuelle User abgefragt
     * @return bool
     * @todo Implementieren
     */
    public function UserIsMember($pUserId = 0) {
        if ($this->LoadMemberships()) {
            return $this->mMemberships->UserIsMember($pUserId);
        }
        return false;
    }

    public function UserIsApprovedMember($pUserId = 0) {
        if ($this->LoadMemberships()) {
            return $this->mMemberships->UserIsApprovedMember($pUserId);
        }
        return false;
    }

    public function LoadMemberships($pForceReload = false) {
        if ($pForceReload || $this->mMemberships == null) {
            $memberships = new lMemberships();
            $memberships->AddCondition('Location',$this->getID());
            if ($memberships->LoadFromDB()) {
                $this->mMemberships = $memberships;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }
	
}

	
	
?>

