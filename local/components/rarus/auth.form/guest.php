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


$arResult["USER"] = $arUser;
$userResultID;
if($arParams["EXHIB_CODE"])
{
    // id результата заполнения формы гося
    $userResultID = $arUser["UF_ID_COMP"];
}

//получение выставок
$arFilter = array(
    "IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
    "ACTIVE" => "Y"
);

if(isset($arParams["EXHIB_CODE"]))
{
    $arFilter["CODE"] = $arParams["EXHIB_CODE"];
}

$arSelect = array(
    "ID",
    "CODE",
    "NAME",
    "IBLOCK_ID",
    "PROPERTY_*"
);

$UID = '';
if ($USER->isAdmin())
{
	$UID = 1;
}
else
{
	$UID = $arResult["USER"]['ID'];
}

/*
$arFilterMessages = array("USER_ID"=> $UID, "FOLDER_ID"=>FID);

$dbrMessages = CForumPrivateMessage::GetListEx(array(), $arFilterMessages);
$arResult['NEW_MESSAGES_COUNT'] = 0;
while($arMsg = $dbrMessages->GetNext())
{
	if ($arMsg['IS_READ'] == 'N')
	{
		$arResult['NEW_MESSAGES_COUNT']++;
	}
}
*/
$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);

while($obElement = $rsElement->GetNextElement())
{
    $arItem = $obElement->GetFields();
    $arItem["PROPERTIES"] = $obElement->GetProperties();

    $userExhibPropertyID = CFormMatrix::getPropertyIDByExh($arItem["ID"]);



    if(!$arUser[$userExhibPropertyID])
    {
    	continue;
    }

    $formID = GUEST_FORM_ID;//id формы регистрации
    $confirmedGroup = $arItem["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];

    //если пользователь в группе подтвержденных гостей
    if(in_array($confirmedGroup,$arUserGroups))
    {
        //id результата заполнения формы пользователя на текущую выставку
        $userResultID = $arUser[$userExhibPropertyID];
        $arResult["CONFIRMED"] = "Y";
    }
    else
    {
        $userResultID = $arUser["UF_ID_COMP"];
        $arResult["CONFIRMED"] = "N";
    }

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

        $arProfile["TYPE"] = "GUEST";
        $arProfile["EXIBITION"] = $arItem["ID"];

        $arProfile["NAME"] = $arUserAnswer[113][216]["USER_TEXT"];//FIELD_ID 113 , ANSWER_ID 216, TITLE => Имя

        $arProfile["LAST_NAME"] = $arUserAnswer[114][217]["USER_TEXT"];//FIELD_ID 114 , ANSWER_ID 217, TITLE => Фамилия

        $arProfile["PHOTO"] = $arUserAnswer[494][1312]["USER_FILE_ID"];//FIELD_ID 494 , ANSWER_ID 1312, TITLE => Фото

        if($arProfile["PHOTO"])
        {
            $arProfile["PHOTO"] = CFile::ResizeImageGet($arProfile["PHOTO"], array('width'=>108, 'height'=>108), BX_RESIZE_IMAGE_EXACT, true);
        }

        $arProfile["EDIT_LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/edit/profile/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");
        $arProfile["SCHEDULE"]["LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/morning/schedule/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");
        $arProfile["MESSAGES"] = array(
            "COUNT" => CHLMFunctions::GetMessagesCount(2, $arItem["ID"], $UID, 3),
            "LINK" => $arParams["PROFILE_URL"] . $arItem["CODE"] . "/messages/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:""),
        );
        $arProfile["EDIT_COLLEAGUE_LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/edit/colleague/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");
        $arProfile["PERSONAL_LINK"] = $arParams["PROFILE_URL"] . $arItem["CODE"] ."/" . (($USER->IsAdmin())?"?UID=". $arUser["ID"]:"");

        $arResult["PROFILE"] = $arProfile;
    }
}
?>