<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист");
?>
<?
$APPLICATION->IncludeComponent(
    'ds:meetings.all.wishlist',
    '',
    Array(
        "IS_HB"                => strtoupper($_REQUEST["hb"]),
        "EXHIBITION_IBLOCK_ID" => 15,
        "EXHIBITION_CODE"      => $_REQUEST["app"],
        "EMAIL"                => $_REQUEST["email"],
    ),
    false
)
?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>