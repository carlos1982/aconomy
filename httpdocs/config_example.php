<?php

/*
 *** IMPORTANT ***
 * CHANGE THE SALTS in
 * app/helper/hsalt.php
 */

/** PATHS */
define('BASEDIR', '/var/www/vhosts/aconomy/app/');
define('DOCUMENT_PATH', '/var/www/vhosts/aconomy/httpdocs/');
define('HELPER_PATH', BASEDIR.'helper/');
define('VIEWS_PATH', BASEDIR.'views/');
define('CONFIG_PATH', BASEDIR.'config/');
define('CONTROLLER_PATH', BASEDIR.'controller/');
define('DATATYPE_PATH', BASEDIR.'datatypes/');
define('INTERFACE_PATH', BASEDIR.'interfaces/');
define('MODEL_PATH', BASEDIR.'models/');
define('UPLOAD_PATH', BASEDIR.'uploads/');
define('CSS_PATH', DOCUMENT_PATH.'css/');


/** URLs */
define('BASEURL', 'url.de');  // BASE URL should NEVER EVER have the protocol in it?! http://
define('CSS_URL', BASEURL.'css/');

/** Database 
 * see app/helper/hmysql(_example) */

/**
 * Admin-Rollen
 */
define('SUPERADMIN', 99);
define('REGIONALADMIN', 50);
define('VERIFIEDUSER', 25);
define('LOGGEDIN', 10);
define('INACTIVE', 1);	// Eigentlich doof.
define('LOGGEDOUT', 0);	// Eigentlich doof.


/*
 * Kontaktpersonen 
 */
define('ADMINISTRATOR_EMAIL', 'example@yourproject.de');


/** Diverse */
define('PROTOKOLL', 'http://'); // oder https://
define('DEBUG', true);
define('CLAIM', 'Her mit dem schönen Leben');
define('APPLICATIONNAME', 'Aconomy');


define('DEFAULT_FORMAT', 'screen');
define('DEFAULT_LANGUAGE', 'de');

?>
