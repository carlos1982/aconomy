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

//TODO: put in templates!
//echo $locations->showList();
if ($locations->CountItems() > 0) {
    echo '<table><thead></thead><tbody>';
    foreach ($locations->mItems as $location) {
        //echo '<div class="course_container">';
        echo '<tr>';
        echo '<td><h3>'.$location->dName->showValue().'</h3></td>';

        $link = _Link('location','show',$location->dEncryptID->getValue());
        //hHtml::getLinkButtonTag(__('Anzeigen'), $link); //Anzeigen
        echo '<td>'.hHtml::getLinkButtonTag(__('Anzeigen'), $link).'</td>';
        //echo '<td>'.$locations->getShowLink().'</td>';
        echo '</tr>';
        //echo '</div><!-- course_container Ende -->';
    }
    echo '</tbody></table>';
}
else {
    echo '<p>'.__('Du bist Mitglied in keiner Location').'</p>';
}
?>


<ul>
	<li><a href="<?=_Link('user','index')?>"><?=__('Startseite')?></a></li>
	<li><a href="<?=_Link('user','logout')?>"><?=__('Logout')?></a></li>
</ul>


