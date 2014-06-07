<?php
/**
 * User: castro
 * Date: 07.06.14
 * Time: 20:25
 *
 * @abstract What are my items, i can lend to the communites i'am a part of?
 *
 */

$item_list = new lItems();
$item_list->AddCondition('UserID', hSession::getUserId());
if ($item_list->LoadFromDB()) {
    hStorage::addVar('ItemListe',$item_list);
}
else {
    hError::Add(__('Es konnte keine Liste geladen werden!'));
}