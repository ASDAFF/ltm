<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//
if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}
if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}
if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}
$arResult["ERROR_MESSAGE"] = "";

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

		$arResult["MENU"][0]["LINK"] = "guest/";
		$arResult["MENU"][0]["ACTIVE"] = "N";
		$arResult["MENU"][0]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST");

		$arResult["MENU"][0]["CHILDE"][0]["LINK"] = "on/";
		$arResult["MENU"][0]["CHILDE"][0]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][0]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_ACCEPT");

		$arResult["MENU"][0]["CHILDE"][1]["LINK"] = "evening/";
		$arResult["MENU"][0]["CHILDE"][1]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][1]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_EVENING");

		$arResult["MENU"][0]["CHILDE"][2]["LINK"] = "hostbuy/";
		$arResult["MENU"][0]["CHILDE"][2]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][2]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_HOSTED_BUYERS");

		$arResult["MENU"][0]["CHILDE"][3]["LINK"] = "off/";
		$arResult["MENU"][0]["CHILDE"][3]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][3]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_DECLINE");

		$arResult["MENU"][0]["CHILDE"][4]["LINK"] = "archiv/";
		$arResult["MENU"][0]["CHILDE"][4]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][4]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_ARCHIV");

		$arResult["MENU"][0]["CHILDE"][5]["LINK"] = "meet/";
		$arResult["MENU"][0]["CHILDE"][5]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][5]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_MEETING");
		
		$arResult["MENU"][0]["CHILDE"][6]["LINK"] = "spam/";
		$arResult["MENU"][0]["CHILDE"][6]["ACTIVE"] = "N";
		$arResult["MENU"][0]["CHILDE"][6]["NAME"] = GetMessage("ADMIN_MENU_PAGE_GUEST_SPAM");

		$arResult["MENU"][1]["LINK"] = "particip/";
		$arResult["MENU"][1]["ACTIVE"] = "N";
		$arResult["MENU"][1]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP");

		$arResult["MENU"][1]["CHILDE"][0]["LINK"] = "on/";
		$arResult["MENU"][1]["CHILDE"][0]["ACTIVE"] = "N";
		$arResult["MENU"][1]["CHILDE"][0]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP_ACCEPT");

		$arResult["MENU"][1]["CHILDE"][1]["LINK"] = "off/";
		$arResult["MENU"][1]["CHILDE"][1]["ACTIVE"] = "N";
		$arResult["MENU"][1]["CHILDE"][1]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP_DECLINE");

		$arResult["MENU"][1]["CHILDE"][2]["LINK"] = "archiv/";
		$arResult["MENU"][1]["CHILDE"][2]["ACTIVE"] = "N";
		$arResult["MENU"][1]["CHILDE"][2]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP_ARCHIV");

		$arResult["MENU"][1]["CHILDE"][3]["LINK"] = "meet/";
		$arResult["MENU"][1]["CHILDE"][3]["ACTIVE"] = "N";
		$arResult["MENU"][1]["CHILDE"][3]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP_MEETING");

		$arResult["MENU"][1]["CHILDE"][4]["LINK"] = "spam/";
		$arResult["MENU"][1]["CHILDE"][4]["ACTIVE"] = "N";
		$arResult["MENU"][1]["CHILDE"][4]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP_SPAM");

		$arResult["MENU"][1]["CHILDE"][5]["LINK"] = "next/";
		$arResult["MENU"][1]["CHILDE"][5]["ACTIVE"] = "N";
		$arResult["MENU"][1]["CHILDE"][5]["NAME"] = GetMessage("ADMIN_MENU_PAGE_PARTICIP_NEXT");

		$arResult["MENU"][2]["LINK"] = "message/";
		$arResult["MENU"][2]["ACTIVE"] = "N";
		$arResult["MENU"][2]["NAME"] = GetMessage("ADMIN_MENU_PAGE_MESSAGE");

		$arResult["MENU"][2]["CHILDE"][0]["LINK"] = "in/";
		$arResult["MENU"][2]["CHILDE"][0]["ACTIVE"] = "N";
		$arResult["MENU"][2]["CHILDE"][0]["NAME"] = GetMessage("ADMIN_MENU_PAGE_MESSAGE_INPUT");

		$arResult["MENU"][2]["CHILDE"][1]["LINK"] = "out/";
		$arResult["MENU"][2]["CHILDE"][1]["ACTIVE"] = "N";
		$arResult["MENU"][2]["CHILDE"][1]["NAME"] = GetMessage("ADMIN_MENU_PAGE_MESSAGE_OUTPUT");

		$arResult["MENU"][2]["CHILDE"][2]["LINK"] = "write/";
		$arResult["MENU"][2]["CHILDE"][2]["ACTIVE"] = "N";
		$arResult["MENU"][2]["CHILDE"][2]["NAME"] = GetMessage("ADMIN_MENU_PAGE_MESSAGE_WRITE");

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
				$arResult["MENU"][$countParent]["CHILDE"][$countChild]["LINK"] = $parent["LINK"].$child["LINK"];
				$countChild++;
			}
			$countParent++;
		}
		$arResult["ISACTIVE"] = $isAct;
	}
	else{
		$arResult["ERROR_MESSAGE"] = "Isn't admin";
	}
}

//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>
