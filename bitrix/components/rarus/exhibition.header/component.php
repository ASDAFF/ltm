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

$arParams["EXHIB_IBLOCK_ID"] = intval($arParams["EXHIB_IBLOCK_ID"]);//id инфоблока выставок
if(!$arParams["EXHIB_IBLOCK_ID"])
	$arParams["EXHIB_IBLOCK_ID"] = 15;

$arParams["EXHIB_CODE"] = trim($arParams["EXHIB_CODE"]);//код выставки из реквеста
if(!$arParams)
	trim($_REQUEST["EXHIBIT_CODE"]);//код выставки из реквеста

$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
if(!$arParams["CACHE_TIME"])
	$arParams["CACHE_TIME"] = 3600;

$lang = strtoupper(LANGUAGE_ID);

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
		 
		if(LANGUAGE_ID != "ru")
		{
			$arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];
		}

		$arItem["VENUE"] = $arItem["PROPERTIES"]["VENUE"]["VALUE"];
		$arItem["EXH_NAME"] = $arItem["PROPERTIES"]["LONG_NAME"]["VALUE"];
		$arItem["DATE"] = $arItem["PROPERTIES"]["DATE"]["VALUE"];

		$arResult = $arItem;
		 
		 
	}

	$this->SetResultCacheKeys(array(
			"ID",
			"NAME",
			"CODE",
			"IBLOCK_ID",
			"PROPERTIES",
			"EXH_NAME",
			"VENUE",
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