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

// Отправка приглашения самому себе
if ($sender_id == $receiver_id) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}


$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($timeslot_id);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
}

// Инфо об отправителе
$arResult['SENDER'] = $req_obj->getUserInfo($sender_id);
if (!$arResult['SENDER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_SENDER_ID');
}

$arResult['RECEIVER'] = $req_obj->getUserInfo($receiver_id);
if (!$arResult['RECEIVER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}


// Сохранение запроса
if (!isset($arResult['ERROR_MESSAGE']) && isset($_POST['submit'])) {
	// Проверим, свободны ли таймслоты
	if ($req_obj->checkTimeslotIsFree( $arResult['TIMESLOT']['id'], array($arResult['RECEIVER']['company_id'], $arResult['SENDER']['company_id']) )) {
	    $fields = array(
	        'RECEIVER_ID' => $arResult['RECEIVER']['company_id'],
	        'SENDER_ID' => $arResult['SENDER']['company_id'],
	        'EXHIBITION_ID' => $arParams['APP_ID'],
	        'TIMESLOT_ID' => $arResult['TIMESLOT']['id'],
	        'STATUS' => ($arResult['USER_TYPE'] == 'ADMIN') ? DokaRequest::getStatusCode(DokaRequest::STATUS_CONFIRMED) : DokaRequest::getStatusCode(DokaRequest::STATUS_PROCESS),
	    );
		$req_obj->Add($fields);

	} else {
		$arResult['FORM_ERROR'] = 'ошибка отправки';
	}
	
	$arResult['REQUEST_SENT'] = true;
	

}

$this->IncludeComponentTemplate();
?>