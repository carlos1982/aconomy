<?php
/**
 * Dies ist die Startseite, wenn sich Studis erfolgreich eingeloggt haben.
 * 
 * 
 * Erwartet (lLocations)$locations
 * 
 */

$locations = hStorage::getVar('Locations');

?>

<h1><?=__('Willkommen')?> <?=hSession::getForename()?>!</h1>


<h2><?=__('Meine Locations')?></h2>

<?php
//echo $locations->showList();
if ($locations->CountItems() > 0) {
    foreach ($locations->mItems as $location) {

        echo '<div class="course_container">';
        echo '<h3>'.$location->dName->showValue().'</h3>';
        echo '</div><!-- course_container Ende -->';
    }
}
else {
    echo '<p>'.__('Du bist Mitglied in keiner Location').'</p>';
}
?>


<ul>
	<li><a href="<?=_Link('user','index')?>"><?=__('Startseite')?></a></li>
	<li><a href="<?=_Link('user','logout')?>"><?=__('Logout')?></a></li>
</ul>


