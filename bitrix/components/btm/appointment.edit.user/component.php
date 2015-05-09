<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/personal/";
}

if(strLen($arParams["ADMIN_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["APP_ELEMENT"])<=0 || $arParams["APP_ELEMENT"]==0){
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_ID_ERROR");
}

if(strLen($arParams["APP_ACTION"])<=0 || $arParams["APP_ACTION"]==''){
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_ACTION_ERROR");
}

if(strLen($arParams["APP_ID"])<=0){
	$arParams["APP_ID"] = "3";
}

if(strLen($arParams["APP_TYPE"])<=0){
	$arParams["APP_TYPE"] = "1";
}

if(strLen($arParams["APP_ACCEPT"])<=0){
	$arParams["APP_ACCEPT"] = "15";
}

if(strLen($arParams["APP_DECLINE"])<=0){
	$arParams["APP_DECLINE"] = "14";
}

if(strLen($arParams["GROUP_ACCEPT"])<=0){
	$arParams["APP_ACCEPT"] = "7";
}

if(strLen($arParams["GROUP_DECLINE"])<=0){
	$arParams["APP_DECLINE"] = "8";
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_AUTH_ERROR");
}

if(strLen($arParams["IS_ACTIVE"])<=0 || $arParams["IS_ACTIVE"] == 'N'){
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_BLOCKED");
}

CModule::IncludeModule('iblock');

$times = array(
	  '10:00 – 10:10', '10:15 – 10:25',
	  '10:30 – 10:40', '10:45 – 10:55',
	  '11:00 – 11:10', '11:15 – 11:25',
	  '11:30 – 11:40', '11:45 – 11:55',
	  '12:10 – 12:20', '12:25 – 12:35',
	  '12:40 – 12:50', '12:55 – 13:05',
	  '13:10 – 13:20', '13:25 – 13:35',
	  '13:40 – 13:50', '13:55 – 14:05',
	  '14:10 – 14:20'
  );
/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	global $USER;
	$thisUser = $USER->GetID();
	$appTmp = array();
	$resMeet = CIBlockElement::GetByID($arParams["APP_ELEMENT"]);
	if($obRes = $resMeet->GetNextElement())
	{
	  $arFields = $obRes->GetFields(); 
	  $ar_res = $obRes->GetProperty("TIME");
      //ПАРАМЕТРЫ встречи
	  $appTmp["ACTIVE"] = $arFields["ACTIVE"];
	  $appTmp["TIME"]["ID"] = $ar_res["VALUE_ENUM_ID"];
	  $appTmp["TIME"]["ID_REAL"]  = $appTmp["TIME"]["ID"];
	  if($appTmp["TIME"]["ID"] > 16){
		  $appTmp["TIME"]["ID"] -= 4;
	  }
	  $appTmp["TIME"]["TITLE"] = $ar_res["VALUE_ENUM"];
	  $ar_res = $obRes->GetProperty("STATUS");
	  $appTmp["STATUS"]["ID"] = $ar_res["VALUE_ENUM_ID"];
	  $appTmp["STATUS"]["TITLE"] = $ar_res["VALUE"];
	  //ОТ кого встреча
	  $appTmp["FROM"]["ID"] = $arFields["CREATED_BY"];
	  $rsUser = CUser::GetByID($arFields["CREATED_BY"]);
	  $reciverUser = $rsUser->Fetch();
	  $appTmp["FROM"]["NAME"] = $reciverUser["NAME"]." ".$reciverUser["LAST_NAME"];
	  $appTmp["FROM"]["COMPANY"] = $reciverUser["WORK_COMPANY"];
	  $appTmp["FROM"]["APP_COUNT"] = $reciverUser["UF_COUNT_APP"];
	  if($appTmp["FROM"]["APP_COUNT"] == ''){
		  $appTmp["FROM"]["APP_COUNT"] = 0;
	  }		  
	  $appTmp["FROM"]["EMAIL"] = $reciverUser["EMAIL"];
	  $appTmp["FROM"]["APP"] = $reciverUser["UF_SHEDULE_".$appTmp["TIME"]["ID"]];
	  $appTmp["FROM"]["WISH_IN"] = $reciverUser["UF_WISH_IN"];
	  $appTmp["FROM"]["WISH_OUT"] = $reciverUser["UF_WISH_OUT"];
	  
	  //ДЛЯ кого встреча
	  $appTmp["TO"]["ID"] = $arFields["DETAIL_TEXT"];
	  $rsUser = CUser::GetByID($arFields["DETAIL_TEXT"]);
	  $reciverUser = $rsUser->Fetch();
	  $appTmp["TO"]["NAME"] = $reciverUser["NAME"]." ".$reciverUser["LAST_NAME"];
	  $appTmp["TO"]["COMPANY"] = $reciverUser["WORK_COMPANY"];
	  $appTmp["TO"]["APP_COUNT"] = $reciverUser["UF_COUNT_APP"];
	  if($appTmp["TO"]["APP_COUNT"] == ''){
		  $appTmp["TO"]["APP_COUNT"] = 0;
	  }		  
	  $appTmp["TO"]["EMAIL"] = $reciverUser["EMAIL"];
	  $appTmp["TO"]["APP"] = $reciverUser["UF_SHEDULE_".$appTmp["TIME"]["ID"]];
	  $appTmp["TO"]["WISH_IN"] = $reciverUser["UF_WISH_IN"];
	  $appTmp["TO"]["WISH_OUT"] = $reciverUser["UF_WISH_OUT"];
	  if($thisUser != $appTmp["FROM"]["ID"] && $thisUser != $appTmp["TO"]["ID"] && !$USER->IsAdmin()){
		  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_EDIT_ERROR");
	  }
	  if($arParams["APP_ELEMENT"] != $appTmp["FROM"]["APP"] || $arParams["APP_ELEMENT"] != $appTmp["TO"]["APP"]){
		  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_CHANGE_ERROR");
	  }


	  /*if($arParams["APP_ACTION"] == 'accept' && !$USER->IsAdmin() && $thisUser != $appTmp["TO"]["ID"]){
		  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_ACCEPT_ERROR");
	  }*/
	  if($arResult["ERROR_MESSAGE"] == '' && $arParams["APP_ACTION"] == 'accept'){
		  if($appTmp["ACTIVE"] == 'N' && $appTmp["STATUS"]["ID"] != $arParams["APP_ACCEPT"]){
			  //Меняем активность у встречи и переносим ее в группу
			  $readMeet = new CIBlockElement;
			  $PROP = array();
			  $PROP[4] = array("VALUE" => $arParams["APP_ACCEPT"]);
			  $arLoadProductArray = Array(
				"ACTIVE"         => "Y",
				"IBLOCK_SECTION" => $arParams["GROUP_ACCEPT"],
				"MODIFIED_BY" => $thisUser
				);
			  CIBlockElement::SetPropertyValueCode($arParams["APP_ELEMENT"], "STATUS", $PROP);
			  $resM = $readMeet->Update($arParams["APP_ELEMENT"], $arLoadProductArray);
			  
			  // Меняем количество подтвержденных встреч
			  $appTmp["TO"]["APP_COUNT"]--;
			  if($appTmp["TO"]["APP_COUNT"] < 0){
				  $appTmp["TO"]["APP_COUNT"] = 0;
			  }
			  $toUser = new CUser;
			  $fields = Array(
				"UF_COUNT_APP"      => $appTmp["TO"]["APP_COUNT"]
				);
			  $toUser->Update($appTmp["TO"]["ID"], $fields);
			  $arResult["MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_ACCEPT");
		  }
		  else{
			  $arResult["MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_ACCEPT_DEJA");
		  }
	  }
	  elseif($arResult["ERROR_MESSAGE"] == '' && $arParams["APP_ACTION"] == 'decline'){
		  if($appTmp["STATUS"]["ID"] != $arParams["APP_DECLINE"]){
			  //Меняем активность у встречи и переносим ее в группу
			  $readMeet = new CIBlockElement;
			  $PROP = array();
			  $PROP[4] = array("VALUE" => $arParams["APP_DECLINE"]);
			  $arLoadProductArray = Array(
				"ACTIVE"         => "N",
				"IBLOCK_SECTION" => $arParams["GROUP_DECLINE"],
				"MODIFIED_BY" => $thisUser
				);
			  CIBlockElement::SetPropertyValueCode($arParams["APP_ELEMENT"], "STATUS", $PROP);
			  $resM = $readMeet->Update($arParams["APP_ELEMENT"], $arLoadProductArray);
			  
			  // Меняем количество подтвержденных встреч если встреча не подтверждена
			  $strError = '';
			  if($appTmp["ACTIVE"] != 'Y'){
				//Получатель
				$appTmp["TO"]["APP_COUNT"]--;
				if($appTmp["TO"]["APP_COUNT"] < 0){
					$appTmp["TO"]["APP_COUNT"] = 0;
				}
			  }
			  $toUser = new CUser;
			  if($appTmp["FROM"]["ID"] != $thisUser){
				  if(strpos($appTmp["TO"]["WISH_IN"], ", ".$appTmp["FROM"]["ID"]." ") === false){
					  $appTmp["TO"]["WISH_IN"] = $appTmp["TO"]["WISH_IN"].", ".$appTmp["FROM"]["ID"]." ";
				  }
				  if(strpos($appTmp["FROM"]["WISH_OUT"], ", ".$appTmp["TO"]["ID"]." ") === false){
					  $appTmp["FROM"]["WISH_OUT"] = $appTmp["FROM"]["WISH_OUT"].", ".$appTmp["TO"]["ID"]." ";
				  }
			  }
			  $fields = Array(
				"UF_COUNT_APP"      => $appTmp["TO"]["APP_COUNT"],
				"UF_SHEDULE_".$appTmp["TIME"]["ID"] => "",
				"UF_WISH_IN" => $appTmp["TO"]["WISH_IN"],
				);
			  $toUser->Update($appTmp["TO"]["ID"], $fields);
			  $strError .= $toUser->LAST_ERROR;
			  
			  if($appTmp["FROM"]["ID"] != $thisUser){
				  $arFieldsMes = array();
				  $arFieldsMes["EMAIL"] = $appTmp["FROM"]["EMAIL"];
				  $arFieldsMes["COMPANY"] = $appTmp["TO"]["COMPANY"];
				  $arFieldsMes["USER"] = $appTmp["TO"]["NAME"];
				  CEvent::Send("DECLINE_APPOINTMENT","s1",$arFieldsMes);
			  }
			  
			  //Отправитель
			  $fromUser = new CUser;
			  $fieldsFrom = Array(
				"UF_SHEDULE_".$appTmp["TIME"]["ID"] => "",
				"UF_WISH_OUT" => $appTmp["FROM"]["WISH_OUT"],
				);
			  $fromUser->Update($appTmp["FROM"]["ID"], $fieldsFrom);
			  $strError .= $fromUser->LAST_ERROR;
			  
			  if($strError){
				  $arResult["ERROR_MESSAGE"] = $strError;
			  }
			  
			  //Создаем почтовое событие и отправляем его.
			  
			  $arResult["MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_DECLINE");
		  }
		  else{
			  $arResult["MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_DECLINE_DEJA");
		  }
	  }
	}
	else{
		$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_EDIT_APP_EXIST_ERROR");
	}
}
$arResult["APPOINT"] = $appTmp;
//echo "<pre>"; print_r($appTmp); echo "</pre>";
$this->IncludeComponentTemplate();
?>