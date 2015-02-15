<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Гости неподтвержденные");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user_off.admin",
	"guest",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
		"GROUP_ID" => "1",
		"USER_TYPE" => "GUEST",
		"USER" => "5",
		"USER_ACCEPT" => "6",
		"USER_EVENING" => "13",
		"USER_HB" => "12",
		"FORM_ID" => "4",
        "USER_SPAM" => "7"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>