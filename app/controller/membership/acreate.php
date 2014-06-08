<?php
/**
 * Created by PhpStorm.
 * User: castro
 * Date: 08.06.14
 * Time: 01:49
 */

$token = hRouter::getToken();

$location = new oLocation();

if ($location->LoadByToken()) {
    $membership = new oMembership();
    $membership->AddCondition('User', hSession::getUserId());
    $membership->AddCondition('Location', $location->getID());
    if ($membership->LoadFromDB()) {
        hError::Add(__('Du bist bereits dieser Location beigetretet'));
    }
    else {
        $membership = new oMembership();
        $membership->dUser->setValue(hSession::getUserId(), true);
        $membership->dLocation->setValue($location->getID(), true);
        $membership->dApproved->setValue(0);
        $membership->dGodfather->setValue('', true);
        $membership->dGodmother->setValue('', true);
        if ($membership->Insert()) {
            hSuccess::Add(__('Du musst nun auf die Freischaltung warten'));
            hRouter::Redirect(_Link('location', 'show', $location->getToken()));
        } else {
            hError::Add(__('Ein Fehler ist aufgetreten'));
        }
    }
}
else {
    hError::Add(__('Falscher Aufruf!'));
    hDebug::Add('Location konnte nicht geladen werden');
}
