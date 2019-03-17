<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Бронирование таймслота");
if (isset($_REQUEST["app"]) && $_REQUEST["app"] != '') {
    $appId = $_REQUEST["app"];
} else {
    $appId = 1;
}
?>
<?
$APPLICATION->IncludeComponent(
    "ds:meetings.time.reserve",
    "admin",
    Array(
        "APP_ID"               => $appId,
        "EXHIBITION_IBLOCK_ID" => 15,
    ),
    false
);
?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>