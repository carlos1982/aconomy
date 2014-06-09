<?php
/**
* @abstract Ein Helper für die Ausgabe aller möglichen HTML-Elemente.
* Wichtig: Link() muss zum Routing kompatibel sein.
* @author Carlos Cota Castro
* @version 0.1
* 
*/

class hHtml {
    
	/**
	 * Eigentlich nur, damit man nicht vergisst method="post zu benutzen".
	 * @param URI $pLink
	 */
	static function getFormTag($pLink = '#') {
		$id = hRouter::getController().'_'.hRouter::getAction().'_form';
		return '<form action="'.$pLink.'" id="'.$id.'" enctype="multipart/form-data" method="post">';
	}
	
	/**
	 * Liefert HTML für einen Button zurück.
	 * @param String $pLabel	// Label sollte bereits übersetzt sein
	 * @param String $pClass	// eine CSS-Klasse
	 */
	static function getButtonTag($pLabel, $pType = 'submit', $pClass = '', $pID = '') {
		if ($pLabel == '') {
			hDebug::Add('Fehler: Button ohne Angaben');
			return '';
		}
		$css_class = ($pClass != '') ? ' class="'.$pClass.'"' : '';
		$dom_id = ($pID != '') ? ' id="'.$pID.'"' : '';
		$button_type = ($pType != '') ? ' type="'.$pType.'"' : '';
		return '<button'.$css_class.$dom_id.$button_type.'><span>'.$pLabel.'</span></button>';
	}
 
	/**
	 * 
	 * Liefert ein a-Tag, dass für Buttons verwendet werden kann.
	 * @param String $pLink		// Erwartet URL
	 * @param String $pLabel	// Erwartet übersetzen String
	 * @param String $pClass	// CSS-Klasse
	 * @param String $pID	// DOM-ID
	 */
	static function getLinkButtonTag($pLabel = '', $pLink = '', $pClass = 'btn btn-default', $pID = '') {
		if (($pLink == '') || ($pLabel == '')) {
			hDebug::Add('Fehler: Link ohne Angaben');
			return '';
		}
		$css_class = ($pClass != '') ? ' class="'.$pClass.'"' : '';
		$dom_id = ($pID != '') ? ' id="'.$pID.'"' : '';
		return '<a href="'.$pLink.'"'.$css_class.$dom_id.'><span>'.$pLabel.'</span></a>';
	}
	
	static function Error($pMessage = '') {
		if ($pMessage != '') {
			return '<div class="errormessage">'.$pMessage.'</div>';
		}
	}
	
	static function EditFormButton($pLabel = 'Speichern', $pEditMode = 'save') {
		$dom_id = hRouter::getController().'_'.hRouter::getAction().'_button';
		$ret = '<input type="hidden" id="editmode" name="editmode" value="'.$pEditMode.'" />';
		$ret .= self::getButtonTag($pLabel,'submit','btn btn-success',$dom_id);
		$ret .= '</form>';
		return $ret;
	}
	
	static function Clear() {
		return '<div class="clear"><hr /></div>';
	}
    
}

?>