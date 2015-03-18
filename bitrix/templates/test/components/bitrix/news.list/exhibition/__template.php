<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?//pre($arResult);?>
<? if(count($arResult["ITEMS"]) > 0):?>

<article>
<h2><?= GetMessage("NEWS_LIST_EXH_TITLE")?></h2>
    <?foreach($arResult["ITEMS"] as $arItem): ?>
    <?if(isset($arItem["PROPERTIES"]["MORE_PHOTO"]) && !empty($arItem["PROPERTIES"]["MORE_PHOTO"])):?>
    <div class="preview">
    	<?/*foreach ($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $cnt => &$photo):?>

    	<? endforeach;*/?>
    	<? $arPhoto = $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"];?>
    	<? $bEnd = false;?>
    	<? if(isset($arPhoto[0])):?>
    	<a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[0]["BIG"]["src"]?>">
    	    <img src="<?= $arPhoto[0]["PREVIEW"]["src"]?>" width="340" height="268" alt=""/>
    	</a>
		<? else:?>
   			<? $bEnd = true;?>
   		<? endif;?>

        <div>
            <? if(isset($arPhoto[1])):?>
            <a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[1]["BIG"]["src"]?>" >
                <img src="<?= $arPhoto[1]["PREVIEW"]["src"]?>" width="110" height="83" alt=""/>
            </a>
            <? else:?>
   			  <? $bEnd = true;?>
   			<? endif;?>
            <? if(isset($arPhoto[2])):?>
            <a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[2]["BIG"]["src"]?>">
                <img src="<?= $arPhoto[2]["PREVIEW"]["src"]?>" width="110" height="83" alt=""/>
            </a>
            <? else:?>
   			  <? $bEnd = true;?>
   			<? endif;?>
            <? if(isset($arPhoto[3])):?>
            <a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[3]["BIG"]["src"]?>" >
                <img src="<?= $arPhoto[3]["PREVIEW"]["src"]?>" width="110" height="83" alt=""/>
            </a>
            <? else:?>
   			  <? $bEnd = true;?>
   			<? endif;?>
            <? if(isset($arPhoto[4])):?>
        </div>
        <a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[4]["BIG"]["src"]?>" >
            <img src="<?= $arPhoto[4]["PREVIEW"]["src"]?>" width="170" height="143" alt=""/>
        </a>
            <? else:?>
   			  <? $bEnd = true;?>
   			<? endif;?>
    	<? if(isset($arPhoto[5])):?>
        <a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[5]["BIG"]["src"]?>" >
            <img src="<?= $arPhoto[5]["PREVIEW"]["src"]?>" width="170" height="116" alt=""/>
        </a>
            <? else:?>
   			  <? $bEnd = true;?>
   			<? endif;?>
    </div>
    <div id="popup-gallery_<?= $arItem["ID"]?>" class="popup-gallery">
	  <div class="photo-gallery-wrap">
	  <? if(!$bEnd):?>
		  <? for ($cnt = count($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"]), $i = 7; $i < $cnt; $i++):?>
		  <? $arPhoto = $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][$i];?>
		   		<a class="popup-gallery" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" alt="" >
		   			<img src="<?= $arPhoto["SMALL"]["src"]?>"  />
		   		</a>
		  <? endfor;?>
	  <? endif?>
	  </div>
	</div>
	<script>
	$(document).ready(function() {
		$("[rel='fancybox-thumb_<?= $arItem["ID"]?>']").fancybox({
			padding : 1,
			transitionIn: 'elastic',
			transitionOut: 'elastic',
			helpers : {
				thumbs :true
			}
		});
	});
	</script>
	<? endif;?>
	<div class="detail"><?= $arItem["DETAIL_TEXT"]?></div>
	<? endforeach;?>
</article>

<?endif;?>

