<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$title = "";
switch($arParams["ACT"])
{
	case "off": $title = "���������������� �����";break;
	case "morning": $title = "����� �� ����"; break;
	case "evening": $title = "����� �� �����"; break;
	case "hostbuy": $title = "����� HB"; break;
	case "spam": $title = "����� ����"; break;
}

if(isset($arResult["EXHIB"]["NAME"])) {
	$title .= " {$arResult["EXHIB"]["NAME"]}";
}

global $APPLICATION;
$APPLICATION->SetTitle($title);
?>