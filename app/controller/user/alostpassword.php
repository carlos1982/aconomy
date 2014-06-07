<?php
/**
 * Controller überprüft ob Password angefordert werden soll. Fall ja, dann wird ein neues Passwort erstellt und per E-Mail geschickt.
 */


$person = new oUser();

$mail_subject = '';
$mail_text = '';


if(_p('editmode') == 'sendpassword' || hRouter::getToken() != '') {

	if(hRouter::getToken() != '') {
		hDebug::Add('Reset-Token gefunden.' . hRouter::getToken());
        $person->AddCondition('ResetPasswordToken', hRouter::getToken());
	}
	else {
		$person->AddCondition('Email', _p('Email'));
	}

	if($person->LoadFromDB()) {

		
		if(hRouter::getToken() != '') {
			// Token übergeben, also wurde der Link aus der E-Mail angeklickt.
		
			$new_password = hSalt::GenerateNewPassword();
			$person->dPassword->setValue(hSalt::Salt($new_password,$person->mDBTable));

			$mail_subject = __('Ihr Passwort wurde zurückgesetzt');
			$mail_text = <<<EOL
	
Ihr neues Passwort:

Your new password:

-------------------------------------
		$new_password
-------------------------------------
	
EOL;
            if (DEBUG) {
                echo $new_password;
            }
		}
		else {
			
			// E-Mail wurde nur angegeben. Token muss gesetzt werden!
			
			$password_reset_token = hSalt::CreateUniqueID();

			$password_resetter = new oPasswordResetter($person->mDBTable);
			$password_resetter->dID->setValue($person->getID());
			$password_resetter->dToken->setValue($person->getToken());
			$password_resetter->dResetPasswordToken->setValue($password_reset_token);
			if (!$password_resetter->Update()) {
				hError::Add(__('Ein Fehler trat auf. Bitte wenden Sie sich an den Webmaster: {#0}',array(ADMINISTRATOR_EMAIL)));
			}
			else {
			
			
			$reset_url = PROTOKOLL.BASEURL._Link(hRouter::getController(),'lostpassword',$password_reset_token);
			
			$mail_subject = __('Neues Password beantragt');
			
			$webmaster = ADMINISTRATOR_EMAIL;
			
			$mail_text = <<<EOL
	
Sie haben ein neues Passwort für das APPLICATIONNAME
beantragt. Bitte besuchen Sie die nachstehende URL
um ein neues Passwort zu generieren.

Please klick on the following link to generate a new
password.  

$reset_url

Sollten Sie nicht beantragt haben Ihr Passwort zu ändern,
so ignorieren Sie diese E-Mail bitte. Sollten Sich diese Anfragen
häufen, so bitte wenden Sie sich bitte an den Webmaster:
$webmaster

If you haven't solicited to reset your password just ignore this
e-mail. If you get more many solicitations, please contact the
webmaster: $webmaster 
	
EOL;
	}

			}

		if($mail_text != '') {

			$header = 'From: '.ADMINISTRATOR_EMAIL."\r\n";
    		$header .= 'Reply-To: '.ADMINISTRATOR_EMAIL."\r\n";

			if(mail($person->dEmail->getValue(), $mail_subject, $mail_text,$header) || DEBUG) {

				if(hRouter::getToken() != '') {

                    hStorage::addVar('User', $person);

					if($person->Update()) {
						hSuccess::Add(__('Eine E-Mail zum zurücksetzen des Passworts ist verschickt worden'));

					}
					else {
						hError::Add(__('Ihr Resetcode konnte nicht gespeichert werden.'));
						hError::Add(__('Bitte wenden Sie sich an den Webmaster: {#0}.',array(ADMINISTRATOR_EMAIL)));
					}
				}
				else {
					hSuccess::Add(__('Schauen Sie bitte in Ihr E-Mail Postfach. Ein Link zum Resetten des Passwortes wurde Ihnen zugeschickt.'));
                    if (DEBUG) {
                        echo $reset_url;
                    }
				}
			}
			else {
				hError::Add(__('E-Mail konnte nicht verschickt werden. Bitte wenden Sie sich an den Webmaster: {#0}.',array(ADMINISTRATOR_EMAIL)));
			}
		}
		
		
		
		
	}
	else {
		hError::Add(__('Datensatz nicht gefunden!'));

	}
}
else {
    hStorage::addVar('User', $person);
}

?>