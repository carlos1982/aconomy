<?php
$item = hStorage::getVar('Item');
if (!_isO($item,'oItem')) {
    echo 'Error';
    return;
}
?>

<h1><?=__('Gegenstand bearbeiten')?></h1>

<?=hHtml::getFormTag(_Link('item','edit', $item->dEncryptID->getValue()))?>
<?php
$item->Edit();
?>
<?=hHtml::EditFormButton(__('Änderungen speichern'))?>
