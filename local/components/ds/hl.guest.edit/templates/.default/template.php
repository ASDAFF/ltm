<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$needMorning = $arResult["USER_DATA"]["USER"]["UF_MR"];
$needEvening = $arResult["USER_DATA"]["USER"]["UF_EV"];
?>
<form action="<?=$arResult['FORM_URL']?>" method="POST" enctype="multipart/form-data">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="ID" value="<?= $arResult["USER_DATA"]["ID"] ?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
        <?if($arResult['SAVED']){?>
            <thead>
            <tr>
                <td>
                    <p class="success">Успешно сохранено</p>
                </td>
            </tr>
            </thead>
        <?}elseif($arResult['ERRORS']){?>
            <thead>
            <tr>
                <td>
                    <p class="error">Ошибка</p>
                    <ul>
                        <?foreach ($arResult['ERRORS'] as $error){?>
                            <?c($error)?>
                        <?}?>
                    </ul>
                </td>
            </tr>
            </thead>
        <?}?>
        <tbody>
        <?
        if ($arResult["USER_DATA"]["ID"]) {
            ?>
            <tr>
                <td width="130">ID результата:</td>
                <td><?= $arResult["USER_DATA"]["ID"] ?></td>
            </tr>
            <?
        }
        ?>
        <? foreach ($arResult["FIELD_DATA"] as $fieldName => $fieldData) { ?>
            <? if ($fieldName === "UF_COLLEAGUES") { ?>
                <? foreach ($fieldData['DAY_TIMES'] as $id => $day) {
                    $colleague = [];
                    foreach ($fieldData["ITEMS"] as $value) {
                        if (reset($value['UF_DAYTIME']) == $id) {
                            $colleague = $value;
                        }
                    }
                    $dayTime = $day['XML_ID'];
                    $dayTimeText = $day["VALUE"];
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
                    if ($colleague) { ?>
                        <input type="hidden" name="COLLEAGUE[<?= $dayTime ?>][ID]" value="<?= $colleague['ID'] ?>">
                    <? } else { ?>
                        <input type="hidden" name="COLLEAGUE[<?= $dayTime ?>][UF_DAYTIME][]" value="<?= $id ?>">
                    <? } ?>
                    <? foreach ($fieldData['FIELDS'] as $key => $field) {?>
                        <? if (!in_array($key, $fieldData["HIDDEN_FIELDS"])) { ?>
                            <tr>
                                <td valign="top">
                                    <span><?= ($field['EDIT_FORM_LABEL']?:$key) . ' (' . $dayTimeText . ')' ?></span>
                                </td>
                                <td <?= $key === "UF_DAYTIME" ? "class='checkbox'" : "" ?>>
                                    <? switch ($key) {
                                        case "UF_DAYTIME":
                                            ?>
                                            <?
                                            foreach ($arResult["FIELD_DATA"][$fieldName]["DAY_TIMES"] as $dayKey => $dayValue) {
                                                ?>
                                                <input type="checkbox"
                                                       name="COLLEAGUE[<?= $dayTime ?>][<?= $key ?>][]"
                                                       id="<?= $key . '_' . $dayTime . '_' . $dayValue['ID'] ?>" <?= (in_array($dayValue['ID'], $colleague[$key]) || $colleague[$key] == $dayValue['ID'] || $dayValue['XML_ID'] === $dayTime ) ? "checked" : "" ?>
                                                       value="<?= $dayValue['ID'] ?>"/>
                                                <label for="<?= $key . '_' . $dayTime . '_' . $dayValue['ID'] ?>"><?= $dayValue['VALUE'] ?></label>
                                                <br/>
                                                <?
                                            } ?>
                                            <?
                                            break;
                                        case "UF_SALUTATION":
                                            ?>
                                            <select name="COLLEAGUE[<?= $dayTime ?>][<?= $key ?>]" id="">
                                                <option value="">Не выбрано</option>
                                                <? foreach ($field["ITEMS"] as $itemKey => $itemValue) { ?>
                                                    <option <?= (in_array($itemValue['ID'], $colleague[$key]) || $colleague[$key] == $itemValue['ID']) ? "selected" : "" ?>
                                                            value="<?= $itemValue['ID'] ?>"><?= $itemValue['UF_VALUE'] ?: $itemValue['UF_NAME'] ?></option>
                                                <? } ?>
                                            </select>
                                            <?
                                            break;
                                        case "UF_PHOTO":
                                            if($dayTime === 'morning'){
                                                $APPLICATION->IncludeComponent("rarus:photo.input",
                                                    ".default",
                                                    array(
                                                        "WIDTH" => 110,
                                                        "HEIGHT" => 110,
                                                        "INPUT_NAME" => "COLLEAGUE[". $dayTime ."][UF_PHOTO]",
                                                        "FILE_ID" => $colleague[$key] ?: false,
                                                    ),
                                                    false
                                                );
                                                }
                                            break;
                                        default:
                                            ?>
                                            <input type="text" name="COLLEAGUE[<?= $dayTime ?>][<?= $key ?>]"
                                                   value="<?= $colleague[$key] ?>">
                                            <?
                                            break;
                                    } ?>
                                </td>
                            </tr>
                        <? } else { ?>
                            <input type="hidden" name="COLLEAGUE[<?= $dayTime ?>][<?= $key ?>]"
                                   value="<?= $value ?>">
                        <? } ?>
                    <? } ?>
                <? } ?>
            <? } else { ?>
                <tr>
                    <td valign="top">
                        <span><?= $fieldData["EDIT_FORM_LABEL"]?:$fieldName ?></span>
                    </td>
                    <? switch ($fieldData["USER_TYPE_ID"]) {
                        case "hlblock": ?>
                            <td class="checkbox">
                                <? if (in_array($fieldName, $arResult["FIELD_DATA_CHECKED_ALL"])) { ?>
                                    <? $rnd = randString(7); ?>
                                    <input type="checkbox" name="check_all" id="<?= $rnd ?>" class="check_all"/>
                                    <label for="<?= $rnd ?>">All</label>
                                    <br/>
                                <? } ?>
                                <? if ($fieldData["MULTIPLE"] === "Y") { ?>
                                    <? foreach ($fieldData['ITEMS'] as $itemKey => $value) { ?>
                                        <input type="checkbox" name="<?= $fieldName . '[]' ?>"
                                               id="<?= $fieldName . '_' . $value['ID'] ?>" <?= (in_array($value['ID'], $arResult["USER_DATA"][$fieldName]) || $arResult["USER_DATA"][$fieldName] == $value['ID']) ? "checked" : "" ?>
                                               value="<?= $value['ID'] ?>"/>
                                        <label for="<?= $fieldName . '_' . $value['ID'] ?>"><?= $value['UF_VALUE'] ?></label>
                                        <br/>
                                    <? } ?>
                                <? } else { ?>
                                    <select name="<?= $fieldName ?>" id="">
                                        <? foreach ($fieldData['ITEMS'] as $itemKey => $value) { ?>
                                            <option <?= (in_array($value['ID'], $arResult["USER_DATA"][$fieldName]) || $arResult["USER_DATA"][$fieldName] == $value['ID']) ? "selected" : "" ?>
                                                    value="<?= $value['ID'] ?>"><?= $value['UF_VALUE'] ?: $value['UF_NAME'] ?></option>
                                        <? } ?>
                                    </select>
                                <? } ?>
                            </td>
                            <? break;
                        case "file":
                            ?>
                            <td>
                                <?
                                $APPLICATION->IncludeComponent("rarus:photo.input",
                                    ".default",
                                    array(
                                        "WIDTH" => 110,
                                        "HEIGHT" => 110,
                                        "INPUT_NAME" => $fieldName,
                                        "FILE_ID" => $arResult["USER_DATA"][$fieldName] ?: false,
                                    ),
                                    false
                                );
                                ?>
                            </td>

                            <?
                            break;
                        case "boolean":
                            ?>
                            <td class="checkbox">
                                <input type="hidden" name="<?= $fieldName ?>" value="0">
                                <input type="checkbox" name="<?= $fieldName ?>"
                                       value="1" <?= $arResult["USER_DATA"][$fieldName] ? "checked" : "" ?>>
                                <label for="<?= $fieldName ?>"></label>
                            </td>
                            <?
                            break;
                        default:
                            ?>
                            <td>
                                <input type="text" name="<?= $fieldName ?>" id="<?= $fieldName ?>"
                                       value="<?= $arResult["USER_DATA"][$fieldName] ?>">
                            </td>
                            <?
                            break;
                    } ?>
                </tr>
            <? } ?>
        <? } ?>
        <tr>
            <td colspan="2" class="send">
                <button type="submit">Применить</button>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    $("input.check_all").change(function () {
        var input = $(this);
        var td = input.closest("td.checkbox");

        if (!input.prop("checked")) {
            td.find("input[type=checkbox][name!=" + input.attr("name") + "]").each(function () {
                $(this).prop("checked", false)
            });
        }
        else {
            td.find("input[type=checkbox][name!=" + input.attr("name") + "]").each(function () {
                $(this).prop("checked", true)
            });
        }
    });
</script>