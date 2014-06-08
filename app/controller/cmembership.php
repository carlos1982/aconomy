<?php

switch (hRouter::getAction()) {

    default:	if (!hSession::IsLoggedIn()) {
                    hRouter::NoPermission();
                }
                break;

}