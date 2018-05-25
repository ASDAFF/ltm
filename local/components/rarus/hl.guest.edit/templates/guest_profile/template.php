<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arrPriorityField = ["UF_NORTH_AMERICA", "UF_EUROPE", "UF_SOUTH_AMERICA", "UF_AFRICA", "UF_ASIA", "UF_OCEANIA"];
?>
<form action="<?=$arResult['FORM_URL']?>" method="POST" enctype="multipart/form-data">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="ID" value="<?= $arResult["USER_DATA"]["ID"] ?>">
    <div class="edit-profil pull-overflow guest-form">
        <div class="profil pull-overflow">
            <div class="pull-left profil-photo">
                <?
                $APPLICATION->IncludeComponent("rarus:photo.input",
                    ".default",
                    array(
                        "WIDTH" => 110,
                        "HEIGHT" => 110,
                        "INPUT_NAME" => "UF_PHOTO",
                        "FILE_ID" => $arResult["USER_DATA"]["UF_PHOTO"] ?: false,
                    ),
                    false
                );
                unset($arResult["FIELD_DATA"]["UF_PHOTO"]);
                ?>
            </div>
            <div class="profil-field pull-left">
                <? foreach ($arResult["FIELD_DATA"] as $fieldName => $fieldData) { ?>
                    <? switch ($fieldData["USER_TYPE_ID"]) {
                        case "hlblock":
                            if ($fieldData["MULTIPLE"] === "Y") {
                                continue;
                            } else { ?>
                                <div class="form-group">
                                    <label class="control-label" for="<?= $fieldName ?>"><?= $fieldData["EDIT_FORM_LABEL"]?:$fieldName ?></label>
                                    <div class="data-control">
                                        <select name="<?= $fieldName ?>" id="">
                                            <? foreach ($fieldData['ITEMS'] as $itemKey => $value) { ?>
                                                <option <?= (in_array($value['ID'], $arResult["USER_DATA"][$fieldName]) || $arResult["USER_DATA"][$fieldName] == $value['ID']) ? "selected" : "" ?>
                                                        value="<?= $value['ID'] ?>"><?= $value['UF_VALUE'] ?: $value['UF_NAME'] ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            <? } ?>
                            <?
                            break;
                        default:
                            ?>
                            <div class="form-group">
                                <? if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])): ?>
                                    <span class="error-fld" title="<?= $arResult["FORM_ERRORS"][$FIELD_SID] ?>"></span>
                                <? endif; ?>
                                <label class="control-label" for="<?= $fieldName ?>"><?= $fieldData["EDIT_FORM_LABEL"]?:$fieldName ?></label>
                                <div class="data-control">
                                    <?
                                    if ($fieldName === "UF_DESCRIPTION") {
                                        ?>
                                        <textarea class="inputtextarea"
                                                  name="<?= $fieldName ?>"
                                                  id="<?= $fieldName ?>"
                                            <?= in_array($fieldName, $arParams["DISABLED_FIELD"]) ? "disabled" : "" ?>
                                                  cols="40"
                                                  rows="5">
                                            <?= $arResult["USER_DATA"][$fieldName] ?>
                                        </textarea>
                                        <?
                                    } else {
                                        ?>
                                        <input type="text"
                                               name="<?= $fieldName ?>"
                                               id="<?= $fieldName ?>"
                                            <?= in_array($fieldName, $arParams["DISABLED_FIELD"]) ? "disabled" : "" ?>
                                               value="<?= $arResult["USER_DATA"][$fieldName] ?>">
                                        <?
                                    } ?>
                                </div>
                            </div>
                            <?
                            break;
                    } ?>
                <? } ?>
            </div>

            <div class="pull-left company-info priority-wrap" style="display: block; clear: both;">
                <div class="title">Выберите приоритетные направления</div>
                <div class="priority-check-global">
                    <label class="check-global" for="check_priority_global" type="checkbox">Global /
                        Worldwide</label>
                    <input id="check_priority_global" type="checkbox" name="PRIORITY_GLOBAL" value=""
                           class="none"/>
                </div>
                <? foreach ($arResult["FIELD_DATA"] as $fieldName => $fieldData) {
                    if (in_array($fieldName, $arrPriorityField)) {
                        ?>
                        <div class="check-priority">
                            <div class="priority-check-all">
                                <label class="check-all <?= (count($fieldData['ITEMS']) == count($arResult["USER_DATA"][$fieldName])) ? "active-all" : "" ?>"
                                       for="check_<?= $fieldName ?>_ALL" type="checkbox"></label>
                                <input id="check_<?= $fieldName ?>_ALL" type="checkbox" name="<?= $fieldName ?>_ALL"
                                       value=""
                                       class="none"
                                    <?= (count($fieldData['ITEMS']) == count($arResult["USER_DATA"][$fieldName])) ? "checked" : "" ?>/>

                                <a href="javascript:void(0);" class="priority-toggle priority-name">
                                    <ins><?= $fieldData["EDIT_FORM_LABEL"]?:$fieldName ?></ins>
                                </a>
                                <a href="javascript:void(0);" class="priority-toggle priority-switch">
                                    <ins>Показать все страны</ins>
                                </a>
                            </div>

                            <div class="priority-items" id="priority-items-<?= randString(5) ?>">
                                <? foreach ($fieldData['ITEMS'] as $itemKey => $value) { ?>
                                    <label class="check-group <?= (in_array($value['ID'], $arResult["USER_DATA"][$fieldName]) || $arResult["USER_DATA"][$fieldName] == $value['ID']) ? "active-group" : ""; ?>"
                                           for="<?= $fieldName . '_' . $value['ID'] ?>"><?= $value['UF_VALUE'] ?></label>
                                    <input id="<?= $fieldName . '_' . $value['ID'] ?>" type="checkbox"
                                           name="<?= $fieldName . '[]' ?>" value="<?= $value['ID'] ?>"
                                           class="none" <?= (in_array($value['ID'], $arResult["USER_DATA"][$fieldName]) || $arResult["USER_DATA"][$fieldName] == $value['ID']) ? "checked" : "" ?>/>
                                <? } ?>
                            </div>
                        </div>
                        <?
                    }
                } ?>
            </div>
        </div>
        <div class="send-change send">
            <input type="submit" name="web_form_apply" value="Сохранить">
        </div>
    </div>
</form>