<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/header.php");
include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/HLfunction.php");

//echo "<pre>";
//print_r($arResult['FORM_DATA_GUEST']);
//echo "</pre>";
?>
<script>
    AjaxPatch = <?=CUtil::PhpToJSObject($arResult["AJAX_PATCH"])?>
</script>
<div class="registr_buy">
    <div class="exhibition-select-block">
        <div class="title"><?= GetMessage("R_B_EXTEND_TITLE") ?></div>
        <div class="exhibition-block-validate">
            <div class="choose-b"><?= GetMessage("R_B_EXHIBITION_TITLE") ?></div>
            <? if (!empty($arResult["EXHIBITION"])): ?>
                <table class="exh-select ">
                    <thead>
                    <tr>
                        <th style="font-size: 12px;"><?= GetMessage("R_MORNING") ?></th>
                        <th style="font-size: 12px;"><?= GetMessage("R_EVENING") ?></th>
                        <th style="font-size: 12px;">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($arResult["EXHIBITION"] as $exhibID => $arItem): ?>
                        <? if ($arItem['PROPERTIES']['FOR_G']['VALUE'] != 'Y')//пропускаем, если выставка закрыта для гостей
                        {
                            continue;
                        }
                        ?>
                        <tr>
                            <td class="morning_b">
                                <? if ($arItem["STATUS"]["GUEST"]["MORNING"] == "Y") { ?>
                                    <label class="check-exh" for="ckeck_m_<?= $arItem['ID'] ?>"></label>
                                    <input id="ckeck_m_<?= $arItem['ID'] ?>" type="checkbox"
                                           name="EXHIBITION[<?= $arItem['ID'] ?>][MORNING]" value="843" class="hide"
                                           data-text="регистрация на рабочую сессию"/>
                                <? } ?>
                            </td>
                            <td class="evening_b">
                                <? if ($arItem["STATUS"]["GUEST"]["EVENING"] == "Y") { ?>
                                    <label class="check-exh" for="ckeck_e_<?= $arItem['ID'] ?>"></label>
                                    <input id="ckeck_e_<?= $arItem['ID'] ?>" type="checkbox"
                                           name="EXHIBITION[<?= $arItem['ID'] ?>][EVENING]" value="844" class="hide"
                                           data-text="регистрация на вечерний коктейль"/>
                                <? } ?>
                            </td>
                            <td class="name_b">
                                <span class="name_exhibition"><?= $arItem['NAME'] ?></span>
                            </td>
                        </tr>

                    <? endforeach; ?>
                    </tbody>
                </table>
                <span id="choice"><?= GetMessage("R_B_YOUR_CHOICE") ?>: <span id="selected-exhibition"></span></span>
            <? else: ?>
                <div><?= GetMessage("R_EXHIBITIONS_NONE") ?></div>
            <? endif; ?>
        </div>
    </div>
    <div class="line-sep-g"></div>


    <? /* БЛОК НЕМНОГО ИНФОРМАЦИИ О ВАС*/ ?>


    <div class="some-information">
        <div class="choose"><?= GetMessage("R_B_SOME_INFORMATION") ?></div>
        <?
        $arrFields = $arResult['FORM_DATA_GUEST'];
        $arrFieldsColleague = $arResult['FORM_DATA_GUEST_COLLEAGUE'];
        ?>
        <? /*Общая информация*/ ?>
        <div class="block-common">
            <? /*название компании*/ ?>
            <?= ShowText($arrFields['UF_COMPANY'], "COMPANY_NAME", "require en capitals"); ?>
            <? /*Вид деятельности*/ ?>
            <?= ShowGroupCheckBox($arrFields['UF_PRIORITY_AREAS'], "BUSINESS_TYPE"); ?>
            <? /*Фактический адрес компании*/ ?>
            <?= ShowText($arrFields['UF_ADDRESS'], "COMPANY_ADDRESS", "require en capitals"); ?>
            <? /*Индекс*/ ?>
            <?= ShowText($arrFields['UF_POSTCODE'], "INDEX", "require index"); ?>
            <? /*Город*/ ?>
            <?= ShowText($arrFields['UF_CITY'], "CITY", "require en capitals"); ?>
            <? /*Страна*/ ?>
            <?= ShowDropDown($arrFields['UF_COUNTRY'], "COUNTRY"); ?>
            <? /*Страна другая*/ ?>
            <?= ShowText($arrFields['UF_COUNTRY_OTHER'], "COUNTRY_OTHER", "hide country_other en"); ?>
            <div class="line-sep-small"></div>
            <? /*Имя*/ ?>
            <?= ShowText($arrFields['UF_NAME'], "NAME", "require en capitals"); ?>
            <? /*Фамилия*/ ?>
            <?= ShowText($arrFields['UF_SURNAME'], "LAST_NAME", "require en capitals"); ?>
            <? /*Обращение*/ ?>
            <?= ShowDropDown($arrFields['UF_SALUTATION'], "SALUTATION"); ?>
            <? /*Должность*/ ?>
            <?= ShowText($arrFields['UF_POSITION'], "JOB_POST", "require en capitals"); ?>
            <? /*Телефон*/ ?>
            <?= ShowText($arrFields['UF_PHONE'], "PHONE", "require phone"); ?>
            <? /*мобильный телефон*/ ?>
            <?= ShowText($arrFields['UF_MOBILE'], "MOBILE_PHONE", "en phone"); ?>
            <? /*Скайп */ ?>
            <?= ShowText($arrFields['UF_SKYPE'], "SKYPE", "skype"); ?>
            <? /*email*/ ?>
            <?= ShowText($arrFields['UF_EMAIL'], "EMAIL", "require email"); ?>
            <? /*confemail*/ ?>
            <?= ShowText($arrFields['UF_EMAIL'], "CONF_EMAIL", "require confemail", "Введите E-mail ещё раз"); ?>
            <? /*сайт*/ ?>
            <?= ShowText($arrFields['UF_SITE'], "WEB_SITE", "web"); ?>
            <div class="line-sep-small"></div>
        </div>

        <? /*Утро */ ?>
        <div class="block-morning hide">
            <? /*Описание компании*/ ?>
            <?= ShowTextArea($arrFields["UF_DESCRIPTION"], "COMPANY_DESCRIPTION", "description en"); ?>
            <div class="priority-areas">
                <div class="priority-title"><?= GetMessage("R_B_SELECT_PRIORITY_AREAS") ?></div>
                <div class="priority-check-global">
                    <label class="check-global" for="check_priority_global"
                           type="checkbox"><?= GetMessage("R_B_CHECK_GLOBAL") ?></label>
                    <input id="check_priority_global" type="checkbox" name="PRIORITY_GLOBAL" value="" class="none"/>
                </div>
                <div class="priority-wrap">
                    <? /*North America*/ ?>
                    <?= ShowPriorityAreasCheckBox($arrFields["UF_NORTH_AMERICA"], "NORTH_AMERICA", GetMessage("R_B_CHECK_ALL")); ?>

                    <? /*Europe*/ ?>
                    <?= ShowPriorityAreasCheckBox($arrFields["UF_EUROPE"], "EUROPE", GetMessage("R_B_CHECK_ALL")); ?>

                    <? /*South America*/ ?>
                    <?= ShowPriorityAreasCheckBox($arrFields["UF_SOUTH_AMERICA"], "SOUTH_AMERICA", GetMessage("R_B_CHECK_ALL")); ?>

                    <? /*Africa*/ ?>
                    <?= ShowPriorityAreasCheckBox($arrFields["UF_AFRICA"], "AFRICA", GetMessage("R_B_CHECK_ALL")); ?>

                    <? /*Asia*/ ?>
                    <?= ShowPriorityAreasCheckBox($arrFields["UF_ASIA"], "ASIA", GetMessage("R_B_CHECK_ALL")); ?>

                    <? /*Oceania*/ ?>
                    <?= ShowPriorityAreasCheckBox($arrFields["UF_OCEANIA"], "OCEANIA", GetMessage("R_B_CHECK_ALL")); ?>
                </div>
            </div>
            <div class="line-sep-small"></div>
            <div class="authorize-title"><?= GetMessage("R_B_AUTHORIZE_TITLE") ?></div>
            <? /*Введите логин/гостевое имя*/ ?>
            <?= ShowText($arrFields["UF_LOGIN"], "LOGIN", "login"); ?>
            <? /*Введите пароль*/ ?>
            <?= ShowPassword($arrFields["UF_PASSWORD"], "PASSWORD", "pass en"); ?>
            <? /*Повторите пароль*/ ?>
            <?= ShowPassword($arrFields["UF_PASSWORD"], "CONF_PASSWORD", "confpass", "Повторите пароль"); ?>
            <div class="authorize-epilogue"><?= GetMessage("R_B_AUTHORIZE_EPILOGUE") ?></div>
            <div class="line-sep-small"></div>

            <div class="authorize-title"><?= GetMessage("R_B_COLLEAGUE_MORNING_TITLE") ?></div>
            <div class="collegue-block">
                <? /*Имя коллеги на утро*/ ?>
                <?= ShowText($arrFieldsColleague["UF_NAME"], "COLLEAGUE[MORNING][NAME]", "en collegue capitals", GetMessage("R_B_NAME")); ?>
                <? /*Фамилия коллеги на утро*/ ?>
                <?= ShowText($arrFieldsColleague["UF_SURNAME"], "COLLEAGUE[MORNING][LAST_NAME]", "en capitals", GetMessage("R_B_LAST_NAME")); ?>
                <? /*Обращение*/ ?>
                <?= ShowDropDown($arrFieldsColleague["UF_SALUTATION"], "COLLEAGUE[MORNING][SALUTATION]", GetMessage("R_B_SALUTATION")); ?>
                <? /*Должность коллеги на утро*/ ?>
                <?= ShowText($arrFieldsColleague["UF_JOB_TITLE"], "COLLEAGUE[MORNING][JOB_POST]", "en capitals", GetMessage("R_B_JOB_POST")); ?>
                <? /*EAMIL коллеги на утро*/ ?>
                <?= ShowText($arrFieldsColleague["UF_EMAIL"], "COLLEAGUE[MORNING][EMAIL]", "email", GetMessage("R_B_EMAIL")); ?>
                <? /*Моб.телефон коллеги на утро*/ ?>
                <?= ShowText($arrFieldsColleague["UF_MOBILE_PHONE"], "COLLEAGUE[MORNING][PHONE]", "en phone", GetMessage("R_B_PHONE")); ?>
            </div>
            <div class="line-sep-small"></div>
        </div>

        <? //вечер?>
        <div class="block-evening hide">
            <div class="priority-title"><?= GetMessage("R_B_COLLEAGUE_EVENING_TITLE") ?></div>
            <div class="line-sep-small"></div>
            <div class="collegue-block">
                <? /*Имя коллеги на вечер*/ ?>
                <?= ShowText($arrFieldsColleague["UF_NAME"], "COLLEAGUE[EVENING][NAME]", "en collegue capitals", GetMessage("R_B_NAME")); ?>
                <? /*Фамилия коллеги на вечер*/ ?>
                <?= ShowText($arrFieldsColleague["UF_SURNAME"], "COLLEAGUE[EVENING][LAST_NAME]", "en capitals", GetMessage("R_B_LAST_NAME")); ?>
                <? /*Обращение*/ ?>
                <?= ShowDropDown($arrFieldsColleague["UF_SALUTATION"], "COLLEAGUE[EVENING][SALUTATION]", GetMessage("R_B_SALUTATION")); ?>
                <? /*Должность коллеги на вечер*/ ?>
                <?= ShowText($arrFieldsColleague["UF_JOB_TITLE"], "COLLEAGUE[EVENING][JOB_POST]", "en capitals", GetMessage("R_B_JOB_POST")); ?>
                <? /*EAMIL коллеги на вечер*/ ?>
                <?= ShowText($arrFieldsColleague["UF_EMAIL"], "COLLEAGUE[EVENING][EMAIL]", "email", GetMessage("R_B_EMAIL")); ?>
            </div>

            <div class="line-sep-small"></div>
        </div>
    </div>
    <div class="line-sep-g"></div>
    <? /* кнопки подтверждения регистрации*/ ?>
    <label class="check-register" for="ckeck_register"><?= GetMessage("R_B_CONF_TERMS") ?></label>
    <input id="ckeck_register" type="checkbox" name="CONFIRM_TERMS" class="hide"/>

    <input type="button" class="register-button" value="<?= GetMessage("R_B_SEND") ?>" name="register_button">
</div>

