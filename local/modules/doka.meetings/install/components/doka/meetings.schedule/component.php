<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (empty($arParams["USER_TYPE"])) {
	ShowError(GetMessage("ERROR_EMPTY_USER_TYPE"));
	return;
}
if (empty($arParams["USER_ID"]))
	$arParams['USER_ID'] = $USER->GetID();

if (!$USER->IsAuthorized() || $arParams['USER_ID'] <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

$timeslots = $req_obj->getTimeslots();
$busy_timeslots = $req_obj->getAllTimesByComp();

// Определяем для какой группы искать свободные слоты компаний
$arGroups = $USER->GetUserGroupArray();
if (in_array($req_obj->getOption('GUESTS_GROUP'), $arGroups))
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
else
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');

// Получаем полный список компаний со свободными таймслотами
$companies_schedule = $req_obj->getFreeTimesByGroup($group_search_id);

$arResult = array();
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');
$arResult['MESSAGE'] = '';
$arResult['MESSAGE_LINK'] = $arParams['MESSAGE_LINK'];
$arResult['SEND_REQUEST_LINK'] = $arParams['SEND_REQUEST_LINK'];
$arResult['CONFIRM_REQUEST_LINK'] = $arParams['CONFIRM_REQUEST_LINK'];
$arResult['REJECT_REQUEST_LINK'] = $arParams['REJECT_REQUEST_LINK'];
$arResult['CUT'] = $arParams['CUT'];
$arResult['HALL'] = $arParams['HALL'];
$arResult['TABLE'] = $arParams['TABLE'];

if(!$arResult['IS_ACTIVE']) {
	$arResult['MESSAGE'] = GetMessage($arParams['USER_TYPE'] . '_IS_LOCKED'); 
}
$arResult['USER_TYPE'] = $req_obj->getUserType();
$arResult['CURRENT_USER_ID'] = $USER->GetID();

$arResult['SCHEDULE'] = array();

foreach ($timeslots as $timeslot_id => $item) {
	if (array_key_exists($timeslot_id, $busy_timeslots)) {
		$meet = $busy_timeslots[$timeslot_id]['meet'];
		$arResult['SCHEDULE'][] = array(
			'timeslot_id' => $timeslot_id,
			'name' => $item['name'],
			'status' => $meet['status'],
			'notes' => DokaGetNote($meet, $arParams['USER_TYPE']),
			'sent_by_you' => ($meet['company_id'] != $USER->GetID()),
			'company_name' => $meet['company_name'],
			'company_rep' => $meet['company_rep'],
			'company_id' => $meet['company_id'],
		);
	} else if (in_array($item['type'], DokaTimeslot::getMeetTypeCodes())) {
		$arResult['SCHEDULE'][] = array(
			'timeslot_id' => $timeslot_id,
			'name' => $item['name'],
			'status' => 'free',
			'notes' => DokaGetNote(array(), $arParams['USER_TYPE']),
			'list' => array_key_exists($timeslot_id, $companies_schedule) ? $companies_schedule[$timeslot_id] : array()
		);
	}
}

if ( isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf' ) {
	require(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/tcpdf.php');
	require_once(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/templates/schedule_' . $arParams['USER_TYPE'] . '.php');

	$APPLICATION->RestartBuffer();
	$arResult['EXHIBITION'] = $req_obj->getOptions();
	// Информация о пользователе, для которого генерируем pdf
    $filter = array( 'ID' => $arParams['USER_ID'] );
    $select = array(
        'SELECT' => array($req_obj->getOption('REPR_PROP_CODE')),
        'FIELDS' => array('WORK_COMPANY', 'ID')
    );
    $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
    if ($arUser = $rsUser->Fetch()) {
        $arResult['USER'] = array(
        	'REP' => $arUser[$req_obj->getOption('REPR_PROP_CODE')],
        	'COMPANY' => $arUser['WORK_COMPANY'],
        	'CITY' => 'CITY',
        );
		DokaGeneratePdf($arResult);
    }
}

$this->IncludeComponentTemplate();

function DokaGetNote($meet, $user_type) {
	global $USER, $arParams;
	switch ($meet['status']) {
		case 'process':
			if ($meet['company_id'] == $USER->GetID())
				$msg = GetMessage($user_type.'_SENT_TO_YOU');
			else
				$msg = GetMessage($user_type.'_SENT_BY_YOU');
			break;
		case 'confirmed':
			if ($meet['modified_by'] == $USER->GetID() || $meet['modified_by'] == $meet['company_id'])
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

?>