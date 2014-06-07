<?php
class hCss {
	
	static $mCssFiles = array();
	
	static function init() {
		
		if (count(self::$mCssFiles) < 1) {
			
			self::$mCssFiles = array(
					'screen' => array(
							'grid' => 1,
							'screen' => 1,
							'screen_nav' => 1,
							'screen_forms' => 1,
							'screen_footer' => 1,
							'screen_listtables' => 1,
							'screen_buttons' => 1,
							'screen_ajax' => 1,
							'screen_ids' => 1,
							'screen_sidebar' => 1
					)
			);
		}
	}
	
	static function activateCssFile($pCssFile) {
		self::init();
		self::$mCssFiles[hRouter::getFormat()][$pCssFile] = 1;
	}
	
	static function deactivateCssFile($pCssFile) {
		self::init();
		self::$mCssFiles[hRouter::getFormat()][$pCssFile] = 0;
	}
	
	static function getCssLink() {
		self::init();
		$return = '';
		if (array_key_exists(hRouter::getFormat(), self::$mCssFiles)) {
			
			foreach (self::$mCssFiles[hRouter::getFormat()] as $css_file => $state) {
				if ($state == 1) {
					$file_name = CSS_PATH.$css_file.'.css';
					if (file_exists($file_name)) {
						hDebug::Add($css_file.' wurde ausgegeben!');
						$return .= '<link rel="stylesheet" href="'.PROTOKOLL.CSS_URL.$css_file.'.css" type="text/css" />'."\n";
					}
					else {
						hDebug::Add($file_name.' wurde nicht gefunden!');
					}
				}
			}
		}
		else {
			hDebug::Add(self::$mCssFiles);
		}
		return $return;
	}
	
}
?>