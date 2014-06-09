<?php
/**
 * User: castro
 * Date: 09.06.14
 * Time: 19:19
 */

$item = new oItem();
if ($item->LoadByToken()) {
    hStorage::addVar('Item', $item);
}
else {
    hRouter::Redirect(_Link('user','index'));
}