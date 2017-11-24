<?/* TODO посмотреть какие поля реально нужны для блока со встречами
    * ID, CODE, PROPERTY_APP_ID, PROPERTY_APP_HB_ID, PROPERTY_V_EN, PROPERTY_V_RU
  * Переписать определение данных об участниках из форм. Сделать 1 запрос, а не много маленьких
  * Убрать все лишнее
  */

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}
if (!CModule::IncludeModule("doka.meetings") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if(!isset($arParams["EXHIB_IBLOCK_ID"]) || $arParams["EXHIB_IBLOCK_ID"] == ''){
	$arParams["EXHIB_IBLOCK_ID"] = 15;
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
$exhibitionParam["TITLE_RU"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'];
$exhibitionParam["HB_EXIST"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["HB_EXIST"]['VALUE'];
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$exhibitionParam["TITLE"] .= " Hosted Buyers session";
	$exhibitionParam["TITLE_RU"] .= " Hosted Buyers сессия";
}
$exhibitionParam["CUT"] = $arParams['CUT'];


/* Поля для участников */
$guestFields = CFormMatrix::$arExelGuestField;
/* Определяем индекс поля для Города, Стола и Зала */
$guestQuestCode = array_flip($guestFields["NAMES_AR"]);
$guestFieldsIndex = array(
	"CITY" => $guestQuestCode["CITY"],
	"HALL" => $guestQuestCode["HALL"],
	"TABLE" => $guestQuestCode["TABLE"],
	"F_NAME" => $guestQuestCode["F_NAME"],
	"L_NAME" => $guestQuestCode["L_NAME"],
	"F_NAME_COL" => $guestQuestCode["F_NAME_COL"],
	"L_NAME_COL" => $guestQuestCode["L_NAME_COL"],
	"MOB_PHONE" => $guestQuestCode["MOB_PHONE"],
	"PHONE" => $guestQuestCode["PHONE"],
);
unset($guestQuestCode);

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
		'col_repr'=> "",
		'mob'=> "",
		'phone'=> "",
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
$selectGuest = array('FIELDS' => array('WORK_COMPANY', 'ID', 'NAME', 'LAST_NAME'), 'SELECT' => array($propertyNameParticipant));
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
		'col_repr'=> "",
		'mob'=> "",
		'phone'=> "",
		'hall' => "",
		'table' => "",
		'city' => ""
	);
	foreach($arAnswer2[$guestFields["QUEST_CODE"][ $guestFieldsIndex["CITY"] ]] as $value){
		$users_list[$arUser['ID']]['city'] = $value["USER_TEXT"];
	}
	foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["F_NAME_COL"] ] ] as $value){
		$users_list[$arUser['ID']]['col_repr'] = trim($value["USER_TEXT"]);
	}
	foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["L_NAME_COL"] ] ] as $value){
		$users_list[$arUser['ID']]['col_repr'] .= " ".trim($value["USER_TEXT"]);
	}
	foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["MOB_PHONE"] ] ] as $value){
		$users_list[$arUser['ID']]['mob'] = trim($value["USER_TEXT"]);
	}
	foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["PHONE"] ] ] as $value){
		$users_list[$arUser['ID']]['phone'] = trim($value["USER_TEXT"]);
	}

	if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
		foreach($arAnswer2[$guestFields["QUEST_CODE"][ $guestFieldsIndex["HALL"] ]] as $value){
			$users_list[$arUser['ID']]['hall'] = $value["MESSAGE"];
		}
		foreach($arAnswer2[$guestFields["QUEST_CODE"][ $guestFieldsIndex["TABLE"] ]] as $value){
			$users_list[$arUser['ID']]['table'] = $value["USER_TEXT"];
		}
	}
}

// Список компаний, для которых выведем занятость
$rsCompanies = $req_obj->getAllMeetTimesByGroup($group_search_id);
$isHB = '';
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y')
	$isHB = "_hb";
$shotPath = '/upload/pdf/'.strtolower($arParams['USER_TYPE']).'/';
$path = $shotPath.strtolower($arParams["EXIB_CODE"]).$isHB.'/';
CheckDirPath($_SERVER['DOCUMENT_ROOT'].$path);
$pdfFolder = $_SERVER['DOCUMENT_ROOT'].$path;

