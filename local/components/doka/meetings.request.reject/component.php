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
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
		}
		else{
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
		}
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
use Doka\Meetings\Wishlists as DWL;

$req_obj = new DokaRequest($arParams['APP_ID']);


$arResult['USER_TYPE'] = $req_obj->getUserType();
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');
$arResult['CUR_USER'] = $USER->GetID();
if (isset($_REQUEST['id']))
	$sender_id = intval($_REQUEST['id']);
else
	$sender_id = $arResult['CUR_USER'];

// Данные таймслота
$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($timeslot_id);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
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

$senderType = $req_obj->getUserTypeById($sender_id);
if($senderType == 'GUEST'){
	$arResult['SENDER'] = $req_obj->getUserInfo($sender_id);
	$arResult['RECEIVER'] = $req_obj->getUserInfoFull($receiver_id, $formId, $propertyNameParticipant, $fio_datesPart);
}
else{
	$arResult['SENDER'] = $req_obj->getUserInfoFull($sender_id, $formId, $propertyNameParticipant, $fio_datesPart);
	$arResult['RECEIVER'] = $req_obj->getUserInfo($receiver_id);
}

// Инфо об отправителе

if (!$arResult['SENDER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_SENDER_ID');
}
// Инфо о получателе

if (!$arResult['RECEIVER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}


$arResult['REJECT_SUCCESS'] = false;
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
			$valid = in_array($req_obj->getOption('GUESTS_GROUP'), $arSenderGroups) || in_array($req_obj->getOption('MEMBERS_GROUP'), $arSenderGroups);
			break;
		case 'GUEST':
			$valid = in_array($req_obj->getOption('GUESTS_GROUP'), $arSenderGroups) || in_array($req_obj->getOption('MEMBERS_GROUP'), $arSenderGroups);
			break;
	}
	if (!$valid) {
		$arResult['ERROR_MESSAGE'][] = GetMessage('ERROR_GROUP_SENDER');
	}

	if ($arResult['USER_TYPE'] != 'ADMIN' && $request['STATUS'] != DokaRequest::getStatusCode(DokaRequest::STATUS_PROCESS)) {
		$arResult['ERROR_MESSAGE'][] = GetMessage('ERROR_STATUS');
	}
	
}
// Если ошибок не было и встречи не заблокированы
if (!isset($arResult['ERROR_MESSAGE']) && ($arResult['IS_ACTIVE'] || $arResult['USER_TYPE'] == 'ADMIN')) {
	// Отменяем запрос
	$req_obj->rejectRequest($request);
	$arResult['REJECT_SUCCESS'] = true;
	// Добавляем компанию в вишлист
	$wish_obj = new DWL($arParams['APP_ID']);
	$fields = array(
		'REASON' => DWL::REASON_REJECTED,
		'SENDER_ID' => $arResult['SENDER']['company_id'],
		'RECEIVER_ID' => $arResult['RECEIVER']['company_id']
	);
	$wish_obj->Add($fields);
	if ($arResult['USER_TYPE'] != 'ADMIN' && $arResult['CUR_USER'] != $arResult['SENDER']['company_id']){
		$arFieldsMes = array(
			"EMAIL" => $arResult["SENDER"]["email"],
			"COMPANY" => $arResult["RECEIVER"]["company_name"],
			"USER" => $arResult["RECEIVER"]["repr_name"],
			"EXIB_NAME_RU" => $arResult["PARAM_EXHIBITION"]["NAME"],
			"EXIB_NAME_EN" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["NAME_EN"]["VALUE"],
			"EXIB_SHORT_RU" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]["VALUE"],
			"EXIB_SHORT_EN" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]["VALUE"],
			"EXIB_DATE" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["DATE"]["VALUE"],
			"EXIB_PLACE" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["VENUE"]["VALUE"],
		);
		CEvent::Send($req_obj->getOption('EVENT_REJECT'),"s1",$arFieldsMes);
	}
} else if(!$arResult['IS_ACTIVE']) {
	$arResult['ERROR_MESSAGE'][] = 'Встречи заблокированы';
}

//var_dump($request);
$this->IncludeComponentTemplate();
?>