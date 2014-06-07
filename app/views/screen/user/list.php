<?php
/**
 * @abstract Gibt eine Liste mit Studierenden aus, die geladen wurden.
 * Von hier aus können Sie bearbeitet werden. Es gibt einen Verweis zum anlegen von neuen Studierenden. 
 *  
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 * @param $students	// erwartet $employees vom typ lEmployees
 * @usedin student/list
 */

$users = hStorage::getVar('Users');

?>
<h1><?=__('Benutzer verwalten')?></h1>

<p><a href="<?=_Link('user','create')?>" class="createlink"><span><?=__('Neue Benutzer anlegen')?></span></a></p>


<?php 

	// Listenausgabe
	if (get_class($users) != 'lUser') {
		echo 'Fehler';
	}
	else {
		$liste = $users->showEditList();
		if ($liste != false) {
			echo $liste;
		}
		else {
			echo __('Keine Benutzer vorhanden!');
		}
	}

?>
