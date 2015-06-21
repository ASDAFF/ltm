<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

$APPLICATION->IncludeComponent(
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
);?>
