<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Фотографии LTM 2008");
?><h1>Фотографии LTM 2008</h1>
    <p style="text-align:right;">
    <select onchange="location.href = this.options[this.selectedIndex].value;">
        <option value="/ru/photo/">2012</option>
        <option value="/ru/photo/2011">2011</option>
        <option value="/ru/photo/2010/">2010</option>
        <option value="/ru/photo/2009/">2009</option>
        <option value="#" selected>2008</option>
        <option value="/ru/photo/2007/">2007</option>
    </select>
    </p>
<?$APPLICATION->IncludeComponent(
	"bitrix:photo.section",
	"",
	Array(
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "photo",
		"IBLOCK_ID" => "1",
		"SECTION_ID" => 2,
		"SECTION_CODE" => "",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"FILTER_NAME" => "arrFilter",
		"FIELD_CODE" => array(),
		"PROPERTY_CODE" => array(),
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"PAGE_ELEMENT_COUNT" => "35",
		"LINE_ELEMENT_COUNT" => "7",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DISPLAY_PANEL" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Фотографии",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N"
	),
false
);?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>