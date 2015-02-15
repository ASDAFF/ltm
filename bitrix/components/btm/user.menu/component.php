<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить формы гостей и участников


$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["GUEST_URL"])<=0){
	$arParams["GUEST_URL"] = "/ru/particip/";
}

if(strLen($arParams["PARTICIP_URL"])<=0){
	$arParams["PARTICIP_URL"] = "/particip/";
}

if(strLen($arParams["ADMIN_URL"])<=0){
	$arParams["ADMIN_URL"] = "/admin/";
}

if(strLen($arParams["GUEST_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе гостя!<br />";
}

if(strLen($arParams["PARTICIP_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе участника!<br />";
}

if(strLen($arParams["ADMIN_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе администратора!<br />";
}

if(!($USER->IsAuthorized()))
{
	if($arParams["USER_TYPE"] == "PARTICIP"){
		LocalRedirect($arParams["PARTICIP_URL"]."login.php");
	}	
	elseif($arParams["USER_TYPE"] == "GUEST"){
		LocalRedirect($arParams["GUEST_URL"]."login.php");
	}	
	else{
		LocalRedirect($arParams["ADMIN_URL"]."login.php");
	}	
}
if($arResult["ERROR_MESSAGE"] == '')
{
	$userId = $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	
	if(in_array($arParams["PARTICIP_GROUP"], $userGroups)){
		$arResult["USER"]["TYPE"] = 'PARTICIP';
		$arResult["USER"]["LINK"] = $arParams["PARTICIP_URL"];

		$arResult["MENU"][0]["LINK"] = "info/";
		$arResult["MENU"][0]["ACTIVE"] = "N";
		$arResult["MENU"][0]["NAME"] = "My registration info";

		$arResult["MENU"][1]["LINK"] = "messages/";
		$arResult["MENU"][1]["ACTIVE"] = "N";
		$arResult["MENU"][1]["NAME"] = "My messages";

		$arResult["MENU"][2]["LINK"] = "shedule/";
		$arResult["MENU"][2]["ACTIVE"] = "N";
		$arResult["MENU"][2]["NAME"] = "My schedule";

		$arResult["MENU"][3]["LINK"] = "guest/";
		$arResult["MENU"][3]["ACTIVE"] = "N";
		$arResult["MENU"][3]["NAME"] = "Guest List";

		$arResult["MENU"][4]["LINK"] = "buyers/";
		$arResult["MENU"][4]["ACTIVE"] = "N";
		$arResult["MENU"][4]["NAME"] = "Hosted buyers";

		$arResult["MENU"][5]["LINK"] = "event/";
		$arResult["MENU"][5]["ACTIVE"] = "N";
		$arResult["MENU"][5]["NAME"] = "Event’s deadlines and info";

		$arResult["MENU"][6]["LINK"] = "contact/";
		$arResult["MENU"][6]["ACTIVE"] = "N";
		$arResult["MENU"][6]["NAME"] = "Contact the administrator";

	}
	elseif(in_array($arParams["GUEST_GROUP"], $userGroups)){
		$arResult["USER"]["TYPE"] = 'GUEST';
		$arResult["USER"]["LINK"] = $arParams["GUEST_URL"];

		$arResult["MENU"][0]["LINK"] = "info/";
		$arResult["MENU"][0]["ACTIVE"] = "N";
		$arResult["MENU"][0]["NAME"] = "Мои регистрационные  данные";

		$arResult["MENU"][1]["LINK"] = "messages/";
		$arResult["MENU"][1]["ACTIVE"] = "N";
		$arResult["MENU"][1]["NAME"] = "Мои сообщения";

		$arResult["MENU"][2]["LINK"] = "shedule/";
		$arResult["MENU"][2]["ACTIVE"] = "N";
		$arResult["MENU"][2]["NAME"] = "Моё расписание встреч";

		$arResult["MENU"][3]["LINK"] = "particip/";
		$arResult["MENU"][3]["ACTIVE"] = "N";
		$arResult["MENU"][3]["NAME"] = "Список участников";

		$arResult["MENU"][4]["LINK"] = "contact/";
		$arResult["MENU"][4]["ACTIVE"] = "N";
		$arResult["MENU"][4]["NAME"] = "Связаться с администратором";
	}
	else{
		$arResult["USER"]["TYPE"] = 'PARTICIP';
		$arResult["USER"]["LINK"] = $arParams["PARTICIP_URL"];

		$arResult["MENU"][0]["LINK"] = "info/";
		$arResult["MENU"][0]["ACTIVE"] = "N";
		$arResult["MENU"][0]["NAME"] = "My registration info";

		$arResult["MENU"][1]["LINK"] = "messages/";
		$arResult["MENU"][1]["ACTIVE"] = "N";
		$arResult["MENU"][1]["NAME"] = "My messages";

		$arResult["MENU"][2]["LINK"] = "shedule/";
		$arResult["MENU"][2]["ACTIVE"] = "N";
		$arResult["MENU"][2]["NAME"] = "My schedule";

		$arResult["MENU"][3]["LINK"] = "guest/";
		$arResult["MENU"][3]["ACTIVE"] = "N";
		$arResult["MENU"][3]["NAME"] = "Guest List";

		$arResult["MENU"][4]["LINK"] = "buyers/";
		$arResult["MENU"][4]["ACTIVE"] = "N";
		$arResult["MENU"][4]["NAME"] = "Hosted buyers";

		$arResult["MENU"][5]["LINK"] = "event/";
		$arResult["MENU"][5]["ACTIVE"] = "N";
		$arResult["MENU"][5]["NAME"] = "Event’s deadlines and info";

		$arResult["MENU"][6]["LINK"] = "contact/";
		$arResult["MENU"][6]["ACTIVE"] = "N";
		$arResult["MENU"][6]["NAME"] = "Contact the administrator";
		
	}
	$uri = $APPLICATION->GetCurPage();
	$countParent = 0;
	$thisLink = '';
	$isAct = 'N';
	foreach($arResult["MENU"] as $parent){
		$thisLink = $arResult["USER"]["LINK"].$parent["LINK"];
		if(strpos($uri, $thisLink ) !== false){
			$arResult["MENU"][$countParent]["ACTIVE"] = "Y";
			$isAct = 'Y';
		}
		$arResult["MENU"][$countParent]["LINK"] = $thisLink;
		$countParent++;
	}
	$arResult["ISACTIVE"] = $isAct;
	if($isAct == 'N'){
		$arResult["MENU"][2]["ACTIVE"] = 'Y';
	}
}

$this->IncludeComponentTemplate();
?>