<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$data = &$arResult["POST_VALUES"];

if($data["EMAIL"] != $data["CONF_EMAIL"])
{
	$arResult["ERRORS"][] = GetMessage("R_B_EMAIL_NOT_EQUAL");
}

if($data["PASSWORD"] != $data["CONF_PASSWORD"])
{
	$arResult["ERRORS"][] = GetMessage("R_B_PASS_NOT_EQUAL");
}

if(empty($data["EXHIBITION"]))
{
	$arResult["ERRORS"][] = GetMessage("R_B_EMPTY_EXHIBITION");
}

$morning = array();
$evening = array();
$exhibitionID = "";


/*получаем выбор пользователя на утро или на вечер*/
foreach ($data["EXHIBITION"] as $exhibID => $arSelectedData)
{
	$exhibitionID = $exhibID;
	
	if(isset($arSelectedData["MORNING"]) && $arSelectedData["MORNING"])
	{
		$morning = array($arSelectedData["MORNING"]);
	}
	
	if(isset($arSelectedData["EVENING"]) && $arSelectedData["EVENING"])
	{
		$evening = array($arSelectedData["EVENING"]);
	}
}

$arExhibition = &$arResult["EXHIBITION"][$exhibitionID];

//получаем пароль и шифруем его для записи в пользовательское поле
$password = $data["PASSWORD"];
$passwordCoded = "";

if(strlen($password) <= 0) //если пароль не задан, генерируем его
{
	//Пароль рассчитан на 10! комбинаций / разных гостей
	$pasAr = array('d', 'p', '!', 'l', '9', '#', 'm', 'A', 'r', '2');
	shuffle($pasAr);
	$password = implode("", $pasAr);
}

//шифруем
$passwordCoded = makePassCode($password);

$login = $data["LOGIN"];
if(strlen($login) <= 0) //если логин не задан, генерируем его
{
	/*получаем транслит первого слова названия выставки*/
	$tok = strtok($arExhibition["CODE"], " -_");
	
	//добавляем первое слово с кода выставки, потом мыло, потом случайный набор цифр
	$loginAr = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
	shuffle($loginAr);
	$login = $tok.$data["EMAIL"].implode("", $loginAr);
}

$webSite = preg_replace("/https?:\/\//", "", $data["WEB_SITE"]);
if(strlen($webSite) > 0)
{
	$webSite = "http://". $webSite;
}

