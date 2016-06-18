<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB, $USER, $APPLICATION;
global $sortField, $sortOrder;

//параметры
$arParams["EXHIB_IBLOCK_ID"] = intval($arParams["EXHIB_IBLOCK_ID"]);
if(!$arParams["EXHIB_IBLOCK_ID"])
    $arParams["EXHIB_IBLOCK_ID"] = 15;

$arParams["EXHIB_CODE"] = trim($_REQUEST["EXHIBIT_CODE"]);

$arParams["FORM_COMMON_ID"] = intval($arParams["FORM_COMMON_ID"]);
if(!$arParams["FORM_COMMON_ID"])
    $arParams["FORM_COMMON_ID"] = 3;

$arParams["CONFIRMED"] = trim($arParams["CONFIRMED"]);
if($arParams["CONFIRMED"] != "Y" && $arParams["CONFIRMED"] != "N")
    $arParams["CONFIRMED"] = "N";

$arParams["SPAM"] = trim($arParams["SPAM"]);
if($arParams["SPAM"] != "Y" && $arParams["SPAM"] != "N")
    $arParams["SPAM"] = "N";

$arParams["PATH_TO_KAB"] = trim($arParams["PATH_TO_KAB"]);
if(empty($arParams["PATH_TO_KAB"]))
    $arParams["PATH_TO_KAB"] = "/admin/";

$arParams["USER_TYPE"] = "participant";

//выполнение
$arResult = array();

$sortField = $arResult["SORT"] = ($_REQUEST["sort"])?$_REQUEST["sort"]:"COMPANY";
$sortOrder = $arResult["ORDER"]  = ($_REQUEST["order"])?$_REQUEST["order"]:"asc";

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
{
    throw new Exception("Can't load modules iblock form");
}

//получение выставок
$arFilter = array(
	"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
    "CODE" => $arParams["EXHIB_CODE"],
    //"ACTIVE" => "Y"
);
$arSelect = array(
	"ID",
    "CODE",
    "NAME",
    "IBLOCK_ID",
    "PROPERTY_*"
);

