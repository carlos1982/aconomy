<?php
/**
*   @abstract  Registrierungformular
*   @author     Carlos Cota Castro
*   @version    0.1   
*   @usedin     /student/register/
*/

$user = hStorage::getVar('User');

?>

<h1><?=__('Registrierung')?></h1>

<?=hHtml::getFormTag(_Link('user','register'))?>
<?=$user->Edit()?>
<?=hHtml::getButtonTag(__('Registrieren'), 'submit', 'btn btn-success')?>