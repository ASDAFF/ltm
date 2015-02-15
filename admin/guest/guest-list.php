<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent("rarus:admin.guest.list", "", Array(
    "IBLOCK_ID_EXHIB"=>15,
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>