<?php
/**
 * Lädt die Liste aller Studierender
 * @version 0.1 // Einfaches laden.
 */

$list = new lUsers();
$list->LoadFromDB();
hStorage::addVar('Users', $list);
?>