<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$APPLICATION->IncludeComponent(
    'ds:meetings.all.schedule',
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
