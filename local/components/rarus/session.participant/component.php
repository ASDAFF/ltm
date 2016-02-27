<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

if(!isset($arParams["EXHIB_IBLOCK_ID"]))
	$arParams["EXHIB_IBLOCK_ID"] = 15;

if(!isset($arParams["EXHIB_CODE"]))
	$arParams["EXHIB_CODE"] = trim($_REQUEST["EXHIBIT_CODE"]);

if(!isset($arParams["TYPE"]))
	$arParams["TYPE"] = "MORNING";

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")  || !CModule::IncludeModule("doka.meetings"))
{
	$this->AbortResultCache();
	throw new Exception("Can't load modules iblock form");
}

$arResult = array();

if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
	$arResult["USER_ID"] = intval($_REQUEST["UID"]);
} else {
	$arResult["USER_ID"] = $USER->GetID();
}

$arResult["EX_TYPE"] = $arParams["TYPE"];

//получение текущей выставки
$filterEx = array(
	"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
	"CODE" => $arParams["EXHIB_CODE"]
);
$rsExhib = CIBlockElement::GetList(
	array(),
	$filterEx,
	false,
	false,
	array("ID", "CODE","IBLOCK_ID","PROPERTY_APP_HB_ID","PROPERTY_APP_ID","PROPERTY_USER_GROUP_ID")
);
while($oExhib = $rsExhib->Fetch()){
	if(isset($arParams["TYPE"]) && $arParams["TYPE"] == 'HB'){
		$appId = $oExhib["PROPERTY_APP_HB_ID_VALUE"];
		$arResult["USER_TYPE"] = "HB";
	}
	else{
		$appId = $oExhib["PROPERTY_APP_ID_VALUE"];
		$arResult["USER_TYPE"] = "";
	}
	$arParams["APP_ID"] = $appId;
	$arParams["EXIB_ID"] = $oExhib["ID"];
	$cParticipantGroup = $oExhib["PROPERTY_USER_GROUP_ID_VALUE"];
}

if (empty($arParams["APP_ID"])) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
$arResult["APP_CODE"] = $arParams["EXHIB_CODE"];

//подключение модуля встреч
use Doka\Meetings\Requests as DokaRequest;

$req_obj = new DokaRequest($arParams["APP_ID"]);
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

$arParams["SORT_TYPE"] = htmlspecialcharsEx(trim($_REQUEST["type"]));
$arParams["SORT"] = htmlspecialcharsEx(trim($_REQUEST["by"]));
//определение из какой группы брать данные
switch ($arParams["SORT"]){
	case "alphabet":
		$arParams["SORT"] = "BY_ALPHABET";
		break;
	case "priority_areas" : $arParams["SORT"] = "BY_PRIORITY_AREAS"; break;
	case "business" : $arParams["SORT"] = "BY_BUSINESS"; break;
	case "city" : $arParams["SORT"] = "BY_CITY"; break;
	case "slots" :
		$arParams["SORT"] = "BY_SLOTS";
		$allTimeSlot = $req_obj->getAllMeetTimeslots();
		foreach($allTimeSlot as $timeID => $timeslot){
			if(!$arParams["SORT_TYPE"]){
				$arParams["SORT_TYPE"] = $timeID;
			}
			$arResult["FILTER"]["CHILD"][$timeID] = $timeslot["name"];
		}
		break;
	default:  $arParams["SORT"] = "BY_ALL";
}

$arResultId = array();//тут список результатов пользователей
$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arParams["EXIB_ID"], 0);//свойство участника
$propertyNameColleague = CFormMatrix::getPropertyIDByExh($arParams["EXIB_ID"], 1);//свойство коллеги
$formId = CFormMatrix::getPFormIDByExh($arParams["EXIB_ID"]);

$arResultId = array();//тут список результатов пользователей
$arResultAllFormId = array();//тут список результатов заполнения формы участники все выставки

