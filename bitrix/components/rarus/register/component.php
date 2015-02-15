<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/*Параметры компонента*/
$arParams["EXHIBIT_IBLOCK_ID"] = intval($arParams["EXHIBIT_IBLOCK_ID"]);
if($arParams["EXHIBIT_IBLOCK_ID"] <= 0)
{
	$arResult["ERROR_MESSAGE"] = GetMessage("EXHIBIT_ID_NOT_FOUND")."<br />";
}

$arParams["GUEST_FORM_ID"] = intval($arParams["GUEST_FORM_ID"]);
if($arParams["GUEST_FORM_ID"] <= 0)
{
	$arResult["ERROR_MESSAGE"] = GetMessage("GUEST_FORM_ID_NOT_FOUND")."<br />";
}

$arParams["COMPANY_FORM_ID"] = intval($arParams["COMPANY_FORM_ID"]);
if($arParams["COMPANY_FORM_ID"] <= 0)
{
	$arResult["ERROR_MESSAGE"] = GetMessage("COMPANY_FORM_ID_NOT_FOUND")."<br />";
}

$arParams["PARTICIPANT_FORM_ID"] = intval($arParams["PARTICIPANT_FORM_ID"]);
if($arParams["PARTICIPANT_FORM_ID"] <= 0)
{
	$arResult["ERROR_MESSAGE"] = GetMessage("PARTICIPANT_FORM_ID_NOT_FOUND")."<br />";
}

$arParams["IBLOCK_PHOTO"] = intval($arParams["IBLOCK_PHOTO"]);
if($arParams["IBLOCK_PHOTO"] <= 0)
{
	$arResult["ERROR_MESSAGE"] = GetMessage("IBLOCK_PHOTO_NOT_FOUND")."<br />";
}

if($arParams["COLLEAGUE_SEND_EMAIL"] != "Y")
{
	$arParams["COLLEAGUE_SEND_EMAIL"] = "N";
}

require_once("function.php");

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
{
	$arResult["ERROR_MESSAGE"] = GetMessage("MODULES_NOT_FOUND")."<br />";
}

if($_REQUEST["AJAX_CALL"] == "Y" || $_REQUEST["is_ajax_post"] == "Y")
{
	$APPLICATION->RestartBuffer();
}



// if user registration blocked - return auth form
if (COption::GetOptionString("main", "new_user_registration", "N") == "N")
	$APPLICATION->AuthForm(array());

$arResult["USE_EMAIL_CONFIRMATION"] = COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y" ? "Y" : "N";

$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");

if($def_group <> "")
	$arResult["GROUP_POLICY"] = CUser::GetGroupPolicy(explode(",", $def_group));
else
	$arResult["GROUP_POLICY"] = CUser::GetGroupPolicy(array());

// use captcha?
$arResult["USE_CAPTCHA"] = COption::GetOptionString("main", "captcha_registration", "N") == "Y" ? "Y" : "N";

// start values
$arResult["VALUES"] = array();
$arResult["ERRORS"] = array();
$register_done = false;

$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/'. bitrix_sessid()."/";
//регистрация лтм

if(isset($_REQUEST["USER_TYPE"]))
{
	$arResult["USER_TYPE"] = htmlspecialcharsEx($_REQUEST["USER_TYPE"]);
}
else 
{
	if(LANGUAGE_ID == "ru")
	{
		$arResult["USER_TYPE"] = "BUYER";
	}
	else 
	{
		$arResult["USER_TYPE"] = "PARTICIPANT";
	}
}


$arResult["AJAX_PATCH"] = array(
	"UPLOAD" => $this->GetPath(). "/ajax_upload_photo.php",
	"DELETE" => $this->GetPath(). "/ajax_delete_photo.php",
	"LOGIN" => $this->GetPath(). "/ajax_login_check.php"
);


//получаем все выставки

//получение выставок
$arFilter = array(
		"IBLOCK_ID" => $arParams["EXHIBIT_IBLOCK_ID"],
		"ACTIVE" => "Y"
);

$arSelect = array(
		"ID",
		"NAME",
		"IBLOCK_ID",
		"CODE",
);


