<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
try {
	if(CModule::IncludeModule('iblock') && CModule::IncludeModule('form')) {
		//подтверждение участников
		$arUserChanges = array("UF_MR", "UF_EV", "UF_HB");
		$unconfirmmedGuestGroupId = 19;

		$rs = CIBlockElement::GetList(array(),
				array("ID"=>intval($_REQUEST["EXHIB_ID"])), false, array("nTopCount"=>1),
				array("ID", "IBLOCK_ID", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP"));
		if($arExhib = $rs->Fetch()) {
			if(isset($arExhib["PROPERTY_UC_GUESTS_GROUP_VALUE"]) && $arExhib["PROPERTY_UC_GUESTS_GROUP_VALUE"]) {
				$unconfirmmedGuestGroupId = $arExhib["PROPERTY_UC_GUESTS_GROUP_VALUE"];
			}

			if(isset($arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"]) && $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"]) {
				$confirmmedGuestGroupId = $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];
			}
		}

		if(isset($_REQUEST["USER_ID"])) {
			confirmUser(intval($_REQUEST["USER_ID"]), $arUserChanges, $unconfirmmedGuestGroupId, $confirmmedGuestGroupId, $arExhib);
		} elseif(isset($_REQUEST["SELECTED_USERS"])) {
			foreach($_REQUEST["SELECTED_USERS"] as $userId) {
				confirmUser($userId, $arUserChanges, $unconfirmmedGuestGroupId, $confirmmedGuestGroupId, $arExhib);
			}
		}
	} else {
		throw new Exception("can't load iblock");
	}
}catch(Exception $e) {
	header( 'HTTP/1.1 400 BAD REQUEST' );
	die($e->GetMessage());
}

function confirmUser($userId, $arUserChanges, $unconfirmmedGuestGroupId, $confirmmedGuestGroupId, $arExhib) {
	$arUserFields = array();

	foreach($arUserChanges as $userFieldName) {
		$requestId = "CONFIRM_{$userFieldName}_{$userId}";
		$arUserFields[$userFieldName] = (isset($_REQUEST[$requestId]) && $_REQUEST[$requestId]) ? 1 : 0;
	}

	if(array_search(1, $arUserFields) === false) {//если не выбрано куда подтверждать, выбрасываемся
		throw new Exception("Not selected where go");
	}

	$arUserFields["GROUP_ID"] = CUser::GetUserGroup($userId);//список групп пользователей

	//добавляем в группу подтвержденных пользователей и убираем из НП
	if(($key = array_search($unconfirmmedGuestGroupId, $arUserFields["GROUP_ID"])) !== false) {
		unset($arUserFields["GROUP_ID"][$key]);
	}

	$arUserFields["GROUP_ID"][] = $confirmmedGuestGroupId;


	$userFieldFormAnswerIdName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);

	//пользовательские данные
	$rs = CUser::GetList(($by = false), ($order = false), array("ID"=>$userId), array("SELECT"=>array("UF_*")));
	$arUser = $rs->Fetch();

	//получаем результаты формы
	$arAnswers = array();
	CForm::GetResultAnswerArray(10, ($a = false), $arAnswers, ($b = false), array("RESULT_ID"=>$arUser[$userFieldFormAnswerIdName]));

	$arFields = array();
	$arFields = array_merge($arFields, $arUser);

// 	foreach($arAnswers as $key=>$arAnswer) {
	$arAnswer = $arAnswers[$arUser[$userFieldFormAnswerIdName]];
		$ar = array();
		foreach($arAnswer as $keyQuestionAnswer=>$arQuestionAnswer) {
			$arCountElems = count($arQuestionAnswer);

			if($arCountElems > 1) {
				$ar[$keyQuestionAnswer] = array();
			}
			foreach($arQuestionAnswer as $arEntityAnswer) {
				switch($arEntityAnswer["FIELD_TYPE"]) {
					case "checkbox": $v = isset($arEntityAnswer["ANSWER_TEXT"]) && $arEntityAnswer["ANSWER_TEXT"] ? $arEntityAnswer["ANSWER_TEXT"] : $arEntityAnswer["TITLE"]; break;
					case "dropdown": $v = $arEntityAnswer["ANSWER_TEXT"]; break;
					default: $v = $arEntityAnswer["USER_TEXT"];
				}
				if($arCountElems > 1) {
					$ar[$keyQuestionAnswer][] = $v;
				} else {
					$ar[$keyQuestionAnswer] = $v;
				}
			}
		}

		//добавляем к инфо пользователя
		foreach($ar as $key=>$val) {
			$arFields["S$key"] = is_array($val) ? implode(" ", $val) : $val;
		}
// 	}

	if(isset($arFields["S113"])) $arUserFields["NAME"] = $arFields["S113"];
	if(isset($arFields["S114"])) $arUserFields["LAST_NAME"] = $arFields["S114"];
	if(isset($arFields["S107"])) $arUserFields["WORK_COMPANY"] = $arFields["S107"];
	if(isset($arFields["S113"]) && isset($arFields["S114"])) $arUserFields["UF_FIO"] = $arFields["S113"]." ".$arUserFields["LAST_NAME"] = $arFields["S114"];

	//сохраняем пользователя
	$user = new CUser;
	$user->Update($userId, $arUserFields);
	
	$arFields["PASSWORD"] = $arFields["S133"];//пароль

	//отправляем письмо
	if(isset($_REQUEST["CONFIRM_UF_MR_{$userId}"])
		&& ($templateId = intval(CFormMatrix::getPostTemplateByExhibID($arExhib["ID"], "GUEST_MORNING")))) {
		CEvent::Send("GUEST_MORNING_CONFIRM", SITE_ID, $arFields, "Y", $templateId);
	}

	if(isset($_REQUEST["CONFIRM_UF_EV_{$userId}"])
		&& ($templateId = intval(CFormMatrix::getPostTemplateByExhibID($arExhib["ID"], "GUEST_EVENING")))) {
		CEvent::Send("GUEST_EVENING_CONFIRM", SITE_ID, $arFields, "Y", $templateId);
	}
	
	if(isset($_REQUEST["CONFIRM_UF_HB_{$userId}"]) && isset($_REQUEST["CONFIRM_UF_MR_{$userId}"]) && ("361" == $arExhib["ID"]) or ("488" == $arExhib["ID"])) {
		CEvent::Send("GUEST_HB_CONFIRM", SITE_ID, $arFields);
	}
}