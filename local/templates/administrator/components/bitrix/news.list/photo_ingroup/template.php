<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //pre($arResult)?>
<? if(!empty($arResult["ITEMS"])):?>

<? $arSection = end($arResult["SECTION"]["PATH"]);?>
<div class="album-preview">
	  <div class="album-title">
			<a id="get-popup_<?= $arSection["ID"]?>" href="javascript:void(0)" ><?= $arSection['NAME']?></a>
	  </div>

	<? $arPhoto = $arResult["ITEMS"][0]["DETAIL_PICTURE"];?>
	<? $bEnd = false;?>
	  <div class="album-preview-bl">
	        <? if(isset($arPhoto)):?>
	   		<a class="photo-gallery popup-link img-md" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	   		 	<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? $arPhoto = $arResult["ITEMS"][1]["DETAIL_PICTURE"];?>
	   		<? if(!$bEnd && isset($arPhoto)):?>
	   		<a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	   		 	<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? $arPhoto = $arResult["ITEMS"][2]["DETAIL_PICTURE"];?>
	   		<? if(!$bEnd && isset($arPhoto)):?>
	   		<a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	   			<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   </div>

	  <div class="album-preview-bl">
	        <? $arPhoto = $arResult["ITEMS"][3]["DETAIL_PICTURE"];?>
	        <? if(!$bEnd && isset($arPhoto)):?>
	   		<a class="photo-gallery popup-link img-md" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	   			<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? $arPhoto = $arResult["ITEMS"][4]["DETAIL_PICTURE"];?>
	   		<? if(!$bEnd && isset($arPhoto)):?>
	   		<a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	   		 	<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   		<? $arPhoto = $arResult["ITEMS"][5]["DETAIL_PICTURE"];?>
	   		<? if(!$bEnd && isset($arPhoto)):?>
	   		 <a class="photo-gallery popup-link img-sm" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	   		  	<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	   		</a>
	   		<? else:?>
	   			<? $bEnd = true;?>
	   		<? endif;?>
	   </div>
	   <? $arPhoto = $arResult["ITEMS"][6]["DETAIL_PICTURE"];?>
	    <? if(!$bEnd && isset($arPhoto)):?>
	 	<a class="photo-gallery popup-link img-lg" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" >
	 	 	<img src="<?= $arPhoto["PREVIEW"]["src"]?>"  />
	 	</a>
	   	<? else:?>
	   		<? $bEnd = true;?>
	 	<? endif;?>

	</div>
	<div id="popup-gallery_<?= $arSection["ID"]?>" class="popup-gallery">
	  <div class="photo-gallery-wrap">
	  <? if(!$bEnd):?>
		  <? for ($cnt = count($arResult["ITEMS"]), $i = 7; $i < $cnt; $i++):?>
		  <? $arPhoto = $arResult["ITEMS"][$i]["DETAIL_PICTURE"];?>
		   		<a class="popup-gallery" rel="fancybox-thumb_<?= $arSection["ID"]?>" href="<?= $arPhoto["BIG"]["src"]?>" alt="" ></a>
		  <? endfor;?>
	  <? endif?>
	  </div>
	</div>
	<script>
	$(document).ready(function() {
		$("[rel='fancybox-thumb_<?= $arSection["ID"]?>']").fancybox({
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