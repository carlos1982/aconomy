<?php
/**
 * Listenklasse für Benutzer
 * @version 0.1 // Einfach abgeleitet
 */

class lMemberships extends lStandard{
	var $mObjectClass = 'oMembership';
	var $mDBTable = 'Memberships';
	
	function __construct() {
		parent::__construct();
	}

    public function UserIsMember($pUserId = 0) {

        if ($pUserId == 0) {
            $pUserId = hSession::getUserId();
        }

        foreach ($this->mItems as $item) {
            if ($item->dUser->getValue() == $pUserId) {
                return true;
            }
        }

        return false;
    }

    public function UserIsApprovedMember($pUserId) {

        if ($pUserId == 0) {
            $pUserId = hSession::getUserId();
        }

        foreach ($this->mItems as $item) {
            if ($item->dUser->getValue() == $pUserId && $item->dApproved->getValue() == '1') {
                return true;
            }
        }

        return false;
    }

    public function ShowTeaser() {
        $str = '';
        foreach ($this->mItems as $item) {
            $str .= $item->show();
        }
        return $str;
    }

}

?>