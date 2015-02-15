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
	//������ ������������ ������
	$meetingList = array();
	$arFilterM = Array(
	   "IBLOCK_ID" => $arParams["APP_ID"],
	   "!PROPERTY_STATUS" => $arParams["APP_TYPE"]
	   );
	$arSelect = Array("DATE_CREATE", "ID", "NAME", "ACTIVE", "PROPERTY_SENDER_ID", "PROPERTY_RECIVER_ID", "PROPERTY_STATUS", "PROPERTY_TIME");
	$resMeet = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilterM, false, false, $arSelect);
	while($ar_meet = $resMeet->GetNext()){
		$meetingList[$ar_meet["ID"]]["FROM"] = $ar_meet['PROPERTY_SENDER_ID_VALUE'];
		$meetingList[$ar_meet["ID"]]["TO"] = $ar_meet['PROPERTY_RECIVER_ID_VALUE'];
		$meetingList[$ar_meet["ID"]]["ACTIVE"] = $ar_meet["ACTIVE"];
		$meetingList[$ar_meet["ID"]]["STATUS"] = $ar_meet['PROPERTY_STATUS_VALUE'];
	}
	//������ ��������� ������������� �� ��������� ����������
	$freeUsersTimes = array();
	$usersList = array();
	for($i=0; $i<$arParams["APP_COUNT"]; $i++){
		$freeUsersTimes[$i]["TITLE"] = $times[$i];
		$fieldNum = $i+1;
		$freeUsersTimes[$i]["FIELD"] = "UF_SHEDULE_".$fieldNum;
		$freeUsersTimes[$i]["LIST"] = array();
		$freeUsersTimes[$i]["COUNT"] = 0;
	}
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["GROUP_RECIVER_ID"])
	);
	$reciveUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
	while($arUsersTemp=$reciveUsers->Fetch()){
	  $usersList[$arUsersTemp["ID"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
	  $usersList[$arUsersTemp["ID"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
	  for($i=0; $i<$arParams["APP_COUNT"]; $i++){
		  if($arUsersTemp[$freeUsersTimes[$i]["FIELD"]] == ''){
			  $freeUsersTimes[$i]["LIST"][$freeUsersTimes[$i]["COUNT"]]["ID"] = $arUsersTemp["ID"];
			  $freeUsersTimes[$i]["LIST"][$freeUsersTimes[$i]["COUNT"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
			  $freeUsersTimes[$i]["COUNT"] = $freeUsersTimes[$i]["COUNT"]+1;
		  }
	  }
	}
	
	//������ �������������
	$userList = array();
	$userList["LIST"]=array();
	$userList["COUNT"]=0;
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["GROUP_SENDER_ID"])
	);
	$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
	$rsUsers->NavStart(30); // ��������� ����������� �� 50 �������
	$arResult["NAVIGATE"] = $rsUsers->GetPageNavStringEx($navComponentObject, "������������", "");

	while($arUsersTemp=$rsUsers->Fetch()){
		$userList["LIST"][$userList["COUNT"]]["ID"] = $arUsersTemp["ID"];
		$userList["LIST"][$userList["COUNT"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
		$userList["LIST"][$userList["COUNT"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
		$userList["LIST"][$userList["COUNT"]]["MEET"] = array();
		for($i=0; $i<$arParams["APP_COUNT"]; $i++){
			$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ID"] = $arUsersTemp[$freeUsersTimes[$i]["FIELD"]];
			if($arUsersTemp[$freeUsersTimes[$i]["FIELD"]] == ''){
				$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["STATUS"] = "FREE";
				$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["LIST"] = $freeUsersTimes[$i]["LIST"];
				$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["COUNT"] = $freeUsersTimes[$i]["COUNT"];
			}
			else{
				$personeId = '';
				$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ACTIVE"] = $meetingList[$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ID"]]["ACTIVE"];
				if($meetingList[$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ID"]]["FROM"] == $userList["LIST"][$userList["COUNT"]]["ID"]){
					$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["STATUS"] = "FROM";
					$personeId = $meetingList[$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ID"]]["TO"];
				}
				else{
					$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["STATUS"] = "TO";
					$personeId = $meetingList[$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ID"]]["FROM"];
				}
				$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["COMPANY"] = $usersList[$personeId]["COMPANY"];
				$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["REP"] = $usersList[$personeId]["REP"];
				if($meetingList[$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["ID"]]["STATUS"] == 'ADM'){
					$userList["LIST"][$userList["COUNT"]]["MEET"][$i]["STATUS"] = "ADM";
				}
			}
		}
		$userList["COUNT"]++;
	}
}
$arResult["USERS"] = $userList;
$arResult["TIMES"] = $times;
$arResult["TIMES_COUNT"] = $arParams["APP_COUNT"];
$this->IncludeComponentTemplate();
?>