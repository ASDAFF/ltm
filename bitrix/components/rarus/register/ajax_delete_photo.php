<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arResponse = array();

if(check_bitrix_sessid("SID"))
{
	$file = $_SERVER["DOCUMENT_ROOT"] . $_REQUEST["FILE"];
	
	if(file_exists($file))
	{
		if(strstr($file, bitrix_sessid()) !== false)
		{
			unlink($file);
			$arResponse["STATUS"] = "success";
		}
		else 
		{
			$arResponse["STATUS"] = "error";
			$arResponse["ERROR_TEXT"] = "Invalid file path";
		}
	}
	else 
	{
		$arResponse["STATUS"] = "success";
	}
}
else 
{
	$arResponse["STATUS"] = "error";
	$arResponse["ERROR_TEXT"] = "Invalid session identifier";
}
echo CUtil::PhpToJSObject($arResponse);
?>