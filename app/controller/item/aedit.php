<?php
/**
 * Erwartet einen Token (EncryptID) und Lädt einen Datensatz. Wenn Daten per Post übergeben wurden, dann werden Daten validiert und gespeichert.
 */

$item = new oItem();
$item->AddCondition('EncryptID',hRouter::getToken());
if (hSession::getAdminrole() < SUPERADMIN) {
    $item->AddCondition('User', hSession::getUserId());
}
if ($item->LoadFromDB()) {
    // Supi
    if(_p('editmode') == 'save') {

        $item->LoadFromPost();

        if ($item->Update()) {
            hSuccess::Add(__('Datensatz ist erfolgreich gespeichert worden'));
            hRouter::Redirect(_Link('item','edit',hRouter::getToken()));
        }
        else {
            // Eventuell Fehlermeldung. Sollte aber in Save schon gesetzt worden sein.
            hError::Add('Speichern fehlgeschlagen!');
        }
    }
    hStorage::addVar('Item', $item);
}
else {
    hError::Add(__('Datensatz konnte nicht geladen werden'));
    hRouter::Redirect(_Link('user','inventory'));
}

?>