//получение списка подтвержденных гостей на данную выставку
$cache = new CPHPCache();
$cache_time = $arParams["CACHE_TIME"];
$cache_id = 'userList'.$arParams["TYPE"].'gr'.$cGuestGroup;
$cache_path = 'userList';
if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path))
{
	$res = $cache->GetVars();
	if (is_array($res["userList"]) && (count($res["userList"]) > 0)){
		$arResult["USERS"] = $res["userList"];
		$arAnswersUser = $res["userAns"];
		$arQuestionsUser = $res["userQuest"];
		$arAnswersCompany = $res["userCompAns"];
		$arQuestionsCompany = $res["userCompQuest"];
	}
}
if (!is_array($arResult["USERS"]) || empty($arResult["USERS"])){
	//получение списка подтвержденных Участников на данную выставку
	$arFilter = array(
		"GROUPS_ID" => $cParticipantGroup,
		"ACTIVE" => "Y",
	);
	$arParamsUser= array(
		"FIELDS" => array("ID", "NAME", "LAST_NAME", "WORK_COMPANY", "LOGIN", "EMAIL"),
		"SELECT" => array("UF_*")
	);

	$arResultId = array();//тут список результатов пользователей
	$arResultAllFormId = array();//тут список результатов заполнения формы участники все выставки

	$rsUsers = $USER->GetList(($by="work_company"), ($order="asc"), $arFilter, $arParamsUser);
	while($arUser = $rsUsers->Fetch()){
		$arResultId[] = $arUser[$propertyNameParticipant];//дописываем id результата заполнения формы
		$arResultId[] = $arUser[$propertyNameColleague];
		$arResultAllFormId[] = $arUser["UF_ID_COMP"];
		$arResult["USERS"][$arUser["ID"]] = $arUser;
	}

	CForm::GetResultAnswerArray(
		$formId,
		$arQuestionsUser,
		$arAnswersUser,
		$arAnswersVarnameUser,
		array("RESULT_ID" => implode("|",$arResultId)
		)
	);

	CForm::GetResultAnswerArray(
		PARTICIPANT_FORM_ID,
		$arQuestionsCompany,
		$arAnswersCompany,
		$arAnswersVarnameCompany,
		array("RESULT_ID" => implode("|",$arResultAllFormId)
		)
	);

	//////////// end cache /////////
	if ($cache_time > 0){
		$cache->StartDataCache($cache_time, $cache_id, $cache_path);
		$cache->EndDataCache(array(
			"userList"=>$arResult["USERS"],
			"userAns"=>$arAnswersUser,
			"userQuest"=>$arQuestionsUser,
			"userCompAns"=>$arAnswersCompany,
			"userCompQuest"=>$arQuestionsCompany,
		));
	}
}

$needSort = true;
$chooseRes = true;
switch ($arParams["SORT"])
{
	case "BY_ALPHABET":
		$arResult["FILTER"]["CHILD"] = array(
			"NUM" =>"#", "A" =>"A", "B" =>"B", "C" =>"C", "D" =>"D", "E" =>"E", "F" =>"F", "G" =>"G", "H" =>"H", "I" =>"I", "J" =>"J",
			"K" =>"K", "L" =>"L", "M" =>"M", "N" =>"N", "O" =>"O", "P" =>"P", "Q" =>"Q", "R" =>"R", "S" =>"S", "T" =>"T", "U" =>"U", "V" =>"V",
			"W" =>"W", "X" =>"X", "Y" =>"Y", "Z" =>"Z");
		$needSort = false;
		$chooseRes = false;
		break;
	case "BY_PRIORITY_AREAS" :
	case "BY_BUSINESS" :
	case "BY_CITY" :
		break;
	case "BY_SLOTS" :
		$needSort = false;
		$chooseRes = false;
		break;
	default:
		$needSort = false;
		$chooseRes = false;
}

$arTmpRes = array();
if(!$arParams["SORT_TYPE"] && !empty($arResult["FILTER"]["CHILD"])){
	foreach($arResult["FILTER"]["CHILD"] as $timeID => $timeslot){
		$arParams["SORT_TYPE"] = $timeID;
		break;
	}
}
$fieldId = CFormMatrix::getQIDByBase(33 , $formId);
$answerID = CFormMatrix::getAnswerRelBase(85 ,$formId);//Participant last name

