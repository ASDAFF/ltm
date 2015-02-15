<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Участники Матрица");
$close = true;
$appCode= 1;
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.matrix",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_NOTES" => "",
		"EXHIB_IBLOCK_ID" => "15",
		"EXIB_CODE" => $exhibCode,
		"APP_ID" => $appCode,
		"USER_TYPE" => "PARTICIP"
	),
false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>