<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$needMorning = $arResult["USER_DATA"]["UF_MORNING"];
$needEvening = $arResult["USER_DATA"]["UF_EVENING"];
?>
<form action="<?= $arResult['FORM_URL'] ?>" method="POST" enctype="multipart/form-data">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="ID" value="<?= $arResult["USER_DATA"]["ID"] ?>">
    <div class="edit-profil pull-overflow guest-form">
        <? foreach ($arResult["FIELD_DATA"]["UF_DAYTIME"]['DAY_TIMES'] as $id => $day) { ?>
            <?
            $colleague = [];
            foreach ($arResult["USER_DATA"]["COLLEAGUES"] as $value) {
                if ($value['UF_DAYTIME'] == $id) {
                    $colleague = $value;
                }
            }
            $dayTime = $day['XML_ID'];
            if ($dayTime === 'morning') {
                if ($needMorning) {
                    $needMorning = false;
                } else {
                    continue;
                }
            }
            if ($dayTime === 'evening') {
                if ($needEvening) {
                    $needEvening = false;
                } else {
                    continue;
                }
            }
            ?>
            <? if ($colleague) { ?>
                <input type="hidden" name="COLLEAGUE[<?= $dayTime ?>][ID]" value="<?= $colleague['ID'] ?>">
            <? }else{ ?>
                <input type="hidden" name="COLLEAGUE[<?= $dayTime ?>][UF_DAYTIME][]" value="<?= $id ?>">
            <? } ?>
            <div class="profil pull-overflow">
                <div class="pull-overflow headline">Коллега на <?= $dayTime === 'morning' ? "утреннюю" : "вечернюю" ?>
                    сессию
                </div>
                <div class="pull-left profil-photo">
                    <?
                    $APPLICATION->IncludeComponent("rarus:photo.input",
                        ".default",
                        array(
                            "WIDTH" => 110,
                            "HEIGHT" => 110,
                            "INPUT_NAME" => "COLLEAGUE[" . $dayTime . "][UF_PHOTO]",
                            "FILE_ID" => $colleague["UF_PHOTO"] ?: false,
                        ),
                        false
                    );
                    unset($arResult["FIELD_DATA"]["UF_PHOTO"]);
                    ?>
                </div>
                <div class="profil-field pull-left">
                    <? foreach ($arResult["FIELD_DATA"] as $fieldName => $fieldData) { ?>
                        <? if (in_array($fieldName, $arParams['HIDDEN_FIELDS'])) {
                            continue;
                        } ?>
                        <? switch ($fieldData["USER_TYPE_ID"]) {
                            case "hlblock":
                                if ($fieldData["MULTIPLE"] === "Y") {
                                    continue;
                                } else { ?>
                                    <div class="form-group">
                                        <label class="control-label"
                                               for="<?= "COLLEAGUE[" . $dayTime . "][" . $fieldName . "]" ?>"><?= $fieldData["EDIT_FORM_LABEL"] ?: $fieldName ?></label>
                                        <div class="data-control">
                                            <select name="<?= "COLLEAGUE[" . $dayTime . "][" . $fieldName . "]" ?>"
                                                    id="">
                                                <? foreach ($fieldData['ITEMS'] as $itemKey => $value) { ?>
                                                    <option <?= (in_array($value['ID'], $colleague[$fieldName]) || $colleague[$fieldName] == $value['ID']) ? "selected" : "" ?>
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
                                        <span class="error-fld"
                                              title="<?= $arResult["FORM_ERRORS"][$FIELD_SID] ?>"></span>
                                    <? endif; ?>
                                    <label class="control-label"
                                           for="<?= "COLLEAGUE[" . $dayTime . "][" . $fieldName . "]" ?>"><?= $fieldData["EDIT_FORM_LABEL"] ?: $fieldName ?></label>
                                    <div class="data-control">
                                        <input type="text"
                                               name="<?= "COLLEAGUE[" . $dayTime . "][" . $fieldName . "]" ?>"
                                               id="<?= $fieldName ?>"
                                            <?= in_array($fieldName, $arParams["DISABLED_FIELD"]) ? "disabled" : "" ?>
                                               value="<?= $colleague[$fieldName] ?>">
                                    </div>
                                </div>
                                <?
                                break;
                        } ?>
                    <? } ?>
                </div>
            </div>
        <? } ?>
        <div class="send-change send">
            <input type="submit" name="web_form_apply" value="Сохранить">
        </div>
    </div>
</form>