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

if(isset($_REQUEST["exib_code"]) && $_REQUEST["exib_code"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
					"CODE" => $_REQUEST["exib_code"]
				),
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
		$arParams["APP_ID"] = $appId;
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

// Отправка приглашения самому себе
if ($sender_id == $receiver_id) {
	$arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_RECEIVER_ID');
}


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
	    $fields = array(
	        'RECEIVER_ID' => $arResult['RECEIVER']['company_id'],
	        'SENDER_ID' => $arResult['SENDER']['company_id'],
	        'EXHIBITION_ID' => $arParams['APP_ID'],
	        'TIMESLOT_ID' => $arResult['TIMESLOT']['id'],
	        'STATUS' => ($arResult['USER_TYPE'] == 'ADMIN') ? DokaRequest::getStatusCode(DokaRequest::STATUS_CONFIRMED) : DokaRequest::getStatusCode(DokaRequest::STATUS_PROCESS),
	    );
		$req_obj->Add($fields);
		$arFieldsMes = array(
			"EMAIL" => $arResult["RECEIVER"]["email"],
			"EXIB_NAME" => $arResult["PARAM_EXHIBITION"]["NAME"],
			"EXIB_SHORT" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]["VALUE"]
		);
		CEvent::Send($req_obj->getOption('EVENT_SENT'),"s1",$arFieldsMes);
	} else {
		$arResult['FORM_ERROR'] = 'ошибка отправки';
	}
	$arResult['REQUEST_SENT'] = true;
}
$this->IncludeComponentTemplate();
?>