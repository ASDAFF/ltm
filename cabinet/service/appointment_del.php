<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
?>
<?
$APPLICATION->IncludeComponent(
    "ds:meetings.request.reject",
    "",
    Array(
        "APP_ID"               => $_REQUEST["app"],
        "EXHIBITION_IBLOCK_ID" => 15,
        "IS_HB"                => false,
    ),
    false
);
?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>