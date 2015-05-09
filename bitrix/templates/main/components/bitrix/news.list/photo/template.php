<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if(!empty($arResult["ITEMS"])):?>
<div class="photo">
<? foreach ($arResult["ITEMS"] as $arItem):?>
    <? if(empty($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"]))
    {
        continue;
    }?>
	<div class="album-preview">
	  <div class="album-title">
		<?
		if(LANGUAGE_ID == "ru"){
			if($arItem['PROPERTIES']['V_RU']['VALUE']){
				?>
				<a id="get-popup_<?= $arItem["ID"]?>" href="javascript:void(0)" ><?= $arItem['PROPERTIES']['V_RU']['VALUE']?></a>
				<?
			}else{
				?>
				<a id="get-popup_<?= $arItem["ID"]?>" href="javascript:void(0)" ><?= $arItem["NAME"]?></a>
				<?
			}
		}else{
			if($arItem['PROPERTIES']['V_EN']['VALUE']){
				?>
				<a id="get-popup_<?= $arItem["ID"]?>" href="javascript:void(0)" ><?= $arItem['PROPERTIES']['V_EN']['VALUE']?></a>
				<?
			}else{
				?>
				<a id="get-popup_<?= $arItem["ID"]?>" href="javascript:void(0)" ><?= $arItem["NAME"]?></a>
				<?
			}
		}
		
		?>
		
	  </div>

	<? $arPhoto = $arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"];?>
	<? $bEnd = false;?>
	  <div class="album-preview-bl">
	        <? if(isset($arPhoto[0])):?>
	   		<a class="photo-gallery popup-link img-md" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[0]["BIG"]["src"]?>" >
	   		 	<img src="<?= $arPhoto[0]["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? if(!$bEnd && isset($arPhoto[1])):?>
	   		<a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[1]["BIG"]["src"]?>" >
	   		 	<img src="<?= $arPhoto[1]["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? if(!$bEnd && isset($arPhoto[2])):?>
	   		<a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[2]["BIG"]["src"]?>" >
	   			<img src="<?= $arPhoto[2]["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   </div>

	  <div class="album-preview-bl">
	        <? if(!$bEnd && isset($arPhoto[3])):?>
	   		<a class="photo-gallery popup-link img-md" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[3]["BIG"]["src"]?>" >
	   			<img src="<?= $arPhoto[3]["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? if(!$bEnd && isset($arPhoto[4])):?>
	   		<a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[4]["BIG"]["src"]?>" >
	   		 	<img src="<?= $arPhoto[4]["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? if(!$bEnd && isset($arPhoto[5])):?>
	   		 <a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[5]["BIG"]["src"]?>" >
	   		  	<img src="<?= $arPhoto[5]["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   </div>
	    <? if(!$bEnd && isset($arPhoto[6])):?>
	 	<a class="photo-gallery popup-link img-lg" rel="fancybox-thumb_<?= $arItem["ID"]?>" href="<?= $arPhoto[6]["BIG"]["src"]?>" >
	 	 	<img src="<?= $arPhoto[6]["PREVIEW"]["src"]?>"  />
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
<? endforeach;?>
</div>

<? endif;?>