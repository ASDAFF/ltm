<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $APPLICATION;
global $USER;

$bIblock = CModule::IncludeModule("iblock");

$lang = strtoupper(LANGUAGE_ID);
$sections = array();

if($bIblock)
{
	foreach($arResult["ITEMS"] as $key=>&$arItem){
		$arResult["ITEMS"][$key]['MOD_PHOTO'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>220, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);

		//переписываем поля по выбранному языку
		if(LANGUAGE_ID != "ru")
		{
		    $value_name = ($arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";
	        $arItem["DETAIL_TEXT"] = $arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang][$value_name]["TEXT"];

	        $value_name = ($arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";
		    $arItem["PREVIEW_TEXT"] = $arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang][$value_name]["TEXT"];

		    $arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];
		}

	}
}
?>
