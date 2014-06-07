<?php
if (false) $user = new oUser();
$user = hStorage::getVar('User');
if (!_isO($user, 'oUser')) {
    echo 'Error';
    return;
}
?>

<h1><?=$user->getDisplayName()?></h1>
<p><?=$user->dCity?></p>