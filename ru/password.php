<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Восстановление пароля");
?>
<?$APPLICATION->IncludeComponent(
	"btm:forget.password.ltm",
	"guest",
	Array(
		"REGISTER_URL" => "",
		"GUEST_ID" => "6",
		"PARTICIP_ID" => "4"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>