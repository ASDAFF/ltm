<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Гости Hosted Buyers");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user_accept.admin",
	"hb",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
		"GROUP_ID" => "1",
		"USER_TYPE" => "GUEST",
		"USER_FORMAT" => "HB",
		"USER" => "12",
		"USER_ACCEPT" => "5",
		"FORM_ID" => "4",
        "USER_SPAM" => "7",
        "USER_SORT" => "work_company"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>