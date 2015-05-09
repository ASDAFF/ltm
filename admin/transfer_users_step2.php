<?php

//#!/usr/local/bin/php
$_SERVER["DOCUMENT_ROOT"] = "/home/u24601/luxurytravelmart.ru/www";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

/*
 * Второй шаг. Дублируем участников из группы подтвержденных прошлого года в группу неподтвержденные этого года, если они регистрировались из личного кабинета.
 * Удаляем префикс у участников, которых дублируем.
 * Переносим заполненные формы из пререгестрации для подтвержденных участников в Форму этого года.
 */


// начальные данные. Формы
$form_from = 10;
$form_to = 1;
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
$group_from = 18;
$group_to = 3;
$anketa_prev = "UF_ANKETA_PREV";
$anketa_cur = "UF_ANKETA";
$anketa_next = "UF_ANKETA_NEXT";

$prefix_name = "old2013_";
//СПИСОК ПОЛЬЗОВАТЕЛЕЙ
$filter = Array(
    "GROUPS_ID" => Array($group_from),
    "!UF_ANKETA" => ""
);
$rsUsers = CUser::GetList(($by = "WORK_COMPANY"), ($order = "asc"), $filter, array("SELECT" => array("UF_*"))); // выбираем пользователей
$countUsers = 0;
$resultFormId = "";
$strError = "";
$message_change = "";
while ($arUser = $rsUsers->Fetch()) {
    $countUsers++;
	$arFields = array();
	// Добавляем Результат
    if ($arUser[$anketa_cur]) {
        $arValuesTmp = CFormResult::GetDataByIDForHTML($arUser[$anketa_cur], "Y");
        $arValues = array();
		$arFields = Array(
		  "EMAIL"             => $arValuesTmp["form_email_429"],
		  "LOGIN"             => $arValuesTmp["form_email_429"],
		  "GROUP_ID"          => array($group_to),
		  "PASSWORD"          => $arUser["ADMIN_NOTES"],
		  "CONFIRM_PASSWORD"  => $arUser["ADMIN_NOTES"],
		  "ADMIN_NOTES"       => $arUser["ADMIN_NOTES"],
		  "UF_ANKETA"         => $arUser["UF_ANKETA"],
		  "NAME"	          => $arValuesTmp["form_text_419"],
		  "LAST_NAME"         => $arValuesTmp["form_text_420"],
		  "WORK_COMPANY"      => $arValuesTmp["form_text_405"],
		  "UF_SOURCE"		  => "ЛК",
		);
        foreach ($questionsChange as $sid => $ans) {
            $arValues[$ans["NEW"]] = $arValuesTmp[$ans["OLD"]];
        }
        if ($RESULT_ID = CFormResult::Add($form_to, $arValues)) {
            $message_change .= "Добавлен результат ID " . $RESULT_ID . "\n";
			$arFields["UF_ANKETA"] = $RESULT_ID;
        } else {
            $strError .= "Не добавлен результат пользователя с ID " . $arUser["ID"] . "\n";
        }
    }
	/**/// Добавляем Пользователя
    $user = new CUser;
	$strTmpError = $user->Add($arFields);
    if (!$strTmpError) {
        $strError .= " Error: User ID: " . $arUser["ID"] . "\n";
    }
    else{
        $message_change .= " User ID: " . $arUser["ID"] . "\n";
    }
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