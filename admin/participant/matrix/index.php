<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Участники Матрица");
$close = true;
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
?>
<? $APPLICATION->IncludeComponent(
    "ds:meetings.matrix",
    "",
    Array(
        "CACHE_TYPE"           => "A",
        "CACHE_TIME"           => "3600",
        "CACHE_NOTES"          => "",
        "EXHIBITION_IBLOCK_ID" => "15",
        "EXHIBITION_CODE"      => $exhibCode,
        "USER_TYPE"            => "PARTICIPANT",
        "USERS_COUNT_PER_PAGE" => 30,
    ),
    false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>