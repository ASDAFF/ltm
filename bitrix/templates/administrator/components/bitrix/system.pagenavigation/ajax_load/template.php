<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$hasMore = false;

if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
{
	$strNavQueryString .= "PAGEN_{$arResult["NavNum"]}=".($arResult["NavPageNomer"]+1);
	$hasMore = true;
}

?>
<? if($hasMore):?>
<div class="show-more-wrap">
	<a 
		href="javascript:void(0)" 
		class="show-more-btn"
		data-url="<?= $arResult["sUrlPath"]?>"
		data-query="<?= $strNavQueryString?>"
	><?= GetMessage("NAV_MORE")?></a>
</div>
<? endif;?>