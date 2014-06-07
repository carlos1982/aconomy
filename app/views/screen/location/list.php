<?php
/**
 * @abstract Gibt eine Liste mit Mitarbeitern aus, die geladen wurden.
 * Von hier aus können Sie bearbeitet werden. Es gibt einen Verweis zum anlegen von neuen Angestellten. 
 *  
 * @author Carlos Cota Castro
 * @version 0.1 // Einfache Version
 * @param $locations	// erwartet $employees vom typ lEmployees
 * @usedin $location/list
 */

$locations = hStorage::getVar('Locations');
?>
<h1><?=__('Locations verwalten')?></h1>

<p><?=hHtml::getLinkButtonTag(__('Neue Location anlegen'), _Link('location','create'), 'createlink');?></p>

<?php 

	// Listenausgabe
	if (get_class($locations) != 'lLocations') {
		echo __('Fehler');
	}
	else {
		echo $locations->showEditList(true,true,true);
	}

?>
