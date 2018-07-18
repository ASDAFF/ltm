<?
$authUser = $USER->GetID();
if (isset($_REQUEST["id"]) && $_REQUEST["id"] != '' && $authUser == 1) {
    $curUser = $_REQUEST["id"];
} else {
    $curUser = $authUser;
}
if (isset($_REQUEST["app"]) && $_REQUEST["app"] != '' && $authUser == 1) {
    $appId = $_REQUEST["app"];
} else {
    $appId = $appCode;
}
if (isset($_REQUEST["type"]) && $_REQUEST["type"] != '' && $authUser == 1) {
    if ($_REQUEST["type"] == "p") {
        $userType = "PARTICIP";
    } else {
        $userType = "GUEST";
    }
} else {
    $userType = 'GUEST';
}
if ($appId != "") {
    $APPLICATION->IncludeComponent(
        "doka:meetings.schedule",
        "",
        [
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "EXHIB_IBLOCK_ID" => "15",
            "EXIB_CODE" => $exhibCode,
            "APP_ID" => $appId,
            "USER_TYPE" => $userType,
            "USER_ID" => $curUser,
            "MESSAGE_LINK" => "/service/write.php",
            "SEND_REQUEST_LINK" => "/service/appointment.php",
            "CONFIRM_REQUEST_LINK" => "/service/appointment_confirm.php",
            "REJECT_REQUEST_LINK" => "/service/appointment_del.php",
            "CUT" => "9",
            "HALL" => "10",
            "TABLE" => "10",
        ],
        false
    );
    ?>
    <div class="request-guests">
    <?
    $APPLICATION->IncludeComponent(
        'ds:meetings.wishlist',
        '',
        [
            'EHIB_ID' => $appId,
            'EHIB_CODE' => $exhibCode,
            "USER_TYPE" => $userType,
            "USER_ID" => $curUser,
        ]);

    $APPLICATION->IncludeComponent(
        "doka:meetings.wishlist",
        "",
        [
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "EXHIB_IBLOCK_ID" => "15",
            "EXIB_CODE" => $exhibCode,
            "APP_ID" => $appId,
            "USER_TYPE" => $userType,
            "USER_ID" => $curUser,
            "MESSAGE_LINK" => "/cabinet/service/write.php",
        ],
        false
    );
    ?></div><?
} else {
    ?>
    <p>Appointments schedule blocked by the organizers</p>
    <?
}
?>