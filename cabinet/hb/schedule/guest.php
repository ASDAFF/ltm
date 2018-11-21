<?
$authUser = $USER->GetID();
if (isset($_REQUEST["id"]) && $_REQUEST["id"] != '' && $authUser == 1) {
    $curUser = $_REQUEST["id"];
} else {
    $curUser = $authUser;
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

$APPLICATION->IncludeComponent(
    "ds:meetings.schedule",
    "",
    [
        "CACHE_TYPE"           => "A",
        "CACHE_TIME"           => "3600",
        "EXHIBITION_IBLOCK_ID" => "15",
        "MESSAGE_LINK"         => "/service/write.php",
        "SEND_REQUEST_LINK"    => "/service/appointment_hb.php",
        "CONFIRM_REQUEST_LINK" => "/service/appointment_hb_confirm.php",
        "REJECT_REQUEST_LINK"  => "/service/appointment_hb_del.php",
        "RESERVE_REQUEST_LINK" => "/ajax/time_reserve.php",
        "USER_TYPE"            => 'GUEST',
        "IS_HB"                => 'Y',
    ],
    false
); ?>
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
</div>
