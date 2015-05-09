<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить Статус Формы в Параметры

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
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователям!<br />";
}

if(strLen($arParams["USER_ACCEPT"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Подтвержденным пользователям!<br />";
}

if(strLen($arParams["USER_SPAM"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по группе для Спама!<br />";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["IS_SPAM"])<=0){
	$arParams["IS_SPAM"] = "N";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Результатам пользователей!<br />";
}
if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}
/*---------------------------------------------------*/
//           ПОДТВЕРЖДАЕМ ПОЛЬЗОВАТЕЛЕЙ              //
/*---------------------------------------------------*/
if(isset($_POST["accept"])){
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		if($arParams["USER_TYPE"] == "PARTICIP"){
			$STATUS_ID = 10;
		}
		else{
			$STATUS_ID = 11;
		}
		$tempUsers = array();
		if ($arParams["USER_TYPE"] == "PARTICIP") {
            $tempUsers = $_POST["accept"];
            $arGroups[] = $arParams["USER_ACCEPT"];
            foreach ($tempUsers as $acceptUser) {
                CUser::SetUserGroup($acceptUser, $arGroups);
                CFormResult::SetStatus($_POST["form" . $acceptUser], $STATUS_ID);
            }
        } else {
            $arGroups[] = $arParams["USER_ACCEPT"];
            $tempUsers = array();
            foreach ($_POST["morning"] as $acceptUser) {
                $tempUsers[$acceptUser]["morning"] = 1;
            }
            foreach ($_POST["evning"] as $acceptUser) {
                $tempUsers[$acceptUser]["evening"] = 1;
            }
            foreach ($_POST["hb"] as $acceptUser) {
                $tempUsers[$acceptUser]["hb"] = 1;
            }
            foreach ($tempUsers as $key => $value) {
                $arGroups = CUser::GetUserGroup($key);
                $arDel = array($arParams["USER"]);
                $arGroups = array_diff($arGroups, $arDel);
                if (isset($value["hb"])) {
                    $arGroups[] = $arParams["USER_HB"];
                }
                if (isset($value["morning"])) {
                    $arGroups[] = $arParams["USER_ACCEPT"];
                    $tmpUser = CUser::GetByID($key);
                    $arTmpUser = $tmpUser->Fetch();
                    $arEventFields = array(
                        "EMAIL" => $_POST["email" . $key],
                        "LOGIN" => $arTmpUser["LOGIN"],
                        "PASSWORD" => $arTmpUser["ADMIN_NOTES"]
                    );
                    CEvent::Send("NEW_MORNING_ACCEPT", "s1", $arEventFields);
                }
                if (isset($value["evening"])) {
                    $arGroups[] = $arParams["USER_EVENING"];
                    $arEventFields = array(
                        "EMAIL" => $_POST["email" . $key]
                    );
                    CEvent::Send("NEW_EVENING_ACCEPT", "s1", $arEventFields);
                }
                CUser::SetUserGroup($key, $arGroups);
            }
        }

		if($arParams["USER_TYPE"] == "PARTICIP"){
			$arResult["MESSAGE"] = GetMessage("ADMIN_USER_OFF_ACCEPT_PARTICIP");
		}
		else{
			$arResult["MESSAGE"] = GetMessage("ADMIN_USER_OFF_ACCEPT_GUEST");
		}
	}
}
elseif(isset($_GET["type"]) && $_GET["type"] == "spam"){
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$tempUsers = $_GET["id"];
		$arGroups[] = $arParams["USER_SPAM"];
		CUser::SetUserGroup($tempUsers, $arGroups);
		if($arParams["USER_TYPE"] == "PARTICIP"){
			$arResult["MESSAGE"] = GetMessage("ADMIN_USER_OFF_SPAM_PARTICIP");
		}
		else{
			$arResult["MESSAGE"] = GetMessage("ADMIN_USER_OFF_SPAM_GUEST");
		}
	}
}


/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		//СПИСОК ПОЛЬЗОВАТЕЛЕЙ
		if($arParams["USER_TYPE"] == "PARTICIP_NEXT"){
			$filter = Array(
				"GROUPS_ID"  => Array($arParams["USER"], $arParams["USER_ACCEPT"]),
				"!UF_ANKETA_NEXT" => ''
			);
		}
		else{
			$filter = Array(
				"GROUPS_ID"  => Array($arParams["USER"])
			);
		}
		$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
		$countUsers = 0;
		$resultFormId = "";
		while($arUsersTemp=$rsUsers->Fetch()){
			$arUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
			$arUsers[$countUsers]["SOURCE"] = '';
			if($arParams["USER_TYPE"] == "PARTICIP_NEXT"){
			  $arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA_NEXT"];
			  $resultFormId .= " | ".$arUsersTemp["UF_ANKETA_NEXT"];
			  $arUsers[$countUsers]["SOURCE"] = CUser::GetUserGroup($arUsersTemp["ID"]);
			  if(in_array($arParams["USER_ACCEPT"], $arUsers[$countUsers]["SOURCE"])){
				  $arUsers[$countUsers]["SOURCE"] = "LK";
			  }
			  else{
				  $arUsers[$countUsers]["SOURCE"] = "PUBLIC";
			  }
			}
			else{
			  $arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
			  $resultFormId .= " | ".$arUsersTemp["UF_ANKETA"];
			}
			$countUsers++;
		}
		$resultFormId = substr($resultFormId, 3);
		$arResult["USERS"]["COUNT"] = $countUsers;

		//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $resultFormId));
		
		//СПИСОК КОЛОНОК ДЛЯ ТАБЛИЦЫ
		$countColumns = 0;
		$countReal = 0;
		$arResult["FIELDS"]["COUNT"]=0;
		foreach($arrColumns as $columnName){
			$arResult["FIELDS"][$countReal]["ID"] = $columnName["ID"];
			$arResult["FIELDS"][$countReal]["TITLE"] = $columnName["TITLE"];
			$arResult["FIELDS"][$countReal]["OTHER"] = "N";
			if($arParams["USER_TYPE"] == "PARTICIP"){
				if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (other)")){
					$arResult["FIELDS"][$countReal]["OTHER"] = "Y";
				}
				else{
					$countColumns++;
				}
			}
			else{
				if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другой)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другая)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другие)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другое)")){
					$arResult["FIELDS"][$countReal]["OTHER"] = "Y";
				}
				else{
					$countColumns++;
				}
			}
			$countReal++;
		}
		$arResult["FIELDS"]["COUNT"] = $countColumns;
		$realFieldTemp = array();
		
		//СПИСОК ПОЛЬЗОВАТЕЛЕЙ С ПОЛЯМИ
		for($i=0; $i<$countUsers; $i++){
			$arResult["USERS"][$i]["ID"] = $arUsers[$i]["ID"];
			$arResult["USERS"][$i]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
			$arResult["USERS"][$i]["SOURCE"] = $arUsers[$i]["SOURCE"];
			$sdvig = 0;
			for($j=0; $j<$countReal; $j++){
				if($arResult["FIELDS"][$j]["OTHER"] == "Y"){
					$sdvig++;
					$tempMean = "";
					foreach($arrAnswers[$arUsers[$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning){
						if($ansMeaning["USER_TEXT"]){
							$tempMean .= ", ".$ansMeaning["USER_TEXT"];
						}
						else{
							$tempMean .= ", ".$ansMeaning["ANSWER_TEXT"];
						}
					}
					$tempMean = trim($tempMean);
					$tempMean = substr($tempMean, 1);
					if($tempMean){
						$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] = $tempMean;
					}
				}
				else{
					$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] = "";
					foreach($arrAnswers[$arUsers[$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning){
						if($ansMeaning["USER_TEXT"]){
							$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] .= ", ".$ansMeaning["USER_TEXT"];
						}
						else{
							$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] .= ", ".$ansMeaning["ANSWER_TEXT"];
						}
					}
					$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] = substr($arResult["USERS"][$i]["FIELDS"][$j-$sdvig], 2);
					$realFieldTemp[$j-$sdvig]["ID"] = $arResult["FIELDS"][$j]["ID"];
					if($arParams["USER_TYPE"] == "PARTICIP"){
						if(strpos($arResult["FIELDS"][$j]["TITLE"], "Short company description") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "Description";
						}
						else{
							$realFieldTemp[$j-$sdvig]["TITLE"] = $arResult["FIELDS"][$j]["TITLE"];
						}
					}
					else{
						if(strpos($arResult["FIELDS"][$j]["TITLE"], "Адрес") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "Адрес";
						}
						elseif(strpos($arResult["FIELDS"][$j]["TITLE"], "Телефон") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "Телефон";
						}
						else{
							$realFieldTemp[$j-$sdvig]["TITLE"] = $arResult["FIELDS"][$j]["TITLE"];
						}
					}
				}
			}
		}
		$countColumns = $arResult["FIELDS"]["COUNT"];
		$arResult["FIELDS"] = $realFieldTemp;
		$arResult["FIELDS"]["COUNT"] = $countColumns;
	}
	else{
		$arResult["ERROR_MESSAGE"] = "У вас недостаточно прав для просмотра данной страницы!";
	}
}
//echo "<pre>"; print_r($realFieldTemp); echo "</pre>";
//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>