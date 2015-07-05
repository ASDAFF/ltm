<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $APPLICATION;
global $USER;
if(CModule::IncludeModule("iblock")){
	foreach($arResult["ITEMS"] as $key=>$arItem){
		$arResult["ITEMS"][$key]['MOD_PHOTO'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>220, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);         
	}
}	
?>
