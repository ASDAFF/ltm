<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;
global $sortField;
global $sortOrder;

isset($arParams["CACHE_TIME"]) or $arParams["CACHE_TIME"] = 3600;
isset($arParams["IBLOCK_ID_EXHIB"]) or $arParams["IBLOCK_ID_EXHIB"] = 15;
isset($arParams["ACT"]) or $arParams["ACT"] = $_REQUEST["ACT"];//off morning evening hostbuy
isset($arParams["EXHIBIT_CODE"]) or $arParams["EXHIBIT_CODE"] = $_REQUEST["EXHIBIT_CODE"];
isset($arParams["GUEST_FORM_ID"]) or $arParams["GUEST_FORM_ID"] = 10;

switch($arParams["ACT"]) {
	case "spam":case "off": case "morning":case "evening":case "hostbuy": break;
	default: throw new Exception("Incorrect ACT");
}

$arResult = array();

$sortField = $arResult["SORT"] = ($_REQUEST["sort"])?$_REQUEST["sort"]:"COMPANY_NAME";
$sortOrder = $arResult["ORDER"]  = ($_REQUEST["order"])?$_REQUEST["order"]:"asc";

	if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
	{
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock form");
	}

	//получаем данные по выставке
	$rs = CIBlockElement::GetList(array("SORT"=>"ASC"),
	        array("IBLOCK_ID"=>intval($arParams["IBLOCK_ID_EXHIB"]), "CODE"=>$arParams["EXHIBIT_CODE"]/*, "ACTIVE"=>"Y"*/),
	        false, array("nTopCount"=>1),
	        array());
	if($ar = $rs->GetNext(true, false)) {
	    if($rsProp = CIBlockElement::GetProperty($ar["IBLOCK_ID"], $ar["ID"], array(), array())) {
	        while($arProp = $rsProp->Fetch()) {
	            $ar["PROPERTIES"][$arProp["CODE"]] = $arProp;
	        }
	    }
	    $arResult["EXHIB"] = $ar;
	}

	if(!isset($arResult["EXHIB"])) {
	    throw new Exception("Incorrect exhibbit");
	}

	switch($arParams["ACT"]) {
		case "off": $groupId = $arResult["EXHIB"]["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"]; break;
		case "spam": $groupId = $arResult["EXHIB"]["PROPERTIES"]["GUEST_SPAM_GROUP"]["VALUE"]; break;
		case "morning":case "evening":case "hostbuy": $groupId = $arResult["EXHIB"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"]; break;
		default: $groupId = false;
	}

    //список пользователей
    $arUserFilter = array("GROUPS_ID"=>$groupId, "ACTIVE"=>"Y");
    switch($arParams["ACT"]) {
    	case "morning": $arUserFilter["UF_MR"] = true; break;
    	case "evening": $arUserFilter["UF_EV"] = true; break;
    	case "hostbuy": $arUserFilter["UF_HB"] = true; break;
    }

    $userFieldFormAnswer = CFormMatrix::getPropertyIDByExh($arResult["EXHIB"]["ID"]);
    $arUserFilter["!$userFieldFormAnswer"] = false;


    $arUserListAll = array();
    $arUserFormAnswersId = array();
    if(!$arResult["SORT"]) $arResult["SORT"] = "work_company";

    $rs = CUser::GetList(($by=$arResult["SORT"]), ($order=$arResult["ORDER"]), $arUserFilter, array("SELECT"=>array("UF_*"), "FIELDS"=>array("ID", "LOGIN", "DATE_REGISTER")));
	$rs->NavStart(30); // разбиваем постранично по 30 записей
	$arResult["NAVIGATE"] = $rs->GetPageNavStringEx($navComponentObject, "Пользователи", "");

    while($ar = $rs->GetNext(true, false)) {
        $rsResult = CFormResult::GetByID($ar[$userFieldFormAnswer]);
        $tmpResFields = $rsResult->Fetch();
        $ar["REG_DATE"]= $ar["DATE_REGISTER"];
        $tmpTime = strtotime($ar["REG_DATE"]);
        $diff = (time() - $tmpTime)/60;//разница в минутах
        $ar["REG_DIFF"] = floor($diff/60)."ч ".($diff% 60)."м";

    	$arUserListAll[ $ar["LOGIN"] ] = $ar;
    	$arUserFormAnswersId[ $ar["LOGIN"] ] = $ar[$userFieldFormAnswer];
    }
    $arResult["USERS_LIST"] = $arUserListAll;
    $arUserFormAnswersLoginById = array_flip($arUserFormAnswersId);

     if(empty($arUserListAll)) {
         echo ("There are 0 users.");
     }

    if(!empty($arResult["USERS_LIST"])) {
        //список результатов ответов в форме
        $arAnswersFilter = array("RESULT_ID"=>implode("|", $arUserFormAnswersId));

        $arAnswers = array();
        CForm::GetResultAnswerArray(
            $arParams["GUEST_FORM_ID"],
            $arResult["GUEST_FORM_QUESTIONS"],
            $arAnswers,
            ($b = false),
            $arAnswersFilter);

        $loginFieldId = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], "LOGIN");



        if(!$loginFieldId) throw new Exception("Incorrect login field id");


        foreach($arAnswers as $key=>$arAnswer) {
            $ar = array();
            foreach($arAnswer as $keyQuestionAnswer=>$arQuestionAnswer) {
                $arCountElems = count($arQuestionAnswer);

                if($arCountElems > 1) {
                    $ar[$keyQuestionAnswer] = array();
                }
                foreach($arQuestionAnswer as $arEntityAnswer) {
                    switch($arEntityAnswer["FIELD_TYPE"]) {
                    	case "checkbox":  $v = (isset($arEntityAnswer["ANSWER_TEXT"]) && trim($arEntityAnswer["ANSWER_TEXT"])) ? $arEntityAnswer["ANSWER_TEXT"] : $arEntityAnswer["TITLE"]; break;
                    	case "dropdown": $v = $arEntityAnswer["ANSWER_TEXT"]; break;
                    	default: $v = $arEntityAnswer["USER_TEXT"];
                    }

                    if($arCountElems > 1) {
                        $ar[$keyQuestionAnswer][] = $v;
                    } else {
                        $ar[$keyQuestionAnswer] = $v;
                    }
                }
            }

            //pre($ar);

            //добавляем к инфо пользователя
            $userLogin = $arUserFormAnswersLoginById[$key];
            $arResult["USERS_LIST"][$userLogin]["FORM_RESULT_ID"] = $key;
            if(isset($arResult["USERS_LIST"][$userLogin])) {
                foreach($ar as $key=>$val) {
                    $arResult["USERS_LIST"][$userLogin][$key] = $val;
                }
            }
        }
    }

    uasort($arResult["USERS_LIST"], "cmp");

	$this->IncludeComponentTemplate();

function cmp($a, $b){
    global $sortField;
    global $sortOrder;

    $sortFileldTmp = "ID";
    switch ($sortField){
        case "BUSINESS":
        case "CITY":
        case "COUNTRY":
        case "REP":
        case "SKYPE":
        case "TABLE":
        case "HALL":
        case "COUNT":
            break;
        default:
            $sortFileldTmp = $sortField;
            break;
    };

    $first = strtolower($a[$sortFileldTmp]);
    $second = strtolower($b[$sortFileldTmp]);
    if ($first == $second) {
        return 0;
    }
    $res = 1;
    if($sortOrder != "asc") $res = -1;
    return ($first < $second) ? -1*$res : 1*$res;


}

?>
