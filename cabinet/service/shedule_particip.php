<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
?>
<?
$APPLICATION->IncludeComponent(
    "ds:meetings.schedule",
    "",
    [
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "EXHIBITION_IBLOCK_ID" => "15",
        "MESSAGE_LINK" => "/service/write.php",
        "SEND_REQUEST_LINK" => "/service/appointment.php",
        "CONFIRM_REQUEST_LINK" => "/service/appointment_confirm.php",
        "REJECT_REQUEST_LINK" => "/service/appointment_del.php",
        "RESERVE_REQUEST_LINK" => "/ajax/time_reserve.php",
        "USER_TYPE" => 'PARTICIPANT'
    ],
    false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>