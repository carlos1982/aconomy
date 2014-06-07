<?php
/**
 * 	Erwartet $person;
 */

$person = hStorage::getVar('User');

?>
<h1><?=__('Passwort vergessen?')?></h1>
<?=hHtml::getFormTag(_Link(hRouter::getController(),'lostpassword'))?>
<?php
if (is_object($person) || get_class($person) == 'oUser') {
    echo $person->dEmail->Edit();
}
?>
<?=hHtml::EditFormButton(__('Neues Passwort anfordern'),'sendpassword')?>

<a href="<?=_Link(hRouter::getController(),'login')?>"><?=__('Zurück zum Login')?></a>