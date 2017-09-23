<?php
$token = hRouter::getToken();

if ($token == '') {
    hError::Add(__('Falscher Aufruf'));
    hRouter::NoPermission();
    //hRouter::getRedirect('user','index');
}
else { // Formal richtiger Aufruf
    $location = new oLocation();
    $location->AddCondition('EncryptID', $token);
    if ($location->LoadFromDB()) {
        hDebug::Add('Location was found');

        $location->LoadMemberships();
        $member_ids = array();
        foreach ($location->mMemberships->mItems as $member) {
            $member_ids[] = $member->dUser->getValue();
        }
        $item_list = new lItems();
        $item_list->AddCondition('User', $member_ids, 'in');
        if ($item_list->LoadFromDB()) {
            hStorage::addVar('ItemList', $item_list);
        }

        hStorage::addVar('Location', $location);
    }
}



?>