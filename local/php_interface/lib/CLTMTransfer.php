<?php

use \Bitrix\Main\Loader;
/**
 * Created by PhpStorm.
 * User: dmitry
 */
class CLTMTransfer
{
	static function Transfer($oldExhId, $newExhId)
	{
		global $USER;
		if(!is_object($USER))
		{
			$USER = new CUser();
		}

		$arOldExh = self::GetExhData($oldExhId);
		$arNewExh = self::GetExhData($newExhId);

		if(!$arOldExh || !$arNewExh)
		{
			return false;
		}

		//получаем список id пользователей, которых необходимо переносить
		$arUsersID = self::GetUsersNeedTransfer($arOldExh["CONFIRMED_GROUP"], array($arNewExh["CONFIRMED_GROUP"], $arNewExh["UNCONFIRMED_GROUP"], $arNewExh["SPAM_GROUP"]));

		//если нет пользователйе для переноса
		if(empty($arUsersID))
		{
			return false;;
		}

		//получаем данные пользователей
		$arUsers = self::GetUsersData($arUsersID);

		foreach($arUsers as $arUser)
		{
			$lastExh = self::GetLastExh($arUser);

			//Если эта выставка последняя, то копируем данные с нее в дефолтную
			if($arOldExh["ID"] == $lastExh)
			{
				self::CopyFormDataToDefault($arUser, $arOldExh["ID"]);
			}

			//Обновляем группы
			$arUser["GROUPS"][] = $arNewExh["UNCONFIRMED_GROUP"];
			$USER->SetUserGroup($arUser["ID"], $arUser["GROUPS"]);
		}
	}

	/**
	 * Получение id групп привязанных к выставке
	 * @param $exh_id - id выставки
	 * @return array|bool
	 */
	private static function GetExhData($exh_id)
	{
		if(!(int)$exh_id)
		{
			return false;
		}

		Loader::includeModule("iblock");

		$rsExh = CIBlockElement::GetList(
			$arOrder = array("SORT" => "ASC"),
			$arFilter = array(
				"ID" => $exh_id
			),
			false,
			$arNav = array("nTopCount" => 1),
			array(
				"ID",
				"IBLOCK_ID",
				"NAME",
				"PROPERTY_USER_GROUP_ID",			//Подтвержденные участники
				"PROPERTY_UC_PARTICIPANTS_GROUP",	//Неподтвержденные участники
				"PROPERTY_PARTICIPANT_SPAM_GROUP",	//Участники Спам группа
			)
		);

		$arExh = array();
		if($arExh = $rsExh->Fetch())
		{
			$arExh["CONFIRMED_GROUP"] = $arExh["PROPERTY_USER_GROUP_ID_VALUE"];
			$arExh["UNCONFIRMED_GROUP"] = $arExh["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"];
			$arExh["SPAM_GROUP"] = $arExh["PROPERTY_PARTICIPANT_SPAM_GROUP_VALUE"];

			unset(
				$arExh["PROPERTY_USER_GROUP_ID_VALUE"],
				$arExh["PROPERTY_USER_GROUP_ID_VALUE_ID"],
				$arExh["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"],
				$arExh["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE_ID"],
				$arExh["PROPERTY_PARTICIPANT_SPAM_GROUP_VALUE"],
				$arExh["PROPERTY_PARTICIPANT_SPAM_GROUP_VALUE_ID"]
			);
		}

		return $arExh;
	}

	/**
	 * Список пользователей из исходной группы, у которых отсутствуют привязки к исключаемым группам
	 * @param $origGroupID - исходная группа
	 * @param $arExcludeGroup - массив груп из для исключени пользователей
	 * @return array|bool
	 */
	private static function GetUsersNeedTransfer($origGroupID, $arExcludeGroup)
	{
		if(empty($origGroupID))
		{
			return false;
		}

		$arOriginUsers = CGroup::GetGroupUser($origGroupID);
		$arExcludeUsers = array();

		foreach($arExcludeGroup as $groupID)
		{
			$tmpUsers = CGroup::GetGroupUser($groupID);
			$arExcludeUsers = array_merge($arExcludeUsers, $tmpUsers);
		}

		//убираем пользователей из групп: подтвержденные, неподтвержденные, спам
		$arResult = array_diff($arOriginUsers, $arExcludeUsers);

		return $arResult;
	}

