<?php
/**
 * @abstract 
 * Hier wird das Routing anhand der Parameter für Controller und Action geregelt.
 * In Controller und Actions wird mittels Funktionen View() und Model() die richtigen Views gesetzt.
 * Mit get(Controller|Action|Modell|View) erhällt man die entsprechenden Werte zum Includen der entsprechenden
 * benötigten Dateien.
 * @author Carlos Cota Castro
 * @version 0.1	// Primitive Version
 * @version 0.2 // NoPermission() hinzugefügt. mLanguage und getter-Funktion hinzugefügt (Aber nur der vollständigkeit halber).
 * 
 */

class hRouter {
	
	static $mController = 'user';
	static $mAction = 'login';
	static $mModel = 'user';
	static $mView = 'login';
	/** Für mögliche andere Ausgabe-Medien sollte schon mal eine entsprechende Konstante festgelegt werden.
	 * Für unsere derzeitige Anwendung reicht die feste Definition für spätere erweiterungen müssten die URLs
	 * nach einem Merkmal für das Format geparst werden.
	 */
	
	static $mToken = '';	// Hier werden IDs für Datensätze die geladen werden sollen übergeben. Unbedingt beim Routing mysql_real_escape_string machen!!!
	
	static $mFormat = DEFAULT_FORMAT;
	static $mLanguage = DEFAULT_LANGUAGE;
	
	static $mRedirect = '';

	
	/**
	 * Hier wird Anhand der Parameter das Routing vorgenommern
	 * @version 0.1	// Primitive Version mit Übergabe per GET-Parameter 
	 */
	static function InitRouting (){
		
		// Controller
		if (self::$mAction != 'redirect') {	// Wenn in der Session Redirect gesetzt wurde, dann mach gar nichts
		    
			$request_url = $_SERVER['REQUEST_URI'];

            // check for static file
            $staticFileExtensions = array('.css','.js','.jpg','.gif','.png','.ico','.zip','.swf');
            foreach($staticFileExtensions as $fileExt) {
                if ( hFunctions::str_ends_with($request_url, $fileExt) ) {
                    self::Redirect( $request_url );
                }
            }


            $base_url = explode('/', BASEURL);
			$url_elements_tmp = explode('/',$request_url);
            $url_elements = array();

            for ($i=0; $i<count($url_elements_tmp); $i++) {
                if (!in_array($url_elements_tmp[$i], $base_url)) {
                    $url_elements[] = $url_elements_tmp[$i];
                }
            }

			$param_index = 1;
			
			if(in_array($url_elements[$param_index],array('screen','file','ajax'))) {
				self::$mFormat = $url_elements[$param_index];
				$param_index++;
			}
			else {
				
			}

			if(in_array($url_elements[$param_index],array('de'))) {
				self::$mLanguage = $url_elements[$param_index];
				$param_index++;
			}
			
			// Controller
			if ($url_elements[$param_index] != '') {
				self::$mController = hParams::StripPathSymbols($url_elements[$param_index]);
		    	
			}
			$param_index++;
			
		    // Action
			if ($url_elements[$param_index] != '') {
				self::$mAction = hParams::StripPathSymbols($url_elements[$param_index]);
				$param_index++;
			}
			else {
				if(hSession::IsLoggedIn()) {
					self::$mAction = 'index';
				}
				else {
					self::$mAction = 'login';
				}
			}

			// Token
			if (_a($param_index,$url_elements) != '') {
					self::$mToken = mysql_real_escape_string($url_elements[$param_index]);
			}
			
			self::$mModel = self::$mController;	// Das Model-Verzeichnis heisst wenn in der Action nichts anderweitig definiert ist, genau wie der Controller.
			self::$mView = self::$mAction; // Der View heisst wenn in der Action nichts anderweitig definiert ist, genau wie die Action.
		}
	}
	
   /** 
    *   @abstract Gibt den relativen Link zu einem Controller/Action auf,
    *   @version 0.1
    *   @todo pParams und pQueryParams einbauen
    *   @param string $pController
    *   @param string $pAction
    *   @param array() $pParams			// Unterscheidung zu $pQueryParams macht dann Sinn, wenn Parameter mit "/" drangehangen werden.
    *   @param array() $pQueryParams
    */
    static function Link($pController = '', $pAction = '', $pToken = '', $pQueryParams = array()) {
        return self::getCompleteLink('','',$pController,$pAction,$pToken,$pQueryParams);
    }
	
