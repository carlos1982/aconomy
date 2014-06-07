<?php
/**
 * @abstract Diese Klasse ist die Standard-Klasse für "einfache" Daten-Objekte.
 * @version 0.1	- 18.10.2011 
 * @version 0.1.1 - Reset-Funktion eingebaut
 * @version 0.1.2 - 03.11.2011  Edit()-Funktion eingebaut
 * @author Carlos Cota Castro
 */
class oStandard extends oMySql{

	/**##############################################################################
	 * 		VARIABLEN
	 * ##############################################################################*/
	

	var $dID = '';
	var $dEncryptID = '';
	
	protected $mListFields = '';
	
	
	/**
	 * Init-Funktion. Hier werden alle Felder instanziert, die jedes Objekt haben sollte.
	 * Abgeleitete Klassen sollten in der Init-Funktion parent::Init() aufrufen.
	 */
	function Init() {
		$this->ResetSQL();
		$this->dID = new tInteger(array('Fieldname' => 'ID', 'Label' => 'ID', 'required' => 'true'));
		$this->dEncryptID = new tString(array('Fieldname' => 'dEncryptID', 'Label' => 'Token', 'required' => 'true'));
		hDebug::Add('Elternfunktion wurde geladen.');
	}
	
		
	public function Reset() {
		foreach (get_object_vars($this) as $key => $value) {
			if (substr($key, 0, 1) == 'd') {
				hDebug::Add($key);
				hDebug::Add(get_class( $this->{$key}));
				$this->{$key}->Reset();
				//$this->{$value}->Reset();
			}
		}
	}
    
    public function Edit() {
		foreach (get_object_vars($this) as $key => $value) {
			if ((substr($key, 0, 1) == 'd') && ($key != 'dID') && ($key != 'dEncryptID') && (method_exists($this->{$key}, 'Edit'))) {
				echo $this->{$key}->Edit();
			}
		}
	}
    
    /**
    *   @abstract Wenn etwas per Post übergeben wurde, dass zu einem Feld im Objekt passt, dann wird True zurückgegeben.
    */
    public function LoadFromPost() {
    	$ret = false;
    	hDebug::Add('LoadFromPost in Standard gestartet');
    	
    	hDebug::Add('LoadFromPost gestartet für Objekt');
    	
		foreach (get_object_vars($this) as $key => $value) {
			
			if (substr($key, 0, 1) == 'd') {
				if (is_object($this->{$key})){
					if((isset($_POST[$this->{$key}->getFieldname()])) || (get_class($this->{$key}) == 'tFile') || (get_class($this->{$key}) == 'tDateTime')) {
						
						if(($key != 'dID') && ($key != 'dEncryptID') && ($this->{$key}->mEditable)) {
							$this->{$key}->LoadFromPost();	
						}
	                	
	                	$ret = true;
	                }
	                else {
	                	hDebug::Add('Wurde nicht per Post übergeben: '.$key);
	                }
				}
				else {
					hDebug::Add('Versuch auf Feld zuzgreifen beim Laden From Post: '.$key);
				}
                
			}
		}
		return $ret;
	}
	
	/**
	 * SQL-Befehl wird benutzt um Daten zu holen.
	 */
	public function LoadByToken() {
        $token = hRouter::getToken();
		if ($token != '') {
			$this->AddCondition('EncryptID', $token);
			return $this->LoadFromDB();
		}
		else {
			return false;
		}
	}
	
	
	/**
	 * SQL-Befehl wird benutzt um Daten zu holen.
	 */
	public function LoadFromDB() {
		$request = '';
		$request = $this->getSqlRequest();
		if($request != '') {
			$result = mysql_query($request);
			hDebug::Add($request);
			if (mysql_num_rows($result) == 1) {
				while ($data = mysql_fetch_assoc($result)) {
					
					foreach ($data as $field_name => $value) {
						$field_name = 'd'.$field_name;
						if (is_object($this->$field_name)) {		// Es können in der SQL-Anweisung Felder vorkommen, die nicht im Objekt enthalten sind.
							$this->$field_name->setValue($value);
						}
						else {
							hDebug::Add('Kann Feld nicht finden: '.$field_name);
						}
					} 
				}
				return true;
			}
		}
		return false;
		
	}
	
