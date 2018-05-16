<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if(!empty($arResult["ITEMS"])):?>
<div class="contacts">
        <h2><?= GetMessage("NL_CONTACTS_TITLE")?></h2>
        <? foreach ($arResult["ITEMS_BY_SECTIONS"] as $id => $arSectionItem)?>
        <?$section = $arResult["SECTION"][$id];?>
        <div class="contact-item headquarters">
            <div class="name"><?= $section["NAME"]?>:</div>
            <span><?= $section["DESCRIPTION"]?></span>
        </div>
        
        <? foreach($arSectionItem as $arContact):?>
        <?
    	$this->AddEditAction($arContact['ID'], $arContact['EDIT_LINK'], CIBlock::GetArrayByID($arContact["IBLOCK_ID"], "ELEMENT_EDIT"));
    	$this->AddDeleteAction($arContact['ID'], $arContact['DELETE_LINK'], CIBlock::GetArrayByID($arContact["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    	?>
         <div class="contact-item" id="<?=$this->GetEditAreaId($arContact['ID']);?>">
            <div class="contact-info employee">
                <div class="title name"><?= $arContact["NAME"]?></div>
                <? if(!empty($arContact["DETAIL_TEXT"])):?>
                    <span class="contact-position"><?= $arContact["DETAIL_TEXT"]?></span>
                <? endif;?>
            </div>
            <div class="contact-info links">
                <? if(!empty($arContact["PROPERTIES"]["EMAIL"]["VALUE"])):
                    $email = $arContact["PROPERTIES"]["EMAIL"]["VALUE"];
                ?>
                    <span><?= GetMessage("NL_CONTACTS_EMAIL")?>:<br/><a href="mailto:<?= $email?>" title="<?= $email?>"><?= $email?></a></span>
                <? endif;?>

                <? if(!empty($arContact["PROPERTIES"]["MOBILE"]["VALUE"])):
                    ?><span><?= GetMessage("NL_CONTACTS_MOBILE")?>: <br/><?
                    $str = "";
                    foreach ($arContact["PROPERTIES"]["MOBILE"]["VALUE"] as $ind => $mobile)
                    {
                        if($ind != 0)
                        {
                            $str .= ",&nbsp" . $mobile;
                        }
                        else
                        {
                            $str = $mobile;
                        }
                    }
                    ?><?= $str?></span>
                <? endif;?>
                <? if(!empty($arContact["PROPERTIES"]["PHONE"]["VALUE"])):
                    ?><span><?= GetMessage("NL_CONTACTS_PHONE")?>: <br/><?
                    $str = "";
                    foreach ($arContact["PROPERTIES"]["PHONE"]["VALUE"] as $ind => $phone)
                    {
                        if($ind != 0)
                        {
                            $str .= ",&nbsp" . $phone;
                        }
                        else
                        {
                            $str = $phone;
                        }
                    }
                    ?><?= $str?></span>
                <? endif;?>
                <span></span>
            </div>
        </div>
        <? endforeach;?>
	<?
	if(LANGUAGE_ID == "ru")
	{
		echo '<br><a href = "http://www.facebook.com/pages/Luxury-Travel-Mart/197410066966979" target = "_blank" class = "fb">Присоединяйтесь к нам на Facebook</a>';
	}
	elseif(LANGUAGE_ID == "en")
	{
		echo '<br><a href = "http://www.facebook.com/pages/Luxury-Travel-Mart/197410066966979" target = "_blank" class = "fb">Join us on Facebook</a>';
	}
	?>
</div>
<? endif;?>