    static function getCompleteLink($pFormat = '', $pLanguage = '', $pController = '', $pAction = '', $pToken = '', $pQueryParams = array()) {
    	$link = '/';
    	
        if($pFormat != '') {
        	$link .= urlencode($pFormat).'/';
        }
        elseif((self::getFormat() != DEFAULT_FORMAT) && (self::getFormat() != 'ajax')){
        	$link .= self::getFormat().'/';
        }
        
        if($pLanguage != '') {
        	$link .= urlencode($pLanguage).'/';
        }
    	
        if($pController != '' ) {
            $link .= urlencode($pController).'/';
            if ($pAction != '') {
                $link .= urlencode($pAction);
            }
        }
        if (is_array($pToken)) {
        	foreach ($pToken as $key => $value) {
        		$link .= '&token['.$key.']='.urlencode($value);
        	}
        }
        elseif($pToken != '') {
        	$link .= '/'.urlencode($pToken);
        }
        
        $trenner = '/';
    	foreach ($pQueryParams as $key => $value) {
        	$link .= $trenner.$key.'='.urlencode($value);
    		if($trenner == '/') {
    			$trenner = '&';	
    		}
        }
        

        //hDebug::Add('Link: '.$link);
        return $link;
    }
    
    /**
     * Wenn jemand weitergeleitet werden muss, da sonst die Action nicht mehr geladen wird, dann kann Redirekt benutzt werden. Z.B. beim Login.
     * @param $link
     */
    static function Redirect($link) {
    	self::setController('user');
    	self::setAction('redirect');
    	self::setModel('user');
    	self::setView('redirect');
    	self::$mRedirect = $link;
    }
    
	static function Debug() {
		hDebug::Add('Controller: '.self::$mController);
		hDebug::Add('Action: '.self::$mAction);
		hDebug::Add('Model: '.self::$mModel);
		hDebug::Add('View: '.self::$mView);
		hDebug::Add('Format: '.self::$mFormat);
	}
	
	/**
	 * ----------------------------------------------------------------------
	 * Getter-Funktionen
	 * ----------------------------------------------------------------------
	 */
	
	/**
	 * @return Controller
	 */
	static function getController() {
		return self::$mController;
	}
	
	/**
	 * @return Action
	 */
	static function getAction() {
		return self::$mAction;
	}
	
	/**
	 * @return Model
	 */
	static function getModel() {
		return self::$mModel;
	}
	
	/**
	 * @return View
	 */
	static function getView() {
		return self::$mView;
	}

	/**
	 * @return Token
	 */
	static function getToken() {
		return self::$mToken;
	}

	/**
	 * @return Format
	 */
	static function getFormat() {
		return self::$mFormat;
	}
	
	/**
	 * @return Language
	 */
	static function getLanguage() {
		return self::$mLanguage;
	}
	
	/**
	 * @return Redirekt-Link
	 */
	static function getRedirect() {
		return self::$mRedirect;
	}
	
	
	/**
	 * ----------------------------------------------------------------------
	 * Setter-Funktionen
	 * ----------------------------------------------------------------------
	 * Zur Verwendung in Controllern und Actions
	 */

	static function setController($pController = '') {
		if($pController != '') self::$mController = hParams::StripPathSymbols($pController);
		else hDebug::Add('Fehler2: setController() wurde aufgerufen ohne Parameter');
	}
	
	static function setModel($pModel = '') {
		if($pModel != '') self::$mModel = hParams::StripPathSymbols($pModel);
		else hDebug::Add('Fehler2: setModel() wurde aufgerufen ohne Parameter');
	}
	
	static function setAction($pAction = '') {
		if($pAction != '') self::$mAction = hParams::StripPathSymbols($pAction);
		else hDebug::Add('Fehler4: setAction() wurde aufgerufen ohne Parameter');
	}
	
	static function setView($pView = '') {
		if($pView != '') self::$mView = hParams::StripPathSymbols($pView);
		else hDebug::Add('Fehler3: setView() wurde aufgerufen ohne Parameter');
	}
	
	static function setFormat($pFormat = '') {
		if($pFormat != '') self::$mFormat = hParams::StripPathSymbols($pFormat);
		else hDebug::Add('Fehler4: setFormat() wurde aufgerufen ohne Parameter');
	}
	
	/**
	 * Kleine Hilfsfunktion zum Routen bei fehlenden Nutzerrechten. 
	 */
	static function NoPermission() {
		
		hError::Add(__('Sie haben keine Berechtigung auf diese Seite zuzugreifen.'));
		
		if (hSession::IsLoggedIn()) {
			self::Redirect(self::Link('user', 'index'));
		}
		else {
			self::Redirect(self::Link('user', 'login'));
		}
	}
}
?>