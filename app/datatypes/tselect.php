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

class tSelect extends tDatatype implements iDataTypes {

	var $mValue = array();
	var $mAllowedValues = array();
	var $mMultiple = false;

	function __construct($pInitParams = array()) {
		parent::__construct($pInitParams);
		
		if ((array_key_exists('AllowedValues', $pInitParams)) &&
			(is_array($pInitParams['AllowedValues']))) {
			$this->mAllowedValues = $pInitParams['AllowedValues'];
		}
		
		if ((array_key_exists('Multiple', $pInitParams)) &&
			(is_bool($pInitParams['Multiple']))) {
			$this->mMultiple = $pInitParams['Multiple'];
		}
	}
	
	/**
	 * @return array()
	 * @see interfaces/iDataTypes::getValue()
	 */
	function getValue() {
		if ($this->mMultiple == false) {
			return $this->mValue[0];
		}
		else {
			return $this->mValue;
		}
	}

	/**
	 *	@param $pAllowedValues = array() 
	 */
	function setAllowedValues($pAllowedValues = array()) {
		if ((is_array($pAllowedValues)) && (count($pAllowedValues) > 0)) {
			$this->mAllowedValues = $pAllowedValues;	
		}
	}
	
	/**
	 * @param bool $pMultiple
	 */
	function setMultiple($pMultiple = false) {
		if (is_bool($pMultiple)) {
			$this->mMultiple = $pMultiple;
		}
	}
	
	/**
	 * Erwartet ein Array() oder einen einzelnen Wert.
	 * @see interfaces/iDataTypes::setValue()
	 */
	function setValue($pValue){
		if (is_array($pValue)) {
			$this->mValue = $pValue;
		}
		else {
			$this->mValue = explode(',',$pValue);
		}
	}
	
	/**
	 * Erwartet ein Array()
	 */
	function addValue($pValue){

		$key_array = array();
		foreach ($this->mValue as $value) {
			$key_array[$value] = 1;
		}
		
		if (is_array($pValue)) {
			foreach ($pValue as $value) {
				$key_array[$value] = 1;
			}
		}
		else {
			$key_array[$pValue] = 1;
		}
		
		$this->mValue = array();
		foreach ($key_array as $key => $value) {
			$key_array[] = $key;
		}
	}
	
	function LoadFromPost(){
		if(isset($_POST[$this->mFieldname])) {
			$value = $_POST[$this->mFieldname];
			$this->setValue($value);
		}
	}
	
	function showValue() {

		$return_values = array();
		if ((is_array($this->mAllowedValues)) && (count($this->mAllowedValues) > 1)) {
			foreach ($this->mAllowedValues as $allowed_value => $value_label) {
	        	
		        if (in_array($allowed_value, $this->mValue)) {
		        	if ((is_numeric($value_label)) && (!is_numeric($allowed_value))) {
		        		$return_values[] = $allowed_value;	
		        	}
		        	else {
		        		$return_values[] = $value_label;
		        	}
		        }
			}
		}
		else {
			if (is_array($this->mValue)) {
				foreach ($this->mValue as $value) {
					$return_values[] = $value;
				}
			}
			else {
				$return_values[] = $this->mValue;
			}
		}
		if (count($return_values) > 1) {
			$ret = '<ul>';
			foreach ($return_values as $value) {
				$ret .= '<li>'.$value.'</li>';
			}
			$ret .= '</ul>';
			
		}
		else {
			$ret = $return_values[0];	
		}
		return $ret;
	}
	
	function Edit() {
		
		if (!parent::Edit()) {
			return;
		}
		
        if ($this->mFieldname != '') {
            $id_string = ' id="'.$this->getFieldname().'"';
            $for_string = ' for="'.$this->getFieldname().'"';
            $multiple_string = '';
            if($this->mMultiple){
            	$multiple_string = ' multiple="multiple"';
            	$name_string = ' name="'.$this->getFieldname().'[]"';	
            }
            else {
            	$name_string = ' name="'.$this->getFieldname().'"';
            }
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
        	$required_str = 'required="required"';
        }
        
        /* Value */
        $value_string = '';
        if (($this->mMultiple == false) && (count($this->mAllowedValues) > 1) && (count($this->mAllowedValues) < 11)) {
        	$value_string = '<option>'.__('Bitte wählen').'</option>';
        }
		if (($this->mRequired == false) && ($this->mMultiple == false)) {
        	$value_string .= '<option></option>';
        }
        foreach ($this->mAllowedValues as $allowed_value => $value_label) {
        	$sel_string = '';
	        if ((is_array($this->mValue) && (in_array($allowed_value, $this->mValue))) || ($allowed_value == $this->mValue)) {
	            $sel_string = ' selected="selected"';
	        }
	        $value_string .= '<option '.$sel_string.' value="'.$allowed_value.'">'.__($value_label).'</option>';
        }
		        
	    /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->getFieldname());
        	$error_class_str = ' class="inputfielderror"'; 
        }
        if ((count($this->mAllowedValues)  > 10) && ($this->mMultiple == false)  && (1 == 0)) {
           return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><input type="number" '.$id_string.$name_string.$multiple_string.$required_str.' list="'.$this->getFieldname().'_list" />
            <datalist id="'.$this->getFieldname().'_list">'.$value_string.'</datalist>'.$error_str.'</div>'; 
        }
        else {
            return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><select '.$id_string.$name_string.$multiple_string.$required_str.'  />'.$value_string.'</select>'.$error_str.'</div>';
        }
        
	}
	
	function Validate() {
		
		$parent_validation = parent::StdValidate();
		if (is_bool($parent_validation)) {
			hDebug::Add($this->getFieldname().': ParentValidation liefert Boolschen Wert bei Select.');
			return $parent_validation;
		}
		
		if($this->mValue == '') {
			hDebug::Add($this->getFieldname().': Es wurde kein Wert angegeben');
			return false;
		}

		if (is_array($this->mValue)) {
			if (count($this->mValue) > 0) {
				foreach ($this->mValue as $value) {
					if(array_key_exists($value, $this->mAllowedValues)) {
						// Alles OK
					}
					else {
						hDebug::Add($this->getFieldname().': Wert "'.strip_tags($value).'" ist nicht erlaubt. Erlaubt sind: '. implode(', ',$this->mAllowedValues));
						return false;
					}
				}
			}
			else {
				return false;
			}
				
		}
		else {
			if(array_key_exists($this->mValue, $this->mAllowedValues)) {
				// Alles OK
			}
			else {
				return false;
			}
		}
		return true;
	}
	
	function Reset() {
        $this->mValue = array('');
    }
}
?>