<?php
/**
 * @author	Carlos Cota Castro
 * @Version 0.2
 */

/** - Orte --------------------------------------------------------- */

$memberships = new lMemberships();
$memberships->AddCondition('User', hSession::getUserId());
if ($memberships->LoadFromDB()) {
    $location_ids = array();
    foreach($memberships->mItems as $item) {
        _Debug($item->dLocation->getValue());
        $location_ids[] = $item->dLocation->getValue();
    }
    $locations = new lLocations();
    //$locations->AddSqlFrom('LocationUser','Location');
    //$locations->AddExternalCondition('LocationUser','User',hSession::getUserId()); // Später, wenn nur noch die eigenen Locations angezeigt werden sollen
    $locations->AddCondition('ID', $location_ids, 'in');
    $locations->LoadFromDB();
    hStorage::addVar('Locations', $locations);
}