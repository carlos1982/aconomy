<?php
/**
 * Das Interface definiert welche Funktionen Datentypen auf jeden Fall inplementieren sollten.
 * 
 * In der Initialisierung müssen mName gesetzt werden.
 * 
 * @author developer
 * @version 0.1 showValue(),getValue(),setValue(),LoadFromPost,Reset,Validate,Edit,getFieldName,setFieldname
 * @version 0.2 setFieldname und getFieldname werden in Oberklasse tDatatype ausgelagert, da die für alle Datentypen gleich sind.
 */
interface iDataTypes {
	
	/**
	 * Gibt den Wert Formatiert aus. Z.B. bei Zeiten.  
	 */
	function showValue();
	
	/**
	 * Gibt den Wert direkt aus.
	 */
	public function getValue();
	
	/**
	 * Setzt den Wert
	 */
	function setValue($pValue);
	
	/**
	 * Liest anhand eines Posts einen Wert ein. 
	 */
	function LoadFromPost();
    
    /**
	 * Leert den Value
	 */
	function Reset();
	
	/**
	 * Soll den gesetzten Wert überprüfen
	 */
	function Validate();
	
	/**
	 * Soll ein Input bzw. Select-Feld erzeugen.
	 */
	function Edit();

}
?>
