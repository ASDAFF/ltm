<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/

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

if(strLen($arParams["USER"])<=0 || $arParams["USER"] == 0){
	$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_WTF_TO_RECEIVE_THIS');
}

if(strLen($arParams["WISH_TYPE"])<=0 || $arParams["WISH_TYPE"]==''){
	$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_WTF_IS_THIS_ACTION');
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_NOT_LOGGED_IN');
}

if(strLen($arParams["IS_ACTIVE"])<=0 || $arParams["IS_ACTIVE"] == 'N'){
	$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_MEETINGS_ARRANGMENT_LOCKED_BY_ADMIN');
}

CModule::IncludeModule('iblock');
/*---------------------------------------------------*/
//           ÔÎÐÌÈÐÓÅÌ ÂÛÂÎÄ ÄËß ØÀÁËÎÍÀ             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$userGroups = CUser::GetUserGroup($USER->GetID());
	if($USER->IsAdmin() || in_array($arParams["GROUP_SENDER_ID"], $userGroups)){
		$arResult["TYPE"] = "FORM";
		$rsUser = CUser::GetByID($arParams["USER"]);
		$reciverUser = $rsUser->Fetch();
		$rsUser = CUser::GetByID($USER->GetID());
		$senderUser = $rsUser->Fetch();
		$arResult["LINK"] = $APPLICATION->GetCurPage();
		$arResult["TITLE"] = GetMessage('WISH_LIST_SEND_REQUEST_TO_WISH_LIST');
		$arResult["WISH_TYPE"] = $arParams["WISH_TYPE"];
		$arResult["SENDER"]["ID"] = $USER->GetID();
		$arResult["SENDER"]["NAME"] = $senderUser["NAME"]." ".$senderUser["LAST_NAME"];
		$arResult["SENDER"]["COMPANY"] = $senderUser["WORK_COMPANY"];
		$arResult["SENDER"]["WISH_IN"] = $senderUser["UF_WISH_IN"];
		$arResult["SENDER"]["WISH_OUT"] = $senderUser["UF_WISH_OUT"];
		$arResult["RECIVER"]["ID"] = $arParams["USER"];
		$arResult["RECIVER"]["NAME"] = $reciverUser["NAME"]." ".$reciverUser["LAST_NAME"];
		$arResult["RECIVER"]["COMPANY"] = $reciverUser["WORK_COMPANY"];
		$arResult["RECIVER"]["EMAIL"] = $reciverUser["EMAIL"];
		$arResult["RECIVER"]["WISH_IN"] = $reciverUser["UF_WISH_IN"];
		$arResult["RECIVER"]["WISH_OUT"] = $reciverUser["UF_WISH_OUT"];
		
		if((isset($_POST['form'])) and ($_POST['form'] == 'send')){
    		$arResult["TYPE"] = "SENT";
		}
		if(strpos($arResult["SENDER"]["WISH_OUT"], ", ".$arResult["RECIVER"]["ID"]." ") !== false){
			$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_USER_ALREADY_IN_WISH_LIST');
		}
		if(strpos($arResult["RECIVER"]["WISH_IN"], ", ".$arResult["SENDER"]["ID"]." ") !== false){
			$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_ALREADY_IN_USERS_WISH_LIST');
		}
		if($arResult["TYPE"] == "SENT" && $arResult["ERROR_MESSAGE"] == '' && $arParams["WISH_TYPE"] == 'welcom'){
			$arFields["UF_WISH_OUT"] = $arResult["SENDER"]["WISH_OUT"].", ".$arResult["RECIVER"]["ID"]." ";
			$user = new CUser;
			$user->Update($arResult["SENDER"]["ID"], $arFields);
			$strError = '';
			$strError .= $user->LAST_ERROR;
	
			$arFieldsRec["UF_WISH_IN"] = $arResult["RECIVER"]["WISH_IN"].", ".$arResult["SENDER"]["ID"]." ";
			$user->Update($arResult["RECIVER"]["ID"], $arFieldsRec);
			$strError .= $user->LAST_ERROR;
			
			$arResult["MESSAGE"] = GetMessage('WISH_LIST_REQUEST_SEND_SUCCESSFULLY');
		}
	}
	else{
		$arResult["ERROR_MESSAGE"] = GetMessage('WISH_LIST_NO_PERMISSION_TO_VIEW_THIS_PAGE');
	}
}
//echo "<pre>"; print_r($arResult); echo "</pre>";
$this->IncludeComponentTemplate();
?>