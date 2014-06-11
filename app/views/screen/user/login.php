<?php
/**
 *  Erwartet:
 *	$login_email;
 *	$login_password;
 */

$login_email = hStorage::getVar('LoginEmail');
$login_password = hStorage::getVar('LoginPassword');

?>

<div id="StudentsLoginFormContainer" class="fivecol">

	<h2><?=__('Anmeldung')?></h2>

	<?=hHtml::getFormTag(_Link('user','login'))?>
        <?php

        echo $login_email->Edit();
        echo $login_password->Edit();

        ?>
        
	<?=hHtml::EditFormButton(__('Anmelden'))?>
	<p><a href="<?=_Link('user','lostpassword')?>"><?=__('')?>Passwort vergessen?</a></p>


</div>

<div id="RegisterHintsContainer" class="fivecol">
	
	<h2><?=__('Registrierung')?></h2>
	
	<p><?=__('Wenn Sie noch nicht im Abgabesystem angemeldet sind, dann können Sie sich <br />{#0}',array(hHtml::getLinkButtonTag(__('Hier Registrieren'),_Link('user','register'),'btn btn-success')))?>
	
	</p>

</div>

<?=hHtml::Clear()?>