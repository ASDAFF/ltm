<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if($this->StartResultCache(false, array($arNavigation)))
{

	$this->SetResultCacheKeys(array(
		"ID",
		"IBLOCK_ID",
		"NAV_CACHED_DATA",
		"NAME",
		"IBLOCK_SECTION_ID",
		"IBLOCK",
		"LIST_PAGE_URL", 
		"~LIST_PAGE_URL",
		"SECTION",
		"PROPERTIES",
	));

	$this->IncludeComponentTemplate();

	if (empty($arResult["ITEM"])) {
		$this->AbortResultCache();
		ShowError("404 Not Found");
		@define("ERROR_404", "Y");
		CHTTP::SetStatus("404 Not Found");
	}	

}

?>