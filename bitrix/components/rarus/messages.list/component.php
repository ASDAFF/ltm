<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!CModule::IncludeModule("highloadblock")):
	ShowError(GetMessage("HL_NO_MODULE"));
	return 0;
elseif (!$USER->IsAuthorized()):
	$APPLICATION->AuthForm(GetMessage("HLM_AUTH"));
	return 0;
endif;

// *****************************************************************************************

	InitSorting();
	global $by, $order;

	if (empty($by))
	{
	    $by = "UF_POST_DATE";
	    $order = "DESC";
	}
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
	$arParams["FID"] = intVal(intVal($arParams["FID"]) <= 0 ? 3 : $arParams["FID"]);
	$arParams["EID"] = intVal(empty($arParams["EID"]) ? $_REQUEST["EID"] : $arParams["EID"]);
	$arParams["HLID"] = intVal(empty($arParams["HLID"]) ? $_REQUEST["HLID"] : $arParams["HLID"]);
	$arParams["PM_PER_PAGE"] = intVal($arParams["PM_PER_PAGE"] > 0 ? $arParams["PM_PER_PAGE"] : 20);
	$arParams["DATE_FORMAT"] = trim(empty($arParams["DATE_FORMAT"]) ? $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")) : $arParams["DATE_FORMAT"]);
	$arParams["DATE_TIME_FORMAT"] = trim(empty($arParams["DATE_TIME_FORMAT"]) ? $DB->DateFormatToPHP(CSite::GetDateFormat("FULL")) : $arParams["DATE_TIME_FORMAT"]);


/***************** STANDART ****************************************/
	$arParams["SET_NAVIGATION"] = ($arParams["SET_NAVIGATION"] == "Y" ? "Y" : "N");
	if ($arParams["CACHE_TYPE"] == "Y" || ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "Y"))
		$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
	else
		$arParams["CACHE_TIME"] = 0;
	$arParams["SET_TITLE"] = ($arParams["SET_TITLE"] == "N" ? "N" : "Y");

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
$arResult["sessid"] = bitrix_sessid_post();
$arResult["FID"] = $arParams["FID"];

// начало сортировка

$SortingField = "AUTHOR_NAME";
if ($arParams["FID"] == 3)
{
    $SortingField = "A_NAME";
    $SortingFieldCompany = "A_COMPANY";
}
elseif ($arParams["FID"] = 4)
{
    $SortingField = "R_NAME";
    $SortingFieldCompany = "R_COMPANY";
}

$arResult["SortingEx"]["SUBJECT"] = SortingEx("UF_SUBJECT");
$arResult["SortingEx"]["AUTHOR"] = SortingEx($SortingField);
$arResult["SortingEx"]["COMPANY"] = SortingEx($SortingFieldCompany);
$arResult["SortingEx"]["POST_DATE"] = SortingEx("UF_POST_DATE");


// конец сортировка

// pagination
$limit = array(
    'nPageSize' => $arParams['PM_PER_PAGE'],
    'iNumPage' => is_set($_GET['PAGEN_1']) ? $_GET['PAGEN_1'] : 1,
    'bShowAll' => true
);

$filter = array(
    "UF_EXHIBITION" => $arParams["EID"],
    "UF_FOLDER" => $arParams["FID"],
);
if($arParams["FID"] == "3")
{
    $filter["UF_RECIPIENT"] = ($USER->IsAdmin())? 1 : $USER->GetID();
}
elseif($arParams["FID"] == "4")
{
    $filter["UF_AUTHOR"] = ($USER->IsAdmin())? 1 : $USER->GetID();
}

$select = array("ID", "UF_AUTHOR", "UF_RECIPIENT",  "UF_POST_DATE", "UF_IS_READ", "UF_SUBJECT", "UF_FOLDER");

$main_query = new Entity\Query($entity);
$main_query->setSelect($select);
if(strstr($by, "UF_") !== false)//для сортировки по свойствам добавляем в выборку
{
    $by = strtoupper($by);
    $order = strtoupper($order);
    $main_query->setOrder(array($by => $order));
}
$main_query->setFilter($filter);

if (isset($limit['nPageTop']))
{
    $main_query->setLimit($limit['nPageTop']);
}
else
{
    $main_query->setLimit($limit['nPageSize']);
    $main_query->setOffset(($limit['iNumPage']-1) * $limit['nPageSize']);
}

$arResult["ITEMS"] = array();

$rMessage = $main_query->exec();
$rMessage = new CDBResult($rMessage);
$rMessage->NavStart($limit['nPageSize'], false);

