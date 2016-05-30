<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use \Bitrix\Main\Localization\Loc;
$request = \Bitrix\Main\HttpContext::getCurrent()->getRequest();
?>

<div class="storage-popup">
	<form method="post" action="<?=$arResult["ACTION_URL"]?>">
		<input type="hidden" name="ID" value="<?=$arResult["USER_ID"]?>">
		<input type="hidden" name="TYPE" value="inworking">
		<h2><?=Loc::getMessage('STORAGE_P_TITLE')?></h2>
		<div class="select-exh"><?=Loc::getMessage('STORAGE_P_SELECT_EXH')?></div>
		<ul class="radio">
			<?$bFirst = true;?>
			<?foreach($arResult['EXHIBITIONS']as $id => $name):?>
				<li><label <?if($bFirst):?>class="active" <?endif?>><input type="radio" name="EXHIBITION" value="<?=$id?>" <?if($bFirst):?>checked="checked"<?endif?>> <?=$name?></label></li>
			<?if($bFirst)$bFirst = false;?>
			<?endforeach;?>
		</ul>
		<div class="format"><?=Loc::getMessage('STORAGE_P_SELECT_FORMAT')?></div>
		<ul class="checkbox">
			<li><label><input type="checkbox" name="MORNING" value="1"> <?=Loc::getMessage('STORAGE_P_MORNING')?></label></li>
			<li><label><input type="checkbox" name="EVENING" value="1"> <?=Loc::getMessage('STORAGE_P_EVENING')?></label></li>
		</ul>
		<div class="clear"></div>
		<input type="submit" value="<?=Loc::getMessage('STORAGE_P_SUBMIT')?>">
	</form>
</div>
