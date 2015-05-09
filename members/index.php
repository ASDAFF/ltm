<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "List of participants fo Luxury Travel Mart");
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
<? 
//редиректим на ближайшую выставку
if($APPLICATION->GetCurDir() == "/members/" && CModule::IncludeModule("iblock"))
{
	$rsElements = CIBlockElement::GetList(
		array("sort"=>"asc"), 
		array(
			"IBLOCK_ID" => 15, 
			"ACTIVE" => "Y"
		),
		false,
		array("nTopCount" => 1),
		array("ID", "CODE")
	);
	if($arExhib = $rsElements->Fetch())
	{
		LocalRedirect("/members/{$arExhib["CODE"]}/");
	}
}
?>
<?$APPLICATION->IncludeComponent(
	"rarus:members.list",
	"",
	Array(
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "IBLOCK_ID_EXHIB" => "15",
        "FORM_COMMON_ID" => "3",
	    "FORM_FIELD_ID_NAME" => "17",
	    "FORM_FIELD_ID_LOGIN" => "18",
	    "FORM_FIELD_ID_COUNTRY" => "22",
	    "FORM_FIELD_ID_PRIORAREA" => array(25, 26, 27, 28, 29, 30),
	    "EXHIBIT_CODE_NAME_IN_REQUEST" => "CODE",
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>