$arUserFormFields = array(
		"form_text_204" => $data["COMPANY_NAME"], 						//название компании
		"form_checkbox_SIMPLE_QUESTION_677" => $data["BUSINESS_TYPE"], 	//вид деятельности
		"form_text_208" => $data["COMPANY_ADDRESS"], 					//фактический адрес компании
		"form_text_209" => $data["INDEX"], 								//индекс
		"form_text_210" => $data["CITY"],								//город
		"form_dropdown_SIMPLE_QUESTION_678" => $data["COUNTRY"],		//страна
		"form_text_216" => $data["NAME"],								//имя
		"form_text_217" => $data["LAST_NAME"],							//фамилия
		"form_text_218" => $data["JOB_POST"],							//должность
		"form_text_219" => cutPhone($data["PHONE"]),					//телефон
		"form_text_1484" => $data["SKYPE"],								//скайп 
		"form_text_220" => $data["EMAIL"],								//email
		"form_text_221" => $data["CONF_EMAIL"],							//подтверждение email
		"form_text_1425" => cutPhone($data["MOBILE_PHONE"]),			//мобильный телефон
		"form_text_222" => $webSite,									//веб сайт
		"form_textarea_238" => $data["COMPANY_DESCRIPTION"],			//описание компании
		
		/*Приоритетные направления*/
		"form_checkbox_SIMPLE_QUESTION_383" => getPriorityAreas($data["NORTH_AMERICA"]), 	//North America
		"form_checkbox_SIMPLE_QUESTION_244" => getPriorityAreas($data["EUROPE"]), 			//Europe
		"form_checkbox_SIMPLE_QUESTION_212" => getPriorityAreas($data["SOUTH_AMERICA"]), 	//South America
		"form_checkbox_SIMPLE_QUESTION_497" => getPriorityAreas($data["AFRICA"]), 			//Africa
		"form_checkbox_SIMPLE_QUESTION_526" => getPriorityAreas($data["ASIA"]), 			//Asia
		"form_checkbox_SIMPLE_QUESTION_878" => getPriorityAreas($data["OCEANIA"]), 			//Oceania and Arctic and Antarctica
		
		/*логин пароль*/
		"form_text_235" => $login,										//Введите логин/гостевое имя
		"form_password_236" => $password,								//Введите пароль
		"form_password_237" => $password,								//Повторите пароль
		
		/*коллеги*/
		
		/*на утро*/
		"form_text_839" => $data["COLLEAGUE"]["MORNING"]["NAME"],		//Имя коллеги (на утро)
		"form_text_840" => $data["COLLEAGUE"]["MORNING"]["LAST_NAME"],	//Фамилия коллеги (на утро)
		"form_text_841" => $data["COLLEAGUE"]["MORNING"]["JOB_POST"],	//Должность коллеги (на утро)
		"form_text_842" => $data["COLLEAGUE"]["MORNING"]["EMAIL"],		//E-mail коллеги (на утро)
		
		/*на вечер 1*/
		"form_text_223" => $data["COLLEAGUE"][0]["NAME"],				//Имя коллеги 1
		"form_text_224" => $data["COLLEAGUE"][0]["LAST_NAME"],			//Фамилия коллеги 1
		"form_text_225" => $data["COLLEAGUE"][0]["JOB_POST"],			//Должность коллеги 1
		"form_text_226" => $data["COLLEAGUE"][0]["EMAIL"],				//E-mail коллеги 1
		
		/*на вечер 2*/
		"form_text_227" => $data["COLLEAGUE"][1]["NAME"],				//Имя коллеги 2
		"form_text_228" => $data["COLLEAGUE"][1]["LAST_NAME"],			//Фамилия коллеги 2
		"form_text_230" => $data["COLLEAGUE"][1]["JOB_POST"],			//Должность коллеги 2
		"form_text_229" => $data["COLLEAGUE"][1]["EMAIL"],				//E-mail коллеги 2
		
		/*на вечер 3*/
		"form_text_231" => $data["COLLEAGUE"][2]["NAME"],				//Имя коллеги 3
		"form_text_232" => $data["COLLEAGUE"][2]["LAST_NAME"],			//Фамилия коллеги 3
		"form_text_233" => $data["COLLEAGUE"][2]["JOB_POST"],			//Должность коллеги 3
		"form_text_234" => $data["COLLEAGUE"][2]["EMAIL"],				//E-mail коллеги 3
		
		/*утро или вечер*/
		"form_checkbox_SIMPLE_QUESTION_836" => $morning, 				//Утро
		"form_checkbox_SIMPLE_QUESTION_156" => $evening, 				//Вечер
);


// создадим новый результат
$RESULT_ID = CFormResult::Add($arParams["GUEST_FORM_ID"], $arUserFormFields);

/*Подготавливаем массив для создания пользователя*/

$bConfirmReq = COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y";

$arUserFields = array(
		"ACTIVE" => $bConfirmReq? "N": "Y",
		"CONFIRM_CODE" => $bConfirmReq? randString(8): "",
		"LID" => SITE_ID,
		"USER_IP" => $_SERVER["REMOTE_ADDR"],
		"USER_HOST" => @gethostbyaddr($REMOTE_ADDR),
		"LOGIN" => $login,
		"PASSWORD" => $password,
		"CONFIRM_PASSWORD" => $password,
		"NAME" => $data["NAME"],
		"LAST_NAME" => $data["LAST_NAME"],
		"EMAIL" => $data["EMAIL"],
		"MAIL" => $data["EMAIL"],
		"PERSONAL_PHONE" => cutPhone($data["PHONE"]),
		"PERSONAL_MOBILE" => cutPhone($data["MOBILE_PHONE"]),
		"WORK_COMPANY" => $data["COMPANY_NAME"],
		"WORK_POSITION" => $data["JOB_POST"],
		"UF_PAS" => $passwordCoded,
		"UF_FIO" => $data["NAME"]." ".$data["LAST_NAME"]
);

