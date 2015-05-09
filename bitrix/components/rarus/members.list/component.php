<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

if(!isset($arParams["LANG"]))
    $arParams["LANG"] = LANG;

if(!isset($arParams["IBLOCK_ID_EXHIB"]))
    $arParams["IBLOCK_ID_EXHIB"] = 15;

if(!isset($arParams["FORM_COMMON_ID"]))
    $arParams["FORM_COMMON_ID"] = 3;

if(!isset($arParams["FORM_FIELD_ID_NAME"]))
    $arParams["FORM_FIELD_ID_NAME"] = 17;

if(!isset($arParams["FORM_FIELD_ID_PRIORAREA"]))
    $arParams["FORM_FIELD_ID_PRIORAREA"] = array(25, 26, 27, 28, 29, 30);

if(!isset($arParams["FORM_FIELD_ID_COUNTRY"]))
    $arParams["FORM_FIELD_ID_COUNTRY"] = 22;

if(!isset($arParams["FORM_FIELD_ID_CATEGORY"]))
    $arParams["FORM_FIELD_ID_CATEGORY"] = 19;

if(!isset($arParams["FORM_FIELD_ID_LOGIN"]))
    $arParams["FORM_FIELD_ID_LOGIN"] = 18;

if(!isset($arParams["EXHIBIT_CODE_NAME_IN_REQUEST"]))
    $arParams["EXHIBIT_CODE_NAME_IN_REQUEST"] = "CODE";

$arResult = array();

if(isset($_REQUEST[ $arParams["EXHIBIT_CODE_NAME_IN_REQUEST"] ])) {
    $arResult["EXHIBIT_CODE"] = $_REQUEST[ $arParams["EXHIBIT_CODE_NAME_IN_REQUEST"] ];
} else {
    $arResult["EXHIBIT_CODE"] = "";
}

if($this->StartResultCache(false, array_merge($arParams, $arResult)))
{
	if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
	{
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock form");
	}

	//список выставок
	$arResult["EXHIB"] = array();
	$arResult["EXHIB_ID_BY_USER_GROUPS_ID"] = array();
	$rs = CIBlockElement::GetList(array("SORT"=>"ASC"),
	        array("IBLOCK_ID"=>$arParams["IBLOCK_ID_EXHIB"], "ACTIVE"=>"Y"), false, false,
	        array("ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_USER_GROUP_ID", "PROPERTY_NAME_EN", "PROPERTY_MENU_EN", "PROPERTY_MENU_RU"));
	while($ar = $rs->GetNext(true, false)) {
	    if($arResult["EXHIBIT_CODE"] && $arResult["EXHIBIT_CODE"] == $ar["CODE"]) {
	    	$ar["SELECTED"] = "Y";
	    }

	    $arResult["EXHIB"][$ar["ID"]] = $ar;
	    if($ar["PROPERTY_USER_GROUP_ID_VALUE"]) {
	    	if(isset($arResult["EXHIB_ID_BY_USER_GROUPS_ID"])) {
	    	    $arResult["EXHIB_ID_BY_USER_GROUPS_ID"][ $ar["PROPERTY_USER_GROUP_ID_VALUE"] ][] = $ar["ID"];
	    	} else {
	    	    $arResult["EXHIB_ID_BY_USER_GROUPS_ID"][ $ar["PROPERTY_USER_GROUP_ID_VALUE"] ] = array($ar["ID"]);
	    	}
	    }
	}

    //список групп
    $arResult["USER_GROUPS"] = array();
    $arResult["USER_GROUPS_BY_ID"] = array();
    foreach($arResult["EXHIB"] as $arItem) {
        if($arResult["EXHIBIT_CODE"] && $arItem["SELECTED"] != "Y") continue;

    	if(($groupId = intval($arItem["PROPERTY_USER_GROUP_ID_VALUE"])) && !isset($arResult["USER_GROUPS"][$groupId])) {
    		$arResult["USER_GROUPS"][$groupId] = CGroup::GetGroupUser($groupId);
    		foreach($arResult["USER_GROUPS"][$groupId] as $userId) {
    		    if(isset($arResult["USER_GROUPS_BY_ID"][$userId])) {
    		        $arResult["USER_GROUPS_BY_ID"][$userId][] = $groupId;
    		    } else {
    		        $arResult["USER_GROUPS_BY_ID"][$userId] = array($groupId);
    		    }
    		}
    	}
    }

    //список пользователей
    $arResult["USERS_LIST"] = array();
    $arResult["USERS_ID_BY_LOGIN"] = array();
    $rs = CUser::GetList(($by = "ID"), ($order = "ASC"), array("GROUPS_ID"=>array_keys($arResult["USER_GROUPS"]), "ACTIVE"=>"Y"),
            array("SELECT"=>array("UF_*"), "FIELDS"=>array("ID", "LOGIN")));
    while($ar = $rs->GetNext(true, false)) {
    	$arResult["USERS_LIST"][ $ar["ID"] ] = $ar;
    	$arResult["USERS_ID_BY_LOGIN"][ $ar["LOGIN"] ] = $ar["ID"];
    }

    //список ответов формы "Участники данные компании ВСЕ ВЫСТАВКИ"
    $arResult["FORM_RESULT_COMMON"] = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array());
