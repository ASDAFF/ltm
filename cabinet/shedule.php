<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");

$curUser = 100; //id пользователя для которого мы это делаем
$appId = 1; // 1 - это id соответствующей группы для встреч. Строго говоря для каждой выставке - свое. Но сейас она единственная и id=1
$timeId = 3; //id таймслота
global $USER;

if (empty($appId) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;
$req_obj = new DokaRequest($appId);

/*--- Получение свободных таймслотов для компании ---*/
echo "<h1>Получение свободных таймслотов для компании</h1>";
$arResult["TIMES"] = $req_obj->getSortedFreeTimesAppoint($curUser);
/*
	Возвращает массив вида
	[id] - id интервала
	[name] - название интервала
*/
echo "<pre>"; print_r($arResult["TIMES"]); echo "</pre>";
//Сслыка для назначения встречи (открываем в отдельном окне)
echo '<p><a href="/cabinet/service/appointment.php?id=560&to=10&time=2&app=1&type=p" target="_blank">Send a request</a></p>';
/*
Изменяемые параметры
id - от кого
to - кому
time - id таймслота
type - тип p или g (участники или гость)
app - id выставки (сейчас для всех это 1)
*/




/*--- Получение массива всех таймслотов типа встреча ---*/
echo "<h1>Получение массива всех таймслотов типа встреча</h1>";
$arResult["ALL_TIMES"] = $req_obj->getAllMeetTimeslots();
/*
	Возвращает массив вида
	[key] - id интервала
	[key][name] - название интервала
*/
echo "<pre>"; print_r($arResult["ALL_TIMES"]); echo "</pre>";




/*--- Получение количество запросов на встречу ---*/
echo "<h1>Получение количества запросов на встречи для текущего пользователя</h1>";

$arResult["COUNT"] = $req_obj->getUnconfirmedRequestsTotal($curUser);
/*
	Возвращает массив вида
	[sent] - отослано
	[incoming] - получено
	[total] - всего
	Из этого мы выводим incoming - т.е. кол-во запросов к пользователю
*/
echo "<pre>"; print_r($arResult["COUNT"]); echo "</pre>";





/*--- Получение компаний у которых свободен таймслот timeId ---*/
echo "<h1>Получение компаний по свободному таймслоту для текущего пользователя</h1>";
$arGroups = CUser::GetUserGroup($curUser);
if (in_array($req_obj->getOption('GUESTS_GROUP'), $arGroups))//если текущий - гость, то ищем в пользователях и наоборот
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
else
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');


$arResult["COMPANIES"] = $req_obj->getFreeCompByTime($timeId, $group_search_id);
/*
	Возвращает массив вида
	[id] - id компании
	[name] - название интервала
*/
echo "<pre>"; print_r($arResult["COMPANIES"]); echo "</pre>";





//echo "<pre>"; print_r(); echo "</pre>";

?> 