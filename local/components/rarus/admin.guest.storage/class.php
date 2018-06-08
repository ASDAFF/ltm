<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use Ltm\Domain\GuestStorage\FormResult as LtmFormResult;
use Bitrix\Highloadblock as HL;
use Ltm\Domain\HlblockOrm\Manager as HlBlockManager;
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
		$this->arResult['ACTION_URL'] = $APPLICATION->GetCurPageParam('', array('FILTER_DATA', 'FILTER_TYPE', 'filter', 'reset_filter', 'popup', 'ID', 'action'));
		if($request->get("reset_filter")){
			LocalRedirect($this->arResult['ACTION_URL']);
		}

		if($request->get('popup')){
			if($request->get('action') == 'delete'){
				self::showPopupDelete();
			} elseif($request->get('action') == 'deleteMass'){
                $APPLICATION->RestartBuffer();
                $this->arResult['VALUES'] = $request->get('items');
                $this->includeComponentTemplate('ajax-delete-mass');
                die();
            }else{
				self::showPopup();
			}
		}

		if($request->get('TYPE') == 'inworking'){
			self::putInWorking();
		}

		if($request->get('TYPE') == 'todelete'){
			self::deleteUser();
		}

		if($request->get('TYPE') == 'todeletemass'){
			self::deleteMassUser();
		}


		if(empty($this->errors)){
			//получаем список гостей в хранилище
			if($request->get('old')){
				self::getGuestList();
				self::getGuestFormData();
			} else {
				self::getHLGuestFormData();
			}
		}

		if($request->isAjaxRequest()){
		    $APPLICATION->RestartBuffer();
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

		if(!$res){
			$this->errors = array_merge($this->errors, $obGS->errors);
		}
	}

	private function deleteUser()
	{
		$request = self::getRequest();
		$obGS = new CLTMGuestStorage();
		$obGS->deleteUser($request->get('ID'));
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

	/**
	 * Preparation data for pop-up window
	 */
	private function showPopupDelete()
	{
		global $APPLICATION;
		$request = self::getRequest();
		self::getExhibitions();
		$this->arResult['USER_ID'] = $request->get('ID');

		$APPLICATION->RestartBuffer();
		$this->includeComponentTemplate('ajax-delete');
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
		//$rsUsers->NavStart($this->arParams['COUNT'], false);
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
		$request = self::getRequest();
		$ltmFormResult = new LtmFormResult();
		$mapping = array_flip($ltmFormResult->getMapping());
		$fields = [];
		foreach($this->arParams["FIELDS"] as $val) {
			$fields[$val] = $mapping[$val];
		}
		$fields['UF_FULL_ADDRESS'] = 'UF_FULL_ADDRESS';
		$fields['MORNING_COLLEAGUE_NAME'] = 'MORNING_COLLEAGUE_NAME';
		$fields['MORNING_COLLEAGUE_EMAIL'] = 'MORNING_COLLEAGUE_EMAIL';
		$fields['MORNING_COLLEAGUE_JOB_TITLE'] = 'MORNING_COLLEAGUE_JOB_TITLE';
		$fields['EVENING_COLLEAGUE_NAME'] = 'EVENING_COLLEAGUE_NAME';
		$fields['EVENING_COLLEAGUE_EMAIL'] = 'EVENING_COLLEAGUE_EMAIL';
		$fields['EVENING_COLLEAGUE_JOB_TITLE'] = 'EVENING_COLLEAGUE_JOB_TITLE';
		$this->arParams["FIELDS2"] = $fields;
		$this->arResult["SORT"] = ($request->get('sort'))?$request->get('sort'):"ID";
		$this->arResult["ORDER"]  = ($request->get('order'))?$request->get('order'):"asc";

		$provider = HlBlockManager::getInstance()->getProvider('GuestStorageColleague');
		$entityColleague = $provider->getEntityClassName();

		$provider = HlBlockManager::getInstance()->getProvider('GuestStorage');
		$entity = $provider->getEntityClassName();
		$params = [];
		if($request->get('FILTER_TYPE')){
			$params['filter'] = [$request->get('FILTER_TYPE') => "%" . $request->get('FILTER_DATA') . "%"];
			if($request->get('FILTER_TYPE') === 'UF_NAME') {
				$params['filter'] = [
					'LOGIC' => 'OR',
					array(
						'UF_NAME' => "%" . $request->get('FILTER_DATA') . "%"
					),
					array(
						'UF_SURNAME' => "%" . $request->get('FILTER_DATA') . "%"
					)
				];
			}
		}
		$params['order'] = [$this->arResult["SORT"] == 'ID' ? 'UF_USER_ID' : $this->arResult["SORT"]  => $this->arResult["ORDER"]];

		$nav = new \Bitrix\Main\UI\PageNavigation("nav");
		$nav->allowAllRecords(true)
			->setPageSize( $this->arParams['COUNT'] )
			->initFromUri();
		$params['count_total'] = true;
		$params['limit'] = $nav->getLimit();
		$params['offset'] = $nav->getOffset();

		$listRes = $entity::getList($params);
		$this->arResult['USERS'] = [];
		$guestStorageManager = new GuestStorageManager();
		$countryList = $guestStorageManager->getCountryList();
		$countries = [];
		foreach ($countryList as $countryItem) {
			$countries[$countryItem['ID']] = $countryItem['UF_VALUE'];
		}
		while ($data = $listRes->Fetch()) {
			$item = $data;
			$colleagues = $entityColleague::getList(['filter' => ['UF_USER_ID' => $data['UF_USER_ID']]]);
			foreach ($colleagues as $colleague) {
				if(in_array(LtmFormResult::MORNING_VAL, $colleague['UF_DAYTIME'])) {
					$item['MORNING_COLLEAGUE_NAME'] = $colleague['UF_NAME'].' '.$colleague['UF_SURNAME'];
					$item['MORNING_COLLEAGUE_EMAIL'] = $colleague['UF_EMAIL'];
					$item['MORNING_COLLEAGUE_JOB_TITLE'] = $colleague['UF_JOB_TITLE'];
				}
				if(in_array(LtmFormResult::EVENING_VAL, $colleague['UF_DAYTIME']) && empty($item['EVENING_COLLEAGUE_NAME'])) {
					$item['EVENING_COLLEAGUE_NAME'] = $colleague['UF_NAME'].' '.$colleague['UF_SURNAME'];
					$item['EVENING_COLLEAGUE_EMAIL'] = $colleague['UF_EMAIL'];
					$item['EVENING_COLLEAGUE_JOB_TITLE'] = $colleague['UF_JOB_TITLE'];
				}
			}
			$item['UF_COUNTRY'] = $countries[$item['UF_COUNTRY']];
			$item['UF_FULL_ADDRESS'] = $item['UF_POSTCODE'].', '.$item['UF_ADDRESS'];
			$this->arResult['USERS'][$data['UF_USER_ID']] = $item;
		}
		$nav->setRecordCount($listRes->getCount());
		$this->arResult["NAVIGATE"] = $this->setNavigation($nav, 'Storage');

		$rsQuestions = \CFormField::GetList(self::STORAGE_FORM, "N");
		$arQuestions = [];
		while ($arQuestion = $rsQuestions->Fetch()) {
			$arQuestions[$arQuestion['ID']] = $arQuestion;
		}
		$this->arResult["QUESTIONS"]  =$arQuestions;
		$this->arResult['QUESTIONS']['UF_FULL_ADDRESS']['TITLE'] = 'Адрес';
		$this->arResult['QUESTIONS']['MORNING_COLLEAGUE_NAME']['TITLE'] = 'Имя коллеги (Утро)';
		$this->arResult['QUESTIONS']['MORNING_COLLEAGUE_EMAIL']['TITLE'] = 'Email (Утро)';
		$this->arResult['QUESTIONS']['MORNING_COLLEAGUE_JOB_TITLE']['TITLE'] = 'Должность (Утро)';
		$this->arResult['QUESTIONS']['EVENING_COLLEAGUE_NAME']['TITLE'] = 'Имя коллеги';
		$this->arResult['QUESTIONS']['EVENING_COLLEAGUE_EMAIL']['TITLE'] = 'Email';
		$this->arResult['QUESTIONS']['EVENING_COLLEAGUE_JOB_TITLE']['TITLE'] = 'Должность';
	}

	private function setNavigation(\Bitrix\Main\UI\PageNavigation $nav, $title)
	{
		global $APPLICATION;

		ob_start();
		$APPLICATION->IncludeComponent(
			"bitrix:main.pagenavigation",
			"",
			array(
				"NAV_OBJECT" => $nav,
				"TITLE" => $title,
			)
		);

		return ob_get_clean();
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

	public function deleteMassUser(){
        $request = self::getRequest();
        $obGS = new CLTMGuestStorage();
        $values = $request->get('VALUES');
        if($values){
            $values = explode(',', $values);
            foreach ($values as $value){
                $obGS->deleteUser($value);
            }
        }

    }
}