<?php
/**
 * Der Salt-Helper dient dem Verschlüsseln von Passwörtern. Nicht nur, dass Sie Verschlüsselt werden: Den Passwörtern wird vor der Verschlüsselung noch ein weiterer
 * String angehangen, so dass ein einfaches Verschlüsseln von vielen Wörterbucheinträgen nicht ausreicht um die Passwörter zu raten.
 * 
 * @author Carlos Cota Castro
 * @version 0.1 - 01.11.2011 mit md5 Verschlüsselung
 * @todo Prüfen, welches Verschlüsselungsverfahren momentan aktuell ist.
 *
 */
class hSalt {
	
	static $mSalts = array(
		'Std' => 'dzajhsdfgasdfasdasgbduzazsdgadsubdsbasabcskldte3bc7sfn)=nmd(*sdkd'
	);
	
	static function Salt($pPassword, $pType = 'Std') {
		$ret = '';
		if(array_key_exists($pType, self::$mSalts)) {
			$ret = md5($pPassword.self::$mSalts[$pType]);
		}
		else {
			$ret = md5($pPassword.self::$mSalts['Std']);
			hDebug::Add('Salt wurde ohne richtigen Parameter aufgerufen: Type="'.$pType.'" Vorher abfragen!!!');
		}
		return $ret;
	}
	
	/**
	 * Zum erstellen mehrerer hintereinander wird zusätzlich noch ein wenig mehr Arbeit generiert.
	 * @param string $tablename
	 */
	static function CreateUniqueID($tablename = 'Standard') {
		return md5(uniqid('',true).time().$tablename);
	}
	
	static function GenerateNewPassword() {
		
		$new_password = '';
		$allowed_chars = 'ABCDEFGHIJKLMNPQRSTWXYZabcdefghijkmnpqrstwxyz123456789#*+-_$&';
		
		$password_length = rand(7, 9);

		for ($i = 0; $i < $password_length; $i++) {
			$start = rand(0, mb_strlen($allowed_chars)-1);
			$char = mb_substr($allowed_chars, $start, 1);
			$new_password .= $char;
			//echo $start.'~'.$char.'<br />';
		}
		
		return $new_password;
	}
	
	
}
?>
