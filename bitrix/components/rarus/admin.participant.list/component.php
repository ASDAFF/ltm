<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

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
/*
$arParams["ACTIVE"] = trim($arParams["ACTIVE"]);
if($arParams["ACTIVE"] != "Y" && $arParams["ACTIVE"] != "N")
    $arParams["ACTIVE"] = "Y";
*/
$arParams["SPAM"] = trim($arParams["SPAM"]);
if($arParams["SPAM"] != "Y" && $arParams["SPAM"] != "N")
    $arParams["SPAM"] = "N";

$arParams["PATH_TO_KAB"] = trim($arParams["PATH_TO_KAB"]);
if(empty($arParams["PATH_TO_KAB"]))
    $arParams["PATH_TO_KAB"] = "/admin/";

$arParams["USER_TYPE"] = "participant";



//выполнение
$arResult = array();

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


        $rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $arFilter, $arParameters);
		$rsUsers->NavStart(30); // разбиваем постранично по 30 записей
		$arResult["NAVIGATE"] = $rsUsers->GetPageNavStringEx($navComponentObject, "Пользователи", "");

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
            $arItem["PARTICIPANT"][$arUser["ID"]] = $arUser;
        }

        uasort($arItem["PARTICIPANT"], "cmp"); //сортировка, в основном, для неподтвержденных, остальные при выборке сортитуются
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

function cmp($a, $b)
{
    $first = strtolower($a["WORK_COMPANY"]);
    $second = strtolower($b["WORK_COMPANY"]);
    if ($first == $second) {
        return 0;
    }
    return ($first < $second) ? -1 : 1;
}

$this->IncludeComponentTemplate();