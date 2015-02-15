<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Выставить счет");
?>

<?$APPLICATION->IncludeComponent(
    "rarus:admin.participant.transfer",
    "",
    Array(
        "PATH_TO_KAB" => "/admin/",
        "USER_ID" => $_REQUEST["uid"],
        "TYPE" => "PARTICIPANT",
        "EXHIB_IBLOCK_ID" => "15",
    ),
    false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>