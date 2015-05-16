<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
?>
<?


$APPLICATION->IncludeComponent(
	"doka:meetings.schedule",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"APP_ID" => $_REQUEST["app"],
		"USER_TYPE" => "GUEST",
		"USER_ID" => $_REQUEST["id"],
		"EXIB_CODE" => $_REQUEST["exhib"],
		"MESSAGE_LINK" => "/ru/personal/service/write.php",
		"SEND_REQUEST_LINK" => "/ru/personal/service/write.php",
		"CONFIRM_REQUEST_LINK" => "/ru/personal/service/write.php",
		"REJECT_REQUEST_LINK" => "/ru/personal/service/write.php",
		"CUT" => "10",
		"HALL" => "10",
		"TABLE" => "10",
		"FORM_RESULT" => "UF_ID_COMP",
		"FORM_RESULT2" => "UF_ID2"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>