$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);
while($obElement = $rsElement->GetNextElement(true, false))
{
	$arItem = $obElement->GetFields();
	$arItem["PROPERTIES"] = $obElement->GetProperties();
	
	//получам статус выставки для гостя
	$statusGuest = array();
	switch($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'])
	{
		case "Available" : //если статус выставки для гостей на утром - Разрешена
			switch ($arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'])
			{
				case "Available":
					 $statusGuest = array(
						"MORNING" => "Y",
						"EVENING" => "Y",
						"ALL" => "OK",
						"TEXT" => "Available"
					);
				break;
				
				
				 case "Sold out":
				 	$statusGuest = array(
					 	"MORNING" => "Y",
					 	"EVENING" => "N",
					 	"ALL" => "OK",
					 	"TEXT" => "Available"
				 		);
				 break;		

				 case "Waiting list":
				 	$statusGuest = array(
					 	"MORNING" => "Y",
					 	"EVENING" => "Y",
					 	"ALL" => "AN",
					 	"TEXT" => "Waiting list"
				 		);
				 break;
				 
			}
		break;
		
		
		case "Sold out" : 
			switch ($arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'])
			{
				case "Available":
					$statusGuest = array(
					"MORNING" => "N",
					"EVENING" => "Y",
					"ALL" => "OK",
					"TEXT" => "Available"
							);
							break;
		
				case "Sold out":
					$statusGuest = array(
					"MORNING" => "N",
					"EVENING" => "N",
					"ALL" => "NO",
					"TEXT" => "Sold out"
							);
						 break;
		
				case "Waiting list":
					$statusGuest = array(
					"MORNING" => "N",
					"EVENING" => "Y",
					"ALL" => "AN",
					"TEXT" => "Waiting list"
							);
						 break;
			}
		break;
		
		
		
		case "Waiting list" : 
			switch ($arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'])
			{
				case "Available":
					$statusGuest = array(
					"MORNING" => "Y",
					"EVENING" => "Y",
					"ALL" => "AN",
					"TEXT" => "Waiting list"
							);
							break;
		
				case "Sold out":
					$statusGuest = array(
					"MORNING" => "Y",
					"EVENING" => "N",
					"ALL" => "AN",
					"TEXT" => "Waiting list"
							);
							break;
		
				case "Waiting list":
					$statusGuest = array(
					"MORNING" => "Y",
					"EVENING" => "Y",
					"ALL" => "AN",
					"TEXT" => "Waiting list"
							);
							break;
			}
			break;
			
	}
	
	
	//Получаем статус выставки для участников
	$statusParticipant = array();
	switch($arItem['PROPERTIES']['STATUS']['VALUE_ENUM']){
		case 'Available' :
			$statusParticipant = array(
				"TEXT" => "Available",
				"ALL" => "OK"
			);
	    break;
		case 'Sold out' :
			$statusParticipant = array(
					"TEXT" => "Sold out",
					"ALL" => "NO"
			);
			 break;
		case 'Waiting list' : 
			$statusParticipant = array(
					"TEXT" => "Waiting list",
					"ALL" => "AN"
			);
			break;
		default : 			
			$statusParticipant = array(
					"TEXT" => "Available",
					"ALL" => "OK"
			); break;
	}
	$arItem["STATUS"] = array(
		"GUEST" => $statusGuest,
		"PARTICIPANT" => $statusParticipant
	);
	
	//Заносим выставки в результат
	$arResult["EXHIBITION"][$arItem["ID"]] = $arItem;
	
}

// register user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["confirmregister"]) && $_REQUEST["confirmregister"] == "Y" && check_bitrix_sessid())
{
	if(COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y')
	{
		//possible encrypted user password
		$sec = new CRsaSecurity();
		if(($arKeys = $sec->LoadKeys()))
		{
			$sec->SetKeys($arKeys);
			$errno = $sec->AcceptFromForm(array('REGISTER'));
			if($errno == CRsaSecurity::ERROR_SESS_CHECK)
				$arResult["ERRORS"][] = GetMessage("main_register_sess_expired");
			elseif($errno < 0)
			$arResult["ERRORS"][] = GetMessage("main_register_decode_err", array("#ERRCODE#"=>$errno));
		}
	}

	$arResult['POST_VALUES'] = htmlspecialcharsEx($_REQUEST);


	if(strlen($arResult["POST_VALUES"]["EMAIL"]) > 0 && COption::GetOptionString("main", "new_user_email_uniq_check", "N") === "Y")
	{
		$res = CUser::GetList($b, $o, array("=EMAIL" => $arResult["POST_VALUES"]["EMAIL"]));
		if($res->Fetch())
			$arResult["ERRORS"][] = GetMessage("REGISTER_USER_WITH_EMAIL_EXIST", array("#EMAIL#" => htmlspecialcharsbx($arResult["VALUES"]["EMAIL"])));
	}

	if(count($arResult["ERRORS"]) > 0)
	{
		if(COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
		{
			$arError = $arResult["ERRORS"];
			foreach($arError as $key => $error)
				if(intval($key) == 0 && $key !== 0)
					$arError[$key] = str_replace("#FIELD_NAME#", '"'.$key.'"', $error);
				CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", false, implode("<br>", $arError));
		}
	}
	else // if there;s no any errors - create user
	{

		if($arResult['POST_VALUES']["USER_TYPE"] == "BUYER")
		{
			require_once("buyer_register.php");
		}
		elseif($arResult['POST_VALUES']["USER_TYPE"] == "PARTICIPANT")
		{
			require_once("participant_register.php");
		}

	}
	
	// if user is registered - redirect him to backurl or to success_page; currently added users too
	if($register_done)
	{
		$arResult["REGISTER_COMPLETE"] = "Y";
		/*
		if($arParams["USE_BACKURL"] == "Y" && $_REQUEST["backurl"] <> '')
			LocalRedirect($_REQUEST["backurl"]);
		elseif($arParams["SUCCESS_PAGE"] <> '')
			LocalRedirect($arParams["SUCCESS_PAGE"]);
		*/
	}
}




if($arResult["USER_TYPE"] == "BUYER")
{
	require("buyer_form.php");
}
else 
{
	require("participant_form.php");
}



$arResult["VALUES"] = htmlspecialcharsEx($arResult["VALUES"]);

// all done
$this->IncludeComponentTemplate();

//удаляем фотки
if(strpos($uploaddir, bitrix_sessid())!==false)
{
	delTreeDir($uploaddir);
}

if($_REQUEST["AJAX_CALL"] == "Y" || $_REQUEST["is_ajax_post"] == "Y")
{
	die();
}
?>