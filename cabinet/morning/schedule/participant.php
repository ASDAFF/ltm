<?
$authUser = $USER->GetID();
if (isset($_REQUEST["UID"]) && $_REQUEST["UID"] != '' && $authUser == 1) {
    $curUser = $_REQUEST["UID"];
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
    $userType = 'PARTICIP';
}
if ($appId != "") {
    $APPLICATION->IncludeComponent(
        "ds:meetings.schedule",
        "",
        [
            "CACHE_TYPE"           => "A",
            "CACHE_TIME"           => "3600",
            "EXHIBITION_IBLOCK_ID" => "15",
            "MESSAGE_LINK"         => "/service/write.php",
            "SEND_REQUEST_LINK"    => "/service/appointment.php",
            "CONFIRM_REQUEST_LINK" => "/service/appointment_confirm.php",
            "REJECT_REQUEST_LINK"  => "/service/appointment_del.php",
            "RESERVE_REQUEST_LINK" => "/ajax/time_reserve.php",
            "USER_TYPE"            => 'PARTICIPANT',
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
            'EXHIBITION_CODE'      => $exhibCode,
            "EXHIBITION_IBLOCK_ID" => "15",
            'USER_ID'              => $curUser,
            'ADD_LINK_TO_WISHLIST' => '/cabinet/service/wish.php',
            'IS_HB'                => 'Y',
        ]);
    ?>
    </div><?
} else {
    ?>
    <p>Appointments schedule blocked by the organizers</p>
    <?
}
?>