	/**
	 * Wenn das Objekt über eine Listenklasse instanziert wird, dann werden die Werte direkt übergeben.
	 * @param unknown_type $obj
	 */
	public function LoadFromArray($pData = array()) {
		if (count($pData) < 3) { // Irgendwas stimmt nicht, da mindestens ID und EncryptID übergeben werden müssen
			return false;
		}
		foreach ($pData as $field_name => $value) {
			$field_name = 'd'.$field_name;
			if (is_object($this->$field_name)) {		// Es können in der SQL-Anweisung Felder vorkommen, die nicht im Objekt enthalten sind.
				$this->$field_name->setValue($value);	// Wir können annehmen, dass das Datentyp-Objekt instanziert wurde, da LoadFromArray nur aufgerufen wird, wenn Objekt instanziert ist.
			}
			else {
				hDebug::Add('Kann Feld nicht finden: '.$field_name);
			}
		} 
		return true;
	}
	
   
	/**
	 *	Liefert für Create und Insert-Befehle die jeweiligen Set, Strings 
	 */
	public function getSqlSetter() {
		$ret = '';
		foreach (get_object_vars($this) as $key => $value) {
				
				if ((substr($key, 0, 1) == 'd') && ($key != 'dID') && ($key != 'dEncryptID')) {

					$db_field_obj = $this->$key;
                    if (
                    	(is_object($db_field_obj)) &&
                    	($db_field_obj->Validate())
                    ) {
	                	$ret .= $db_field_obj->getSqlSetter();
                    }
                    else {
                    	if((is_object($db_field_obj))) {
                    		hError::Add(__('"{#0}" wurde nicht richtig gesetzt!',array($db_field_obj->getFieldname())));
							hDebug::Add($db_field_obj->getFieldname().': Validierung fehlgeschlagen');
                    	}
                    	else {
                    		hDebug::Add($db_field_obj.': Validierung fehlgeschlagen');
                    	}
						return '';
					}
				}
		}
		return $ret;
	}
	
	/**
	 * Verwendung: if(!$obj->Insert()) {... Fehler }
	 */
    public function Insert() {
        
        if (is_array(get_object_vars($this)))
        {
			$request = $this->getSqlSetter();

			if ($request != '') {
				
				$request .= "EncryptID='".hSalt::CreateUniqueID($this->mDBTable)."'";

				$request = 'INSERT INTO '.$this->mDBTable.' SET '.$request;
        		
        		$result = mysql_query($request);
        		
        	
        		if (!mysql_error()) {
	        		hDebug::Add('Insert-Befehl funktioniert:'.$request);
					$this->dID->setValue(mysql_insert_id());
	        		// Dateien wirklich kopieren
	        		foreach (get_object_vars($this) as $key => $value) {
						if (substr($key, 0, 1) == 'd')  {
							$db_field_obj = $this->$key;
								if ((get_class($db_field_obj) == 'tFile') || (get_class($db_field_obj) == 'tImage')){
									$db_field_obj->SaveTmpFile();
								}
						}
	        		}
	        		
    	    		return true;
        		}
        		else {
        			hDebug::Add('Insert-Befehl schlug fehl:'.$request.mysql_error());
        			if (strpos(mysql_error(),'uplicate') > 0) {
        				hError::Add('Es existiert bereits ein entsprechender Datensatz.');
        			}
        			return false;
        		}
			}
			else {
				hDebug::Add('Nichts zum Einfügen vorhanden');
				return false;
			}
        }
        hDebug::Add('Objekt hat keine Daten.');
        return false;
        
	}
	
	
	
	
	/**
	 * Verwendung: if(!$obj->Update()) {... Fehler }
	 * @todo Insert abändern und dann Funktionen analog benutzen
	 */
    public function Update() {
        
        if (is_array(get_object_vars($this)))
        {
        	$request = $this->getSqlSetter();

			if ($request != '') {
		
				$request = substr($request, 0, -2);
        		$request = 'UPDATE '.$this->mDBTable.' SET '.$request." WHERE ID=".$this->dID->getValue()." and EncryptID='".$this->dEncryptID->getValue()."'";
        		
        		$result = mysql_query($request);
        	
        		if (!mysql_error()) {
	        		hDebug::Add('Update-Befehl funktioniert:'.$request);
	        		// Dateien wirklich kopieren
	        		foreach (get_object_vars($this) as $key => $value) {
						if (substr($key, 0, 1) == 'd')  {
							$db_field_obj = $this->$key;
								if ((get_class($db_field_obj) == 'tFile') || (get_class($db_field_obj) == 'tImage')){
									$db_field_obj->SaveTmpFile();
								}
						}
	        		}
    	    		return true;
        		}
        		else {
        			hDebug::Add('Insert-Befehl schlug fehl:'.$request.mysql_error());
        			if (strpos(mysql_error(),'Duplicate') >= 0) {
        				hError::Add('Es existiert bereits ein entsprechender Datensatz.');
        			}
        			return false;
        		}
			}
			else {
				hDebug::Add('Nichts zum Einfügen vorhanden');
				return false;
			}
        }
        hDebug::Add('Objekt hat keine Daten.');
        return false;
        
	}
	
