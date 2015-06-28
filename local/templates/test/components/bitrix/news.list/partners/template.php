<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$lang = strtoupper(LANGUAGE_ID);?>
<? if(!empty($arResult["ITEMS"])):?>
<div class="partners">
    <? foreach($arResult["ITEMS"] as $arItem):?>
    <?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
    <div class="partner-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <h2><?= $arItem["NAME"]?><span class="kind"><?= GetMessage("NL_PARTNERS_TYEP_" . $arItem["PROPERTIES"]["TYPE"]["VALUE_XML_ID"])?></span></h2>
        <div class='prev-img'>
        <figure>
            <img src="<?= $arItem["PICT_MOD"]["src"]?>" width="221" alt="<?= $arItem["NAME"]?>" />
            <figcaption>
                <span class="mail"><a href="<?= $arItem["PROPERTIES"]["HTTP"]["VALUE"]?>" title=""  target = "_blank"><?= str_replace("http://", "", $arItem["PROPERTIES"]["HTTP"]["VALUE"])?></a></span>
                <?
                $address = ($lang == "RU")?$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]:$arItem["PROPERTIES"]["ADDRESS_" . $lang]["VALUE"];
                if(!empty($address)):?>
                <span><?= GetMessage("NL_PARTNERS_ADDRESS")?>:&nbsp;<?= $address?></span>
                <? endif;?>
                <?
                if(!empty($arItem["PROPERTIES"]["PHONE"]["VALUE"])):?>
                <span><?= GetMessage("NL_PARTNERS_PHONE_SHORT")?>:&nbsp;<?= $arItem["PROPERTIES"]["PHONE"]["VALUE"]?></span>
                <? endif;?>
            </figcaption>
        </figure>
        </div>
        <div class='prev-text'>
        <?= $arItem["PREVIEW_TEXT"]?>
            <? if(!empty($arItem["DETAIL_PAGE_URL"])):?>
            <a href="<?= $arItem["DETAIL_PAGE_URL"]?>" title="<?= GetMessage("NL_PARTNERS_READ_MORE")?>"><?= GetMessage("NL_PARTNERS_READ_MORE")?></a>
            <? endif;?>
         </div>
    </div>
    <? endforeach;?>
</div>
<? endif;?>