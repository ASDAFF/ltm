<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$lang = strtoupper(LANGUAGE_ID);
$bIblock = CModule::IncludeModule("iblock");

$sections = array();

if($bIblock)
{

	   //переписываем поля по выбранному языку
	    if(LANGUAGE_ID != "ru")
	    {
	        $value_name = ($arResult["PROPERTIES"]["DETAIL_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";
	        $arResult["DETAIL_TEXT"] = $arResult["PROPERTIES"]["DETAIL_TEXT_" . $lang][$value_name]["TEXT"];

	        $value_name = ($arResult["PROPERTIES"]["PREVIEW_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";
	        $arResult["PREVIEW_TEXT"] = $arResult["PROPERTIES"]["PREVIEW_TEXT_" . $lang][$value_name]["TEXT"];

	        if (in_array('PREVIEW_TEXT',$arParams['DETAIL_FIELD_CODE']))
	        {
	        	$arResult["FIELDS"]["PREVIEW_TEXT"] = $arResult["PREVIEW_TEXT"] ;
	        }
	        if (in_array('DETAIL_TEXT',$arParams['DETAIL_FIELD_CODE']))
	        {
	        	$arResult["FIELDS"]["DETAIL_TEXT"] = $arResult["DETAIL_TEXT"] ;
	        }

	    }


	    if(!empty($arResult["PROPERTIES"]["HTTP"]["VALUE"]) && substr($arResult["PROPERTIES"]["HTTP"]["VALUE"], 0 , 4) != "http")
	    {
	        $arResult["PROPERTIES"]["HTTP"]["VALUE"] = "http://" . $arResult["PROPERTIES"]["HTTP"]["VALUE"];
	    }
		
		$arResult['PICT_MOD'] = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"]['ID'], array('width'=>221, 'height'=>99999), BX_RESIZE_IMAGE_PROPORTIONAL, true);


}

?>