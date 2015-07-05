<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
      Welcome,  <?=$arResult["NAME"]?><br />
      You have <span><?=$arResult["NEW_APP"]?></span> unconfirmed appointment requests and <span><?=$arResult["NEW_MESSAGES"]?></span> new messages
	<?
}
?>