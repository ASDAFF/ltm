<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��� ����");
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.wishlist",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"APP_ID" => $_REQUEST["app"],
		"USER_TYPE" => "PARTICIP",
		"USER_ID" => $_REQUEST["id"],
		"EXIB_CODE" => $_REQUEST["exhib"],
		"MESSAGE_LINK" => "/ru/personal/service/write.php",
		"IS_HB" => "Y",
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>