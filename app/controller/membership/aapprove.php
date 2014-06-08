<?php
/**
 * Created by PhpStorm.
 * User: castro
 * Date: 08.06.14
 * Time: 02:51
 */

$membership = new oMembership();

if($membership->LoadByToken()) {

    $membership->dUser->AllowCurrentValue();
    $membership->dLocation->AllowCurrentValue();

    if($membership->dApproved->getValue() == 1){
        hError::Add(__('Das Mitglied ist bereits bestätigt'));
    }
    else {

        $location = new oLocation();
        $location->AddCondition('ID', $membership->dLocation->getValue());
        if ($location->LoadFromDB()) {

            if ($membership->dUser->getValue() == hSession::getUserId()) {
                hError::Add(__('Du kannst dich nicht selber bestätigen'));
                hRouter::Redirect(_Link('location','show', $location->getToken()));
                return;
            }

            if ($membership->dGodfather->getValue() < 1) {
                $membership->dGodfather->setValue(hSession::getUserId(), true);
                $membership->dGodmother->AllowCurrentValue();
                if ($location->dCountMembers->getValue() < 2) {
                    $membership->dApproved->setValue(1);
                }

                if ($membership->Update()) {
                    if ($location->dCountMembers->getValue() < 2) {
                        hSuccess::Add(__('Die Mitgliedschaft ist nun bestätigt.'));
                    }
                    else {
                        hSuccess::Add(__('Es bedarf jetzt noch einer weiteren Bestätigung durch ein anderes Mitglied'));
                    }
                }
                else {
                    hError::Add(__('Bei Speichern kam es zu einem Fehler'));
                }

                $location->dCountMembers->IncreaseValue();
                if(!$location->Update()) {
                    hError::Add(__('Error: 1324. Bitte an Webmaster wenden.'));
                }
                hRouter::Redirect(_Link('location','show',$location->getToken()));
                return;
            }
            else {

                if ($membership->dGodmother->getValue() < 1) {

                    if ($membership->dGodfather->getValue() == hSession::getUserId()) {
                       hError::Add(__('Es bedarf der Bestätigung durch einen weiteren User'));
                       hRouter::Redirect(_Link('location','show',$location->getToken()));
                       return;
                    }

                    $membership->dGodfather->AllowCurrentValue();
                    $membership->dGodmother->setValue(hSession::getUserId(), true);
                    $membership->dApproved->setValue(1);

                    if ($membership->Update()) {
                        hSuccess::Add(__('Die Mitgliedschaft ist nun bestätigt.'));
                    }
                    else {
                        hError::Add(__('Bei Speichern kam es zu einem Fehler'));
                    }


                    $location->dCountMembers->IncreaseValue();
                    if(!$location->Update()) {
                        hError::Add(__('Error: 1324. Bitte an Webmaster wenden.'));
                    }
                    hRouter::Redirect(_Link('location','show',$location->getToken()));
                    return;

                }
            }
        }
        else {
            hError::Add(__('Location kann nicht geladen werden'));
        }
    }
}
else {
    hError::Add(__('Falscher Aufruf'));
}