<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//Подключаем плагин для передвижения?>
<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-ui-1.11.4/jquery-ui.min.js')?>

<div class="drag-drop">
	<div class="title">	Please upload a minimum of 6 (maximum 12) photos of your company/hotel. The maximum size of each photo is 3mb. Don’t forget — the better the quality of your photos, the better the impression of your company/hotel.</div>
	<label class="button-dark ltm-btn" id="upload_photo">upload photos<input type="file" id="uploadbtn" multiple value="Загрузить" name="photo-file" style="display: none;"/></label>
	<span>You can upload <span id="photo_count"><?=$arParams["MAX_PHOTO_COUNT"] - count($arResult["ITEMS"])?></span> photos</span>
	<div id="dropzone" class="dropzone">
		<ul id="sortable" class="photo-list">
			<?if($arResult["ITEMS"]):?>
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<li data-id="<?=$arItem["ID"]?>">
						<div class="img-wrapper">
							<img src="<?=$arItem["PHOTO_SMALL"]["SRC"]?>"/>
						</div>
						<span class="photo-delete" title="Удалить">&times;</span>
					</li>
				<?endforeach?>
			<?endif?>
		</ul>
		<div class="dnd-title">Drag and Drop files here</div>
	</div>
</div>
<script type="application/javascript">
	maxPhotoCount = <?=$arParams["MAX_PHOTO_COUNT"]?>;
</script>
