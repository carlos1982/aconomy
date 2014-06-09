<?php
/**
 * Typen-Klasse.
 * Anhand des Daten-Typs werden später Ausgaben, einfache plausabilitätsabfragen
 * ausgeführt sowie Fehlermeldungen generiert.
 * 
 * $mValue verwendet ein array() 
 * 
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 */

class tForeignKey extends tSelect {

	var $mMultiple = false;
	var $mForeignTable = '';		// Auf welche Tabelle zeigt das Feld?
	var $mForeignFields = array();	// Welche Felder sollen angezeigt werden um Auswahl zu treffen?
	var $mForeignObject = '';

	function tForeignKey($pInitParams = array()) {
		parent::__construct($pInitParams);
		
		if (array_key_exists('ForeignTable', $pInitParams)) {
			$this->mForeignTable = $pInitParams['ForeignTable'];
		}
		
		if ((array_key_exists('ForeignFields', $pInitParams)) &&
			(is_array($pInitParams['ForeignFields']))) {
			$this->mForeignFields = $pInitParams['ForeignFields'];
		}
		
		if (array_key_exists('Multiple', $pInitParams)) {
			$this->mMultiple = false;
		}
		
	}
	
	/**
	 * @return array()
	 * @see interfaces/iDataTypes::getValue()
	 */
	function getValue() {
		return $this->mValue;
	}

	/**
	 *	@param $pAllowedValues = array() 
	 */
	function setAllowedValues($pAllowedValues = array()) {
		if ((is_array($pAllowedValues)) && (count($pAllowedValues) > 0)) {
			$this->mAllowedValues = $pAllowedValues;
		}
		hDebug::Add('Erlaubte Werte wurden gesetzt');
	}
	
	/**
	 * @param bool $pMultiple
	 * @abstract Eigentlich nicht nötig, aber zur Vorsicht Funktion lehr gemacht.
	 */
	function setMultiple($pMultiple = false) {
	}
	
	/**
	 * Erwartet ein Array() oder einen einzelnen Wert.
	 * @see interfaces/iDataTypes::setValue()
	 */
	function setValue($pValue, $pForceValueToValidate = false){
		$this->mValue = $pValue;
        if ($pForceValueToValidate) {
            $this->AllowCurrentValue();
        }
	}
	
	/**
	 * Wenn ein Fremdschlüssel nicht editierbar war, dann kann man in der Action sagen, dass der aktuelle Wert erlaubt werden soll, bzw.
	 * faktisch keine weitere Validierung stattfindet.
	 * 
	 * Beispiel bei /sheet/astartcorrection.php
	 */
	function AllowCurrentValue() {
		$this->mAllowedValues = array($this->mValue => 'dummy');
	}
	
	
	/**
	 * Überschrieben
	 */
	function addValue($pValue){

	}
	
	function LoadFromPost(){
		if(isset($_POST[$this->getFieldname()])) {
			$value = $_POST[$this->getFieldname()];
			$this->setValue($value);
		}
	}
	
	/**
	 * Zeigt die Felder der Verknüpften Tabelle, die angegeben wurden.
	 * überschreibt datatypes/tSelect::showValue()
	 */
	public function showValue() {

		if ((!is_numeric($this->mValue)) || (!is_array($this->mForeignFields)) || (count($this->mForeignFields) < 1) || ($this->mForeignTable == '')) {
			hDebug::Add($this->getFieldname().' wurde nicht richtig gesetzt. Ausgabe unmöglich.'.$this->ForeignTable);
			return '';
		}
		
		$ret = '';

		if($this->LoadForeignObject($object_name)) {
            $f_object = $this->mForeignObject;
            foreach ($this->mForeignFields as $field_name) {
				$field_var_name = 'd'.$field_name;
                if (is_object($f_object->$field_var_name)) {
                    $ret .= $f_object->$field_var_name->showValue().' ';
                }
                else {
                    _Debug('Objekt '.get_class($f_object).' fehlt die Eigenschaft '.$field_var_name, false );
                }

			} 
		}
		else  {
			hDebug::Add('Fehler beim Laden von Fremden Objekt');
		}
		return $ret;
	}
	
	
	/**
	 * Lädt das einen Datensatz eines verknüpften Objektes
	 * 
	 */
	public function LoadForeignObject($pForceReload = false) {

        if (_isO($this->mForeignObject) && get_class($this->mForeignObject) == $object_name && !$pForceReload) {
            return true;
        }

        if (!is_numeric($this->mValue) || $this->mValue == 0) return false;

		$object_name = 'o'.substr($this->mForeignTable,0,-1);
		if (substr($object_name, -2) == 'ie') {
			$object_name = substr($object_name, 0, -2).'y';
		}
		
		if(!get_class($this->mForeignObject) == $object_name) {
			$object = new $object_name();
			$object->AddCondition('ID',$this->mValue);
			if($object->LoadFromDB()) {
				$this->mForeignObject = $object;
				return true;
			}
			else {
				hDebug::Add('Objekt konnte nicht geladen werden');
			}
		}
		return true;
	}
	
	
	function Reset() {
        $this->mValue = '';
    }
}
?>