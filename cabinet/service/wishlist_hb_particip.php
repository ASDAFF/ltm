<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист");

global $USER;
$authUser = $USER->GetID();
if (isset($_REQUEST['id']) && $_REQUEST['id'] != '' && $authUser == 1) {
    $curUser = $_REQUEST['id'];
} else {
    $curUser = $authUser;
}

$APPLICATION->IncludeComponent(
    'ds:meetings.wishlist',
    '',
    [
        'EXHIBITION_ID'        => $_REQUEST['app'],
        'EXHIBITION_IBLOCK_ID' => '15',
        'EXHIBITION_CODE'      => $_REQUEST['exhib'],
        "USER_ID"              => $curUser,
        "ADD_LINK_TO_WISHLIST" => 'cabinet/service/wish_hb.php',
        'IS_HB'                => 'Y',
    ]
);
?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>