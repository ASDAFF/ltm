<? /* TODO посмотреть какие поля реально нужны для блока со встречами
    * ID, CODE, PROPERTY_APP_ID, PROPERTY_APP_HB_ID, PROPERTY_V_EN
  * Переписать определение данных об участниках из форм. Сделать 1 запрос, а не много маленьких
  * Переписать чтобы генерировалось в фоне
  * Убрать все лишнее
  */

set_time_limit(0);
ignore_user_abort(true);

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}
if (!CModule::IncludeModule("doka.meetings") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

$arResult = array();
if(isset($arParams["EXIB_CODE"]) && $arParams["EXIB_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array( "IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
					"CODE" => $arParams["EXIB_CODE"] ),
			false,
			false,
			array("ID", "CODE","IBLOCK_ID","PROPERTY_*")
			);
	while($oExhib = $rsExhib->GetNextElement(true, false))
	{
		$arResult["PARAM_EXHIBITION"] = $oExhib->GetFields();
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
		unset($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["MORE_PHOTO"]);
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
		}
		else{
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
		}
		$arParams["APP_ID"] = $appId;
	}
}

if (empty($arParams["APP_ID"])) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

if(empty($arParams["USER_TYPE"])){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(empty($arParams["EMAIL"])){
	$arParams["EMAIL"] = "info@luxurytravelmart.ru";
}

/* Настройки вывода PDF */
$arResult['USER_TYPE'] = $arParams["USER_TYPE"];
$arResult['APP_ID'] = $arParams['APP_ID'];
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');
$arResult['CUT'] = $arParams['CUT'];
$arResult['HALL'] = "";
$arResult['TABLE'] = "";
$arResult['CITY'] = "";

$exhibitionParam = array();
$exhibitionParam["IS_HB"] = $arParams["IS_HB"];
$exhibitionParam["TITLE"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'];
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$exhibitionParam["TITLE"] .= " Hosted Buyers session";
}
$exhibitionParam["CUT"] = $arParams['CUT'];


/* Поля для участников */
$guestFields = CFormMatrix::$arExelGuestField;
$fioParticip = "";
$formId = $req_obj->getOption('FORM_ID');
$propertyNameParticipant = $req_obj->getOption('FORM_RES_CODE');//свойство участника
$fio_dates = array();
$fio_dates[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);
$fio_dates[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);//Имя участника
$fio_dates[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);
$fio_dates[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);//Фамилия участника
$fio_dates[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_148', $formId);
$fio_dates[2][1] = CFormMatrix::getAnswerRelBase(1319 ,$formId);//Стол участника
$fio_dates[3][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_732', $formId);
$fio_dates[3][1] = CFormMatrix::getAnswerRelBase('SIMPLE_QUESTION_732' ,$formId);//Зал участника

$timeslots = $req_obj->getTimeslots();
$meet_timeslots = $req_obj->getMeetTimeslotsIds();
$statuses_free = $req_obj->getStatusesFree();

// Определяем для какой группы генерировать расписание
if ($arResult['USER_TYPE'] != 'PARTICIP'){
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
	$group_opposite_id = $req_obj->getOption('MEMBERS_GROUP');}
else{
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
	$group_opposite_id = $req_obj->getOption('GUESTS_GROUP');}

// Полный список компаний из групп участников и гостей
$users_list = array();

// Добавляем участников
$selectPart = array( 'SELECT' => array($propertyNameParticipant),
	'FIELDS' => array('WORK_COMPANY', 'ID') );
$filter = array( "GROUPS_ID"  => array($req_obj->getOption('MEMBERS_GROUP')) );
$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectPart);
while ($arUser = $rsUsers->Fetch()) {
	$arAnswer = CFormResult::GetDataByID(
		$arUser[$propertyNameParticipant],
		array(),
		$arResultTmp,
		$arAnswer2);
	$users_list[$arUser['ID']] =  array(
		'id' => $arUser['ID'],
		'name' => $arUser['WORK_COMPANY'],
		'repr_name' => trim($arAnswer2[$fio_dates[0][0]][$fio_dates[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio_dates[1][0]][$fio_dates[1][1]]["USER_TEXT"]),
		'hall' => "",
		'table' => "",
		'city' => ""
	);
	foreach($arAnswer2[$fio_dates[3][0]] as $value){
		$users_list[$arUser['ID']]['hall'] = $value["MESSAGE"];
	}
	foreach($arAnswer2[$fio_dates[2][0]] as $value){
		$users_list[$arUser['ID']]['table'] = $value["USER_TEXT"];
	}
}

//Добавляем гостей, они могу тбыть HB
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$filter = array( "GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_HB" => "1" );
}
else{
	$filter = array( "GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_MR" => "1" );
}
$selectGuest = array('FIELDS' => array('WORK_COMPANY', 'ID', 'NAME', 'LAST_NAME'));
$rsGUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectGuest);
while ($arUser = $rsGUsers->Fetch()) {
	$arAnswer = CFormResult::GetDataByID(
		$arUser[$propertyNameParticipant],
		array(),
		$arResultTmp,
		$arAnswer2);
	$users_list[$arUser['ID']] =  array(
		'id' => $arUser['ID'],
		'name' => $arUser['WORK_COMPANY'],
		'repr_name' => $arUser["NAME"]." ".$arUser["LAST_NAME"],
		'hall' => "",
		'table' => "",
		'city' => ""
	);
	foreach($arAnswer2[$guestFields["QUEST_CODE"][11]] as $value){
		$users_list[$arUser['ID']]['city'] = $value["USER_TEXT"];
	}
	if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
		foreach($arAnswer2[$guestFields["QUEST_CODE"][25]] as $value){
			$users_list[$arUser['ID']]['hall'] = $value["MESSAGE"];
		}
		foreach($arAnswer2[$guestFields["QUEST_CODE"][26]] as $value){
			$users_list[$arUser['ID']]['table'] = $value["USER_TEXT"];
		}
	}
}

