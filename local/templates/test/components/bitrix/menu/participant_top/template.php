<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $userId = intval($_REQUEST["UID"]);?>
<div class="exhibition-list pull-overflow clearfix">
	<?if (!empty($arResult)):?>
	<ul>
	<?foreach($arResult as $arItem):?>

	<?if ($arItem["PERMISSION"] > "D"):?>
		<li <?=$arItem['SELECTED']?'class="active"':''?>>
    		<a href="<?=$arItem["LINK"]  . (($userId)?"?UID=" . $userId : "") ?>"title="<?=$arItem["TEXT"]?>" >
    		    <?=$arItem["TEXT"]?>
    		</a>
		</li>
	<?endif?>

<?endforeach?>

	</ul>
	<?endif?>
</div>
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
