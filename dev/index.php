<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
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


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