@unlink($_SERVER['DOCUMENT_ROOT'].$shotPath.$arParams["EXIB_CODE"].$isHB.'.zip');
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
		'col_rep' => $users_list[$data['ID']]['col_repr'],
		'mob' => $users_list[$data['ID']]['mob'],
		'phone' => $users_list[$data['ID']]['phone'],
		'hall' => $users_list[$data['ID']]['hall'],
		'table' => $users_list[$data['ID']]['table'],
		'city' => $users_list[$data['ID']]['city'],
		'path' => $pdfFolder.$pdfName,
		'schedule' => array(),
		'exhib' => $exhibitionParam,
		'APP_ID' => $arResult['APP_ID']
	);

	$statuses = $req_obj->getTimslotsStatuses($data);
	$meetTypes = DokaTimeslot::getMeetTypeCodes();
	//echo "<pre>"; var_dump($data); echo "</pre>";
	// Если пользователя нет в таблице занятости, значит у него все слоты свободны
	if ($data['USER_ID'] === null) {
		foreach ($timeslots as $timeslot_id=>$timeslotValue) {
			if(!in_array($timeslot_id, $meet_timeslots )){
				$company['schedule'][$timeslot_id] = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslotValue['name'],
					'status' => $timeslotValue['type'],
					'notes' => $timeslotValue['type'],
				);
			}
			else{
				$company['schedule'][$timeslot_id] = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslotValue['name'],
					'status' => 'free',
				);
			}
		}
	} else {
		//Получаем расписание для текущей компании
		$busy_timeslots = $req_obj->getAllTimesByComp($data['USER_ID']);
		$shedule = [];
		foreach ($timeslots as $timeslot_id => $timeslotValue) {
			if (array_key_exists($timeslot_id, $busy_timeslots)) {
				$meet = $busy_timeslots[$timeslot_id]['meet'];
				$user_id = substr($data['MEET_' . $timeslot_id], 0, -1);
				$shedule = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslotValue['name'],
					'status' => $meet['status'],
					'notes' => DokaGetNote($meet, $arResult['USER_TYPE'], $data["ID"]),
					'user_is_sender' => ($meet['modified_by'] == $data["ID"]),
					'company_name' => $users_list[$user_id]['name'],
					'company_rep' => $users_list[$user_id]['repr_name'],
					'company_id' => $users_list[$user_id]['id'],
					'is_busy' => true,
					'hall' => $users_list[$user_id]['hall'],
					'table' => $users_list[$user_id]['table'],
				);
			} else if (in_array($timeslotValue['type'], $meetTypes)) {
				$shedule = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslotValue['name'],
					'status' => 'free',
					'is_busy' => false,
					'notes' => ""
				);
			} else{
				$shedule = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslotValue['name'],
					'status' => $timeslotValue['type'],
					'notes' => $timeslotValue['type'],
				);
			}
			$company['schedule'][$timeslot_id] = $shedule;
		}
	}
	/* Формируем сам pdf */
	$APPLICATION->RestartBuffer();
	$company['exhibition'] = $req_obj->getOptions();
	DokaGeneratePdf($company);
}
/**-----------------------------------
 * Создание архива и удаление папки
 * $pdfFolder - исходная папка
 * $fileName - имя архива
 -----------------------------------*/

$fileName = $_SERVER['DOCUMENT_ROOT'].$shotPath.$arParams["EXIB_CODE"].$isHB.'.zip';//имя файла архива
MakeZipArchive($pdfFolder,$fileName);
if(file_exists($fileName) && is_file($fileName) && filesize($fileName)>0){
    $arEventFields = array(
        "EMAIL" => $arParams["EMAIL"],
        "EXIBITION" => $exhibitionParam["TITLE"],
        "TYPE" => "расписание",
        "USER_TYPE" => strtolower($arParams["USER_TYPE"]),
        "LINK" => "http://".$_SERVER['SERVER_NAME'].$shotPath.strtolower($arParams["EXIB_CODE"]).$isHB.'.zip'
    );
    CEvent::SendImmediate("ARCHIVE_READY", "s1", $arEventFields, $Duplicate = "Y");
}
fullRemove_ff($pdfFolder);
/** --------------------------------- */


function DokaGetNote($meet, $user_type, $curUser) {
	switch ($meet['status']) {
		case 'process':
			if ($meet['modified_by'] == $curUser)
				$msg = GetMessage($user_type.'_SENT_BY_YOU');
			else
				$msg = GetMessage($user_type.'_SENT_TO_YOU');
			break;
		case 'confirmed':
			if($meet['modified_by'] == $curUser) {
				$msg = GetMessage($user_type.'_CONFIRMED_SELF');
			} elseif($meet['modified_by'] == $meet['company_id']) {
				$msg = GetMessage($user_type.'_CONFIRMED');
			} else {
				$msg = GetMessage($user_type.'_CONFIRMED_BY_ADMIN');
			}
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