$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);
while($obElement = $rsElement->GetNextElement())
{

    $arItem = $obElement->GetFields();
    $arItem["PROPERTIES"] = $obElement->GetProperties();

    //получение ид свойства пользователя в котором хранится результат заполнения формы участника
    $userExhibPropertyID = CFormMatrix::getPropertyIDByExh($arItem["ID"]);

    //получение (личных) данных участника
    $formID="";
    if("Y" == $arParams["CONFIRMED"])
    {
        $formID = CFormMatrix::getPFormIDByExh($arItem["ID"]);
    }
    else
    {
        $formID = 4;//представители москва весна (туда все падают)
    }


    //получение данных о группе пользователей
    $arGroupsPropName = array("USER_GROUP_ID", "UC_PARTICIPANTS_GROUP", "PARTICIPANT_SPAM_GROUP");

    foreach ($arGroupsPropName as $propName)
    {
    	if(intval($arItem["PROPERTIES"][$propName]["VALUE"]) > 0)
    	{
    	    $rsGroup = CGroup::GetByID($arItem["PROPERTIES"][$propName]["VALUE"], "Y");
    	    $arGroup = $rsGroup->Fetch();
    	    $arItem["GROUPS"][$arGroup["ID"]] = $arGroup;
    	}
    }



    if($arParams["CONFIRMED"] == "Y")
    {
        $participantGroupID = array($arItem["PROPERTIES"]["USER_GROUP_ID"]["VALUE"]);
    }
    elseif($arParams["CONFIRMED"] == "N")
    {
        $participantGroupID = array($arItem["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"]);
    }

    if($arParams["SPAM"] == "Y")
    {
         $participantGroupID = array($arItem["PROPERTIES"]["PARTICIPANT_SPAM_GROUP"]["VALUE"]);
    }

    if($participantGroupID)
    {
        $arFilter = array(
            "GROUPS_ID" => $participantGroupID,
            "ACTIVE" => "Y"
        );

        $arParameters = array(
            "FIELDS" => array("ID", "LOGIN","PASSWORD", "EMAIL", "WORK_COMPANY"),
            "SELECT" => array("UF_*")
            //UF_ID - москва осень, UF_ID2 - баку, UF_ID3 - киев, UF_ID4 - алмата, UF_ID5 - москва осень, UF_ID_COMP - Участники данные компании ВСЕ ВЫСТАВКИ
        );

        $isFiltered = false;
        if(isset($_REQUEST["filter"])) {
            $arResult["FILTER"]["ID"] = $_REQUEST["ID"];
            $arResult["FILTER"]["COMPANY"] = $_REQUEST["COMPANY"];
            $arResult["FILTER"]["REP"] = $_REQUEST["REP"];
            $arResult["FILTER"]["PHONE"] = $_REQUEST["PHONE"];
            $arResult["FILTER"]["EMAIL"] = $_REQUEST["EMAIL"];
            $arResult["FILTER"]["BUSINESS"] = $_REQUEST["BUSINESS"];
            $arResult["FILTER"]["LOGIN"] = $_REQUEST["LOGIN"];

            if(!empty($arResult["FILTER"]["ID"])) {
                $arFilter["ID"] = $arResult["FILTER"]["ID"];
            }
            if(!empty($arResult["FILTER"]["LOGIN"])) {
                $arFilter["LOGIN"] = $arResult["FILTER"]["LOGIN"];
            }
            if(!empty($arResult["FILTER"]["EMAIL"]) || !empty($arResult["FILTER"]["COMPANY"]) || !empty($arResult["FILTER"]["REP"])
              || !empty($arResult["FILTER"]["PHONE"]) || !empty($arResult["FILTER"]["BUSINESS"])) {
                $isFiltered = true;
            }
        }

        $rsUsers = CUser::GetList(($by=$arResult["SORT"]), ($order=$arResult["ORDER"]), $arFilter, $arParameters);

        while($arUser = $rsUsers->Fetch())
        {
            $arUsers[$arUser["ID"]] = $arUser;
            //массив результатов заоплнения форм компаний и профиля
            $arCompanyResultID[] = $arUser["UF_ID_COMP"];

            if("Y" == $arParams["CONFIRMED"])
            {
                $arUserResultID[] = $arUser[$userExhibPropertyID];//id результата
            }

            if("N" == $arParams["CONFIRMED"] || !$formResultID)
                $arUserResultID[] = $arUser["UF_ID"];//id результата из общих
        }

        //получение резульататов заполнения формы компании
        //Получение ответов формы Участники данные компании ВСЕ ВЫСТАВКИ
        $arResult["FORM_RESULT_COMMON"] = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array());

        CForm::GetResultAnswerArray(
        $arParams["FORM_COMMON_ID"],
        $arResult["FORM_RESULT_COMMON"]["QUESTIONS"],
        $arResult["FORM_RESULT_COMMON"]["ANSWERS"],
        $arResult["FORM_RESULT_COMMON"]["ANSWERS2"],
        array("RESULT_ID" => implode("|", $arCompanyResultID))
        );


        //получение ответов формы Представители
        $arResult["FORM_RESULT_USERS"] = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array());

        CForm::GetResultAnswerArray(
        $formID,
        $arResult["FORM_RESULT_USERS"]["QUESTIONS"],
        $arResult["FORM_RESULT_USERS"]["ANSWERS"],
        $arResult["FORM_RESULT_USERS"]["ANSWERS2"],
        array("RESULT_ID" => implode("|", $arUserResultID))
        );

        $cmpField = CFormMatrix::$arExelCompParticipantField["QUEST_ID"][0];
        $businessField = CFormMatrix::$arExelCompParticipantField["QUEST_ID"][1];
        $phoneField = CFormMatrix::getQIDByBase(36, $formID);
        $repNameField = CFormMatrix::getQIDByBase(32, $formID);
        $repLNameField = CFormMatrix::getQIDByBase(33, $formID);
        $emailField = CFormMatrix::getQIDByBase(37, $formID);

        $arFilterRes = [];
        foreach ($arUsers as $arUser)
        {
            $formResultCommon = $arUser["UF_ID_COMP"];
            if($formResultCommon)
            {
                $result = $arResult["FORM_RESULT_COMMON"]["ANSWERS"][$formResultCommon];

                foreach ($result as $answerID => $answer)//проход по ответам
                {

                    $optionCount = count($answer);
                    foreach ($answer as $option)//проход по ответам
                    {
                        //определегние названия свойсвта
                        $propAnswer = "";
                        switch ($option["FIELD_TYPE"])
                        {
                        	case "dropdown" :
                        	case "checkbox" : $propAnswer = "ANSWER_TEXT"; break;
                        	case "text" : $propAnswer = "USER_TEXT"; break;
                        	case "image" : $propAnswer = "USER_FILE_ID"; break;
                        	default: $propAnswer = "USER_TEXT";
                        }

                        if($optionCount > 1)
                        {
                            $arUser["FORM_DATA"][$answerID]["FIELD_TYPE"] = $option["FIELD_TYPE"];
                            $arUser["FORM_DATA"][$answerID]["QUESTIONS"] = $option["TITLE"];
                            $arUser["FORM_DATA"][$answerID]["VALUE"][$option["ANSWER_ID"]] = $option[$propAnswer];
                            $arUser["FORM_DATA"][$answerID]["ANSWER_ID"] = $option["ANSWER_ID"];
                            $arUser["FORM_DATA"][$answerID]["ANSWER_ID"] = $option["SID"];
                        }
                        else
                        {
                            $arUser["FORM_DATA"][$answerID] = array(
                                "FIELD_TYPE" => $option["FIELD_TYPE"],
                                "QUESTIONS" => $option["TITLE"],
                                "VALUE" => $option[$propAnswer],
                                "ANSWER_ID" => $option["ANSWER_ID"],
                                "SID" => $option["SID"],
                            );
                        }
                    }
                }
            }

            //получение данных участника
            $formResultID="";
            if("Y" == $arParams["CONFIRMED"])
            {
                $formResultID = $arUser[$userExhibPropertyID];//id результата
            }

            if("N" == $arParams["CONFIRMED"] || !$formResultID)
                $formResultID = $arUser["UF_ID"];//id результата из общих


            if($formResultID)
            {
                $result = $arResult["FORM_RESULT_USERS"]["ANSWERS"][$formResultID];

                foreach ($result as $answerID => $answer)//проход по ответам
                {
                    $optionCount = count($answer);
                    foreach ($answer as $option)//проход по ответам
                    {
                        //pre($option);
                        //определегние названия свойсвта
                        $propAnswer = "";
                        switch ($option["FIELD_TYPE"])
                        {
                        	case "dropdown" :
                        	case "checkbox" : $propAnswer = "ANSWER_TEXT"; break;
                        	case "text" : $propAnswer = "USER_TEXT"; break;
                        	case "image" : $propAnswer = "USER_FILE_ID"; break;
                        	default: $propAnswer = "USER_TEXT";
                        }

                        if($optionCount > 1)
                        {
                            $arUser["FORM_USER"][$answerID]["FIELD_TYPE"] = $option["FIELD_TYPE"];
                            $arUser["FORM_USER"][$answerID]["QUESTIONS"] = $option["TITLE"];
                            $arUser["FORM_USER"][$answerID]["VALUE"][$option["ANSWER_ID"]] = $option[$propAnswer];
                            $arUser["FORM_USER"][$answerID]["ANSWER_ID"] = $option["ANSWER_ID"];
                            $arUser["FORM_USER"][$answerID]["SID"] = $option["SID"];
                        }
                        else
                        {
                            $arUser["FORM_USER"][$answerID] = array("FIELD_TYPE" => $option["FIELD_TYPE"],
                                "QUESTIONS" => $option["TITLE"],
                                "FIELD_TYPE" => $option["FIELD_TYPE"],
                                "VALUE" => $option[$propAnswer],
                                "ANSWER_ID" => $option["ANSWER_ID"],
                                "SID" => $option["SID"]
                            );
                        }
                    }
                }
            }
            $arUser["COMPANY"] = $arUser["FORM_DATA"][ $cmpField ]["VALUE"];
            $arUser["BUSINESS"] = $arUser["FORM_DATA"][ $businessField ]["VALUE"];
            $arUser["REP"] = $arUser["FORM_USER"][ $repNameField ]["VALUE"]. " ".
                            $arUser["FORM_USER"][ $repLNameField ]["VALUE"];
            $arUser["PHONE"] = $arUser["FORM_USER"][ $phoneField ]["VALUE"];
            $arUser["EMAIL"] = $arUser["FORM_USER"][ $emailField ]["VALUE"];

            $arItem["PARTICIPANT"][$arUser["ID"]] = $arUser;

            if($isFiltered) {
                $addToFilter = true;
                if(!empty($arResult["FILTER"]["COMPANY"]) &&
                  strpos(strtolower($arUser["COMPANY"]), strtolower($arResult["FILTER"]["COMPANY"])) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["BUSINESS"]) &&
                  strpos(strtolower($arUser["BUSINESS"]), strtolower($arResult["FILTER"]["BUSINESS"])) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["REP"]) &&
                  strpos(strtolower($arUser["REP"]), strtolower($arResult["FILTER"]["REP"])) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["PHONE"]) &&
                  strpos(strtolower($arUser["PHONE"]), strtolower($arResult["FILTER"]["PHONE"])) === false) {
                    $addToFilter = false;
                }
                if(!empty($arResult["FILTER"]["EMAIL"]) &&
                  strpos(strtolower($arUser["EMAIL"]), strtolower($arResult["FILTER"]["EMAIL"])) === false) {
                    $addToFilter = false;
                }
                if($addToFilter) {
                    $arFilterRes[ $arUser["ID"] ] = $arUser;
                }
            }
        }
        if($isFiltered) {
            $arItem["PARTICIPANT"] = $arFilterRes;
            unset($arFilterRes);
        }

        uasort($arItem["PARTICIPANT"], "cmp"); //сортировка, в основном, для неподтвержденных, остальные при выборке сортитуются
        $res = new CDBResult;
        $res->InitFromArray($arItem["PARTICIPANT"]);
        $res->NavStart(30); // разбиваем постранично по 30 записей
        $arResult["NAVIGATE"] = $res->GetPageNavStringEx($navComponentObject, "Пользователи", "");
        unset($arItem["PARTICIPANT"]);
        while($ar = $res->Fetch()) {
            $arItem["PARTICIPANT"][ $ar["ID"] ] = $ar;
        }
    }

    $arResult["EXHIBITION"] = $arItem;
}


if($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET")//если происходят действия с пользователем
{
	if(isset($_REQUEST["CONFIRM"]))
	{
		require_once ("confirm.php");
	}
	elseif (isset($_REQUEST["CANCEL"]))
	{
	    require_once ("cancel.php");
	}
	elseif (isset($_REQUEST["SPAM"]))
	{
	    require_once ("spam.php");
	}
}

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

$this->IncludeComponentTemplate();