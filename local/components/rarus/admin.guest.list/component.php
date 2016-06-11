<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB, $USER, $APPLICATION;
global $sortField, $sortOrder;

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

$sortField = $arResult["SORT"] = ($_REQUEST["sort"])?$_REQUEST["sort"]:"COMPANY";
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
	        array("ID", "NAME", "CODE", "PROPERTY_UC_GUESTS_GROUP", "PROPERTY_GUEST_SPAM_GROUP", "PROPERTY_C_GUESTS_GROUP"));
	if($ar = $rs->GetNext(true, false)) {
	    $arResult["EXHIB"] = $ar;
	}

	if(!isset($arResult["EXHIB"])) {
	    throw new Exception("Incorrect exhibbit");
	}

	switch($arParams["ACT"]) {
		case "off": $groupId = $arResult["EXHIB"]["PROPERTY_UC_GUESTS_GROUP_VALUE"]; break;
		case "spam": $groupId = $arResult["EXHIB"]["PROPERTY_GUEST_SPAM_GROUP_VALUE"]; break;
		case "morning":case "evening":case "hostbuy": $groupId = $arResult["EXHIB"]["PROPERTY_C_GUESTS_GROUP_VALUE"]; break;
		default: $groupId = false;
	}

    //список пользователей
    $arUserFilter = [
      "GROUPS_ID" => $groupId,
      "ACTIVE" => "Y"
    ];
    switch($arParams["ACT"]) {
    	case "morning": $arUserFilter["UF_MR"] = true; break;
    	case "evening": $arUserFilter["UF_EV"] = true; break;
    	case "hostbuy": $arUserFilter["UF_HB"] = true; break;
    }

    $userFieldFormAnswer = CFormMatrix::getPropertyIDByExh($arResult["EXHIB"]["ID"]);
    $arUserFilter["!$userFieldFormAnswer"] = false;

    $arUserListAll = array();
    $arUserFormAnswersId = array();

    $isFiltered = false;
    if(isset($_REQUEST["filter"])) {
        $arResult["FILTER"]["ID"] = $_REQUEST["ID"];
        $arResult["FILTER"]["COMPANY"] = $_REQUEST["COMPANY"];
        $arResult["FILTER"]["REP"] = $_REQUEST["REP"];
        $arResult["FILTER"]["PHONE"] = $_REQUEST["PHONE"];
        $arResult["FILTER"]["EMAIL"] = $_REQUEST["EMAIL"];
        $arResult["FILTER"]["DATE_REGISTER"] = $_REQUEST["DATE_REGISTER"];
        $arResult["FILTER"]["LOGIN"] = $_REQUEST["LOGIN"];

        if(!empty($arResult["FILTER"]["ID"])) {
            $arUserFilter["ID"] = $arResult["FILTER"]["ID"];
        }
        if(!empty($arResult["FILTER"]["LOGIN"])) {
            $arUserFilter["LOGIN"] = $arResult["FILTER"]["LOGIN"];
        }
        if(!empty($arResult["FILTER"]["EMAIL"]) || !empty($arResult["FILTER"]["COMPANY"]) || !empty($arResult["FILTER"]["REP"])
          || !empty($arResult["FILTER"]["PHONE"]) || !empty($arResult["FILTER"]["DATE_REGISTER"])) {
            $isFiltered = true;
        }
    }

    $rs = CUser::GetList(
      ($by="ID"),
      ($order="ASC"),
      $arUserFilter,
      array(
        "SELECT"=>array("UF_*"),
        "FIELDS"=>array("ID", "LOGIN", "DATE_REGISTER")
      )
    );
    while($ar = $rs->Fetch()) {
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
        $cmpField = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], 0);
        $phoneField = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], 10);
        $repNameField = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], 7);
        $repLNameField = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], 8);
        $emailField = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], 13);

        if(!$loginFieldId) throw new Exception("Incorrect login field id");

        $arFilterRes = [];

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

            $arResult["USERS_LIST"][$userLogin]["COMPANY"] = $arResult["USERS_LIST"][$userLogin][ $cmpField ];
            $arResult["USERS_LIST"][$userLogin]["REP"] = $arResult["USERS_LIST"][$userLogin][ $repNameField ]. " ".
                                                         $arResult["USERS_LIST"][$userLogin][ $repLNameField ];
            $arResult["USERS_LIST"][$userLogin]["PHONE"] = $arResult["USERS_LIST"][$userLogin][ $phoneField ];
            $arResult["USERS_LIST"][$userLogin]["EMAIL"] = $arResult["USERS_LIST"][$userLogin][ $emailField ];
            if($isFiltered) {
                $addToFilter = true;
                if(!empty($arResult["FILTER"]["COMPANY"]) &&
                  strpos($arResult["USERS_LIST"][$userLogin]["COMPANY"], $arResult["FILTER"]["COMPANY"]) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["REP"]) &&
                  strpos($arResult["USERS_LIST"][$userLogin]["REP"], $arResult["FILTER"]["REP"]) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["PHONE"]) &&
                  strpos($arResult["USERS_LIST"][$userLogin]["PHONE"], $arResult["FILTER"]["PHONE"]) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["EMAIL"]) &&
                  strpos($arResult["USERS_LIST"][$userLogin]["EMAIL"], $arResult["FILTER"]["EMAIL"]) === false) {
                    $addToFilter = false;
                }
                if($addToFilter) {
                    $arFilterRes[$userLogin] = $arResult["USERS_LIST"][$userLogin];
                }
            }
        }
    }

    if($isFiltered) {
        $arResult["USERS_LIST"] = $arFilterRes;
        unset($arFilterRes);
    }

    uasort($arResult["USERS_LIST"], "cmp");

    $res = new CDBResult;
    $res->InitFromArray($arResult["USERS_LIST"]);
    $res->NavStart(30); // разбиваем постранично по 30 записей
    $arResult["NAVIGATE"] = $res->GetPageNavStringEx($navComponentObject, "Пользователи", "");
    unset($arResult["USERS_LIST"]);
    while($ar = $res->Fetch()) {
        $arResult["USERS_LIST"][] = $ar;
    }

	$this->IncludeComponentTemplate();

function cmp($a, $b){
    global $sortField;
    global $sortOrder;

    $first = strtolower($a[$sortField]);
    $second = strtolower($b[$sortField]);
    if ($first == $second) {
        return 0;
    }
    $res = 1;
    if($sortOrder != "asc") $res = -1;
    return ($first < $second) ? -1*$res : 1*$res;
}

?>
