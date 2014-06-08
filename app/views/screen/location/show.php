<?php
if (false) $location = new oLocation();
$location = hStorage::getVar('Location');
if (!_isO($location, 'oLocation')) {
    echo 'Error';
    return;
}
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
       if ($location->UserIsMember()){
           if ($location->UserIsApprovedMember()) {
               echo 'Du bist Mitglied dieser Gruppe';
               // @todo Move to Class

               $approved_members = '';
               $full_members = '';

               foreach ($location->mMemberships->mItems as $member) {
                   $user = new oUser();
                   $user->AddCondition('id',$member->dUser->getValue());
                   $user->LoadFromDB();
                   if ($member->dApproved->getValue() == 1) {
                       $full_members .= '<li><a href="'._Link('user','show',$user->getEncryptID()).'">'.$member->dUser->showValue().'</a></li>';
                   }
                   else {
                       $approved_members .= '<li><a href="'._Link('user','approve',$member->getEncryptID()).'">'.$member->dUser->showValue().'</a></li>';
                   }
               }

               echo '<h2>'.__('Members').'</h2>';
               echo '<ul>';
               echo $full_members;
               echo '</ul>';

               if ($approved_members != '') {
                   echo '<h2>'.__('Neue Mitgliedsanträge').'</h2>';
                   echo '<ul>';
                   echo $approved_members;
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


<?=hHtml::Clear()?>

<?php //=hGoogleMaps::showAdress($location);