// Список компаний, для которых выведем занятость
$rsCompanies = $req_obj->getAllMeetTimesByGroup($group_search_id);

$path = '/upload/pdf/'.strtolower($arParams["EXIB_CODE"]).'/';
$shotPath = '/upload/pdf/';
CheckDirPath($_SERVER['DOCUMENT_ROOT'].$path);
$pdfFolder = $_SERVER['DOCUMENT_ROOT'].$path;

require(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/tcpdf.php');
require_once(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/templates/schedule_all_' . $arParams['USER_TYPE'] . '.php');
while ($data = $rsCompanies->Fetch()) {
	$pdfName = str_replace(" ", "_", $data["WORK_COMPANY"])."_".$data["ID"].".pdf";
	$pdfName = str_replace("/", "", $pdfName);
	$pdfName = str_replace("*", "", $pdfName);
	$company = array(
		'id' => $data['ID'],
		'name' => $data['WORK_COMPANY'],
		'rep' => $users_list[$data['ID']]['repr_name'],
		'hall' => $users_list[$data['ID']]['hall'],
		'table' => $users_list[$data['ID']]['table'],
		'city' => $users_list[$data['ID']]['city'],
		'path' => $pdfFolder.$pdfName,
		'schedule' => array(),
		'exhib' => $exhibitionParam
	);

	$statuses = $req_obj->getTimslotsStatuses($data);
	//echo "<pre>"; var_dump($data); echo "</pre>";
	// Если пользователя нет в таблице занятости, значит у него все слоты свободны
	if ($data['USER_ID'] === null) {
		foreach ($meet_timeslots as $timeslot_id) {
			$company['schedule'][$timeslot_id][] = array(
				'id' => $timeslot_id,
				'name' => $timeslots[$timeslot_id]['name'],
				'status' => DokaRequest::getStatusCode($statuses[$timeslot_id]),
			);
		}
	} else {
		foreach ($statuses as $timeslot_id => $status_id) {
			if ( in_array($timeslot_id, $meet_timeslots)) {
				$curMeet = array( "status" => "free", "modified_by" => "", "company_id" => "");
				$schedule = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslots[$timeslot_id]['name'],
					'status' => DokaRequest::getStatusCode($statuses[$timeslot_id]),
					'is_busy' => false,
					'notes' => ""
				);
				// если слот занят
				if ( !in_array($statuses[$timeslot_id], $statuses_free) ) {
					$user_is_sender = $data['MEET_'.$timeslot_id] % 10;
					$user_id = substr($data['MEET_'.$timeslot_id], 0, -1);
					$schedule['company_id'] = $users_list[$user_id]['id'];
					$schedule['company_name'] = $users_list[$user_id]['name'];
					$schedule['company_rep'] = $users_list[$user_id]['repr_name'];
					$schedule['user_is_sender'] = $user_is_sender;
					$schedule['is_busy'] = true;
					$curMeet["status"] = $schedule['status'];
					$curMeet["modified_by"] = $user_id;
					$curMeet["company_id"] = $schedule['company_id'];
				}
				$schedule["notes"] = DokaGetNote( $curMeet, $arResult['USER_TYPE'], $data['ID']);

				$company['schedule'][ $timeslot_id ] = $schedule;
			}
		}
	}

	/* Формируем сам pdf */
	$APPLICATION->RestartBuffer();
	$company['exhibition'] = $req_obj->getOptions();
	DokaGeneratePdf($company);
}
/* Создание архива и удаление папки */
include_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/lib/pclzip.lib.php"); //Подключаем библиотеку.
$archive = new PclZip($_SERVER['DOCUMENT_ROOT'].$shotPath.$arParams["EXIB_CODE"].'.zip'); //Создаём объект и в качестве аргумента, указываем название архива, с которым работаем.
$result = $archive->create($pdfFolder, PCLZIP_OPT_REMOVE_PATH, $_SERVER['DOCUMENT_ROOT'].$shotPath); // Этим методом класса мы создаём архив с заданным выше названием
if($result == 0) {
	echo $archive->errorInfo(true); //Возращает причину ошибки
}
else{
	$arEventFields = array(
		"EMAIL" => $arParams["EMAIL"],
		"EXIBITION" => $exhibitionParam["TITLE"],
		"TYPE" => "расписание",
		"USER_TYPE" => strtolower($arParams["USER_TYPE"]),
		"LINK" => "http://".$_SERVER['SERVER_NAME'].$shotPath.strtolower($arParams["EXIB_CODE"]).'.zip'
	);
	CEvent::SendImmediate("ARCHIVE_READY ", "s1", $arEventFields);
}

fullRemove_ff($pdfFolder);


function DokaGetNote($meet, $user_type, $curUser) {
	global $USER, $arParams;
	switch ($meet['status']) {
		case 'process':
			if ($meet['modified_by'] == $curUser)
				$msg = GetMessage($user_type.'_SENT_BY_YOU');
			else
				$msg = GetMessage($user_type.'_SENT_TO_YOU');
			break;
		case 'confirmed':
			if ($meet['modified_by'] == $curUser || $meet['modified_by'] == $meet['company_id'])
				$msg = GetMessage($user_type.'_CONFIRMED');
			else
				$msg = GetMessage($user_type.'_CONFIRMED_BY_ADMIN');
			break;

		default:
			$msg = GetMessage($user_type.'_SLOT_EMPTY');
			break;
	}
	return $msg;
}

function fullRemove_ff($path,$t="1") {
	$rtrn="1";
	if (file_exists($path) && is_dir($path)) {
		$dirHandle = opendir($path);
		while (false !== ($file = readdir($dirHandle))) {
			if ($file!='.' && $file!='..') {
				$tmpPath=$path.'/'.$file;
				chmod($tmpPath, 0777);
				if (is_dir($tmpPath)) {
					fullRemove_ff($tmpPath);
				} else {
					if (file_exists($tmpPath)) {
						unlink($tmpPath);
					}
				}
			}
		}
		closedir($dirHandle);
		if ($t=="1") {
			if (file_exists($path)) {
				rmdir($path);
			}
		}
	} else {
		$rtrn="0";
	}
	return $rtrn;
}
?>