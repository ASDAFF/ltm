<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $lang = strtoupper(LANGUAGE_ID);?>
<? if(!empty($arResult["ITEMS"])):?>
<div class="organizers">

<? foreach ($arResult["ITEMS"] as $arItem):?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
    <div class="organizers-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="name"><?= $arItem["NAME"]?></div>
        <div class="organizers-left-side">
            <figure class="organizers-photo">
                <? if(!empty($arItem["PROPERTIES"]["LOGO"]["SRC"])):?>
                <div class="company-logo"><img src="<?= $arItem["PROPERTIES"]["LOGO"]["SRC"]?>" width="220" alt="logo" /></div>
                <? endif;?>
                <img src="<?= $arItem["DETAIL_PICTURE"]["SRC"]?>" width="220"  alt="<?= $arItem["DETAIL_PICTURE"]["ALT"]?>" />
                <figcaption><?
                $organizers = ($lang == "RU")?$arItem["PROPERTIES"]["ORGANIZERS"]["VALUE"]:$arItem["PROPERTIES"]["ORGANIZERS_" . $lang]["VALUE"];
                if(!empty($organizers))
                {
                    $str = "";
                	foreach ($organizers as $ind => $value)
                	{
                		if($ind != 0)
                		{
                		    $str .= ", ". $value;
                		}
                		else
                		{
                		    $str = $value;
                		}
                	}
                	echo $str;
                }
                else
                {
                    echo $arItem["NAME"];
                }
              ?></figcaption>
            </figure>
        </div>
        <div class="organizers-infoblock"><?= $arItem["DETAIL_TEXT"]?></div>
    </div>
<? endforeach;?>
</div>

<? endif;?>