<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$data = &$arResult["POST_VALUES"];
$eventName = array();

if($data["EMAIL"] != $data["CONF_EMAIL"])
{
	$arResult["ERRORS"][] = GetMessage("R_E_EMAIL_NOT_EQUAL");
}

if(strlen(trim($data["COMPANY_NAME_FOR_INVOICE"])) <= 0)
{
	$data["COMPANY_NAME_FOR_INVOICE"] = $data["COMPANY_NAME"];
}

if(empty($data["EXHIBITION"]))
{
	$arResult["ERRORS"][] = GetMessage("R_E_EMPTY_EXHIBITION");
}

$arExhibitions = &$arResult["EXHIBITION"];

//получаем пароль и шифруем его для записи в пользовательское поле
$password = $data["PASSWORD"];
if(strlen($password) <= 0) //если пароль не задан, генерируем его
{
	//Пароль рассчитан на 10! комбинаций / разных гостей
	$pasAr = array('d', 'p', 'l', '9', 'K', 'm', 'A', 'r', '2');
	shuffle($pasAr);
	$password = trim(implode("", $pasAr));
}

//шифруем
$passwordCoded = makePassCode($password);
$login = $data["LOGIN"];

//получаем лототип
$logotip = array();
$arLogotip = array();
GetListFiles($uploaddir."logo",$logotip);
if(!empty($logotip))
{
	$logotip = reset($logotip);
	
	// уменьшение картинки для превью
	$rif = CFile::ResizeImageFile(
			$sourceFile = $logotip,
			$destinationFile =  $uploaddir."tmpimgfile.tmp",
			$arSize = array('width'=>100,'height'=>99999),
			$resizeType = BX_RESIZE_IMAGE_PROPORTIONAL
	);
	
	if ($rif) {//заменяем картинку
		unlink($logotip);
		rename($uploaddir."tmpimgfile.tmp", $logotip);
	}
	$arLogotip = CFile::MakeFileArray($logotip);
}


//получаем персональное фото
$personalPhoto = array();
$arPersonal = array();
GetListFiles($uploaddir."personal",$personalPhoto);

if(!empty($personalPhoto))
{
	$personalPhoto = reset($personalPhoto);
	$arPersonal = CFile::MakeFileArray($personalPhoto);
}

//Получаем фотографии компаний
$companyPhoto = array();
$arCompanyPhoto = array();
GetListFiles($uploaddir."photos",$companyPhoto);

//объект секций фоток
$obSection = new CIBlockSection;
$arFieldsSection = Array(
		"ACTIVE" => 'Y',
		"IBLOCK_ID" => $arParams["IBLOCK_PHOTO"],
		"NAME" => $data["COMPANY_NAME"],
);
$ID_PHOTO_SECTION = $obSection->Add($arFieldsSection);

if(!empty($companyPhoto) && count($companyPhoto) >= 6 && count($companyPhoto) <= 12)
{
	//объект элементов фоток
	$obIBElement = new CIBlockElement;

	foreach ($companyPhoto as $photoInd => $photoPath)
	{
		$arLoad = Array(
				"IBLOCK_SECTION_ID" => $ID_PHOTO_SECTION,
				"IBLOCK_ID"      => $arParams["IBLOCK_PHOTO"],
				"NAME"           => GetMessage("R_PHOTO", array("#PHOTO_NUM#"=> $photoInd+1)),
				"ACTIVE"         => "Y",
				"PREVIEW_PICTURE" => CFile::MakeFileArray($photoPath)
		);
		
		$obIBElement->Add($arLoad);
	}
}

$webSite = preg_replace("/https?:\/\//", "", $data["WEB_SITE"]);
if(strlen($webSite) > 0)
{
	$webSite = "http://". $webSite;
}

/*  Заполняем массив для вефбормы компании  */
$arCompanyFormFields = array(
		"form_text_30" => $data["COMPANY_NAME"], 							//Company or hotel name
		"form_text_29" => $data["COMPANY_NAME_FOR_INVOICE"], 				//Official name for invoice, if different
		"form_text_31" => $data["LOGIN"], 									//Your login
		"form_dropdown_SIMPLE_QUESTION_284" => $data["AREA_OF_BUSINESS"], 	//Area of the business
		"form_text_35" => $data["ADDRESS"],									//Official adress
		"form_text_36" => $data["CITY"],									//City
		"form_text_37" => $data["COUNTRY"],									//Country
		"form_text_38" => $webSite,											//веб сайт
		"form_textarea_39" => $data["COMPANY_DESCRIPTION"],					//фамилия


		/*Приоритетные направления*/
		"form_checkbox_SIMPLE_QUESTION_876" => getPriorityAreas($data["NORTH_AMERICA"]), 	//North America
		"form_checkbox_SIMPLE_QUESTION_367" => getPriorityAreas($data["EUROPE"]), 			//Europe
		"form_checkbox_SIMPLE_QUESTION_328" => getPriorityAreas($data["SOUTH_AMERICA"]), 	//South America
		"form_checkbox_SIMPLE_QUESTION_459" => getPriorityAreas($data["AFRICA"]), 			//Africa
		"form_checkbox_SIMPLE_QUESTION_931" => getPriorityAreas($data["ASIA"]), 			//Asia
		"form_checkbox_SIMPLE_QUESTION_445" => getPriorityAreas($data["OCEANIA"]), 			//Oceania and Arctic and Antarctica

		"form_image_193" => $arLogotip										//Logo
);


