<?
set_time_limit(0);
ignore_user_abort(true);
session_write_close();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист");
?>
<?$APPLICATION->IncludeComponent(
	"doka:meetings.all.wishlist",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"USER_TYPE" => strtoupper($_REQUEST["type"]),
		"IS_HB" => strtoupper($_REQUEST["hb"]),
		"EXIB_CODE" => $_REQUEST["app"],
		"EMAIL" => $_REQUEST["email"],
		"MESSAGE_LINK" => "/ru/personal/service/write.php",
		"FORM_RESULT" => "UF_ID_COMP",
		"FORM_RESULT2" => "UF_ID"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>