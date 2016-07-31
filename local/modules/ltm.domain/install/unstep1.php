<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$message = array(
	"TYPE" => "ERROR",
	"MESSAGE" => GetMessage("MOD_UNINST_ERR"),
	"DETAILS" => GetMessage("LTM_DOMAIN_MODULE_INSTALL_DELETE_NO_NO"),
	"HTML" => true
);

echo CAdminMessage::ShowMessage($message);

?>
<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>">
	<input type="submit" name="" value="<?=GetMessage("MOD_BACK")?>">
</form>