// создадим новый результат
$COMPANY_RESULT_ID = CFormResult::Add($arParams["COMPANY_FORM_ID"], $arCompanyFormFields);

/*Заполняем массив для вебформы участника*/
$arPersonalFormFields = array(
		"form_text_84" => $data["NAME"], 									//Participant first name
		"form_text_85" => $data["LAST_NAME"], 								//Participant last name
		"form_text_87" => $data["JOB_POST"], 								//Job title
		"form_text_88" => cutPhone($data["PHONE"]), 						//Telephone
		"form_text_1474" => $data["SKYPE"], 								//Skype 
		"form_text_89" => $data["EMAIL"],									//E-mail
		"form_text_90" => $data["CONF_EMAIL"],								//Please confirm your e-mail
		"form_text_91" => $data["ALT_EMAIL"],								//Alternative e-mail
		"form_dropdown_SIMPLE_QUESTION_889" => $data["SALUTATION"],			//Salutation
		"form_image_195" => $arPersonal,									//Персональное фото
);

// создадим новый результат
$PERSONAL_RESULT_ID = CFormResult::Add($arParams["PARTICIPANT_FORM_ID"], $arPersonalFormFields);

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
		"WORK_COMPANY" => $data["COMPANY_NAME"],
		"COMP_NAME" => $data["COMPANY_NAME"],
		"UF_PAS" => $passwordCoded,
		"UF_FIO" => $data["NAME"]." ".$data["LAST_NAME"]
);

/*Добавляем стандартные группы при регистрации*/
$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");

if($def_group != "")
	$arUserFields["GROUP_ID"] = explode(",", $def_group);


/*Добавляем в группу неподтвержденных Участников выставки*/

foreach ($data["EXHIBITION"] as $exhId)
{
	/*добавление групп пользователю*/
	$ucParticipantGroupID = $arExhibitions[$exhId]["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];
	if($ucParticipantGroupID)
	{
		$arUserFields["GROUP_ID"][] = $ucParticipantGroupID;
	}
	
	/*Добавляем тип почтовых событий для отправки писем*/
	$eventName[] = [
		"EVENT" => $arExhibitions[$exhId]["PROPERTIES"]["EVENT_REG_PARTICIPANT"]["VALUE"],
		"EXIB" => [
			"EXIB_NAME_RU" => $arExhibitions[$exhId]["NAME"],
			"EXIB_NAME_EN" => $arExhibitions[$exhId]["PROPERTIES"]["NAME_EN"]["VALUE"],
			"EXIB_SHORT_RU" => $arExhibitions[$exhId]["PROPERTIES"]["V_RU"]["VALUE"],
			"EXIB_SHORT_EN" => $arExhibitions[$exhId]["PROPERTIES"]["V_EN"]["VALUE"],
			"EXIB_DATE" => $arExhibitions[$exhId]["PROPERTIES"]["DATE"]["VALUE"],
			"EXIB_PLACE" => $arExhibitions[$exhId]["PROPERTIES"]["VENUE"]["VALUE"],
		]
	];
}

/*Записываем результаты вебформы в свойства пользователя*/
if($COMPANY_RESULT_ID && $PERSONAL_RESULT_ID)
{
	$arUserFields["UF_ID"] = $PERSONAL_RESULT_ID;
	$arUserFields["UF_ID_COMP"] = $COMPANY_RESULT_ID;
}


/*добавляем ИД фотогалереи*/
if($ID_PHOTO_SECTION)
{
	$arUserFields["UF_ID_GROUP"] = $ID_PHOTO_SECTION;
}

$arResult["VALUES"] = $arUserFields;
$bOk = true;


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

	foreach ($eventName as $eventInfo)
	{
		$fullEventFields = array_merge($arEventFields, $eventInfo["EXIB"]);
		$event->SendImmediate($eventInfo["EVENT"], SITE_ID, $fullEventFields);
	}


	if($bConfirmReq)
		$event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);


	/*Отправка писем о регистрации*/

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