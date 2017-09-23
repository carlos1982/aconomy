<?php
/**
 * @abstract Wird zur Installation benutzt.
 * @version 0.1 - 19.Januar 2012 Erstellen eines Administrators.
 */

$user = new oUser();
$user->AddCondition('Adminrole',SUPERADMIN);
if($user->LoadFromDB()){
	hError::Add('System ist bereits installiert.');
	hRouter::NoPermission();
}
elseif($user->LoadFromPost()){
	$user->dAdminrole->setValue(SUPERADMIN);
	$user->dConfirmed->setValue(1);
	if ($user->Insert()) {
		hSuccess::Add('Admin erfolgreich gespeichert.');
		hRouter::Redirect(_Link('user'));
	} else {
		hError::Add('AdministratorIn konnte nicht angelegt werden.');
	}
}
$user->dAdminrole->setEditable(false);
hStorage::addVar('User',$user);