/*Добавляем стандартные группы при регистрации*/
$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");

if($def_group != "")
	$arUserFields["GROUP_ID"] = explode(",", $def_group);

/*Добавляем в группу неподтвержденных гостей выставки*/

$ucGuestGroupID = $arExhibition["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"];
if($ucGuestGroupID)
{
	$arUserFields["GROUP_ID"][] = $ucGuestGroupID;
}

/*Записываем результаты вебформы в свойства пользователя*/

$exhibPropName = CFormMatrix::getPropertyIDByExh($exhibitionID);
if($exhibPropName && $RESULT_ID)
{
	$arUserFields[$exhibPropName] = $RESULT_ID;
	$arUserFields["UF_ID_COMP"] = $RESULT_ID;
}

/*Запись гостя на утро или на вечер*/
//$arUserFields["UF_MR"] = (!empty($morning))?"Y":"N";
//$arUserFields["UF_EV"] = (!empty($evening))?"Y":"N";


/*отправляем в компонент*/
$arResult["VALUES"] = $arUserFields;
$bOk = true;

//pre("request\r\n", "f");
//pre($_REQUEST, "f");

/*
pre("arUserFormFields\r\n", "f");
pre($arUserFormFields, "f");
*/
/*
pre("arUserFields\r\n", "f");
pre($arResult["VALUES"], "f");
*/

global $USER_FIELD_MANAGER;
$USER_FIELD_MANAGER->EditFormAddFields("USER", $arResult["VALUES"]);

if ($bOk)
{
	$user = new CUser();
	$USER_ID = $user->Add($arResult["VALUES"]);
}

