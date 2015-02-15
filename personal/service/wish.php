<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Добавление в wish-лист");
?>
<?$APPLICATION->IncludeComponent(
	"btm:wish.list.user",
	"particip",
	Array(
		"PATH_TO_KAB" => "/personal/",
		"GROUP_SENDER_ID" => "4",
		"GROUP_RECIVER_ID" => "6",
		"ADMIN_ID" => "1",
		"USER_TYPE" => "PARTICIP",
		"USER" => $_REQUEST["id"],
		"WISH_TYPE" => $_REQUEST["wish"],
		"IS_ACTIVE" => "Y"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>