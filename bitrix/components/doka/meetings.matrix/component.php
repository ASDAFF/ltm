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

if (empty($arParams["USER_TYPE"])) {
	ShowError(GetMessage("ERROR_EMPTY_USER_TYPE"));
	return;
}

$arResult['USER_TYPE'] = $arParams['USER_TYPE'];
$arResult['APP'] = $arParams['APP_ID'];
$fioParticip = "";
$formId = CFormMatrix::getPFormIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);
$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arResult["PARAM_EXHIBITION"]["ID"], 0);//свойство участника
$fio_dates = array();
$fio_dates[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);
$fio_dates[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);//Имя участника
$fio_dates[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);
$fio_dates[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);//Фамилия участника
$fio_dates[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_148', $formId);
$fio_dates[2][1] = CFormMatrix::getAnswerRelBase(1319 ,$formId);//Стол участника
$fio_dates[3][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_732', $formId);
$fio_dates[3][1] = CFormMatrix::getAnswerRelBase('SIMPLE_QUESTION_732' ,$formId);//Зал участника

if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$arResult['SEND_REQUEST_LINK'] = "/admin/service/appointment_hb.php";
	$arResult['CONFIRM_REQUEST_LINK'] = "/admin/service/appointment_hb_confirm.php";
	$arResult['REJECT_REQUEST_LINK'] = "/admin/service/appointment_hb_del.php";
}
else{
	$arResult['SEND_REQUEST_LINK'] = "/admin/service/appointment.php";
	$arResult['CONFIRM_REQUEST_LINK'] = "/admin/service/appointment_confirm.php";
	$arResult['REJECT_REQUEST_LINK'] = "/admin/service/appointment_del.php";
}


use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

// FIXME ПРОВЕРКА НА АДМИНА?

$timeslots = $req_obj->getTimeslots();
$meet_timeslots = $req_obj->getMeetTimeslotsIds();
$statuses_free = $req_obj->getStatusesFree();

// Определяем для какой группы выводить матрицу
if ($arResult['USER_TYPE'] != 'PARTICIP'){
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
	$group_opposite_id = $req_obj->getOption('MEMBERS_GROUP');}
else{
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
	$group_opposite_id = $req_obj->getOption('GUESTS_GROUP');}

// Список таймслотов со списком компаний, у которых он свободен
$arResult['TIME'] = array();
$arResult['TIMES_FREE'] = array();
$companies_schedule = $req_obj->getFreeTimesByGroup($group_opposite_id);
foreach ($meet_timeslots as $timeslot_id) {
	$arResult['TIMES_FREE'][$timeslot_id] = '';
	foreach($companies_schedule[$timeslot_id] as $company){
		$arResult['TIMES_FREE'][$timeslot_id] .= '<option value="'.$company['id'].'">'.$company['name'].'</option>';
	}
	$arResult['TIME'][$timeslot_id] = array(
		'id' => $timeslot_id,
		'name' => $timeslots[$timeslot_id]['name'],
		'companies' => (array_key_exists($timeslot_id, $companies_schedule)) ? $companies_schedule[$timeslot_id] : array(),
		'html' => $arResult['TIMES_FREE'][$timeslot_id]
	);
}
unset($companies_schedule);

// Полный список компаний из групп участников и гостей
$selectPart = array(
    'SELECT' => array($propertyNameParticipant),
    'FIELDS' => array('WORK_COMPANY', 'ID')
);

$users_list = array();
$filter = array(
	"GROUPS_ID"  => array($req_obj->getOption('MEMBERS_GROUP'))
);

$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectPart);
while ($arUser = $rsUsers->Fetch()) {
	$arAnswer = CFormResult::GetDataByID(
                $arUser[$propertyNameParticipant], 
                array(),  // вопрос "Какие области знаний вас интересуют?" 
                $arResultTmp, 
                $arAnswer2);
    $users_list[$arUser['ID']] =  array(
        'id' => $arUser['ID'],
        'name' => $arUser['WORK_COMPANY'],
        'repr_name' => trim($arAnswer2[$fio_dates[0][0]][$fio_dates[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio_dates[1][0]][$fio_dates[1][1]]["USER_TEXT"]),
    );
}	

if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$filter = array(
		"GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_HB" => "1"
	);
}
else{
	$filter = array(
		"GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_MR" => "1"
	);
}

$selectGuest = array('FIELDS' => array('WORK_COMPANY', 'ID', 'NAME', 'LAST_NAME'));
$rsGUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectGuest);
while ($arUser = $rsGUsers->Fetch()) {
    $users_list[$arUser['ID']] =  array(
        'id' => $arUser['ID'],
        'name' => $arUser['WORK_COMPANY'],
        'repr_name' => $arUser["NAME"]." ".$arUser["LAST_NAME"],
    );
}

// Список компаний, для которых выведем занятость
$rsCompanies = $req_obj->getAllMeetTimesByGroup($group_search_id);

$rsCompanies->NavStart(30); // разбиваем постранично по 30 записей
$arResult["NAVIGATE"] = $rsCompanies->GetPageNavStringEx($navComponentObject, "Пользователи", "");

while ($data = $rsCompanies->Fetch()) {
	$company = array(
        'id' => $data['ID'],
        'name' => $data['WORK_COMPANY'],
        'rep' => $users_list[$data['ID']]['repr_name'],
        'schedule' => array()
	);

    $statuses = $req_obj->getTimslotsStatuses($data);
    //echo "<pre>"; var_dump($data); echo "</pre>";
    // Если пользователя нет в таблице занятости, значит у него все слоты свободны
    if ($data['USER_ID'] === null) {
        foreach ($meet_timeslots as $timeslot_id) {
            $company['schedule'][$timeslot_id][] = array(
                'timeslot_id' => $timeslot_id,
                'timeslot_name' => $timeslots[$timeslot_id]['name'],
                'status' => DokaRequest::getStatusCode($statuses[$timeslot_id]),
            );
        }
    } else {
        foreach ($statuses as $timeslot_id => $status_id) {
            if ( in_array($timeslot_id, $meet_timeslots)) {
	            $schedule = array(
	                'timeslot_id' => $timeslot_id,
	                'timeslot_name' => $timeslots[$timeslot_id]['name'],
	                'status' => DokaRequest::getStatusCode($statuses[$timeslot_id]),
                	'is_busy' => false
	            );
	            // если слот занят
	            if ( !in_array($statuses[$timeslot_id], $statuses_free) ) {
	            	$user_is_sender = $data['MEET_'.$timeslot_id] % 10;
	            	$user_id = substr($data['MEET_'.$timeslot_id], 0, -1);
	            	$schedule['company_id'] = $users_list[$user_id]['id'];
	            	$schedule['company_name'] = $users_list[$user_id]['name'];
	            	$schedule['rep'] = $users_list[$user_id]['repr_name'];
	            	$schedule['user_is_sender'] = $user_is_sender;
	            	$schedule['is_busy'] = true;
	            }
	            $company['schedule'][$timeslot_id] = $schedule;
            }
        }
    }

	$arResult['USERS'][] = $company;
}
unset($meet_timeslots);


$this->IncludeComponentTemplate();
?>