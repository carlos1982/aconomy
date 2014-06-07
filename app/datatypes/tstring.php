<?php
/**
 * Typen-Klasse für Strings.
 * @author Carlos Cota Castro
 * @version 0.1
 */
class tString extends tDatatype implements iDataTypes {
	
	public $mPattern = '';
	public $mLength = '';

	/**
	 * Parameter zur Initialisierung des Datentyps
	 * @param array $pInitParams
	 */
	function __construct($pInitParams = array()) {
		parent::__construct($pInitParams);

		if (array_key_exists('Pattern', $pInitParams)) {
			$this->mPattern = $pInitParams['Pattern'];
		}
		
		if (array_key_exists('Length', $pInitParams)) {
			$this->mLength = $pInitParams['Length'];
		}
		
	}
	
	/**##############################################################################
	 * 		Implementierung iDatatypes
	 * ##############################################################################*/
	
	function getValue() {
		return $this->mValue;	
	}
	
	function setValue($pValue){	// Wenn das aus der Datenbank kommt, dann geht das ja auch so zurück.
		$this->mValue = $pValue;
	}
	
	function LoadFromPost(){
		if ($this->mFieldname == 'Title') {
			hDebug::Add('#################### Titel ######################');
		}
		
		if(isset($_POST[$this->mFieldname])) {
			$value = $_POST[$this->mFieldname];
			$this->mValue = $value;
			hDebug::Add($this->mFieldname.' Wert:'. $this->mValue.'---------------------------');
		}
		else {
			hDebug::Add($this->mFieldname.' Wert nicht gesetzt:'. $this->mValue.'---------------------------');
			$this->mValue = '';
		}
		
		
	}
	
	public function showValue() {
		return hParams::EscapeHtmlTags($this->mValue);
	}
	
	public function Edit() {
		
		if (!parent::Edit()) {
			return;
		}
		
        if ($this->mFieldname != '') {
            $id_string = ' id="'.$this->mFieldname.'"';
            $for_string = ' for="'.$this->mFieldname.'"';
            $name_string = ' name="'.$this->mFieldname.'"';
        }
        else {
            hDebug::Add('Ohne Namen macht ein Input-Feld keinen Sinn!');
            return '';
        }
        
        /* Label */
        if ($this->mLabel != '') {
            $label_string = $this->mLabel;
        }
        else {
            $label_string = $this->mFieldname;
        }
		
		/* Required */
        $required_str = '';
        if ($this->mRequired) {
        	$required_str = ' required="required"';
        }
        
        
	    /* Value */
        $value_string = '';
        if ($this->mValue != '') {
            $value_string = ' value="'.$this->mValue.'"';
        }
        
        /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->mFieldname);
        	$error_class_str = ' class="inputfielderror"'; 
        }
        
        /* Pattern */
        $pattern_string = '';
        if($this->mPattern) {
        	$pattern_string = ' pattern="'.$this->mPattern."'";	
        }
        
        /* Länge */
		$maxlength_string = '';
        if(is_numeric($this->mLength) &&($this->mLength > 0)) {
        	$maxlength_string = ' maxlength="'.$this->mLength."'";	
        }
        
        return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><input type="text" '.$id_string.$name_string.$pattern_string.$required_str.$value_string.$maxlength_string.'  />'.$error_str.'</div>';
	}
	
	public function Validate(){
		
		$parent_validation = parent::StdValidate();
		if (is_bool($parent_validation)) {
			hDebug::Add('Standard-Validierung liefert bool.'.$this->mFieldname.'~'.$this->mValue);
			return $parent_validation;
		}
		if($this->mPattern != '') {
			hDebug::Add($this->mFieldname.': Pattern wird benutzt '.$this->mPattern);
			$past_test = mb_ereg_match($this->mPattern, $this->mValue);
			if(is_bool($past_test)){
				if ($past_test) {
					hDebug::Add('Ergebnis: TRUE');
				}
				elseif($past_test == false) {
					hDebug::Add('Ergebnis: FALSE');
				}
			}
			else {
				hDebug::Add('Ergebnis ist kein BOOL Wert');
			}
			return $past_test;
		}
		else {
			hDebug::Add($this->mFieldname.': Patern ist leer');
		}

        return true;
	}
    
    function Reset() {
        $this->mValue = '';
    }
    

    /**
     * Setzt die Erlaubte String-Länge
     * @param $pLength
     */
    function setLength($pLength) {
    	
    	if(is_numeric($pLength) && ($pLength > 0)) {
    		$this->mLength = $pLength;
    	}
    }
    
    /**
     * 
     * Ist der String gesetzt?
     * 
     * 
     * @return boolean
     */
    function isEmpty() {
    	if ($this->mValue != '') {
    		return false;
    	}
    	return true;
    }



}
?>

