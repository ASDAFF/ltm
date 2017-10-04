<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use Ltm\Domain\GuestStorage\Manager as GuestStorageManager;

class CAdminGuestStorage extends CBitrixComponent
{

	var $arResult;
	var $errors = array();

	const WORKING_FORM = 10;
	const STORAGE_FORM = 31;
	const STORAGE_GROUP_ID = 59;
	const EXHIBITION_IB = 15;

	public function executeComponent()
	{
		global $APPLICATION;
		self::checkModules();

		$request = self::getRequest();
		$this->arResult['ACTION_URL'] = $APPLICATION->GetCurPageParam('', array('FILTER_DATA', 'FILTER_TYPE', 'filter', 'reset_filter', 'popup', 'ID'));
		if($request->get("reset_filter")){
			LocalRedirect($this->arResult['ACTION_URL']);
		}

		if($request->get('popup')){
			self::showPopup();
		}

		if($request->get('TYPE') == 'inworking'){
			self::putInWorking();
		}


		if(empty($this->errors)){
			//получаем список гостей в хранилище
			self::getGuestList();
			if($request->get('old')){
				self::getGuestFormData();
			} else {
				self::getHLGuestFormData();
			}
		}


		$this->includeComponentTemplate();
	}

	/**
	 * Transferring a guest in the working database
	 */
	private function putInWorking()
	{
		$request = self::getRequest();

		$obGS = new CLTMGuestStorage();

		$res = $obGS->putInWorking($request->get('ID'), $request->get('EXHIBITION'), $request->get('MORNING'), $request->get('EVENING'));

		if($res){
			LocalRedirect($this->arResult['ACTION_URL']);
		}else{
			$this->errors = array_merge($this->errors, $obGS->errors);
		}
	}

	/**
	 * Preparation data for pop-up window
	 */
	private function showPopup()
	{
		global $APPLICATION;
		$request = self::getRequest();
		self::getExhibitions();
		$this->arResult['USER_ID'] = $request->get('ID');

		$APPLICATION->RestartBuffer();
		$this->includeComponentTemplate('ajax-form');
		die();
	}

	/** Получение списка выставок
	 * @param string $active
	 */
	private function getExhibitions($active = 'Y')
	{
		if($active != 'N'){
			$active = 'Y';
		}

		$rsExh = CIBlockElement::GetList(
			array('sort' => 'asc'),
			array(
				'IBLOCK_ID' => self::EXHIBITION_IB,
				'ACTIVE' => $active
			),
			false,
			false,
			array('ID', 'NAME')
		);
		while($arExh = $rsExh->Fetch()){
			$this->arResult['EXHIBITIONS'][$arExh['ID']] = $arExh['NAME'];
		}
	}

	/**
	 * Preparation guest list
	 */
	private function getGuestList()
	{
		$request = self::getRequest();

		$arUserFilter = array(
			'ACTIVE' => 'Y',
			'GROUPS_ID' => self::STORAGE_GROUP_ID,
		);

		if($request->get('FILTER_TYPE')){
			$arUserFilter[$request->get('FILTER_TYPE')] = "%" . $request->get('FILTER_DATA') . "%";
		}
		$rsUsers = CUser::GetList(
			$by = 'id',
			$order = 'asc',
			$arUserFilter,
			array(
				'FIELDS' => array("ID", "LOGIN", "EMAIL"),
				'SELECT' => array("UF_*"),
				//"NAV_PARAMS" => array("nPageSize" => $this->arParams['COUNT'])
			)
		);
		$rsUsers->NavStart($this->arParams['COUNT'], false);
		$this->arResult["NAVIGATE"] = $rsUsers->GetNavPrint(GetMessage("STORAGE_PAGES"));
		while($arUser = $rsUsers->Fetch()){
			$this->arResult['USERS'][$arUser['ID']] = $arUser;
		}
	}

