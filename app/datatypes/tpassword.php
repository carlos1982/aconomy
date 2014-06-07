<?php
/**
 * Typen-Klasse für Passwörter.
 * Passwörter haben spezielle Input-Felder. Vor dem Speichern müssen sie mit hSalt verschlüsselt werden.
 * @author Carlos Cota Castro
 * @version 0.1
 */
class tPassword extends tDatatype implements iDataTypes {

	public $mSalt = 'Std';	// Standard-Verschlüsselung


	/**
	 * Parameter zur Initialisierung des Datentyps
	 * @param array $pInitParams
	 */
	function __construct($pInitParams = array()) {
		parent::__construct($pInitParams);

		if (array_key_exists('Salt', $pInitParams)) {
			$this->mSalt = $pInitParams['Salt'];
		}		
		
	}
	
	/**##############################################################################
	 * 		Implementierung iDatatypes
	 * ##############################################################################*/
	
	function getValue() {
		return $this->mValue;	// Ist bereits verschlüsselt aus der Datenbank gekommen oder wurde beim LoadFromPost verschlüsselt.
	}
	
	function setValue($pValue){	// Wenn das aus der Datenbank kommt, dann geht das ja auch so zurück.
		$this->mValue = $pValue;
	}
	
	function LoadFromPost(){
		if(isset($_POST[$this->getFieldname()])) {
			$value = $_POST[$this->getFieldname()];
			if ($value != '') {
				$this->mValue = hSalt::Salt($value,$this->mSalt);	// Vor dem setzen wird der Wert verschlüsselt.
			}
		}
	}
	
	function showValue() {
		return '**********'; // Passwörter dürfen nicht angezeigt werden.
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
        
        /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->mFieldname);
        	$error_class_str = ' class="inputfielderror"'; 
        }
        
        return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><input type="password" '.$id_string.$name_string.$required_str.'  />'.$error_str.'</div>';
	}
	
	public function Validate(){
		
		$parent_validation = parent::StdValidate();
		if (is_bool($parent_validation)) {
			return $parent_validation;
		}
		
		/**
		 * @todo // Prüfen ob Mindestanforderungen erfüllt sind. 
		 **/

        return true;
	}
    
    function Reset() {
        $this->mValue = '';
    }

}
?>