	/**
	 * @abstract Löscht den Datensatz
	 * Verwendung: if(!$obj->Delete()) {... Fehler }
	 */
    public function Delete() {
        
		if($this->dEncryptID->getValue() != '') {
		
       		$request = 'DELETE FROM '.$this->mDBTable." WHERE ID='".mysql_real_escape_string($this->dID->getValue())."' and EncryptID='".mysql_real_escape_string($this->dEncryptID->getValue())."'";
        		
       		$result = mysql_query($request);
        	
       		if (!mysql_error()) {
        		hDebug::Add('Delete-Befehl funktioniert:'.$request);
        		
       			foreach (get_object_vars($this) as $key => $value) {
					if (substr($key, 0, 1) == 'd')  {
						$db_field_obj = $this->$key;
						if ((get_class($db_field_obj) == 'tFile') || (get_class($db_field_obj) == 'tImage')){
							$db_field_obj->Delete();
						}
					}
	        	}
   	    		return true;
       		}
       		else {
       			hDebug::Add('Delete-Befehl schlug fehl:'.$request.mysql_error());
       			return false;
       		}
		}
		else {
			hDebug::Add('Nichts zum Einfügen vorhanden');
			return false;
		}

        hDebug::Add('Objekt hat keine Daten.');
        return false;
        
	}
	

	/**
	 * Geht alle Felder durch und Schaut ob Sie Validieren.
	 */
	function Validate() {
		foreach (get_object_vars($this) as $key => $value) {
			if (substr($key, 0, 1) == 'd')  {
				$db_field_obj = $this->$key;
				if (!$db_field_obj->Validate())  {
					hDebug::Add($db_field_obj->getFieldname().': Validierung fehlgeschlagen! Wert: '.$db_field_obj->getValue());
					return false;                    	
				}
			}
		}
		return true;
	}
	
	
	function getID() {
		return $this->dID->getValue();
	}
	
	/**
	 * Liefert die EncryptID
	 */
	public function getEncryptID() {
		return $this->dEncryptID->getValue();
	}
	
	/**
	 * Alias für getEncryptID()
	 */
	public function getToken() {
		return $this->getEncryptID();
	}
	
	/**
	 * In ListenObjekten kann diese Funktion aufgerufen werden um die Zeile in einer Liste auszugeben. 
	 */
	function showEditListItem($pShow = true, $pEdit = true, $pDelete = false) {

		if (!($this->dID->getValue() > 0)) {
			return '';
		}
		else {
			$ret = '<tr>';
			foreach ($this->showEditListVarNames() as $var_name) {
					if(isset($this->$var_name)) {
						$db_field_obj = $this->$var_name;	
						$ret .= '<td>'.$db_field_obj->showValue().'</td>';
					}
					elseif (method_exists($this, 'show'.$var_name)){
						$method_name = 'show'.$var_name;
						$ret .= '<td>'.$this->$method_name().'</td>';
					}

			}
			if ($pShow || $pEdit || $pDelete) {
				$ret .= '<td>';
				if($pShow) $ret .= $this->getShowLink();
				if($pEdit) $ret .= $this->getEditLink();
				if($pDelete) $ret .= $this->getDeleteLink();
				$ret .= '</td>';
			}
			$ret .= '</tr>';
		}
		return $ret;
	}
	
	
	/**
	 * In ListenObjekten kann diese Funktion aufgerufen werden um die Zeile in einer Liste auszugeben. 
	 */
	function showListItem() {
		return $this->showEditListItem(true,false,false);
	}
	
	
	function showEditListHeader($pShow = true, $pEdit = true, $pDelete = false) {
		$ret = '<table class="editlist"><thead>';
		$ret .= '<tr>';
		foreach ($this->showEditListVarNames() as $var_name) {
			if(isset($this->$var_name)) {
				$db_field_obj = $this->$var_name;
				$ret .= '<th>'.$db_field_obj->getLabel().'</th>';
			}
			elseif (method_exists($this, 'show'.$var_name)){
				$ret .= '<th>'.__($var_name).'</th>';
			}
			
		}
		if ($pShow || $pEdit || $pDelete) {
			$ret .= '<th></th>';
		}
		$ret .= '<tr>';
		$ret .= '</thead><tbody>'; // @todo Prüfen ob es Sinnvoll ist an dieser Stelle Filter auszugeben.
		return $ret;
	}
	
