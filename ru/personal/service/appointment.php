<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
?><?$APPLICATION->IncludeComponent(
	"btm:appointment.user",
	"particip",
	Array(
		"PATH_TO_KAB" => "/ru/personal/",
		"GROUP_SENDER_ID" => "6",
		"GROUP_RECIVER_ID" => "4",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "GUEST",
		"USER" => $_REQUEST["id"],
		"TIME" => $_REQUEST["time"],
		"APP_ID" => "3",
		"APP_TYPE" => "13",
		"IS_ACTIVE" => "N"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>