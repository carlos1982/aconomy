<?php
/**
 * Da Daten-Objekte und Listen-Objekte oftmals die selben SQL-Befehle zusammenbauen müssen, wird hier eine Abstrakte Klasse geschrieben von der Listen und Objekt-Klassen abgeleitet werden. 
 */

class oMySql {

	/* Informationen zu den Daten  ----------------------------------------------- */
	var $mDBTable = '';	// Aus welcher Datenbank soll ein Datensatz geladen werden?
	
	/* Variablen für SQL-Anweisung zum Laden von Objekten ------------------------- */
	protected $mSqlFrom = array('');
	protected $mSqlWhere = array('');
	
	protected $mSqlOrder = '';
	
/**##############################################################################
	 * 		SQL-Funktionen
	 * ##############################################################################*/
	
	/**
	 * Wenn ein benutztes Objekt verwendet wird um erneut einen Datensatz zu laden müssen die Werte Initialisiert werden.
	 *
	 */
	public function ResetSQL() {
		$this->mSqlFrom[] = $this->mDBTable;
		$this->mSqlWhere = array();
	}
	
	
	/**
	 * Benutze Diese Funktion um Bedingungen zu generieren, die die selbe Tabelle Betreffen, die in der Objekt-Klasse angegeben ist.
	 * @param unknown_type $pFieldname
	 * @param unknown_type $pValue
	 * @param unknown_type $pOperator
	 */
	public function AddCondition($pFieldname = '', $pValue = '', $pOperator = '=') {
		 $condition_string = $this->GenerateCondition($pFieldname, $pValue, $pOperator, $this->mDBTable);
		 if ($condition_string != '')$this->mSqlWhere[] = $condition_string;
	}
	
	/**
	 * Benutze Diese Funktion um Bedingungen zu generieren, die Tabelle Betreffen ausserhalb von $this->mDBTable,
	 * @param unknown_type $pFieldname
	 * @param unknown_type $pValue
	 * @param unknown_type $pOperator
	 * @package Table-Name	$pTable	// pTable muss vorher in SQL-From gesetzt worden sein!!!
	 */
	public function AddExternalCondition($pTable = '', $pFieldname = '', $pValue = '', $pOperator = '=') {
		 if ($pTable == '') {
		 	hError::Add('Ein Fehler ist aufgetreten');
		 	hDebug::Add('Tabellenname ist leer');
		 	hRouter::NoPermission();
		 	return false;
		 }
		 if (in_array($pTable, $this->mSqlFrom)) {
		 	$condition_string = $this->GenerateCondition($pFieldname, $pValue, $pOperator, $pTable);
		 	if ($condition_string != '') {
		 		$this->mSqlWhere[] = $condition_string;
		 	}
		 	else {
		 		hDebug::Add('Conditionstring ist leer');
		 		hError::Add('Ein Fehler ist aufgetreten');
		 		hRouter::NoPermission();
		 		return false;
		 	}	
		 }
		 else {
		 	hError::Add('Ein Fehler ist aufgetreten');
		 	hRouter::NoPermission();
		 	return false;
		 }
		 
	}
	
