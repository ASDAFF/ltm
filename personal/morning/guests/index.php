<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Morning Session Guests");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user.kabinet.list",
	"",
	Array(
		"PATH_TO_KAB" => "/personal/",
		"AUTH_PAGE" => "/personal/login.php",
		"ADMIN_ID" => "1",
		"GROUP_ID" => "6",
		"USER" => "PARTICIP",
		"FORM_ID" => "4"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>