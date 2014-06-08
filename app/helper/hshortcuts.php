<?php
/**
*   @abstract in dieser Datei werden globale Funktionen geschrieben, bzw.
*   @version 0.1
*/


/**
*   Shortcut für hTranslator::TranslateTo
*/
function __($pStr, $pParams = '') {
    // Dummy
    return hTranslator::TranslateToOutputLanguage($pStr,$pParams);
}

function _Link($pController = '',$pAction = '',$pToken = '',$pQueryParams = array()) {
	return hRouter::Link($pController,$pAction,$pToken,$pQueryParams);
}

function _Debug($pMixed = '', $pEchoNow = true) {
    if (DEBUG) {
        if ($pEchoNow) {
            echo '<pre>';
            var_dump($pMixed);
            echo '</pre>';
        }
        else {
            hDebug::Add($pMixed);
        }
    }
}

/**
 * 
 * Liefert die Schöne Anzeige zu einem Byte-Wert
 * @param $bytes
 * @param $precision
 * @author	Admin von http://www.script-tutorials.com/pure-html5-file-upload/
 */
function BytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}



/**
 *  Aus PHP-Manual für ini_get()
 *  Liefert einen Byte-Wert für einen gegebenen Wert aus der PHP-Konfiguration
 */
function ReturnBytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

/**
 * Liefert den kleinsten Wert der für Uploads von Wichtigkeit ist.
 */
function getMaxUploadSize() {
	$max_upload = ReturnBytes(ini_get('upload_max_filesize'));
	$max_post = ReturnBytes(ini_get('post_max_size'));
	$memory_limit = ReturnBytes(ini_get('memory_limit'));
	return min($max_upload, $max_post, $memory_limit);	
}


/**
 * @abstract Liefert Wert zurück, wenn Key in Array existiert
 * @param string $pKey
 * @param array $pArray
 * @return mixed
 *
 *
 */
function _a($pKey = '', $pArray = array()) {
    if ($pKey != '' && is_array($pArray) && array_key_exists($pKey,$pArray)) {
            return $pArray[$pKey];
    }
    return '';
}

/**
 * @abstract Liefert Wert zurück, wenn Key in $_POST existiert
 * @param string $pKey
 * @return mixed
 */
function _p($pKey = '') {
    return _a($pKey,$_POST);
}


/**
 * Prüft ob die angegebene Variable von dem angegebenen Objekttypen ist.
 *
 *
 */
function _isO($pObject = null, $pClassname = '') {
    return ($pObject != null && $pClassname != '' && is_object($pObject) && get_class($pObject) == $pClassname);
}

function str_starts_with($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

function str_ends_with($haystack, $needle) {
    return strpos($haystack, $needle) + strlen($needle) === strlen($haystack);
}

?>