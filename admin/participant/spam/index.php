<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ó÷àñòíèêè ÑÏÀÌ");
?>
<?$APPLICATION->IncludeComponent(
	"rarus:admin.participant.list",
	"",
	Array(
		"EXHIB_IBLOCK_ID" => "15",
        "CONFIRMED" => "N",
	    "PATH_TO_KAB" => "/admin/",
	    "SPAM" => "Y"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>