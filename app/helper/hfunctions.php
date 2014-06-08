<?php
/**
 * Created by PhpStorm.
 * User: bison
 * Date: 08.06.14
 * Time: 03:39
 */
class hFunctions {
    static function str_starts_with($haystack, $needle) {
        return strpos($haystack, $needle) === 0;
    }

    static function str_ends_with($haystack, $needle) {
        return strpos($haystack, $needle) + strlen($needle) === strlen($haystack);
    }
}

?>