<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent("rarus:admin.guest.list", "", Array(
    "IBLOCK_ID_EXHIB"=>15,
	"HLBLOCK_GUEST_ID" => 15,
	"HLBLOCK_GUEST_COLLEAGUES_ID" => 18,
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>