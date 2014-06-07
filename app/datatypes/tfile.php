<?php
/**
 * Datentyp zum Verwalten von hochgeladenen Dateien.
 * @version 0.1 Einfacher Datentyp
 * @version 0.2 Verwendet hMimeTypes zum Validieren von Mime-Types
 */
class tFile extends tDatatype implements iDataTypes {
	
	public $mFileType = '';
	public $mAllowedFileTypes = '';
	
	var $mTmpFile = '';		// Variablen werden gesetzt, wenn per Post was kommt
	var $mDestination = '';
	var $mOwner;

	/**
	 * Parameter zur Initialisierung des Datentyps
	 * @param array $pInitParams
	 */
	function __construct($pInitParams = array()) {
		parent::__construct($pInitParams);

		if (array_key_exists('AllowedFileTypes', $pInitParams)) {
			$this->mAllowedFileTypes = $pInitParams['AllowedFileTypes'];
		}
	
		if (array_key_exists('Owner', $pInitParams)) {
			$this->mOwner = &$pInitParams['Owner'];
		}		
		
	}
	
	/**##############################################################################
	 * 		Implementierung iDatatypes
	 * ##############################################################################*/
	
	function getValue() {
		return $this->mValue;	// Liefert den Datei-Pfad relativ zum Upload-Ordner
	}
	
	function setValue($pValue){	// Wenn das aus der Datenbank kommt, dann geht das ja auch so zurück.
		$this->mValue = $pValue;
	}
	
	function LoadFromPost(){

		if(isset($_FILES[$this->mFieldname])) {
			
			if ($this->mValue != '') {
					// @todo Lösche altes Bild 
			}
			
			
			
			$new_file = $_FILES[$this->mFieldname];
			
			if ($new_file['name'] != '') {

				$this->mValue = '';	// Wenn es Probleme gibt, dann wird dieses Feld nicht gesetzt und Validierung schlägt fehl!!!
				
				$file_size = bytesToSize1024($new_file['size'], 1);
				if ($file_size < getMaxUploadSize()) {
					$file_name = $new_file['name'];
					$file_type = $new_file['type'];
				
					//Alt: if ((count($this->mAllowedFileTypes) < 1) || (array_key_exists($file_type, $this->mAllowedFileTypes))) {
					if (hMimeTypes::isAllowedMimeType($file_type,$this->mAllowedFileTypes)) {
					
						//$file_path = UPLOAD_PATH.$file_name;
	
						$destination = date('Y/m/d/').hSalt::Salt(time().$this->mFieldname).'/';
						$file_value = $destination.$file_name;
						$file_path = UPLOAD_PATH.$file_value;
				
						$this->mValue = $file_value;
						$this->mTmpFile =  $new_file['tmp_name'];
						$this->mDestination = $destination;
					}
					else {
						hError::Add('Datei entspricht nicht den erlaubten Datentypen!');
						return false;
					}
				}
				else {
					hError::Add(__('Datei ist zu groß!'));
					return false;
				}
				
			}
			else {
				if(($this->mRequired) && ($this->mValue == '')) {
					hError::Add(__('Probleme beim Datei-Upload!'));
				}
			}
		}
		
	}
	
	/**
	 * Speicher die Temporäre-Datei im Entsprechenden Verzeichnis. Wird nach erfolgreicher Validierung und erfolgreichem Insert-Befehl aufgerufen.
	 */
	function SaveTmpFile() {
		$file_path = UPLOAD_PATH.$this->mValue;
		
		// Pfad erstellen
		
		if ($this->mTmpFile != '') {
			if (!is_dir(UPLOAD_PATH.$this->mDestination)) mkdir(UPLOAD_PATH.$this->mDestination,0777,TRUE);
		
			if (move_uploaded_file($this->mTmpFile, $file_path)){
				hDebug::Add($this->mFieldname.' Dateiname: '.$this->mValue.' wurde kopiert');
			}
			else {
				hDebug::Add('TMP-File: '.$this->mTmpFile.' Dateiname: '.$this->mValue.' GEHT VERLOREN GEHT VERLOREN GEHT VERLOREN GEHT VERLOREN GEHT VERLOREN ');
			}			
		}
		

	}
	
