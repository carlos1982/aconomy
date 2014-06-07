<?php
$user = new oUser();
if (!$user->LoadByToken()) {
    hError::Add(__('Falscher Aufruf'));
    hRouter::NoPermission();
    //hRouter::getRedirect('user','index');
}
else {
    hDebug::Add('User was found');
    hStorage::addVar('User', $user);
}
