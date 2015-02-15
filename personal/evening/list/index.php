<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Evening Session list");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user.kabinet.list",
	"evening",
	Array(
		"PATH_TO_KAB" => "/personal/",
		"AUTH_PAGE" => "/personal/login.php",
		"ADMIN_ID" => "1",
		"GROUP_ID" => "13",
		"USER" => "PARTICIP_EV",
		"FORM_ID" => "4"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>