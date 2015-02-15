<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "Разработка";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "Development";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE MAIN EXHIBITION IN THE LUXURY TRAVEL INDUSTRY");
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>