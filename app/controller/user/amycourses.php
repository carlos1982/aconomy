<?php
/**
 * Lädt die Liste der Lehrverantaltungen eines/einer Studierenden
 * @todo Participant Klassen erstellen.
 */

$participants = new lParticipants();
$participants->AddCondition('Student',hSession::getStudentId());


?>