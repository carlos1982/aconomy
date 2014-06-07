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
echo '<p>'.hHtml::getLinkButtonTag(__('Eine Location hinzufügen'),_Link('location','create'),'createbutton').'</p>';
?>

<?php 

echo $locations->showList();

foreach ($locations->mItems as $location) {
	
	echo '<div class="course_container">';
	
	echo '<h3>'.$location->dName->showValue().'</h3>';

	

	echo '</div><!-- course_container Ende -->';
}

?>


<ul>
	<li><a href="<?=_Link('user','index')?>"><?=__('Startseite')?></a></li>
	<li><a href="<?=_Link('user','logout')?>"><?=__('Logout')?></a></li>
</ul>


