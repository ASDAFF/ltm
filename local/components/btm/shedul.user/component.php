<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить количество временных интервалов

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

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = GetMessage("SHEDULE_AUTH_ERROR");
}

$arResult["IS_ACTIVE"] = $arParams["IS_ACTIVE"];
if(strLen($arParams["IS_ACTIVE"])<=0 || $arParams["IS_ACTIVE"] == 'N'){
	$arResult["MESSAGE"] = GetMessage("SHEDULE_MODULE_IS_BLOCKED");
}

CModule::IncludeModule('iblock');

$times = array(
	  '10:00 – 10:10', '10:15 – 10:25',
	  '10:30 – 10:40', '10:45 – 10:55',
	  '11:00 – 11:10', '11:15 – 11:25',
	  '11:30 – 11:40', '11:45 – 11:55',
	  '12:10 – 12:20', '12:25 – 12:35',
	  '12:40 – 12:50', '12:55 – 13:05',
	  '13:10 – 13:20', '13:25 – 13:35',
	  '13:40 – 13:50', '13:55 – 14:05',
	  '14:10 – 14:20'
  );

/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$rsUser = CUser::GetByID($USER->GetID());
	$thisUser = $rsUser->Fetch();
	$myShedule = array();
	$myFreeMeet = array();
	$myBeasyMeet = array();
	$myFreeCount = 0;
	//Формируем основу для массива встреч
	for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
		$myShedule[$i]["ID"] = $thisUser["UF_SHEDULE_".$i];
		$myShedule[$i]["TITLE"] = $times[$i-1];
		$myShedule[$i]["STATUS"] = '';
		$myShedule[$i]["NOTES"] = 'FREE';
		$myShedule[$i]["PARTNER_ID"] = '';
		$myShedule[$i]["REP"] = '';
		$myShedule[$i]["COMPANY"] = '';
		$myShedule[$i]["LIST"]["COUNT"] = 0;
		$myShedule[$i]["LIST"]["COMPANYS"] = array();
		if($thisUser["UF_SHEDULE_".$i] == ''){
		  $myFreeMeet[] = $i;
		  $myFreeCount++;
		}
		else{
		  $myShedule[$i]["NOTES"] = 'ACT';
		  $myBeasyMeet[] = $thisUser["UF_SHEDULE_".$i];
		}
	}
	//СПИСОК НАЗНАЧЕННЫХ ВСТРЕЧ
	if($myBeasyMeet){
		$arFilterM = Array(
		   "IBLOCK_ID" => $arParams["APP_ID"],
		   "ID" => $meeting_list
		   );
		$arSelect = Array("DATE_CREATE", "ID", "NAME", "ACTIVE", "PROPERTY_SENDER_ID", "PROPERTY_RECIVER_ID", "PROPERTY_STATUS", "PROPERTY_TIME");
		$resMeet = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilterM, false, false, $arSelect);
		while($ar_meet = $resMeet->GetNext()){
		  for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
			  if($thisUser["UF_SHEDULE_".$i] == $ar_meet["ID"]){
				if($ar_meet["ACTIVE"] == 'N'){
				  $myShedule[$i]["NOTES"] = 'N';
				}
				if($ar_meet['PROPERTY_SENDER_ID_VALUE'] == $thisUser['ID']){
				  $myShedule[$i]["STATUS"] = 'MY';
				  $myShedule[$i]["PARTNER_ID"] = $ar_meet['PROPERTY_RECIVER_ID_VALUE'];
				}
				else{
				  $myShedule[$i]["STATUS"] = 'PEP';
				  $myShedule[$i]["PARTNER_ID"] = $ar_meet['PROPERTY_SENDER_ID_VALUE'];
				}
				if($ar_meet['PROPERTY_STATUS_VALUE'] == 'ADM'){
				  $myShedule[$i]["STATUS"] = 'ADM';
				}
			  }
		  }
		}
	}
	//СПИСОК ПОЛЬЗОВАТЕЛЕЙ
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["GROUP_RECIVER_ID"])
	);
	$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
	$myWishIn = array();
	$myWishOut = array();
	$notFreeTimes = array();
	while($arUsersTemp=$rsUsers->Fetch()){
		$countFree = 0;
		for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
			if($myShedule[$i]["ID"] == ''){
			  if($arUsersTemp["UF_SHEDULE_".$i] == ''){
				$myShedule[$i]["LIST"]["COMPANYS"][$myShedule[$i]["LIST"]["COUNT"]]["ID"] = $arUsersTemp["ID"];
				$myShedule[$i]["LIST"]["COMPANYS"][$myShedule[$i]["LIST"]["COUNT"]]["NAME"] = $arUsersTemp["WORK_COMPANY"];
				$myShedule[$i]["LIST"]["COUNT"]++;
			  }
			}
			else{
				if($myShedule[$i]["PARTNER_ID"] == $arUsersTemp["ID"]){
				  $myShedule[$i]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
				  $myShedule[$i]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
				}
			}
			if($arUsersTemp["UF_SHEDULE_".$i] != ''){
				$countFree++;
			}
			
		}
		if($countFree == $arParams["APP_COUNT"]){
			$notFreeTimes[$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
		}
		if(stripos($thisUser["UF_WISH_OUT"], ", ".$arUsersTemp["ID"]." ") !== false){
			$myWishOut[$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
		}
		if(stripos($thisUser["UF_WISH_IN"], ", ".$arUsersTemp["ID"]." ") !== false){
			$myWishIn[$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
		}

	}
	$arResult["SHEDULE"] = $myShedule;
	$arResult["APP_COUNT"] = $arParams["APP_COUNT"];
	$arResult["WISH_IN"] = $myWishIn;
	$arResult["WISH_OUT"] = $myWishOut;
	$arResult["NOT_FREE"] = $notFreeTimes;
	
}
//echo "<pre>"; print_r($myShedule); echo "</pre>";
$this->IncludeComponentTemplate();
?>