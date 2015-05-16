<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Участники подтвержденные");
?>
<?$APPLICATION->IncludeComponent(
	"rarus:admin.participant.list",
	"",
	Array(
		"EXHIB_IBLOCK_ID" => "15",
        "CONFIRMED" => "Y",
	    "PATH_TO_KAB" => "/admin/",
	    "ACTIVE" => "Y"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>