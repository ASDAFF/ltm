<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
?>
 <?$APPLICATION->IncludeComponent(
	"doka:meetings.request.reject",
	"",
	Array(
		"APP_ID" => $_REQUEST["app"],
		"IS_HB" => "Y",
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>