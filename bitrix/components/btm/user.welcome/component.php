<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить формы гостей и участников


$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["GUEST_URL"])<=0){
	$arParams["GUEST_URL"] = "/ru/particip/";
}

if(strLen($arParams["PARTICIP_URL"])<=0){
	$arParams["PARTICIP_URL"] = "/particip/";
}

if(strLen($arParams["ADMIN_URL"])<=0){
	$arParams["ADMIN_URL"] = "/admin/";
}

if(strLen($arParams["GUEST_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе гостя!<br />";
}

if(strLen($arParams["PARTICIP_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе участника!<br />";
}

if(strLen($arParams["ADMIN_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе администратора!<br />";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["USER_GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по группе пользователя!<br />";
}

if(strLen($arParams["USER_FORM"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по Результатам пользователей!<br />";
}

if(!($USER->IsAuthorized()))
{
	if($arParams["USER_TYPE"] == "PARTICIP"){
		LocalRedirect($arParams["PARTICIP_URL"]."login.php");
	}	
	elseif($arParams["USER_TYPE"] == "GUEST"){
		LocalRedirect($arParams["GUEST_URL"]."login.php");
	}	
	else{
		LocalRedirect($arParams["ADMIN_URL"]."login.php");
	}	
}
if($arResult["ERROR_MESSAGE"] == '')
{
	$userId = $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	$rsUser = CUser::GetByID($userId);
	$arUser = $rsUser->Fetch();
	$arResult["USER"]["ID"] = $arUser["ID"];
	$arResult["USER"]["UF_ANKETA"] = $arUser["UF_ANKETA"];
	$arResult["USER"]["NAME"] = $arUser["NAME"];
	$arResult["USER"]["LAST_NAME"] = $arUser["LAST_NAME"];
	$arResult["USER"]["MEETINGS"] = ($arUser["UF_MEETING"] == '') ? "0" : $arUser["UF_MEETING"];
	$arResult["USER"]["MESSAGES"] = ($arUser["UF_MESSAGE"] == '') ? "0" : $arUser["UF_MESSAGE"];
	$arResult["USER"]["LOGOUT"] = '';
	/*
	if(($arParams["USER_TYPE"] == "PARTICIP" && !in_array($arParams["PARTICIP_GROUP"], $userGroups)) || ($arParams["USER_TYPE"] == "GUEST" && !in_array($arParams["GUEST_GROUP"], $userGroups)) || !in_array($arParams["ADMIN_GROUP"], $userGroups)){
		if(in_array($arParams["GUEST_GROUP"], $userGroups)){
			LocalRedirect($arParams["GUEST_URL"].);
		}
		elseif(in_array($arParams["ADMIN_GROUP"], $userGroups)){
			LocalRedirect($arParams["ADMIN_URL"].);
		}
		else{
			LocalRedirect($arParams["PARTICIP_URL"].);
		}
	}*/
	if(in_array($arParams["PARTICIP_GROUP"], $userGroups)){
		$arResult["USER"]["TITLE"] = '';
		$arResult["WELCOME"] = 'Welcome, ';
		$arResult["USER"]["LOGOUT"] = $arParams["PARTICIP_URL"].'logout.php';
		
		//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
		CForm::GetResultAnswerArray('1', $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $arResult["USER"]["UF_ANKETA"]));
		foreach($arrAnswersVarname[$arResult["USER"]["UF_ANKETA"]] as $userField){
			switch ($userField[0]["TITLE"]){
				case "First Name":
					$arResult["USER"]["NAME"] = $userField[0]["USER_TEXT"];
					break;
				case "Last Name":
					$arResult["USER"]["LAST_NAME"] = $userField[0]["USER_TEXT"];
					break;
				case "Title":
					$arResult["USER"]["TITLE"] = $userField[0]["ANSWER_TEXT"];
					break;
				case "Title (other)":
					if($userField[0]["USER_TEXT"]){
						$arResult["USER"]["TITLE"] = $userField[0]["USER_TEXT"];
					}
					break;
			}
		}
		$arResult["WELCOME"] .= $arResult["USER"]["TITLE"]." ".$arResult["USER"]["NAME"]." ".$arResult["USER"]["LAST_NAME"];
		$arResult["WELCOME2"] = "You have <span>".$arResult["USER"]["MEETINGS"]."</span> unconfirmed appointment requests and <span>".$arResult["USER"]["MESSAGES"]."</span> new messages.";
	}
	elseif(in_array($arParams["GUEST_GROUP"], $userGroups)){
		$arResult["WELCOME"] = 'Добро пожаловать, ';
		$arResult["USER"]["LOGOUT"] = $arParams["GUEST_URL"].'logout.php';
		if($arResult["USER"]["LAST_NAME"] == '' || $arResult["USER"]["NAME"] == ''){
			//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
			CForm::GetResultAnswerArray('4', $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $arResult["USER"]["UF_ANKETA"]));
			foreach($arrAnswersVarname[$arResult["USER"]["UF_ANKETA"]] as $userField){
				switch ($userField[0]["TITLE"]){
					case "Имя":
						$arResult["USER"]["NAME"] = $userField[0]["USER_TEXT"];
						break;
					case "Фамилия":
						$arResult["USER"]["LAST_NAME"] = $userField[0]["USER_TEXT"];
						break;
				}
			}
		}
		$arResult["WELCOME"] .= $arResult["USER"]["NAME"]." ".$arResult["USER"]["LAST_NAME"];
		$arResult["WELCOME2"] = "У вас <span>".$arResult["USER"]["MEETINGS"]."</span> неподтвержденных запросов на встречи и <span>".$arResult["USER"]["MESSAGES"]."</span> новых сообщений.";
	}
	else{
		//$rsUser = CUser::GetByID($userId);
		$rsUser = CUser::GetByID('47');
		$arUser = $rsUser->Fetch();
		$arResult["USER"]["ID"] = $arUser["ID"];
		$arResult["USER"]["UF_ANKETA"] = $arUser["UF_ANKETA"];
		$arResult["USER"]["NAME"] = $arUser["NAME"];
		$arResult["USER"]["LAST_NAME"] = $arUser["LAST_NAME"];
		$arResult["USER"]["MEETINGS"] = ($arUser["UF_MEETING"] == '') ? "0" : $arUser["UF_MEETING"];
		$arResult["USER"]["MESSAGES"] = ($arUser["UF_MESSAGE"] == '') ? "0" : $arUser["UF_MESSAGE"];
		$arResult["WELCOME"] = 'Добро пожаловать ';
		$arResult["USER"]["LOGOUT"] = $arParams["GUEST_URL"].'logout.php';
		//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
		CForm::GetResultAnswerArray('4', $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $arResult["USER"]["UF_ANKETA"]));
		foreach($arrAnswersVarname[$arResult["USER"]["UF_ANKETA"]] as $userField){
			switch ($userField[0]["TITLE"]){
				case "Имя":
					$arResult["USER"]["NAME"] = $userField[0]["USER_TEXT"];
					break;
				case "Фамилия":
					$arResult["USER"]["LAST_NAME"] = $userField[0]["USER_TEXT"];
					break;
			}
		}
		$arResult["WELCOME"] .= $arResult["USER"]["NAME"]." ".$arResult["USER"]["LAST_NAME"];
		$arResult["WELCOME2"] = "У вас <span>".$arResult["USER"]["MEETINGS"]."</span> неподтвержденных запросов на встречи и <span>".$arResult["USER"]["MESSAGES"]."</span> новых сообщений.";
	}
}


$this->IncludeComponentTemplate();
?>