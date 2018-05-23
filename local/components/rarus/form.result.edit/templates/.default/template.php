<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<form action="" method="POST" enctype="multipart/form-data">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="ID" value="<?= $arResult["USER_DATA"]["ID"] ?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
        <tbody>
        <?
        if ($arResult["USER_DATA"]["ID"]) {
            ?>
            <tr>
                <td width="130">ID:</td>
                <td><?= $arResult["USER_DATA"]["ID"] ?></td>
            </tr>
            <?
        }
        ?>
        <? foreach ($arResult["FIELD_DATA"] as $fieldName => $fieldData) { ?>
            <? if ($fieldName === "UF_COLLEAGUES") { ?>
                <? foreach ($fieldData['ITEMS'] as $colleague) { ?>
                    <? $dayTime = $fieldData['DAY_TIMES'][reset($colleague["UF_DAYTIME"])];
                    $dayTimeText = $dayTime["VALUE"];
                    $dayTimeCode = strtoupper($dayTime["XML_ID"]); ?>
                    <? foreach ($colleague as $key => $value) { ?>
                        <? if (!in_array($key, $fieldData["HIDDEN_FIELDS"])) { ?>
                            <tr>
                                <td valign="top">
                                    <span><?= $key . '(' . $dayTimeText . ')' ?></span>
                                </td>
                                <td <?= $key === "UF_DAYTIME" ? "class='checkbox'" : "" ?>>
                                    <? switch ($key) {
                                        case "UF_DAYTIME":
                                            ?>
                                            <?
                                            foreach ($arResult["FIELD_DATA"][$fieldName]["DAY_TIMES"] as $dayKey => $dayValue) {
                                                ?>
                                                <input type="checkbox"
                                                       name="COLLEAGUE[<?= $dayTimeCode ?>][<?= $key ?>][]"
                                                       id="<?= $key . '_' . $dayTimeCode . '_' . $dayValue['ID'] ?>" <?= (in_array($dayValue['ID'], $value) || $value == $dayValue['ID']) ? "checked" : "" ?>
                                                       value="<?= $dayValue['ID'] ?>"/>
                                                <label for="<?= $key . '_' . $dayTimeCode . '_' . $dayValue['ID'] ?>"><?= $dayValue['VALUE'] ?></label>
                                                <br/>
                                                <?
                                            } ?>
                                            <?
                                            break;
                                        case "UF_SALUTATION":
                                            ?>
                                            <select name="COLLEAGUE[<?= $dayTimeCode ?>][<?= $key ?>]" id="">
                                                <? foreach ($arResult["FIELD_DATA"]["UF_SALUTATION"]["ITEMS"] as $itemKey => $itemValue) { ?>
                                                    <option <?= (in_array($itemValue['ID'], $value) || $value == $itemValue['ID']) ? "selected" : "" ?>
                                                            value="<?= $itemValue['ID'] ?>"><?= $itemValue['UF_VALUE'] ?: $itemValue['UF_NAME'] ?></option>
                                                <? } ?>
                                            </select>
                                            <?
                                            break;
                                        default:
                                            ?>
                                            <input type="text" name="COLLEAGUE[<?= $dayTimeCode ?>][<?= $key ?>]"
                                                   value="<?= $value ?>">
                                            <?
                                            break;
                                    } ?>
                                </td>
                            </tr>
                        <? } else { ?>
                            <input type="hidden" name="COLLEAGUE[<?= $dayTimeCode ?>][<?= $key ?>]" value="<?= $value ?>">
                        <? } ?>
                    <? } ?>
                <? } ?>
            <? } else { ?>
                <tr>
                    <td valign="top">
                        <span><?= $fieldName ?></span>
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
                                <input type="file" name="<?= $fieldName ?>" value="">
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
                <button type="submit"><?= GetMessage("FORM_APPLY") ?></button>
                <button type="reset"><?= GetMessage("FORM_RESET"); ?></button>
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