<?php
/**
 * Erwartet einen Token (EncryptID) und Lädt einen Datensatz. Wenn Daten per Post übergeben wurden, dann werden Daten validiert und gespeichert.
 */

$location = new oLocation();
$location->AddCondition('EncryptID',hRouter::getToken());
if ($location->LoadFromDB()) {
    // Supi
    if(_p('editmode') == 'save') {

        $location->LoadFromPost();

        if ($location->Update()) {
            hSuccess::Add(__('Datensatz ist erfolgreich gespeichert worden'));
            hRouter::Redirect(_Link('location','show',hRouter::getToken()));
        }
        else {
            // Eventuell Fehlermeldung. Sollte aber in Save schon gesetzt worden sein.
            hError::Add('Speichern fehlgeschlagen!');
        }
    }
    hStorage::addVar('Location', $location);
}
else {
    hError::Add(__('Datensatz konnte nicht geladen werden'));
    hRouter::Redirect(_Link('location','list'));
}

?>