//     $rs = CFormResult::GetList($arParams["FORM_COMMON_ID"], ($by = "ID"), ($order = "ASC"), array(), ($isFilteres = false), "N", false);
//     while($ar = $rs->GetNext(true, false)) {
//         $arResult["FORM_RESULT_COMMON"]["RESULTS"][$ar["ID"]] = $ar;
//     }

    //список результатов ответов формы "Участники данные компании ВСЕ ВЫСТАВКИ"
    CForm::GetResultAnswerArray(
        $arParams["FORM_COMMON_ID"],
        $arResult["FORM_RESULT_COMMON"]["QUESTIONS"],
        $arResult["FORM_RESULT_COMMON"]["ANSWERS"]);

    //список мероприятий
    $arResult["ITEMS"] = array();
    foreach($arResult["FORM_RESULT_COMMON"]["ANSWERS"] as $key=>$arAnswer) {
        $nameAnswer = reset($arAnswer[ $arParams["FORM_FIELD_ID_NAME"] ]);

        $categoryAnswer = reset($arAnswer[ $arParams["FORM_FIELD_ID_CATEGORY"] ]);
        $category = $categoryAnswer["ANSWER_TEXT"];

        $countryAnswer = reset($arAnswer[ $arParams["FORM_FIELD_ID_COUNTRY"] ]);

        $arPriorarea = array();
        foreach($arParams["FORM_FIELD_ID_PRIORAREA"] as $formFieldId) {
            foreach($arAnswer[$formFieldId] as $arPriorareaItem) {
            	if($arPriorareaItem["ANSWER_TEXT"]) {
            	    $curPriorarea = $arPriorareaItem["ANSWER_TEXT"];
            	    $arPriorarea[] = $curPriorarea;
            	    if(!isset($arResult["AVAILABLE_PRIORAREA"])) {
            	        $arResult["AVAILABLE_PRIORAREA"][$curPriorarea] = true;
            	    }
            	}
            }
        }

        $userLogin = reset($arAnswer[ $arParams["FORM_FIELD_ID_LOGIN"] ]);
        $userLogin = $userLogin["USER_TEXT"]; if(!$userLogin) continue;
        $userId = $arResult["USERS_ID_BY_LOGIN"][$userLogin]; if(!$userId) continue;
        $userGroups = $arResult["USER_GROUPS_BY_ID"][$userId]; if(!$userGroups) continue;

        $arExibits = array();
        foreach($userGroups as $userGroup) {
            $exibitsId = $arResult["EXHIB_ID_BY_USER_GROUPS_ID"][$userGroup];
            foreach($exibitsId as $exibitId) {
                $exibitName = $arResult["EXHIB"][$exibitId]["NAME"];
            	$arExibits[$exibitId] = $exibitName;
            }
        }
        if(empty($arExibits)) continue;

    	$arResult["ITEMS"][$key] = array(
    	        "ID" => $key,
    	        "NAME" => $nameAnswer["USER_TEXT"],
    	        "EXIBITS" => $arExibits,
    	        "CATEGORY" => $category,
    	        "COUNTRY" => $countryAnswer["USER_TEXT"],
    	        "PRIORAREA" => $arPriorarea
    	);

    	if(strstr($nameAnswer["USER_TEXT"], "Seychelles"))
    	{
    		//pre($arResult["ITEMS"][$key]);
    	}
    }

    //сортировка по названиям компаний
    usort($arResult["ITEMS"], "cmp");

    $arResult["AVAILABLE_CATEGORIES"] = array();
    $arResult["AVAILABLE_PRIORAREA"] = array();
    foreach($arResult["ITEMS"] as $arItem) {
        $category = $arItem["CATEGORY"];
        if(!isset($arResult["AVAILABLE_CATEGORIES"][$category])) {
            $arResult["AVAILABLE_CATEGORIES"][$category] = true;
        }

        foreach($arItem["PRIORAREA"] as $priorarea) {
            if(!isset($arResult["AVAILABLE_PRIORAREA"][$priorarea])) {
                $arResult["AVAILABLE_PRIORAREA"][$priorarea] = true;
            }
        }
    }
    $arResult["AVAILABLE_CATEGORIES"] = array_keys($arResult["AVAILABLE_CATEGORIES"]);
    $arResult["AVAILABLE_PRIORAREA"] = array_keys($arResult["AVAILABLE_PRIORAREA"]);

    sort($arResult["AVAILABLE_CATEGORIES"]);
    sort($arResult["AVAILABLE_PRIORAREA"]);

    $arResult["ITEMS_ID_BY_CATEGORY"] = array();
    foreach($arResult["ITEMS"] as $index => $arItem) {
        $category = $arItem["CATEGORY"];
	    if(isset($arResult["ITEMS_ID_BY_CATEGORY"][$category])) {
	        $arResult["ITEMS_ID_BY_CATEGORY"][$category][] = $index; //$arItem["ID"];
	    } else {
	        $arResult["ITEMS_ID_BY_CATEGORY"][$category] = array($index/*$arItem["ID"]*/);
	    }
    }
	global $USER;
	//сортируем массив по категориям
    ksort($arResult["ITEMS_ID_BY_CATEGORY"]);
	//сортируем по названию компаний участников
	$newCat = array();
	//$nameId = array();
	foreach($arResult["ITEMS_ID_BY_CATEGORY"] as $categoryName=>$arItemsId){
		foreach($arItemsId as $arItemId){
			$newCat[$categoryName][$arItemId] = $arResult["ITEMS"][$arItemId]["NAME"];
			//$nameId[$arItemId] = $arResult["ITEMS"][$arItemId]["NAME"];
		}
	}
	foreach($newCat as $arItemsId=>$categoryName){
		asort($categoryName);
		/*foreach($categoryName as $arItemId){
			$idComp[] = array_search($arItemId, $nameId);
		}
		$arResult["ITEMS_ID_BY_CATEGORY"][$arItemsId] = $idComp;*/
		$arResult["ITEMS_ID_BY_CATEGORY"][$arItemsId] = array_keys($categoryName);
		//$idComp = array();
	}

	$this->IncludeComponentTemplate();
}

function cmp($a, $b)
{
    $first = strtolower($a["NAME"]);
    $second = strtolower($b["NAME"]);
    if ($first == $second) {
        return 0;
    }
    return ($first < $second) ? -1 : 1;
}
?>
