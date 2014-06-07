<?php
/**
 * @abstract Storage wird dazu benutzt um in den
 * Actions Objekte an einem Zentralen Ort anzulegen, die nicht mit den Variablen der Views Kolidieren sollen.
 * @version 0.1
 * @author Carlos Cota Castro
 * 
 */

class hStorage {
	
	static $mData = array();
	
	static function addVar($pKey = '', $pVar = '') {
		if ($pKey != '') {
			self::$mData[$pKey] = $pVar;
		}
	}
	
	static function VarExists($pKey) {
		if ($pKey != '' && array_key_exists($pKey, self::$mData)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	static function getVar($pKey = '') {
		if (self::VarExists($pKey)) {
			return self::$mData[$pKey];
		}
	}
	
}

?>