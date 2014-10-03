<?php
/**
 * @abstract Diese Klasse ist die Standard-Klasse für "einfache" Daten-Objekte.
 * @version 0.1	- 18.10.2011	// Dummy-Klasse
 * @version 0.2	- 24.01.2012 	// Unterstützt From und Where
 * @version 0.3 - 24.01.2012	// leitet nun MySql ab um gemeinsame Funktionen mit Objekt-Klassen zu teilen
 * @author Carlos Cota Castro
 * @todo mSqlGroup, mSqlHaving 
 */
class lStandard extends oMySql {

	/**##############################################################################
	 * 		VARIABLEN
	 * ##############################################################################*/
	
	
	var $mObjectClass = '';	// Von welchem Typ sollen die List-Items sein?

	/* Variable in der Referenzen auf Objekte gespeichert werden.*/
	var $mItems = array();

	/**##############################################################################
	 * 		Funktionen
	 * ##############################################################################*/
	
	/**
	 * Init()
	 */
	function __construct() {
		$this->ResetSQL();
		$this->mSqlOrder = $this->mDBTable.'.ID ASC';
	}	

	/**
	 * SQL-Befehlt wird benutzt um Daten zu laden
	 */
	public function LoadFromDB() {
		$request = '';
		$request = $this->getSqlRequest();
		if($request != '') {
			$result = mysql_query($request);
			hDebug::Add($request);

			while ($data = mysql_fetch_assoc($result)) {
				$TmpItem = new $this->mObjectClass();
				if(!$TmpItem->LoadFromArray($data)) {
					hDebug::Add('Objekt konnte nicht geladen werden!!! Typ: '.get_class($TmpItem));
				}
				else {
					$this->mItems[] = $TmpItem;
				}
			}
			return true;
		}
		return false;
		
	}
	
	/**
	 * Liefert eine Liste zum Bearbeiten von Datensätzen.
	 */
	function showEditList($pShow = true, $pEdit = true, $pDelete = false) {
		if (count($this->mItems) < 1) {
			return __('Keine Einträge vorhanden!');
		}
        $ret = '';
		$ret .= $this->mItems[0]->showEditListHeader($pShow, $pEdit, $pDelete);
		
		foreach ($this->mItems as $item) {
            $ret .= $item->showEditListItem($pShow, $pEdit, $pDelete);
		}
		$ret .= '</tbody></table><div class="clear"></div>';
		return $ret;
	}
	
	/**
	 * Liefert eine Liste zum Bearbeiten von Datensätzen.
	 */
	function showList() {
		if (count($this->mItems) < 1) {
			return __('Keine Einträge vorhanden!');
		}
		$ret = '<table class="table table-striped"><thead>';
		$ret .= $this->mItems[0]->showEditListHeader();
		$ret .= '</thead><tbody>'; // @todo Prüfen ob es Sinnvoll ist an dieser Stelle Filter auszugeben.
		foreach ($this->mItems as $item) {
			$ret .= $item->showListItem();
		}
		$ret .= '</tbody></table>';
		return $ret;
	}
	
	/**
	 * Liefert die Anzahl der Elemente zurück.
	 */
	public function CountItems() {
		return count($this->mItems);
	}

	
	/**
	 * Setzt gegebenenfalls die Reihenfolge
	 * @param unknown_type $pOrder
	 */
	public function setOrder($pOrder = array()) {
		$order_str = '';
		if (is_array($pOrder)){
			
			$obj_class = $this->mObjectClass;
			$dummy_obj = new $obj_class();
			
			foreach ($pOrder as $field => $direction) {
				$obj_var = 'd'.$field;
				if(	
					(array_key_exists($obj_var, get_class_vars($this->mObjectClass))) && 
					(
						($direction == 'ASC') ||
						($direction == 'DESC')
					)
				) {
					$order_str .= $dummy_obj->$obj_var->getFieldname().' '.$direction.', ';
				}
				else {
					hDebug::Add('Sortierung Fehlerhaft. Wird Ignoriert:');	
				}
				
				hDebug::Add('SortierOrdnung: '.$field.' - '.$direction);
			}
			$order_str = substr($order_str, 0, -2);
			hDebug::Add('SortierOrdnung: '.$order_str);
			$this->mSqlOrder = $order_str;
		}

	}
	
	

	
}
?>