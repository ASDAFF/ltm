<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$bIblock = CModule::IncludeModule("iblock");

$lang = strtoupper(LANGUAGE_ID);
$sections = array();

if($bIblock)
{
	foreach ($arResult["ITEMS"] as &$arItem)
	{

	    //переписываем поля по выбранному языку
	    if(LANGUAGE_ID != "ru")
	    {
	    	$value_name = ($arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang]["VALUE"]["TYPE"] == "html")?"~VALUE":"VALUE";

	        $arItem["DETAIL_TEXT"] = $arItem["PROPERTIES"]["DETAIL_TEXT_" . $lang][$value_name]["TEXT"];
	    	$arItem["NAME"] = $arItem["PROPERTIES"]["NAME_" . $lang]["VALUE"];
	    }



	    $arResult["ITEMS_BY_SECTIONS"][$arItem["IBLOCK_SECTION_ID"]][] = $arItem;
	}

	$arSelect = array("ID", "NAME", "DESCRIPTION");
	//получаем пользовательские свойства для других языков
	if(LANGUAGE_ID != "ru")
	{
	    $arSelect = array_merge($arSelect, array("UF_NAME_" . $lang, "UF_DESCRIPTION_" . $lang));
	}

	$rsSect = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arResult["ID"],"ID" => array_keys($arResult["ITEMS_BY_SECTIONS"])), false, $arSelect);
	while($arSection = $rsSect->GetNext(true,false))
	{
	    //переписываем поля по выбранному языку
	    if(LANGUAGE_ID != "ru")
	    {
	        $arSection["DESCRIPTION"] = $arSection["UF_DESCRIPTION_" . $lang];
	        $arSection["NAME"] = $arSection["UF_NAME_" . $lang];

	    }


		$arResult["SECTION"][$arSection["ID"]] = $arSection;
	}
}



?>