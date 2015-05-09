<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!CModule::IncludeModule("highloadblock")):
	ShowError(GetMessage("HL_NO_MODULE"));
	return 0;
elseif (!$USER->IsAuthorized()):
	$APPLICATION->AuthForm(GetMessage("HLM_AUTH"));
	return 0;
endif;

	InitSorting();
	global $by, $order;

/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
	$arParams["MID"] = intVal($arParams["MID"] > 0 ? $arParams["MID"] : $_REQUEST["MID"]);
	$arParams["UID"] = intVal($USER->GetID());
	if($USER->IsAdmin())
	{
	    $arParams["UID"] = 1;
	}

	$arParams["EID"] = intVal(empty($arParams["EID"]) ? $_REQUEST["EID"] : $arParams["EID"]);
	$arParams["HLID"] = intVal(empty($arParams["HLID"]) ? $_REQUEST["HLID"] : $arParams["HLID"]);

/***************** URL *********************************************/
$URL_NAME_DEFAULT = array(
    "hlm_list" => "",
    "hlm_read" => "MID=#MID#",
    "hlm_new" => "id=#UID#",
    "hlm_company_view" => "",
);

foreach ($URL_NAME_DEFAULT as $URL => $URL_VALUE)
{
    if (strLen(trim($arParams["URL_TEMPLATES_".strToUpper($URL)])) <= 0)
        $arParams["URL_TEMPLATES_".strToUpper($URL)] = $APPLICATION->GetCurPageParam($URL_VALUE, array("FID", "TID", "UID", "MID", "action","sessid", BX_AJAX_PARAM_ID));
    $arParams["~URL_TEMPLATES_".strToUpper($URL)] = $arParams["URL_TEMPLATES_".strToUpper($URL)];

    $arParams["URL_TEMPLATES_".strToUpper($URL)] = htmlspecialcharsEx($arParams["~URL_TEMPLATES_".strToUpper($URL)]);
}

