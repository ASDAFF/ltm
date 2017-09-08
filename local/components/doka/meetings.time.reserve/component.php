<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
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

$arResult["TIME"] = intval($arParams["TIME"]);
$arResult["USER_ID"] = intval($_REQUEST['id']);

if ($arResult["TIME"] <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_TIMESLOT_ID"));
	return;
}

use Doka\Meetings\Requests as DokaRequest;

$req_obj = new DokaRequest($arParams['APP_ID']);
$statusReserve = $req_obj->getStatusCode($req_obj::STATUS_RESERVE);
$statusFree = $req_obj->getStatusesFree();

$arResult["APP"] = $arParams['APP_ID'];
$arResult['USER_TYPE'] = $req_obj->getUserType();
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

if(!$arResult['IS_ACTIVE'] && $arResult['USER_TYPE'] != 'ADMIN') {
	$arResult['ERROR_MESSAGE'][] = GetMessage("EXHIBITION_BLOCKED");
	$this->IncludeComponentTemplate();
	return;
}

if (empty($arResult["USER_ID"]) || $arResult['USER_TYPE'] != 'ADMIN' )
	$arResult["USER_ID"] = $USER->GetID();

if(isset($_REQUEST["type"]) && $_REQUEST["type"] == "p" && $USER->GetID() == 1){
	$arResult['USER_TYPE'] = "PARTICIP";
}
elseif(isset($_REQUEST["type"]) && $USER->GetID() == 1){
	$arResult['USER_TYPE'] = "GUEST";
}

if($arResult['USER_TYPE'] != "PARTICIP" && $arResult['USER_TYPE'] != 'ADMIN') {
	ShowError(GetMessage("ERROR_NOT_PARTICIP"));
	return;
}
// Проверяем существует ли такой таймслот
$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($arResult["TIME"]);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
}
$companyTimeslot = $req_obj->getAllTimesByComp($arResult["USER_ID"]);
$arResult['TO_RESERVE'] = 'Y';
if(isset($companyTimeslot[$arResult["TIME"]]) && $companyTimeslot[$arResult["TIME"]]['meet']['status'] == $statusReserve) {
	$arResult['TO_RESERVE'] = 'N';
}

$fields = array(
	'RECEIVER_ID' => $arResult["USER_ID"],
	'SENDER_ID' => $arResult["USER_ID"],
	'EXHIBITION_ID' => $arParams['APP_ID'],
	'TIMESLOT_ID' => $arResult['TIMESLOT']['id'],
	'STATUS' => '',
);
$arResult['SEND'] = 'N';
if($arResult['TO_RESERVE'] == 'N') {
	$request = $req_obj->getActiveRequest($arResult["TIME"], $arResult["USER_ID"], $arResult["USER_ID"]);
	$req_obj->rejectRequest($request);
	$arResult['SEND'] = 'Y';
} else {
	if(isset($companyTimeslot[$arResult["TIME"]])) {
		$arResult['ERROR_MESSAGE'][] = GetMessage("ERROR_TIMESLOT_BUSY");
		$this->IncludeComponentTemplate();
		return;
	}
	if($_REQUEST['confirm'] == 'Y') {
		$req_obj->reserveRequest($fields);
		$arResult['SEND'] = 'Y';
	}
}

$this->IncludeComponentTemplate();
?>