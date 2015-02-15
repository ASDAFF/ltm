<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//ƒобавить параметр от кого
//ƒобавить параметр группу дл€ встреч

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/personal/";
}

if(strLen($arParams["GROUP_SENDER_ID"])<=0){
	$arParams["GROUP_SENDER_ID"] = "4";
}

if(strLen($arParams["GROUP_RECIVER_ID"])<=0){
	$arParams["GROUP_RECIVER_ID"] = "6";
}

if(strLen($arParams["ADMIN_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["USER"])<=0 || $arParams["USER"]==0){
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_USER_ERROR");
}

if(strLen($arParams["APP_ID"])<=0){
	$arParams["APP_ID"] = "3";
}

if(strLen($arParams["APP_TYPE"])<=0){
	$arParams["APP_TYPE"] = "1";
}

if(strLen($arParams["TIME"])<=0){
	$arParams["TIME"] = "0";
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_AUTH_ERROR");
}

$arResult["IS_ACTIVE"] = $arParams["IS_ACTIVE"];
if(strLen($arParams["IS_ACTIVE"])<=0 || $arParams["IS_ACTIVE"] == 'N'){
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_IS_BLOCKED");
}

CModule::IncludeModule('iblock');

$times = array(
	  '10:00 Ц 10:10', '10:15 Ц 10:25',
	  '10:30 Ц 10:40', '10:45 Ц 10:55',
	  '11:00 Ц 11:10', '11:15 Ц 11:25',
	  '11:30 Ц 11:40', '11:45 Ц 11:55',
	  '12:10 Ц 12:20', '12:25 Ц 12:35',
	  '12:40 Ц 12:50', '12:55 Ц 13:05',
	  '13:10 Ц 13:20', '13:25 Ц 13:35',
	  '13:40 Ц 13:50', '13:55 Ц 14:05',
	  '14:10 Ц 14:20'
  );
if(isset($_REQUEST["to"]) && ($_REQUEST["to"] == 1 || $_REQUEST["to"] == 0)){
	$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_NO_USER");
}

/*---------------------------------------------------*/
//           ‘ќ–ћ»–”≈ћ ¬џ¬ќƒ ƒЋя ЎјЅЋќЌј             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$senderId = $USER->GetID();
	$userGroups = CUser::GetUserGroup($senderId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_SENDER_ID"], $userGroups)){
		$arResult["TYPE"] = "FORM";
		if((isset($_POST['form'])) and ($_POST['form'] == 'send')){
    		$arResult["TYPE"] = "SENT";
		}
		if($arParams["USER_TYPE"] == 'ADMIN'){
			$senderId = $arParams["USER"];
			$arParams["USER"] = $_REQUEST["to"];
		}
		if(!in_array($arParams["GROUP_RECIVER_ID"], $userGroups)){
		  $rsUser = CUser::GetByID($arParams["USER"]);
		  $reciverUser = $rsUser->Fetch();
		  $rsUser = CUser::GetByID($senderId);
		  $senderUser = $rsUser->Fetch();
		  $arResult["LINK"] = $APPLICATION->GetCurPage();
		  $arResult["SENDER"]["ID"] = $senderId;
		  $arResult["SENDER"]["NAME"] = $senderUser["NAME"]." ".$senderUser["LAST_NAME"];
		  $arResult["SENDER"]["COMPANY"] = $senderUser["WORK_COMPANY"];
		  $arResult["RECIVER"]["ID"] = $arParams["USER"];
		  $arResult["RECIVER"]["NAME"] = $reciverUser["NAME"]." ".$reciverUser["LAST_NAME"];
		  $arResult["RECIVER"]["COMPANY"] = $reciverUser["WORK_COMPANY"];
		  $arResult["RECIVER"]["APP_COUNT"] = $reciverUser["UF_COUNT_APP"];
		  if($arResult["RECIVER"]["APP_COUNT"] == ''){
			  $arResult["RECIVER"]["APP_COUNT"] = 0;
		  }		  
		  $arResult["RECIVER"]["EMAIL"] = $reciverUser["EMAIL"];
		  
		  $arResult["TIME"]["ID"] = $arParams["TIME"];

		  $arResult["TIME"]["TITLE"] = $times[$arParams["TIME"]];
		  $fieldName = $arParams["TIME"]+1;
		  $fieldName ="UF_SHEDULE_".$fieldName;
		  if($reciverUser[$fieldName] != ''){
			  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_TIME_RECIVER_BUSY");
		  }
		  if($senderUser[$fieldName] != ''){
			  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_TIME_SENDER_BUSY");
		  }
		  if($arResult["TYPE"] == "SENT" && $arResult["ERROR_MESSAGE"] == ''){
			$message = new CIBlockElement;
			$PROP = array();
			$PROP[1] = $arResult["SENDER"]["ID"];
			$PROP[2] = $arResult["RECIVER"]["ID"];
			$PROP[3] = array("VALUE" => $arResult["TIME"]["ID"]+1);
			if($PROP[3]["VALUE"] > 12){
				$PROP[3]["VALUE"] += 4;
			}
			$PROP[4] = array("VALUE" => $arParams["APP_TYPE"]);
			if($arParams["USER_TYPE"] == 'ADMIN'){
				$arLoadProductArray = Array(
				  "MODIFIED_BY"    => $arResult["SENDER"]["ID"], // элемент изменен текущим пользователем
				  "CREATED_BY"	   => $arResult["SENDER"]["ID"],
				  "IBLOCK_SECTION_ID" => 7,          // элемент лежит в корне раздела
				  "IBLOCK_ID"      => $arParams["APP_ID"],
				  "PROPERTY_VALUES"=> $PROP,
				  "NAME"           => $arResult["TIME"]["TITLE"]." From ".$arResult["SENDER"]["ID"]." To ".$arResult["RECIVER"]["ID"],
				  "ACTIVE"         => "Y",            // активен
				  "PREVIEW_TEXT"   => $arResult["TIME"]["TITLE"],
				  "DETAIL_TEXT"    => $arResult["RECIVER"]["ID"]
				  );
			}
			else{
				$arLoadProductArray = Array(
				  "MODIFIED_BY"    => $arResult["SENDER"]["ID"], // элемент изменен текущим пользователем
				  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
				  "IBLOCK_ID"      => $arParams["APP_ID"],
				  "PROPERTY_VALUES"=> $PROP,
				  "NAME"           => $arResult["TIME"]["TITLE"]." From ".$arResult["SENDER"]["ID"]." To ".$arResult["RECIVER"]["ID"],
				  "ACTIVE"         => "N",            // активен
				  "PREVIEW_TEXT"   => $arResult["TIME"]["TITLE"],
				  "DETAIL_TEXT"    => $arResult["RECIVER"]["ID"]
				  );
			}
			  if($PRODUCT_ID = $message->Add($arLoadProductArray)){
				  $arFields[$fieldName] = $PRODUCT_ID;
				  $user = new CUser;
				  $user->Update($arResult["SENDER"]["ID"], $arFields);
				  $strError = '';
				  $strError .= $user->LAST_ERROR;

				  if($arParams["USER_TYPE"] != 'ADMIN'){
					  $arFields['UF_COUNT_APP'] = $arResult["RECIVER"]["APP_COUNT"]+1;
				  }
				  $userTo = new CUser;
				  $userTo->Update($arResult["RECIVER"]["ID"], $arFields);
				  $strError .= $userTO->LAST_ERROR;
				  
				  if($strError == '' && $arParams["USER_TYPE"] != 'ADMIN'){
					$arFieldsMes = array(
						"EMAIL" => $arResult["RECIVER"]["EMAIL"]
					);
					CEvent::Send("APPOINTMENT_REQUEST","s1",$arFieldsMes);
				  }
				  elseif($strError != ''){
        			  $arResult["ERROR_MESSAGE"] = $strError;
					  print_r($strError);
				  }
			  }
			  else{
     			  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_SEND_ERROR");
			  }
		  }
		}
		else{
		  $arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_GROUP_ERROR");
		}
	}
	else{
		$arResult["ERROR_MESSAGE"] = GetMessage("APPOINTMENT_PERMISSION_ERROR");
	}
}
//echo "<pre>"; print_r($arParams); echo "</pre>";
$this->IncludeComponentTemplate();
?>