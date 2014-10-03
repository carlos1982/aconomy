<?php
/**
 * Typen-Klasse.
 * Anhand des Daten-Typs werden später Ausgaben, einfache plausabilitätsabfragen z.B. is_numeric()
 * ausgeführt sowie Fehlermeldungen generiert.
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 * @version 0.1 Edit-Funktion die mName und mLabel berücksichtigt.
 * @version 0.2 Benutzt für Edit hError als Datensammlung und gibt Fehlermeldungen direkt beim Input-Feld aus.
 * @version 0.3 mRequired, mMinValue, mMaxValue werden in Edit und Validate benutzt und in tInteger initialisiert, wenn gesetzt.
 */
class tDatatype {
	public $mValue = '';
    public $mFieldname = '';
    public $mLabel = '';
    public $mRequired = true;
    public $mEditable = true;
    public $mHint = '';

	/**
	 * Parameter zur Initialisierung des Datentyps
	 * @param array $pInitParams
	 */
	function __construct($pInitParams = array()) {
        if (array_key_exists('Fieldname', $pInitParams)) {
			$this->mFieldname = $pInitParams['Fieldname'];
            //hDebug::Add('Feldname wird gesetzt: '.$this->mFieldname);
		}
		else {
			hDebug::Add('Ohne Feldnamen macht das ganze keinen Sinn: '.get_class($this));
			hDebug::Add(print_r($this,true));
			
		}
		if (array_key_exists('Label', $pInitParams)) {
			$this->setLabel($pInitParams['Label']);
		}		
		if ((array_key_exists('Required', $pInitParams)) && (is_bool($pInitParams['Required']))) {
			$this->mRequired = $pInitParams['Required'];
		}
		if ((array_key_exists('Editable', $pInitParams)) && (is_bool($pInitParams['Editable']))) {
			$this->mEditable = $pInitParams['Editable'];
		}
		if (array_key_exists('Hint', $pInitParams)) {
			$this->mHint = $pInitParams['Hint'];
		}
		
	}
	
	function Reset() {
        $this->mValue = '';
    }
	
	function getFieldname() {
		return $this->mFieldname;
	}

	function setFieldname($pFieldname = '') {
		if($pFieldname != '') {
			hDebug::Add('Feldname wird von '.$this->mFieldname.' zu '.$pFieldname.' geändert.');
			$this->mFieldname = $pFieldname;
		}
	}
	
	function getLabel() {
		return $this->mLabel;
	}
	
	function setLabel($pLabel) {
		$this->mLabel = __($pLabel);
	}

	function setRequired($pRequired) {
		if (is_bool($pRequired)) {
			$this->mRequired = $pRequired;
		}
	}
	
	
	function StdValidate() {
		if (($this->mValue == '') && ($this->mRequired == true)) {
			hError::AddFormError(__('{#0} ist nicht gesetzt.',array($this->mLabel)),$this->getFieldname());
            return false;
		}
		elseif($this->mValue == '') {
			return true;	// Für den Fall, dass mRequired auf false gesetzt wurde ist ein Leerer Wert ein gültiger.
		}
	}
	
	function setEditable($pEditable = '') {
		if (is_bool($pEditable)) {
			$this->mEditable = $pEditable;
		}
	}
	
	
	/**
	 * Wird zum Einfügen und Speichern verwendet.
	 * Liefert String wo Werte gesetzt werden zurück.
	 * @return String
	 */
	function getSqlSetter() {
		$ret = '';
		if (is_numeric($this->getValue())) {
	    	$ret = $this->getFieldname().'='.$this->getValue().', ';
	    }
	    elseif (is_array($this->getValue())) {    	
			$ret = $this->getFieldname()."='".hMySQL::escapeString(join(",",hParams::UnEscapeHtmlTags($this->getValue())))."', ";
	    }
	    else {
	    	$ret = $this->getFieldname()."='".hMySQL::escapeString(hParams::UnEscapeHtmlTags($this->getValue()))."', ";
		}
		return $ret;
	}
	
	
	function Edit() {
		if (!$this->mEditable) {
			return false;
		}
		else {
			return true;
		}
	}

    public function __toString() {
        return $this->showValue();
    }



}
?>
