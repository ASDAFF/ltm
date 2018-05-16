<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $DB, $USER, $APPLICATION;
global $sortField, $sortOrder;

isset($arParams["CACHE_TIME"]) or $arParams["CACHE_TIME"] = 3600;
isset($arParams["IBLOCK_ID_EXHIB"]) or $arParams["IBLOCK_ID_EXHIB"] = 15;
isset($arParams["ACT"]) or $arParams["ACT"] = $_REQUEST["ACT"];//off morning evening hostbuy
isset($arParams["EXHIBIT_CODE"]) or $arParams["EXHIBIT_CODE"] = $_REQUEST["EXHIBIT_CODE"];
isset($arParams["GUEST_FORM_ID"]) or $arParams["GUEST_FORM_ID"] = 10;

switch ($arParams["ACT"]) {
    case "spam":
    case "off":
    case "morning":
    case "evening":
    case "hostbuy":
        break;
    default:
        throw new Exception("Incorrect ACT");
}

$arResult = array();

$sortField = $arResult["SORT"] = ($_REQUEST["sort"]) ? $_REQUEST["sort"] : "COMPANY";
$sortOrder = $arResult["ORDER"] = ($_REQUEST["order"]) ? $_REQUEST["order"] : "asc";

if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form") || !CModule::IncludeModule("highloadblock")) {
    $this->AbortResultCache();
    throw new Exception("Can't load modules iblock form hlblock");
}

//получаем данные по выставке
$rs = CIBlockElement::GetList(array("SORT" => "ASC"),
    array("IBLOCK_ID" => intval($arParams["IBLOCK_ID_EXHIB"]), "CODE" => $arParams["EXHIBIT_CODE"]/*, "ACTIVE"=>"Y"*/),
    false, array("nTopCount" => 1),
    array("ID", "NAME", "CODE", "PROPERTY_UC_GUESTS_GROUP", "PROPERTY_GUEST_SPAM_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_APP_HB_ID"));
if ($ar = $rs->GetNext(true, false)) {
    $arResult["EXHIB"] = $ar;
}

if (!isset($arResult["EXHIB"])) {
    throw new Exception("Incorrect exhibbit");
}
//список пользователей
$arUserFilter = [
    "GROUPS_ID" => $groupId,
    "ACTIVE" => "Y"
];
$arHlBlockFilter = [
    "UF_EXHIB_ID" => $arResult["EXHIB"]["ID"],
];
$collegueDayTime = false;
$ufEnum = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["HLBLOCK_GUEST_COLLEAGUES_ID"], 'LANG' => LANGUAGE_ID, 'FIELD_NAME' => 'UF_DAYTIME') )->Fetch();
switch ($arParams["ACT"]) {
    case "off":
        $groupId = $arResult["EXHIB"]["PROPERTY_UC_GUESTS_GROUP_VALUE"];
        break;
    case "spam":
        $groupId = $arResult["EXHIB"]["PROPERTY_GUEST_SPAM_GROUP_VALUE"];
        break;
    case "morning":
        $groupId = $arResult["EXHIB"]["PROPERTY_C_GUESTS_GROUP_VALUE"];
        $arUserFilter["UF_MR"] = true;
        $arHlBlockFilter["UF_MORNING"] = true;
        $dayTime = CUserFieldEnum::GetList(array(), array(
            "USER_FIELD_ID" => $ufEnum['ID'],
            "XML_ID" => strtolower($arParams["ACT"]),
        ))->Fetch();
        $collegueDayTime = $dayTime['ID'];
        break;
    case "evening":
        $groupId = $arResult["EXHIB"]["PROPERTY_C_GUESTS_GROUP_VALUE"];
        $arUserFilter["UF_EV"] = true;
        $arHlBlockFilter["UF_EVENING"] = true;
        $dayTime = CUserFieldEnum::GetList(array(), array(
            "USER_FIELD_ID" => $ufEnum['ID'],
            "XML_ID" => strtolower($arParams["ACT"]),
        ))->Fetch();
        $collegueDayTime = $dayTime['ID'];
        break;
    case "hostbuy":
        $groupId = $arResult["EXHIB"]["PROPERTY_C_GUESTS_GROUP_VALUE"];
        $arUserFilter["UF_HB"] = true;
        $arHlBlockFilter["UF_MORNING"] = true;
        $dayTime = CUserFieldEnum::GetList(array(), array(
            "USER_FIELD_ID" => $ufEnum['ID'],
            "XML_ID" => strtolower("morning"),
        ))->Fetch();
        $collegueDayTime = $dayTime['ID'];
        break;
    default:
        $groupId = false;
}



$userFieldFormAnswer = CFormMatrix::getPropertyIDByExh($arResult["EXHIB"]["ID"]);
$arUserFilter["!$userFieldFormAnswer"] = false;

$arUserListAll = array();
$arUserFormAnswersId = array();

$isFiltered = false;
if (isset($_REQUEST["filter"])) {
    $arResult["FILTER"]["ID"] = $_REQUEST["ID"];
    $arResult["FILTER"]["COMPANY"] = $_REQUEST["COMPANY"];
    $arResult["FILTER"]["REP"] = $_REQUEST["REP"];
    $arResult["FILTER"]["PHONE"] = $_REQUEST["PHONE"];
    $arResult["FILTER"]["EMAIL"] = $_REQUEST["EMAIL"];
    $arResult["FILTER"]["DATE_REGISTER"] = $_REQUEST["DATE_REGISTER"];
    $arResult["FILTER"]["LOGIN"] = $_REQUEST["LOGIN"];

    if (!empty($arResult["FILTER"]["ID"])) {
        $arUserFilter["ID"] = $arResult["FILTER"]["ID"];
    }
    if (!empty($arResult["FILTER"]["LOGIN"])) {
        $arUserFilter["LOGIN"] = $arResult["FILTER"]["LOGIN"];
    }
    if (!empty($arResult["FILTER"]["EMAIL"]) || !empty($arResult["FILTER"]["COMPANY"]) || !empty($arResult["FILTER"]["REP"])
        || !empty($arResult["FILTER"]["PHONE"]) || !empty($arResult["FILTER"]["DATE_REGISTER"])) {
        $isFiltered = true;
    }
}

