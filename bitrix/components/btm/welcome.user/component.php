<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//
if(strLen($arParams["ADMIN"])<=0){
	$arParams["ADMIN"] = 1;
}
if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}
if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/personal/login.php";
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
else{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$rsUser = CUser::GetByID($userId);
		$arUser = $rsUser->Fetch();
		if (!CModule::IncludeModule("forum")){
			ShowError(GetMessage("F_NO_MODULE"));
		}
		$db_res = CForumPrivateMessage::GetListEx(array(), array("USER_ID" => $USER->GetID(), "IS_READ" => "N"));
		$arResult["NEW_MESSAGES"] = $db_res->SelectedRowsCount();
		$arResult["NAME"] = $arUser["NAME"]." ".$arUser["LAST_NAME"];
		$arResult["NEW_APP"] = $arUser["UF_COUNT_APP"];
		if($arResult["NEW_APP"] == ''){
			$arResult["NEW_APP"] = 0;
		}
	}
	else{
		LocalRedirect($arParams["AUTH_PAGE"]);
	}
}
//echo "<pre>"; print_r($arResult); echo "</pre>";
$this->IncludeComponentTemplate();
?>
