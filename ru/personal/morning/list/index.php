<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Morning Session Guests");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user.kabinet.list",
	"guest",
	Array(
		"PATH_TO_KAB" => "/ru/personal/",
		"AUTH_PAGE" => "/ru/personal/login.php",
		"ADMIN_ID" => "1",
		"GROUP_ID" => "4",
		"USER" => "GUEST",
		"FORM_ID" => "1"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>