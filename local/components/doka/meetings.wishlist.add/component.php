<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arParams["APP_ID"]) || !CModule::IncludeModule("doka.meetings") ) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

if (!$USER->IsAuthorized()) {
	ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
	return;
}

$receiver_id = intval($arParams["TO"]);

if ($receiver_id <= 0) {
	ShowError(GetMessage("ERROR_WRONG_RECEIVER_ID"));
	return;
}

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Wishlists as DWL;

$req_obj = new DokaRequest($arParams['APP_ID']);

$arResult = array();
$arResult['USER_TYPE'] = $req_obj->getUserType();

if (isset($arParams['USER_ID']))
	$sender_id = intval($arParams['USER_ID']);
else
	$sender_id = $USER->GetID();

// Р”РѕР±Р°РІР»СЏРµРј РєРѕРјРїР°РЅРёСЋ РІ РІРёС€Р»РёСЃС‚
$wish_obj = new DWL($arParams['APP_ID']);
$fields = array(
	'REASON' => DWL::REASON_SELECTED,
	'SENDER_ID' => $sender_id,
	'RECEIVER_ID' => $receiver_id
);
$wish_obj->Add($fields);

//var_dump($arResult);
$this->IncludeComponentTemplate();
?>