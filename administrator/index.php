<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;

CModule::IncludeModule('iblock');
?>

<? if ($USER->isAdmin()) {
	if ($_REQUEST["off"]=="Y") {
		CIBlockElement::SetPropertyValuesEx(13909, 24, array("APP_OFF" => 1));
	}
	if ($_REQUEST["off"]=="N") {
		CIBlockElement::SetPropertyValuesEx(13909, 24, array("APP_OFF" => 0));
	}
    LocalRedirect("/administrator/exhibitions/");
?>
    
<?}else{?>

	<?$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"template1",
			Array(
					"COMPONENT_TEMPLATE" => ".default",
					"FORGOT_PASSWORD_URL" => "",
					"PROFILE_URL" => "",
					"REGISTER_URL" => "",
					"SHOW_ERRORS" => "N"
			)
	);?>

<?}?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>