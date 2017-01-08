<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Бронирование таймслота");
if(isset($_REQUEST["app"]) && $_REQUEST["app"]!=''){
	$appId = $_REQUEST["app"];
}
else{
	$appId=1;
}
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.time.reserve",
	"admin",
	Array(
		"APP_ID" => $appId,
		"TIME" => $_REQUEST['time']
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>