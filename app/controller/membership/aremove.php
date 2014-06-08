<?php
/**
 * Created by PhpStorm.
 * User: castro
 * Date: 08.06.14
 * Time: 16:06
 */

$membership = new oMembership();
if ($membership->LoadByToken()) {
    $location = new oLocation();
    $location->AddCondition('ID', $membership->dLocation->getValue());
    $location->LoadFromDB();
    if ($membership->Delete()) {
        hSuccess::Add(__('Mitgliedschaft gelöscht'));
        $location->dCountMembers->DecreaseValue();
        if (!$location->Update()) {
            hError::Add(__('Konnte die Anzahl der Member nicht runter setzen'));
        }
        hRouter::Redirect(_Link('location','show',$location->getToken()));
    }
    else {
        hSuccess::Error(__('Mitgliedschaft konnte nicht gekillt werden'));
    }
} else {
    hError::Add(__('Mitgliedschaft existiert nicht'));
}