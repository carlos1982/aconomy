<?php

switch (hRouter::getAction()) {

    case 'remove':  if (hSession::getAdminrole() < SUPERADMIN) {
                        hRouter::NoPermission();
                    }
                    break;

    default:	    if (!hSession::IsLoggedIn()) {
                        hRouter::NoPermission();
                    }
                    break;

}