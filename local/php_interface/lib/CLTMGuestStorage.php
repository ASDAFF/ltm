<?php

/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 23.05.2016
 * Time: 20:44
 */
use Bitrix\Main\Loader;
use Ltm\Domain\GuestStorage\Manager as GuestStorageManager;
use Ltm\Domain\GuestStorage\FormResult as LtmFormResult;

class CLTMGuestStorage
{
	var $userID;
	var $exhID;
	var $arUser = array();
	var $errors = array();
	var $arExcludeSID = array(
		'SIMPLE_QUESTION_836',//Утро
		'SIMPLE_QUESTION_156',//Вечер
		'SIMPLE_QUESTION_762',//Зал
		'SIMPLE_QUESTION_211',//Стол
	);


	const WORKING_FORM = 10;
	const STORAGE_FORM = 31;
	const STORAGE_GROUP_ID = 59;

	public function __construct()
	{
		//проверяем наличие необходимых модулей
		if(!Loader::includeModule('form')){
			$this->userID[] = 'web form module is not installed';
		}
		if(!Loader::includeModule('iblock')){
			$this->userID[] = 'iblock module is not installed';
		}
		if(!Loader::includeModule('ltm.domain')){
			$this->userID[] = 'ltm.domain module is not installed';
		}
	}

	/** Places the guest in storage
	 * @param $userID
	 * @param $exhID
	 * @param bool $morning
	 * @param bool $evening
	 * @return bool
	 */
	public function putInWorkingOld($userID, $exhID, $morning = false, $evening = false)
	{
		$userID = intval($userID);
		if(!$userID){
			$this->errors[] = 'Empty user ID';
			return false;
		}else{
			$this->userID = $userID;
		}

		$exhID = intval($exhID);
		if(!$exhID){
			$this->errors[] = 'Empty exhibiton ID';
			return false;
		}else{
			$this->exhID = $exhID;
		}


		if(self::getUserInfo()){
			$resultID = intval($this->arUser['UF_ID_COMP']);
			if($resultID){
				$fieldSID = CFormMatrix::getSIDListGuestForm(self::STORAGE_FORM);

				//получаем результат из базы
				CFormResult::GetDataByID(
					$resultID,
					$fieldSID,
					$arResult,
					$arAnswerSID
				);

				$newArAnswerSID = array();

				foreach($fieldSID as $index => $sid){
					if(isset($arAnswerSID[$sid])){
						foreach($arAnswerSID[$sid] as $arAnswer){
							switch($arAnswer["FIELD_TYPE"]){
								case "dropdown" :
									$answerValue = CFormMatrix::getListAnswersIDGuestForm(self::STORAGE_FORM, self::WORKING_FORM, $sid, $arAnswer["ANSWER_ID"]);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$sid}";
									$newArAnswerSID[$questionName] = $answerValue;
									break;
								case "multiselect" :
								case "checkbox" :
									$answerValue = CFormMatrix::getListAnswersIDGuestForm(self::STORAGE_FORM, self::WORKING_FORM, $sid, $arAnswer["ANSWER_ID"]);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$sid}";
									$newArAnswerSID[$questionName][] = $answerValue;
									break;
								case "image" :
									$answerValue = CFile::MakeFileArray($arAnswer["USER_FILE_ID"]);
									$fieldID = CFormMatrix::getAnswerGuestForm($arAnswer["ANSWER_ID"], self::STORAGE_FORM, self::WORKING_FORM);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$fieldID}";
									$newArAnswerSID[$questionName] = $answerValue;
									break;
								case "text" :
								case "textarea" :
								case "date" :
									$answerValue = $arAnswer["USER_TEXT"];
									$fieldID = CFormMatrix::getAnswerGuestForm($arAnswer["ANSWER_ID"], self::STORAGE_FORM, self::WORKING_FORM);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$fieldID}";
									$newArAnswerSID[$questionName] = $answerValue;
									break;
							}
						}

					}
				}

				//Если выбрали утро
				if($morning){
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_836'] = array(843);
				}
				//Если выбрали вечер
				if($evening){
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_156'] = array(844);
				}
				$newResultID = CFormResult::Add(self::WORKING_FORM, $newArAnswerSID);

				$arExhGroups = self::getExhGroups($exhID);


				//Подготавливаем данные для обновления пользователя
				$arNewGroups = array_diff($this->arUser['GROUPS'], array(self::STORAGE_GROUP_ID));
				$arNewGroups[] = $arExhGroups['NOT_CONFIRMED'];

				$arNewUserFields = array(
					'UF_ID_COMP' => $newResultID,
					'UF_MR' => "",	//$morning
					'UF_EV' => "",	//$evening
					'UF_HB' => "",
					CFormMatrix::getPropertyIDByExh($exhID) => $newResultID,
					'GROUP_ID' => $arNewGroups
				);


				//Обновляем пользователя
				$obUser = new CUser();
				if($obUser->Update($this->userID, $arNewUserFields)){
					CFormResult::Delete($resultID);
				}else{
					$this->errors[] = 'User not updated. ' . $obUser->LAST_ERROR;
				}

			}else{
				$this->errors[] = 'Web-form result ID not found';
				return false;
			}
		}

		if(empty($this->errors)){
			return true;
		}
		return false;
	}

	public function putInWorking($userID, $exhID, $morning = false, $evening = false)
	{
		$userID = intval($userID);
		if (!$userID) {
			$this->errors[] = 'Empty user ID';

			return false;
		} else {
			$this->userID = $userID;
		}

		$exhID = intval($exhID);
		if (!$exhID) {
			$this->errors[] = 'Empty exhibiton ID';

			return false;
		} else {
			$this->exhID = $exhID;
		}

		if (self::getUserInfo()) {
			$guestStorageManager = new GuestStorageManager();
			$res = $guestStorageManager->getResultListByUserIDs([$userID]);
			$newArAnswerSID = array();
			$res[$userID][626]['VALUE'] = $res[$userID][625]['VALUE'];
			$res[$userID][642]['VALUE'] = $res[$userID][641]['VALUE'];

			$fieldAr = ['SIMPLE_QUESTION_677', 'SIMPLE_QUESTION_383', 'SIMPLE_QUESTION_244', 'SIMPLE_QUESTION_212', 'SIMPLE_QUESTION_497', 'SIMPLE_QUESTION_526', 'SIMPLE_QUESTION_878'];
			foreach ($res[$userID] as $qid => $question) {
				if (count($question['ANSWERS']) > 1) {
					$answers = [];
					foreach ($question['ANSWERS'] as $k => $v) {
						$answers[$v['MESSAGE']] = $k;
					}
					if(count($question['VALUE']) > 1) {
						$values = [];
						foreach ($question['VALUE'] as $val) {
							$values[] = $answers[$val];
						}
					} else {
						if(in_array($question['SID'], $fieldAr)){
							$values = $answers[$question['VALUE'][0]];
						} else {
							$values = $answers[$question['VALUE']];
						}
					}
				} else {
					$values = $question['VALUE'];
				}

				$answer = $question["ANSWERS"][ array_keys($question["ANSWERS"])[0] ];
				$fieldID = '';
				switch ($answer['FIELD_TYPE']) {
					case "dropdown" :
						if(count($values) > 1)
						{
							$t = [];
							foreach($values as $val)
							{
								$val_new = CFormMatrix::getListAnswersIDGuestForm(self::STORAGE_FORM, self::WORKING_FORM, $question['SID'], $val);
								$t[] = $val_new;
							}
						} else {
							$t = CFormMatrix::getListAnswersIDGuestForm(self::STORAGE_FORM, self::WORKING_FORM, $question['SID'], $values);
						}
						$questionName = "form_{$answer['FIELD_TYPE']}_{$question['SID']}";
						$newArAnswerSID[$questionName] = $t;
						break;
					case "multiselect" :
					case "checkbox" :
						if(count($values) > 1)
						{
							$t = [];
							foreach($values as $val)
							{
								$val_new = CFormMatrix::getListAnswersIDGuestForm(self::STORAGE_FORM, self::WORKING_FORM, $question['SID'], $val);
								$t[] = $val_new;
							}
						} else {
							$t = CFormMatrix::getListAnswersIDGuestForm(self::STORAGE_FORM, self::WORKING_FORM, $question['SID'], $values);
						}
						$questionName = "form_{$answer['FIELD_TYPE']}_{$question['SID']}";
						$newArAnswerSID[$questionName] = $t;
						break;
					case "image" :
						$fieldID = CFormMatrix::getAnswerGuestForm($answer["ID"], self::STORAGE_FORM, self::WORKING_FORM);
						$questionName = "form_{$answer['FIELD_TYPE']}_{$fieldID}";
						$newArAnswerSID[$questionName] = $values;
						break;
					case "text" :
					case "textarea" :
					case "password" :
					case "date" :
						$fieldID = CFormMatrix::getAnswerGuestForm($answer["ID"], self::STORAGE_FORM, self::WORKING_FORM);
						$questionName = "form_{$answer['FIELD_TYPE']}_{$fieldID}";
						$newArAnswerSID[$questionName] = $values;
						break;
				}
			}
			//Если выбрали утро
			if($morning){
				$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_836'] = array(843);
			}
			//Если выбрали вечер
			if($evening){
				$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_156'] = array(844);
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_677']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_677'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_677'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_677']];
				}
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_383']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_383'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_383'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_383']];
				}
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_244']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_244'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_244'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_244']];
				}
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_212']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_212'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_212'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_212']];
				}
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_497']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_497'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_497'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_497']];
				}
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_526']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_526'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_526'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_526']];
				}
			}
			if(isset($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_878']))
			{
				if(!is_array($newArAnswerSID['form_checkbox_SIMPLE_QUESTION_878'])) {
					$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_878'] = [$newArAnswerSID['form_checkbox_SIMPLE_QUESTION_878']];
				}
			}
			if(isset($newArAnswerSID['form_image_1312']))
			{
				if(!empty($newArAnswerSID['form_image_1312'])) {
					$newArAnswerSID['form_image_1312'] = \CFile::MakeFileArray($newArAnswerSID['form_image_1312']);
				}
			}
			if(isset($newArAnswerSID['form_image_1313']))
			{
				if(!empty($newArAnswerSID['form_image_1313'])) {
					$newArAnswerSID['form_image_1313'] = \CFile::MakeFileArray($newArAnswerSID['form_image_1313']);
				}
			}
			$newResultID = CFormResult::Add(self::WORKING_FORM, $newArAnswerSID);
			$arExhGroups = self::getExhGroups($exhID);
			$arNewGroups = array_diff($this->arUser['GROUPS'], array(self::STORAGE_GROUP_ID));
			$arNewGroups[] = $arExhGroups['NOT_CONFIRMED'];

			$arNewUserFields = array(
				'UF_ID_COMP' => $newResultID,
				'UF_MR' => "",	//$morning
				'UF_EV' => "",	//$evening
				'UF_HB' => "",
				CFormMatrix::getPropertyIDByExh($exhID) => $newResultID,
				'GROUP_ID' => $arNewGroups
			);


			//Обновляем пользователя
			$obUser = new CUser();
			if($obUser->Update($this->userID, $arNewUserFields)){
				$guestStorageManager = new GuestStorageManager();
				$guestStorageManager->deleteResult($userID);
			}else{
				$this->errors[] = 'User not updated. ' . $obUser->LAST_ERROR;
			}
		}

		if (empty($this->errors)) {
			return true;
		}

		return false;
	}

	/** Puts a guest in a working form
	 * @param $userID
	 * @return bool
	 */
	public function putInStorageOld($userID)
	{
		$userID = intval($userID);
		if(!$userID){
			$this->errors[] = 'Empty user ID';
			return false;
		}else{
			$this->userID = $userID;
		}

		if(self::getUserInfo()){
			$resultID = intval($this->arUser['UF_ID_COMP']);
			if($resultID){
				$fieldSID = CFormMatrix::getSIDListGuestForm(self::WORKING_FORM);

				//получаем результат из базы
				CFormResult::GetDataByID(
					$resultID,
					$fieldSID,
					$arResult,
					$arAnswerSID
				);

				$newArAnswerSID = array();

				foreach($fieldSID as $index => $sid){
					if(isset($arAnswerSID[$sid]) && !in_array($sid, $this->arExcludeSID)){

						foreach($arAnswerSID[$sid] as $arAnswer){
							switch($arAnswer["FIELD_TYPE"]){
								case "dropdown" :
									$answerValue = CFormMatrix::getListAnswersIDGuestForm(self::WORKING_FORM, self::STORAGE_FORM, $sid, $arAnswer["ANSWER_ID"]);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$sid}";
									$newArAnswerSID[$questionName] = $answerValue;
									break;
								case "multiselect" :
								case "checkbox" :
									$answerValue = CFormMatrix::getListAnswersIDGuestForm(self::WORKING_FORM, self::STORAGE_FORM, $sid, $arAnswer["ANSWER_ID"]);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$sid}";
									$newArAnswerSID[$questionName][] = $answerValue;
									break;
								case "image" :
									$answerValue = CFile::MakeFileArray($arAnswer["USER_FILE_ID"]);
									$fieldID = CFormMatrix::getAnswerGuestForm($arAnswer["ANSWER_ID"], self::WORKING_FORM, self::STORAGE_FORM);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$fieldID}";
									$newArAnswerSID[$questionName] = $answerValue;
									break;
								case "text" :
								case "textarea" :
								case "date" :
									$answerValue = $arAnswer["USER_TEXT"];
									$fieldID = CFormMatrix::getAnswerGuestForm($arAnswer["ANSWER_ID"], self::WORKING_FORM, self::STORAGE_FORM);
									$questionName = "form_{$arAnswer["FIELD_TYPE"]}_{$fieldID}";
									$newArAnswerSID[$questionName] = $answerValue;
									break;
							}
						}

					}
				}

				$newResultID = CFormResult::Add(self::STORAGE_FORM, $newArAnswerSID);
				$exhID = self::getExhID();
				$arExhGroups = self::getExhGroups($exhID);

				//Подготавливаем данные для обновления пользователя
				$arNewGroups = array_diff($this->arUser['GROUPS'], array_values($arExhGroups));
				$arNewGroups[] = self::STORAGE_GROUP_ID;

				$arNewUserFields = array(
					'UF_ID_COMP' => $newResultID,
					'UF_MR' => "",
					'UF_EV' => "",
					'UF_HB' => "",
					CFormMatrix::getPropertyIDByExh($exhID) => "",
					'GROUP_ID' => $arNewGroups
				);

				//Обновляем пользователя
				$obUser = new CUser();
				if($obUser->Update($this->userID, $arNewUserFields)){
					CFormResult::Delete($resultID);
				}else{
					$this->errors[] = 'User not updated. ' . $obUser->LAST_ERROR;
				}
			}else{
				$this->errors[] = 'Web-form result ID not found';
				return false;
			}
		}

		if(empty($this->errors)){
			return true;
		}
		return false;
	}

	public function putInStorage($userID)
	{
		$userID = intval($userID);
		if(!$userID){
			$this->errors[] = 'Empty user ID';
			return false;
		}else{
			$this->userID = $userID;
		}

		if(self::getUserInfo()){
			$guestStorageManager = new GuestStorageManager();
			$resultID = intval($this->arUser['UF_ID_COMP']);
			if($resultID){
				$res = $guestStorageManager->addActiveFormResult($resultID);

				if ($res === false) {
					$this->errors[] = 'HL record not created.' ;
				} else {
					$newResultID = $res;
					$exhID = self::getExhID();
					$arExhGroups = self::getExhGroups($exhID);

					//Подготавливаем данные для обновления пользователя
					$arNewGroups = array_diff($this->arUser['GROUPS'], array_values($arExhGroups));
					$arNewGroups[] = self::STORAGE_GROUP_ID;

					$arNewUserFields = array(
						'UF_ID_COMP' => $newResultID,
						'UF_MR' => "",
						'UF_EV' => "",
						'UF_HB' => "",
						CFormMatrix::getPropertyIDByExh($exhID) => "",
						'GROUP_ID' => $arNewGroups
					);

					//Обновляем пользователя
					$obUser = new CUser();
					if($obUser->Update($this->userID, $arNewUserFields)){
						CFormResult::Delete($resultID);
					}else{
						$this->errors[] = 'User not updated. ' . $obUser->LAST_ERROR;
					}
				}
			}else{
				$this->errors[] = 'Web-form result ID not found';
				return false;
			}
		}

		if(empty($this->errors)){
			return true;
		}
		return false;
	}

	/** Groups list for exhibition
	 * @param $exhID
	 * @return array|bool
	 */
	private function getExhGroups($exhID)
	{
		if(empty($exhID)){
			return false;
		}

		$rsExh = CIBlockElement::GetList(
			array(),
			array('ID' => $exhID),
			false,
			array('nTopCount' => 1),
			array('ID', 'IBLOCK_ID')
		);
		if($obExh = $rsExh->GetNextElement(true, false)){
			$properties = $obExh->GetProperties();

			return array(
				'CONFIRMED' => $properties['C_GUESTS_GROUP']['VALUE'],
				'NOT_CONFIRMED' => $properties['UC_GUESTS_GROUP']['VALUE'],
				'SPAM' => $properties['GUEST_SPAM_GROUP']['VALUE'],
			);
		}
		return false;
	}

	/** Exhibitions id for guest
	 * @return bool|int|string
	 */
	private function getExhID()
	{
		if(empty($this->arUser)){
			return false;
		}


		$arExhProp = CFormMatrix::getExhProp();
		foreach($arExhProp as $exhID => $arPropCodes){
			$exhProCode = $arPropCodes[0];

			if($this->arUser[$exhProCode] == $this->arUser['UF_ID_COMP']){
				return $exhID;
			}
		}

		return false;
	}

	/** Info guest
	 * @return bool
	 */
	private function getUserInfo()
	{
		if(!$this->userID){
			return false;
		}

		$rsUser = CUser::GetList(
			$by = 'id',
			$order = 'asc',
			array('ID' => $this->userID),
			array(
				'FIELDS' => array('ID', 'LOGIN', 'EMAIL'),
				'SELECT' => array('UF_*'),
				'NAV_PARAMS' => array('nTopCount' => 1)
			)
		);
		if($arUser = $rsUser->Fetch()){
			//получаем список групп пользователя
			$arUser['GROUPS'] = CUser::GetUserGroup($this->userID);

			$this->arUser = $arUser;
			return true;
		}

		$this->errors[] = 'User not found';
		return false;
	}
}