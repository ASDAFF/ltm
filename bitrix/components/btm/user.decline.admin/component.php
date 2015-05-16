<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по Пользователям!<br />";
}

if(strLen($arParams["GROUP"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по Подтвержденным пользователям!<br />";
}

if(strLen($arParams["GROUP_OFF"])<=0){
	$arResult["ERROR_MESSAGE"] .= "Не введены данные по Отмененным пользователям!<br />";
}

if(strLen($arParams["APP_ID"])<=0){
	$arParams["APP_ID"] = "3";
}

if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}

CModule::IncludeModule('iblock');

/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/
$userGroups = CUser::GetUserGroup($arParams["USER"]);
if(!in_array($arParams["GROUP"], $userGroups)){
	$arResult["ERROR_MESSAGE"] = "Вы не можете изменить данного пользователя";
}
$arResult["SHOW"] = "FORM";
if($arResult["ERROR_MESSAGE"] == '')
{
	if($USER->IsAdmin()){
		if((isset($_POST['form'])) and ($_POST['form'] == 'send')){
    		$arResult["SHOW"] = "SENT";
		}
		$rsUser = CUser::GetByID($arParams["USER"]);
		$declineUser = $rsUser->Fetch();
		//ВСТРЕЧИ ПОЛЬЗОВАТЕЛЯ
		$userMeetings = array();
		$countMeetings = 0;
		for($i=1; $i<13; $i++){
			if($declineUser["UF_SHEDULE_".$i] != ''){
				$userMeetings[$declineUser["UF_SHEDULE_".$i]]["ID"] = $delineUser["UF_SHEDULE_".$i];
				$countMeetings++;
			}
		}
		$meeting_list = array();
		$meeting_list["LIST"] = array();
		$meeting_list["COUNT"] = 0;
		$arResult["FROM_NAME"] = $declineUser["NAME"]." ".$declineUser["LAST_NAME"];
		$arResult["FROM_COMPANY"] = $declineUser["WORK_COMPANY"];
		if($countMeetings){
			$arFilterM = Array(
			   "IBLOCK_ID" => $arParams["APP_ID"],
			   "!=PROPERTY_STATUS_VALUE" => 'DECLINE',
			   array(
					"LOGIC" => "OR",
					array("=PROPERTY_SENDER_ID" => $arParams["USER"]),
					array("=PROPERTY_RECIVER_ID" => $arParams["USER"]),
				),
			   );
			$arSelect = Array("DATE_CREATE", "ID", "NAME", "ACTIVE", "PROPERTY_SENDER_ID", "PROPERTY_RECIVER_ID", "PROPERTY_STATUS", "PROPERTY_TIME");
			$resMeet = CIBlockElement::GetList(Array("PROPERTY_TIME"=>"ASC"), $arFilterM, false, false, $arSelect);
			while($ar_meet = $resMeet->GetNext()){
				if(isset($userMeetings[$ar_meet["ID"]])){
					$meeting_list["LIST"][$meeting_list["COUNT"]]["ID"] = $ar_meet["ID"];
					$meeting_list["LIST"][$meeting_list["COUNT"]]["STATUS_KOD"] = $ar_meet["PROPERTY_STATUS_VALUE"];
					if($ar_meet["PROPERTY_STATUS_VALUE"] = 'ACCEPT'){
						$meeting_list["LIST"][$meeting_list["COUNT"]]["STATUS"] = "Подтверждена";
					}
					elseif($ar_meet["PROPERTY_STATUS_VALUE"] = 'ADM'){
						$meeting_list["LIST"][$meeting_list["COUNT"]]["STATUS"] = "Администратор";
					}
					else{
						$meeting_list["LIST"][$meeting_list["COUNT"]]["STATUS"] = "Не подтверждена";
					}
					$meeting_list["LIST"][$meeting_list["COUNT"]]["TIME_ID"] = $ar_meet["PROPERTY_TIME_ENUM_ID"];
					$meeting_list["LIST"][$meeting_list["COUNT"]]["TIME"] = $ar_meet["PROPERTY_TIME_VALUE"];
					if($arParams["USER"] == $ar_meet["PROPERTY_SENDER_ID_VALUE"]){
						$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_ID"] = $ar_meet["PROPERTY_RECIVER_ID_VALUE"];
					}
					else{
						$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_ID"] = $ar_meet["PROPERTY_SENDER_ID_VALUE"];
					}
					$rsUser = CUser::GetByID($meeting_list["LIST"][$meeting_list["COUNT"]]["TO_ID"]);
					$toTmpUser = $rsUser->Fetch();
					$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_NAME"] = $toTmpUser["NAME"]." ".$toTmpUser["LAST_NAME"];
					$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_COMPANY"] = $toTmpUser["WORK_COMPANY"];
					$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_APP"] = $toTmpUser["UF_SHEDULE_".$ar_meet["PROPERTY_TIME_ENUM_ID"]];
					$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_APP_COUNT"] = $toTmpUser["UF_COUNT_APP"];
					if($meeting_list["LIST"][$meeting_list["COUNT"]]["TO_APP_COUNT"] == ''){
						$meeting_list["LIST"][$meeting_list["COUNT"]]["TO_APP_COUNT"] = 0;
					}
					$meeting_list["COUNT"]++;
				}
			}
		}
		$arResult["MEETING"] = $meeting_list;
		//WISH ЛИСТЫ
		$declineUser["UF_WISH_OUT"] = trim(str_replace("  "," ",str_replace(",", "|", substr($declineUser["UF_WISH_OUT"],2))));
		$declineUser["UF_WISH_IN"] = trim(str_replace("  "," ",str_replace(",", "|", substr($declineUser["UF_WISH_IN"],2))));
		$myWishIn = array();
		$countIn = 0;
		$myWishOut = array();
		$countOut = 0;
		if($declineUser["UF_WISH_OUT"] != ''){
			$filter = Array(
				"ID"  => $declineUser["UF_WISH_OUT"]
			);
			$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
			while($arUsersTemp=$rsUsers->Fetch()){
				$myWishOut[$countOut]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
				$myWishOut[$countOut]["ID"] = $arUsersTemp["ID"];
				$myWishOut[$countOut]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
				$countOut++;
			}
		}
		if($declineUser["UF_WISH_IN"] != ''){
			$filter = Array(
				"ID"  => $declineUser["UF_WISH_IN"]
			);
			$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
			while($arUsersTemp=$rsUsers->Fetch()){
				$myWishIn[$countIn]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
				$myWishIn[$countIn]["ID"] = $arUsersTemp["ID"];
				$myWishIn[$countIn]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
				$countIn++;
			}
		}
		$arResult["WISH_IN"]["LIST"] = $myWishIn;
		$arResult["WISH_IN"]["COUNT"] = $countIn;
		$arResult["WISH_OUT"]["LIST"] = $myWishOut;
		$arResult["WISH_OUT"]["COUNT"] = $countOut;
		if($arResult["SHOW"] == "SENT"){
			$fieldsFrom = Array(
			"UF_COUNT_APP"      => "",
			);
			//Отмена встреч
			for($i=0; $i < $arResult["MEETING"]["COUNT"]; $i++){
				if($arResult["MEETING"]["LIST"][$i]["ID"] == $arResult["MEETING"]["LIST"][$i]["TO_APP"]){
				  //Меняем активность у встречи и переносим ее в группу
				  $readMeet = new CIBlockElement;
				  $PROP = array();
				  $PROP[4] = array("VALUE" => $arParams["APP_DECLINE"]);
				  $arLoadProductArray = Array(
					"ACTIVE"         => "N",
					"IBLOCK_SECTION" => $arParams["GROUP_DECLINE"]
					);
				  CIBlockElement::SetPropertyValueCode($arResult["MEETING"]["LIST"][$i]["ID"], "STATUS", $PROP);
				  $resM = $readMeet->Update($arResult["MEETING"]["LIST"][$i]["ID"], $arLoadProductArray);	
				  
				  // Меняем количество подтвержденных встреч
				  $strError = '';
				  //Получатель
				  if($arResult["MEETING"]["LIST"][$i]["STATUS_KOD"] == 'NOT'){
					  $arResult["MEETING"]["LIST"][$i]["TO_APP_COUNT"]--;
					  if($arResult["MEETING"]["LIST"][$i]["TO_APP_COUNT"] < 0){
						  $arResult["MEETING"]["LIST"][$i]["TO_APP_COUNT"] = 0;
					  }
				  }
				  
				  $toUser = new CUser;				  
				  $fields = Array(
					"UF_COUNT_APP"      => $arResult["MEETING"]["LIST"][$i]["TO_APP_COUNT"],
					"UF_SHEDULE_".$arResult["MEETING"]["LIST"][$i]["TIME_ID"] => "",
					);
				  $toUser->Update($arResult["MEETING"]["LIST"][$i]["TO_ID"], $fields);
				  $strError .= $toUser->LAST_ERROR;
				  
				  if($strError){
					  $arResult["ERROR_MESSAGE"] .= $strError;
				  }

				//Отправитель
				  $fieldsFrom["UF_SHEDULE_".$arResult["MEETING"]["LIST"][$i]["TIME_ID"]] = "";				  
				}
				else{
					$arResult["ERROR_MESSAGE"] .= "У второго пользователя с id=".$arResult["MEETING"]["LIST"][$i]["TO_ID"]." данная встреча отсутствует!";
				}
			}
			//Удаление из wish листов
			for($i; $i < $arResult["WISH_IN"]["COUNT"]; $i++){
				$strError = '';
				$rsWishUser = CUser::GetByID($arResult["WISH_IN"]["LIST"][$i]["ID"]);
				$userWishUser = $rsWishUser->Fetch();
				$wishListOut = str_replace(", ".$arParams["USER"]." ", "", $userWishUser["UF_WISH_OUT"]);
				$toWishUser = new CUser;
				$fieldsWish = array();
				$fieldsWish = Array(
				  "UF_WISH_OUT"      => $wishListOut,
				);
				$toWishUser->Update($arResult["WISH_IN"]["LIST"][$i]["ID"], $fieldsWish);
				$strError .= $toWishUser->LAST_ERROR;
				
				if($strError){
					$arResult["ERROR_MESSAGE"] .= $strError;
				}				
			}
			for($i; $i < $arResult["WISH_OUT"]["COUNT"]; $i++){
				$strError = '';
				$rsWishUser = CUser::GetByID($arResult["WISH_OUT"]["LIST"][$i]["ID"]);
				$userWishUser = $rsWishUser->Fetch();
				$wishListIn = str_replace(", ".$arParams["USER"]." ", "", $userWishUser["UF_WISH_IN"]);
				$toWishUser = new CUser;
				$fieldsWish = array();
				$fieldsWish = Array(
				  "UF_WISH_IN"      => $wishListIn,
				);
				$toWishUser->Update($arResult["WISH_OUT"]["LIST"][$i]["ID"], $fieldsWish);
				$strError .= $toWishUser->LAST_ERROR;
				
				if($strError){
					$arResult["ERROR_MESSAGE"] .= $strError;
				}				
			}
			$fieldsFrom["UF_WISH_OUT"] = '';
			$fieldsFrom["UF_WISH_IN"] = '';
			$fromUser = new CUser;
			$fromUser->Update($arParams["USER"], $fieldsWish);
			$strError .= $fromUser->LAST_ERROR;
			
			if($strError){
				$arResult["ERROR_MESSAGE"] .= $strError;
			}
		}
	}
	else{
		$arResult["ERROR_MESSAGE"] = "У вас недостаточно прав для просмотра данной страницы!";
	}
}
//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>