<?php
/**
 * @author Carlos Cota Castro
 * @version 1.0
 * @abstract Ich will nicht, dass beim Debugging das SQL-Passwort übertragen wird.
 * Deshalb speichere ich die Daten in einem Helper, statt in den globalen Konstanten.
 */
class hMySQL {
	static function Init() {
		mysql_connect('localhost','mysql_user','user_password') or die('Konnte nicht zum MySQL-Server verbinden: '.mysql_error());
		mysql_selectdb('database');
	}
}
?>


