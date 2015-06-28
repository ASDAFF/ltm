<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $APPLICATION;
global $USER;

CModule::IncludeModule("iblock");
	
foreach($arResult["ITEMS"] as $key=>$arItem){
	if($arItem['PROPERTIES']['FOR_E']['VALUE'] == 'Y'){
		$arResult["ITEMS"][$key] = $arItem;
	}else{
		$arResult["ITEMS"][$key] = '';
	}
}
$arResult["ITEMS"] = array_diff($arResult["ITEMS"], array(''));
		
?>
