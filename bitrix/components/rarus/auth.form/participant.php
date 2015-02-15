<?
CModule::IncludeModule("form");
CModule::IncludeModule("iblock");
CModule::IncludeModule("forum");
define('FID', 1);

if($USER->IsAdmin() && isset($_REQUEST["UID"]) && intval($_REQUEST["UID"]))
{
	$rsUser = CUser::GetByID($_REQUEST["UID"]);
	$arUser = $rsUser->Fetch();
	$arUserGroups = CUser::GetUserGroup($_REQUEST["UID"]); //переписываем группы пользователя
}
else //если не админ получаем данные для текущего пользователя
{
	$arUserGroups = CUser::GetUserGroup($userId);
    $rsUser = CUser::GetByID($userId);
	$arUser = $rsUser->Fetch();
}
$UID = '';
$arResult["USER"] = $arUser;
if ($USER->isAdmin())
{
	$UID = 1;
}
else
{
	$UID = $arResult["USER"]['ID'];
}

/*$arFilterMessages = array("USER_ID"=> $UID, "FOLDER_ID"=>FID);
$dbrMessages = CForumPrivateMessage::GetListEx(array(), $arFilterMessages);
$arResult['NEW_MESSAGES_COUNT'] = 0;
while($arMsg = $dbrMessages->GetNext())
{
	if ($arMsg['IS_READ'] == 'N')
	{
		$arResult['NEW_MESSAGES_COUNT']++;
	}
}*/

// id результата заполнения формы компании
$companyResultID = $arUser["UF_ID_COMP"];


//получение результата заполнени формы о компании
$arResultAnswerCompany = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array(), "ANSWERS2"=>array());


CForm::GetResultAnswerArray(
    COMPANY_FORM_ID,
    $arResultAnswerCompany["QUESTIONS"],
    $arResultAnswerCompany["ANSWERS"],
    $arResultAnswerCompany["ANSWERS2"],
    array("RESULT_ID" => $companyResultID)
);


//получение выставок
$arFilter = array(
    "IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
    "ACTIVE" => "Y"
);
$arSelect = array(
    "ID",
    "CODE",
    "NAME",
    "IBLOCK_ID",
    "PROPERTY_*"
);

