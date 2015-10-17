<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if (!CModule::IncludeModule("doka.meetings") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
if(!isset($arParams["EXHIB_IBLOCK_ID"]) || $arParams["EXHIB_IBLOCK_ID"] == ''){
	$arParams["EXHIB_IBLOCK_ID"] = 15;
}
$arResult = array();

if(isset($arParams["EXIB_CODE"]) && $arParams["EXIB_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
					"CODE" => $arParams["EXIB_CODE"]
				),
			false,
			false,
			array("ID", "CODE","IBLOCK_ID","PROPERTY_*")
			);
	if($oExhib = $rsExhib->GetNextElement(true, false))
	{
		$arResult["PARAM_EXHIBITION"] = $oExhib->GetFields();
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
		unset($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["MORE_PHOTO"]);
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
		}
		else{
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
		}
		$arParams["APP_ID"] = $appId;
	}
}

if (empty($arParams["APP_ID"])) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}


if (empty($arParams["USER_ID"]))
	$arParams['USER_ID'] = $USER->GetID();

if (!$USER->IsAuthorized() || $arParams['USER_ID'] <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

use Doka\Meetings\Requests as DR;
use Doka\Meetings\Wishlists as DWL;

$req_obj = new DR($arParams['APP_ID']);
$wishlist_obj = new DWL($arParams['APP_ID']);

if(empty($arParams["USER_TYPE"]) && $USER->IsAdmin()){
	$arParams["USER_TYPE"] = $req_obj->getUserTypeById($arParams['USER_ID']);
}
if (empty($arParams["USER_TYPE"])) {
	$arParams["USER_TYPE"] = $req_obj->getUserType();
}
$arResult['USER_TYPE'] = $arParams['USER_TYPE'];
$arResult['USER_ID'] = $arParams['USER_ID'];
$arResult['APP_ID'] = $arParams['APP_ID'];
$fioParticip = '';
$formId = CFormMatrix::getPFormIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);
$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arResult["PARAM_EXHIBITION"]["ID"], 0);//свойство участника
$fio_dates = array();
$fio_dates[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);
$fio_dates[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);//Имя участника
$fio_dates[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);
$fio_dates[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);//Фамилия участника

// Получим все вишлисты
if($arResult['USER_TYPE'] == 'GUEST' && isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf'){
	$wishlists = $wishlist_obj->getWishlistsFull($arParams['USER_ID'], $formId, $propertyNameParticipant, $fio_dates);
}
elseif($arResult['USER_TYPE'] == 'PARTICIP'){
	$wishlists = $wishlist_obj->getWishlists($arParams['USER_ID']);
}
else{
	$senderType = $req_obj->getUserTypeById($arParams['USER_ID']);
	if($senderType == 'GUEST' && isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf'){
		$wishlists = $wishlist_obj->getWishlistsFull($arParams['USER_ID'], $formId, $propertyNameParticipant, $fio_dates);
	}
	else{
		$wishlists = $wishlist_obj->getWishlists($arParams['USER_ID']);
	}
}
$arResult['WISH_IN'] = $wishlists['WISH_IN']; // с кем хочет встретиться
$arResult['WISH_OUT'] = $wishlists['WISH_OUT'];
$arResult['CITY'] = "";

if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf'){
	$rsUser = CUser::GetByID($arParams['USER_ID']);
	$thisUser = $rsUser->Fetch();
	$arAnswer = CFormResult::GetDataByID($thisUser[$propertyNameParticipant], array(), $arTmpResult, $arAnswer2);
	if($arResult['USER_TYPE'] == "PARTICIP"){
		$fioParticip = $arAnswer2[$fio_dates[0][0]][$fio_dates[0][1]]["USER_TEXT"]." ".$arAnswer2[$fio_dates[1][0]][$fio_dates[1][1]]["USER_TEXT"];
	}
	else{
		foreach($arAnswer2["SIMPLE_QUESTION_672"] as $value){
			$arResult['CITY'] = $value["USER_TEXT"];
		}
	}
}
if ( isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'pdf' ) {
	require(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/tcpdf.php');
	require_once(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/templates/wishlist_' . $arParams['USER_TYPE'] . '.php');

	$APPLICATION->RestartBuffer();
	$arResult['EXHIBITION'] = $req_obj->getOptions();
	// Информация о пользователе, для которого генерируем pdf
    $filter = array( 'ID' => $arParams['USER_ID'] );
    $select = array(
        'SELECT' => array($req_obj->getOption('REPR_PROP_CODE')),
        'FIELDS' => array('WORK_COMPANY', 'ID')
    );
    $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
    if ($arUser = $rsUser->Fetch()) {
        if($fioParticip == ''){
			$fioParticip = $arUser[$req_obj->getOption('REPR_PROP_CODE')];
    	}
        $arResult['USER'] = array(
        	'REP' => $fioParticip,
        	'COMPANY' => $arUser['WORK_COMPANY'],
        	'CITY' => $arResult['CITY'],
        );
		DokaGeneratePdf($arResult);
    }
}

$arResult['COMPANIES']=array();
$arGroups = $USER->GetUserGroupArray();
if (in_array($req_obj->getOption('GUESTS_GROUP'), $arGroups) || (isset($_REQUEST["type"]) && $_REQUEST["type"] == "g"))
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
else
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
// Соберем список компаний, у которых заняты все таймслоты
$rsCompanies = $req_obj->getBusyCompanies($group_search_id);
while ($data = $rsCompanies->Fetch()) {
	$arResult['COMPANIES'][] = $data;
}
$this->IncludeComponentTemplate();
?>