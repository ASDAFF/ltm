<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (!$USER->IsAuthorized()) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

$timeslot_id = intval($_REQUEST['timeslot_id']);
if ($timeslot_id <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_TIMESLOT_ID"));
	return;
}
$receiver_id = intval($_REQUEST['receiver_id']);
if ($receiver_id <= 0) {
	ShowError(GetMessage("ERROR_WRONG_RECEIVER_ID"));
	return;
}

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

$arResult = array();
$arResult['USER_TYPE'] = $req_obj->getUserType();
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

if (isset($_REQUEST['sender_id']) && $arResult['USER_TYPE'] == 'ADMIN' )
	$sender_id = intval($_REQUEST['sender_id']);
else
	$sender_id = $USER->GetID();

// Данные таймслота
$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($timeslot_id);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
}

// Инфо об отправителе
$arResult['SENDER'] = $req_obj->getUserInfo($sender_id);
if (!$arResult['SENDER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_SENDER_ID');
}
// Инфо о получателе
$arResult['RECEIVER'] = $req_obj->getUserInfo($receiver_id);
if (!$arResult['RECEIVER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}


$arResult['CONFIRM_SUCCESS'] = false;
$request = $req_obj->getActiveRequest($timeslot_id, $sender_id, $receiver_id);

if (!$request) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_REQUEST_NOT_FOUND');
} else {
	// Дополнительные проверки
	$valid = true;
	$arSenderGroups = CUser::GetUserGroup($request['SENDER_ID']);
	switch ($arResult['USER_TYPE']) {
		case 'ADMIN':
			$valid = true;
			break;
		case 'PARTICIP':
			$valid = in_array($req_obj->getOption('GUESTS_GROUP'), $arSenderGroups);
			break;
		case 'GUEST':
			$valid = in_array($req_obj->getOption('MEMBERS_GROUP'), $arSenderGroups);
			break;
	}
	if (!$valid) {
		$arResult['ERROR_MESSAGE'][] = GetMessage('ERROR_GROUP_SENDER');
	}

	if ($request['STATUS'] != DokaRequest::getStatusCode(DokaRequest::STATUS_PROCESS)) {
		$arResult['ERROR_MESSAGE'][] = GetMessage('ERROR_STATUS');
	}
	
}

// Если ошибок не было и встречи не заблокированы
if (!isset($arResult['ERROR_MESSAGE']) && ($arResult['IS_ACTIVE'] || $arResult['USER_TYPE'] == 'ADMIN')) {
	// Подтверждаем запрос
	$req_obj->confirmRequest($request);
	$arResult['CONFIRM_SUCCESS'] = true;
} else if(!$arResult['IS_ACTIVE']) {
	$arResult['ERROR_MESSAGE'][] = 'Встречи заблокированы';
}

$this->IncludeComponentTemplate();
?>