<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bIblock = CModule::IncludeModule("iblock");
$lang = strtoupper(LANGUAGE_ID);

if($bIblock)
{
	foreach ($arResult["ITEMS"] as &$arItem)
	{
	    if(isset($arItem["PROPERTIES"]["MORE_PHOTO"]) && !empty($arItem["PROPERTIES"]["MORE_PHOTO"]))
	    {
	        //переписываем поля по выбранному языку
	        if(LANGUAGE_ID != "ru")
	        {
	            $value_name = ($arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";
    	        $arItem["DETAIL_TEXT"] = $arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang][$value_name]["TEXT"];

    	        $value_name = ($arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";
    	        $arItem["PREVIEW_TEXT"] = $arItem["PROPERTIES"]["PREVIEW_TEXT_" . $lang]["VALUE"]["TEXT"];

	            $arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];
	        }

	    	foreach ($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as &$photo)
	    	{
	    	    $small = CFile::ResizeImageGet($photo, array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	    	    $preview = CFile::ResizeImageGet($photo, array('width'=>340, 'height'=>266), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	    	    $big = CFile::ResizeImageGet($photo, array('width'=>900, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	    	    $full = CFile::GetFileArray($photo);
	    	    $photo = array();
	    	    $photo["SMALL"] = $small;
	    	    $photo["BIG"] = $big;
	    	    $photo["FULL"] = $full;
	    	    $photo["PREVIEW"] = $preview;
	    	}
	    }
	}
}
?>