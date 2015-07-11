<input class="custom-buttom" name="SPAM" type="submit" value="<?=GetMessage("ADM_PARC_NO_SPAM")?>">
<input name="SPAM_TYPE" type="hidden" value="N">
<a class="custom-buttom" href="/exel/particip.php?type=particip_spam&app=<?=$arParams["EXHIB_CODE"]?>">Генерировать Excel</a>

	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
<div class="table-responsive">
<table class="table">
	<tr>
		<? $arFields = CFormMatrix::$arUCParticipantField;?>
		<? foreach ($arFields as $ind => $fieldName):?>
			<th <?= $class?>><?= $fieldName?></th>
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
				<td class="description"><div><?= $arUser["FORM_DATA"][24]["VALUE"]?></div></td>
				<? // данные из форму представителя?>
				<td><?= $arUser["FORM_USER"][32]["VALUE"]?> <?= $arUser["FORM_USER"][33]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][106]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][35]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][36]["VALUE"]?></td>
				<td><?= $arUser["FORM_USER"][37]["VALUE"]?></td>
				<td><?= $arUser["FORM_DATA"][17]["VALUE"]?></td>
				<td class="text-center">

					<div class="action">
					<img src="<?= SITE_TEMPLATE_PATH?>/images/edit.png">
                        <ul>
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
                            <li><a href="<?= $APPLICATION->GetCurPageParam("ACTION[]=" . $arUser["ID"] . "&SPAM=Y&SPAM_TYPE=N",array("ACTION", "SPAM", "SPAM_TYPE", "EXHIBIT_CODE"));?>"><?= GetMessage("ADM_PARC_NO_SPAM")?></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <? endforeach;?>
	</table>
</div>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
