<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист");
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.wishlist",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"APP_ID" => $_REQUEST["app"],
		"USER_TYPE" => "GUEST",
		"IS_HB" => "Y",
		"USER_ID" => $_REQUEST["id"],
		"EXIB_CODE" => $_REQUEST["exhib"],
		"MESSAGE_LINK" => "/ru/personal/service/write.php",
		"FORM_RESULT" => "UF_ID_COMP",
		"FORM_RESULT2" => "UF_ID"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>