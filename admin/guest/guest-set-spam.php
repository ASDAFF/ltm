<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
try {
	if(CModule::IncludeModule('iblock')) {

		$rs = CIBlockElement::GetList(array(),
				array("ID"=>intval($_REQUEST["EXHIB_ID"])), false, array("nTopCount"=>1),
				array("ID", "IBLOCK_ID", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP", "PROPERTY_GUEST_SPAM_GROUP"));
		if($arExhib = $rs->Fetch()) {
			if(isset($arExhib["PROPERTY_UC_GUESTS_GROUP_VALUE"]) && $arExhib["PROPERTY_UC_GUESTS_GROUP_VALUE"]) {
				$groupUConfirmID = $arExhib["PROPERTY_UC_GUESTS_GROUP_VALUE"];
			}

			if(isset($arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"]) && $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"]) {
				$groupConfirmID = $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];
			}

			if(isset($arExhib["PROPERTY_GUEST_SPAM_GROUP_VALUE"]) && $arExhib["PROPERTY_GUEST_SPAM_GROUP_VALUE"]) {
			    $groupSpamID = $arExhib["PROPERTY_GUEST_SPAM_GROUP_VALUE"];
			}
		}

		if(isset($_REQUEST["USER_ID"])) {
			toSpamGuest(intval($_REQUEST["USER_ID"]), $groupUConfirmID, $groupConfirmID, $groupSpamID, $_REQUEST["SPAM_TYPE"]);
		} elseif(isset($_REQUEST["SELECTED_USERS"])) {
			foreach($_REQUEST["SELECTED_USERS"] as $userId) {
				toSpamGuest($userId, $groupUConfirmID, $groupConfirmID, $groupSpamID, $_REQUEST["SPAM_TYPE"]);
			}
		}
	} else {
		throw new Exception("can't load iblock");
	}
}catch(Exception $e) {
	header( 'HTTP/1.1 400 BAD REQUEST' );
	die($e->GetMessage());
}

function toSpamGuest($userId, $groupUConfirmID, $groupConfirmID, $groupSpamID, $spamType) {

	$arGroups = CUser::GetUserGroup($userId);

    foreach ($arGroups as $index => $group)
    {
        if($group == $groupConfirmID || $group == $groupSpamID || $group == $groupUConfirmID)
        {
            unset($arGroups[$index]);
        }
    }

    if($spamType == "Y")//если добавляем в спам
    {
        $arGroups[] = $groupSpamID;
    }
    elseif($spamType == "N")//если вытаскиваем из спама
    {
        $arGroups[] = $groupUConfirmID;
    }
    else
    {
        return;
    }

    CUser::SetUserGroup($userId, $arGroups);

}