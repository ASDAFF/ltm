<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if(!isset($arParams["EXHIB_IBLOCK_ID"]) || $arParams["EXHIB_IBLOCK_ID"] == ''){
	$arParams["EXHIB_IBLOCK_ID"] = 15;
}
$arResult = array();

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (!$USER->IsAuthorized()) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

$timeslot_id = intval($arParams["TIME"]);

if ($timeslot_id <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_TIMESLOT_ID"));
	return;
}


use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);
$statusReserve = $req_obj->getStatusCode($req_obj::STATUS_RESERVE);
$statusFree = $req_obj->getStatusesFree();

$arResult["APP"] = $arParams['APP_ID'];
$arResult['USER_TYPE'] = $req_obj->getUserType();
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

if (isset($_REQUEST['id']) && $arResult['USER_TYPE'] == 'ADMIN' )
	$userId = intval($_REQUEST['id']);
else
	$userId = $USER->GetID();

if(isset($_REQUEST["type"]) && $_REQUEST["type"] == "p" && $USER->GetID() == 1){
	$arResult['USER_TYPE'] = "PARTICIP";
}
elseif(isset($_REQUEST["type"]) && $USER->GetID() == 1){
	$arResult['USER_TYPE'] = "GUEST";
}

if($arResult['USER_TYPE'] != "PARTICIP") {
	ShowError(GetMessage("ERROR_NOT_PARTICIP"));
	return;
}
// Проверяем существует ли такой таймслот
$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($timeslot_id);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
}
$companyTimeslot = $req_obj->getAllTimesByComp($userId);
$arResult['TO_RESERVE'] = 'Y';
if(isset($companyTimeslot[$timeslot_id]) && $companyTimeslot[$timeslot_id]['meet']['status'] == $statusReserve) {
	$arResult['TO_RESERVE'] = 'N';
}

$fields = array(
	'RECEIVER_ID' => $userId,
	'SENDER_ID' => $userId,
	'EXHIBITION_ID' => $arParams['APP_ID'],
	'TIMESLOT_ID' => $arResult['TIMESLOT']['id'],
	'STATUS' => '',
);
if($arResult['TO_RESERVE'] == 'N') {
	$request = $req_obj->getActiveRequest($timeslot_id, $userId, $userId);
	$req_obj->rejectRequest($request);
} else {
	$companyFreeTimes = $req_obj->getFreeTimesIdsByComp($userId);
	if(!in_array($timeslot_id, $companyFreeTimes)) {
		ShowError(GetMessage("ERROR_TIMESLOT_BUSY"));
		return;
	}
	$req_obj->reserveRequest($fields);
}

$this->IncludeComponentTemplate();
?>