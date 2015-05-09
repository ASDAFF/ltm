<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Registration form for exhibitors and buyers");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "РЕГИСТРАЦИЯ";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "REGISTRATION";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?> 
<?$APPLICATION->IncludeComponent("rarus:register",
	 ".default", 
	array(
		"AUTH" => "N",
		"USE_BACKURL" => "N",
		"SUCCESS_PAGE" => "",
		"EXHIBIT_IBLOCK_ID" => 15,
		"TERMS_LINK" => "/terms/",
		"GUEST_FORM_ID" => "10",
		"COMPANY_FORM_ID" => "3",
		"PARTICIPANT_FORM_ID" => "4",
		"IBLOCK_PHOTO" => "16"

	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>