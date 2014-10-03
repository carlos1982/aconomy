<?php
/**
 * hSession regelt alles in Zusammenhang mit der aktuellen Session des Benutzers.
 * @author Carlos Cota Castro
 * @version 0.1 - 18.10.2011 Funktion IsLoggedIn() und IsStudent() werden als Dummy-Funktionen geschrieben.
 * @version 0.2 - 01.11.2011 Init() und Save() implementiert.
 */
class hSession{
	
	static $mSessionID;
	
	/* Eigenschaften */
	static $mUserId;
	static $mAdminrole;
	static $mNickname;
	static $mForename;
	static $mSurname;
	
	/* ##########################################################################
	*	Funktionen zur Verwendung in index.php
	*	Generelle Instanzierung der Session sowie das Speichern
	*/
	
	static function Init() {
		self::LogOut(); // Ist zeitgleich auch die Initialisierung der Werte
		session_start();
		$request = "Select * From Sessions Where ID='".hMySQL::escapeString(session_id())."'";
		$result = mysql_query($request);
		hDebug::Add($request);
		if (mysql_num_rows($result) == 1) {
		//if (hMySQL::countRows($request) == 1) {
			while ($data = mysql_fetch_array($result)) {
			//foreach (hMySQL::fetchAssociated($pStatement) as $data) {
				$session_values = unserialize($data['Data']);
				$was_logged_in = 0;
				
				if ((array_key_exists('UserId', $session_values))) {
					$was_logged_in = $session_values['UserId'];	
				}
				
				if ($was_logged_in > 0) { // War gerade noch eingeloggt.
					self::$mUserId = $session_values['UserId'];
					self::$mAdminrole = $session_values['Adminrole'];
					self::$mNickname = $session_values['Nickname'];
					self::$mForename = $session_values['Forename'];
					self::$mSurname = $session_values['Surname'];
					
					if(strtotime($data['LastUpdate']) > ( strtotime(date('Y-m-d H:i:s')) - (60 * 60))) { 
						// Gültigkeit 60 Minuten ohne Aktivität. 	
					}
					else {
						hError::Add(__('Session abgelaufen'));
						hRouter::Redirect(hRouter::Link('user', 'login'));
						self::LogOut();
					
					}
				}
				else {
 					hDebug::Add(__('Was Logged in: {#0}', $was_logged_in));
				}
				hDebug::Add(session_id().'~'.$data['LastUpdate'].'~'.$data['Data']);  
			}	
		}
		else {
			//hDebug::Add('Session wurde nicht erfolgreich geladen. SQL-Fehler');
			hDebug::Add('Session wurde nicht erfolgreich geladen. '.mysql_error($result));
		}
		
		
	}
	
	static function Save() {

		$session_obj = array();
		$session_obj['UserId'] = self::$mUserId;
		$session_obj['Adminrole'] = self::$mAdminrole;
		$session_obj['Nickname'] = self::$mNickname;
		$session_obj['Forename'] = self::$mForename;
		$session_obj['Surname'] = self::$mSurname;
		
		$request = "REPLACE Sessions SET ID='".hMySQL::escapeString(session_id())."', Data='".serialize($session_obj)."', LastUpdate='".date('Y-m-d H:i:s')."'";

		$result = mysql_query($request);		
		//$result = hMySQL::Query($request);
		
		if (!$result) {
			hDebug::Add('SQL-Error beim Speichern. '.mysql_error());
			//hDebug::Add('SQL-Error beim Speichern.');
		}
		else {
			hDebug::Add('Session erfolgreich gespeichert: '.$request);
		}
	}

	/* ##########################################################################
	 * Funktionen zur Verwendung
	 * 
	 * in Controller > Actions: 
	 * 		Student > Login
	 * und	Employee > Login
	 * 
	 * Session soll unabhängig von der jeweil verwendeten Datenquelle funktionieren.
	 * Wenn es z.B. eine Login-Seite nur für Externe mitarbeiter gäbe, dann könnte dieser
	 * Helper immer noch benutzt werden. 
	 */
	
	
	/**
	 * Vornamen, Nachnamen, Studi-ID, EmployeeID setzen.
	 * @param unknown_type $pForename
	 */
	
	static function setNickname($pNickname) {
		self::$mNickname = $pNickname;
	}
	
	static function setForename($pForename) {
		self::$mForename = $pForename;
	}
	
	static function setSurname($pSurname) {
		self::$mSurname = $pSurname;
	}
	
	static function setAdminrole($pAdminrole) {
		self::$mAdminrole = $pAdminrole;
	}
	
	static function setUserId($pUserId) {
		self::$mUserId = $pUserId;
	}

	
	/**
	 * Version 1.0 // 18.02.2012
	 * @return boolean
	 */
	static function IsLoggedIn() {
		if (is_numeric(self::getUserId()) && self::getUserId() > 0) {
			return true;
		}
		return false;
	}

    static function getUserId() {
        return self::$mUserId;
    }
    
	static function getAdminrole() {
		return self::$mAdminrole;
	}
	
	static function getAdminroleString() {
		$adminrole = '';
		switch (self::$mAdminrole) {
			case SUPERADMIN:	$adminrole = __('Super AdministratorIn');
								break;
			case REGIONALADMIN:	$adminrole = __('Admin');
								break;
			case VERIFIEDUSER:	$adminrole = __('Verifizierter User');
								break;
			case LOGGEDIN:		$adminrole = __('Eingeloggt');
								break;
			case INACTIVE:		$adminrole = __('Nicht mehr aktiv');
								break;
		}
		return $adminrole;

	}
	
	static function getForename() {
		return self::$mForename;
	}
	
	static function getSurname() {
		return self::$mSurname;
	}
	
	
	static function LogOut() {
		self::$mUserId = '';
		self::$mAdminrole = 0;
		self::$mForename = '';
		self::$mSurname= '';
	}
}
?>