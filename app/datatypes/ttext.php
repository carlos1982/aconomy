<?php
/**
 * Typen-Klasse für Texte.
 * @author Carlos Cota Castro
 * @version 0.1
 */
class tText extends tString{
	
	public $mPattern = '';
	public $mLength = '';

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
            $value_string = $this->showValue();
        }
        
        /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->mFieldname);
        	$error_class_str = ' class="inputfielderror"'; 
        }

        
        return '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label><textarea '.$id_string.$name_string.$required_str.'>'.$value_string.'</textarea>'.$error_str.'</div>';
	}
	

}
?>

