<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("���������� ������");
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.request.confirm",
	"",
	Array(
		"APP_ID" => $_REQUEST["app"],
		"IS_HB" => "Y"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>