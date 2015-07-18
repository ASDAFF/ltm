<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalogue">
	<div class="member-title clearfix">
		<h2><?=$arResult["NAME"]?></h2>
		<a href="/members/"><?=GetMessage("BACK_LINK")?></a>
	</div>
    
    <div class="photo-preview">
    <?if(($count = count($arResult["PHOTOS"])) >= 6):?>
        <?$arSizes = array(array(221,250), array(130,110), array(130,110), array(130,110), array(231,133), array(171,133))?>
        <?for($i=0; $i < 6; $i++):?>
            <?$big = CFile::ResizeImageGet($arResult["PHOTOS"][$i]["DETAIL_PICTURE"], array('width'=>900, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
            <?$preview = CFile::ResizeImageGet($arResult["PHOTOS"][$i]["PREVIEW_PICTURE"], array('width'=>$arSizes[$i][0]*2, 'height'=>$arSizes[$i][1]*2), BX_RESIZE_IMAGE_EXACT, true);?>
            <a href="<?=$big["src"]?>" title="<?=$arResult["PHOTOS"][$i]["NAME"]?>" rel="fancybox-thumb">
            <img src="<?=$preview["src"]?>" width="<?=$arSizes[$i][0]?>" height="<?=$arSizes[$i][1]?>" alt="<?=$arResult["PHOTOS"][$i]["NAME"]?>"/>
            </a>
        <?endfor?>
        <?for($i=6; $i < $count; $i++):?>
            <?$big = CFile::ResizeImageGet($arResult["PHOTOS"][$i]["DETAIL_PICTURE"], array('width'=>900, 'height'=>700), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
            <?$preview = CFile::ResizeImageGet($arResult["PHOTOS"][$i]["PREVIEW_PICTURE"], array('width'=>99*2, 'height'=>87*2), BX_RESIZE_IMAGE_EXACT, true);?>
            <a href="<?=$big["src"]?>" title="<?=$arResult["PHOTOS"][$i]["NAME"]?>" rel="fancybox-thumb">
            <img src="<?=$preview["src"]?>" width="99" height="87" alt="<?=$arResult["PHOTOS"][$i]["NAME"]?>"/>
            </a>
        <?endfor?>
    <?endif?>
    </div>
    <div class="text-block">
        <div class="text-block-left">
            <?if($arResult["COMPANY_LOGO"]["SRC"]):?>
                <?$logo = CFile::ResizeImageGet($arResult["COMPANY_LOGO"]["ID"], array('width'=>100, 'height'=>999), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                <img src="<?=$logo["src"]?>" alt="<?=$arResult["NAME"]?>" />
            <?endif?>
            <span class="company-info"><?//=$arResult["ADDRESS"]?></span>

            <?if($arResult["SITE_URL"]):?>
                <span class="company-info"><a href="<?=substr($arResult["SITE_URL"], 0 , 4) == "http" ? $arResult["SITE_URL"] : "http://" . $arResult["SITE_URL"] ?>" title="<?=$arResult["NAME"]?>" target="_blank"><?=GetMessage("WEB_SITE")?></a></span>
            <?endif?>
        </div>
        <?foreach(explode("\n", $arResult["TEXT"]) as $textLine):?>
            <p><?=$textLine?></p>
        <?endforeach?>
    </div>
</div>

<script>
$(document).ready(function() {
	$("div.photo-preview [rel='fancybox-thumb']").fancybox({
		padding : 1,
		transitionIn: 'elastic',
		transitionOut: 'elastic',
		helpers : {
			thumbs :true
		}
	});
});
</script>