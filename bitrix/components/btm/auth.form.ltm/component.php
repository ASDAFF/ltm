<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//

if(strLen($arParams["GUEST_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = GetMessage("AUTH_FORM_MODULE_GUEST_ERROR");
}

if(strLen($arParams["GUEST_URL"])<=0){
	$arResult["ERROR_MESSAGE"] = GetMessage("AUTH_FORM_MODULE_GUEST_ERROR");
}

if(strLen($arParams["PARTICIP_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = GetMessage("AUTH_FORM_MODULE_PARTICIP_ERROR");
}

if(strLen($arParams["PARTICIP_URL"])<=0){
	$arResult["ERROR_MESSAGE"] = GetMessage("AUTH_FORM_MODULE_PARTICIP_ERROR");
}

if(strLen($arParams["SHOW_ERRORS"])<=0){
	$arParams["SHOW_ERRORS"] = "N";
}

if(strLen($arParams["IS_REDIRECT"])<=0){
	$arParams["IS_REDIRECT"] = "N";
}
$arResult["FORM_TYPE"] = "login";

global $USER;
if (!is_object($USER)) $USER = new CUser;

if($USER->IsAuthorized())
{
	if(isset($_REQUEST["action"]) && $_REQUEST["action"] == 'logout'){
		$UID = $USER->GetID();
		$arGroups = CUser::GetUserGroup($UID);
		$logoutLink = '';
		if($arParams["IS_REDIRECT"] == "Y"){
		  if(in_array($arParams["GUEST_ID"], $arGroups)){
			  $logoutLink = "/ru/";
		  }
		  elseif(in_array($arParams["PARTICIP_ID"], $arGroups)){
			  $logoutLink = "/eng.php";
		  }
		  elseif(in_array(1, $arGroups)){
			  $logoutLink = "/eng.php";
		  }
		}
		$USER->Logout();
		if($logoutLink){
			LocalRedirect($logoutLink);
		}
	    $arResult["ERROR_MESSAGE"] = GetMessage("AUTH_FORM_LOGOUT_SUCCESS");
	}
	else{
		if(isset($arParams["IS_SHOW"]) && $arParams["IS_SHOW"] == 'N'){
			$UID = $USER->GetID();
			$arGroups = CUser::GetUserGroup($UID);
			if($arParams["IS_REDIRECT"] == "Y"){
			  if(in_array($arParams["GUEST_ID"], $arGroups)){
				LocalRedirect($arParams["GUEST_URL"]);
			  }
			  elseif(in_array($arParams["PARTICIP_ID"], $arGroups)){
				LocalRedirect($arParams["PARTICIP_URL"]);
			  }
			  elseif(in_array(1, $arGroups)){
				LocalRedirect("/admin/");
			  }
			}
		}
	  $arResult["ERROR_MESSAGE"] = GetMessage("IS_BLOCKED");
          $arResult["FORM_TYPE"] = "is_block";
	  if(isset($arParams["IS_BLOCKED"]) && $arParams["IS_BLOCKED"] == 'N'){
		  $arResult["ERROR_MESSAGE"] = '';
                 $arResult["FORM_TYPE"] = "is_auth";
	  }	  
	}
}
elseif($arResult["ERROR_MESSAGE"] == '')
{
	if (isset($_REQUEST["LOGIN"])){
	  $arAuthResult = $USER->Login($_REQUEST["LOGIN"], $_REQUEST["PASSWORD"], "Y");
	  if($USER->IsAuthorized()){
		$UID = $USER->GetID();
		$arGroups = CUser::GetUserGroup($UID);
		if($arParams["IS_REDIRECT"] == "Y"){
		  if(in_array($arParams["GUEST_ID"], $arGroups)){
			LocalRedirect($arParams["GUEST_URL"]);
		  }
		  elseif(in_array($arParams["PARTICIP_ID"], $arGroups)){
			LocalRedirect($arParams["PARTICIP_URL"]);
		  }
		  elseif(in_array(1, $arGroups)){
			LocalRedirect("/admin/");
		  }
		}
              $arResult["ERROR_MESSAGE"] = GetMessage("IS_BLOCKED");
              $arResult["FORM_TYPE"] = "is_block";
              if(isset($arParams["IS_BLOCKED"]) && $arParams["IS_BLOCKED"] == 'N'){
                      $arResult["ERROR_MESSAGE"] = '';
                     $arResult["FORM_TYPE"] = "is_auth";
              }
	  }
	  else{
		  $arResult["ERROR_MESSAGE"] = GetMessage("AUTH_FORM_DATA_ERROR");
	  }
	}
}
//echo "<pre>"; print_r($arGroups); echo "</pre>";
$this->IncludeComponentTemplate();
?>
