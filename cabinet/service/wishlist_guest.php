<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист");

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
$APPLICATION->IncludeComponent(
    'ds:meetings.wishlist',
    '',
    [
        'EXHIBITION_ID' => $_REQUEST["app"],
        'EXHIBITION_CODE' => $_REQUEST["exhib"],
        "USER_TYPE" => $userType,
        "USER_ID" => $curUser,
        "ADD_LINK_TO_WISHLIST" => "cabinet/service/wish.php",
    ]);
?>
<? //$APPLICATION->IncludeComponent(
//	"doka:meetings.wishlist",
//	"",
//	Array(
//		"CACHE_TYPE" => "A",
//		"CACHE_TIME" => "3600",
//		"APP_ID" => $_REQUEST["app"],
//		"USER_TYPE" => "GUEST",
//		"USER_ID" => $_REQUEST["id"],
//		"EXIB_CODE" => $_REQUEST["exhib"],
//		"MESSAGE_LINK" => "/ru/personal/service/write.php",
//		"FORM_RESULT" => "UF_ID_COMP",
//		"FORM_RESULT2" => "UF_ID"
//	),
//false
//);?><!-- -->
<? //require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>