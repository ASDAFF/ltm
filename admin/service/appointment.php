<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("���������� ������");
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.request.send",
	"",
	Array(
		"APP_ID" => $_REQUEST["app"]
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>