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


$timeslots = $req_obj->getTimeslots();

// Определяем для какой группы выводить матрицу
if ($arResult['USER_TYPE'] != 'PARTICIP')
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
else
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');

// Список таймслотов со списком компаний, у которых он свободен
$arResult['MEET'] = array();

// Список компаний, для которых выведем занятость
$rsRequests = $req_obj->getRejectedRequests($group_search_id);
$rsRequests->NavStart(50);
$arResult["NAVIGATE"] = $rsRequests->GetPageNavStringEx($navComponentObject, GetMessage($arResult['USER_TYPE'] . '_navigate'), "");
while ($data = $rsRequests->Fetch()) {
	$request = $data;
	$request['timeslot_name'] =  $timeslots[$data['timeslot_id']]['name'];
	$arResult['MEET'][] = $request;
}

$this->IncludeComponentTemplate();
?>