/***************** STANDART ****************************************/
	$arParams["SET_NAVIGATION"] = ($arParams["SET_NAVIGATION"] == "Y" ? "Y" : "N");
	if ($arParams["CACHE_TYPE"] == "Y" || ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "Y"))
		$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
	else
		$arParams["CACHE_TIME"] = 0;
	$arParams["SET_TITLE"] = ($arParams["SET_TITLE"] == "N" ? "N" : "Y");

	$arParams["DATE_FORMAT"] = trim(empty($arParams["DATE_FORMAT"]) ? $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")) : $arParams["DATE_FORMAT"]);
	$arParams["DATE_TIME_FORMAT"] = trim(empty($arParams["DATE_TIME_FORMAT"]) ? $DB->DateFormatToPHP(CSite::GetDateFormat("FULL")) : $arParams["DATE_TIME_FORMAT"]);
/********************************************************************
				/Input params
********************************************************************/


// начало ********************* highloadblock init ***************************************

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$hlblock = HL\HighloadBlockTable::getById($arParams["HLID"])->fetch();
// получаем сущность
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$HLDataClass = $entity->getDataClass();

// uf info
global $USER_FIELD_MANAGER;
$HLFields = $USER_FIELD_MANAGER->GetUserFields('HLBLOCK_'.$hlblock['ID'], 0, LANGUAGE_ID);


// конец ********************* highloadblock init *****************************************


$arResult = array();
$arResult["EXHIBIT"] = CHLMFunctions::GetExhibByID($arParams["EID"]);
$arResult["ERROR_MESSAGE"] = "";
$arResult["OK_MESSAGE"] = "";
$arResult["MESSAGE"] = array();

$main_query = new Entity\Query($entity);
$main_query->setSelect(array('*'));

$main_query->setFilter(array("=ID" => $arParams["MID"]));

$rMessage = $main_query->exec();
$rMessage = new CDBResult($rMessage);

if ($rMessage && ($arMessage = $rMessage->Fetch()))
{
    if($arMessage["UF_AUTHOR"] != $arParams["UID"] && $arMessage["UF_RECIPIENT"] != $arParams["UID"])
    {
        ShowError(GetMessage("HL_MESSAGE_NOT_FOUND"));
        return false;
    }
 $arItem = array("ID" => $arMessage["ID"]);
    foreach ($arMessage as $k => $v)
    {
        if ($k == 'ID')
        {
            continue;
        }

        $newKey = str_replace("UF_", "", $k);

        $arUserField = $HLFields[$k];

       if($arUserField["USER_TYPE_ID"] == "datetime")
       {
           if(is_object($v))
           {
               $v = $v->getTimestamp();
           }
           else
           {
               $v = false;
           }
       }

       if($arUserField["USER_TYPE_ID"] == "enumeration")
       {
           $arUserFieldsEnum = getPropertyEnum($arUserField["ID"]);
           $arResult["USER_FIELDS_ENUM"][$newKey] = $arUserFieldsEnum;
       }

       $arItem[$newKey] = $v;
    }

    //получение данных автора
    if($arItem["AUTHOR"] == 1)
    {
        $arItem["AUTHOR"] = array(
            "ID" => $arItem["AUTHOR"],
            "NAME" => CHLMFunctions::GetUserName($arItem["AUTHOR"]),
            "COMPANY_ID" => CHLMFunctions::GetUserCompanyID($arItem["AUTHOR"]),
            "COMPANY_NAME" => CHLMFunctions::GetUserCompany($arItem["AUTHOR"]),
        );
    }
    else
    {
        $userData = CHLMFunctions::GetUserInfoForm($arItem["AUTHOR"], $arResult["EXHIBIT"]);
        $arItem["AUTHOR"] = array(
            "ID" => $arItem["AUTHOR"],
            "NAME" => $userData["NAME"]. " " . $userData["LAST_NAME"],
            "COMPANY_ID" => CHLMFunctions::GetUserCompanyID($arItem["AUTHOR"]),
            "COMPANY_NAME" => $userData["COMPANY_NAME"],
        );
    }

    //получение данных получателя
    if($arItem["RECIPIENT"] == 1)
    {
        $arItem["RECIPIENT"] = array(
            "ID" => $arItem["RECIPIENT"],
            "NAME" => CHLMFunctions::GetUserName($arItem["RECIPIENT"]),
            "COMPANY_ID" => CHLMFunctions::GetUserCompanyID($arItem["RECIPIENT"]),
            "COMPANY_NAME" => CHLMFunctions::GetUserCompany($arItem["RECIPIENT"]),
        );
    }
    else
    {
        $userData = CHLMFunctions::GetUserInfoForm($arItem["RECIPIENT"], $arResult["EXHIBIT"]);
        $arItem["RECIPIENT"] = array(
            "ID" => $arItem["RECIPIENT"],
            "NAME" => $userData["NAME"]. " " . $userData["LAST_NAME"],
            "COMPANY_ID" => CHLMFunctions::GetUserCompanyID($arItem["RECIPIENT"]),
            "COMPANY_NAME" => $userData["COMPANY_NAME"],
            );
    }

    $folderProp = $arResult["USER_FIELDS_ENUM"]["FOLDER"];
    $folderCode = $folderProp[$arItem["FOLDER"]]["XML_ID"];
    $folderCode = strtolower($folderCode);

    $arItem["URL_HLM_LIST"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_LIST"], array("FCODE" => $folderCode));
    $arItem["URL_HLM_READ"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_READ"], array("MID" => $arItem["ID"]));
    $arItem["URL_HLM_NEW"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_NEW"], array("UID" => $arItem["AUTHOR"]["ID"]));
    $arItem["URL_HLM_COMPANY_VIEW"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_COMPANY_VIEW"], array("CID" => $arItem["AUTHOR"]["COMPANY_ID"]));

    $arResult["MESSAGE"] = $arItem;


    if(!$arMessage["UF_IS_READ"]){

        $arMessage["UF_IS_READ"] =  1;
        $USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$hlblock['ID'], null, $arMessage);
        $result = $HLDataClass::Update($arMessage["ID"], $arMessage);

        if(!$result->isSuccess())
        {
            $arResult["ERROR_MESSAGE"][] = $result->getErrorMessages();
        }
    }

}
else
{
	ShowError(GetMessage("HL_MESSAGE_NOT_FOUND"));
	return false;
}


if ($arParams["SET_NAVIGATION"] != "N")
{
	$APPLICATION->AddChainItem(GetMessage("HLM_TITLE_NAV"), CComponentEngine::MakePathFromTemplate($arParams["~URL_TEMPLATES_HLM_FOLDER"], array()));
	$APPLICATION->AddChainItem($arResult["FolderName"], CComponentEngine::MakePathFromTemplate($arParams["~URL_TEMPLATES_HLM_LIST"],array("FID" => $arParams["FID"])));

}
/*******************************************************************/
if ($arParams["SET_TITLE"] != "N")
{
	$APPLICATION->SetTitle(GetMessage("HLM_TITLE_READ"));
}

$this->IncludeComponentTemplate();




function getPropertyEnum($id)
{
	$rsUserField = CUserTypeEnum::GetList(array("ID" => $id));
	while($arUserField = $rsUserField->GetNext())
	{
		$arUserFields[$arUserField["ID"]] = $arUserField;
	}

	return $arUserFields;
}

?>