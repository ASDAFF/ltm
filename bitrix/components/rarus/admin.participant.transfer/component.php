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

$arParams["EXHIB_IBLOCK_ID"] = intval($arParams["EXHIB_IBLOCK_ID"]);//id ��������� ��������
if(!$arParams["EXHIB_IBLOCK_ID"])
    $arParams["EXHIB_IBLOCK_ID"] = 15;

intval($arParams["CACHE_TIME"]) or $arParams["CACHE_TIME"] = 3600;

$arParams["USER_ID"] = intval($arParams["USER_ID"]);

$lang = strtoupper(LANGUAGE_ID);


$userId = $arParams["USER_ID"];


if(!($USER->IsAuthorized()))
{
    $arResult["ERROR_MESSAGE"] = "�� �� ������������!<br />";
}

if(intval($arParams["USER_ID"]) <= 0){
    $arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������������!<br />";
}

if(!($USER->IsAdmin()))
{
    $arResult["ERROR_MESSAGE"] = "�� �� �������������!<br />";
}

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
{
    $arResult["ERROR_MESSAGE"] = "������ ����������� �������!<br />";
}


$rsUser = CUser::GetByID($userId);
$arUser = $rsUser->Fetch();

$arUserGroups = CUser::GetUserGroup($userId); //������ ������������

$arResult["URL"] = $APPLICATION->GetCurUri();

if($arResult["ERROR_MESSAGE"] == '')
{

    if($this->StartResultCache())
    {

    	$arFilter = array(
    			"ACTIVE" => "Y",
    			"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"]
    	);

    	$arSelect = array(
    			"ID",
    			"NAME",
    			"CODE",
    			"IBLOCK_ID",
    			"PROPERTY_USER_GROUP_ID",
    	        "PROPERTY_UC_PARTICIPANTS_GROUP",
    	        "PROPERTY_STATUS"
    	);

    	$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);
    	while($obElement = $rsElement->GetNextElement(true,false))
    	{
    		$arItem = $obElement->GetFields();
        	//$arItem["PROPERTIES"] = $obElement->GetProperties();

        	$arItem["USER_GROUP_ID"] = $arItem["PROPERTY_USER_GROUP_ID_VALUE"];
        	$arItem["UC_PARTICIPANTS_GROUP"] = $arItem["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"];
        	$arItem["EXH_STATUS"] = $arItem["PROPERTY_STATUS_VALUE"];

        	if(in_array($arItem["USER_GROUP_ID"], $arUserGroups))//���� � ������ ��������������
        	{
        	    $arItem["STATUS"] = "CONFIRMED";
        	}
        	elseif(in_array($arItem["UC_PARTICIPANTS_GROUP"], $arUserGroups))//���� � ������ �� ��������������
        	{
        	    $arItem["STATUS"] = "UNCONFIRMED";
        	}
        	else //���� �� ��������� �� � ����� �������
        	{
        	    $arItem["STATUS"] = "NONE";
        	}

        	$arResult["ITEMS"][$arItem["ID"]] = $arItem;
    	}

    		$this->SetResultCacheKeys(array(
    				"ID",
    				"NAME",
    				"CODE",
    				"IBLOCK_ID",
    				"PROPERTIES",
    		));

	}
	else
	{
		$this->AbortResultCache();
		ShowError(GetMessage("T_NEWS_DETAIL_NF"));
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
	}

	//��������� �����

	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"]) && check_bitrix_sessid())
	{
		foreach ($arResult["ITEMS"] as &$arItem)
		{
			if("CONFIRMED" == $arItem["STATUS"])//���� �������� ����������� ������ �� ������ � ����� ������
			{
				continue;
			}
			elseif("UNCONFIRMED" == $arItem["STATUS"])//���� �������� � ���������������� �� ������ ��������, �� ����� ��� ������� �� ���
			{
				if(isset($_POST["EXH"][$arItem["ID"]]) && "on" == $_POST["EXH"][$arItem["ID"]])//��� � ���������������� � �������
				{
					continue;
				}
				else//������ ������� �� ����������������
				{
					$index = array_search($arItem["UC_PARTICIPANTS_GROUP"], $arUserGroups);
					if($index)
					{
					    unset($arUserGroups[$index]);
					    $arItem["STATUS"] = "NONE";
					}


				}
			}
			elseif("NONE" == $arItem["STATUS"])
			{
			    if(isset($_POST["EXH"][$arItem["ID"]]) && "on" == $_POST["EXH"][$arItem["ID"]])//�� ��� � ������ ����������������, �� ������ ��������
			    {
			        $arUserGroups[] = $arItem["UC_PARTICIPANTS_GROUP"];
			        $arItem["STATUS"] = "UNCONFIRMED";
			    }
			    else//���� �� ��� �������� ������ �� ��������
			    {
			        continue;
			    }
			}
		}

		$USER->SetUserGroup($arParams["USER_ID"], $arUserGroups);
	}
}
$this->IncludeComponentTemplate();
