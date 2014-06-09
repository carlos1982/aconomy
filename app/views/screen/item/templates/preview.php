<?php
$item = $template_arguments[0];
//////////////////////////////////////

$item->dUser->LoadForeignObject();

$item_link = _Link('item', 'show', $item->getEncryptID());
$owner_link = _Link('user','show', $item->dUser->mForeignObject->getEncryptID());
$request_link = _Link('item','request', $item->getEncryptID());

//////////////////////////////////////
?>
<div class="item_preview">
    <a href="<?=$item_link?>" class="item_name"><?=$item->dName?></a>
    <small>
        <a href="<?=$owner_link?>" class="item_owner"><?=__('Owner')?>: <?=$item->dUser?></a> |
        <span><?=$item->dStatus?></span>
        <a href="<?=$request_link?>"><?=__('Ausleihen')?></a>
    </small>
</div>