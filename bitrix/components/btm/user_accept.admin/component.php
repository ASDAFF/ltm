<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/

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
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� �������������!<br />";
}

if(strLen($arParams["USER_ACCEPT"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� �������������� �������������!<br />";
}

if(strLen($arParams["USER_SPAM"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������ ��� �����!<br />";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������� �������������!<br />";
}
if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}
/*---------------------------------------------------*/
//           ������������ �������������              //
/*---------------------------------------------------*/
if(isset($_POST["accept"])){
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$tempUsers = array();
		$tempUsers = $_POST["accept"];
		$arGroups[] = $arParams["USER_ACCEPT"];
		foreach($tempUsers as $acceptUser){
			CUser::SetUserGroup($acceptUser, $arGroups);
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
elseif(isset($_POST["pay"])){
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$tempUsers = array();
		$tempUsers = $_POST["pay"];
		$strError = '';
		$fields = Array(
			"UF_PAY"  => "1",
		);
		$user = new CUser;
		foreach($tempUsers as $acceptUser){
			$user->Update($acceptUser, $fields);
			$strError .= $user->LAST_ERROR;
			if($strError){
				$strError .= $strError.' - '.$acceptUser.'<br />';
			}
		}
		$arResult["MESSAGE"] = $strError;
	}
}
elseif(isset($_GET["type"]) && $_GET["type"] == "del_pay"){
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$tempUsers = array();
		$tempUsers = $_GET["id"];
		$strError = '';
		$fields = Array(
			"UF_PAY"  => "",
		);
		$user = new CUser;
		$user->Update($tempUsers, $fields);
		$strError .= $user->LAST_ERROR;
		if($strError){
			$strError .= $strError.' - '.$acceptUser.'<br />';
		}
		$arResult["MESSAGE"] = $strError;
	}
}


/*---------------------------------------------------*/
//           ��������� ����� ��� �������             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		//������ �������������
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["USER"])
		);
		if(isset($arParams["USER_SORT"]) && $arParams["USER_SORT"] != ''){
			$sort = $arParams["USER_SORT"];
		}
		else{
			$sort = "UF_PAY";
		}
		$rsUsers = CUser::GetList(($by=$sort), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
		$rsUsers->NavStart(100); // ��������� ����������� �� 30 �������
		$arResult["NAVIGATE"] = $rsUsers->GetPageNavStringEx($navComponentObject, "������������", "");
		$countUsers = 0;
		$resultFormId = "";
		while($arUsersTemp=$rsUsers->Fetch()){
			$arUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
			$arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
			$arUsers[$countUsers]["UF_PAY_COUNT"] = $arUsersTemp["UF_PAY_COUNT"];
			$arUsers[$countUsers]["UF_PAY"] = $arUsersTemp["UF_PAY"];
			$arUsers[$countUsers]["UF_INVOICE"] = $arUsersTemp["UF_INVOICE"];
			$arUsers[$countUsers]["ADMIN_NOTES"] = $arUsersTemp["ADMIN_NOTES"];
			
			$resultFormId .= " | ".$arUsersTemp["UF_ANKETA"];
			$countUsers++;
		}
		$resultFormId = substr($resultFormId, 3);
		$arResult["USERS"]["COUNT"] = $countUsers;
		if(isset($arParams["USER_FORMAT"])){
			$arResult["USERS"]["FORMAT"] = $arParams["USER_FORMAT"];
		}

		//���������� �������������
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $resultFormId));
		
		//������ ������� ��� �������
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
				if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)")){
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
		
		//������ ������������� � ������
		for($i=0; $i<$countUsers; $i++){
			$arResult["USERS"][$i]["ID"] = $arUsers[$i]["ID"];
			$arResult["USERS"][$i]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
			$arResult["USERS"][$i]["PAY"] = $arUsers[$i]["UF_PAY_COUNT"];
			$arResult["USERS"][$i]["IS_PAY"] = $arUsers[$i]["UF_PAY"];
			$arResult["USERS"][$i]["UF_INVOICE"] = $arUsers[$i]["UF_INVOICE"];
			$arResult["USERS"][$i]["PASS"] = $arUsers[$i]["ADMIN_NOTES"];
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
						//echo $arResult["USERS"][$i]["FIELDS"][$j-$sdvig]."<br />";
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
						if(strpos($arResult["FIELDS"][$j]["TITLE"], "�����") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "�����";
						}
						elseif(strpos($arResult["FIELDS"][$j]["TITLE"], "�������") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "�������";
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
		$arResult["ERROR_MESSAGE"] = "� ��� ������������ ���� ��� ��������� ������ ��������!";
	}
}
//echo "<pre>"; print_r($realFieldTemp); echo "</pre>";
//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>