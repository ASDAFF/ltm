<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//В параметрах сделать ссылку на страницу Неподтвержденных госте
//В параметрах сделать ссылку на страницу Неподтвержденных участников
//В параметрах сделать ссылки на страницу Неоплативших (хотя может и не нужно)
//Добавить про сообщения


$arResult["ERROR_MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["GUEST"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Гостям!<br />";
}

if(strLen($arParams["GUEST_ACCEPT"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Гостям на утро!<br />";
}

if(strLen($arParams["GUEST_EVENING"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Гостям на вечер!<br />";
}

if(strLen($arParams["GUEST_HB"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Гостям hosted buyers!<br />";
}

if(strLen($arParams["PARTICIP"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Участникам!<br />";
}

if(strLen($arParams["PARTICIP_ACCEPT"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Участникам!<br />";
}

/*
if(strLen($arParams["MESSAGE"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Сообщениям!<br />";
}
*/

if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}
elseif($arResult["ERROR_MESSAGE"] == '')
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		//ГОСТИ
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["GUEST"])
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); // выбираем пользователей
		$usersCount = $rsUsers->SelectedRowsCount();
		$arResult["GUEST"]["COUNT"] = $usersCount;
		$arResult["GUEST"]["LINK"] = $arParams["PATH_TO_KAB"]."guest/off/";
		
		//УЧАСТНИКИ
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["PARTICIP"])
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); // выбираем пользователей
		$usersCount = $rsUsers->SelectedRowsCount();
		$arResult["PARTICIP"]["COUNT"] = $usersCount;
		$arResult["PARTICIP"]["LINK"] = $arParams["PATH_TO_KAB"]."particip/off/";
		
		//НЕОПЛАТИВШИЕ УЧАСТНИКИ
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["PARTICIP_ACCEPT"]),
			"UF_PAY" => ""
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); // выбираем пользователей
		$usersCount = $rsUsers->SelectedRowsCount();
		$arResult["PAY"]["COUNT"] = $usersCount;
		$arResult["PAY"]["LINK"] = $arParams["PATH_TO_KAB"]."particip/on/";
		
		//ГОСТИ утро
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["GUEST_ACCEPT"])
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); // выбираем пользователей
		$usersCount = $rsUsers->SelectedRowsCount();
		$arResult["GUEST_MORNING"]["COUNT"] = $usersCount;
		$arResult["GUEST_MORNING"]["LINK"] = $arParams["PATH_TO_KAB"]."guest/on/";
		
		//ГОСТИ вечер
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["GUEST_EVENING"])
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); // выбираем пользователей
		$usersCount = $rsUsers->SelectedRowsCount();
		$arResult["GUEST_EVENING"]["COUNT"] = $usersCount;
		$arResult["GUEST_EVENING"]["LINK"] = $arParams["PATH_TO_KAB"]."guest/evening/";
		
		//ГОСТИ HB
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["GUEST_HB"])
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); // выбираем пользователей
		$usersCount = $rsUsers->SelectedRowsCount();
		$arResult["GUEST_HB"]["COUNT"] = $usersCount;
		$arResult["GUEST_HB"]["LINK"] = $arParams["PATH_TO_KAB"]."guest/hostbuy/";
		
		
	}
	else{
		$arResult["ERROR_MESSAGE"] = "У вас недостаточно прав для просмотра данной страницы!";
	}
}

//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>