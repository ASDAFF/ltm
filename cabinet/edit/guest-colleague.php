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
	$curPage = "/cabinet/".$_REQUEST["EXHIBIT_CODE"]."/edit/colleague/".(isset($_REQUEST["UID"])?"?UID={$_REQUEST["UID"]}":"");

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


	$arQuestionToShow = array();
	if(isset($arUser["UF_MR"]) && $arUser["UF_MR"]) {
		$arQuestionToShow[] = array("NAME"=>"Коллега на утреннюю сессию", "ITEMS"=>array(
				array("ID"=>"SIMPLE_QUESTION_873", "IS_PIC"=>true),
				array("ID"=>"SIMPLE_QUESTION_816", "TITLE"=>"Имя"),
				array("ID"=>"SIMPLE_QUESTION_596", "TITLE"=>"Фамилия"),
				array("ID"=>"SIMPLE_QUESTION_304", "TITLE"=>"Должность"),
				array("ID"=>"SIMPLE_QUESTION_278", "TITLE"=>"E-mail")));
	}

	if(isset($arUser["UF_EV"]) && $arUser["UF_EV"]) {
		$arQuestionToShow[] = array("NAME"=>"Коллега на вечернюю сессию", "ITEMS"=>array(
				array("ID"=>"SIMPLE_QUESTION_367", "TITLE"=>"Имя"),
				array("ID"=>"SIMPLE_QUESTION_482", "TITLE"=>"Фамилия"),
				array("ID"=>"SIMPLE_QUESTION_187", "TITLE"=>"Должность"),
				array("ID"=>"SIMPLE_QUESTION_421", "TITLE"=>"E-mail")));
		$arQuestionToShow[] = array("NAME"=>"Коллега на вечернюю сессию", "ITEMS"=>array(
				array("ID"=>"SIMPLE_QUESTION_225", "TITLE"=>"Имя"),
				array("ID"=>"SIMPLE_QUESTION_770", "TITLE"=>"Фамилия"),
				array("ID"=>"SIMPLE_QUESTION_280", "TITLE"=>"Должность"),
				array("ID"=>"SIMPLE_QUESTION_384", "TITLE"=>"E-mail")));
		$arQuestionToShow[] = array("NAME"=>"Коллега на вечернюю сессию", "ITEMS"=>array(
				array("ID"=>"SIMPLE_QUESTION_765", "TITLE"=>"Имя"),
				array("ID"=>"SIMPLE_QUESTION_627", "TITLE"=>"Фамилия"),
				array("ID"=>"SIMPLE_QUESTION_788", "TITLE"=>"Должность"),
				array("ID"=>"SIMPLE_QUESTION_230", "TITLE"=>"E-mail")));
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
		"QUESTION_TO_SHOW" => $arQuestionToShow,
	    "EDITING" => $exhGuestEdit
	)
);?>

	<div class="exhibition-session">
	    <div class="signature">
    		<b>Если кто-то из ваших коллег хочет отдельные от вас встречи, то ему необходимо пройти процесс регистрации.</b><br>
    		При загрузке фотографий учитывайте, что файлы должны быть не более 2мб и представлять лицо участника крупным планом или логотип компании.
		</div>
	</div>
<?}catch(Exception $e){}?>