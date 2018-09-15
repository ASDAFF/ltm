<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
if (isset($_REQUEST["app"]) && $_REQUEST["app"] != '') {
    $appId = $_REQUEST["app"];
} else {
    $appId = 1;
}
?>
<?
$APPLICATION->IncludeComponent(
    "ds:meetings.request.confirm",
    "",
    Array(
        "APP_ID"               => $appId,
        "EXHIBITION_IBLOCK_ID" => 15,
        "IS_HB"                => true,
    ),
    false
);
?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>