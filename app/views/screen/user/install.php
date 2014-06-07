<?php
/**
 * @usedin employee/install
 */

$user = hStorage::getVar('User');

?>
<?=hHtml::getFormTag(_Link('user','install'))?>
<?php
echo $user->Edit();
?>
<?=hHtml::getButtonTag(__('Installieren'))?>
</form>
