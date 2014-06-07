<?php
$item = hStorage::getVar('Item');
?>
<h1><?=__('Neuen Gegenstand erstellen')?></h1>
<?=hHtml::getFormTag(_Link('item','create'))?>
<?php
$item->Edit();
?>
<?=hHtml::EditFormButton(__('Gegenstand erstellen'))?>
