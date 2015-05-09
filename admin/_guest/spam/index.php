<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Спам");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user_off.admin",
	"particip_spam",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
		"GROUP_ID" => "1",
		"USER_TYPE" => "PARTICIP",
		"USER" => "7",
		"USER_ACCEPT" => "5",
		"FORM_ID" => "4",
        "USER_SPAM" => "7",
        "IS_SPAM" => "Y"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>