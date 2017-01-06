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
				$detailText = $arResult["PROPERTIES"]["DETAIL_TEXT_" . $lang];
				$detailTextType =strtolower($detailText["VALUE"]["TYPE"]);
				$value_name = ($detailTextType == "html")?"~VALUE":"VALUE";
				$arResult["DETAIL_TEXT"] = $detailText[$value_name]["TEXT"];
				if($detailTextType != "html") {
					$arResult["DETAIL_TEXT"] =  preg_replace('|\r\n|', '<br>', $arResult["DETAIL_TEXT"]);
					$arResult["DETAIL_TEXT"] =  preg_replace('|\n|', '<br>', $arResult["DETAIL_TEXT"]);
				}

				$previewText = $arResult["PROPERTIES"]["PREVIEW_TEXT_" . $lang];
				$previewTextType =strtolower($previewText["VALUE"]["TYPE"]);
				$value_name = ($previewTextType == "html")?"~VALUE":"VALUE";
				$arResult["PREVIEW_TEXT"] = $previewText[$value_name]["TEXT"];
				if($previewTextType != "html") {
					$arResult["PREVIEW_TEXT"] =  preg_replace('|\r\n|', '<br>', $arResult["PREVIEW_TEXT"]);
					$arResult["PREVIEW_TEXT"] =  preg_replace('|\n|', '<br>', $arResult["PREVIEW_TEXT"]);
				}

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