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

if((isset($_REQUEST["exib_code"]) && $_REQUEST["exib_code"]!='') || !empty($arParams["APP_ID"])){
	$filterEx = array("IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"]);
	if(!empty($_REQUEST["exib_code"]))
		$filterEx["CODE"] = $_REQUEST["exib_code"];
	elseif(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y')
		$filterEx["PROPERTY_APP_HB_ID"] = $arParams["APP_ID"];
	else{
		$filterEx["PROPERTY_APP_ID"] = $arParams["APP_ID"];
	}

	$rsExhib = CIBlockElement::GetList(
		array(),
		$filterEx,
		false,
		false,
		array("ID", "CODE", "NAME", "IBLOCK_ID","PROPERTY_*")
	);
	while($oExhib = $rsExhib->GetNextElement(true, false))
	{
		$arResult["PARAM_EXHIBITION"] = $oExhib->GetFields();
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
		unset($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["MORE_PHOTO"]);
		$exibOther = '';
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
			$exibOther = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
		}
		else{
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
			$exibOther = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
		}
		$arParams["APP_ID"] = $appId;
		$arParams["APP_ID_OTHER"] = $exibOther;
	}
}

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (!$USER->IsAuthorized()) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

$timeslot_id = intval($_REQUEST['time']);
if ($timeslot_id <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_TIMESLOT_ID"));
	return;
}
$receiver_id = intval($_REQUEST['to']);
if ($receiver_id <= 0) {
	ShowError(GetMessage("ERROR_WRONG_RECEIVER_ID"));
	return;
}

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);


$arResult['USER_TYPE'] = $req_obj->getUserType();
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

if (isset($_REQUEST['id']) && $arResult['USER_TYPE'] == 'ADMIN' )
	$sender_id = intval($_REQUEST['id']);
else
	$sender_id = $USER->GetID();
	
if ($arResult['USER_TYPE'] != 'ADMIN' ){
	$sender_id = $receiver_id;
	$receiver_id = $USER->GetID();
}

$resCheckingRights = $req_obj->checkMeetingRights($receiver_id, $sender_id);
if(!$resCheckingRights["SENDER"]) {
	ShowError(GetMessage("ERROR_WRONG_SENDER_RIGHTS"));
	return;
}
if(!$resCheckingRights["RECEIVER"]) {
	ShowError(GetMessage("ERROR_WRONG_RECEIVER_RIGHTS"));
	return;
}
// Данные таймслота
$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($timeslot_id);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
}

//Проверяем нет ли уже встреч с этим же пользователем
$exibArr = array($arParams["APP_ID"]);
if($arParams["APP_ID_OTHER"])
	$exibArr[] = $arParams["APP_ID_OTHER"];
$allSlotsBetween = $req_obj->getAllSlotsBetween($sender_id, $receiver_id, $exibArr);
if(!empty($allSlotsBetween) && count($allSlotsBetween) > 1){
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_COMPANY_MEET_EXIST');
}

$formId = CFormMatrix::getPFormIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);
$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arResult["PARAM_EXHIBITION"]["ID"], 0);//свойство участника
$fio_datesPart = array();
$fio_datesPart[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);//Имя участника
$fio_datesPart[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);
$fio_datesPart[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);//Фамилия участника
$fio_datesPart[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);
$fio_datesPart[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_859', $formId);//Email участника
$fio_datesPart[2][1] = CFormMatrix::getAnswerRelBase(89 ,$formId);

if($arResult['USER_TYPE'] == 'GUEST'){
	$arResult['SENDER'] = $req_obj->getUserInfo($sender_id);
	$arResult['RECEIVER'] = $req_obj->getUserInfoFull($receiver_id, $formId, $propertyNameParticipant, $fio_datesPart);
}
elseif($arResult['USER_TYPE'] == 'PARTICIP'){
	$arResult['SENDER'] = $req_obj->getUserInfoFull($receiver_id, $formId, $propertyNameParticipant, $fio_datesPart);
	$arResult['RECEIVER'] = $req_obj->getUserInfo($sender_id);
}
else{
	$senderType = $req_obj->getUserTypeById($sender_id);
	if($senderType == 'GUEST'){
		$arResult['SENDER'] = $req_obj->getUserInfo($sender_id);
		$arResult['RECEIVER'] = $req_obj->getUserInfoFull($receiver_id, $formId, $propertyNameParticipant, $fio_datesPart);
	}
	else{
		$arResult['SENDER'] = $req_obj->getUserInfoFull($receiver_id, $formId, $propertyNameParticipant, $fio_datesPart);
		$arResult['RECEIVER'] = $req_obj->getUserInfo($sender_id);
	}
}

// Инфо об отправителе
if (!$arResult['SENDER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_SENDER_ID');
}
// Инфо о получателе
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