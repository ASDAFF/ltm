<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$bIblock = CModule::IncludeModule("iblock");

$lang = strtoupper(LANGUAGE_ID);
$sections = array();

if($bIblock)
{
	foreach ($arResult["ITEMS"] as &$arItem)
	{
	    //переписываем поля по выбранному языку
	    if(LANGUAGE_ID != "ru")
	    {
	        $value_name = ($arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";

	        $arItem["DETAIL_TEXT"] = $arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang][$value_name]["TEXT"];
	        $arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];
	    }

	    if(!empty($arItem["PROPERTIES"]["LOGO"]["VALUE"]))
	    {
	        $arItem["PROPERTIES"]["LOGO"] = CFile::GetFileArray($arItem["PROPERTIES"]["LOGO"]["VALUE"]);
	    }
	}
}
?>