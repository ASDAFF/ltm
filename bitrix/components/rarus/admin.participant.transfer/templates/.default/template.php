<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if(!isset($arResult["ERROR"])):?>
<div class="exhibition">
    <? if(!empty($arResult["ITEMS"])):?>
    <form name="transfer" action="<?=$arResult["URL"]?>" enctype="application/x-www-form-urlencoded" method="post">
    <?=bitrix_sessid_post()?>
        <table>
            <tr>
                <td></td><td>Название выставки</td><td>Статус выставки</td><td>Статус участника</td>
            </tr>
            <? foreach ($arResult["ITEMS"] as $arItem):?>
            <?
            switch ($arItem["STATUS"])
            {
            	case "CONFIRMED" : $partClass = "confirmed"; break;
            	case "UNCONFIRMED" : $partClass = "unconfirmed"; break;
            	case "NONE" : $partClass = "none"; break;
            	default: $partClass = "none";
            }

            switch ($arItem["EXH_STATUS"])
            {
            	case "Sold out" : $exhClass = "red"; break;
            	case "Waiting list" : $exhClass = "white";break;
            	case "Available" : $exhClass = "green";break;
            	default: $exhClass = "white";
            }


            ?>
            <tr class="<?= $partClass?>">
                <td>
                    <input
                        type="checkbox"
                        name="EXH[<?= $arItem["ID"]?>]"
                        <? if("CONFIRMED" == $arItem["STATUS"] || "UNCONFIRMED" == $arItem["STATUS"]){echo "checked='checked'";}?>
                        <? if("CONFIRMED" == $arItem["STATUS"]){echo "disabled";}?>
                        id="<?= $arItem["ID"]?>"
                    />
                </td>
                <td><label for="<?= $arItem["ID"]?>"><?= $arItem["NAME"]?></label></td>
                <td class="<?= $exhClass?>"><?= $arItem["EXH_STATUS"]?></td>
                <td><?= GetMessage($arItem["STATUS"])?></td>
            </tr>
            <? endforeach;?>

        </table>
        <br />
        <input type="submit" name="save" value="Сохранить">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="reset" name="reset" value="Сбросить">
    </form>
    <? endif;?>
</div>
<? else:?>
	<p><?= $arResult["ERROR"]?></p>
<? endif;?>
