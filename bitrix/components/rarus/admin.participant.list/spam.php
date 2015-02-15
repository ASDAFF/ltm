<?
$arSpamUsersID = $_REQUEST["ACTION"];
$spamType = $_REQUEST["SPAM_TYPE"];

foreach($arSpamUsersID as $userID)
{
    $arGroups = CUser::GetUserGroup($userID);

    $groupSpamID = $arResult["EXHIBITION"]["PROPERTIES"]["PARTICIPANT_SPAM_GROUP"]["VALUE"];
    $groupConfirmID = $arResult["EXHIBITION"]["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
    $groupUConfirmID = $arResult["EXHIBITION"]["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];

    foreach ($arGroups as $index => $group)
    {
        if($group == $groupConfirmID || $group == $groupSpamID || $group == $groupUConfirmID)
        {
            unset($arGroups[$index]);
        }
    }

    if($spamType == "Y")//если добавляем в спам
    {
        $arGroups[] = $groupSpamID;
    }
    elseif($spamType == "N")//если вытаскиваем из спама
    {
        $arGroups[] = $groupUConfirmID;
    }
    else
    {
        break;
    }

    CUser::SetUserGroup($userID, $arGroups);

}

$url = $APPLICATION->GetCurPageParam("", array("ACTION","CONFIRM","EXHIBIT_CODE", "SPAM", "SPAM_TYPE"));

LocalRedirect($url);

?>