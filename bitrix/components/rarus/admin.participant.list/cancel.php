<?
$arCancelUsersID = $_REQUEST["ACTION"];

foreach($arCancelUsersID as $userID)
{
	//�������� ������ ������������
    $arGroups = CUser::GetUserGroup($userID);

    $groupConfirmID = $arResult["EXHIBITION"]["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
    $groupUConfirmID = $arResult["EXHIBITION"]["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];

    if(!$groupConfirmID || !$groupUConfirmID)//����� ���� �� ������ ������ ����������
    {
    	break;
    }

    $needUpdate = false;
    foreach ($arGroups as $index => $group)//��������� ������������ �� ������ ���������������� �������������� � ��������������
    {
    	if($group == $groupConfirmID)
    	{
    		unset($arGroups[$index]);
    		$arGroups[] = $groupUConfirmID;
    		$needUpdate = true;
    	}
    }

    if($needUpdate)
    {
        CUser::SetUserGroup($userID, $arGroups);
    }
}

$url = $APPLICATION->GetCurUri();

LocalRedirect($url);

?>