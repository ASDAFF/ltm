<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//
if(strLen($arParams["ADMIN"])<=0){
	$arParams["ADMIN"] = 1;
}
if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}
if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/personal/login.php";
}
if(strLen($arParams["EVENT_TEMP"])<=0){
	$arParams["EVENT_TEMP"] = "PARTICIP_CHANGE";
}

$arResult["ERROR_MESSAGE"] = "";
if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}
else
{
	$userId= $USER->GetID();
	$rsUser = CUser::GetByID($userId);
	$arUser = $rsUser->Fetch();
	$RESULT_ID = $arUser["UF_ANKETA"];
	$arTmpResult["ACTION_TYPE"] = "CREATE";
	if($arUser["UF_ANKETA_NEXT"] != ''){
		$arTmpResult["ACTION_TYPE"] = "EDITE";
		$RESULT_ID = $arUser["UF_ANKETA_NEXT"];
	}
	$arAnswer = CFormResult::GetDataByID($RESULT_ID, array(), $arResult, $arAnswer2);	
	foreach($arAnswer as $fieldAns => $arrAns){
		$arResult["QUEST"][$fieldAns]["FIELD"] = $arrAns[0]["FIELD_ID"];
		$arResult["QUEST"][$fieldAns]["ANSWER_ID"] = $arrAns[0]["ANSWER_ID"];
		$arResult["QUEST"][$fieldAns]["TYPE"] = $arrAns[0]["FIELD_TYPE"];
		$arResult["QUEST"][$fieldAns]["ANSWER_ARR"] = array();
		$arResult["QUEST"][$fieldAns]["TITLE"] = $arrAns[0]["TITLE"];
		if($arrAns[0]["FIELD_TYPE"] == 'dropdown'){
			$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["MESSAGE"];
			$rsAnswers = CFormAnswer::GetList($arResult["QUEST"][$fieldAns]["FIELD"], $by="s_sort", $order="asc", array(), $is_filtered);
			while ($arAnswer = $rsAnswers->Fetch())
			{
				$arResult["QUEST"][$fieldAns]["ANSWER_ARR"][] = $arAnswer;
			}

		}
		else{
			$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["USER_TEXT"];
		}
	}
	if((isset($_POST['usact'])) and ($_POST['usact'] == 'update') && ($arTmpResult["ACTION_TYPE"] == "EDITE")){
		$sendMessage = "";
		foreach($arResult["QUEST"] as $fieldAns => $arrAns){
			if(isset($_POST[$fieldAns])){
				if(($_POST[$fieldAns] != $_POST["OLD_".$fieldAns] && $arrAns["TYPE"] != 'dropdown') || ($_POST[$fieldAns] != '' && $_POST[$fieldAns] != $arResult["QUEST"][$fieldAns]["ANSWER_ID"] && $arrAns["TYPE"] == 'dropdown')){
					$arVALUE = array();
					$ANSWER_ID = $arrAns["ANSWER_ID"]; // ID поля ответа
					if($arrAns["TYPE"] == 'dropdown'){
						$arVALUE[$_POST[$fieldAns]] = '';
						$newAns = '';
						foreach($arrAns["ANSWER_ARR"] as $keyField => $optField){
							if($optField["ID"] == $_POST[$fieldAns]){
								$newAns .= $optField["MESSAGE"]."; ";
							}
						}
						$sendMessage .= "Поле ".$arResult["QUEST"][$fieldAns]["TITLE"].". Старое значение: ".$arResult["QUEST"][$fieldAns]["VALUE"]." Новое значение: ".$newAns."\n";
					}
					elseif($arrAns["TYPE"] == 'checkbox'){
						foreach($_POST[$fieldAns] as $checkValue){
							$arVALUE[$checkValue] = '';
						}
					}
					else{
						$arVALUE[$ANSWER_ID] = $_POST[$fieldAns];
						$sendMessage .= "Поле ".$arResult["QUEST"][$fieldAns]["TITLE"].". Старое значение: ".$arResult["QUEST"][$fieldAns]["VALUE"]." Новое значение: ".$_POST[$fieldAns]."\n";
					}
					CFormResult::SetField($RESULT_ID, $fieldAns, $arVALUE);
				}
			}
		}
		$arResult["MESSAGE"] = GetMessage("UPDATE_SUCCESS");
		$arAnswer = CFormResult::GetDataByID($RESULT_ID, array(), $arResult2, $arAnswer2);	
		foreach($arAnswer as $fieldAns => $arrAns){
			$arResult["QUEST"][$fieldAns]["FIELD"] = $arrAns[0]["FIELD_ID"];
			$arResult["QUEST"][$fieldAns]["ANSWER_ID"] = $arrAns[0]["ANSWER_ID"];
			$arResult["QUEST"][$fieldAns]["TYPE"] = $arrAns[0]["FIELD_TYPE"];
			$arResult["QUEST"][$fieldAns]["ANSWER_ARR"] = array();
			$arResult["QUEST"][$fieldAns]["TITLE"] = $arrAns[0]["TITLE"];
			if($arrAns[0]["FIELD_TYPE"] == 'dropdown'){
				$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["MESSAGE"];
				$rsAnswers = CFormAnswer::GetList($arResult["QUEST"][$fieldAns]["FIELD"], $by="s_sort", $order="asc", array(), $is_filtered);
				while ($arAnswer = $rsAnswers->Fetch())
				{
					$arResult["QUEST"][$fieldAns]["ANSWER_ARR"][] = $arAnswer;
				}

			}
			else{
				$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["USER_TEXT"];
			}
		}
	}
	elseif((isset($_POST['usact'])) and ($_POST['usact'] == 'update') && ($arTmpResult["ACTION_TYPE"] == "CREATE")){
		// массив значений ответов
		$arValues = array();
		if (CForm::GetDataByID($arParams["FORM_ID"], $form, $questions, $answers, $dropdown, $multiselect)){
			foreach($answers as $fieldAns => $arrAns){
				if($_POST[$fieldAns] != ''){
					switch ($arrAns[0]["FIELD_TYPE"]){
						case "text":
							$arValues["form_text_".$arrAns[0]["ID"]] = $_POST[$fieldAns];
							break;						
						case "textarea":
							$arValues["form_textarea_".$arrAns[0]["ID"]] = $_POST[$fieldAns];
							break;						
						case "dropdown":
							$arValues["form_dropdown_".$fieldAns] = $_POST[$fieldAns];
							break;						
						case "email":
							$arValues["form_email_".$arrAns[0]["ID"]] = $_POST[$fieldAns];
							break;						
					}
				}
			}
			// создадим новый результат
			if ($RESULT_ID = CFormResult::Add($arParams["FORM_ID"], $arValues))
			{
				$userTmp = new CUser;
				$userGroups = CUser::GetUserGroup($userId);
				if(!in_array($arParams["GROUP_ID"], $userGroups)){
					$userGroups[] = $arParams["GROUP_ID"];
				}
				$arTmpUsFields = Array(
				  "UF_ANKETA_NEXT"    => $RESULT_ID,
				  "UF_SOURCE"  	  	  => "ЛК",
				  "GROUP_ID"   		  => $userGroups,
				);
				$userTmp->Update($userId, $arTmpUsFields);
				$strError = '';
				$strError .= $userTmp->LAST_ERROR;
				if($strError == ''){
					$arEventFields = array(
						"ID"                  => $arUser["ID"],
						"EMAIL"          	  => $arUser["EMAIL"],
						"NAME"          	  => $arUser["NAME"]." ".$arUser["LAST_NAME"],
						"COMPANY"	          => $arUser["WORK_COMPANY"]
						);
					CEvent::Send("NEW_PARTICIP_NEXT_YEAR", "s1", $arEventFields);
					$arTmpResult["ACTION_TYPE"] = "EDITE";
					$arResult["MESSAGE"] = GetMessage("SAVE_SUCCESS");
					$arAnswer = CFormResult::GetDataByID($RESULT_ID, array(), $arResult2, $arAnswer2);	
					foreach($arAnswer as $fieldAns => $arrAns){
						$arResult["QUEST"][$fieldAns]["FIELD"] = $arrAns[0]["FIELD_ID"];
						$arResult["QUEST"][$fieldAns]["ANSWER_ID"] = $arrAns[0]["ANSWER_ID"];
						$arResult["QUEST"][$fieldAns]["TYPE"] = $arrAns[0]["FIELD_TYPE"];
						$arResult["QUEST"][$fieldAns]["ANSWER_ARR"] = array();
						$arResult["QUEST"][$fieldAns]["TITLE"] = $arrAns[0]["TITLE"];
						if($arrAns[0]["FIELD_TYPE"] == 'dropdown'){
							$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["MESSAGE"];
							$rsAnswers = CFormAnswer::GetList($arResult["QUEST"][$fieldAns]["FIELD"], $by="s_sort", $order="asc", array(), $is_filtered);
							while ($arAnswer = $rsAnswers->Fetch())
							{
								$arResult["QUEST"][$fieldAns]["ANSWER_ARR"][] = $arAnswer;
							}
			
						}
						else{
							$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["USER_TEXT"];
						}
					}
				}
				else{
					$arEventFields = array(
						"ERROR"                  => 'Не добавился пользователь',
						"ID"                  => $arUser["ID"],
						"EMAIL"          	  => $arUser["EMAIL"],
						"NAME"          	  => $arUser["NAME"]." ".$arUser["LAST_NAME"],
						"COMPANY"	          => $arUser["WORK_COMPANY"]
						);
					CEvent::Send("NEW_PARTICIP_ERROR", "s1", $arEventFields);
					$arResult["ERROR_MESSAGE"] = GetMessage("FORM_NEV_YEAR_SAVE_ERROR");
				}
			}
			else
			{
				$arResult["ERROR_MESSAGE"] = GetMessage("FORM_NEV_YEAR_CREATE_ERROR");
			}
		}
		else{
			$arResult["ERROR_MESSAGE"] = GetMessage("FORM_NEV_YEAR_ERROR");
		}
	}
	elseif($arTmpResult["ACTION_TYPE"] == "EDITE"){
		$arResult["MESSAGE"] = GetMessage("FORM_NEV_YEAR_ALREADY_CREATE");
	}
	$arResult["ACTION_TYPE"] = $arTmpResult["ACTION_TYPE"];
}
//echo "<pre>"; print_r($arResult); echo "</pre>";
$this->IncludeComponentTemplate();
?>
