<?php
/**
 * Erwartet einen Token (EncryptID) und Lädt einen Datensatz. Wenn Daten per Post übergeben wurden, dann werden Daten validiert und gespeichert.
 */

$item = new oItem();

// Supi
if($_POST['editmode'] == 'save') {
	
	$item->LoadFromPost();

    $item->dUser->setValue(hSession::getUserId(), true);
    $item->dCreated->setValue(date('Y-m-d H:i:s'));

	if ($item->Insert()) {

        hSuccess::Add(__('Datensatz ist erfolgreich gespeichert worden'));


		hRouter::Redirect(_Link('user','inventory'));
	}
	else {
        hError::Add(__('Gegenstand kann nicht erzeugt werden!'));
		// Eventuell Fehlermeldung. Sollte aber in Save schon gesetzt worden sein.
	}
}
hStorage::addVar('Item',$item);

?>
