<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["ITEMS"])): ?>
    <div id = "news">
        <h2 class = "news"><?= GetMessage("NEWS_LIST_TITLE")?></h2>
        <?
        $i = 0;
        foreach($arResult["ITEMS"] as $index => $arItem):
        	$i++;
        	?>
        	<?
        	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        	?>
        	<article id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        		<h3><?=$arItem["NAME"]?></h3>
        		<img src = "<?=$arItem['MOD_PHOTO']['src']?>" alt = "<?=$arItem["NAME"]?>" />
        		<p><?=$arItem["PREVIEW_TEXT"]?><a href = "<?=$arItem["DETAIL_PAGE_URL"]?>" class = "more"><?= GetMessage("NEWS_LIST_READ_MORE")?></a></p>
        	</article>
        	<? if ($i==1){ ?><div class = "clear"></div> <? } ?>
        <?endforeach;?>
    </div>
<?endif; ?>