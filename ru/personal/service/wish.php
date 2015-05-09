<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Добавление в wish-лист");
?>
<?$APPLICATION->IncludeComponent(
	"btm:wish.list.user",
	"particip",
	Array(
		"PATH_TO_KAB" => "/personal/",
		"GROUP_SENDER_ID" => "6",
		"GROUP_RECIVER_ID" => "4",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "GUEST",
		"USER" => $_REQUEST["id"],
		"WISH_TYPE" => $_REQUEST["wish"],
		"IS_ACTIVE" => "Y"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>