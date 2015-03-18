<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$lang = strtoupper(LANGUAGE_ID);
$bIblock = CModule::IncludeModule("iblock");

if($bIblock)
{
	foreach ($arResult["ITEMS"] as &$arItem)
	{
	    //переписываем поля по выбранному языку
	    if(LANGUAGE_ID != "ru")
	    {
	        $arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"]["TEXT"];
	    }
	}
}
?>