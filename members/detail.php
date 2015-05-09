<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$title = "";
if(LANGUAGE_ID == "ru")
{
    $title = "УЧАСТНИКИ";
}
elseif(LANGUAGE_ID == "en")
{
    $title = "PARTICIPANTS";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?>
<?$APPLICATION->IncludeComponent(
	"rarus:members.detail",
	"",
	Array(
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "IBLOCK_ID_EXHIB" => "15",
	    "IBLOCK_ID_PHOTO" => "16",
        "FORM_COMMON_ID" => "3",
	    "FORM_FIELD_ID_NAME" => "17",
	    "FORM_FIELD_ID_LOGIN" => "18",
	    "FORM_FIELD_ID_COUNTRY" => "22",
	    "FORM_FIELD_ID_TEXT" => "24",
	    "FORM_FIELD_ID_SITE_URL" => "23",
        "FORM_FIELD_ID_COMPANY_LOGO" => "100",
        "FORM_FIELD_ID_COMPANY_OFFICIAL_ADDRESS" => "20",
	    "FORM_FIELD_ID_PRIORAREA" => array(25, 26, 27, 28, 29, 30),
	    "MEMBER_CODE_NAME_IN_REQUEST" => "ID",
	    "EXHIBIT_CODE_NAME_IN_REQUEST" => "CODE",
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>