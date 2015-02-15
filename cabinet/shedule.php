<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");

$curUser = 100; //id ������������ ��� �������� �� ��� ������
$appId = 1; // 1 - ��� id ��������������� ������ ��� ������. ������ ������ ��� ������ �������� - ����. �� ����� ��� ������������ � id=1
$timeId = 3; //id ���������
global $USER;

if (empty($appId) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;
$req_obj = new DokaRequest($appId);

/*--- ��������� ��������� ���������� ��� �������� ---*/
echo "<h1>��������� ��������� ���������� ��� ��������</h1>";
$arResult["TIMES"] = $req_obj->getSortedFreeTimesAppoint($curUser);
/*
	���������� ������ ����
	[id] - id ���������
	[name] - �������� ���������
*/
echo "<pre>"; print_r($arResult["TIMES"]); echo "</pre>";
//������ ��� ���������� ������� (��������� � ��������� ����)
echo '<p><a href="/cabinet/service/appointment.php?id=560&to=10&time=2&app=1&type=p" target="_blank">Send a request</a></p>';
/*
���������� ���������
id - �� ����
to - ����
time - id ���������
type - ��� p ��� g (��������� ��� �����)
app - id �������� (������ ��� ���� ��� 1)
*/




/*--- ��������� ������� ���� ���������� ���� ������� ---*/
echo "<h1>��������� ������� ���� ���������� ���� �������</h1>";
$arResult["ALL_TIMES"] = $req_obj->getAllMeetTimeslots();
/*
	���������� ������ ����
	[key] - id ���������
	[key][name] - �������� ���������
*/
echo "<pre>"; print_r($arResult["ALL_TIMES"]); echo "</pre>";




/*--- ��������� ���������� �������� �� ������� ---*/
echo "<h1>��������� ���������� �������� �� ������� ��� �������� ������������</h1>";

$arResult["COUNT"] = $req_obj->getUnconfirmedRequestsTotal($curUser);
/*
	���������� ������ ����
	[sent] - ��������
	[incoming] - ��������
	[total] - �����
	�� ����� �� ������� incoming - �.�. ���-�� �������� � ������������
*/
echo "<pre>"; print_r($arResult["COUNT"]); echo "</pre>";





/*--- ��������� �������� � ������� �������� �������� timeId ---*/
echo "<h1>��������� �������� �� ���������� ��������� ��� �������� ������������</h1>";
$arGroups = CUser::GetUserGroup($curUser);
if (in_array($req_obj->getOption('GUESTS_GROUP'), $arGroups))//���� ������� - �����, �� ���� � ������������� � ��������
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
else
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');


$arResult["COMPANIES"] = $req_obj->getFreeCompByTime($timeId, $group_search_id);
/*
	���������� ������ ����
	[id] - id ��������
	[name] - �������� ���������
*/
echo "<pre>"; print_r($arResult["COMPANIES"]); echo "</pre>";





//echo "<pre>"; print_r(); echo "</pre>";

?> 