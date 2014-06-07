<?php
class  hMeta {
	
	static $mTitle = '';
	static $mDescription = '';
	static $mKeywords = '';
	
	static function getTitle() {
		if (self::$mTitle == '') {
			return APPLICATIONNAME;
		}
		return self::$mTitle;
	}
}
?>