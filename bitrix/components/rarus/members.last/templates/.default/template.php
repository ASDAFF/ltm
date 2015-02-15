<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<figure id = "merop">
	<? if(!empty($arResult["ITEMS"])):?>
		<span><?= GetMessage("MEMBERS_LAST_TITLE")?></span>
		<p>
		<? foreach ($arResult["ITEMS"] as $ind => $arItem):?>
			<? if($arParams["ELEMENT_COUNT"] != 0 && $ind >= $arParams["ELEMENT_COUNT"]) break;?>
			<?= $arItem["COMPANY_NAME"]?>,<br>
		<? endforeach;?>
		</p>
		<a href = "<?= str_replace("#ELEMENT_ID#/","", $arParams["URL_TEMPLATE"])?>"><?= GetMessage("MEMBERS_LAST_SHOW_ALL")?></a>
	<? endif?>
</figure>