	/**
	 * Получает данные по пользователям
	 * @param $arUsersID - массив ID пользователей
	 * @return array|bool
	 */
	function GetUsersData($arUsersID)
	{
		if(empty($arUsersID))
		{
			return false;
		}

		global $USER;

		$arFilter = array(
			"ID" => implode(" | ", $arUsersID)
		);

		$arSelect = array(
			"FIELDS" => array("ID", "ACTIVE", "LOGIN", "EMAIL", "WORK_COMPANY", ),
			"SELECT" => array(
				"UF_*"
			)
		);

		#получаем список пользователей из группы подтвержденных пользователей
		$rsUsers = $USER->GetList(
			$by="id",
			$order="asc",
			$arFilter,
			$arSelect
		);

		$arUsers = array();
		while($arUser = $rsUsers->fetch())
		{
			$arUser["GROUPS"] = $USER->GetUserGroup($arUser["ID"]);
			$arUsers[$arUser["ID"]] = $arUser;
		}

		return $arUsers;
	}

	/**
	 * Получаем ID последней выставки, в которой учавствовал пользователь
	 * @param $arUser
	 * @return bool|int
	 * @throws \Bitrix\Main\LoaderException
	 */
	private static function GetLastExh($arUser)
	{
		if(empty($arUser) || empty($arUser["GROUPS"]))
		{
			return false;
		}

		Loader::includeModule("iblock");

		$arExhibFilter = array(
			"IBLOCK_ID" => 15,
			"PROPERTY_USER_GROUP_ID" => $arUser["GROUPS"],
			"<=PROPERTY_DATE_TIME" => date("Y-m-d")
		);
		$arSelect = array(
			"ID",
			"IBLOCK",
			"PROPERTY_USER_GROUP_ID"
		);

		$rsExhib = CIBlockElement::GetList(array("PROPERTY_DATE_TIME" => "desc"), $arExhibFilter, false, array("nTopCount" => 1), $arSelect);
		if($arExhib = $rsExhib->Fetch())
		{
			return $arExhib["ID"];
		}

	}

	/**
	 * @param $arUser - данные пользователя
	 * @param $fromExhId - ID исходной формы
	 * @return array
	 */

	private static function CopyFormDataToDefault($arUser, $fromExhId)
	{
		global $USER;
		Loader::includeModule("form");
		if(!is_object($USER))
		{
			$USER = new CUser();
		}


		#данные с выставки
		$formID = CFormMatrix::getPFormIDByExh($fromExhId);
		$formPropName = CFormMatrix::getPropertyIDByExh($fromExhId);//получение имени свойства пользователя для выставки

		#получаем айди результата
		$resultId = $arUser[$formPropName];
		$baseResultID = $arUser["UF_ID"];

		$FieldSID = array(
			"NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_446",$formID),		//Participant first name
			"LAST_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_551",$formID),	//Participant last name
			"JOB_TITLE" =>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_729",$formID),	//Job title
			"PHONE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_394",$formID),		//Telephone
			"SKYPE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_211",$formID),		//Skype
			"EMAIL" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859",$formID),		//E-mail
			"EMAIL_CONF" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_585",$formID),	//Please confirm your e-mail
			"EMAIL_ALT" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_749",$formID),	//Alternative e-mail
			"PHOTO" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575",$formID),		//Персональное фото
			"SALUTATION" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_889",$formID),	//Salutation
		);

		//получаем результат из базы
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
			$arAnswerSID
		);

		$newArAnswerSID = array();

		foreach ($FieldSID as $name => $sid)
		{
			if(isset($arAnswerSID[$sid]))
			{
				$resName = "";
				$tmp = reset($arAnswerSID[$sid]);
				switch ($tmp["FIELD_TYPE"])
				{
					case "dropdown" : $resName = "ANSWER_ID";break;
					case "image" : $resName = "USER_FILE_ID"; break;
					case "text" : $resName = "USER_TEXT"; break;
				}

				$newArAnswerSID[$name] = $tmp[$resName];
			}
		}

		/*Заполняем массив для вебформы участника*/
		$arPersonalFormFields = array(
			"form_text_84" => $newArAnswerSID["NAME"], 								//Participant first name
			"form_text_85" => $newArAnswerSID["LAST_NAME"], 						//Participant last name
			"form_text_87" => $newArAnswerSID["JOB_TITLE"], 						//Job title
			"form_text_88" => $newArAnswerSID["PHONE"], 							//Telephone
			"form_text_1474" => $newArAnswerSID["SKYPE"], 							//Skype
			"form_text_89" => $newArAnswerSID["EMAIL"],								//E-mail
			"form_text_90" => $newArAnswerSID["EMAIL_CONF"],						//Please confirm your e-mail
			"form_text_91" => $newArAnswerSID["EMAIL_ALT"],							//Alternative e-mail
			"form_dropdown_SIMPLE_QUESTION_889" =>
				CFormMatrix::getAnswerSalutationBase($newArAnswerSID["SALUTATION"],$formID),	//Salutation
			"form_image_195" => Cfile::MakeFileArray($newArAnswerSID["PHOTO"]),		//Персональное фото
		);

		//обновляем результат
		CFormResult::Update($baseResultID, $arPersonalFormFields, "N", "N");

		return $newArAnswerSID;
	}

}