<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
try {
	if(CModule::IncludeModule('iblock')) {
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
			confirmUser(intval($_REQUEST["USER_ID"]), $arUserChanges, $unconfirmmedGuestGroupId, $confirmmedGuestGroupId);
		} elseif(isset($_REQUEST["SELECTED_USERS"])) {
			foreach($_REQUEST["SELECTED_USERS"] as $userId) {
				confirmUser($userId, $arUserChanges, $unconfirmmedGuestGroupId, $confirmmedGuestGroupId);
			}
		}
	} else {
		throw new Exception("can't load iblock");
	}
}catch(Exception $e) {
	header( 'HTTP/1.1 400 BAD REQUEST' );
	die($e->GetMessage());
}

function confirmUser($userId, $arUserChanges, $unconfirmmedGuestGroupId, $confirmmedGuestGroupId) {
	$arUserFields = array();

	$arUserFields["GROUP_ID"] = CUser::GetUserGroup($userId);//список групп пользователей

	//добавляем в группу подтвержденных пользователей и убираем из НП
	if(($key = array_search($confirmmedGuestGroupId, $arUserFields["GROUP_ID"])) !== false) {
		unset($arUserFields["GROUP_ID"][$key]);
	}

	foreach($arUserChanges as $arUserChange) {
		$arUserFields[$arUserChange] = false;
	}

	$arUserFields["GROUP_ID"][] = $unconfirmmedGuestGroupId;

	$user = new CUser;
	$user->Update($userId, $arUserFields);
}