<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true

    )die();
/* --------------- TO DO ------------------- */

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if (strLen($arParams["PATH_TO_KAB"]) <= 0) {
    $arParams["PATH_TO_KAB"] = "/admin/";
}

if (strLen($arParams["GROUP_ID"]) <= 0) {
    $arParams["GROUP_ID"] = "CURE";
}

if (strLen($arParams["AUTH_PAGE"]) <= 0) {
    $arParams["AUTH_PAGE"] = "/admin/login.php";
}

if (strLen($arParams["USER"]) <= 0) {
    $arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователям!<br />";
}

if (strLen($arParams["FORM_ID"]) <= 0) {
    $arResult["ERROR_MESSAGE"] = "Не введены данные по Результатам пользователей!<br />";
}
/* --------------------------------------------------- */
//          ФОРМИРУЕМ ФИЛЬТР ПО АЛФАВИТУ             //
/* --------------------------------------------------- */
$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0";
$letter_filt = '';
$isLetter = true;
$thisUrl = $APPLICATION->GetCurPage();
for ($i = 0; $i < 27; $i++) {
    if (isset($_REQUEST['letter']) && $_REQUEST['letter'] == $letters[$i]) {
        if ($i == 26) {
            $letter_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">0-9</span>';
            $isLetter = false;
        } else {
            $letter_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">' . $letters[$i] . '</span>';
            $isLetter = false;
        }
    } else {
        if ($i == 26) {
            $letter_filt .= '<a href="' . $thisUrl . '?letter=' . $letters[$i] . '" style="margin:0 3px 0;">0-9</a>';
        } else {
            $letter_filt .= '<a href="' . $thisUrl . '?letter=' . $letters[$i] . '" style="margin:0 3px 0;">' . $letters[$i] . '</a>';
        }
    }
}
if ($isLetter) {
    $letter_filt = $letter_filt . '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">All</span>';
} else {
    $letter_filt = $letter_filt . '<a href="' . $thisUrl . '" style="margin:0 3px 0;">All</a>';
}
$arResult["FILTER"]["ALP"] = $letter_filt;

