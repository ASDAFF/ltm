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

$arResult = array();
if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
	$arResult["USER_ID"] = intval($_REQUEST["UID"]);
} else {
	$arResult["USER_ID"] = $USER->GetID();
}

$arResult["EX_TYPE"] = $arParams["TYPE"];

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form") || !CModule::IncludeModule("doka.meetings")){
	$this->AbortResultCache();
	throw new Exception("Can't load modules iblock, form or doka");
}

//Определяем текущую выставку
if(isset($arParams["EXHIB_CODE"]) && $arParams["EXHIB_CODE"]!=''){
	$filterEx = array(
		"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
		"CODE" => $arParams["EXHIB_CODE"]
	);
	$rsExhib = CIBlockElement::GetList(
		array(),
		$filterEx,
		false,
		false,
		array("ID", "CODE","IBLOCK_ID","PROPERTY_APP_HB_ID","PROPERTY_APP_ID","PROPERTY_C_GUESTS_GROUP")
	);
	while($oExhib = $rsExhib->Fetch()){
		if(isset($arParams["TYPE"]) && $arParams["TYPE"] == 'HB' && !empty($oExhib["PROPERTY_APP_HB_ID_VALUE"])){
			$appId = $oExhib["PROPERTY_APP_HB_ID_VALUE"];
		}
		else{
			$appId = $oExhib["PROPERTY_APP_ID_VALUE"];
		}
		$arParams["APP_ID"] = $appId;
		$arParams["EXIB_ID"] = $oExhib["ID"];
		$cGuestGroup = $oExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];
	}
}

if (empty($arParams["APP_ID"])) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

$arResult["APP_CODE"] = $arParams["EXHIB_CODE"];
$arResult["APP_ID"] = $arParams["APP_ID"];

//подключение модуля встреч
use Doka\Meetings\Requests as DokaRequest;

$req_obj = new DokaRequest($arParams["APP_ID"]);
$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

$arParams["SORT_TYPE"] = htmlspecialcharsEx(trim($_REQUEST["type"]));
$arParams["SORT"] = htmlspecialcharsEx(trim($_REQUEST["by"]));

