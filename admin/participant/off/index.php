<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Участники неподтвержденные");
?>
<?$APPLICATION->IncludeComponent(
	"rarus:admin.participant.list",
	"",
	Array(
		"EXHIB_IBLOCK_ID" => "15",
        "CONFIRMED" => "N",
	    "PATH_TO_KAB" => "/admin/",
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>