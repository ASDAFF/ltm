<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//

if(strLen($arParams["GUEST_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по гостям.";
}

if(strLen($arParams["PARTICIP_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по участникам.";
}

$arResult["FORM_TYPE"] = "FORM";

if($arResult["ERROR_MESSAGE"] == '')
{
	if (isset($_REQUEST["email"])){
	  $rsUser = CUser::GetList(($by="id"), ($order="asc"), array("EMAIL" => $_REQUEST["email"]));
	  $arUser = $rsUser->Fetch();
	  if (!$arUser["ID"]){
		$arResult["ERROR_MESSAGE"] = GetMessage("PASS_WRONG_EMAIL");		  
	  }
	  else{
		$arGroups = CUser::GetUserGroup($arUser["ID"]);
		if(in_array($arParams["GUEST_ID"], $arGroups) || in_array($arParams["PARTICIP_ID"], $arGroups)){
		  $arResult["FORM_TYPE"] = "is_auth";
		  $arResult["MESSAGE"] = GetMessage("PASS_OK");		  
			$arEventFields = array(
				"EMAIL"          =>$arUser["EMAIL"],
				"LOGIN"         => $arUser["LOGIN"],
				"PASS"         => $arUser["ADMIN_NOTES"]
				);
			if(in_array($arParams["GUEST_ID"], $arGroups)){
				CEvent::Send("FORGET_PASSWORD_RU", "s1", $arEventFields);
			}
			else{
				CEvent::Send("FORGET_PASSWORD_EN", "s1", $arEventFields);
			}
		}
		else{
			$arResult["ERROR_MESSAGE"] = GetMessage("PASS_WRONG_USER");		  
		}
	  }
	}
	elseif (isset($_REQUEST["login"])){
		$rsUser = CUser::GetByLogin($_REQUEST["login"]);
		$arUser = $rsUser->Fetch();
	  if (!$arUser){
		$arResult["ERROR_MESSAGE"] = GetMessage("PASS_WRONG_LOGIN");	  
	  }
	  else{
		$arGroups = CUser::GetUserGroup($arUser["ID"]);
		if(in_array($arParams["GUEST_ID"], $arGroups) || in_array($arParams["PARTICIP_ID"], $arGroups)){
		  $arResult["FORM_TYPE"] = "is_auth";
		  $arResult["MESSAGE"] = GetMessage("PASS_OK");		  
			$arEventFields = array(
				"EMAIL"          =>$arUser["EMAIL"],
				"LOGIN"         => $arUser["LOGIN"],
				"PASS"         => $arUser["ADMIN_NOTES"]
				);
			if(in_array($arParams["GUEST_ID"], $arGroups)){
				CEvent::Send("FORGET_PASSWORD_RU", "s1", $arEventFields);
			}
			else{
				CEvent::Send("FORGET_PASSWORD_EN", "s1", $arEventFields);
			}
		}
		else{
			$arResult["ERROR_MESSAGE"] = GetMessage("PASS_WRONG_USER");		  
		}
	  }
	}
}
//echo "<pre>"; print_r($arResult); echo "</pre>";
$this->IncludeComponentTemplate();
?>
