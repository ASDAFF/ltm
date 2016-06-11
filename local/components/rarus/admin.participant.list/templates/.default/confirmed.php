<input class="custom-buttom" name="<?= ("Y" == $arParams["CONFIRMED"])?"CANCEL":"CONFIRM"?>" type="submit" value="<?= ("Y" == $arParams["CONFIRMED"])?GetMessage("ADM_PARC_CANCEL"):GetMessage("ADM_PARC_CONFIRM")?>">
<a class="custom-buttom" href="/exel/particip.php?type=particip&app=<?=$arParams["EXHIB_CODE"]?>">Генерировать Excel</a>
<a class="custom-buttom" href="/exel/particip.php?type=particip_all&app=<?=$arParams["EXHIB_CODE"]?>">Excel (все люди)</a>
<a class="custom-buttom go" href="/ajax/all_pdf_shedule.php?type=particip&app=<?=$arParams["EXHIB_CODE"]?>" data-hb="" data-to="shedule">PDF расписания</a>
<a class="custom-buttom go" href="/ajax/all_pdf_shedule.php?type=particip&app=<?=$arParams["EXHIB_CODE"]?>&hb=y" data-hb="y" data-to="shedule">PDF HB расписания</a>
<a class="custom-buttom go" href="/ajax/all_pdf_wishlist.php?type=particip&app=<?=$arParams["EXHIB_CODE"]?>" data-hb="" data-to="wishlist">PDF вишлисты</a>
<a class="custom-buttom go" href="/ajax/all_pdf_wishlist.php?type=particip&app=<?=$arParams["EXHIB_CODE"]?>&hb=y" data-hb="y" data-to="wishlist">PDF HB вишлисты</a>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
<div class="table-responsive">

<table class="table">
            <tr>
            <?
                $arFields = CFormMatrix::$arCParticipantField;
                $arFieldsSort = CFormMatrix::$arCParticipantFieldSort;
            ?>
            <? $formID = CFormMatrix::getPFormIDByExh($arResult["EXHIBITION"]["ID"]);?>
                <? foreach ($arFields as $ind => $fieldName):?>
                <th>
                    <?if($arFieldsSort[$ind]):?>
                        <?
                        $orderSort = 'asc';
                        if($arFieldsSort[$ind] == $arResult["SORT"] && $arResult["ORDER"] == 'asc'){
                            $orderSort = 'desc';
                        }
                        ?>
                        <a href="?sort=<?=$arFieldsSort[$ind]?>&order=<?=$orderSort?>" class="sort-title"><?= $fieldName?></a>
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
                $member2ResultID = $arUser[CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"],1)];
            ?>
             <tr class="<?= (($index++ % 2) != 0)?"even":"odd"?>">
                <td class="text-center"><input type="checkbox" name="ACTION[]" value="<?= $arUser["ID"]?>"></td>
                <? //данные из свойств пользователя?>
                <td class="text-center"><?= $arUser["ID"]?></td>
                <td><?= $arUser["LOGIN"]?></td>
                <td>
                	<?= $arUser["UF_PAS"]?>
                	<br />
                	<? $href = $arParams["PATH_TO_KAB"]. "service/pass.php?uid=" . $arUser["ID"]?>
                    <a
                    	href="<?= $href?>"
                        target="_blank"
                        onclick="newWind('<?= $href?>', 500, 300); return false;"
                        ><?= GetMessage("ADM_PARC_EDIT_PAS")?></a>
                </td>
                <? //данные из Общей формы по всем участникам?>
                <td><?= $arUser["FORM_DATA"][17]["VALUE"]?></td>
                <td><?= $arUser["FORM_DATA"][19]["VALUE"]?></td>
                <td><?= $arUser["FORM_DATA"][21]["VALUE"]?></td>
                <td><?= $arUser["FORM_DATA"][22]["VALUE"]?></td>
                <? // данные из форму представителя?>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(32, $formID)]["VALUE"]?> <?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(33, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(36, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(586, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(37, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(497, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(496, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(508, $formID)]["VALUE"]?></td>
                <td><?= $arUser["FORM_USER"][CFormMatrix::getQIDByBase(509, $formID)]["VALUE"]?></td>
                <td class="text-center">
                    <div class="action" id="action_<?=$arUser["ID"]?>">
                    <img src="<?= SITE_TEMPLATE_PATH?>/images/edit.png">
                        <ul class="ul-popup">
                            <li><a href="<?= $APPLICATION->GetCurPageParam("ACTION[]=" . $arUser["ID"] . "&CANCEL=Y",array("ACTION", "CONFIRM"));?>"><?= GetMessage("ADM_PARC_CANCEL")?></a></li>
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
                            <li><a
                                href="<?= $arParams["PATH_TO_KAB"]. "service/count.php?uid=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["ID"] . ""?>"
                                target="_blank"
                                onclick="newWind('<?= $arParams["PATH_TO_KAB"]. "service/count.php?uid=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["ID"] . ""?>', 500, 300); return false;"
                            ><?= GetMessage("ADM_PARC_BILL")?></a></li>
                            <li><a
                            	href="<?= $arParams["PATH_TO_KAB"]. "service/pdf_shedule_particip.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$arResult["EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"]."&type=p&mode=pdf"?>"
                                target="_blank"
                                onclick="newWind('<?= $arParams["PATH_TO_KAB"]. "service/pdf_shedule_particip.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$arResult["EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"]."&type=p&mode=pdf"?>', 600, 700); return false;"
                            ><?= GetMessage("ADM_PARC_SCHEDULE")?></a></li>                            
                            <li><a
                            	href="<?= $arParams["PATH_TO_KAB"]. "service/wishlist_particip.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$arResult["EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"]."&type=p&mode=pdf"?>"
                                target="_blank"
                                onclick="newWind('<?= $arParams["PATH_TO_KAB"]. "service/wishlist_particip.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$arResult["EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"]."&type=p&mode=pdf"?>', 600, 700); return false;"
                            ><?= GetMessage("ADM_PARC_WISHLIST")?></a></li>
                            <? if(isset($arResult["EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"]) && $arResult["EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"] != ''){
                                $appId = $arResult["EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
                            ?>
                            <li><a
                                href="<?= $arParams["PATH_TO_KAB"]. "service/pdf_shedule_particip_hb.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$appId."&type=p&mode=pdf"?>"
                                target="_blank"
                                onclick="newWind('<?= $arParams["PATH_TO_KAB"]. "service/pdf_shedule_particip_hb.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$appId."&type=p&mode=pdf"?>', 600, 700); return false;"
                            ><?= GetMessage("ADM_PARC_SCHEDULE_HB")?></a></li>
                            <li><a
                                href="<?= $arParams["PATH_TO_KAB"]. "service/wishlist_particip_hb.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$appId."&type=p&mode=pdf"?>"
                                target="_blank"
                                onclick="newWind('<?= $arParams["PATH_TO_KAB"]. "service/wishlist_particip_hb.php?id=" . $arUser["ID"]. "&exhib=".$arResult["EXHIBITION"]["CODE"] . "&app=".$appId."&type=p&mode=pdf"?>', 600, 700); return false;"
                            ><?= GetMessage("ADM_PARC_WISHLIST_HB")?></a></li>
                            <?}?>
                        </ul>
                    </div>
                </td>
            </tr>
            <? endforeach;?>
            </table>
            </div>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
