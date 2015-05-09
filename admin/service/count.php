<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Выставить счет");
?>
<?$APPLICATION->IncludeComponent(
    "rarus:admin.participant.count",
    "",
    Array(
        "PATH_TO_KAB" => "/admin/",
        "USER_ID" => $_REQUEST["uid"],
        "EXHIB_ID" => $_REQUEST["exhib"],
    ),
    false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>