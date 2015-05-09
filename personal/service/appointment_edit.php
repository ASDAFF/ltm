<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
?><?$APPLICATION->IncludeComponent(
	"btm:appointment.edit.user",
	"particip",
	Array(
		"PATH_TO_KAB" => "/personal/",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "PARTICIP",
		"APP_ELEMENT" => $_REQUEST["meet_id"],
		"APP_ACTION" => $_REQUEST["meetact"],
		"APP_ID" => "3",
		"APP_DECLINE" => "14",
		"APP_ACCEPT" => "15",
		"GROUP_DECLINE" => "9",
		"GROUP_ACCEPT" => "10",
		"IS_ACTIVE" => "N"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>