//определение из какой группы брать данные
switch ($arParams["SORT"])
{
	case "alphabet":
		$arParams["SORT"] = "BY_ALPHABET";
		break;
	case "priority_areas" : $arParams["SORT"] = "BY_PRIORITY_AREAS"; break;
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

$propertyName = CFormMatrix::getPropertyIDByExh($arParams["EXIB_ID"]);

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
		$arAnswers = $res["userAns"];
		$arQuestions = $res["userQuest"];
	}
}
if (!is_array($arResult["USERS"]) || empty($arResult["USERS"])){
	$arFilter = array(
		"GROUPS_ID" => $cGuestGroup,
		"ACTIVE" => "Y",
	);
	if($arParams["TYPE"] == "MORNING"){
		$arFilter["UF_MR"] = 1;
	}
	elseif($arParams["TYPE"] == "EVENING"){
		$arFilter["UF_EV"] = 1;
	}
	elseif($arParams["TYPE"] == "HB"){
		$arFilter["UF_HB"] = 1;
	}

	$arParamsUser= array(
		"FIELDS" => array("ID", "NAME", "LAST_NAME", "WORK_COMPANY", "LOGIN", "EMAIL"),
		"SELECT" => array("UF_*")
	);
	$rsUsers = $USER->GetList(($by="work_company"), ($order="asc"), $arFilter, $arParamsUser);

	$arResultId = array();//тут список результатов пользователей
	while($arUser = $rsUsers->Fetch()){
		$arResultId[] = $arUser[$propertyName];//дописываем id результата заполнения формы
		$arResult["USERS"][$arUser["ID"]] = $arUser;
	}

	//получение результатов заполнения формы регистрациия для пользователей
	CForm::GetResultAnswerArray(
		GUEST_FORM_ID,
		$arQuestions,
		$arAnswers,
		$arAnswersVarname,
		array("RESULT_ID" => implode("|",$arResultId)
		)
	);

	//////////// end cache /////////
	if ($cache_time > 0){
		$cache->StartDataCache($cache_time, $cache_id, $cache_path);
		$cache->EndDataCache(array(
			"userList"=>$arResult["USERS"],
			"userAns"=>$arAnswers,
			"userQuest"=>$arQuestions,
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

		break;
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
foreach($arResult["USERS"] as &$arUser){
	$resultId = $arUser[$propertyName];

	//гость
	$gName = trim($arAnswers[$resultId][113][216]["USER_TEXT"]);
	$gLastName = trim($arAnswers[$resultId][114][217]["USER_TEXT"]);
	$gCompanyName = trim($arAnswers[$resultId][107][204]["USER_TEXT"]);

	//если ввели два адреса
	$gCompanyLink = trim($arAnswers[$resultId][119][222]["USER_TEXT"]);

	if($gCompanyLink == "http://")
	{
		$gCompanyLink = "";
	}
	else
	{
		$gCompanyLink = preg_split ("/[;,!]/", $gCompanyLink);
		$gCompanyLink = $gCompanyLink[0];
		$gCompanyLink = (substr($gCompanyLink, 0 , 4) == "http") ? $gCompanyLink : "http://" . $gCompanyLink;
	}

	$gCity = trim($arAnswers[$resultId][111][210]["USER_TEXT"]);

	$gCountry = reset($arAnswers[$resultId][112]);
	$gCountry = trim($gCountry["ANSWER_TEXT"]);

	//коллега 1 на вечер
	$ce1Name = trim($arAnswers[$resultId][120][223]["USER_TEXT"]);
	$ce1LastName = trim($arAnswers[$resultId][121][224]["USER_TEXT"]);
	//коллега 2 на вечер
	$ce2Name = trim($arAnswers[$resultId][124][227]["USER_TEXT"]);
	$ce2LastName = trim($arAnswers[$resultId][125][228]["USER_TEXT"]);
	//коллега 3 на вечер
	$ce3Name = trim($arAnswers[$resultId][128][231]["USER_TEXT"]);
	$ce3LastName = trim($arAnswers[$resultId][129][232]["USER_TEXT"]);
	//коллега на утро
	$cmName = trim($arAnswers[$resultId][477][839]["USER_TEXT"]);
	$cmLastName = trim($arAnswers[$resultId][478][840]["USER_TEXT"]);

	//Вид деятельности
	$arBusinessType = array();
	foreach ($arAnswers[$resultId][108] as $BusinessType)
	{
		$arBusinessType[$BusinessType["ANSWER_ID"]] = trim($BusinessType["ANSWER_TEXT"]);
	}
	//Описание
	$arDescript = array();
	foreach ($arAnswers[$resultId][135] as $Descript)
	{
		$arDescript[$Descript["ANSWER_ID"]] = trim($Descript["USER_TEXT"]);
	}
	$arDescript = str_replace('вЂў', '', $arDescript);

	//Город
	$arTown = array();
	if($arParams["SORT"] == "BY_CITY"){
		foreach ($arAnswers[$resultId][111] as $Town){
			$arTown[$Town["ANSWER_ID"]] = trim($Town["USER_TEXT"]);
			$arResult["FILTER"]["CHILD"][trim($Town["USER_TEXT"])] = trim($Town["USER_TEXT"]);
			$arTmpRes[trim($Town["USER_TEXT"])][$arUser["ID"]] = $arUser["ID"];
		}
	}
	else{
		foreach ($arAnswers[$resultId][111] as $Town){
			$arTown[$Town["ANSWER_ID"]] = trim($Town["USER_TEXT"]);
		}
	}
	//Приоритетное направление
	if($arParams["SORT"] == "BY_PRIORITY_AREAS"){
		//North America
		if(isset($arAnswers[$resultId][136]) && !empty($arAnswers[$resultId][136])){
			foreach ($arAnswers[$resultId][136] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = $country["ANSWER_TEXT"];
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Europe
		if(isset($arAnswers[$resultId][137]) && !empty($arAnswers[$resultId][137])){
			foreach ($arAnswers[$resultId][137] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = $country["ANSWER_TEXT"];
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//South America
		if(isset($arAnswers[$resultId][138]) && !empty($arAnswers[$resultId][138])){
			foreach ($arAnswers[$resultId][138] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = $country["ANSWER_TEXT"];
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Africa
		if(isset($arAnswers[$resultId][139]) && !empty($arAnswers[$resultId][139])){
			foreach ($arAnswers[$resultId][139] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = $country["ANSWER_TEXT"];
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Asia
		if(isset($arAnswers[$resultId][475]) && !empty($arAnswers[$resultId][475])){
			foreach ($arAnswers[$resultId][475] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = $country["ANSWER_TEXT"];
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
		//Oceania
		if(isset($arAnswers[$resultId][476]) && !empty($arAnswers[$resultId][476])){
			foreach ($arAnswers[$resultId][476] as $country){
				$arResult["FILTER"]["CHILD"][$country["ANSWER_ID"]] = $country["ANSWER_TEXT"];
				$arTmpRes[$country["ANSWER_ID"]][$arUser["ID"]] = $arUser["ID"];
			}
		}
	}

	//По алфавиту
	if($arParams["SORT"] == "BY_ALPHABET"){
		$firstLetter = strtoupper($gCompanyName{0});
		if($firstLetter === $arParams["SORT_TYPE"] || ($arParams["SORT_TYPE"] == 'NUM' && is_numeric($firstLetter))){
			$arResult["RESULTS"][$arUser["ID"]] = $arUser["ID"];
		}
	}

	if($arParams["SORT"] == "BY_ALL"){
		$arResult["RESULTS"][$arUser["ID"]] = $arUser["ID"];
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
	$arFormData = array(
		"NAME" =>$gName,
		"LAST_NAME" =>$gLastName,
		"FIO" => $gName . " " . $gLastName,
		"COMPANY" => array("NAME" => $gCompanyName, "LINK" => $gCompanyLink),
		"CITY" => $gCity,
		"COUNTRY" => $gCountry,
		"COLLEAGUE_E" => array(
			array(
				"NAME" => $ce1Name,
				"LAST_NAME" => $ce1LastName,
				"FIO" => $ce1Name . " " . $ce1LastName
			),
			array(
				"NAME" => $ce2Name,
				"LAST_NAME" => $ce2LastName,
				"FIO" => $ce2Name . " " . $ce2LastName
			),
			array(
				"NAME" => $ce3Name,
				"LAST_NAME" => $ce3LastName,
				"FIO" => $ce3Name . " " . $ce3LastName
			),
		),
		"COLLEAGUE_M" => array(
			"NAME" => $cmName,
			"LAST_NAME" => $cmLastName,
			"FIO" => $cmName . " " . $cmLastName
		),
		"PRIORITY_AREAS" => $arPriorArea,
		"BUSINESS_TYPE" => $arBusinessType,
		"TIME_SLOTS" => $userTimeSlot,
		"DESCRIPTION" => $arDescript,
		"TOWN" => $arTown,
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
$rsItems = new CDBResult;
$rsItems->InitFromArray($arResult["RESULTS"]);
$rsItems->NavStart(50);

$arResult["NAVIGATE"] = $rsItems->GetPageNavStringEx($navComponentObject, "", "");
$arResult["RESULTS"] = array();

while($arItems = $rsItems->Fetch())
{
	$arResult["RESULTS"][$arItems] = $arResult["USERS"][$arItems];
}
unset($arResult["USERS"]);

$this->IncludeComponentTemplate();

?>