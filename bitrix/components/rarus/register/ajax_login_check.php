<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arResponse = array();

if(check_bitrix_sessid("SID"))
{
	$login = $_REQUEST["LOGIN"];

	if(strlen($login) <= 0)
	{
		$arResponse["STATUS"] = "error";
		$arResponse["ERROR_TEXT"] = "Empty login";
		$arResponse["ERROR_CODE"] = "require";
	}
	elseif(strlen($login) < 3)
	{
		$arResponse["STATUS"] = "error";
		$arResponse["ERROR_TEXT"] = "Min length is 3 symbols";
		$arResponse["ERROR_CODE"] = "minLength";
	}
	elseif(strlen($login) > 16)
	{
		$arResponse["STATUS"] = "error";
		$arResponse["ERROR_TEXT"] = "Max length is 16 symbols";
		$arResponse["ERROR_CODE"] = "maxLength";
	}
	elseif(!preg_match("/^[a-zA-Z0-9_@\.-]{3,16}$/",$login))
	{
		$arResponse["STATUS"] = "error";
		$arResponse["ERROR_TEXT"] = "Login must consist of latin letters, numbers or underscores";
		$arResponse["ERROR_CODE"] = "login";
	}
	else //проверяем на наличие в битриксе
	{
		$rsUser = CUser::GetByLogin($login);
		if($arUser = $rsUser->Fetch())//логин занят
		{
			$arResponse["STATUS"] = "error";
			$arResponse["ERROR_TEXT"] = "Login has already been taken";
			$arResponse["ERROR_CODE"] = "login_busy";
		}
		else 
		{
			$arResponse["STATUS"] = "success";
		}
	}
}
else
{
	$arResponse["STATUS"] = "error";
	$arResponse["ERROR_TEXT"] = "Invalid session identifier";
	$arResponse["ERROR_CODE"] = "BAD_SID";
}
echo CUtil::PhpToJSObject($arResponse);


?>