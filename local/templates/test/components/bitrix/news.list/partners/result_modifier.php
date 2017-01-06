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
	    if(LANGUAGE_ID != "ru") {
	      $detailText = $arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang];
				$detailTextType =strtolower($detailText["VALUE"]["TYPE"]);
				$value_name = ($detailTextType == "html")?"~VALUE":"VALUE";
				$arItem["DETAIL_TEXT"] = $detailText[$value_name]["TEXT"];
				if($detailTextType != "html") {
					$arItem["DETAIL_TEXT"] =  preg_replace('|\r\n|', '<br>', $arItem["DETAIL_TEXT"]);
					$arItem["DETAIL_TEXT"] =  preg_replace('|\n|', '<br>', $arItem["DETAIL_TEXT"]);
				}

				$previewText = $arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang];
				$previewTextType =strtolower($previewText["VALUE"]["TYPE"]);
				$value_name = ($previewTextType == "html")?"~VALUE":"VALUE";
				$arItem["PREVIEW_TEXT"] = $previewText[$value_name]["TEXT"];
				if($previewTextType != "html") {
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