<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="pull-left profil-photo">
	<div class="member">
		<div  alt="userpic" class="img_preview"  style="background-image: url('<?=($arResult["PHOTO"]) ? $arResult["PHOTO"]["SRC"] : $templateFolder . "/images/empty.gif";?>'); "></div>
		<div class="layer"></div>
		<span class="photo-delete" title="Удалить" onclick="flushInput(this, '<?=$arParams["INPUT_NAME"]?>_del');"></span>
	</div>
	<label class="photo-uploader">
		<input class="inputfile" type="file" size="0" name="<?=$arParams["INPUT_NAME"]?>" onchange="handleFileSelect(event, this);">
		Upload photo
	</label>
</div>
<style type="text/css">
	.member .img_preview{background-image: url('<?=$templateFolder?>/images/empty.gif');}
</style>