<?
try{

	global $USER;
	if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
		$userId = intval($_REQUEST["UID"]);
	} else {
		$userId = $USER->GetID();
	}

	$rsUser = CUser::GetList(($by = false), ($order = false), array("ID"=>$userId), array("SELECT"=>array("UF_*")));
	$arUser = $rsUser->Fetch();
	$resultId = $arUser["UF_ID_COMP"];
	$curPage = "/cabinet/".$_REQUEST["EXHIBIT_CODE"]."/edit/profile/".(isset($_REQUEST["UID"])?"?UID={$_REQUEST["UID"]}":"");
	CModule::IncludeModule("form");

	//получение id выставки
	$exhCode = trim($_REQUEST["EXHIBIT_CODE"]);
	if($exhCode && CModule::IncludeModule("iblock"))
	{
		$rsExhib = CIBlockElement::GetList(array("sort" => 'asc'), array("ACTVE" => "Y", "CODE" => $exhCode), false, false, array("ID", "CODE", "NAME", "PROPERTY_SHORT_NAME", "PROPERTY_DATE", "PROPERTY_PARTICIPANT_EDIT", "PROPERTY_GUEST_EDIT"));
		if($arExhib = $rsExhib->Fetch())
		{
			$formID = CFormMatrix::getPFormIDByExh($arExhib["ID"]);
			$formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);//получение имени свойства пользователя для текущей выставки
			$resultId = $arUser[$formPropName];
			$exhName = $arExhib["PROPERTY_SHORT_NAME_VALUE"];
			$exhDate = $arExhib["PROPERTY_DATE_VALUE"];
			$exhParticipantEdit = $arExhib["PROPERTY_PARTICIPANT_EDIT_VALUE"];
			$exhGuestEdit = $arExhib["PROPERTY_GUEST_EDIT_VALUE"];

		}
	}

	/*
	 		84,//Participant first name
			85,//Participant last name
			87,//Job title
			88,//Telephone
			89,//E-mail
			90,//Please confirm your e-mail
			91,//Alternative e-mail
			195,//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
	 */

	//сохранение имени, фамилии и фио в поля пользователя
	$fieldName = "form_text_" . CFormMatrix::getAnswerRelBase(84, $formID); //Participant first name
	$fieldLastName = "form_text_" . CFormMatrix::getAnswerRelBase(85, $formID); //Participant last name
	$fieldEmail =  "form_text_" . CFormMatrix::getAnswerRelBase(89, $formID); //Email
	$fieldJobTitle = "form_text_" . CFormMatrix::getAnswerRelBase(87, $formID);//Job title
	$fieldPhone = "form_text_" . CFormMatrix::getAnswerRelBase(88, $formID);//Telephone
	$fieldEmailAlt = "form_text_" . CFormMatrix::getAnswerRelBase(91, $formID);//Alternative e-mail
	$fieldSalutation = "form_dropdown_" . CFormMatrix::getAnswerRelBase("SIMPLE_QUESTION_889", $formID);//Salutation
	$fieldSkype = "form_text_" . CFormMatrix::getAnswerRelBase(1474, $formID);//Skype

	//получение приветствия из базы
	$rsSalutation = CFormAnswer::GetByID($_REQUEST[$fieldSalutation]);
	$fieldSalutation = $rsSalutation->Fetch();
	$fieldSalutation = $fieldSalutation["MESSAGE"];


	$FieldSID = array(
	    "NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_446",$formID),//Participant first name
	    "LAST_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_551",$formID),//Participant last name
	    "JOB_TITLE" =>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_729",$formID),//Job title
	    "PHONE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_394",$formID),//Telephone
	    "SKYPE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_211",$formID),//Skype
	    "EMAIL" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859",$formID),//E-mail
	    "EMAIL_CONF" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_585",$formID),//Please confirm your e-mail
	    "EMAIL_ALT" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_749",$formID),//Alternative e-mail
	    "PHOTO" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575",$formID),//Персональное фото
	    "SALUTATION" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_889",$formID),//Salutation
	);


	//тут запрещается редактирование

	if("Y" != $exhParticipantEdit)
	{
	    echo "<p style='color:red;'>Data editing is blocked by the administrator!</p>";

	    if("POST" == $_SERVER["REQUEST_METHOD"])
	    {
	        unset($_REQUEST["web_form_submit"]);
	        unset($_REQUEST["web_form_apply"]);

	        unset($_POST["web_form_submit"]);
	        unset($_POST["web_form_apply"]);
	    }
	}

	if($userId && "Y" == $exhParticipantEdit &&
	    $_SERVER["REQUEST_METHOD"] == "POST" &&
	    //isset($_REQUEST["RESULT_ID"]) &&
		//$_REQUEST["RESULT_ID"] == $resultId	&&
		isset($_REQUEST[$fieldName]) &&
		isset($_REQUEST[$fieldLastName]) &&
	    isset($_REQUEST[$fieldEmail])
	) {
		$obUser = new CUser;
		$obUser->Update($userId, array(
				"NAME"=>$_REQUEST[$fieldName],
				"LAST_NAME"=>$_REQUEST[$fieldLastName],
				"UF_FIO"=>$_REQUEST[$fieldName] . " " . $_REQUEST[$fieldLastName],
		        "EMAIL" => $_REQUEST[$fieldEmail]
		    )
		);


		//получение старых полей формы

		//получение старого описания компании

		/*
		 	"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
		 */




		$arAnswer = CFormResult::GetDataByID(
			$resultId,
			array(
				$FieldSID["NAME"],
				$FieldSID["LAST_NAME"],
				$FieldSID["JOB_TITLE"],
				$FieldSID["PHONE"],
				$FieldSID["SKYPE"],
				$FieldSID["EMAIL"],
				$FieldSID["EMAIL_CONF"],
				$FieldSID["EMAIL_ALT"],
				$FieldSID["PHOTO"],
				$FieldSID["SALUTATION"],
			),
			$arResult,
			$arAnswerSID);


		$oldFields = array();

		foreach ($FieldSID as $name => $sid)
		{
			if(isset($arAnswerSID[$sid]))
			{
			    $resName = "";
			    $tmp = reset($arAnswerSID[$sid]);
			    switch ($tmp["FIELD_TYPE"])
			    {
			    	case "dropdown" : $resName = "ANSWER_TEXT";break;
			    	case "image" : $resName = "USER_FILE_ID"; break;
			    	case "text" : $resName = "USER_TEXT"; break;
			    }

			    ;
			    $oldFields[$name . "_OLD"] = $tmp[$resName];
			}
		}


		$arSendFields = array(
			"NAME_OLD" => $oldFields["NAME_OLD"],
		    "LAST_NAME_OLD" => $oldFields["LAST_NAME_OLD"],
		    "JOB_TITLE_OLD" => $oldFields["JOB_TITLE_OLD"],
		    "PHONE_OLD" => $oldFields["PHONE_OLD"],
			"SKYPE_OLD" => $oldFields["SKYPE_OLD"],
		    "EMAIL_OLD" => $oldFields["EMAIL_OLD"],
		    "EMAIL_ALT_OLD" => $oldFields["EMAIL_ALT_OLD"],
		    "SALUTATION_OLD" => $oldFields["SALUTATION_OLD"],

		    "NAME_NEW" => $_REQUEST[$fieldName],
		    "LAST_NAME_NEW" => $_REQUEST[$fieldLastName],
		    "JOB_TITLE_NEW" => $_REQUEST[$fieldJobTitle],
		    "PHONE_NEW" => $_REQUEST[$fieldPhone],
			"SKYPE_NEW" => $_REQUEST[$fieldSkype],
		    "EMAIL_NEW" => $_REQUEST[$fieldEmail],
		    "EMAIL_ALT_NEW" => $_REQUEST[$fieldEmailAlt],
		    "SALUTATION_NEW" => $fieldSalutation,

		    "USER_ID" => $userId,
		    "COMPANY_NAME" => $arUser["WORK_COMPANY"]
		);

		CEvent::Send("PARTICIPANT_PROFILE_CHANGE", "s1", $arSendFields);
	}//пост


    if(isset($_REQUEST["formresult"]) && $_REQUEST["formresult"] == "editok")
    {
        //вывод информации об успешном сохранении
        echo "<p style='color:green;'>Your information has been updated. Thank you!</p>";
    }

	$APPLICATION->IncludeComponent(
	"bitrix:form.result.edit",
	"participant_profile",
	Array(
		"SEF_MODE" => "N",
		"RESULT_ID" => $resultId,
		"EDIT_ADDITIONAL" => "Y",
		"EDIT_STATUS" => "Y",
		"LIST_URL" => $curPage,
		"VIEW_URL" => $curPage,
		"CHAIN_ITEM_TEXT" => $curPage,
		"CHAIN_ITEM_LINK" => $curPage,
		"IGNORE_CUSTOM_TEMPLATE" => "Y",
		"USE_EXTENDED_ERRORS" => "N",
		"QUESTION_TO_SHOW" => array(
			array("NAME"=>"PARTICIPANT at " .$exhName . " " .$exhDate , "ITEMS"=>array(
				array("ID"=>$FieldSID["PHOTO"], "IS_PIC"=>true),//Персональное фото
                array("ID"=>$FieldSID["NAME"], "TITLE" => "First name"),//Participant first name
                array("ID"=>$FieldSID["LAST_NAME"], "TITLE" => "Last name"),//Participant last name
                array("ID"=>$FieldSID["SALUTATION"], "TITLE" => "Title"),////Salutation
                array("ID"=>$FieldSID["JOB_TITLE"], "TITLE" => "Job title"),//Job title
                array("ID"=>$FieldSID["PHONE"], "TITLE" => "Telephone"),//Telephone
				array("ID"=>$FieldSID["SKYPE"], "TITLE" => "Skype"),//Skype
                array("ID"=>$FieldSID["EMAIL"], "TITLE" => "E-mail"),//E-mail
                array("ID"=>$FieldSID["EMAIL_ALT"], "TITLE" => "Alternative e-mail"),//Alternative e-mail
			    )
			)
		),
	    "EDITING" => $exhParticipantEdit

	)
);?>

<?}catch(Exception $e){}?>