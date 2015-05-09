<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
      Добро пожаловать,  <?=$arResult["NAME"]?><br />
      У Вас <span><?=$arResult["NEW_APP"]?></span> неподтвержденных запросов на встречи и <span><?=$arResult["NEW_MESSAGES"]?></span> новых сообщений
	<?
}
?>