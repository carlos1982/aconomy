<?php

$location = hStorage::getVar('Location');
if (!_isO($location, 'oLocation')) {
    echo 'Error';
    return;
}

$is_member = false;

if ($location->UserIsMember() || hSession::getAdminrole() == SUPERADMIN){
    if ($location->UserIsApprovedMember() || hSession::getAdminrole() == SUPERADMIN) {


        $is_member = true;

        if (!$location->UserIsMember()) {
            echo '<p><a href="'._Link('membership','create',$location->getToken()).'" class="btn request-membership-button">'.__('Gruppe beitreten').'</a></p>';
        }

        // @todo Move to Class
        $unapproved_members = '';
        $full_members = '';

        $remove_link = '';

        $i = 0;

        foreach ($location->mMemberships->mItems as $member) {
            $user = new oUser();
            $user->AddCondition('id',$member->dUser->getValue());
            $user->LoadFromDB();

            $show_link = _Link('user','show',$user->getEncryptID());
            if (hSession::getAdminrole() == SUPERADMIN) {
                $remove_link = ' | <a href="'._Link('membership','remove', $member->getEncryptID()).'">'.__('Löschen').'</a>';
            }

            if ($member->dApproved->getValue() == 1) {
                if ($i < 8) {
                    $full_members .= '<li><a href="'.$show_link.'">'.$user->dNickname->getValue().'x</a>';
                    $full_members .= $remove_link;
                    $full_members .= '</li>';
                }
                $i++;
            }
            else {
                $unapproved_members .= '<li><a href="'.$show_link.'">'.$user->dNickname->showValue().'</a>';
                $unapproved_members .= ' | <a href="'._Link('membership','approve',$member->getEncryptID()).'">'.__('Bestätigen').'</a>';
                $unapproved_members .= $remove_link;
                $unapproved_members .= '</li>';
            }
        }

    }
    else {
        echo 'Noch nicht bestätigt';
    }
}
else {
    echo '<a href="'._Link('membership','create',$location->getToken()).'" class="btn request-membership-button">'.__('Gruppe beitreten').'</a>';
}
?>

<h1><?=$location->dName?></h1>

<div class="eightcol">

<address class="threecol">

    <?=$location->dStreet.' '.$location->dHousenumber?><br />
    <?=$location->dZip.' '.$location->dCity?><br />
    <?=$location->dCountry?>

</address>

<div id="location_descriptop" class="fourcol">
    <?=$location->dDescription?>
</div>
<?=hHtml::Clear()?>
<?php
// Items Ausgeben -----------------------------
$item_list = hStorage::getVar();

if(false) $item_list = new lItems();

if ($is_member && hStorage::VarExists('ItemList')) {
    $item_list = hStorage::getVar('ItemList');

    $anz_items = count($item_list->mItems);
    if ($anz_items > 0) {
        echo '<h2>'.__('Ausleihbare Gegenstände').'</h2>';
        foreach ($item_list->mItems as $item) {
            hTemplate::Render('screen/item/preview', $item);
        }
    }

}
?>

</div>

<div class="threecol">



<?php
if ($full_members != '') {

    echo '<h2>'.$i.' '.__('Members').'</h2>';
    echo '<ul>';
    echo $full_members;
    echo '</ul>';

    echo '<a href="'._Link('location', 'members' , $location->getToken()).'">'.__('Member anzeigen').'</a>';
}

if ($unapproved_members != '') {
    echo '<h2>'.__('Neue Mitgliedsanträge').'</h2>';
    echo '<ul>';
    echo $unapproved_members;
    echo '</ul>';
}
?>
</div>


<?=hHtml::Clear()?>

<?php //=hGoogleMaps::showAdress($location);