if (intval($USER_ID) > 0)
{
	$register_done = true;

	// authorize user
	if ($arParams["AUTH"] == "Y" && $arResult["VALUES"]["ACTIVE"] == "Y")
	{
		if (!$arAuthResult = $USER->Login($arResult["VALUES"]["LOGIN"], $arResult["VALUES"]["PASSWORD"]))
			$arResult["ERRORS"][] = $arAuthResult;
	}

	$arResult['VALUES']["USER_ID"] = $USER_ID;

	$arEventFields = $arResult['VALUES'];
	//unset($arEventFields["PASSWORD"]);
	unset($arEventFields["CONFIRM_PASSWORD"]);

	$event = new CEvent;
	$eventName = array();
	
	/*Выбор типа почтового собырия*/
	switch ($exhibitionID)
	{
		case "361" ://Москва, Россия. 13 марта 2014
			{
				if(!empty($morning))
				{
					$eventName["M"] = "REG_NEW_B_MOSSP_M";
				}
				if(!empty($evening))
				{
					$eventName["E"] = "REG_NEW_B_MOSSP_E";
				}
			}
		break;
		case "357" ://Баку, Азербайджан. 10 апреля 2014
			{
				if(!empty($morning))
				{
					$eventName["M"] = "REG_NEW_B_BAK_M";
				}
				if(!empty($evening))
				{
					$eventName["E"] = "REG_NEW_B_BAK_E";
				}
			}
		break;
		case "360" ://Киев, Украина. 23 сентября 2014	
			{
				if(!empty($morning))
				{
					$eventName["M"] = "REG_NEW_B_KIEV_M";
				}
				if(!empty($evening))
				{
					$eventName["E"] = "REG_NEW_B_KIEV_E";
				}
			}
		break;
		case "488" ://Москва, Россия. 12-13 марта 2015	
			{
				if(!empty($morning))
				{
					$eventName["M"] = "REG_NEW_B_MOSSP15_M";
				}
				if(!empty($evening))
				{
					$eventName["E"] = "REG_NEW_B_MOSSP15_E";
				}
			}
		break;
		case "358" ://Москва, Россия. 2 октября 2014	
			{
				if(!empty($morning))
				{
					$eventName["M"] = "REG_NEW_B_MOSOT_M";
				}
				if(!empty($evening))
				{
					$eventName["E"] = "REG_NEW_B_MOSOT_E";
				}
			}
		break;
		case "359" ://Алматы, Казахстан. 26 сентября 2014
			{
				if(!empty($morning))
				{
					$eventName["M"] = "REG_NEW_B_ALM_M";
				}
				if(!empty($evening))
				{
					$eventName["E"] = "REG_NEW_B_ALM_E";
				}
			}
		break;
		case "3522" ://Киев, Украина. 22 сентября 2015
		{
			if(!empty($morning))
			{
				$eventName["M"] = "REG_NEW_B_KIEV_M";
			}
			if(!empty($evening))
			{
				$eventName["E"] = "REG_NEW_B_KIEV_E";
			}
		}
		break;
		case "3523" ://Москва, Россия. 1 октября 2015
		{
			if(!empty($morning))
			{
				$eventName["M"] = "REG_NEW_B_MOSOT_M";
			}
			if(!empty($evening))
			{
				$eventName["E"] = "REG_NEW_B_MOSOT_E";
			}
		}
			break;
		case "3521" ://Алматы, Казахстан. 25 сентября 2015
		{
			if(!empty($morning))
			{
				$eventName["M"] = "REG_NEW_B_ALM_M";
			}
			if(!empty($evening))
			{
				$eventName["E"] = "REG_NEW_B_ALM_E";
			}
		}
			break;
	}

	foreach ($eventName as $when => $eventNameElem)
	{
		$event->SendImmediate($eventNameElem, SITE_ID, $arEventFields);
		if($arParams["COLLEAGUE_SEND_EMAIL"] == "Y")
		{
			if($when == "E")
			{
				$arEveningColleagueEventFields = array();
				foreach ($data["COLLEAGUE"] as $arColleague)
				{
					if(!empty($arColleague["EMAIL"]) && !empty($arColleague["NAME"]))
					{
						$arEveningColleagueEventFields[] = $arColleague;
						$arEveningColleagueEventFields["MAIL"] = $arColleague["EMAIL"];
					}
				}
				
				if(!empty($arEveningColleagueEventFields))
				{
					foreach ($arEveningColleagueEventFields as $arMailFields)
					{
						$event->SendImmediate($eventNameElem, SITE_ID, $arMailFields);
					}
				}
			}
			elseif($when == "M")
			{
				$arMailFields = array();
				if(!empty($data["COLLEAGUE"]["MORNING"]["EMAIL"]) && !empty($data["COLLEAGUE"]["MORNING"]["EMAIL"]))
				{
					$arMailFields = $data["COLLEAGUE"]["MORNING"];
					$arMailFields["MAIL"] = $data["COLLEAGUE"]["MORNING"]["EMAIL"];
				}
				if(!empty($arMailFields))
				{
					$event->SendImmediate($eventNameElem, SITE_ID, $arMailFields);
				}
			}
			
			
		}  
	}
	
	/*
	 
	$arEveningColleagueEventFields = array();
	foreach ($data["COLLEAGUE"] as $arColleague)
	{
		if(!empty($arColleague["EMAIL"]) && !empty($arColleague["NAME"]))
		{
			$arEveningColleagueEventFields[] = $arColleague;
			$arEveningColleagueEventFields["MAIL"] = $arColleague["EMAIL"];
		}
	}
	 
	foreach($arEveningColleagueEventFields as $arEveningEventFields) 
	{
		$event->SendImmediate("", SITE_ID, $arEveningEventFields);
	}
	
	*/
	
	if($bConfirmReq)
		$event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
	
	
	/*Отправка писем о регистрации*/
	
	/*
	$event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);
	if($bConfirmReq)
		$event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
	*/
}
else
{
	$arResult["ERRORS"][] = $user->LAST_ERROR;
}

if(count($arResult["ERRORS"]) <= 0)
{
	if(COption::GetOptionString("main", "event_log_register", "N") === "Y")
		CEventLog::Log("SECURITY", "USER_REGISTER", "main", $ID);
}
else
{
	if(COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
		CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", $ID, implode("<br>", $arResult["ERRORS"]));
}
?>