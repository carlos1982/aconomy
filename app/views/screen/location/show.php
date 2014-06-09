<?php
if (false) $location = new oLocation();
$location = hStorage::getVar('Location');
if (!_isO($location, 'oLocation')) {
    echo 'Error';
    return;
}

$is_member = false;

?>

<h1><?=$location->dName?></h1>

<address class="threecol">

    <?=$location->dStreet.' '.$location->dHousenumber?><br />
    <?=$location->dZip.' '.$location->dCity?><br />
    <?=$location->dCountry?>

</address>

<div id="location_descriptop" class="fourcol">
    <?=$location->dDescription?>
</div>

<div class="threecol">

    <?php
       if ($location->UserIsMember() || hSession::getAdminrole() == SUPERADMIN){
           if ($location->UserIsApprovedMember() || hSession::getAdminrole() == SUPERADMIN) {


               $is_member = true;


               echo '<a href="'._Link('location', 'members' , $location->getToken()).'">'.__('Member anzeigen').'</a>';

               if (!$location->UserIsMember()) {
                   echo '<p><a href="'._Link('membership','create',$location->getToken()).'" class="btn request-membership-button">'.__('Gruppe beitreten').'</a></p>';
               }


               // @todo Move to Class
               $unapproved_members = '';
               $full_members = '';

               $remove_link = '';

               foreach ($location->mMemberships->mItems as $member) {
                   $user = new oUser();
                   $user->AddCondition('id',$member->dUser->getValue());
                   $user->LoadFromDB();

                   $show_link = _Link('user','show',$user->getEncryptID());
                   if (hSession::getAdminrole() == SUPERADMIN) {
                        $remove_link = ' | <a href="'._Link('membership','remove', $member->getEncryptID()).'">'.__('Löschen').'</a>';
                   }

                   if ($member->dApproved->getValue() == 1) {
                       $full_members .= '<li><a href="'.$show_link.'">'.$member->dUser->showValue().'</a>';
                       $full_members .= $remove_link;
                       $full_members .= '</li>';
                   }
                   else {
                       $unapproved_members .= '<li><a href="'.$show_link.'">'.$member->dUser->showValue().'</a>';
                       $unapproved_members .= ' | <a href="'._Link('membership','approve',$member->getEncryptID()).'">'.__('Bestätigen').'</a>';
                       $unapproved_members .= $remove_link;
                       $unapproved_members .= '</li>';
                   }
               }

               echo '<h2>'.__('Members').'</h2>';
               echo '<ul>';
               echo $full_members;
               echo '</ul>';

               if ($unapproved_members != '') {
                   echo '<h2>'.__('Neue Mitgliedsanträge').'</h2>';
                   echo '<ul>';
                   echo $unapproved_members;
                   echo '</ul>';
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
</div>

<?php
// Items Ausgeben -----------------------------
$item_list = hStorage::getVar();

if(false) $item_list = new lItems();

if ($is_member && hStorage::VarExists('ItemList')) {
    $item_list = hStorage::getVar('ItemList');
    echo $item_list->showList();
}
?>


<?=hHtml::Clear()?>

<?php //=hGoogleMaps::showAdress($location);