<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Загрузка фотографий");
?>
<?
$APPLICATION->IncludeComponent(
    "luxor:photo.load",
    "",
    Array(
        "IBLOCK_ID" => 23,
        "IBLOCK_TYPE" => "photo",
    ),
    false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>