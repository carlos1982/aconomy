<?php
/**
 * 	Erwartet $employee;
 */
$location = hStorage::getVar('Location');
?>
<h1><?=__('Neue Location erstellen')?></h1>
<?=hHtml::getFormTag(_Link('location','create'))?>
<?php
$location->Edit();
?>
<?=hHtml::EditFormButton(__('Location erstellen'))?>
