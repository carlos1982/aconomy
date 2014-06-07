<?php
/**
 * Hilfsfunktionen im Zusammenhang mit Semester-Angaben.
 */


class hSemester{
	

	/**
	 * Gibt das aktuelle Semester zurück.
	 * Ab zwei Wochen vor Semesterbeginn wird bereits das nächste Semester angezeigt.
	 */
	static function getThisSemester() {
		$year = date('Y');
		$month_day = date('md');
		
		if (($month_day > '0315') && ($month_day < '0915')) {
			return $year.SoSe;
		}
		elseif ($month_day > '0915'){
			return $year.'WiSe';
		}
		else {
			return ($year-1).'WiSe';
		}
	}
	
	/*
	 * Liefert time-Wert zurück seit Semester-Beginn
	 */
	static function getSemesterBegining() {
		$year = date('Y');
		$month_day = date('md');
		
		if (($month_day > '0315') && ($month_day < '0915')) {
			return $year.'-04-01 00:00:00';
		}
		elseif ($month_day > '0915'){
			return $year.'-10-01 00:00:00';
		}
		else {
			return ($year-1).'-10-01 00:00:00';
		}
	}
}

?>