<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$lang = strtoupper(LANGUAGE_ID);
$bIblock = CModule::IncludeModule("iblock");

$sections = array();

if($bIblock)
{
	foreach ($arResult["ITEMS"] as $k=>&$arItem)
	{

	    //переписываем поля по выбранному языку
	    if(LANGUAGE_ID != "ru")
	    {
	        $value_name = (strtolower($arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang]["VALUE"]["TYPE"]) == "html")?"~VALUE":"VALUE";
	        $arItem["DETAIL_TEXT"] = $arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang][$value_name]["TEXT"];

	        $value_name = (strtolower($arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang]["VALUE"]["TYPE"]) == "html")?"~VALUE":"VALUE";
	        $arItem["PREVIEW_TEXT"] = $arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang][$value_name]["TEXT"];
            if(strtolower($arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang]["VALUE"]["TYPE"]) != "html") {
                $arItem["PREVIEW_TEXT"] =  preg_replace('|\r\n|', '<br>', $arItem["PREVIEW_TEXT"]);
                $arItem["PREVIEW_TEXT"] =  preg_replace('|\n|', '<br>', $arItem["PREVIEW_TEXT"]);
            }
	    }


	    if(!empty($arItem["PROPERTIES"]["HTTP"]["VALUE"]) && substr($arItem["PROPERTIES"]["HTTP"]["VALUE"], 0 , 4) != "http")
	    {
	        $arItem["PROPERTIES"]["HTTP"]["VALUE"] = "http://" . $arItem["PROPERTIES"]["HTTP"]["VALUE"];
	    }
		
		$arResult["ITEMS"][$k]['PICT_MOD'] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"]['ID'], array('width'=>221, 'height'=>99999), BX_RESIZE_IMAGE_PROPORTIONAL, true);          


	}
}
?>