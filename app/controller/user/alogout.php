<?php
/**
 * @version 0.1 // 08. Dezember 2011. Einfaches Logout mit Meldung, dass man ausgeloggt ist.
 * @version 0.2 // 09. Mai 2013: Umgestellt auf Weiterleitung zu User/Login
 */
hSession::Logout();
hSuccess::Add(__('Du wurdest ausgeloggt.'));
hRouter::Redirect(_Link('user', 'login'));
?>