<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//�������� ������ ����� � ���������
//��������, ���� ��� �������������

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

if(strLen($arParams["USER_COUNT"])<=0){
	$arParams["USER_COUNT"] = 15;
}

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� �������������!<br />";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������� �������������!<br />";
}

/*---------------------------------------------------*/
//           ��������� ����� ��� �������             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	//������ �������������
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["USER"])
	);
	$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_*"), "NAV_PARAMS" => array("nPageSize"=>$arParams["USER_COUNT"]))); // �������� �������������
	$countUsers = 0;
	$resultFormId = "";
	while($arUsersTemp=$rsUsers->Fetch()){
		$arUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
		$arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
		$resultFormId .= " | ".$arUsersTemp["UF_ANKETA"];
		$countUsers++;
	}
	$resultFormId = substr($resultFormId, 3);
	$arResult["USERS"]["COUNT"] = $countUsers;

	//���������� �������������
	CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $resultFormId));
	
	//������ ������������� � ������
	for($i=0; $i<$countUsers; $i++){
		$arResult["USERS"][$i]["ID"] = $arUsers[$i]["ID"];
		$arResult["USERS"][$i]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
		$arResult["USERS"][$i]["COMPANY"] = $arrAnswers[$arUsers[$i]["UF_ANKETA"]][6][10]["USER_TEXT"];
	}
	$countColumns = $arResult["FIELDS"]["COUNT"];
	$arResult["FIELDS"] = $realFieldTemp;
	$arResult["FIELDS"]["COUNT"] = $countColumns;
}
//echo "<pre>"; print_r($arrAnswers); echo "</pre>";
//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>