$rsData = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["HLBLOCK_GUEST_ID"], 'LANG' => LANGUAGE_ID) );
$arHlBlockInfo = [];
while($arRes = $rsData->Fetch())
{
    $arHlBlockInfo[$arRes["FIELD_NAME"]] = $arRes;
    if($arRes["USER_TYPE_ID"] == "hlblock"){
        $hlblock = HL\HighloadBlockTable::getById($arRes["SETTINGS"]["HLBLOCK_ID"])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $arrFilter = [];
        if($arRes["FIELD_NAME"] === "UF_COLLEAGUES"){
            $arrFilter['UF_DAYTIME'] = $collegueDayTime;
        }
        $result = $entity_data_class::getList([
            'filter' => $arrFilter,
        ]);
        while ($arElem = $result->Fetch()){
            if($arRes["FIELD_NAME"] === "UF_COLLEAGUES"){
                $arHlBlockInfo[$arRes["FIELD_NAME"]]["ITEMS"] = $arElem;
            }else{
                $arHlBlockInfo[$arRes["FIELD_NAME"]]["ITEMS"][$arElem["ID"]] = $arElem;
            }
        }
    }
}
$hlblock = HL\HighloadBlockTable::getById($arParams["HLBLOCK_GUEST_ID"])->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    'select' => ['*'],
    'filter' => $arHlBlockFilter,
    'order' => ['UF_USER_ID' => 'ASC']
));

while ($el = $rsData->fetch()) {

    $user = CUser::GetByID($el["UF_USER_ID"])->Fetch();
    $user["REG_DATE"] = $user["DATE_REGISTER"];
    $tmpTime = strtotime($user["REG_DATE"]);
    $user["REG_DATE_DATE"] = date("d.m.Y", $tmpTime);
    $diff = (time() - $tmpTime) / 60;//разница в минутах
    $user["REG_DIFF"] = floor($diff / 60) . "ч " . ($diff % 60) . "м";

    foreach ($el as $key => $value) {
        if(array_key_exists($key, $arHlBlockInfo)){
            switch ($arHlBlockInfo[$key]["USER_TYPE_ID"]){
                case "hlblock":
                    if($key === "UF_COUNTRY"){
                        if($el["UF_COUNTRY_OTHER"]){
                            $user[$key] = $el["UF_COUNTRY_OTHER"];
                        }else{
                            $user[$key] = $arHlBlockInfo[$key]["ITEMS"][$value]["UF_VALUE"];
                        }
                    }elseif($key === "UF_COLLEAGUES"){
                        $user[$key] = $arHlBlockInfo[$key]["ITEMS"];
                        foreach ($user[$key] as $field => $elem){
                            if($field === "UF_SALUTATION"){
                                $user["COLLEAGUE_" . $field] = $arHlBlockInfo[$field]["ITEMS"][$elem]["UF_VALUE"];
                            }else{
                                $user["COLLEAGUE_" . $field] = $elem;
                            }
                        }
                    }elseif(is_array($value)){
                        $newValues = [];
                        foreach ($value as $elem){
                            $newValues[] = $arHlBlockInfo[$key]["ITEMS"][$elem]["UF_VALUE"];
                        }
                        $user[$key] = $newValues;
                    }else{
                        $user[$key] = $arHlBlockInfo[$key]["ITEMS"][$value]["UF_VALUE"];
                    }
                    break;
                default:
                    switch ($key){
                        case "UF_MORNING":
                            if($value){
                                $user[$key] = "Утро";
                            }
                            break;
                        case "UF_EVENING":
                            if($value){
                                $user[$key] = "Вечер";
                            }
                            break;
                        default:
                            $user[$key] = $value;
                            break;
                    }
                    break;
            }
        }
    }
    $arUserListAll[$user["LOGIN"]] = $user;
}
$arResult["USERS_LIST"] = $arUserListAll;

if (empty($arUserListAll)) {
    echo("There are 0 users.");
}

if ($isFiltered) {
    $arResult["USERS_LIST"] = $arFilterRes;
    unset($arFilterRes);
}

uasort($arResult["USERS_LIST"], "cmp");
$res = new CDBResult;
$res->InitFromArray($arResult["USERS_LIST"]);
$res->NavStart(30); // разбиваем постранично по 30 записей
$arResult["NAVIGATE"] = $res->GetPageNavStringEx($navComponentObject, "Пользователи", "");
unset($arResult["USERS_LIST"]);
while ($ar = $res->Fetch()) {
    $arResult["USERS_LIST"][] = $ar;
}

$this->IncludeComponentTemplate();

function cmp($a, $b)
{
    global $sortField;
    global $sortOrder;

    $first = strtolower($a[$sortField]);
    $second = strtolower($b[$sortField]);
    if ($first == $second) {
        return 0;
    }
    $res = 1;
    if ($sortOrder != "asc") $res = -1;
    return ($first < $second) ? -1 * $res : 1 * $res;
}

?>
