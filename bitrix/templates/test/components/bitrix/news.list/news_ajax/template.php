<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["ITEMS"])): ?>
    <div id = "news">
        <h2 class = "news"><?= GetMessage("NEWS_LIST_TITLE")?></h2>
        <?
        if(isset($_REQUEST["ajax"]) && "Y" == $_REQUEST["ajax"])
        {
        	global $APPLICATION;
        	$APPLICATION->RestartBuffer();
        }
        
        $i = 0;
        foreach($arResult["ITEMS"] as $index => $arItem):
        	$i++;
        	?>
        	<?
        	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        	?>
        	<article id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        		<div class="news-text">
	        		<h3 class = "main_h3"><?=$arItem["NAME"]?></h3>
	        		<div ><?=$arItem["PREVIEW_TEXT"]?><a href = "<?=$arItem["DETAIL_PAGE_URL"]?>" class = "more"><?= GetMessage("NEWS_LIST_READ_MORE")?></a></div>
        		</div>
        		<div class="news-img">
        		    <img 
	        			src = "<?=$arItem['MOD_PHOTO']['src']?>" 
	        			alt = "<?=$arItem["NAME"]?>" 
	        			class = "main_img" 
	        			width="220"
	        			height="150"
        			/>
        		</div>

        		
        	</article>
        	<? if ($i==1){ ?><div class = "clear"></div> <? } ?>
        <?endforeach;?>
        
        <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
			<br /><?=$arResult["NAV_STRING"]?>
		<?endif;?>
		
		<? 
		if(isset($_REQUEST["ajax"]) && "Y" == $_REQUEST["ajax"])
        {
        	die();
        }
        ?>
    </div>
<?endif; ?>