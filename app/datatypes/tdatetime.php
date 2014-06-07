<?php
/**
 * Typen-Klasse für Strings.
 * @author Carlos Cota Castro
 * @version 0.1
 */
class tDateTime extends tDatatype implements iDataTypes {
	
	public $mPattern;
	public $mHint;

	/**
	 * Parameter zur Initialisierung des Datentyps
	 * @param array $pInitParams
	 */
	function __construct($pInitParams = array()) {
		parent::__construct($pInitParams);
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

		if(isset($_POST[$this->mFieldname.'_day'])) {
			
			$date_string = $_POST[$this->mFieldname.'_year'].'-';
			$date_string .= $_POST[$this->mFieldname.'_month'].'-';
			
			switch ($_POST[$this->mFieldname.'_month']) {	// Prüfen ob Datum versehentlich falsch gesetzt wurde
				case '01':
				case '03':
				case '05':
				case '07':
				case '08':
				case '10':
				case '12':
							if ($_POST[$this->mFieldname.'_day'] > '31') {
								$date_string .= '31 ';
							}
							else {
								$date_string .= $_POST[$this->mFieldname.'_day'].' ';
							}
							break;
				
				case '04':
				case '06':
				case '09':
				case '11':	if ($_POST[$this->mFieldname.'_day'] > '30') {
								$date_string .= '30 ';
							}
							else {
								$date_string .= $_POST[$this->mFieldname.'_day'].' ';
							}
							break;
				
				case '02':	if ($_POST[$this->mFieldname.'_day'] > '29') {
								$date_string .= '29 ';
							}
							else {
								$date_string .= $_POST[$this->mFieldname.'_day'].' ';
							}
							break;

			}
			
			
			$date_string .= $_POST[$this->mFieldname.'_hour'].':';
			$date_string .= $_POST[$this->mFieldname.'_minute'].':00';

			hDebug::Add($this->mFieldname.': '.$date_string);
			
			$this->setValue($date_string);
		}
		hDebug::Add($this->mFieldname.' Wert:'. $this->mValue);
	}
	
	function showValue() {
		return __('{#0} Uhr', array(date('d. M. Y - H:i',strtotime($this->mValue))));
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
        $required_string = '';
        if ($this->mRequired) {
        	$required_string = ' required="required"';
        }
        
        
	    /* Value */
        $value_string = '';
        if ($this->mValue != '') {
            $value_string = ' value="'.$this->mValue.'"';
        }
        
        /* Error-Feedback */
        $error_string = '';
        $error_class_string = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_string = hError::showMessages($this->mFieldname);
        	$error_class_string = ' class="inputfielderror"'; 
        }
        
        $hint_string = '';
        if($this->mHint != '') {
        	$hint_string = '<span class="hint">'.__($this->mHint).'</span>';
        }
        
        $return  = '<div'.$error_class_string.'>';
        $return .= '<label'.$for_string.'>'.$label_string.'</label>';
        
        
        /* 
         * Das Leben könnte so einfach sein mit HTML5:
         * $return .= '<input type="datetime" '.$id_string.$name_string.$required_string.$value_string.'  />';
        */
        
        /* Old-School Inputs */
        
        // Tag _______________________________________________
        $return .=	'<select id="'.$this->mFieldname.'_day" name="'.$this->mFieldname.'_day" class="dayselector">';
        $return .= '<option value=""> </option>';
        for ($i = 1; $i <= 31; $i++) {
        	$option_value = ($i < 10) ? '0'.$i : $i;
        	$option_selected = ($option_value == date('d',strtotime($this->getValue())) && $this->getValue() != '') ? ' selected="selected"' : ''; 
        	$return .= '<option value="'.$option_value.'"'.$option_selected.'>'.$option_value.'</option>';
        }
        $return .=	'</select>.';
        
        // Monat _______________________________________________
        $return .=	'<select id="'.$this->mFieldname.'_month" name="'.$this->mFieldname.'_month" class="monthselector">';
        $return .= '<option value=""> </option>';
        for ($i = 1; $i <= 12; $i++) {
        	$option_value = ($i < 10) ? '0'.$i : $i;
        	$option_selected = ($option_value == date('m',strtotime($this->getValue())) && $this->getValue() != '') ? ' selected="selected"' : ''; 
        	$return .= '<option value="'.$option_value.'"'.$option_selected.'>'.$option_value.'</option>';
        }
        $return .=	'</select>.';
        
        // Jahr _______________________________________________
        $return .=	'<select id="'.$this->mFieldname.'_year" name="'.$this->mFieldname.'_year" class="yearselector">';
        $return .= '<option value=""></option>';
        for ($i = 2012; $i <= 2030; $i++) {
        	$option_value = $i;
        	$option_selected = ($option_value == date('Y',strtotime($this->getValue()))) ? ' selected="selected"' : ''; 
        	$return .= '<option value="'.$option_value.'"'.$option_selected.'>'.$option_value.'</option>';
        }
        $return .=	'</select> - ';
        
        // Stunde _______________________________________________
        $return .=	'<select id="'.$this->mFieldname.'_hour" name="'.$this->mFieldname.'_hour" class="hourselector">';
        $return .= '<option value=""></option>';
        for ($i = 0; $i <= 23; $i++) {
        	$option_value = ($i < 10) ? '0'.$i : $i;
        	$option_selected = ($option_value == date('H',strtotime($this->getValue())) && $this->getValue() != '') ? ' selected="selected"' : ''; 
        	$return .= '<option value="'.$option_value.'"'.$option_selected.'>'.$option_value.'</option>';
        }
        $return .=	'</select>:';
        
        // Stunde _______________________________________________
        $return .=	'<select id="'.$this->mFieldname.'_minute" name="'.$this->mFieldname.'_minute" class="minuteselector">';
        $return .= '<option value=""></option>';
        for ($i = 0; $i <= 59; $i++) {
        	$option_value = ($i < 10) ? '0'.$i : $i;
        	$option_selected = ($option_value == date('i',strtotime($this->getValue())) && $this->getValue() != '') ? ' selected="selected"' : ''; 
        	$return .= '<option value="'.$option_value.'"'.$option_selected.'>'.$option_value.'</option>';
        }
        $return .=	'</select>';
        
        
      
        $return .= $hint_string;
        $return .= $error_string.'</div>';
        return $return; 
	}
	
	public function Validate(){
		
		$parent_validation = parent::StdValidate();
		if (is_bool($parent_validation)) {
			hDebug::Add('Standard-Validierung liefert bool.'.$this->mFieldname.'~'.$this->mValue);
			return $parent_validation;
		}
		if(!is_int(strtotime($this->mValue))) {
			hError::AddFormError(__('{#0} ist kein gültiger Datums/Zeit-Wert.',array($this->mLabel)),$this->getFieldname());
			return false;
		}
		
        return true;
	}
    
    function Reset() {
        $this->mValue = '';
    }

}
?>
