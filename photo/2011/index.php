<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("LTM 2011 PHOTOS");
?>
<h1>LTM 2011 PHOTOS</h1>

<p style="text-align: right;">
    <select onchange="location.href = this.options[this.selectedIndex].value;">
        <option value="/photo/">2013</option>
		<option value="/photo/2012/">2012</option>
        <option selected="" value="#">2011</option>
        <option value="/photo/2010/">2010</option>
        <option value="/photo/2009/">2009</option>
        <option value="/photo/2008/">2008</option>
        <option value="/photo/2007/">2007</option>
    </select>
</p>
<?
$APPLICATION->IncludeComponent(
        "bitrix:photo.section",
        "",
        Array(
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "photo",
            "IBLOCK_ID" => "1",
            "SECTION_ID" => 11,
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
            "PAGER_TITLE" => "����������",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "AJAX_OPTION_SHADOW" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N"
        )
);
?> <? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>