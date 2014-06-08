<?php
/**
 * Action zum registrieren neuer Studierender.
 */
$user = new oUser();
$user->dConfirmed->setEditable(false);
$user->dConfirmed->setValue('0');
$user->dConfirmed->setAllowedValues(array('0' => 'dummy'));
$user->dAdminrole->setAllowedValues(array(LOGGEDIN => 'dummy'));
$user->dAdminrole->setValue(LOGGEDIN, true);
if ($user->LoadFromPost()) {
	if	($user->Insert()) {
		hSuccess::Add(__('Sie haben Sich erfolgreich registriert! Sie können sich nun einloggen!'));
		//hRouter::setModel('student'); Überlüssig, da wir ja schon im Student-Controller sind.
		hRouter::Redirect(_Link('user', 'index'));
	}
}

hStorage::addVar('User', $user);

?>
