<?php
$location = hStorage::getVar('Location');
if (!_isO($location,'oLocation')) {
    echo 'Error';
    return;
}
?>

<h1><?=__('Location bearbeiten')?></h1>

<?=hHtml::getFormTag(_Link('location','edit', $location->dEncryptID->getValue()))?>
<?php
$location->Edit();
?>
<?=hHtml::EditFormButton(__('Änderungen speichern'))?>
