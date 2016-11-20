<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

if(!isset($arParams["LANG"]))
    $arParams["LANG"] = LANGUAGE_ID;

$arParams["FORM_COMMON_ID"] = trim($arParams["FORM_COMMON_ID"]);
if(strlen($arParams["FORM_COMMON_ID"]) <= 0)
    $arParams["FORM_COMMON_ID"] = 3;

$arParams["FORM_FIELD_COMPANY_NAME_ID"] = trim($arParams["FORM_FIELD_COMPANY_NAME_ID"]);
if(strlen($arParams["FORM_FIELD_COMPANY_NAME_ID"]) <= 0)
    $arParams["FORM_FIELD_COMPANY_NAME_ID"] = 17;

foreach ($arParams["USER_GROUP_ID"] as &$groupID)
{
    $groupID = intval($groupID);
}
if(empty($arParams["USER_GROUP_ID"]))
    $arParams["USER_GROUP_ID"] = array(21);

$arParams["FORM_FIELD_LOGIN_ID"] = trim($arParams["FORM_FIELD_LOGIN_ID"]);
if(strlen($arParams["FORM_FIELD_LOGIN_ID"]) <= 0)
    $arParams["FORM_FIELD_ID_LOGIN"] = 18;

$arParams["URL_TEMPLATE"] = trim($arParams["URL_TEMPLATE"]);
if(strlen($arParams["URL_TEMPLATE"]) <= 0)
    $arParams["URL_TEMPLATE"] = "/members/#ELEMENT_ID#/";

$arParams["ELEMENT_COUNT"] = intval($arParams["ELEMENT_COUNT"]);


$arResult = array();

$arGroupUser = array();

if($this->StartResultCache(false, $arParams))
{
	if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
	{
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock form");
	}

	//список пользователей разделенный на группы подтвержденных для участия
	foreach ($arParams["USER_GROUP_ID"] as $groupID)
	{

	    $arGroupUser[$groupID] = CGroup::GetGroupUser($groupID);
	    foreach ($arGroupUser[$groupID] as &$user)
	    {
	        $rsUser = CUser::GetByID($user);
	        $arUser = $rsUser->Fetch();
	        $user = $arUser["LOGIN"];
	    }
	}

    //список ответов формы "Участники данные компании ВСЕ ВЫСТАВКИ"
    $arResult["FORM_RESULT_COMMON"] = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array());
//     $rs = CFormResult::GetList($arParams["FORM_COMMON_ID"], ($by = "ID"), ($order = "ASC"), array(), ($isFilteres = false), "N", false);
//     while($ar = $rs->GetNext(true, false)) {
//         $arResult["FORM_RESULT_COMMON"]["RESULTS"][$ar["ID"]] = $ar;
//     }

    //список результатов ответов формы "Участники данные компании ВСЕ ВЫСТАВКИ"
    $arFieldsID = $arParams["FORM_FIELD_COMPANY_NAME_ID"]."|".$arParams["FORM_FIELD_LOGIN_ID"];

    CForm::GetResultAnswerArray(
        $arParams["FORM_COMMON_ID"],
        $arResult["FORM_RESULT_COMMON"]["QUESTIONS"],
        $arResult["FORM_RESULT_COMMON"]["ANSWERS"],
        $arAnswer2,
        array("FIELD_ID" => $arFieldsID)
    );

    krsort($arResult["FORM_RESULT_COMMON"]["ANSWERS"]);


    $arResult["ITEMS"] = array();
    foreach ($arResult["FORM_RESULT_COMMON"]["ANSWERS"] as $resID => $answer)
    {
        $arItem = array();

        $login = reset($answer[$arParams["FORM_FIELD_LOGIN_ID"]]);
        $login = $login["USER_TEXT"];

        $bExist = false;
        foreach ($arGroupUser as $arUserLogin)
        {
        	if(in_array($login, $arUserLogin))
        	{
        	    $bExist = true;
        	    break;
        	}
        }

        if($bExist)
        {
            $company_name = reset($answer[$arParams["FORM_FIELD_COMPANY_NAME_ID"]]);
            $arItem["COMPANY_NAME"] = $company_name["USER_TEXT"];
            $arItem["DETAIL_PAGE_URL"] = str_replace("#ELEMENT_ID#", $resID, $arParams["URL_TEMPLATE"]);

            if(!in_array($arItem, $arResult["ITEMS"]))
            {
                $arResult["ITEMS"][] = $arItem;
            }
        }
    }

	$this->IncludeComponentTemplate();
}
?>
