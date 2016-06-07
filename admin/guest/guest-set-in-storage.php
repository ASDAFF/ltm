<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Main\Loader;

$obGS = new CLTMGuestStorage();
if(isset($_REQUEST["USER_ID"])){
	$obGS->putInStorage($_REQUEST["USER_ID"]);
}elseif(isset($_REQUEST["ALL_USERS"]) && $_REQUEST["ALL_USERS"] == 'Y' && $_REQUEST["ACT"] && $_REQUEST["EXHIB_ID"]){

	//Получаем список пользователей для текущей выставки и формы участия
	Loader::includeModule('iblock');

	$rsExhib = CIBlockElement::GetList(
		array(),
		array(
			'ID' => $_REQUEST["EXHIB_ID"],
			'IBLOCK_ID' => 15
		),
		false,
		array('nTopCount' => 1),
		array('ID', 'IBLOCK_ID', 'PROPERTY_UC_GUESTS_GROUP', 'PROPERTY_GUEST_SPAM_GROUP', 'PROPERTY_C_GUESTS_GROUP')
	);

	if($arExhib = $rsExhib->Fetch()){
		$arUserFilter = array("ACTIVE" => "Y");
		switch($_REQUEST["ACT"]){
			case "morning":
				$arUserFilter["UF_MR"] = true;
				$arUserFilter['GROUPS_ID'] = $arExhib['PROPERTY_C_GUESTS_GROUP_VALUE'];
				break;
			case "evening":
				$arUserFilter["UF_EV"] = true;
				$arUserFilter['GROUPS_ID'] = $arExhib['PROPERTY_C_GUESTS_GROUP_VALUE'];
				break;
			case "hostbuy":
				$arUserFilter["UF_HB"] = true;
				$arUserFilter['GROUPS_ID'] = $arExhib['PROPERTY_C_GUESTS_GROUP_VALUE'];
				break;
			case "spam":
				$arUserFilter['GROUPS_ID'] = $arExhib['PROPERTY_GUEST_SPAM_GROUP_VALUE'];
				break;
			case "off":
				$arUserFilter['GROUPS_ID'] = $arExhib['PROPERTY_UC_GUESTS_GROUP_VALUE'];
				break;
		}
		$userFieldFormAnswer = CFormMatrix::getPropertyIDByExh($_REQUEST["EXHIB_ID"]);
		$arUserFilter["!$userFieldFormAnswer"] = false;

		$arUsers = array();
		//Переносим пользователей
		$rsUsers = CUser::GetList(
			$by = 'id',
			$order = 'asc',
			$arUserFilter,
			array(
				"FIELDS" => array('ID')
			)
		);
		while($arUser = $rsUsers->Fetch()){
			$arUsers[] = $arUser['ID'];
		}

		foreach($arUsers as $userId){
			$obGS->putInStorage($userId);
		}
	}


}