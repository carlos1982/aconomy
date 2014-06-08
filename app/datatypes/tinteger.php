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
 * @todo	Sicherstellen, dass es wirklich ein Integer ist und nicht nur ein float.
 */
class tInteger extends tDatatype implements iDataTypes {
    public $mMinValue = '';
    public $mMaxValue = '';

	/**
	 * Parameter zur Initialisierung des Datentyps
	 * @param array $pInitParams
	 */
	function tInteger($pInitParams = array()) {

		parent::__construct($pInitParams);

		if ((array_key_exists('MaxValue', $pInitParams)) &&
			(is_numeric($pInitParams['MaxValue']))) {
			$this->mMaxValue = $pInitParams['MaxValue'];
		}
		if ((key_exists('MinValue', $pInitParams)) &&
			(is_numeric($pInitParams['MinValue']))) {
			$this->mMinValue = $pInitParams['MinValue'];
		}
	}
	
	public function setMaxValue($pMaxValue) {
		if(is_numeric($pMaxValue)) {
			$this->mMaxValue = $pMaxValue;
		}
	}
	
	/**##############################################################################
	 * 		Implementierung iDatatypes
	 * ##############################################################################*/
	
	function getValue() {
		return $this->mValue;
	}
	
	function setValue($pValue){
		$this->mValue = $pValue;
	}
	
	function LoadFromPost(){
		if(isset($_POST[$this->getFieldname()])) {
			$this->mValue = $_POST[$this->getFieldname()];	// Bei Integer passiert nichts weiter
		}
	}
	
	function showValue() {
		return $this->mValue; // Bei Integer gibt es keinen Unterschied zu getValue()
	}
	
	public function Edit() {
		
		if (!parent::Edit()) {
			return;
		}
		
        if ($this->mFieldname != '') {
            $id_string = ' id="'.$this->getFieldname().'"';
            $for_string = ' for="'.$this->getFieldname().'"';
            $name_string = ' name="'.$this->getFieldname().'"';
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
            $label_string = $this->getFieldname();
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
        /* Ist wegen Load From Post nicht notwendig
        if (isset($this->mFieldname, $_POST)) {
            $value_string = ' value="'.$_POST[$this->getFieldname()].'"';
        }
        */
        
        /* Min-Value */
        $min_str = '';
        if ((is_numeric($this->mMinValue)) && ($this->mMinValue > 0)) {
        	$min_str = ' min="'.$this->mMinValue.'"';
        }
        
	    /* Max-Value */
        $max_str = '';
        if ((is_numeric($this->mMaxValue)) && ($this->mMaxValue > 0)) {
        	$max_str = ' max="'.$this->mMaxValue.'"';
        }

	    /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->getFieldname());
        	$error_class_str = ' class="inputfielderror"'; 
        }
        
        return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><input type="number" '.$id_string.$name_string.$value_string.$min_str.$max_str.$required_str.'  />'.$error_str.'</div>';
	}
	
	public function Validate(){
		
		$parent_validation = parent::StdValidate();
		if (is_bool($parent_validation)) {
			return $parent_validation;
		}
		
		// Egal ob Wert verlangt wird oder nicht, wenn was gesetzt wird, dann muss der Wert Korrekt sein.		
		if (!is_numeric($this->mValue)) {
            hError::AddFormError('Bitte Ganzzahl angeben.',$this->getFieldname());
            return false;
        }
		if (($this->mMinValue != '') && ($this->mValue < $this->mMinValue)) {
            hError::AddFormError(__('Wert muss höher als {#0} sein.', array($this->mMinValue)),$this->getFieldname());
            return false;
        }
        if (($this->mMaxValue != '') && ($this->mValue > $this->mMaxValue)) {
            hError::AddFormError(__('Wert muss kleiner als {#0} sein.', array($this->mMaxValue)),$this->getFieldname());
            return false;
        }
        return true;
	}
    
    function Reset() {
        $this->mValue = '';
    }

    public function IncreaseValue($pValue = 1) {
        if (is_numeric($pValue) && is_integer(intval($pValue))) {
            $this->mValue += $pValue;
        }
    }

    public function DecreaseValue($pValue = 1) {
        if (is_numeric($pValue) && is_integer(intval($pValue))) {
            $this->mValue -= $pValue;
        }
    }

}
?>