	/**
	 * Welche Felder sollen in den Listen-Ausgaben ausgegeben werden?
	 */
	function showEditListVarNames() {
		if(is_array($this->mListFields)) {
			$var_names = $this->mListFields;
		}
		else {
			$var_names = array_keys(get_object_vars($this)) ;
		}
		$ret = array();
		foreach ($var_names as $var_name) {
			if (is_object($this->$var_name)
				 && (substr($var_name, 0, 1) == 'd')
				 && ($var_name != 'dEncryptID')
				 && ($var_name != 'dPassword')
				 && (($var_name != 'dID') || (hSession::getAdminrole() == SUPER_ADMIN))
				){
				$ret[$var_name] = $var_name;
			}
			if(method_exists($this, 'show'.$var_name)){
				$ret[$var_name] = $var_name;
			}

		}
		return array_keys($ret);		
	}
	
	/**
	 * Liefert den Link zum Bearbeiten des Datensatzes
	 */
	function getEditLink() {
		
		$controller_name = substr(strtolower(get_class($this)),1);
		hDebug::Add('Controller-Name für Edit-Link:'.$controller_name);
		if(file_exists(CONTROLLER_PATH.$controller_name.'/aedit.php')) {
			$link = _Link($controller_name,'edit',$this->dEncryptID->getValue());
			return hHtml::getLinkButtonTag(__('Bearbeiten'), $link, 'editlink');
		}
		else {
			return '';
		}
		
	}
	
	/**
	 * Liefert den Link zum Ansehen des Datensatzes
	 */
	function getShowLink() {
		$controller_name = substr(strtolower(get_class($this)),1);
		
		if(file_exists(CONTROLLER_PATH.$controller_name.'/ashow.php')) {
			hDebug::Add('Controller-Name für Link: '.$controller_name);
			$link = _Link($controller_name,'show',$this->dEncryptID->getValue());
			return hHtml::getLinkButtonTag(__('Anzeigen'), $link, 'showlink');
		}
		else {
			return '';
		}
		
		
	}
	
	/**
	 * Liefert den Link zum Ansehen des Datensatzes
	 */
	function getDeleteLink() {
		//$controller_name = substr(strtolower($this->mDBTable),0,-1);
		$controller_name = substr(strtolower(get_class($this)),1);
		
		if(file_exists(CONTROLLER_PATH.$controller_name.'/adelete.php')) {
			$link = _Link($controller_name,'delete',$this->dEncryptID->getValue());
			return hHtml::getLinkButtonTag(__('Löschen'), $link, 'deletelink');
		}
		else {
			return '';
		}
	
	}
	
	/**
	 *	Liefert ein paar formatierte Infos 
	 */
	public function showSelectedFields() {
		$field_names = func_get_args();
		if (count($field_names) == 0) {
			hDebug::Add('Es wurde nicht angegeben, was ausgegeben werden soll');
			return;
		}
		$ret = '<ul>';
		foreach ($field_names as $attribute_name) {
			if (is_object($this->$attribute_name)){
				$ret .= '<li>'.__($this->$attribute_name->getLabel()).': '.$this->$attribute_name->showValue().'</li>';
			}
		}
		$ret .= '</ul>';
		return $ret;
	}
	
	function LoadChildList($pListName = '') {
		
		if ($pListName == '') {
			hDebug::Add('Was soll geladen werden, wenn Listenname nicht angegeben ist?');
			return false;
		}
		$list_name = 'm'.$pListName;
		if (!array_key_exists($list_name, get_object_vars($this))) {
			hDebug::Add('Dieses Objekt hat keine Liste mit dem Namen "'.$list_name.'"!');
			return false;
		}
		
		if($this->dID->getValue() != '') {
			$class_name = 'l'.$pListName;
			$this->$list_name = new $class_name();
			$this->$list_name->AddCondition($this->getFieldnameForForeignkey(),$this->dID->getValue());
			return $this->$list_name->LoadFromDB();
		}
		else {
			hDebug::Add('Dieses Objekt ist noch nicht geladen worden!');
			return false;
		}
	}

	function getFieldnameForForeignkey() {
		return substr($this->mDBTable, 0, -1);
	}
	
	/**
	 * @Liefert z.B. bei oPostType posttype
	 */
	public function getClassControllerName() {
		return strtolower(substr(get_class($this), 1));
	}


    public function showDebug() {

        if (!DEBUG) {
            return;
        }

        foreach (get_object_vars($this) as $key => $value) {
            if (substr($key, 0, 1) == 'd')  {
                $db_field_obj = $this->$key;
                echo '<p>'.$key.' => '.$db_field_obj->getValue().'</p>';
            }
        }
    }
	
}
?>
