<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Гости вечер");
?>
<?$APPLICATION->IncludeComponent(
	"btm:user_accept.admin",
	"guest_ev",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
		"GROUP_ID" => "1",
		"USER_TYPE" => "GUEST",
		"USER_FORMAT" => "EVENING",
		"USER" => "13",
		"USER_ACCEPT" => "5",
		"FORM_ID" => "4",
        "USER_SPAM" => "7",
        "USER_SORT" => "work_company"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>