	function showValue() {
		
		$return = '';
		
		if($this->mValue == '') {
			return '';
		}
		
	
		$file = UPLOAD_PATH.$this->mValue;
		$file_name = basename($file);
		if(file_exists($file)){
			$file_size = filesize($file);
		}
		if($file_name.$file_size != '') {
			$return = $file_name.' - '.__('Größe:').': '.BytesToSize1024($file_size);
			
			if (($this->mOwner != null) && (method_exists($this->mOwner, 'getClassControllerName')) && ($this->mOwner->getClassControllerName() != '')) {
				
				$return = hHtml::getLinkButtonTag($return,hRouter::getCompleteLink('file',hRouter::getLanguage(),$this->mOwner->getClassControllerName(),'download',$this->mOwner->getToken()),'download');
			}
			else {
				//var_dump($this->mOwner);
			}
			
		}
		
		
		return $return;
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
        	if($this->mValue == '') {
        		$required_str = ' required="required"';
        	}
        }
        
        
	    /* Value */
        $value_string = '';
        if ($this->mValue != '') {
            $value_string = '<div class="saved_file_name">'.$this->showValue().'</div>';
        }
        
        /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
        	$error_str = hError::showMessages($this->mFieldname);
        	$error_class_str = ' class="inputfielderror"'; 
        }
        
        $max_file_size_string = '<br />'.__('Maximale Dateigröße').': '.BytesToSize1024(getMaxUploadSize());
        
        /* Hinweis zum Ausfüllen */
		$hint_string = '';
        if($this->mHint != '') {
        	$hint_string = '<span class="hint">'.__($this->mHint).$max_file_size_string.'</span>';
        }
        elseif(count($this->mAllowedFileTypes) > 0) {
        	$allowed_file_types = '';
        	foreach ($this->mAllowedFileTypes as $allowed_file_type) {
        		$allowed_file_types .= hMimeTypes::getExplanation($allowed_file_type).', ';
        	}
        	$allowed_file_types = substr($allowed_file_types,0,-2);
        	$hint_string = '<p class="hint">'.__('Erlaubte Datei-Typen sind:').' '.$allowed_file_types.$max_file_size_string.'</p>';
        }
        
        
        
        $ret = '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label>'.$value_string.'<input type="file" '.$id_string.$name_string.$required_str.' />'.$hint_string.$error_str.'</div>';
		return $ret; 
	}
	
	public function Validate(){
		
		$parent_validation = parent::StdValidate();
		if (is_bool($parent_validation)) {
			hDebug::Add('Standard-Validierung liefert bool.'.$this->mFieldname.'~'.$this->mValue);
			return $parent_validation;
		}
        return true;
	}
    
    function Reset() {
        $this->mValue = '';
    }
    
    
    function getFileType() {
    	if($this->mValue == '') return '';
    	$finfo = new finfo(FILEINFO_MIME);
    	$file_type = $finfo->file(UPLOAD_PATH.$this->mValue);
    	hDebug::Add('File-Type: '.$file_type);
    	finfo_close($finfo);
    	return $file_type;
    }
    
	function getFilePath() {
    	if($this->mValue == '') return '';
    	$file = UPLOAD_PATH.$this->mValue;
		if (file_exists($file)) {
  			return $file;
		}
    }
    
    function getFileName() {
    	return basename($this->mValue);
    }
    
	function getFileSize() {
    	return filesize(UPLOAD_PATH.$this->mValue);
    }

    /**
     * @abstract Löscht bestehende Datei von der Festplatte.
     */
    function Delete() {
    	if ($this->mValue == '') return;
    	if (unlink(UPLOAD_PATH.$this->mValue)) {
    		hDebug::Add($this->mValue.' wurde gelöscht ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒!');
    		$dirname = substr(UPLOAD_PATH.$this->mValue, 0, -(strlen(basename($this->mValue))));
    		rmdir($dirname);
    	}
    	else {
    		hDebug::Add($this->mValue.' wurde nicht gelöscht!');
    	}

    }
    
    /**
     * Erwartet Array zum setzen der erlaubten Mime-Types. Mime-Types müssen im Key angegeben sein.
     * @param array of Mime-Types $pAllowedFileTypes
     * @todo prüfen ob noch weitere Überprüfungen nötig sind.
     */
    function setAllowedFileTypes($pAllowedFileTypes = '') {
    	if(is_array($pAllowedFileTypes)) {
    		$this->mAllowedFileTypes = $pAllowedFileTypes;
    	}
    }
    
}
?>