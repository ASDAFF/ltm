<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?define('MAXCOUNT_DISPLAY_IMG_ON_INDEX', 6);?>
<? if(count($arResult["ITEMS"]) > 0):?>
<article>
<h2><?= GetMessage("NEWS_LIST_EXH_TITLE")?></h2>

    <?foreach($arResult["ITEMS"] as $arItem): ?>
    <?if(isset($arItem["PROPERTIES"]["MORE_PHOTO"]) && !empty($arItem["PROPERTIES"]["MORE_PHOTO"])):?>
     <div class="preview">

    	<?$counter = 0;?>
    	<? foreach ( $arItem["PROPERTIES"]["MORE_PHOTO"]['RESIZED'] as $arPhoto):?>
	    	<?
	    	$counter ++ ;
	    	if ($counter > MAXCOUNT_DISPLAY_IMG_ON_INDEX )
	    		break;

	        if ($counter == 2):?>
	           <div>
	        <?endif;?>

	        	<a rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto["FILE_BIG"]["SRC"]?>">
	        	    <img src="<?= $arPhoto["FILE_PREVIEW"]["SRC"]?>" width="<?=$arPhoto["FILE_PREVIEW"]['WIDTH']?>" height="<?=$arPhoto["FILE_PREVIEW"]['HEIGHT']?>" alt=""/>
	        	</a>

	         <?if ($counter == 4):?>
	           </div>
	           <?endif;?>
          <?endforeach;?>
   </div>


    <div id="popup-gallery_<?= $arItem["ID"]?>" class="popup-gallery">
	  <div class="photo-gallery-wrap">
	  <? if(!$bEnd):?>
		<?$counter = 0;
		foreach($arItem["PROPERTIES"]["MORE_PHOTO"]['RESIZED'] as $key=>$arPhoto):
			$counter ++;
			if ($counter > MAXCOUNT_DISPLAY_IMG_ON_INDEX):?>
				<a class="popup-gallery" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto["FILE_BIG"]["SRC"]?>" alt="<?=$arItem["PROPERTIES"]["MORE_PHOTO"]['DESCRIPTION'][$key]?>" >
	   			<img src="<?= $arPhoto["FILE_SMALL"]["SRC"]?>"  title='<?=$arItem["PROPERTIES"]["MORE_PHOTO"]['DESCRIPTION'][$key]?>' />
	   			</a>
			<?endif;?>
		<? endforeach;?>
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

	<div class="detail"><?= $arItem["DETAIL_TEXT"]?></div>
	<? endif;?>
	<? endforeach;?>
</article>

<?endif;?>

