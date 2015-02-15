<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Матрица встреч");
?>
<?$APPLICATION->IncludeComponent(
	"btm:appointments.admin",
	"guest",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"GROUP_SENDER_ID" => "6",
		"GROUP_RECIVER_ID" => "4",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "PARTICIP",
		"APP_ID" => "3",
		"APP_TYPE" => "14"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>