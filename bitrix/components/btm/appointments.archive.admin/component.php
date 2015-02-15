<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//�������� ���������� ��������� ����������

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/personal/";
}

if(strLen($arParams["GROUP_SENDER_ID"])<=0){
	$arParams["GROUP_SENDER_ID"] = "4";
}

if(strLen($arParams["GROUP_RECIVER_ID"])<=0){
	$arParams["GROUP_RECIVER_ID"] = "6";
}

if(strLen($arParams["ADMIN_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["APP_ID"])<=0){
	$arParams["APP_ID"] = "3";
}

if(strLen($arParams["APP_TYPE"])<=0){
	$arParams["APP_TYPE"] = "1";
}

if(!isset($arParams["APP_COUNT"]) || strLen($arParams["APP_COUNT"])<=0){
	$arParams["APP_COUNT"] = "17";
}

global $USER;
if (!is_object($USER)) $USER = new CUser;

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = GetMessage("SHEDULE_AUTH_ERROR");
}

CModule::IncludeModule('iblock');

$times = array(
	  '10:00 � 10:10', '10:15 � 10:25',
	  '10:30 � 10:40', '10:45 � 10:55',
	  '11:00 � 11:10', '11:15 � 11:25',
	  '11:30 � 11:40', '11:45 � 11:55',
	  '12:10 � 12:20', '12:25 � 12:35',
	  '12:40 � 12:50', '12:55 � 13:05',
	  '13:10 � 13:20', '13:25 � 13:35',
	  '13:40 � 13:50', '13:55 � 14:05',
	  '14:10 � 14:20'
  );

/*---------------------------------------------------*/
//           ��������� ����� ��� �������             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '' && $USER->IsAdmin())
{
	//������ ������������� �����������
	$freeUsersTimes = array();
	$reciverList = array();
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["GROUP_RECIVER_ID"])
	);
	$reciveUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
	while($arUsersTemp=$reciveUsers->Fetch()){
	  $reciverList[$arUsersTemp["ID"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
	  $reciverList[$arUsersTemp["ID"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
	}
	//������ ������������� ������������
	$freeUsersTimes = array();
	$senderList = array();
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["GROUP_SENDER_ID"])
	);
	$senderUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
	while($arUsersTemp=$senderUsers->Fetch()){
	  $senderList[$arUsersTemp["ID"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
	  $senderList[$arUsersTemp["ID"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
	}
	//������ ������������ ������
	$meetingList = array();
	$meetingList["LIST"] = array();
	$meetingList["COUNT"] = 0;
	$arFilterM = Array(
	   "IBLOCK_ID" => $arParams["APP_ID"],
	   "PROPERTY_STATUS" => $arParams["APP_TYPE"]
	   );
	$arSelect = Array("DATE_CREATE", "ID", "NAME", "ACTIVE", "PROPERTY_SENDER_ID", "PROPERTY_RECIVER_ID", "PROPERTY_STATUS", "PROPERTY_TIME");
	$resMeet = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilterM, false, false, $arSelect);
	while($ar_meet = $resMeet->GetNext()){
		if(isset($senderList[$ar_meet['PROPERTY_SENDER_ID_VALUE']]) && is_array($senderList[$ar_meet['PROPERTY_SENDER_ID_VALUE']])){
			$meetingList["LIST"][$meetingList["COUNT"]]["FROM_COMPANY"] = $senderList[$ar_meet['PROPERTY_SENDER_ID_VALUE']]["COMPANY"];
			$meetingList["LIST"][$meetingList["COUNT"]]["FROM_REP"] = $senderList[$ar_meet['PROPERTY_SENDER_ID_VALUE']]["REP"];
			$meetingList["LIST"][$meetingList["COUNT"]]["TO_COMPANY"] = $reciverList[$ar_meet['PROPERTY_RECIVER_ID_VALUE']]["COMPANY"];
			$meetingList["LIST"][$meetingList["COUNT"]]["TO_REP"] = $reciverList[$ar_meet['PROPERTY_RECIVER_ID_VALUE']]["REP"];
			$meetingList["LIST"][$meetingList["COUNT"]]["TIME"] = $ar_meet['PROPERTY_TIME_VALUE'];
			$meetingList["COUNT"]++;
		}
	}
}
$arResult["MEETINGS"] = $meetingList;
$arResult["TIMES"] = $times;
$arResult["TIMES_COUNT"] = $arParams["APP_COUNT"];
$this->IncludeComponentTemplate();
?>