<?php 
//ресайзим картинку
if(isset($arResult["DETAIL_PICTURE"]) && !empty($arResult["DETAIL_PICTURE"]))
{
	$newFile = CFile::ResizeImageGet(
			$arResult["DETAIL_PICTURE"]["ID"], 
			array("width"=>640, "height"=>99999), 
			BX_RESIZE_IMAGE_PROPORTIONAL_ALT, 
			true
	);
	$arResult["DETAIL_PICTURE"] = array_change_key_case($newFile, CASE_UPPER);
}

?>