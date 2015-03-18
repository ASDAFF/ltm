<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>
<nav class="menu-top">

<?
$i = 0;
foreach($arResult as $arItem):
	$i++;
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<a href = "<?=$arItem["LINK"]?>" class="selected<? if($i==1){ ?> first<? } ?><? if($i==count($arResult)){ ?> last<? } ?>"><?=$arItem["TEXT"]?></a>
	<?else:?>
		<a href = "<?=$arItem["LINK"]?>" <? if($i==1){ ?> class="first"<? } ?><? if($i==count($arResult)){ ?>class="last"<? } ?>><?=$arItem["TEXT"]?></a>
	<?endif?>
	
<?endforeach?>
</nav>
<?endif?>