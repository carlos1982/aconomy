<?php


?>
<?php
/**
 * Controller User verwaltet, die Rechte. Welcher Benutzer darf welche Actions aufrufen?
 * Wird auch verwendet, wenn z.B. Umleitungen gemacht werden.
 * Bekannte actions: redirect
 * @version 0.1
 * @author Carlos Cota Castro
 */


// Wenn keine Angabe, dann darf jedeR alles. Z.B. Registrierung oder die Startseite.

switch (hRouter::getAction()) {
	
	case 'index':		if(!(hSession::IsLoggedIn())) {
							hRouter::NoPermission();
						}
						break;
	case 'login':		if((hSession::IsLoggedIn())) {
							hRouter::NoPermission();
						}
						break;
	case 'register':	if((hSession::IsLoggedIn())) {
							hRouter::NoPermission();
						}
						break;
	case 'lostpassword':if(hSession::IsLoggedIn()) {
							hRouter::NoPermission();
						}
						break;
	case 'install':		/*if((hSession::IsLoggedIn())) {
							hRouter::NoPermission();
						}
						*/
						break;
	default:			if(!hSession::IsLoggedIn()) {
							hRouter::NoPermission();
						}
						break;
}


?>