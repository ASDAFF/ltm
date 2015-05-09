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


	//получение id выставки
	$exhCode = trim($_REQUEST["EXHIBIT_CODE"]);
	if($exhCode && CModule::IncludeModule("iblock"))
	{
		$rsExhib = CIBlockElement::GetList(array("sort" => 'asc'), array("ACTVE" => "Y", "CODE" => $exhCode), false, false, array("ID", "CODE", "NAME", "PROPERTY_SHORT_NAME", "PROPERTY_DATE",  "PROPERTY_PARTICIPANT_EDIT", "PROPERTY_GUEST_EDIT"));
		if($arExhib = $rsExhib->Fetch())
		{
			$formID = CFormMatrix::getPFormIDByExh($arExhib["ID"]);
			$formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"], 1);//получение имени свойства коллеги для текущей выставки

			$resultId = $arUser[$formPropName];

			$exhName = $arExhib["PROPERTY_SHORT_NAME_VALUE"];
			$exhDate = $arExhib["PROPERTY_DATE_VALUE"];

			$exhParticipantEdit = $arExhib["PROPERTY_PARTICIPANT_EDIT_VALUE"];
			$exhGuestEdit = $arExhib["PROPERTY_GUEST_EDIT_VALUE"];
		}
	}

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

	if(isset($_REQUEST["formresult"]) && $_REQUEST["formresult"] == "editok")
	{
	    //вывод информации об успешном сохранении
	    echo "<p style='color:red;'>Your information has been updated. Thank you!</p>";
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
			array("NAME"=>"COLLEAGUE at " .$exhName . " " .$exhDate , "ITEMS"=>array(
				array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575", $formID), "IS_PIC"=>true),//Персональное фото
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_446", $formID), "TITLE" => "First name"),//Participant first name
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_551", $formID), "TITLE" => "Last name"),//Participant last name
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_889", $formID), "TITLE" => "Title"),////Salutation
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_729", $formID), "TITLE" => "Job title"),//Job title
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_394", $formID), "TITLE" => "Telephone"),//Telephone
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859", $formID), "TITLE" => "E-mail"),//E-mail
                array("ID"=>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_749", $formID), "TITLE" => "Alternative e-mail"),//Alternative e-mail

			))),
	    "EDITING" => $exhParticipantEdit,
		"EMAIL_SID" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859", $formID),
		"CONF_EMAIL_SID" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_585", $formID)
		)
);?>

<?}catch(Exception $e){}?>