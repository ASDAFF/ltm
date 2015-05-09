<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2013 Bitrix
 */

/**
 * Bitrix vars
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponent $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

define("COMPANY_FORM_ID", 3);

$arParams["EXHIB_IBLOCK_ID"] = intval($arParams["EXHIB_IBLOCK_ID"]);//id инфоблока выставок
if(!$arParams["EXHIB_IBLOCK_ID"])
    $arParams["EXHIB_IBLOCK_ID"] = 15;

$arParams["EXHIB_CODE"] = trim($_REQUEST["EXHIBIT_CODE"]);//код выставки из реквеста
$arParams["CACHE_TIME"] = 3600;

$arParams["USER_ID"] = intval($arParams["USER_ID"]);

$lang = strtoupper(LANGUAGE_ID);

$userType = $_SESSION["USER_TYPE"];
$userId = $arParams["USER_ID"];
$rsUser = CUser::GetByID($userId);
$arUser = $rsUser->Fetch();

$arUserGroups = CUser::GetUserGroup($userId); //группы пользователя

if($this->StartResultCache(false, $lang))
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arFilter = array(
			"ACTIVE" => "Y",
			"CODE" => $arParams["EXHIB_CODE"]
	);

	$arSelect = array(
			"ID",
			"NAME",
			"CODE",
			"IBLOCK_ID",
			"PROPERTY_*"
	);

	$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);
	while($obElement = $rsElement->GetNextElement())
	{
		$arItem = $obElement->GetFields();
    	$arItem["PROPERTIES"] = $obElement->GetProperties();

    	//Проверка на доступ пользователя к этой выставке
    	
    	$confirmedGroupID = $arItem["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
    	
    	//если пользователь не находится в группе подтвержденных
    	if(!in_array($confirmedGroupID, $arUserGroups))
    	{
    		$arResult["ERROR"] = "Permissions error";
    		break;
    	}
    	
    	if(LANGUAGE_ID != "ru")
    	{
    		$arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];
    	}
    	$arItem["EXH_NAME"] = $arItem["PROPERTIES"]["V_" . $lang]["VALUE"];
    	$arItem["VENUE"] = $arItem["PROPERTIES"]["VENUE"]["VALUE"];
    	$arItem["SHORT_NAME"] = $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"];
    	$arItem["DATE"] = $arItem["PROPERTIES"]["DATE"]["VALUE"];
    	
    	
    	// id результата заполнения формы компании
    	$companyResultID = $arUser["UF_ID_COMP"];
    	
    	//получение ид свойства пользователя в котором хранится результат заполнения формы участника
    	$userExhibPropertyID = CFormMatrix::getPropertyIDByExh($arItem["ID"]);
    	
    	//id результата заполнения формы пользователя на текущую выставку
    	$userResultID = $arUser[$userExhibPropertyID];
    	
    	//id формы пользователя на текущую выставку
    	$formID = CFormMatrix::getPFormIDByExh($arItem["ID"]);
    	
    	$arResult["FORM_COMPANY"] = array("FORM_ID" => COMPANY_FORM_ID, "FORM_RESULT_ID" => $companyResultID);
    	$arResult["FORM_PROFILE"] = array("FORM_ID" => $formID, "FORM_RESULT_ID" => $userResultID);
    	$arResult["USER_ID"] = $arParams["USER_ID"];
    	
    	$arResult = array_merge($arResult, $arItem);
    	
    	
	}

		$this->SetResultCacheKeys(array(
				"ID",
				"NAME",
				"CODE",
				"IBLOCK_ID",
				"PROPERTIES",
				"FORM_COMPANY",
				"FORM_PROFILE",
				"USER_ID",
				"EXH_NAME",
				"VENUE",
				"SHORT_NAME",
				"DATE"
		));

		$this->IncludeComponentTemplate();
	}
	else
	{
		$this->AbortResultCache();
		ShowError(GetMessage("T_NEWS_DETAIL_NF"));
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
	}