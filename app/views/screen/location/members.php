<?php
/**
 * User: carlos1982
 * Date: 08.06.14
 * Time: 18:08
 */
$location = hStorage::getVar('Location');
$members = $location->mMemberships;
?>

<h1><a href="<?=_Link('location','show',$location->getEncryptId())?>"><?=$location->dName?></a> / <?=__('Members')?></h1>

<div class="tencol">
    <div class="wrapper">
<?php
    foreach($members->mItems as $member) {

        $member->dUser->LoadForeignObject();
        $user = $member->dUser->mForeignObject;



        $str = '<div class="fivecol userlist_item">';

        $str .= '<a href="'._Link('user','show',$user->getEncryptId()).'">';
        $str .= $member->dUser->showValue().'</a>';
        $str .= '</div>';
        echo $str;
    }
?>
    </div>
</div>
<?=hHtml::Clear()?>