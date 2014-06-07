<?php
/**
 * 	@abstract Ein Konstrukt um Token für das Resetten von Passwörtern zu speichern.
 *  @version 0.1
 *  @author Carlos Cota Castro
 */

class oPasswordResetter extends oStandard{

	var $dResetPasswordToken;
	
	function __construct($pTableName = '') {
		$this->Init();
		$this->mDBTable = $pTableName;
	}
	
	function Init() {
		parent::Init();
		
		$this->dResetPasswordToken = new tString(array('Fieldname' => 'ResetPasswordToken', 'Label' => 'Password Reset Token', 'Editable' => false));
	}
	
}
?>
