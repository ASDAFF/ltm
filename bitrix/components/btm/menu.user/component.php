<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//
if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}
if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}
if(strLen($arParams["ADMIN"])<=0){
	$arParams["ADMIN"] = "1";
}
if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/personal/login.php";
}
if(strLen($arParams["TYPE"])<=0){
	$arParams["TYPE"] = "PARTICIP";
}


$arResult["ERROR_MESSAGE"] = "";

global $USER;
if (!is_object($USER)) $USER = new CUser;

$thisUrl = $APPLICATION->GetCurPage();

if(!($USER->IsAuthorized()))
{
	if($thisUrl == $arParams["AUTH_PAGE"]){
		$arResult["ERROR_MESSAGE"] = "auth";
	}
	else{
		LocalRedirect($arParams["AUTH_PAGE"]);
	}
}
elseif($thisUrl == $arParams["AUTH_PAGE"]){
	$arResult["ERROR_MESSAGE"] = "auth";
}
else
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		if($arParams["TYPE"] == "PARTICIP"){
			$arResult["MENU"][0]["LINK"] = "morning/";
			$arResult["MENU"][0]["ACTIVE"] = "N";
			$arResult["MENU"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MORNING");
	
			$arResult["MENU"][0]["CHILDE"][0]["LINK"] = "my/";
			$arResult["MENU"][0]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][0]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MORNING_MY");
	
			$arResult["MENU"][0]["CHILDE"][1]["LINK"] = "guests/";
			$arResult["MENU"][0]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][0]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MORNING_GUESTS");

			$arResult["MENU"][1]["LINK"] = "hb/";
			$arResult["MENU"][1]["ACTIVE"] = "N";
			$arResult["MENU"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_HB");
	
			$arResult["MENU"][1]["CHILDE"][0]["LINK"] = "my/";
			$arResult["MENU"][1]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][1]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_HB_MY");
	
			$arResult["MENU"][1]["CHILDE"][1]["LINK"] = "list/";
			$arResult["MENU"][1]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][1]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_HB_LIST");

			$arResult["MENU"][2]["LINK"] = "evening/";
			$arResult["MENU"][2]["ACTIVE"] = "N";
			$arResult["MENU"][2]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_EVENING");
	
			$arResult["MENU"][2]["CHILDE"][0]["LINK"] = "list/";
			$arResult["MENU"][2]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][2]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_EVENING_LIST");

			$arResult["MENU"][3]["LINK"] = "info/";
			$arResult["MENU"][3]["ACTIVE"] = "N";
			$arResult["MENU"][3]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_INFO");
	
			$arResult["MENU"][3]["CHILDE"][0]["LINK"] = "my/";
			$arResult["MENU"][3]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][3]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_INFO_MY");
	
			$arResult["MENU"][3]["CHILDE"][1]["LINK"] = "deadlines/";
			$arResult["MENU"][3]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][3]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_INFO_DEADLINES");

			$arResult["MENU"][4]["LINK"] = "messages/";
			$arResult["MENU"][4]["ACTIVE"] = "N";
			$arResult["MENU"][4]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES");
	
			$arResult["MENU"][4]["CHILDE"][0]["LINK"] = "received/";
			$arResult["MENU"][4]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][4]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_RECEIVED");
	
			$arResult["MENU"][4]["CHILDE"][1]["LINK"] = "sent/";
			$arResult["MENU"][4]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][4]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_SENT");
	
			$arResult["MENU"][4]["CHILDE"][2]["LINK"] = "morning/guests/";
			$arResult["MENU"][4]["CHILDE"][2]["ACTIVE"] = "N";
			$arResult["MENU"][4]["CHILDE"][2]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_WRITE");
			$arResult["MENU"][4]["CHILDE"][2]["REDIR"] = "Y";
	
			$arResult["MENU"][4]["CHILDE"][3]["LINK"] = "admin/";
			$arResult["MENU"][4]["CHILDE"][3]["ACTIVE"] = "N";
			$arResult["MENU"][4]["CHILDE"][3]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_ADMIN");
			
			$arResult["MENU"][5]["LINK"] = "next_reg/";
			$arResult["MENU"][5]["ACTIVE"] = "N";
			$arResult["MENU"][5]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_NEXT_REGIST");
	
			$arResult["MENU"][5]["CHILDE"][0]["LINK"] = "reg/";
			$arResult["MENU"][5]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][5]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_NEXT_REGIST");
		}
		else{
			$arResult["MENU"][0]["LINK"] = "morning/";
			$arResult["MENU"][0]["ACTIVE"] = "N";
			$arResult["MENU"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MORNING");
	
			$arResult["MENU"][0]["CHILDE"][0]["LINK"] = "my/";
			$arResult["MENU"][0]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][0]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MORNING_MY");
	
			$arResult["MENU"][0]["CHILDE"][1]["LINK"] = "list/";
			$arResult["MENU"][0]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][0]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MORNING_LIST");

			$arResult["MENU"][1]["LINK"] = "info/";
			$arResult["MENU"][1]["ACTIVE"] = "N";
			$arResult["MENU"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_INFO");
	
			$arResult["MENU"][1]["CHILDE"][0]["LINK"] = "my/";
			$arResult["MENU"][1]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][1]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_INFO_MY");
	
			$arResult["MENU"][1]["CHILDE"][1]["LINK"] = "deadlines/";
			$arResult["MENU"][1]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][1]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_INFO_DEADLINES");

			$arResult["MENU"][2]["LINK"] = "messages/";
			$arResult["MENU"][2]["ACTIVE"] = "N";
			$arResult["MENU"][2]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES");
	
			$arResult["MENU"][2]["CHILDE"][0]["LINK"] = "received/";
			$arResult["MENU"][2]["CHILDE"][0]["ACTIVE"] = "N";
			$arResult["MENU"][2]["CHILDE"][0]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_RECEIVED");
	
			$arResult["MENU"][2]["CHILDE"][1]["LINK"] = "sent/";
			$arResult["MENU"][2]["CHILDE"][1]["ACTIVE"] = "N";
			$arResult["MENU"][2]["CHILDE"][1]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_SENT");
	
			$arResult["MENU"][2]["CHILDE"][2]["LINK"] = "morning/list/";
			$arResult["MENU"][2]["CHILDE"][2]["ACTIVE"] = "N";
			$arResult["MENU"][2]["CHILDE"][2]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_WRITE");
			$arResult["MENU"][2]["CHILDE"][2]["REDIR"] = "Y";
	
			$arResult["MENU"][2]["CHILDE"][3]["LINK"] = "admin/";
			$arResult["MENU"][2]["CHILDE"][3]["ACTIVE"] = "N";
			$arResult["MENU"][2]["CHILDE"][3]["NAME"] = GetMessage("PARTICIP_MENU_PAGE_MESSAGES_ADMIN");
		}

		$uri = $APPLICATION->GetCurPage();
		$countParent = 0;
		$isAct = 'N';
		foreach($arResult["MENU"] as $parent){
			if(strpos($uri, $arParams["PATH_TO_KAB"]) !== false && strpos($uri, $parent["LINK"]) !== false){
				$arResult["MENU"][$countParent]["ACTIVE"] = "Y";
			}
			$countChild = 0;
			$parent["LINK"] = $arParams["PATH_TO_KAB"].$parent["LINK"];
			$arResult["MENU"][$countParent]["LINK"] = $parent["LINK"];
			foreach($parent["CHILDE"] as $child){
				if(strpos($uri, $child["LINK"]) !== false && strpos($uri, $parent["LINK"]) !== false){
					$arResult["MENU"][$countParent]["CHILDE"][$countChild]["ACTIVE"] = "Y";
					$isAct = 'Y';
				}
				if(isset($child["REDIR"]) && $child["REDIR"] == 'Y'){
					$arResult["MENU"][$countParent]["CHILDE"][$countChild]["LINK"] = $arParams["PATH_TO_KAB"].$child["LINK"];
				}
				else{
					$arResult["MENU"][$countParent]["CHILDE"][$countChild]["LINK"] = $parent["LINK"].$child["LINK"];
				}
				$countChild++;
			}
			$countParent++;
		}
		$arResult["ISACTIVE"] = $isAct;
		if($isAct == 'N'){
			$arResult["MENU"][0]["CHILDE"][0]["ACTIVE"] = "Y";
			$arResult["MENU"][0]["ACTIVE"] = "Y";
		}
	}
	else{
		$arResult["ERROR_MESSAGE"] = "Isn't admin";
	}
}

//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>
