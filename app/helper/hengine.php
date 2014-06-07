<?php
/**
 * @abstract Die Einbindung der Controller / Actions / Views soll künftig mit Hilfe dieser Klasse vollzogen werden.
 * In der Index werden lediglich die entsprechenden Functionen dieser Klasse aufgerufen. Somit kann die Kapselung von Variablen
 * an das entsprechende bisherige Include erreicht werden.
 * @author Carlos Cota Castro
 * @version 0.1
 */

class hEngine {
	
	static function Controller() {

		$controller_path = CONTROLLER_PATH.'c'.hRouter::getController().'.php';
		if(file_exists($controller_path)) {
			include_once($controller_path);
		}
		else {
		    hRouter::setView('404');
		    hDebug::Add('Controller nicht gefunden.');
		}
	}

	
	static function Action() {
		$action_path = CONTROLLER_PATH.hRouter::getController().'/a'.hRouter::getAction().".php";
		if(file_exists($action_path)) {
			include_once($action_path);
		}
		else {
			hRouter::setView('404');
			hDebug::Add('Action nicht gefunden: '.$action_path);
		}
	}
	
	static function View() {
		if(file_exists(VIEWS_PATH.hRouter::getFormat().'/header.php')) {
			include(VIEWS_PATH.hRouter::getFormat().'/header.php');
		}
		if(file_exists(VIEWS_PATH.hRouter::getFormat().'/'.hRouter::getModel().'/'.hRouter::getView().'.php')){
			include(VIEWS_PATH.hRouter::getFormat().'/'.hRouter::getModel().'/'.hRouter::getView().'.php');
		}
		else {
			if(file_exists(VIEWS_PATH.hRouter::getFormat().'/404.php')){
				include(VIEWS_PATH.hRouter::getFormat().'/404.php');
			}
			else {
				include(VIEWS_PATH.'/404.php');
			}
		}
		if(file_exists(VIEWS_PATH.hRouter::getFormat().'/footer.php')) {
			include(VIEWS_PATH.hRouter::getFormat().'/footer.php');
		}
	}
	
}

?>