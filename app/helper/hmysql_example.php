<?php
/**
 * @author Carlos Cota Castro
 * @version 1.0
 * @abstract Ich will nicht, dass beim Debugging das SQL-Passwort übertragen wird.
 * Deshalb speichere ich die Daten in einem Helper, statt in den globalen Konstanten.
 */
class hMySQL {
		
	static $mDataBaseHandle = null;	// Database-Handle
	static $mPreparedStatement;
	static $mLastStatement;
	static $mResultObject;
	
	static function Init() {
            if (is_null(static::$mDataBaseHandle)) {
                try {
                    static::$mDataBaseHandle = new mysqli('localhost', 'dbname', 'dbpassword', 'dbuser');
                } catch (Exception $e) {
                    error_log($e->getMessage() . $e->getFile() . $e->getLine());
                    die;
                }
            }
            return static::$mDataBaseHandle;
	}
	
	static public function getLastStatement($pStatement = '') {
		if ($pStatement != '') {
			static::$mLastStatement = $pStatement;
		}
		return static::$mLastStatement;
	}
	

	
	static public function Query($pStatement) {
                static::Init();
		static::$mResultObject = static::$mDataBaseHandle->query(self::getLastStatement($pStatement));
		return static::$mResultObject;
	}

	static public function countRows() {
		return (is_object(static::$mResultObject))
                    ? self::$mResultObject->num_rows
                    : 0
                ;
	}
	
	static public function escapeString($pString = '') {
		return static::$mDataBaseHandle->real_escape_string($pString);
	}
	
	static public function getInsertID() {
		return static::$mDataBaseHandle->insert_id;
	}
	
	static public function getError() {
		return mysqli_error(static::$mDataBaseHandle);
	}
	
}