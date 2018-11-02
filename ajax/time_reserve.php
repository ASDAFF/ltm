<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if (isset($_REQUEST["app"]) && $_REQUEST["app"] != '') {
    $appId = $_REQUEST["app"];
} else {
    $appId = 1;
}
?>
<?
$APPLICATION->IncludeComponent(
    "ds:meetings.time.reserve",
    "",
    Array(
        "APP_ID"               => $appId,
        "EXHIBITION_IBLOCK_ID" => 15,
        "IS_HB"                => $_REQUEST['is_hb'],
    ),
    false
);
?>