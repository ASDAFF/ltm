<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$title = "";
switch($arParams["ACT"])
{
	case "off": $title = "Неподтвержденные Гости";break;
	case "morning": $title = "Гости на Утро"; break;
	case "evening": $title = "Гости на Вечер"; break;
	case "hostbuy": $title = "Гости HB"; break;
	case "spam": $title = "Гости спам"; break;
}

if(isset($arResult["EXHIB"]["NAME"])) {
	$title .= " {$arResult["EXHIB"]["NAME"]}";
}

global $APPLICATION;
$APPLICATION->SetTitle($title);
?>