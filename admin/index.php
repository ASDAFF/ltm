<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Кабинет администратора");
?><?/*$APPLICATION->IncludeComponent(
	"btm:main.admin",
	"",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
		"GROUP_ID" => "1",
		"GUEST" => "5",
		"GUEST_ACCEPT" => "6",
		"GUEST_EVENING" => "13",
		"GUEST_HB" => "12",
		"PARTICIP" => "3",
		"PARTICIP_ACCEPT" => "4",
		"MESSAGE" => ""
	),
false
);*/?>

<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
?>
<?$APPLICATION->IncludeComponent(
	"rarus:admin.info",
	"",
	Array(
    "PATH_TO_KAB" => "/admin/",
    "AUTH_PAGE" => "/admin/login.php",
    "EXHIB_IBLOCK_ID" => "15",
    "EXHIB_CODE" => $exhibCode,
	),
false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>