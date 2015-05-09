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

$arParams["EDIT_ACT"] = $arParams["IS_ACTIVE"];

if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}
else
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$rsUser = CUser::GetByID($userId);
		$arUser = $rsUser->Fetch();
		$RESULT_ID = $arUser["UF_ANKETA"];
		$arAnswer = CFormResult::GetDataByID($RESULT_ID, array(), $arResult, $arAnswer2);
		$directId = 0;
		$directAnsArr = array();
		foreach($arAnswer as $fieldAns => $arrAns){
			$arResult["QUEST"][$fieldAns]["FIELD"] = $arrAns[0]["FIELD_ID"];
			$arResult["QUEST"][$fieldAns]["ANSWER_ID"] = $arrAns[0]["ANSWER_ID"];
			$arResult["QUEST"][$fieldAns]["TYPE"] = $arrAns[0]["FIELD_TYPE"];
			$arResult["QUEST"][$fieldAns]["ANSWER_ARR"] = array();
			$arResult["QUEST"][$fieldAns]["TITLE"] = $arrAns[0]["TITLE"];
			if($arrAns[0]["SID"] == 'directions'){
				$directId = $arrAns[0]["FIELD_ID"];
			}
			if($arrAns[0]["FIELD_TYPE"] == 'dropdown'){
				$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["MESSAGE"];
				$rsAnswers = CFormAnswer::GetList($arResult["QUEST"][$fieldAns]["FIELD"], $by="s_sort", $order="asc", array(), $is_filtered);
				while ($arAnswer = $rsAnswers->Fetch())
				{
					$arResult["QUEST"][$fieldAns]["ANSWER_ARR"][] = $arAnswer;
				}
			}
			elseif($arrAns[0]["FIELD_TYPE"] == 'checkbox'){
				$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["MESSAGE"];
				$arResult["QUEST"][$fieldAns]["ANSWER_ARR"] = $arrAns;
				foreach($arrAns as $fieldAnsDir => $arrAnsDir){
					$directAnsArr[] = $arrAnsDir["ANSWER_ID"];
				}
			}
			else{
				$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["USER_TEXT"];
			}
		}
		if($directId == 0){
			$directId = 274;
		}
		$rsAnswers = CFormAnswer::GetList($directId, $by="s_sort", $order="asc", array(), $is_filtered);
		$arResult["QUEST"]["directions"]["ALL_ANS"] = array();
		while ($arAnswer3 = $rsAnswers->Fetch())
		{
			if(in_array($arAnswer3["ID"], $directAnsArr)){
				$arAnswer3["CHECKED"] = "Y";
			}
			else{
				$arAnswer3["CHECKED"] = "N";
			}
			$arResult["QUEST"]["directions"]["ALL_ANS"][] = $arAnswer3;
		}

		if($arParams["EDIT_ACT"] == "N"){
			$arResult["MESSAGE"] = GetMessage("IS_BLOKED");
		}
		if((isset($_POST['usact'])) and ($_POST['usact'] == 'update') and ($arParams["EDIT_ACT"] != "N")){
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
						elseif($fieldAns == "directions"){
							$arVALUE = array();
							foreach($_POST[$fieldAns] as $keyField => $optField){
								$arVALUE[$optField] = '';
							}
						}
						else{
							$arVALUE[$ANSWER_ID] = $_POST[$fieldAns];
							$sendMessage .= "Поле ".$arResult["QUEST"][$fieldAns]["TITLE"].". Старое значение: ".$arResult["QUEST"][$fieldAns]["VALUE"]." Новое значение: ".$_POST[$fieldAns]."\n";
						}
						CFormResult::SetField($RESULT_ID, $fieldAns, $arVALUE);
						if($sendMessage != ''){
							$arEventFields = array(
								"ID" => $userId,
								"MESSAGE" => $sendMessage
							);
							CEvent::Send($arParams["EVENT_TEMP"], "s1", $arEventFields);
						}
					}
				}
			}
			if(isset($_REQUEST["SIMPLE_QUESTION_605"]) && $_REQUEST["SIMPLE_QUESTION_605"] != ''){
				$userTmp = new CUser;
				$arTmpUsFields = Array(
				  "NAME"	          => $_REQUEST["SIMPLE_QUESTION_605"],
				  "LAST_NAME"         => $_REQUEST["SIMPLE_QUESTION_151"],
				);
				$userTmp->Update($userId, $arTmpUsFields);
				$strError .= $userTmp->LAST_ERROR;
			}
			elseif(isset($_REQUEST["company"]) && $_REQUEST["company"] != ''){
				$userTmp = new CUser;
				$arTmpUsFields = Array(
				  "EMAIL"             => $_REQUEST["email"],
				  "NAME"	          => $_REQUEST["name"],
				  "LAST_NAME"         => $_REQUEST["surname"],
				);
				$userTmp->Update($userId, $arTmpUsFields);
				$strError .= $userTmp->LAST_ERROR;
			}
			$arResult["MESSAGE"] = GetMessage("UPDATE_SUCCESS");
			$arAnswer = CFormResult::GetDataByID($RESULT_ID, array(), $arResult2, $arAnswer2);	
			$directAnsArr = array();
			$directId = 0;
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
				elseif($arrAns[0]["FIELD_TYPE"] == 'checkbox'){
					$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["MESSAGE"];
					$arResult["QUEST"][$fieldAns]["ANSWER_ARR"] = $arrAns;
					foreach($arrAns as $fieldAnsDir => $arrAnsDir){
						$directAnsArr[] = $arrAnsDir["ANSWER_ID"];
					}
				}
				else{
					$arResult["QUEST"][$fieldAns]["VALUE"] = $arrAns[0]["USER_TEXT"];
				}
			}
			$oldAns = $arResult["QUEST"]["directions"]["ALL_ANS"];
			$arResult["QUEST"]["directions"]["ALL_ANS"] = array();
			foreach($oldAns as $fieldAnsDir => $arrAnsDir){
				if(in_array($arrAnsDir["ID"], $directAnsArr)){
					$arrAnsDir["CHECKED"] = "Y";
				}
				else{
					$arrAnsDir["CHECKED"] = "N";
				}
				$arResult["QUEST"]["directions"]["ALL_ANS"][] = $arrAnsDir;
			}
		}
	}
	else{
		$arResult["ERROR_MESSAGE"] = "Isn't admin";
	}
}
$arResult["EDIT_ACT"] = $arParams["EDIT_ACT"];
//echo "<pre>"; print_r($arAnswer3); echo "</pre>";
$this->IncludeComponentTemplate();
?>
