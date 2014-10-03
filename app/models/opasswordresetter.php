<?php
/**
 * 	@abstract Ein Konstrukt um Token für das Resetten von Passwörtern zu speichern.
 *  @version 0.1
 *  @author Carlos Cota Castro
 */

class oPasswordResetter extends oStandard{

	var $dResetPasswordToken;
	
	function __construct($pTableName = '') {
		parent::__construct();
		$this->mDBTable = $pTableName;		
		$this->dResetPasswordToken = new tString(array('Fieldname' => 'ResetPasswordToken', 'Label' => 'Password Reset Token', 'Editable' => false));
	}
	
}
?>