	/**
	 * GenerateCondition dient dem hinzufügen von SQL-Bedingungen. Hier wird davon ausgegangen, dass Bedingungen im Normalfall
	 * vom Typ "Feld=Wert" und mit einem und mit anderen Bedingungen Verknüpft werden.
	 * Für 95% aller Anweisungen reicht diese Funktion aus. Diese Funktion ist zu benutzen, da hier mysql_real_escape_string  
	 * verwendet wird und dieses somit nicht so schnell vergessen wird.
	 * @param Fieldname $pFieldname
	 * @param unknown_type $pValue
	 * @param unknown_type $pConnector
	 * @param Table-Name	$pTable
	 * @todo Test-Fälle entwickeln!!!!
	 */
	private function GenerateCondition($pFieldname = '', $pValue = '', $pOperator = '=', $pTable = '')
	{
		$condition_string = '';
		if(	// Einfache String-Vergleiche
			($pOperator == '=') ||
			($pOperator == '!=') ||
			($pOperator == 'like') ||
			($pOperator == 'not like')
		) {
			if (is_numeric($pValue)) {
				$condition_string = $pTable.'.'.$pFieldname.$pOperator.hMySQL::escapeString($pValue);
			}
			else {
				$condition_string = $pTable.'.'.$pFieldname.$pOperator."'".hMySQL::escapeString($pValue)."'";
			}
		}
		elseif( // Numerische-Vergleiche
			($pOperator == '>') ||
			($pOperator == '<') ||
			($pOperator == '>=') ||
			($pOperator == '<=')
		) {
			if(is_numeric($pValue)) {
				$condition_string = $pTable.'.'.$pFieldname.$pOperator.$pValue;
			}
			else {
				$condition_string = $pTable.'.'.$pFieldname.$pOperator."'".mysql_real_escape_string($pValue)."'";
				hDebug::Add('Fehler1: Value is not Numeric');
			}
		}
		elseif ($pOperator == 'in') {
			if (is_array($pValue)) {
				$value_str = '';
				foreach ($pValue as $in_value) {
					$value_str .= "'".mysql_real_escape_string($in_value)."',"; 
				}
				$pValue = substr($value_str, 0, -1);	// Letztes Komma entfernen
				$condition_string = $pTable.'.'.$pFieldname.' in ('.$pValue.')';
			}
			else {
				if (substr($pValue,0,1) == '(') $pValue = substr($pValue,1);
				if (substr($pValue,-1) == ')') $pValue = substr($pValue,0,-1);

				foreach (explode($pValue) as $in_value) {
					if (substr($pValue,0,1) == "'") $pValue = substr($pValue,1);
					if (substr($pValue,-1) == "'") $pValue = substr($pValue,0,-1);
					$value_str .= "'".mysql_real_escape_string($in_value)."',"; 
				}
				$pValue = substr($value_str, 0, -1);	// Letztes Komma entfernen
				$condition_string = $pTable.'.'.$pFieldname.' in ('.$pValue.')';
			}
			
		}
		return $condition_string;
	}
	
	/**
	 * Aus welchen Tabellen wir der Datensatz geladen?
	 * Hier können die Tabellen angegeben werden, falls die Standard-Tabelle nicht reicht.
	 * @param $pFrom	// String mit Tabellennamen
	 * @param $pForeignID	// Wenn Feldname, der Fremdschlüssel enthällt. 
	 */
	function AddSqlFrom($pFrom = '',$pForeignID = '',$pInternalKey = false) {

		
		
		if($pForeignID == '') $pForeignID = substr($this->mDBTable,0,-1); // Falls nicht anders angegeben wird angenommen, dass Fremdschlüssel Namen der Tabelle Trägt.
		
		
		if ((mb_ereg_match('[A-Z][a-z]+', $pFrom))
			&& (mb_ereg_match('[A-Z][a-z]+',$pForeignID))
			&& (!in_array($pFrom, $this->mSqlFrom))
		){
			$this->mSqlFrom[] = $pFrom;
			if ($pInternalKey == false) {
				$this->mSqlWhere[] = $this->mDBTable.'.ID='.$pFrom.'.'.$pForeignID;
			}
			else {
				$this->mSqlWhere[] = $pFrom.'.ID='.$this->mDBTable.'.'.$pForeignID;
			}
			
			hDebug::Add('Verknüpfte Tabelle: '.$pFrom.' - Fremschlüssel: '.$pForeignID);	
		}
	}
	
    /**
    *   Liefert einen SQL-String zum Laden einer Liste.
    */
	public function getSqlRequest() {
		$req = '';
		if (count($this->mSqlWhere) > 0) {
			$sql_where = join(' and ', $this->mSqlWhere);
		}
		else {
			$sql_where = '1=1';
		}

		foreach ($this->mSqlFrom as $key => $tablename) {
			if($tablename == '') unset($this->mSqlFrom[$key]);
		}
		
		if (count($this->mSqlFrom) > 1) {

			$sql_from = join(', ', $this->mSqlFrom);
		}
		else {
			$sql_from = $this->mDBTable;
		}
		
		$sql_order = '';
		if ($this->mSqlOrder != '') {
			$sql_order = ' ORDER BY '.$this->mSqlOrder;
		}
		
		
	
		$req = 'Select '.$this->mDBTable.'.* From '.$sql_from.' WHERE '.$sql_where.$sql_order;
		return $req;
	}

}

?>