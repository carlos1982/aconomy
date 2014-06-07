<?php
/**
 * Lädt die Liste der Locations
 * @version 0.1 // Einfaches laden.
 */

$locations = new lLocations();
$locations->LoadFromDB();
hStorage::addVar('Locations', $locations);
?>