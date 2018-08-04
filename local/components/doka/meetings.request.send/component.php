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

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

$arResult["APP"] = $arParams['APP_ID'];
$arResult['USER_TYPE'] = $req_obj->getUserType();
if ($receiver_id <= 0) {
	if($arResult['USER_TYPE'] == 'PARTICIP'){
	  ShowError(GetMessage("ERROR_WRONG_RECEIVER_PARTICIP_ID"));
	}
	else{
	  ShowError(GetMessage("ERROR_WRONG_RECEIVER_ID"));
	}
	return;
}

$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');
$arResult['OPERATION_TYPE'] = $_REQUEST["status"];

if (isset($_REQUEST['id']) && $arResult['USER_TYPE'] == 'ADMIN' )
	$sender_id = intval($_REQUEST['id']);
else
	$sender_id = $USER->GetID();

if(isset($_REQUEST["type"]) && $_REQUEST["type"] == "p" && $USER->GetID() == 1){
	$arResult['USER_TYPE'] = "PARTICIP";
}
elseif(isset($_REQUEST["type"]) && $USER->GetID() == 1){
	$arResult['USER_TYPE'] = "GUEST";
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

// Отправка приглашения самому себе
if ($sender_id == $receiver_id) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}

$arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($timeslot_id);
if (!$arResult['TIMESLOT']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
}

//Проверяем нет ли уже встреч с этим же пользователем
$exibArr = array($arParams["APP_ID"]);
if($arParams["APP_ID_OTHER"])
	$exibArr[] = $arParams["APP_ID_OTHER"];

$allSlotsBetween = $req_obj->getAllSlotsBetween($sender_id, $receiver_id, $exibArr);
if(!empty($allSlotsBetween)){
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

// Инфо об отправителе
if($arResult['USER_TYPE'] == 'PARTICIP'){
	$arResult['SENDER'] = $req_obj->getUserInfoFull($sender_id, $formId, $propertyNameParticipant, $fio_datesPart);
	$arResult['RECEIVER'] = $req_obj->getUserInfo($receiver_id);
}
else{
	$arResult['RECEIVER'] = $req_obj->getUserInfoFull($receiver_id, $formId, $propertyNameParticipant, $fio_datesPart);
	$arResult['SENDER'] = $req_obj->getUserInfo($sender_id);
}


if (!$arResult['SENDER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_SENDER_ID');
}
if (!$arResult['RECEIVER']) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}

// Сохранение запроса
if (!isset($arResult['ERROR_MESSAGE']) && isset($_POST['submit'])) {
	// Проверим, свободны ли таймслоты
	if ($req_obj->checkTimeslotIsFree( $arResult['TIMESLOT']['id'], array($arResult['RECEIVER']['company_id'], $arResult['SENDER']['company_id']) )) {
	  $status = DokaRequest::STATUS_PROCESS;
		if($arResult['USER_TYPE'] == 'ADMIN' && $arResult['OPERATION_TYPE'] == 'ADMIN') {
			$status = DokaRequest::STATUS_CONFIRMED;
		}

		$fields = array(
	        'RECEIVER_ID' => $arResult['RECEIVER']['company_id'],
	        'SENDER_ID' => $arResult['SENDER']['company_id'],
	        'EXHIBITION_ID' => $arParams['APP_ID'],
	        'TIMESLOT_ID' => $arResult['TIMESLOT']['id'],
	        'STATUS' => DokaRequest::getStatusCode($status),
	    );
		$req_obj->Add($fields);
		$arFieldsMes = array(
			"EMAIL" => $arResult["RECEIVER"]["email"],
			"EXIB_NAME_RU" => $arResult["PARAM_EXHIBITION"]["NAME"],
			"EXIB_NAME_EN" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["NAME_EN"]["VALUE"],
			"EXIB_SHORT_RU" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]["VALUE"],
			"EXIB_SHORT_EN" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]["VALUE"],
			"EXIB_DATE" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["DATE"]["VALUE"],
			"EXIB_PLACE" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["VENUE"]["VALUE"],
		);
		CEvent::Send($req_obj->getOption('EVENT_SENT'),"s1",$arFieldsMes);
	} else {
		$arResult['FORM_ERROR'] = 'ошибка отправки';
	}
	$arResult['REQUEST_SENT'] = true;
}
$this->IncludeComponentTemplate();
?>