<?
if("Y" == $_SESSION["ADMIN"] && isset($_REQUEST["UID"]) && intval($_REQUEST["UID"]))
{
	$rsUser = CUser::GetByID($_REQUEST["UID"]);
	$arUser = $rsUser->Fetch();
	$arUserGroups = CUser::GetUserGroup($_REQUEST["UID"]); //переписываем группы пользователя
}
else //если не админ получаем данные для текущего пользователя
{
    $rsUser = CUser::GetByID($userId);
	$arUser = $rsUser->Fetch();
}

$arResult["USER"] = $arUser;



?>