/* --------------------------------------------------- */
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/* --------------------------------------------------- */
if ($arResult["ERROR_MESSAGE"] == '') {
    //СПИСОК ПОЛЬЗОВАТЕЛЕЙ
    $filter = Array(
        "GROUPS_ID" => Array($arParams["USER"])
    );
    $rsUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter, array("SELECT" => array("UF_*"))); // выбираем пользователей
    $countUsers = 0;
    $resultFormId = "";
    while ($arUsersTemp = $rsUsers->Fetch()) {
        $arUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
        if ($arParams["GROUP_ID"] == "PREV" || $arParams["GROUP_ID"] == "PREV13") {
            $arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA_PREV"];
        } else {
            $arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
        }
        $arUsers[$countUsers]["UF_PAY_COUNT"] = $arUsersTemp["UF_PAY_COUNT"];
        $resultFormId .= " | " . $arUsers[$countUsers]["UF_ANKETA"];
        $countUsers++;
    }
    $resultFormId = substr($resultFormId, 3);
    $arResult["USERS"]["COUNT"] = $countUsers;

    //РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
    CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $resultFormId));

    //СПИСОК КОЛОНОК ДЛЯ ТАБЛИЦЫ
    $countReal = 0;
    $QUESTION_ID = "";
    $arResult["FIELDS"]["COUNT"] = 0;
    foreach ($arrColumns as $columnName) {
        $arResult["FIELDS"][$countReal]["ID"] = $columnName["ID"];
        $arResult["FIELDS"][$countReal]["TITLE"] = $columnName["TITLE"];
        $countReal++;
        if ($columnName["TITLE"] == "Area of business") {
            $QUESTION_ID = $columnName["ID"];
        }
    }
    $arResult["FIELDS"]["COUNT"] = $countReal;

    //СПИСОК КАТЕГОРИЙ
    $arCategory = array();
    $countCategory = 0;
    $filterAr = array();
    $rsAnswersMean = CFormAnswer::GetList($QUESTION_ID, $by = "s_sort", $order = "asc", array(), $is_filtered);
    while ($arAnswer = $rsAnswersMean->Fetch()) {
        $arCategory[$countCategory]["TITLE"] = $arAnswer["MESSAGE"];
        $arCategory[$countCategory]["COMPANYS"] = array();
        $arCategory[$countCategory]["COUNT"] = 0;
        $countCategory++;
    }
    for ($i = 0; $i < $countUsers; $i++) {
        for ($k = 0; $k < $countCategory; $k++) {
            $arResult["USERS"][$i]["ID"] = $arUsers[$i]["ID"];
            $arResult["USERS"][$i]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
            $flag = false;
            $flagCat = false;
            foreach ($arrAnswers[$arUsers[$i]["UF_ANKETA"]][$QUESTION_ID] as $ansField) {
                if ($ansField["ANSWER_TEXT"] == $arCategory[$k]["TITLE"]) {
                    $flagCat = true;
                }
                if (isset($_REQUEST['letter']) && $_REQUEST['letter'] != '') {
                    if ($arParams["GROUP_ID"] == "PREV") {
                        if ($_REQUEST['letter'] == '0' && ctype_digit($arrAnswers[$arUsers[$i]["UF_ANKETA"]][228][687]["USER_TEXT"][0])) {
                            $flag = true;
                        } else {
                            if (strtoupper($arrAnswers[$arUsers[$i]["UF_ANKETA"]][251][732]["USER_TEXT"][0]) == $_REQUEST['letter']) {
                                $flag = true;
                            } else {
                                $flag = false;
                            }
                        }
                    }
					elseif($arParams["GROUP_ID"] == "PREV13"){
                        if ($_REQUEST['letter'] == '0' && ctype_digit($arrAnswers[$arUsers[$i]["UF_ANKETA"]][275][997]["USER_TEXT"][0])) {
                            $flag = true;
                        } else {
                            if (strtoupper($arrAnswers[$arUsers[$i]["UF_ANKETA"]][275][997]["USER_TEXT"][0]) == $_REQUEST['letter']) {
                                $flag = true;
                            } else {
                                $flag = false;
                            }
                        }
					}
					else {
                        if ($_REQUEST['letter'] == '0' && ctype_digit($arrAnswers[$arUsers[$i]["UF_ANKETA"]][6][10]["USER_TEXT"][0])) {
                            $flag = true;
                        } else {
                            if (strtoupper($arrAnswers[$arUsers[$i]["UF_ANKETA"]][6][10]["USER_TEXT"][0]) == $_REQUEST['letter']) {
                                $flag = true;
                            } else {
                                $flag = false;
                            }
                        }
                    }
                } else {
                    $flag = true;
                }
            }
            if ($flag && $flagCat) {
                for ($j = 0; $j < $countReal; $j++) {
                    foreach ($arrAnswers[$arUsers[$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning) {
                        $arCategory[$k]["COMPANYS"]["ID"][$arCategory[$k]["COUNT"]] = $arUsers[$i]["ID"];
                        if ($ansMeaning["TITLE"] == "Company or Hotel") {
                            $arCategory[$k]["COMPANYS"]["COMPANY"][$arCategory[$k]["COUNT"]] = $ansMeaning["USER_TEXT"];
                        } elseif (strpos($ansMeaning["TITLE"], "Short company description") !== false) {
                            $arCategory[$k]["COMPANYS"]["DESC"][$arCategory[$k]["COUNT"]] = $ansMeaning["USER_TEXT"];
                        } elseif ($ansMeaning["TITLE"] == "Company's web-site") {
                            $arCategory[$k]["COMPANYS"]["SITE"][$arCategory[$k]["COUNT"]] = $ansMeaning["USER_TEXT"];
                        }
                    }
                }
                $arCategory[$k]["COUNT"]++;
                break;
            }
            //print_r($arrAnswers[$arUsers[$i]["UF_ANKETA"]]);
        }
    }

    //СОРТИРОВКА РЕЗУЛЬТИРУЮЩЕГО МАССИВА
    for ($k = 0; $k < $countCategory; $k++) {
        array_multisort($arCategory[$k]["COMPANYS"]["COMPANY"], $arCategory[$k]["COMPANYS"]["ID"], $arCategory[$k]["COMPANYS"]["DESC"], $arCategory[$k]["COMPANYS"]["SITE"]);
    }
    $arResult["COUNT"] = $countCategory;
    $arResult["CATEGORIES"] = $arCategory;
}
//echo "<pre>"; print_r($arCategory); echo "</pre>";
//echo "<pre>"; print_r(); echo "</pre>";

$this->IncludeComponentTemplate();
?>