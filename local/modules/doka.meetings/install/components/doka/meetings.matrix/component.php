<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (empty($arParams["USER_TYPE"])) {
	ShowError(GetMessage("ERROR_EMPTY_USER_TYPE"));
	return;
}

$arResult = array();
$arResult['USER_TYPE'] = $arParams['USER_TYPE'];

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

// FIXME ПРОВЕРКА НА АДМИНА?

$timeslots = $req_obj->getTimeslots();
$meet_timeslots = $req_obj->getMeetTimeslotsIds();
$statuses_free = $req_obj->getStatusesFree();

// Определяем для какой группы выводить матрицу
if ($arResult['USER_TYPE'] != 'PARTICIP')
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
else
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');

// Список таймслотов со списком компаний, у которых он свободен
$arResult['TIME'] = array();
$companies_schedule = $req_obj->getFreeTimesByGroup($group_search_id);
foreach ($meet_timeslots as $timeslot_id) {
	$arResult['TIME'][$timeslot_id] = array(
		'id' => $timeslot_id,
		'name' => $timeslots[$timeslot_id]['name'],
		'companies' => (array_key_exists($timeslot_id, $companies_schedule)) ? $companies_schedule[$timeslot_id] : array()
	);
}

// Полный список компаний из групп участников и гостей
$users_list = array();
$filter = array(
	"GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP'), $req_obj->getOption('MEMBERS_GROUP'))
);
$select = array(
    'SELECT' => array($req_obj->getOption('REPR_PROP_CODE')),
    'FIELDS' => array('WORK_COMPANY', 'ID')
);
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
while ($arUser = $rsUsers->Fetch()) {
    $users_list[$arUser['ID']] =  array(
        'id' => $arUser['ID'],
        'name' => $arUser['WORK_COMPANY'],
        'repr_name' => $arUser[$req_obj->getOption('REPR_PROP_CODE')],
    );
}

// Список компаний, для которых выведем занятость
$rsCompanies = $req_obj->getAllMeetTimesByGroup($group_search_id);
$rsCompanies->NavStart(50); // разбиваем постранично по 50 записей
$arResult["NAVIGATE"] = $rsCompanies->GetPageNavStringEx($navComponentObject, "Пользователи", "");



while ($data = $rsCompanies->Fetch()) {
	$company = array(
        'id' => $data['ID'],
        'name' => $data['WORK_COMPANY'],
        'rep' => $users_list[$data['ID']]['repr_name'],
        'schedule' => array()
	);

    $statuses = $req_obj->getTimslotsStatuses($data);
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


$this->IncludeComponentTemplate();
?>