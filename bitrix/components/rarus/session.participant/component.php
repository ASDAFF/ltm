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

$arResult = array();

if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
	$arResult["USER_ID"] = intval($_REQUEST["UID"]);
} else {
	$arResult["USER_ID"] = $USER->GetID();
}

//подключение модуля встреч
use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

if($this->StartResultCache(false, array_merge($arParams, $arResult)))
{
	if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")  || !CModule::IncludeModule("doka.meetings"))
	{
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock form");
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
		
		if(isset($arParams["TYPE"]) && $arParams["TYPE"] == 'HB'){
			$appId = $arResult["EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
			$arResult["USER_TYPE"] = "HB";
		}
		else{
			$appId = $arResult["EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
			$arResult["USER_TYPE"] = "";
		}

		$arResult["APP_ID"] = $appId;

		$req_obj = new DokaRequest($appId);
		$arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

		//получение списка подтвержденных Участников на данную выставку

		$cParticipantGroup = $arResult["EXHIBITION"]["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];

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
		$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"], 0);//свойство участника
		$propertyNameColleague = CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"], 1);//свойство коллеги

		$rsUsers = $USER->GetList(($by="work_company"), ($order="asc"), $arFilter, $arParamsUser);
		while($arUser = $rsUsers->Fetch())
		{

			$arResultId[] = $arUser[$propertyNameParticipant];//дописываем id результата заполнения формы
			$arResultId[] = $arUser[$propertyNameColleague];
			$arResultAllFormId[] = $arUser["UF_ID_COMP"];

			$arResult["SESSION"]["USERS"][$arUser["ID"]] = $arUser;

		}

		//получение результатов заполнения формы регистрациия для пользователей

		$formId = CFormMatrix::getPFormIDByExh($arResult["EXHIBITION"]["ID"]);

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

		//pre($arQuestionsCompany);
		$allTimeSlot = $req_obj->getAllMeetTimeslots();
		$arResult["SESSION"]["BY_SLOTS"] = $allTimeSlot;//список всех таймслотов

		foreach ($arResult["SESSION"]["USERS"] as &$arUser)
		{
		    $times =  $req_obj->getSortedFreeTimesAppoint($arUser["ID"]);//свободные таймслоты пользователя

		    $userTimeSlot = array();
		    foreach ($times as $time)
		    {
		        $userTimeSlot[$time["id"]] = $time["name"];
		        $arResult["SESSION"]["BY_SLOTS"][$time["id"]]["ID"][] = $arUser["ID"];
		    }

			$resultIdUser = $arUser[$propertyNameParticipant];
			$resultIdColleague = $arUser[$propertyNameColleague];
			$resultIdCompany = $arUser["UF_ID_COMP"];


			if(!isset($arResult["SESSION"]["BY_ALPHABET"][0]))
			{
				$arResult["SESSION"]["BY_ALPHABET"][0] = array("NAME" => "NUM", "ID" => array());
			}


			$fieldId = CFormMatrix::getQIDByBase(32 , $formId);
			$answerID = CFormMatrix::getAnswerRelBase(84 ,$formId);//Participant first name

			//участник
			$pName = trim($arAnswersUser[$resultIdUser][$fieldId][$answerID]["USER_TEXT"]);
			//коллега
			$cName = trim($arAnswersUser[$resultIdColleague][$fieldId][$answerID]["USER_TEXT"]);


			$fieldId = CFormMatrix::getQIDByBase(33 , $formId);
			$answerID = CFormMatrix::getAnswerRelBase(85 ,$formId);//Participant last name

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


			$arPriorArea = array();

			//North America
			$arPriorArea[25]["NAME"] = trim($arQuestionsCompany[25]["TITLE"]);
			foreach ($arAnswersCompany[$resultIdCompany][25] as $country)
			{
				$arPriorArea[25]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
			}

			//pre();

			//Europe
			$arPriorArea[26]["NAME"] = trim($arQuestionsCompany[26]["TITLE"]);
			foreach ($arAnswersCompany[$resultIdCompany][26] as $country)
			{
				$arPriorArea[26]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
			}

			//South America
			$arPriorArea[27]["NAME"] = trim($arQuestionsCompany[27]["TITLE"]);
			foreach ($arAnswersCompany[$resultIdCompany][27] as $country)
			{
				$arPriorArea[27]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
			}

			//Africa
			$arPriorArea[28]["NAME"] = trim($arQuestionsCompany[28]["TITLE"]);
			foreach ($arAnswersCompany[$resultIdCompany][28] as $country)
			{
				$arPriorArea[28]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
			}

			//Asia
			$arPriorArea[29]["NAME"] = trim($arQuestionsCompany[29]["TITLE"]);
			foreach ($arAnswersCompany[$resultIdCompany][29] as $country)
			{
				$arPriorArea[29]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
			}

			//Oceania
			$arPriorArea[30]["NAME"] = trim($arQuestionsCompany[30]["TITLE"]);
			foreach ($arAnswersCompany[$resultIdCompany][30] as $country)
			{
				$arPriorArea[30]["ITEMS"][$country["ANSWER_ID"]] = trim($country["ANSWER_TEXT"]);
			}

			//Вид деятельности
			$arBusinessType = array();
			foreach ($arAnswersCompany[$resultIdCompany][19] as $BusinessType)
			{
				$arBusinessType[$BusinessType["ANSWER_ID"]] = trim($BusinessType["ANSWER_TEXT"]);
			}


			/*
			//рарзделение по приоритетным направлениям и по городам в них
			foreach ($arPriorArea as $id=>$area)
			{
				$arResult["SESSION"]["BY_PRIORITY_AREAS"][$id]["NAME"] = $area["NAME"];

				foreach ($area["ITEMS"] as $answerID => $name)
				{
					//поиск в уже существующих
					$found = false;
					foreach ($arResult["SESSION"]["BY_PRIORITY_AREAS"][$id]['ITEMS'] as &$areaData)
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
						$arResult["SESSION"]["BY_PRIORITY_AREAS"][$id]['ITEMS'][] = array("NAME" => $name, "ID" => array($arUser["ID"]));
					}
				}
			}
			*/


			//рарзделение по городам в приоритетных направлениях
			foreach ($arPriorArea as $id=>$area)
			{
				foreach ($area["ITEMS"] as $answerID => $name)
				{
					//поиск в уже существующих
					$found = false;
					foreach ($arResult["SESSION"]["BY_PRIORITY_AREAS"] as $index => &$areaData)
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
						$arResult["SESSION"]["BY_PRIORITY_AREAS"][] = array("NAME" => $name, "ID" => array($arUser["ID"]));
					}
				}
			}

			//разделение по городам
			$found = false;
			foreach ($arResult["SESSION"]["BY_CITY"] as &$cityData)
			{
				if($pCity == $cityData["NAME"])
				{
					$found = true;
					$cityData["ID"][] = $arUser["ID"];
					break;
				}
			}
			unset($cityData);
			if(!$found)
			{
				$arResult["SESSION"]["BY_CITY"][] = array("NAME" => $pCity, "ID" => array($arUser["ID"]));
			}

			//без разделения
			$arResult["SESSION"]["BY_ALL"][] = $arUser["ID"];

			//По алфавиту
			$firstLetter = strtoupper($pCompanyName{0});
			$found = false;


			if(is_numeric($firstLetter))
			{
				$arResult["SESSION"]["BY_ALPHABET"][0]["ID"][] = $arUser["ID"];
				$found = true;
			}
			else
			{
				foreach($arResult["SESSION"]["BY_ALPHABET"] as &$data)
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
				$arResult["SESSION"]["BY_ALPHABET"][] = array("NAME" => $firstLetter, "ID" => array($arUser["ID"]));
			}

			//разделение по виду деятельности
			foreach ($arBusinessType as $answerID => $name)
			{
				//поиск в уже существующих
				$found = false;
				foreach ($arResult["SESSION"]["BY_BUSINESS"] as $index => &$businessData)
				{
					if($businessData["NAME"] == $name)
					{
						$found = true;
						$businessData["ID"][] = $arUser["ID"];
						break;
					}
				}
				unset($areaData);

				if(!$found)
				{
					$arResult["SESSION"]["BY_BUSINESS"][] = array("NAME" => $name, "ID" => array($arUser["ID"]));
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
		}//по пользователям
	}

	usort($arResult["SESSION"]["BY_CITY"], "cmp");
	usort($arResult["SESSION"]["BY_BUSINESS"], "cmp");	
	usort($arResult["SESSION"]["BY_PRIORITY_AREAS"], "cmp");

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