<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
/*$APPLICATION->IncludeComponent(
    "doka:meetings.all.schedule",
    "",
    Array(
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "USER_TYPE" => strtoupper($_REQUEST["type"]),
        "IS_HB" => strtoupper($_REQUEST["hb"]),
        "EXIB_CODE" => $_REQUEST["app"],
        "EMAIL" => $_REQUEST["email"],
        "MESSAGE_LINK" => "/ru/personal/service/write.php",
        "SEND_REQUEST_LINK" => "/ru/personal/service/write.php",
        "CONFIRM_REQUEST_LINK" => "/ru/personal/service/write.php",
        "REJECT_REQUEST_LINK" => "/ru/personal/service/write.php",
        "CUT" => "10",
        "HALL" => "10",
        "TABLE" => "10",
        "FORM_RESULT" => "UF_ID_COMP",
        "FORM_RESULT2" => "UF_ID2"
    ),
    false
);*/

$APPLICATION->IncludeComponent(
    'ds:meetings.all.schedule',
    '',
    Array(
        "IS_HB"                => strtoupper($_REQUEST["hb"]),
        "EXHIBITION_IBLOCK_ID" => 15,
        "EXHIBITION_CODE"      => $_REQUEST["app"],
        "EMAIL"                => $_REQUEST["email"],
        "CUT"                  => "10",
    ),
    false
)
?>
