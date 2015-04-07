#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: dmitrz
 */
$_SERVER["DOCUMENT_ROOT"] = "/home/u24601/luxurytravelmart.ru/www";

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("BX_CAT_CRON", true);
define('NO_AGENT_CHECK', true);
define('SITE_ID', 's1'); // your site ID - need for language ID

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

//���������� ���� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);

#����������� �������
CModule::includeModule("form");

global $USER;
if(!is_object($USER))
{
	$USER = new CUser();
}


#���������

#Id ������ ��������
$OLD_FORM_ID = 4;

#Id ����� ��������
$NEW_FORM_ID = 29;

#id ������ ������������� �������������� �� ��������
$USER_GROUP_ID = 10;

$arUsers = getUserByGroup($USER_GROUP_ID);

foreach($arUsers as $arUser)
{
	#�������� ��������� �������� ��� ���������
	$arFormDataParticipant = convertAnswerById($arUser["UF_ID"], $NEW_FORM_ID);
	$arFormDataColleague = convertAnswerById($arUser["UF_ID6"], $NEW_FORM_ID);

	//�������� ��������� ���������
	$resParticipantID = CFormResult::Add($NEW_FORM_ID, $arFormDataParticipant);
	//�������� ��������� �������
	$resColleagueID = CFormResult::Add($NEW_FORM_ID, $arFormDataColleague);

	//��������� ������ ������������
	$USER->Update($arUser["ID"], array("UF_MSCSPRING2016" => $resParticipantID, "UF_MSCSPRING2016COL" => $resColleagueID));

}










#�������

/**
 * �������� ������ ������������� ����������� � ������������ ������
 * @param $USER_GROUP_ID - id ������ �������������
 * @return array
 */
function getUserByGroup($USER_GROUP_ID)
{
	global $USER;

	$arFilter = array(
		"GROUPS_ID" => array($USER_GROUP_ID),
	);

	$arSelect = array(
		"FIELDS" => array("ID", "ACTIVE"),
		"SELECT" => array("UF_ID", "UF_ID6", "UF_MSCSPRING2016", "UF_MSCSPRING2016COL")
	);

	#�������� ������ ������������� �� ������ �������������� �������������
	$rsUsers = $USER->GetList(
		$by="id",
		$order="asc",
		$arFilter,
		$arSelect
	);

	$arUsers = array();
	while($arUser = $rsUsers->fetch())
	{
		$arUsers[$arUser["ID"]] = $arUser;
	}

	return $arUsers;
}


/**
 * ����� ������ � �������
 * @param $arData - ������
 * @param bool $dump - �������� ����� var_export
 */
function show($arData, $dump = false)
{
	ob_start();
	if($dump)
	{
		echo var_export($arData, true) . PHP_EOL;
	}
	else
	{
		echo print_r($arData, true) . PHP_EOL;
	}
	$result = ob_get_contents();
	ob_end_clean();

	echo iconv("CP1251", "UTF-8", $result);

}

/**
 * ������������ ��������� �������� � id 4, �������� ����������
 * @param $resultId - id ���������� ��������
 * @param $formID - id ����� � ������� �������������� ������
 * @return array - ������, ������� ����� ������������ ��� ���������� ���������� ��������
 */
function convertAnswerById($resultId, $formID)
{
	$answerid = array("text", "textarea", "password", "date", "file", "image", "hidden");
	$answersid = array("dropdown", "checkbox", "multiselect", "radio");
	$arZal = array(
		"1323" => "1460",	//None
		"1314" => "1461",	//Ballroom Hall
		"1315" => "1462",	//Istanbul I Hall
		"1316" => "1463",	//Istanbul II Hall
		"1317" => "1464",	//Moscow Hall
		"1318" => "1465",	//Washington Hall
		"1320" => "1466",	//O2 Lounge (12th Floor)
		"1321" => "1467",	//Pre-function
		"1322" => "1468",	//Almaty Hall
	);

	$arNewFields = array();
	$arAnswer = CFormResult::GetDataByID(
		$resultId,
		array(
			"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//������������ ����
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_539",//����� �����
			"SIMPLE_QUESTION_680",//����� �����
			"SIMPLE_QUESTION_667",//���������
			"SIMPLE_QUESTION_148",//����
			"SIMPLE_QUESTION_732",//���
		),
		$arResult,
		$arAnswerSID);

	#����������� ��������� ��� ����� �����
	foreach($arAnswerSID as $SID =>$arAnswer)
	{
		$question = reset($arAnswer);

		if(in_array($question["FIELD_TYPE"], $answerid))
		{
			$fieldType = "ANSWER_ID";
		}
		elseif(in_array($question["FIELD_TYPE"], $answersid))
		{
			$fieldType = "SID";
		}
		else
		{
			break;
		}

		switch ($question["FIELD_TYPE"])
		{
			case "radio" :
			case "dropdown" :
			case "checkbox" : $propAnswer = "ANSWER_ID"; break;
			case "text" : $propAnswer = "USER_TEXT"; break;
			case "image" : $propAnswer = "USER_FILE_ID"; break;
			default: $propAnswer = "USER_TEXT";
		}


		$fieldName = "form_" . $question["FIELD_TYPE"] . "_" . CFormMatrix::getAnswerRelBase($question[$fieldType], $formID);

		if("image" == $question["FIELD_TYPE"])
		{
			$value = CFile::MakeFileArray($question[$propAnswer]);
		}
		elseif("dropdown" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_889")//Salutation
		{
			$value = CFormMatrix::getAnswerSalutationRelBase($question["ANSWER_ID"], $formID);
		}
		elseif("radio" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_667")//���������
		{
			$value = CFormMatrix::getIndexRequisiteRelBase($question["ANSWER_ID"], $formID);
		}
		elseif("dropdown" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_732")//���
		{
			$value = $arZal[$question["ANSWER_ID"]]; //������� �� ���� ���, � ������� ����� - ���� ������� � ������ �����?  � �������� - ���� ������� � �����
		}
		else
		{
			$value = $question[$propAnswer];
		}

		$arNewFields[$fieldName] = $value;
	}


	return $arNewFields;
}