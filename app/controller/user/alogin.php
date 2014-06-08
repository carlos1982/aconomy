<?php
/**
*   Controller dient dem Login von Studierenden. Wenn E-Mail und Passwort angegeben sind 
*   wird versucht ein passender Datensatz in der Studierenden-Tabelle zu finden.
*
*   Bei Erfolg wird im Session-Helper der Status gesetzt, dass der Nutzer ein Student ist und 
*   eingeloggt.
*
*   Sonst wird eine Fehlermeldung ausgegeben.
*
*   Version 0.1	Dummy-Adresse wird abgefragt.
*   Version 0.2	$student wird versucht zu laden.
*   Version 0.3	Studenten-Infos werden in Session gespeichert.
*   @Todo Bei Erfolg: Redirect benutzten, so dass student/index auch der Controller gerufen wird und dort Statistiken z.B. direkt angezeigt werden.
*/

$user = new oUser();

if( (_p('LoginEmail') != '') ||
    (_p('LoginPassword') != '')
  ) {
    $user->AddCondition('Email',$_POST['LoginEmail']);
    $user->AddCondition('Password',hSalt::Salt($_POST['LoginPassword'],$user->mDBTable));
  
    //if (($student->LoadFromDB()) && ($student->Validate())) {
    if ($user->LoadFromDB()) {
    	hSuccess::Add('Erfolgreich eingeloggt!');
    	
    	hSession::setUserId($user->getID());
    	hSession::setNickname($user->dNickname->getValue());
		hSession::setForename($user->dForename->getValue());
		hSession::setSurname($user->dSurname->getValue());

        hSession::setAdminrole($user->dAdminrole->getValue());
    	
    	//hRouter::setView('index'); // Sind im Controller Student. Also student/index
    	hRouter::Redirect(_Link('user', 'index'));

    }
    else {
    	hError::Add(__('Login fehlgeschlagen. Passwort vergessen?'));
    }
     
}

$login_email = $user->dEmail;
$login_email->setFieldName('LoginEmail');
        
$login_password = $user->dPassword;
$login_password->setFieldName('LoginPassword');	

hStorage::addVar('LoginEmail', $login_email);
hStorage::addVar('LoginPassword', $login_password);


?>
