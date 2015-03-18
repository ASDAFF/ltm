<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

$bIblock = CModule::IncludeModule("iblock");
$lang = strtoupper(LANGUAGE_ID);
$arResizeImages = array(
	"0" => array('width' => 340, 'height' => 280),
	"1" => array('width' => 110, 'height' => 89),
	"2" => array('width' => 110, 'height' => 89),
	"3" => array('width' => 110, 'height' => 89),
	"4" => array('width' => 170, 'height' => 136),
	"5" => array('width' => 170, 'height' => 136),
	);
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
	        $arItem["PROPERTIES"]["MORE_PHOTO"]['RESIZED'] = array();
	        $resized = &$arItem["PROPERTIES"]["MORE_PHOTO"]['RESIZED'];
	    	foreach ($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key=>&$photo)
	    	{
	    			$resized[$key]['FILE_SMALL'] = array_change_key_case(CFile::ResizeImageGet($photo, array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true), CASE_UPPER) ;
	    			$resized[$key]["FILE_BIG"] = array_change_key_case(CFile::ResizeImageGet($photo, array('width'=>900, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL, true), CASE_UPPER);
	    			$resized[$key]["FILE_VALUE"] = CFile::GetFileArray($photo);
	    	}
	    	foreach ($arResizeImages as $index=>$imgsize)
	    	{
	    		$resized[$index]["FILE_PREVIEW"] = array_change_key_case(CFile::ResizeImageGet($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][$index], array('width'=>$imgsize['width'], 'height'=>$imgsize['height']), BX_RESIZE_IMAGE_EXACT , true),CASE_UPPER);
	    	}

	    }
	}
}
?>