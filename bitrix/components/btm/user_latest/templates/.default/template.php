<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	for($i=0; $i<$arResult["USERS"]["COUNT"]; $i++){
		echo $arResult["USERS"][$i]["COMPANY"].",<br />";
	}
	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>