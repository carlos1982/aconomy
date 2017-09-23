<?php

/**
 * Erwartet einen Token (EncryptID) und Lädt einen Datensatz. Wenn Daten per Post übergeben wurden, dann werden Daten validiert und gespeichert.
 */
$location = new oLocation();

// Supi
if ($_POST['editmode'] == 'save') {

    $location->LoadFromPost();
    $location->dCountMembers->setValue(1);

    if ($location->Insert()) {

        hSuccess::Add(__('Datensatz ist erfolgreich gespeichert worden'));

        $membership = new oMembership();
        $membership->dUser->setValue(hSession::getUserId(), true);
        $membership->dLocation->setValue($location->getID(), true);
        $membership->dApproved->setValue(1);
        $membership->dGodfather->setValue(1, true);
        $membership->dGodmother->setValue(1, true);

        /*
          _Debug('Test User: '. $membership->dUser->Validate(),true);
          _Debug('Test Location: '. $membership->dLocation->Validate(),true);
          _Debug('Test Approved: '. $membership->dApproved->Validate(),true);
          _Debug('Test Godfather: '. $membership->dGodfather->Validate(),true);
          _Debug('Test Godmother: '. $membership->dGodmother->Validate(),true);
         */

        if (!$membership->Insert()) {
            hError::Add(__('Du wurdest nicht als Mitglied hinzugefügt, bitte wende dich an {#0}', array(ADMINISTRATOR_EMAIL)));
        }


        hRouter::Redirect(_Link('location', 'list'));
    } else {
        // Eventuell Fehlermeldung. Sollte aber in Save schon gesetzt worden sein.
    }
}
hStorage::addVar('Location', $location);
?>
