<?php
/**
 * Ausgabe um Leute weiterzuleiten.
 */
?>
<h1><?=__('Weiterleitung')?></h1>
<p><?=__('Falls Ihr Browser Sie nicht automatisch weiterleitet, klicken Sie bitte <a href="{#0}">hier</a>.',array(hRouter::getRedirect()))?></p>