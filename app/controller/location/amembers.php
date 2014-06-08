<?php
/**
 * User: castro
 * Date: 08.06.14
 * Time: 19:11
 */

$location = new oLocation();
if ($location->LoadByToken()) {
    hStorage::addVar('Location', $location);

    if (!$location->UserIsApprovedMember() && !hSession::getAdminrole() == SUPERADMIN) {
        hError::Add(__('Du hast keine Berechtigung dir die Member anzuschauen!'));
        hRouter::Redirect($location->getShowLink());
        return false;
    }
}
else {
    hError::Add(__('Du hast keine Berechtigung dir die Member anzuschauen!'));
    hRouter::Redirect($location->getShowLink());
    return false;
}