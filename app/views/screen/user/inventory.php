<?php
/**
 * User: castro
 * Date: 07.06.14
 * Time: 21:27
 */

$item_list = hStorage::getVar('ItemListe');
?>


<h1><?=__('Meine Gegenstände')?></h1>

<div class="eightcol">
<?=$item_list->showEditList()?>
</div>

<div class="threeocol">
    <a href="<?=_Link('item','create')?>" class="btn btn-success">Neuen Gegenstand anlegen</a>
</div>

<?=hHtml::Clear()?>