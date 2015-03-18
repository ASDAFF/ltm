<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bIblock = CModule::IncludeModule("iblock");
$lang = strtoupper(LANGUAGE_ID);

$arResizeImages = array(
    "0" => array('width' => 230, 'height' => 176),
    "1" => array('width' => 110, 'height' => 84),
    "2" => array('width' => 110, 'height' => 84),
    "3" => array('width' => 230, 'height' => 176),
    "4" => array('width' => 110, 'height' => 84),
    "5" => array('width' => 110, 'height' => 84),
    "6" => array('width' => 160, 'height' => 266),
);

if($bIblock)
{
	foreach ($arResult["ITEMS"] as &$arItem)
	{
	    //получаем элементы из этой секции


	       if(intval($arItem["~DETAIL_PICTURE"]) > 0)
	       {
	           $photo = $arItem["~DETAIL_PICTURE"];
	           $arItem["DETAIL_PICTURE"] = array(
	               "SMALL" => CFile::ResizeImageGet($photo, array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true),
	               "PREVIEW" => CFile::ResizeImageGet($photo, array('width'=>230, 'height'=>176), BX_RESIZE_IMAGE_EXACT, true),
	               "BIG" => CFile::ResizeImageGet($photo, array('width'=>900, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL, true),
	               "FULL" => CFile::GetFileArray($photo),
	           );
	       }
	}

	foreach ($arResizeImages as $index=>$imgsize)
	{
	    $arResult["ITEMS"][$index]["DETAIL_PICTURE"]["PREVIEW"] = CFile::ResizeImageGet($arResult["ITEMS"][$index]["~DETAIL_PICTURE"], array('width'=>$imgsize['width'], 'height'=>$imgsize['height']), BX_RESIZE_IMAGE_EXACT , true);
	}
}
?>