	/**
	 * Preparation web forms data
	 */
	private function getHLGuestFormData()
	{
		if(empty($this->arResult['USERS'])){
			$this->errors[] = 'Users not found';
		}

		//Получаем ID результатов веб-форм
		$arResultUserID = array();
		foreach($this->arResult['USERS'] as $k=>$arUser){
			if($arUser['UF_ID_COMP']){
				$arResultUserID[$arUser['ID']] = $k;
			}
		}

		$guestStorageManager = new GuestStorageManager();
		$res = $guestStorageManager->getResultListByUserIDs(array_keys($arResultUserID));
		foreach($res as $userid => $data)
		{
			$this->arResult['USERS'][ $arResultUserID[$userid] ]['FORM_DATA'] = $data;
		}

		$rsQuestions = \CFormField::GetList(self::STORAGE_FORM, "N");
		$arQuestions = [];
		while ($arQuestion = $rsQuestions->Fetch()) {
			$arQuestions[$arQuestion['ID']] = $arQuestion;
		}

		$this->arResult["QUESTIONS"]  =$arQuestions;
	}

	/**
	 * Preparation web forms data
	 */
	private function getGuestFormData()
	{
		if(empty($this->arResult['USERS'])){
			$this->errors[] = 'Users not found';
		}

		//Получаем ID результатов веб-форм
		$arResultID = array();
		foreach($this->arResult['USERS'] as $arUser){
			if($arUser['UF_ID_COMP']){
				$arResultID[] = $arUser['UF_ID_COMP'];
			}
		}

		if($arResultID){
			//Получаем результаты вебформ
			CForm::GetResultAnswerArray(
				self::STORAGE_FORM,
				$this->arResult["QUESTIONS"],
				$this->arResult["ANSWERS"],
				$this->arResult["ANSWERS2"],
				array("RESULT_ID" => implode("|", $arResultID))
			);


			foreach($this->arResult['USERS'] as &$arUser){
				$formResult = $arUser["UF_ID_COMP"];
				if($formResult){
					$result = $this->arResult["ANSWERS"][$formResult];

					foreach($result as $answerID => $answer)//проход по ответам
					{

						$optionCount = count($answer);
						foreach($answer as $option)//проход по ответам
						{
							//определегние названия свойсвта
							$propAnswer = "";
							switch($option["FIELD_TYPE"]){
								case "dropdown" :
								case "checkbox" :
									$propAnswer = "ANSWER_TEXT";
									break;
								case "text" :
									$propAnswer = "USER_TEXT";
									break;
								case "image" :
									$propAnswer = "USER_FILE_ID";
									break;
								default:
									$propAnswer = "USER_TEXT";
							}

							if($optionCount > 1){
								if(!isset($arUser["FORM_DATA"][$answerID])){
									$arUser["FORM_DATA"][$answerID] = array(
										"FIELD_TYPE" => $option["FIELD_TYPE"],
										"QUESTIONS" => $option["TITLE"],
										"ANSWER_ID" => $option["ANSWER_ID"],
										"SID" => $option["SID"],
										"VALUE" => array(),
									);
								}

								$arUser["FORM_DATA"][$answerID]["VALUE"][$option["ANSWER_ID"]] = $option[$propAnswer];

							}else{
								$arUser["FORM_DATA"][$answerID] = array(
									"FIELD_TYPE" => $option["FIELD_TYPE"],
									"QUESTIONS" => $option["TITLE"],
									"ANSWER_ID" => $option["ANSWER_ID"],
									"SID" => $option["SID"],
									"VALUE" => $option[$propAnswer],
								);
							}
						}
					}
				}
			}
		}
	}

	/** Connect the required modules
	 * @throws \Bitrix\Main\LoaderException
	 */
	private function checkModules()
	{
		if(!Loader::includeModule('iblock')){
			$this->errors[] = 'iblock module is not installed';
		}
		if(!Loader::includeModule('form')){
			$this->errors[] = 'web-form module is not installed';
		}
		if(!Loader::includeModule('ltm.domain')){
			$this->errors[] = 'ltm.domain module is not installed';
		}
	}

	/**
	 * @return \Bitrix\Main\HttpRequest
	 */
	public function getRequest()
	{
		return $this->request;
	}
}