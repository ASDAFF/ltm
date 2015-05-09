<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?$APPLICATION->IncludeComponent(
    "rarus:admin.participant.count",
    "",
    Array(
        "PATH_TO_KAB" => "/admin/",
        "USER_ID" => $_REQUEST["uid"],
        "EXHIB_ID" => $_REQUEST["exhib"],
    ),
    false
);
?>