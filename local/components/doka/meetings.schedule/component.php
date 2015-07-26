<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}
if (!CModule::IncludeModule("doka.meetings") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

$arResult = array();
if(isset($arParams["EXIB_CODE"]) && $arParams["EXIB_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
					"CODE" => $arParams["EXIB_CODE"]
				),
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
if (empty($arParams["USER_ID"]))
	$arParams['USER_ID'] = $USER->GetID();

if (!$USER->IsAuthorized() || $arParams['USER_ID'] <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}
use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

if(empty($arParams["USER_TYPE"]) && $USER->IsAdmin()){
	$arParams["USER_TYPE"] = $req_obj->getUserTypeById($_REQUEST["id"]);
}
if (empty($arParams["USER_TYPE"])) {
	$arParams["USER_TYPE"] = $req_obj->getUserType();
}

// Определяем для какой группы искать свободные слоты компаний
$arGroups = $USER->GetUserGroupArray();
if (in_array($req_obj->getOption('GUESTS_GROUP'), $arGroups) || (isset($_REQUEST["type"]) && $_REQUEST["type"] == "g"))
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
else
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');

// Получаем полный список компаний со свободными таймслотами
$companies_schedule = $req_obj->getFreeTimesByGroup($group_search_id);

$arResult['USER_TYPE'] = $arParams["USER_TYPE"];

$arResult['APP_ID'] = $arParams['APP_ID'];
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');
$arResult['MESSAGE'] = '';
$arResult['MESSAGE_LINK'] = "/cabinet".$arParams['MESSAGE_LINK'];
$arResult['SEND_REQUEST_LINK'] = "/cabinet".$arParams['SEND_REQUEST_LINK'];
$arResult['CONFIRM_REQUEST_LINK'] = "/cabinet".$arParams['CONFIRM_REQUEST_LINK'];
$arResult['REJECT_REQUEST_LINK'] = "/cabinet".$arParams['REJECT_REQUEST_LINK'];
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$arResult['WISHLIST_LINK'] = "/cabinet/service/wishlist_hb";
	$arResult['SHEDULE_LINK'] =  "/cabinet/service/shedule_hb";
}
else{
	$arResult['WISHLIST_LINK'] = "/cabinet/service/wishlist";
	$arResult['SHEDULE_LINK'] =  "/cabinet/service/shedule";
}
$arResult['CUT'] = $arParams['CUT'];
$arResult['HALL'] = "";
$arResult['TABLE'] = "";
$arResult['CITY'] = "";

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

if(!$arResult['IS_ACTIVE']) {
	$arResult['MESSAGE'] = GetMessage($arParams['USER_TYPE'] . '_IS_LOCKED'); 
}

$arResult['CURRENT_USER_ID'] = $USER->GetID();
if(isset($_REQUEST["id"]) && $_REQUEST["id"]!='' && $USER->IsAdmin()){
	$arResult['CURRENT_USER_ID'] = $_REQUEST["id"];
	$arParams['USER_ID'] = $_REQUEST["id"];
}
$timeslots = $req_obj->getTimeslots();
if($arResult['USER_TYPE'] == "PARTICIP" && $arParams["IS_HB"] != 'Y'){
	$busy_timeslots = $req_obj->getAllTimesByComp($arResult['CURRENT_USER_ID']);
}
elseif($arResult['USER_TYPE'] == "PARTICIP" && isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$formIdGuest = CFormMatrix::getGResultIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);
	$propertyNameParticipantGuest = "UF_ID_COMP";
	$fio_datesGuest = array();
	$fio_datesGuest[0][0] = $guestFields["QUEST_CODE"][ $guestFieldsIndex["F_NAME"] ];
	$fio_datesGuest[1][0] = $guestFields["QUEST_CODE"][ $guestFieldsIndex["L_NAME"] ];
	$fio_datesGuest[2][0] = $guestFields["QUEST_CODE"][ $guestFieldsIndex["TABLE"] ];
	$fio_datesGuest[3][0] = $guestFields["QUEST_CODE"][ $guestFieldsIndex["HALL"] ];
	$busy_timeslots = $req_obj->getAllTimesByCompNamed($arResult['CURRENT_USER_ID'], $formIdGuest, $propertyNameParticipantGuest, $fio_datesGuest);
}
else{
	$busy_timeslots = $req_obj->getAllTimesByCompNamed($arResult['CURRENT_USER_ID'], $formId, $propertyNameParticipant, $fio_dates);
}

