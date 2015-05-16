<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Write a message");
?>
<?$APPLICATION->IncludeComponent(
	"rarus:admin.messages.write",
	"",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
		"GROUP_ID" => "1",
		"GUEST" => "6",
		"GUEST_HB" => "12",
		"PARTICIP" => "4",
		"MESSAGE" => ""
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>