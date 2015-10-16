<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;


$arParams["WIDTH"] = intval($arParams["WIDTH"]);
if(!isset($arParams["WIDTH"]))
	$arParams["WIDTH"] = 108;

$arParams["HEIGHT"] = intval($arParams["HEIGHT"]);
if(!isset($arParams["HEIGHT"]))
	$arParams["HEIGHT"] = 108;

$arParams["FILE_ID"] = intval($arParams["FILE_ID"]);
if(!isset($arParams["FILE_ID"]))
	$arParams["FILE_ID"] = false;


$arResult = array();

//Получаем данные картинки
if($arParams["FILE_ID"])
{
	//Если задан размер, то обрезаем фото перед выводом
	$arResizePhoto = CFile::ResizeImageGet($arParams["FILE_ID"], Array("width"=>$arParams["WIDTH"], "height"=>$arParams["HEIGHT"]), BX_RESIZE_IMAGE_EXACT);

	$arResult["PHOTO"] = array_change_key_case($arResizePhoto, CASE_UPPER);
}


$this->IncludeComponentTemplate();

?>