if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf'){
	$rsUser = CUser::GetByID($arResult['CURRENT_USER_ID']);
	$thisUser = $rsUser->Fetch();
	$arAnswer = CFormResult::GetDataByID($thisUser[$propertyNameParticipant], array(), $arTmpResult, $arAnswer2);
	if($arResult['USER_TYPE'] == "PARTICIP"){
		$fioParticip = $arAnswer2[$fio_dates[0][0]][$fio_dates[0][1]]["USER_TEXT"]." ".$arAnswer2[$fio_dates[1][0]][$fio_dates[1][1]]["USER_TEXT"];
		foreach($arAnswer2[$fio_dates[3][0]] as $value){
			$arResult['HALL'] = $value["MESSAGE"];
		}
		foreach($arAnswer2[$fio_dates[2][0]] as $value){
			$arResult['TABLE'] = $value["USER_TEXT"];
		}
	}
	else{
		foreach($arAnswer2[$guestFields["QUEST_CODE"][ $guestFieldsIndex["CITY"] ]] as $value){
			$arResult['CITY'] = $value["USER_TEXT"];
		}
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			foreach($arAnswer2[$guestFields["QUEST_CODE"][ $guestFieldsIndex["HALL"] ]] as $value){
				$arResult['HALL'] = $value["MESSAGE"];
			}
			foreach($arAnswer2[$guestFields["QUEST_CODE"][ $guestFieldsIndex["TABLE"] ]] as $value){
				$arResult['TABLE'] = $value["USER_TEXT"];
			}			
		}
	}
/*print_r($arResult['HALL']);
die();*/
}

$arResult['SCHEDULE'] = array();
foreach ($timeslots as $timeslot_id => $item) {
	if (array_key_exists($timeslot_id, $busy_timeslots)) {
		$meet = $busy_timeslots[$timeslot_id]['meet'];
		$arResult['SCHEDULE'][] = array(
			'timeslot_id' => $timeslot_id,
			'name' => $item['name'],
			'status' => $meet['status'],
			'notes' => DokaGetNote($meet, $arResult['USER_TYPE'], $arResult['CURRENT_USER_ID']),
			'sent_by_you' => ($meet['modified_by'] == $arResult['CURRENT_USER_ID']),
			'company_name' => $meet['company_name'],
			'company_rep' => $meet['company_rep'],
			'company_id' => $meet['company_id'],
			'form_res' => $meet['form_res'],
			'hall' => $meet['hall'],
			'table' => $meet['table'],
		);
	} else if (in_array($item['type'], DokaTimeslot::getMeetTypeCodes())) {
		$arResult['SCHEDULE'][] = array(
			'timeslot_id' => $timeslot_id,
			'name' => $item['name'],
			'status' => 'free',
			'notes' => DokaGetNote(array(), $arResult['USER_TYPE'], $arResult['CURRENT_USER_ID']),
			'list' => array_key_exists($timeslot_id, $companies_schedule) ? $companies_schedule[$timeslot_id] : array()
		);
	}
	else{
		$arResult['SCHEDULE'][] = array(
			'timeslot_id' => $timeslot_id,
			'name' => $item['name'],
			'status' => 'coffe',
			'notes' => 'coffe',
			'list' => array()
		);
	}
}

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf') {
	require(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/tcpdf.php');
	require_once(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/templates/schedule_' . $arParams['USER_TYPE'] . '.php');

	$APPLICATION->RestartBuffer();
	$arResult['EXHIBITION'] = $req_obj->getOptions();
	// Информация о пользователе, для которого генерируем pdf
    $filter = array( 'ID' => $arResult['CURRENT_USER_ID']);
    $select = array(
        'SELECT' => array($req_obj->getOption('REPR_PROP_CODE'), $propertyNameParticipant),
        'FIELDS' => array('WORK_COMPANY', 'ID')
    );
    $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
    if ($arUser = $rsUser->Fetch()) {
    	if($fioParticip == ''){
			$fioParticip = $arUser[$req_obj->getOption('REPR_PROP_CODE')];
    	}
        $arResult['USER'] = array(
        	'REP' => $fioParticip,
        	'COMPANY' => $arUser['WORK_COMPANY'],
        	'CITY' => $arResult['CITY'],
        );
		if($arParams['USER_TYPE'] == "GUEST"){
			$arAnswer = CFormResult::GetDataByID(
				$arUser[$propertyNameParticipant],
				array(),
				$arResultTmp,
				$arAnswer2);

			$arResult['USER']['COL_REP'] = "";
			foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["F_NAME_COL"] ] ] as $value){
				$arResult['USER']['COL_REP'] = trim($value["USER_TEXT"]);
			}
			foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["L_NAME_COL"] ] ] as $value){
				$arResult['USER']['COL_REP'] .= " ".trim($value["USER_TEXT"]);
			}
			$arResult['USER']['COL_REP'] = trim($arResult['USER']['COL_REP']);
			$arResult['USER']['MOB'] = "";
			foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["MOB_PHONE"] ] ] as $value){
				$arResult['USER']['MOB'] = trim($value["USER_TEXT"]);
			}
			$arResult['USER']['PHONE'] = "";
			foreach($arAnswer2[ $guestFields["QUEST_CODE"][ $guestFieldsIndex["PHONE"] ] ] as $value){
				$arResult['USER']['PHONE'] = trim($value["USER_TEXT"]);
			}
		}

		DokaGeneratePdf($arResult);
    }
}

$this->IncludeComponentTemplate();

function DokaGetNote($meet, $user_type, $curUser) {
	global $USER, $arParams;
	switch ($meet['status']) {
		case 'process':
			if ($meet['modified_by'] == $curUser)
				$msg = GetMessage($user_type.'_SENT_BY_YOU');
			else
				$msg = GetMessage($user_type.'_SENT_TO_YOU');
			break;
		case 'confirmed':
			if ($meet['modified_by'] == $curUser || $meet['modified_by'] == $meet['company_id'])
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