<?php
/**
 * User: castro
 * Date: 09.06.14
 * Time: 18:58
 */
$item = hStorage::getVar('Item');
?>

<h1><?=$item->dUser?> / <?=$item->dName?></h1>

<div class="eightcol">
    <h2><?=__('Hinweise')?></h2>
    <?=$item->dDescription?>
</div>

<div class="twocol">
    <a href="<?=_Link('item','request',$item->getEncryptId())?>">Ausleihen</a>
</div>

<?=hHtml::Clear()?>
