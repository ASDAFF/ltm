<?php

//#!/usr/local/bin/php
$_SERVER["DOCUMENT_ROOT"] = "/home/u24601/luxurytravelmart.ru/www";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

/*
 * Первый шаг. Переносим участников из группы подтвержденных в группу старые.
 * Удаляем значения всех лишних полей и сдвигаем анкеты на год назад.
 * Переносим заполненные формы для подтвержденных участников во вновь созданную Форму.
 */


// начальные данные. Формы
$form_from = 1;
$form_to = 14;
$questionsChange = array();

// получим список всех вопросов веб-формы
if (CForm::GetDataByID($form_from, $form, $questions, $answers, $dropdown, $multiselect)) {
    foreach ($answers as $fieldAns => $arrAns) {
        $questionsChange[$fieldAns]["NEW"] = "";
        $questionsChange[$fieldAns]["OLD"] = "";
        switch ($arrAns[0]["FIELD_TYPE"]) {
            case "text":
                $questionsChange[$fieldAns]["OLD"] = "form_text_" . $arrAns[0]["ID"];
                break;
            case "textarea":
                $questionsChange[$fieldAns]["OLD"] = "form_textarea_" . $arrAns[0]["ID"];
                break;
            case "dropdown":
                $questionsChange[$fieldAns]["OLD"] = "form_dropdown_" . $fieldAns;
                break;
            case "email":
                $questionsChange[$fieldAns]["OLD"] = "form_email_" . $arrAns[0]["ID"];
                break;
        }
    }
    if (CForm::GetDataByID($form_to, $form, $questions, $answers, $dropdown, $multiselect)) {
        foreach ($answers as $fieldAns => $arrAns) {
            switch ($arrAns[0]["FIELD_TYPE"]) {
                case "text":
                    $questionsChange[$fieldAns]["NEW"] = "form_text_" . $arrAns[0]["ID"];
                    break;
                case "textarea":
                    $questionsChange[$fieldAns]["NEW"] = "form_textarea_" . $arrAns[0]["ID"];
                    break;
                case "dropdown":
                    $questionsChange[$fieldAns]["NEW"] = "form_dropdown_" . $fieldAns;
                    break;
                case "email":
                    $questionsChange[$fieldAns]["NEW"] = "form_email_" . $arrAns[0]["ID"];
                    break;
            }
        }
    } else {
        $mailto = "diana_box@list.ru";
        $mail = "Ошибки при обработке переноса анкет Luxury\n" . $strError;
        mail($mailto, "Перенос пользователей", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
        echo "Ошибки при обработке переноса анкет сайта Luxury\n" . $strError;
    }
} else {
    $mailto = "diana_box@list.ru";
    $mail = "Ошибки при обработке переноса анкет Luxury\n" . $strError;
    mail($mailto, "Перенос пользователей", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
    echo "Ошибки при обработке переноса анкет сайта Luxury\n" . $strError;
}


// начальные данные. Группы, поля
// Закомментированы группы, чтобы случайно ничего не перенесли
$group_from = 4;
$group_to = 18;
$anketa_prev = "UF_ANKETA_PREV";
$anketa_cur = "UF_ANKETA";
$anketa_next = "UF_ANKETA_NEXT";
$fields = array(
    'UF_SHEDULE_1', 'UF_SHEDULE_2',
    'UF_SHEDULE_3', 'UF_SHEDULE_4',
    'UF_SHEDULE_5', 'UF_SHEDULE_6',
    'UF_SHEDULE_7', 'UF_SHEDULE_8',
    'UF_SHEDULE_9', 'UF_SHEDULE_10',
    'UF_SHEDULE_11', 'UF_SHEDULE_12',
    'UF_WISH_OUT', 'UF_WISH_IN',
    'UF_COUNT_APP', 'UF_REKVIZIT',
    'UF_INVOICE', 'UF_PAY_COUNT',
    'UF_PAY'
);
$prefix_name = "old2013_";
//СПИСОК ПОЛЬЗОВАТЕЛЕЙ
$filter = Array(
    "GROUPS_ID" => Array($group_from)
);
$rsUsers = CUser::GetList(($by = "WORK_COMPANY"), ($order = "asc"), $filter, array("SELECT" => array("UF_*"))); // выбираем пользователей
$countUsers = 0;
$resultFormId = "";
$strError = "";
$message_change = "";
while ($arUser = $rsUsers->Fetch()) {
    $arUserTemp = $arUser;
    foreach ($fields as $value) {
        $arUserTemp[$value] = "";
    }
    $arUserTemp[$anketa_prev] = $arUser[$anketa_cur];
    $arUserTemp[$anketa_cur] = $arUser[$anketa_next];
    $arUserTemp[$anketa_next] = "";
    $arUserTemp["GROUP_ID"] = array($group_to);
    $arUserTemp["LOGIN"] = $prefix_name . $arUser["LOGIN"];

    if ($arUser[$anketa_cur]) {
        $arValuesTmp = CFormResult::GetDataByIDForHTML($arUser[$anketa_cur], "Y");
        $arValues = array();
        foreach ($questionsChange as $sid => $ans) {
            $arValues[$ans["NEW"]] = $arValuesTmp[$ans["OLD"]];
        }
        if ($RESULT_ID = CFormResult::Add($form_to, $arValues)) {
            $message_change .= "Добавлен результат ID " . $RESULT_ID . "\n";
			$arUserTemp[$anketa_prev] = $RESULT_ID;
        } else {
            $strError .= "Не добавлен результат пользователя с ID " . $arUser["ID"] . "\n";
        }
    }

    //Изменяем пользователя
    $user = new CUser;
    $strTmpError = "";
    $user->Update($arUser["ID"], $arUserTemp);
    $strTmpError = $user->LAST_ERROR;
    if ($strTmpError) {
        $strError .= " User ID: " . $arUser["ID"] . " " . $strTmpError . "\n";
    }
    else{
        $message_change .= " User ID: " . $arUser["ID"] . "\n";
    }

    $countUsers++;
}
if ($strError) {
    $mailto = "diana_box@list.ru";
    $mail = "Ошибки при обработке встреч с сайта Luxury\n" . $strError;
    mail($mailto, "Перенос пользователей", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
    echo "Ошибки при обработке встреч с сайта Luxury\n" . $strError;
} else {
    $mailto = "diana_box@list.ru";
    $mail = "Нет ошибок при обработке встреч с сайта Luxury\n" . $message_change;
    mail($mailto, "Перенос пользователей", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
    echo "Нет ошибок при обработке встреч с сайта Luxury<br />";
    echo $message_change;
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>