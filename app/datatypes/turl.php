<?php
/**
 * Typen-Klasse für Strings.
 * @author Carlos Cota Castro
 * @version 0.1
 */
class tUrl extends tString {
	
	function __construct($pInitParams = array()) {
		parent::__construct($pInitParams);

		$this->mPattern = '^(https?|ftp)\:\/\/([a-z0-9-.]*)\.([a-z]{2,3})(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?';
	
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
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->mFieldname);
        	$error_class_str = ' class="inputfielderror"'; 
        }
        
        $hint_string = '';
        if($this->mHint != '') {
        	$hint_string = '<span class="hint">'.__($this->mHint).'</span>';
        }
        
        $input_type = 'url';
        
		return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><input type="'.$input_type.'" '.$id_string.$name_string.$required_string.$value_string.'  />'.$hint_string.$error_str.'</div>';
        
        
        
	}
	
	public function Validate(){
		
		$parent_validation = parent::Validate();
		if (is_bool($parent_validation)) {
			if ($parent_validation == false) {
				if ($this->mDomain != '') {
					hError::AddFormError(__('Bitte geben Sie eine gültige "{#0}"-URL an.',array($this->mDomain)),$this->mFieldname);
				}
				else {
					hError::AddFormError(__('Bitte geben Sie eine gültige URL an.'),$this->mFieldname);
				}
			}
			else {
				hDebug::Add(__('URL ist gültig.'));
			}
			return $parent_validation;
		}

        return true;
	}
    
    function Reset() {
        $this->mValue = '';
    }

}
?>
