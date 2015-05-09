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

//подключаем ядро битрикса
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);

#Подключение модулей
CModule::includeModule("form");

global $USER;
if(!is_object($USER))
{
	$USER = new CUser();
}


#Константы

#Id старой вебформы
$OLD_FORM_ID = 4;

#Id Новой вебформы
$NEW_FORM_ID = 29;

#id группы пользователей подтвержденных на выставку
$USER_GROUP_ID = 10;

$arUsers = getUserByGroup($USER_GROUP_ID);

foreach($arUsers as $arUser)
{
	#получаем результат вебформы для участника
	$arFormDataParticipant = convertAnswerById($arUser["UF_ID"], $NEW_FORM_ID);
	$arFormDataColleague = convertAnswerById($arUser["UF_ID6"], $NEW_FORM_ID);

	//копируем результат участника
	$resParticipantID = CFormResult::Add($NEW_FORM_ID, $arFormDataParticipant);
	//копируем результат коллеги
	$resColleagueID = CFormResult::Add($NEW_FORM_ID, $arFormDataColleague);

	//обновляем данные пользователя
	$USER->Update($arUser["ID"], array("UF_MSCSPRING2016" => $resParticipantID, "UF_MSCSPRING2016COL" => $resColleagueID));

}










#функции

/**
 * Получаем список пользователей находящихся в определенной группе
 * @param $USER_GROUP_ID - id группы пользователей
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

	#получаем список пользователей из группы подтвержденных пользователей
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
 * Вывод данных в консоль
 * @param $arData - данные
 * @param bool $dump - выводить через var_export
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
 * Конвертирует результат вебформы с id 4, заданную параметром
 * @param $resultId - id результата вебформы
 * @param $formID - id формы в которую конвертируются данные
 * @return array - массив, который можно использовать для добавления результата вебформы
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
			"SIMPLE_QUESTION_575",//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_539",//Номер счета
			"SIMPLE_QUESTION_680",//Сумма счета
			"SIMPLE_QUESTION_667",//Реквизиты
			"SIMPLE_QUESTION_148",//Стол
			"SIMPLE_QUESTION_732",//Зал
		),
		$arResult,
		$arAnswerSID);

	#преобразуем результат для новой формы
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
		elseif("radio" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_667")//Реквизиты
		{
			$value = CFormMatrix::getIndexRequisiteRelBase($question["ANSWER_ID"], $formID);
		}
		elseif("dropdown" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_732")//Зал
		{
			$value = $arZal[$question["ANSWER_ID"]]; //костыль на один раз, в массиве ключи - айди ответов в старой форме?  в значении - айди ответов в новой
		}
		else
		{
			$value = $question[$propAnswer];
		}

		$arNewFields[$fieldName] = $value;
	}


	return $arNewFields;
}