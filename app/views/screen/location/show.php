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
    <h2>Members</h2>
    <?php
       if ($location->UserIsMember()){
           if ($location->UserIsApprovedMember()) {
               echo 'Du bist Mitglied dieser Gruppe';
               // @todo Move to Class
               echo '<ul>';
               foreach ($location->mMemberships->mItems as $member) {
                   $user = new oUser();
                   $user->AddCondition('id',$member->dUser->getValue());
                   $user->LoadFromDB();
                   echo '<li><a href="'._Link('user','show',$user->getEncryptID()).'">'.$member->dUser->showValue().'</a></li>';
               }
               echo '</ul>';
           }
           else {
               echo 'Noch nicht bestätigt';
           }
       }
       else {
           echo '<a href="#" class="btn request-membership-button">'.__('Gruppe beitreten').'</a>';
       }
    ?>
</div>


<?=hHtml::Clear()?>

<?php //=hGoogleMaps::showAdress($location);