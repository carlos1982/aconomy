<?php
/**
 * @author	Carlos Cota Castro
 * @Version 0.1	// 08. Dezember: Solange keine Daten betraucht werden bleibt die Datei leer.
 */

/** - Orte --------------------------------------------------------- */
$locations = new lLocations();
//$locations->AddSqlFrom('LocationUser','Location');
//$locations->AddExternalCondition('LocationUser','User',hSession::getUserId()); // Später, wenn nur noch die eigenen Locations angezeigt werden sollen
$locations->setOrder(array('Semester' => 'DESC'));
$locations->LoadFromDB();
hStorage::addVar('Locations', $locations);
?>