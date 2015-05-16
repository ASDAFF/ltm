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

	//тут запрещается редактирование
	if("Y" != $exhGuestEdit)
	{
	    echo "<p style='color:red;'>Редактирование закрыто администратором!</p>";

	    if("POST" == $_SERVER["REQUEST_METHOD"])
	    {
	        unset($_REQUEST["web_form_submit"]);
	        unset($_REQUEST["web_form_apply"]);

	        unset($_POST["web_form_submit"]);
	        unset($_POST["web_form_apply"]);
	    }
	}

	//сохранение имени, фамилии и фио в поля пользователя
	$fieldNameName = "form_text_216"; //имя
	$fieldLastNameName = "form_text_217";//фамилия
	$fieldEmeil =  "form_text_220"; //мыло

	if($userId && "Y" == $exhGuestEdit &&
	    $_SERVER["REQUEST_METHOD"] == "POST" &&
	    //isset($_REQUEST["RESULT_ID"]) &&
		//$_REQUEST["RESULT_ID"] == $resultId	&&
		isset($_REQUEST[$fieldNameName]) &&
		isset($_REQUEST[$fieldLastNameName]) &&
	    isset($_REQUEST[$fieldEmeil])
	) {
		$obUser = new CUser;
		$obUser->Update($userId, array(
				"NAME"=>$_REQUEST[$fieldNameName],
				"LAST_NAME"=>$_REQUEST[$fieldLastNameName],
				"UF_FIO"=>$_REQUEST[$fieldNameName] . " " . $_REQUEST[$fieldLastNameName],
		        "EMAIL" => $_REQUEST[$fieldEmeil]
		    )
		);
	}


	if(isset($_REQUEST["formresult"]) && $_REQUEST["formresult"] == "editok")
	{
	    //вывод информации об успешном сохранении
	    echo "<p style='color:red;'>Внесенные изменения сохранены</p>";
	}

	$APPLICATION->IncludeComponent(
	"bitrix:form.result.edit",
	"guest_collegue",
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
			array("ITEMS"=>array(
				array("ID"=>"SIMPLE_QUESTION_269", "IS_PIC"=>true),
				array("ID"=>"SIMPLE_QUESTION_750"),
				array("ID"=>"SIMPLE_QUESTION_823"),
				array("ID"=>"SIMPLE_QUESTION_115", "DISABLED"=>true),
				array("ID"=>"SIMPLE_QUESTION_391"),
				array("ID"=>"SIMPLE_QUESTION_773", "TITLE"=>"Адрес"),
				array("ID"=>"SIMPLE_QUESTION_672"),
				array("ID"=>"SIMPLE_QUESTION_678"),
				array("ID"=>"SIMPLE_QUESTION_756"),
				array("ID"=>"SIMPLE_QUESTION_636"),
				array("ID"=>"SIMPLE_QUESTION_373"),
				array("ID"=>"SIMPLE_QUESTION_552", "TITLE"=>"Web-сайт"),
				array("ID"=>"SIMPLE_QUESTION_166", "TITLE"=>"Описание компании"),
				array("ID"=>"SIMPLE_QUESTION_383"),
				array("ID"=>"SIMPLE_QUESTION_244"),
				array("ID"=>"SIMPLE_QUESTION_212"),
				array("ID"=>"SIMPLE_QUESTION_497"),
				array("ID"=>"SIMPLE_QUESTION_526"),
				array("ID"=>"SIMPLE_QUESTION_878"),
			))),
	    "EDITING" => $exhGuestEdit
		)
);?>
<?}catch(Exception $e){}?>