foreach($arResult["USERS"] as &$arUser) {
	$resultIdUser = $arUser[$propertyNameParticipant];
	$resultIdColleague = $arUser[$propertyNameColleague];
	$resultIdCompany = $arUser["UF_ID_COMP"];

	$fieldId = CFormMatrix::getQIDByBase(32 , $formId);
	$answerID = CFormMatrix::getAnswerRelBase(84 ,$formId);//Participant first name

	//участник
	$pName = trim($arAnswersUser[$resultIdUser][$fieldId][$answerID]["USER_TEXT"]);
	//коллега
	$cName = trim($arAnswersUser[$resultIdColleague][$fieldId][$answerID]["USER_TEXT"]);




	//участник
	$pLastName = trim($arAnswersUser[$resultIdUser][$fieldId][$answerID]["USER_TEXT"]);
	//коллега
	$cLastName = trim($arAnswersUser[$resultIdColleague][$fieldId][$answerID]["USER_TEXT"]);



	$pCompanyName = trim($arAnswersCompany[$resultIdCompany][17][30]["USER_TEXT"]);

	//если ввели два адреса
	$pCompanyLink = trim($arAnswersCompany[$resultIdCompany][23][38]["USER_TEXT"]);
	if($pCompanyLink == "http://")
	{
		$pCompanyLink = "";
	}
	else
	{
		$pCompanyLink = preg_split ("/[;,!]/", $pCompanyLink);
		$pCompanyLink = $pCompanyLink[0];
		$pCompanyLink = (substr($pCompanyLink, 0 , 4) == "http") ? $pCompanyLink : "http://" . $pCompanyLink;
	}


	$pCity = trim($arAnswersCompany[$resultIdCompany][21][36]["USER_TEXT"]);
	$pCountry = trim($arAnswersCompany[$resultIdCompany][22][37]["USER_TEXT"]);

	//Город
	$arTown = array();
	if($arParams["SORT"] == "BY_CITY"){
		$arResult["FILTER"]["CHILD"][$pCity] = $pCity;
		$arTmpRes[$pCity][$arUser["ID"]] = $arUser["ID"];
	}

	//По алфавиту
	if($arParams["SORT"] == "BY_ALPHABET"){
		$firstLetter = strtoupper($pCompanyName{0});
		if($firstLetter === $arParams["SORT_TYPE"] || ($arParams["SORT_TYPE"] == 'NUM' && is_numeric($firstLetter))){
			$arResult["RESULTS"][$arUser["ID"]] = $arUser["ID"];
		}
	}

	$times =  $req_obj->getSortedFreeTimesAppoint($arUser["ID"]);//свободные таймслоты пользователя
	$userTimeSlot = array();

	if($arParams["SORT"] == "BY_SLOTS"){
		foreach ($times as $time){
			$userTimeSlot[$time["id"]] = $time["name"];
			if($time["id"] == $arParams["SORT_TYPE"])
				$arResult["RESULTS"][$arUser["ID"]] = $arUser["ID"];
		}
	}
	else{
		foreach ($times as $time){
			$userTimeSlot[$time["id"]] = $time["name"];
		}
	}
	//Вид деятельности
	$arBusinessType = array();
	if($arParams["SORT"] == "BY_BUSINESS"){
		foreach ($arAnswersCompany[$resultIdCompany][19] as $business){
			$arBusinessType[$business["ANSWER_ID"]] = trim($business["ANSWER_TEXT"]);
			$arResult["FILTER"]["CHILD"][$business["ANSWER_ID"]] = trim($business["ANSWER_TEXT"]);
			$arTmpRes[$business["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
		}
	}
	else{
		foreach ($arAnswersCompany[$resultIdCompany][19] as $business){
			$arBusinessType[$business["ANSWER_ID"]] = trim($business["ANSWER_TEXT"]);
		}
	}

	if($arParams["SORT"] == "BY_ALL"){
		$arResult["RESULTS"][$arUser["ID"]] = $arUser["ID"];
	}

	//Приоритетное направление
	if($arParams["SORT"] == "BY_PRIORITY_AREAS"){
		//North America
		if(isset($arAnswersCompany[$resultIdCompany][25]) && !empty($arAnswersCompany[$resultIdCompany][25])){
			foreach ($arAnswersCompany[$resultIdCompany][25] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Europe
		if(isset($arAnswersCompany[$resultIdCompany][26]) && !empty($arAnswersCompany[$resultIdCompany][26])){
			foreach ($arAnswersCompany[$resultIdCompany][26] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//South America
		if(isset($arAnswersCompany[$resultIdCompany][27]) && !empty($arAnswersCompany[$resultIdCompany][27])){
			foreach ($arAnswersCompany[$resultIdCompany][27] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Africa
		if(isset($arAnswersCompany[$resultIdCompany][28]) && !empty($arAnswersCompany[$resultIdCompany][28])){
			foreach ($arAnswersCompany[$resultIdCompany][28] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Asia
		if(isset($arAnswersCompany[$resultIdCompany][29]) && !empty($arAnswersCompany[$resultIdCompany][29])){
			foreach ($arAnswersCompany[$resultIdCompany][29] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Oceania
		if(isset($arAnswersCompany[$resultIdCompany][30]) && !empty($arAnswersCompany[$resultIdCompany][30])){
			foreach ($arAnswersCompany[$resultIdCompany][30] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
	                                                                }

	$arFormData = array(
		"PARTICIPANT" => array(
			"NAME" =>$pName,
			"LAST_NAME" =>$pLastName,
			"FIO" => $pName . " " . $pLastName,
		),
		"COLLEAGUE" => array(
			"NAME" =>$cName,
			"LAST_NAME" =>$cLastName,
			"FIO" => $cName . " " . $cLastName,
		),

		"COMPANY" => array("NAME" => $pCompanyName, "LINK" => $pCompanyLink),
		"CITY" => $pCity,
		"PRIORITY_AREAS" => $arPriorArea,
		"BUSINESS_TYPE" => $arBusinessType,
		"TIME_SLOTS" => $userTimeSlot
	);
	$arUser["FORM_DATA"] = $arFormData;
}

if($needSort){
	asort($arResult["FILTER"]["CHILD"]);
}
if(!$arParams["SORT_TYPE"]){
	foreach($arResult["FILTER"]["CHILD"] as $timeID => $timeslot){
		$arParams["SORT_TYPE"] = $timeID;
		break;
	}
}
if($chooseRes){
	$arResult["RESULTS"] = $arTmpRes[$arParams["SORT_TYPE"]];
	unset($arTmpRes);
}

$arResult["SORT"] = $arParams["SORT"];
$arResult["SORT_TYPE"] = $arParams["SORT_TYPE"];

$arResult["RESULTS"] = $arResult["USERS"];

unset($arResult["USERS"]);

$this->IncludeComponentTemplate();
?>