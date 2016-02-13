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

//подключение модуля встреч
use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;


$arResult = array();

if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
	$arResult["USER_ID"] = intval($_REQUEST["UID"]);
} else {
	$arResult["USER_ID"] = $USER->GetID();
}

$by = htmlspecialcharsEx(trim($_REQUEST["by"]));

//определение из какой группы брать данные
switch ($by)
{
	case "alphabet" : $sort = "BY_ALPHABET"; break;
	case "priority_areas" : $sort = "BY_PRIORITY_AREAS"; break;
	case "city" : $sort = "BY_CITY"; break;
	case "slots" : $sort = "BY_SLOTS"; break;
	case "all" : $sort = "BY_ALL"; break;
	default:  $sort = "BY_ALL";
}

$arResult["EX_TYPE"] = $arParams["TYPE"];
if($this->StartResultCache(false, array_merge($arParams, $arResult)))
{
	if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form") || !CModule::IncludeModule("doka.meetings"))
	{
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock, form or doka");
	}


	//получение текущей выставки

	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
					"CODE" => $arParams["EXHIB_CODE"]
				),
			false,
			false,
			array("ID", "CODE","IBLOCK_ID","PROPERTY_*")
			);
	if($oExhib = $rsExhib->GetNextElement(true, false))
	{

		$arResult["EXHIBITION"] = $oExhib->GetFields();
		$arResult["EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
		unset($arResult["EXHIBITION"]["PROPERTIES"]["MORE_PHOTO"]);
		if($arParams["TYPE"] == 'HB' && $arResult["EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"]){
			$appId = $arResult["EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
		}
		else{
			$appId = $arResult["EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
		}		

		$arResult["APP_ID"] = $appId;
		$arResult["APP_CODE"] = $arParams["EXHIB_CODE"];
		$req_obj = new DokaRequest($appId);
		$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

		//получение списка подтвержденных гостей на данную выставку

		$cGuestGroup = $arResult["EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];

		$arFilter = array(
				"GROUPS_ID" => $cGuestGroup,
				"ACTIVE" => "Y",
				);
		$arParamsUser= array(
				"FIELDS" => array("ID", "NAME", "LAST_NAME", "WORK_COMPANY", "LOGIN", "EMAIL"),
				"SELECT" => array("UF_*")
				);

		$arResultId = array();//тут список результатов пользователей
		$propertyName = CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"]);

		$rsUsers = $USER->GetList(($by="work_company"), ($order="asc"), $arFilter, $arParamsUser);
		while($arUser = $rsUsers->Fetch())
		{

			$arResultId[] = $arUser[$propertyName];//дописываем id результата заполнения формы

			//разделение участников на утро, вечер, hb
			//утро
			if($arUser["UF_MR"] && $arParams["TYPE"] == "MORNING")
			{
				$arResult["SESSION"]["MORNING"]["USERS"][$arUser["ID"]] = $arUser;
			}

			//вечер
			if($arUser["UF_EV"] && $arParams["TYPE"] == "EVENING")
			{
				$arResult["SESSION"]["EVENING"]["USERS"][$arUser["ID"]] = $arUser;
			}

			//hosted buyers
			if($arUser["UF_HB"] && $arParams["TYPE"] == "HB")
			{
				$arResult["SESSION"]["HB"]["USERS"][$arUser["ID"]] = $arUser;
			}
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
		
		//echo '<pre>'; print_r($arAnswers); echo '</pre>';

		$allTimeSlot = $req_obj->getAllMeetTimeslots();
		$arTypes = array("MORNING", "EVENING", "HB");
		foreach($arTypes as $type)
		{
			if($type != $arParams["TYPE"])
			{
				continue;
			}
		    $arResult["SESSION"][$type]["BY_SLOTS"] = $allTimeSlot;
			//проход по пользователям в типе
			foreach ($arResult["SESSION"][$type]["USERS"] as &$arUser)
			{
				//слоты пользователей
				if($sort = "BY_SLOTS"){
					$times =  $req_obj->getSortedFreeTimesAppoint($arUser["ID"]);//свободные таймслоты пользователя
					$userTimeSlot = array();

					foreach ($times as $time)
					{
						$userTimeSlot[$time["id"]] = $time["name"];
						$arResult["SESSION"][$type]["BY_SLOTS"][$time["id"]]["ID"][] = $arUser["ID"];

					}
				}

				if($sort = "BY_ALPHABET"){
					if(!isset($arResult["SESSION"][$type]["BY_ALPHABET"][0]))
					{
						$arResult["SESSION"][$type]["BY_ALPHABET"][0] = array("NAME" => "NUM", "ID" => array());
					}
				}

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


				$arPriorArea = array();

				//North America
				$arPriorArea[136]["NAME"] = trim($arQuestions[136]["TITLE"]);
				foreach ($arAnswers[$resultId][136] as $country)
				{
					$arPriorArea[136]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				}

				//Europe
				$arPriorArea[137]["NAME"] = trim($arQuestions[137]["TITLE"]);
				foreach ($arAnswers[$resultId][137] as $country)
				{
					$arPriorArea[137]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				}

				//South America
				$arPriorArea[138]["NAME"] = trim($arQuestions[138]["TITLE"]);
				foreach ($arAnswers[$resultId][138] as $country)
				{
					$arPriorArea[138]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				}

				//Africa
				$arPriorArea[139]["NAME"] = trim($arQuestions[139]["TITLE"]);
				foreach ($arAnswers[$resultId][139] as $country)
				{
					$arPriorArea[139]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				}

				//Asia
				$arPriorArea[475]["NAME"] = trim($arQuestions[475]["TITLE"]);
				foreach ($arAnswers[$resultId][475] as $country)
				{
					$arPriorArea[475]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				}

				//Oceania
				$arPriorArea[139]["NAME"] = trim($arQuestions[476]["TITLE"]);
				foreach ($arAnswers[$resultId][476] as $country)
				{
					$arPriorArea[475]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
				}

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
				foreach ($arAnswers[$resultId][111] as $Town)
				{
					$arTown[$Town["ANSWER_ID"]] = trim($Town["USER_TEXT"]);
				}

				//рарзделение по городам в приоритетных направлениях
				if($sort = "BY_PRIORITY_AREAS"){
					foreach ($arPriorArea as $id=>$area)
					{
						foreach ($area["ITEMS"] as $answerID => $name)
						{
							//поиск в уже существующих
							$found = false;
							foreach ($arResult["SESSION"][$type]["BY_PRIORITY_AREAS"] as $index => &$areaData)
							{
								if($areaData["NAME"] == $name)
								{
									$found = true;
									$areaData["ID"][] = $arUser["ID"];
									break;
								}
							}
							unset($areaData);

							if(!$found)
							{
								$arResult["SESSION"][$type]["BY_PRIORITY_AREAS"][] = array("NAME" => $name, "ID" => array($arUser["ID"]));
							}
						}
					}
				}


				//разделение по городам
				$found = false;
				if($sort = "BY_CITY"){
					foreach ($arResult["SESSION"][$type]["BY_CITY"] as &$cityData)
					{
						if($gCity == $cityData["NAME"])
						{
							$found = true;
							$cityData["ID"][] = $arUser["ID"];
							break;
						}
					}
					unset($cityData);
					if(!$found)
					{
						$arResult["SESSION"][$type]["BY_CITY"][] = array("NAME" => $gCity, "ID" => array($arUser["ID"]));
					}
				}


				//без разделения
				if($sort = "BY_ALL"){
					$arResult["SESSION"][$type]["BY_ALL"][] = $arUser["ID"];
				}


				//По алфавиту
				if($sort = "BY_ALL"){
					$firstLetter = strtoupper($gCompanyName{0});
					$found = false;


					if(is_numeric($firstLetter))
					{
						$arResult["SESSION"][$type]["BY_ALPHABET"][0]["ID"][] = $arUser["ID"];
						$found = true;
					}
					else
					{
						foreach($arResult["SESSION"][$type]["BY_ALPHABET"] as &$data)
						{

							if($data["NAME"] == $firstLetter)
							{
								$found = true;
								$data["ID"][] = $arUser["ID"];
								break;
							}
						}
						unset($data);
					}


					if(!$found)
					{
						$arResult["SESSION"][$type]["BY_ALPHABET"][] = array("NAME" => $firstLetter, "ID" => array($arUser["ID"]));
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
			}//по пользователям
		}//по типу (утро, вечер, хб)

	}

	if(in_array($sort, array("BY_CITY", "BY_PRIORITY_AREAS"))){
		usort($arResult["SESSION"][$arParams["TYPE"]][$sort], "cmp");
	}

	$rsItems = new CDBResult;

	$rsItems->InitFromArray($arResult["SESSION"][$arParams["TYPE"]][$sort]);
	$rsItems->NavStart(50);

	$arResult["NAVIGATE"] = $rsItems->GetPageNavStringEx($navComponentObject, "", "");
	$arResult["SESSION"][$arParams["TYPE"]][$sort] = array();

	while($arItems = $rsItems->Fetch())
	{
		$arResult["SESSION"][$arParams["TYPE"]][$sort][] = $arItems;
	}

	$this->SetResultCacheKeys(array(
			"USER_ID",
			"EXHIBITION",
			"SESSION"
	));


	$this->IncludeComponentTemplate();

}
else
{
	$this->AbortResultCache();
	ShowError("NotFound");
	@define("ERROR_404", "Y");
	if($arParams["SET_STATUS_404"]==="Y")
		CHTTP::SetStatus("404 Not Found");
}

function cmp($a, $b)
{
    if ($a["NAME"] == $b["NAME"]) {
        return 0;
    }
    return ($a["NAME"] < $b["NAME"]) ? -1 : 1;
}

?>