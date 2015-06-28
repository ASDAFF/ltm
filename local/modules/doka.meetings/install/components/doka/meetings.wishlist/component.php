<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (empty($arParams["USER_TYPE"])) {
	ShowError(GetMessage("ERROR_EMPTY_USER_TYPE"));
	return;
}

if (empty($arParams["USER_ID"]))
	$arParams['USER_ID'] = $USER->GetID();

if (!$USER->IsAuthorized() || $arParams['USER_ID'] <= 0) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

$arResult = array();
$arResult['USER_TYPE'] = $arParams['USER_TYPE'];

use Doka\Meetings\Requests as DR;
use Doka\Meetings\Wishlists as DWL;

$req_obj = new DR($arParams['APP_ID']);
$wishlist_obj = new DWL($arParams['APP_ID']);

// Получим все вишлисты
$wishlists = $wishlist_obj->getWishlists($arParams['USER_ID']);
$arResult['WISH_IN'] = $wishlists['WISH_IN']; // с кем хочет встретиться
$arResult['WISH_OUT'] = $wishlists['WISH_OUT'];

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
        $arResult['USER'] = array(
        	'REP' => $arUser[$req_obj->getOption('REPR_PROP_CODE')],
        	'COMPANY' => $arUser['WORK_COMPANY'],
        	'CITY' => 'CITY',
        );
		DokaGeneratePdf($arResult);
    }
}


// Соберем список компаний, у которых заняты все таймслоты
$rsCompanies = $req_obj->getBusyCompanies();
while ($data = $rsCompanies->Fetch()) {
	$arResult['COMPANIES'][] = $data;
}

$this->IncludeComponentTemplate();
?>