<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Personal page");
?><?$APPLICATION->IncludeComponent(
	"btm:shedul.user",
	"guest",
	Array(
		"PATH_TO_KAB" => "/ru/personal/",
		"GROUP_SENDER_ID" => "6",
		"GROUP_RECIVER_ID" => "4",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "GUEST",
		"APP_ID" => "3",
		"APP_TYPE" => "13",
		"IS_ACTIVE" => "N"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>