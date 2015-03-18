<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<nav id = "bottom">
<?
$i = 0;
foreach($arResult as $arItem):
	$i++;
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<a href = "<?=$arItem["LINK"]?>" <? if($i==1) { ?> class = "f" <? } ?>><?=$arItem["TEXT"]?></a>
	
<?endforeach?>
</nav>
<?endif?>