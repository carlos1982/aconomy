<?php
$token = hRouter::getToken();

if ($token == '') {
    hError::Add(__('Falscher Aufruf'));
    hRouter::NoPermission();
    //hRouter::getRedirect('user','index');
}
else { // Formal richtiger Aufruf
    $location = new oLocation();
    $location->AddCondition('EncryptID', $token);
    if ($location->LoadFromDB()) {
        hDebug::Add('Location was found');
        hStorage::addVar('Location', $location);
    }
}



?>