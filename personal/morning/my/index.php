<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("My schedule for the Morning Session");
?><?$APPLICATION->IncludeComponent(
	"btm:shedul.user",
	"particip",
	Array(
		"PATH_TO_KAB" => "/personal/",
		"GROUP_SENDER_ID" => "4",
		"GROUP_RECIVER_ID" => "6",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "PARTICIP",
		"APP_ID" => "3",
		"APP_TYPE" => "13",
		"IS_ACTIVE" => "N"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>