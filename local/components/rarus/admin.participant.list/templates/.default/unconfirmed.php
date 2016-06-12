<input class="custom-buttom" name="CONFIRM" type="submit" value="<?=GetMessage("ADM_PARC_CONFIRM")?>">

<a class="custom-buttom" href="/exel/particip.php?type=particip_no&app=<?=$arParams["EXHIB_CODE"]?>" ><?= GetMessage("ADM_PARC_EXCEL")?></a>

<input class="custom-buttom" name="SPAM" type="submit" value="<?=GetMessage("ADM_PARC_SPAM")?>">
<input name="SPAM_TYPE" type="hidden" value="Y">

<?
    $arFields = CFormMatrix::$arUCParticipantField;
    $arFieldsSort = CFormMatrix::$arUCParticipantFieldSort;
    $arFieldsFilter = CFormMatrix::$arUCParticipantFieldFilter;
?>
<?//ФИЛЬТРЫ?>
<div class="filters">
    <?foreach($arFields as $filterCode => $filterName):?>
        <?if($arFieldsFilter[$filterCode]):?>
          <?$filtCode = $arFieldsFilter[$filterCode];?>
            <div class="filters__item">
                <label for="<?=$filtCode?>"><?=$filterName?></label>
                <input type="text" name="<?=$filtCode?>" id="<?=$filtCode?>" value="<?=$arResult["FILTER"][$filtCode]?>">
            </div>
        <?endif;?>
    <?endforeach;?>
    <input class="custom-buttom" name="filter" value="Применить" type="submit">
    <input class="custom-buttom" name="reset" value="Отменить" type="submit">
</div>
<? if(!empty($arResult["EXHIBITION"]["PARTICIPANT"])):?>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
<div class="table-responsive">
<table class="table">
	<tr>

		<? foreach ($arFields as $ind => $fieldName):?>
			<th <?= $class?>>
                <?if($arFieldsSort[$ind]):?>
                    <?
                    $orderSort = 'asc';
                    $class = '';
                    if($arFieldsSort[$ind] == $arResult["SORT"] && $arResult["ORDER"] == 'asc'){
                        $orderSort = 'desc';
                    }
                    if($arFieldsSort[$ind] == $arResult["SORT"]){
                        $class = 'active';
                    }
                    ?>
                    <a href="?sort=<?=$arFieldsSort[$ind]?>&order=<?=$orderSort?>" class="sort-title <?=$class?>"><?= $fieldName?></a>
                <?else:?>
                    <?= $fieldName?>
                <?endif;?>
            </th>
		<? endforeach;?>
	</tr>

	<? //вывод полей?>
	<? $index = 1;?>
	<?foreach ($arResult["EXHIBITION"]["PARTICIPANT"] as $arUser):?>
    <?
        $companyResultID = $arUser["UF_ID_COMP"];
        $member1ResultID = $arUser[CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"],0)];
        if(!$member1ResultID)
        {
            $member1ResultID = $arUser["UF_ID"];
        }
        $member2ResultID = $arUser[CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"],1)];
    ?>
		<tr class="<?= (($index++ % 2) != 0)?"even":"odd"?>">
			<td class="text-center"><input type="checkbox" name="ACTION[]" value="<?= $arUser["ID"]?>"></td>
				<? //данные из Общей формы по всем участникам?>
				<td><?= $arUser["FORM_DATA"][17]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][19]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][21]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][22]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][20]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][23]["VALUE"]?></td>
				<td class="description"><div class="data-wrap"><?= $arUser["FORM_DATA"][24]["VALUE"]?></div></td>
				<? // данные из формы представителя?>
				<td><?= $arUser["FORM_USER"][32]["VALUE"]?> <?= $arUser["FORM_USER"][33]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][106]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][35]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][36]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][586]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][37]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][17]["VALUE"]?></td>
				<td class="text-center">

					<div class="action" id="action_<?=$arUser["ID"]?>">
					<img src="<?= SITE_TEMPLATE_PATH?>/images/edit.png">
                        <ul class="ul-popup">
                            <li><a href="<?= $APPLICATION->GetCurPageParam("ACTION[]=" . $arUser["ID"] . "&CONFIRM=Y",array("ACTION", "CONFIRM"));?>"><?= GetMessage("ADM_PARC_CONFIRM")?></a></li>
                            <? if(!empty($companyResultID)):?>
                            <? $href = $arParams["PATH_TO_KAB"]. "service/edit.php?id=" . $arUser["ID"]. "&result=" . $companyResultID . "&type=C"?>
                            <li>
                                <a
                                    href="<?= $href?>"
                                    target="_blank"
                                    onclick="newWind('<?= $href?>', 500, 600); return false;"
                                    ><?= GetMessage("ADM_PARC_EDIT_C")?></a>
                            </li>
                            <? endif;?>
                            <? if(!empty($member1ResultID)):?>
                            <? $href = $arParams["PATH_TO_KAB"]. "service/edit.php?id=" . $arUser["ID"]. "&result=" . $member1ResultID . "&type=M"?>
                            <li>
                                <a
                                    href="<?= $href?>"
                                    target="_blank"
                                    onclick="newWind('<?= $href?>', 500, 600); return false;"
                                    ><?= GetMessage("ADM_PARC_EDIT_M1")?></a>
                            </li>
                            <? endif;?>
                            <? if(!empty($member2ResultID)):?>
                            <? $href = $arParams["PATH_TO_KAB"]. "service/edit.php?id=" . $arUser["ID"]. "&result=" . $member2ResultID . "&type=edit"?>
                            <li>
                                <a
                                    href="<?= $href?>"
                                    target="_blank"
                                    onclick="newWind('<?= $href?>', 500, 600); return false;"
                                    ><?= GetMessage("ADM_PARC_EDIT_M2")?></a>
                            </li>
                            <? endif;?>
                            <? $href = $arParams["PATH_TO_KAB"]. "service/pass.php?uid=" . $arUser["ID"]?>
                            <li>
                                <a
                                    href="<?= $href?>"
                                    target="_blank"
                                    onclick="newWind('<?= $href?>', 500, 300); return false;"
                                    ><?= GetMessage("ADM_PARC_EDIT_PAS")?></a>
                            </li>
                            <li><a href="<?= $arParams["PATH_TO_KAB"]. "service/excel.php?uid=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["ID"] . "&type=p"?>"><?= GetMessage("ADM_PARC_EXCEL")?></a></li>
                            <li><a href="<?= $APPLICATION->GetCurPageParam("ACTION[]=" . $arUser["ID"] . "&SPAM=Y&SPAM_TYPE=Y",array("ACTION", "SPAM","SPAM_TYPE", "EXHIBIT_CODE"));?>"><?= GetMessage("ADM_PARC_SPAM")?></a></li>
                            <? $href = $arParams["PATH_TO_KAB"]. "service/transfer.php?uid=" . $arUser["ID"];?>
                            <li>
                                <a
                                    href="<?= $href?>"
                                    target="_blank"
                                    onclick="newWind('<?= $href?>', 500, 300); return false;"
                                    ><?= GetMessage("ADM_PARC_TO_EXHIB")?></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <? endforeach;?>
	</table>
</div>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
<? else:?>
    <?= GetMessage("ADM_PARC_NO_PARTICIPANTS")?>
<? endif;?>