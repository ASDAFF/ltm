<?
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss("/cabinet/edit/style.css");
Asset::getInstance()->addJs("/cabinet/edit/script.js");

global $USER;
if ($USER->IsAdmin() && isset($_REQUEST["UID"])) {
    $userId = intval($_REQUEST["UID"]);
} else {
    $userId = $USER->GetID();
}

$rsUser = CUser::GetList(($by = false), ($order = false), array("ID" => $userId), array("SELECT" => array("UF_*")));
$arUser = $rsUser->Fetch();
$resultId = $arUser["UF_ID_COMP"];
$curPage = "/cabinet/" . $_REQUEST["EXHIBIT_CODE"] . "/edit/profile/" . (isset($_REQUEST["UID"]) ? "?UID={$_REQUEST["UID"]}" : "");

//получение id выставки
$exhCode = trim($_REQUEST["EXHIBIT_CODE"]);
if ($exhCode && CModule::IncludeModule("iblock")) {
    $rsExhib = CIBlockElement::GetList(array("sort" => 'asc'), array("ACTVE" => "Y", "CODE" => $exhCode), false, false, array("ID", "CODE", "NAME", "PROPERTY_SHORT_NAME", "PROPERTY_DATE", "PROPERTY_PARTICIPANT_EDIT", "PROPERTY_GUEST_EDIT"));
    if ($arExhib = $rsExhib->Fetch()) {
        $formID = CFormMatrix::getPFormIDByExh($arExhib["ID"]);
        $formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);//получение имени свойства пользователя для текущей выставки
        $resultId = $arUser[$formPropName];
        $exhName = $arExhib["PROPERTY_SHORT_NAME_VALUE"];
        $exhDate = $arExhib["PROPERTY_DATE_VALUE"];
        $exhParticipantEdit = $arExhib["PROPERTY_PARTICIPANT_EDIT_VALUE"];
        $exhGuestEdit = $arExhib["PROPERTY_GUEST_EDIT_VALUE"];

    }
}

//тут запрещается редактирование
if ("Y" != $exhGuestEdit) {
    echo "<p style='color:red;'>Редактирование закрыто администратором!</p>";

    if ("POST" == $_SERVER["REQUEST_METHOD"]) {
        unset($_REQUEST["web_form_submit"]);
        unset($_REQUEST["web_form_apply"]);

        unset($_POST["web_form_submit"]);
        unset($_POST["web_form_apply"]);
    }
}

//сохранение имени, фамилии и фио в поля пользователя
$fieldNameName = "form_text_216"; //имя
$fieldLastNameName = "form_text_217";//фамилия
$fieldEmeil = "form_text_220"; //мыло

if ($userId && "Y" == $exhGuestEdit &&
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    //isset($_REQUEST["RESULT_ID"]) &&
    //$_REQUEST["RESULT_ID"] == $resultId	&&
    isset($_REQUEST[$fieldNameName]) &&
    isset($_REQUEST[$fieldLastNameName]) &&
    isset($_REQUEST[$fieldEmeil])
) {
    $obUser = new CUser;
    $obUser->Update($userId, array(
            "NAME" => $_REQUEST[$fieldNameName],
            "LAST_NAME" => $_REQUEST[$fieldLastNameName],
            "UF_FIO" => $_REQUEST[$fieldNameName] . " " . $_REQUEST[$fieldLastNameName],
            "EMAIL" => $_REQUEST[$fieldEmeil]
        )
    );
}


if (isset($_REQUEST["formresult"]) && $_REQUEST["formresult"] == "editok") {
    //вывод информации об успешном сохранении
    echo "<p style='color:red;'>Внесенные изменения сохранены</p>";
}
$APPLICATION->IncludeComponent(
    "ds:hl.guest.edit",
    "guest_profile",
    [
        "HLBLOCK_REGISTER_GUEST_ID" => 15,
        "HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID" => 18,
        "USER_ID" => $userId,
        "FIELD_TO_SHOW" =>
            [
                "UF_PHOTO",
                "UF_NAME",
                "UF_SURNAME",
                "UF_SALUTATION",
                "UF_COMPANY",
                "UF_ADDRESS",
                "UF_CITY",
                "UF_PHONE",
                "UF_MOBILE",
                "UF_SKYPE",
                "UF_EMAIL",
                "UF_SITE",
                "UF_DESCRIPTION",
                "UF_NORTH_AMERICA",
                "UF_EUROPE",
                "UF_SOUTH_AMERICA",
                "UF_AFRICA",
                "UF_ASIA",
                "UF_OCEANIA",
                "UF_COUNTRY",
                "UF_POSITION",
                "UF_POSTCODE",
            ],
        "DISABLED_FIELD" =>
            [
                "UF_NAME",
                "UF_SURNAME",
                "UF_COMPANY",
                "UF_COUNTRY",
                "UF_CITY",
            ]
    ],
    false
);
?>