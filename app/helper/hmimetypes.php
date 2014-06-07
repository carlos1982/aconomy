<?php
/**
 * @abstract Funktionen in Zusammenhang mit Mime-Types.
*/

class hMimeTypes {

	
	static $mMimetypes = array(
	'*' 				=>	'Beliebige Datei',
	'text/*'			=>	'Beliebige Text-Datei',
	'image/*'			=>	'Beliebige Grafik-Datei',
	'application/*'		=>	'Beliebige Anwendung',
	'image/jpeg'		=>	'jpg-Grafik',
	'image/png'			=>	'png-Grafik',
	'image/tiff'		=>	'tiff-Grafik',
	'image/svg+xml'		=>	'svg-Grafik',
	'text/plain'		=>	'text-datei',
	'text/html'			=>	'HTML-Datei',
	'text/xml'			=>	'XML-Datei',
	'application/xml'	=>	'application/xml',
	'text/csv'			=>	'CSV-Datei',
	'application/zip'	=>	'ZIP-Datei',
	'application/pdf'	=>	'PDF-Dokument',
	'application/xhtml+xml'	=> 'XHTML-Datei',
	'application/vnd.*' =>	'z.B. Open Office-Dokument');
	
	
	/**
	 * Liefert das Array mit allen Mime-Types zurück
	 * @return array
	 */
	static function getAllMimeTypesArray() {
		return self::$mMimetypes;
	}
	
	/**
	 * Überprüft ob ein übergebener Mime-Type erlaubt ist.
	 * @param unknown_type $pFileType
	 * @param array or empty $pAllowedMimeTypes
	 */
	static function isAllowedMimeType($pFileMimeType,$pAllowedMimeTypes = '') {
		
		if (($pFileMimeType == '') || !(is_string($pFileMimeType))) {
			hDebug::Add('Mime-Type der Datei wurde nicht richtig übergeben!');
			return false;
		}
		
		if(!is_array($pAllowedMimeTypes)) {
			if($pAllowedMimeTypes == ''){
				$allowed_mime_types = self::$mMimetypes;
			}
			else {
				hDebug::Add('Erlaubte Mime-Types nicht als Array übergeben.');
				return false;
			}
		}
		else {
			$allowed_mime_types = $pAllowedMimeTypes;
		}
		
		list($file_mime_group,$file_mime_type) = explode('/', $pFileMimeType);
		
		foreach ($allowed_mime_types as $mime_type => $description) {

			if ($mime_type == '*') {
				return true;
			}
			
			list($allowed_mime_group,$allowed_mime_type) = explode('/', $mime_type);
			
			if(($file_mime_group == $allowed_mime_group)) {
				if (($file_mime_type == $allowed_mime_type) || ($allowed_mime_group == '*')) {
					return true;
				}
			}
			
		}
		
		return false; // Wenn kein Return True erfolgt ist, dann ist Mime-Type nicht erlaubt.
	}
	
	/**
	 * Übersetzt Mime-Type-Angabe in verständliche Ausgabe
	 * @param $pMimeType
	 */
	static function getExplanation($pMimeType = '') {
		if(array_key_exists($pMimeType, self::$mMimetypes)) {
			return __(self::$mMimetypes[$pMimeType]);
		}
		else {
			hDebug::Add('Keine Übersetzung für Mime-Type gefunden.: '.$pMimeType);
			return $pMimeType;
		}
	}
}

?>