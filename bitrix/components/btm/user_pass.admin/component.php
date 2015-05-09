<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить в параметры FORM_ID


$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователю!<br />";
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = "Вы не авторизованы!<br />";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arParams["FORM_ID"] = '1';
}


/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/

if($arResult["ERROR_MESSAGE"] == '')
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$rsUser = CUser::GetByID($arParams["USER"]);
		$thisUser = $rsUser->Fetch();
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $thisUser["UF_ANKETA"]));
		$realUser = array();
		$realUser["ID"] = $arParams["USER"];
		$realUser["EMAIL"] = $arrAnswers[$thisUser["UF_ANKETA"]][10][19]["USER_TEXT"];
		$realUser["NAME"] = $arrAnswers[$thisUser["UF_ANKETA"]][1][1]["USER_TEXT"];
		$realUser["SURNAME"] = $arrAnswers[$thisUser["UF_ANKETA"]][2][2]["USER_TEXT"];
		$realUser["COMPANY"] = $arrAnswers[$thisUser["UF_ANKETA"]][6][10]["USER_TEXT"];
		$realUser["PASS"] = $thisUser["ADMIN_NOTES"];
		$realUser["LOGIN"] = $thisUser["LOGIN"];

		if($_REQUEST["pass_save"] == "Сохранить"){
			$user = new CUser;
			$strError = '';
			if($_REQUEST["pass"]){
				$fields = Array(
				  "NAME"              => $realUser["NAME"],
				  "LAST_NAME"         => $realUser["SURNAME"],
				  "ADMIN_NOTES"      => $_REQUEST["pass"],
				  "PASSWORD"       => $_REQUEST["pass"],
				  "CONFIRM_PASSWORD"       => $_REQUEST["pass"],
				  );
				$user->Update($arParams["USER"], $fields);
				$strError .= $user->LAST_ERROR;
				$realUser["PASS"] = $_REQUEST["pass"];
			}
			else{
				$strError .= "Вы не ввели пароль!";
			}
			if(!$strError){
				$arResult["MESSAGE"] = GetMessage("ADMIN_USER_PASS");
			}
			else{
				$arResult["MESSAGE"] = $strError;
			}
		}
		$arResult["USER"] = $realUser;
	}
	else{
		$arResult["ERROR_MESSAGE"] = "У вас недостаточно прав для просмотра данной страницы!";
	}
}

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

$this->IncludeComponentTemplate();
?>