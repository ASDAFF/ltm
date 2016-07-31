<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!check_bitrix_sessid()) return;

global $errors;

if (!is_array($errors) && strlen($errors) <= 0 || is_array($errors) && count($errors) <= 0) {
	echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
} else {
	$alErrors = "";

	for ($i = 0; $i < count($errors); $i++) {
		$alErrors .= $errors[$i]."<br>";
	}

	$arMessage = [
		"TYPE" => "ERROR",
		"MESSAGE" => GetMessage("MOD_INST_ERR"),
		"DETAILS" => $alErrors,
		"HTML" => true
	];

	echo CAdminMessage::ShowMessage($arMessage);
}

if ($objEx = $APPLICATION->GetException()) {
	$arMessage = [
		"TYPE" => "ERROR",
		"MESSAGE" => GetMessage("MOD_INST_ERR"),
		"HTML" => true,
		"DETAILS" => $objEx->GetString()
	];

	echo CAdminMessage::ShowMessage($arMessage);
}

?>

<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>">
	<input type="submit" name="" value="<?=GetMessage("MOD_BACK")?>">
</form>

