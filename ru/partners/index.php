<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнеры");
?> 
<h1>Партнеры</h1>
 <?$APPLICATION->IncludeComponent(
	"btm:sponsors",
	"sponsors",
	Array(
		"IBLOCK_TYPE" => "sponsors",
		"IBLOCK_ID" => "5",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"SECTION_URL" => "",
		"COUNT_ELEMENTS" => "Y",
		"TOP_DEPTH" => "2",
		"SECTION_FIELDS" => array(),
		"SECTION_USER_FIELDS" => array(),
		"ADD_SECTIONS_CHAIN" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => "Y"
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>