$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);
$first = true;
while($obElement = $rsElement->GetNextElement())
{

    $arItem = $obElement->GetFields();
    $arItem["PROPERTIES"] = $obElement->GetProperties();

    $confirmedGroup = $arItem["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];

    //получение ид свойства пользователя в котором хранится результат заполнения формы участника
    $userExhibPropertyID = CFormMatrix::getPropertyIDByExh($arItem["ID"]);

    $formID = CFormMatrix::getPFormIDByExh($arItem["ID"]);

    //id результата заполнения формы пользователя на текущую выставку
    $userResultID = $arUser[$userExhibPropertyID];

   // pre($confirmedGroup, "amanda1876");
    //pre($userResultID, "amanda1876");
    if($userResultID)
    {
        //получение результата заполнени формы пользователя
        $arResultAnswerUser = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array(), "ANSWERS2"=>array());

        CForm::GetResultAnswerArray(
        $formID,
        $arResultAnswerUser["QUESTIONS"],
        $arResultAnswerUser["ANSWERS"],
        $arResultAnswerUser["ANSWERS2"],
        array("RESULT_ID" => $userResultID)
        );

        $arProfile = array();
        $arUserAnswer = $arResultAnswerUser["ANSWERS"][$userResultID];

        //заполнение данных профиля

        $arProfile["TYPE"] = "PARTICIPANT";

        $nameQuestionsId = CFormMatrix::getQIDByBase(32, $formID);//32 Participant first name
        $nameAnswerId = CFormMatrix::getAnswerRelBase(84, $formID);// 84 Participant first name
        $arProfile["NAME"] = $arUserAnswer[$nameQuestionsId][$nameAnswerId]["USER_TEXT"];

        $lastNameQuestionsId = CFormMatrix::getQIDByBase(33, $formID);//33 Participant first name
        $lastNameAnswerId = CFormMatrix::getAnswerRelBase(85, $formID);//85 Participant last name
        $arProfile["LAST_NAME"] = $arUserAnswer[$lastNameQuestionsId][$lastNameAnswerId]["USER_TEXT"];

        $photoQuestionsId = CFormMatrix::getQIDByBase(101, $formID);//101 Participant first name
        $photoAnswerId = CFormMatrix::getAnswerRelBase(195, $formID);//Персональное фото
        $arProfile["PHOTO"] = $arUserAnswer[$photoQuestionsId][$photoAnswerId]["USER_FILE_ID"];

        if($arProfile["PHOTO"])
        {
            $arProfile["PHOTO"] = CFile::ResizeImageGet($arProfile["PHOTO"], array('width'=>108, 'height'=>108), BX_RESIZE_IMAGE_EXACT, true);
        }

        $arProfile["COMPANY_NAME"] = $arResultAnswerCompany["ANSWERS"][$companyResultID][17][30]["USER_TEXT"]; //17 (field id), 30 (answer id) Company or hotel name
        $arProfile["COMPANY_LINK"] = "/members/".$companyResultID . "/";

        $arProfile["EDIT_LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/edit/profile/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");
        $arProfile["MESSAGE_LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/messages/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");
        $arProfile["EDIT_COMPANY_LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/edit/company/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");
        $arProfile["PERSONAL_LINK"] = $arParams["PROFILE_URL"] . "" . $arItem["CODE"] . "/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");



        if($first && in_array($confirmedGroup, $arUserGroups))//если пользователь подтвержден на выставку беремпервую попавшуюся
        {
            $arResult["PROFILE"] = $arProfile;
            $first = false;
        }
        elseif(!isset($arResult["PROFILE"]))//запоминаем профиль с первой попавшейся выставки (для отображения профиля по умолчанию)
        {
            $arResult["PROFILE"] = $arProfile;
        }
        elseif($arItem["CODE"] == $arParams["EXHIB_CODE"])//если в параметрах есть код выставки переопределяем данные для этой выставки
        {
            $arResult["PROFILE"] = $arProfile;
        }
    }

    $arExhib = array();
    $arExhib["STATUS"] = $arItem["PROPERTIES"]["STATUS"]["VALUE"];
    $arExhib["STATUS_G_M"] = $arItem["PROPERTIES"]["STATUS_G_M"]["VALUE"];
    $arExhib["STATUS_G_E"] = $arItem["PROPERTIES"]["STATUS_G_E"]["VALUE"];
    //если пользователь зарегистрирован на эту выставку
    if(in_array($confirmedGroup, $arUserGroups))
    {

        if($arItem["CODE"] == $arParams["EXHIB_CODE"])
        {
            $arExhib["SELECTED"] = "Y";
        }
        $arExhib["ID"] = $arItem["ID"];
        //$arExhib["PROPERTIES"] = $arItem["PROPERTIES"];

        $arExhib["MESSAGES"] = array(
            "COUNT" => CHLMFunctions::GetMessagesCount(2, $arExhib["ID"], $UID, 3),
            "LINK" => $arParams["PROFILE_URL"] . "" . $arItem["CODE"] . "/messages/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"")
        );

        $arExhib["WISHLIST"] = array(
            "LINK" => $arParams["PROFILE_URL"] . "" . $arItem["CODE"] . "/morning/shedule/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"")
        );

        $arExhib["SCHEDULE"] = array(
            "LINK" => $arParams["PROFILE_URL"] . "" . $arItem["CODE"] . "/morning/shedule/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"")
        );

        $arExhib["EDIT"] = array(
            "LINK" => $arParams["PROFILE_URL"] . "" . $arItem["CODE"] . "/edit/colleague/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"")
        );

        $arExhib["EXH_NAME"] = $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"];
        $arExhib["NAME"] = (LANGUAGE_ID == "ru")?$arItem["NAME"]:$arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];

        $arResult["EXHIBITION"]["CONFIRMED"][] = $arExhib;
    }
    else//если не зарегистрирован
    {
        $arExhib["ID"] = $arItem["ID"];
        //$arExhib["PROPERTIES"] = $arItem["PROPERTIES"];

        $arExhib["EXH_NAME"] = $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"];
        $arExhib["NAME"] = (LANGUAGE_ID == "ru")?$arItem["NAME"]:$arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];

        $arResult["EXHIBITION"]["UNCONFIRMED"][] = $arExhib;
    }
}
?>