while ($arMessage = $rMessage->Fetch())
{
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
        $arUserGroup = Cuser::GetUserGroup($arItem["AUTHOR"]["ID"]);
        if(in_array($arResult["EXHIBIT"]["PROPERTY_C_GUESTS_GROUP_VALUE"], $arUserGroup))
        {
            $arItem["AUTHOR"]["COMPANY_ID"] = "";
        }
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
        $arUserGroup = Cuser::GetUserGroup($arItem["RECIPIENT"]["ID"]);
        if(in_array($arResult["EXHIBIT"]["PROPERTY_C_GUESTS_GROUP_VALUE"], $arUserGroup))
        {
            $arItem["RECIPIENT"]["COMPANY_ID"] = "";
        }
    }

    $folderProp = $arResult["USER_FIELDS_ENUM"]["FOLDER"];
    $folderCode = $folderProp[$arItem["FOLDER"]]["XML_ID"];
    $folderCode = strtolower($folderCode);

    $arItem["URL_HLM_LIST"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_LIST"], array("FCODE" => $folderCode));
    $arItem["URL_HLM_READ"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_READ"], array("MID" => $arItem["ID"]));
    if($arParams["FID"] == "3")
    {
        $UIDTO = $arItem["AUTHOR"]["ID"];
        $CID = $arItem["AUTHOR"]["COMPANY_ID"];
    }
    elseif($arParams["FID"] == "4")
    {
        $UIDTO = $arItem["RECIPIENT"]["ID"];
        $CID = $arItem["RECIPIENT"]["COMPANY_ID"];
    }
    $arItem["URL_HLM_NEW"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_NEW"], array("UID" => $UIDTO));
    $arItem["URL_HLM_COMPANY_VIEW"] = CComponentEngine::MakePathFromTemplate($arParams["URL_TEMPLATES_HLM_COMPANY_VIEW"], array("CID" => $CID));

    $arResult["ITEMS"][] = $arItem;

    /************ URL_TEMPLATE ***************/


//сортировка по полям не входящими в сообщение
if(strstr($by, "UF_") === false)
{
    switch ($by)
    {
        case "A_COMPANY":
            {
                usort($arResult["ITEMS"], "cmp_a_company");
            }
            break;
        case "R_COMPANY":
            {
                usort($arResult["ITEMS"], "cmp_r_company");
            }
            break;

        case "AUTHOR":
            {
                usort($arResult["ITEMS"], "cmp_author");
            }
            break;

        case "RECIPIENT":
            {
                usort($arResult["ITEMS"], "cmp_recipient");
            }
            break;
    }
    if(strtolower($order) == "asc")
    {
        rsort($arResult["ITEMS"]);
    }
}
}

//пагинация
//костыль, так как битриксойды не реализовали нормально этот функционал

$countQuery = new Entity\Query($entity);
$countQuery->setSelect(array('CNT'=>array('expression' => array('COUNT(1)'), 'data_type'=>'integer')));
$countQuery->setFilter($filter);
$totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
$totalCount = intval($totalCount['CNT']);
$totalPage = ceil($totalCount/$limit['nPageSize']);
$rMessage->NavRecordCount = $totalCount;
$rMessage->NavPageCount = $totalPage;
$rMessage->NavPageNomer = $limit["iNumPage"];

$arResult["NAV_STRING"] = $rMessage->GetPageNavString('', (is_set($arParams['NAV_TEMPLATE'])) ? $arParams['NAV_TEMPLATE'] : '');
$arResult["NAV_PARAMS"] = $rMessage->GetNavParams();
$arResult["NAV_NUM"] = $rMessage->NavNum;

//$arResult['sort_id'] = $sort_id;
//$arResult['sort_type'] = $sort_type;


if ($arParams["SET_TITLE"] != "N")
{
   $APPLICATION->SetTitle(GetMessage("HLM_TITLE_LIST"));
}
switch($arParams["FID"])
{
    case 3 :
        {
            $arResult["StatusUser"] = "RECIPIENT";
            $arResult["InputOutput"] = "RECIPIENT_ID";
        }
        break;
    case 4 :
        {
            $arResult["StatusUser"] = "SENDER";
            $arResult["InputOutput"] = "AUTHOR_ID";
        }
        break;
    default:
        {
            $arResult["StatusUser"] = "AUTHOR";
            $arResult["InputOutput"] = "AUTHOR_ID";
        }
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


function cmp_a_company($arA, $arB)
{
    $a = strtolower($arA["AUTHOR"]["COMPANY_NAME"]);
    $b = strtolower($arB["AUTHOR"]["COMPANY_NAME"]);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
function cmp_r_company($arA, $arB)
{
    $a = strtolower($arA["RECIPIENT"]["COMPANY_NAME"]);
    $b = strtolower($arB["RECIPIENT"]["COMPANY_NAME"]);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
function cmp_author($arA, $arB)
{
    $a = strtolower($arA["AUTHOR"]["NAME"]);
    $b = strtolower($arB["AUTHOR"]["NAME"]);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
function cmp_recipient($arA, $arB)
{
    $a = strtolower($arA["RECIPIENT"]["NAME"]);
    $b = strtolower($arB["RECIPIENT"]["NAME"]);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
?>