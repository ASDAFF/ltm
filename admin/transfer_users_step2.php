<?php

//#!/usr/local/bin/php
$_SERVER["DOCUMENT_ROOT"] = "/home/u24601/luxurytravelmart.ru/www";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

/*
 * ������ ���. ��������� ���������� �� ������ �������������� �������� ���� � ������ ���������������� ����� ����, ���� ��� ���������������� �� ������� ��������.
 * ������� ������� � ����������, ������� ���������.
 * ��������� ����������� ����� �� �������������� ��� �������������� ���������� � ����� ����� ����.
 */


// ��������� ������. �����
$form_from = 10;
$form_to = 1;
$questionsChange = array();

// ������� ������ ���� �������� ���-�����
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
        $mail = "������ ��� ��������� �������� ����� Luxury\n" . $strError;
        mail($mailto, "������� �������������", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
        echo "������ ��� ��������� �������� ����� ����� Luxury\n" . $strError;
    }
} else {
    $mailto = "diana_box@list.ru";
    $mail = "������ ��� ��������� �������� ����� Luxury\n" . $strError;
    mail($mailto, "������� �������������", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
    echo "������ ��� ��������� �������� ����� ����� Luxury\n" . $strError;
}

// ��������� ������. ������, ����
// ���������������� ������, ����� �������� ������ �� ���������
$group_from = 18;
$group_to = 3;
$anketa_prev = "UF_ANKETA_PREV";
$anketa_cur = "UF_ANKETA";
$anketa_next = "UF_ANKETA_NEXT";

$prefix_name = "old2013_";
//������ �������������
$filter = Array(
    "GROUPS_ID" => Array($group_from),
    "!UF_ANKETA" => ""
);
$rsUsers = CUser::GetList(($by = "WORK_COMPANY"), ($order = "asc"), $filter, array("SELECT" => array("UF_*"))); // �������� �������������
$countUsers = 0;
$resultFormId = "";
$strError = "";
$message_change = "";
while ($arUser = $rsUsers->Fetch()) {
    $countUsers++;
	$arFields = array();
	// ��������� ���������
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
		  "UF_SOURCE"		  => "��",
		);
        foreach ($questionsChange as $sid => $ans) {
            $arValues[$ans["NEW"]] = $arValuesTmp[$ans["OLD"]];
        }
        if ($RESULT_ID = CFormResult::Add($form_to, $arValues)) {
            $message_change .= "�������� ��������� ID " . $RESULT_ID . "\n";
			$arFields["UF_ANKETA"] = $RESULT_ID;
        } else {
            $strError .= "�� �������� ��������� ������������ � ID " . $arUser["ID"] . "\n";
        }
    }
	/**/// ��������� ������������
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
    $mail = "������ ��� ��������� ������ � ����� Luxury\n" . $strError;
    mail($mailto, "������� �������������", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
    echo "������ ��� ��������� ������ � ����� Luxury\n" . $strError;
} else {
    $mailto = "diana_box@list.ru";
    $mail = "��� ������ ��� ��������� ������ � ����� Luxury\n" . $message_change;
    mail($mailto, "������� �������������", $mail, "Content-Type: text/plain; charset=windows-1251\r\n");
    echo "��� ������ ��� ��������� ������ � ����� Luxury<br />";
    echo $message_change;
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>