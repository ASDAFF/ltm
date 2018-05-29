<?
global $USER;
if ($USER->IsAdmin() && isset($_REQUEST["UID"])) {
    $userId = intval($_REQUEST["UID"]);
} else {
    $userId = $USER->GetID();
}

$rsUser = CUser::GetList(($by = false), ($order = false), array("ID" => $userId), array("SELECT" => array("UF_*")));
$arUser = $rsUser->Fetch();
$resultId = $arUser["UF_ID_COMP"];
$curPage = "/cabinet/" . $_REQUEST["EXHIBIT_CODE"] . "/edit/colleague/" . (isset($_REQUEST["UID"]) ? "?UID={$_REQUEST["UID"]}" : "");

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


if (isset($_REQUEST["formresult"]) && $_REQUEST["formresult"] == "editok") {
    //вывод информации об успешном сохранении
    echo "<p style='color:red;'>Внесенные изменения сохранены</p>";
}

$APPLICATION->IncludeComponent(
    'ds:hl.guest-colleague',
    '',
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
                "UF_EMAIL",
                "UF_JOB_TITLE",
                "UF_DAYTIME"
            ],
        "HIDDEN_FIELDS" =>
            [
                "UF_DAYTIME"
            ]
    ]
);
?>

<div class="exhibition-session">
    <div class="signature">
        <b>Если кто-то из ваших коллег хочет отдельные от вас встречи, то ему необходимо пройти процесс
            регистрации.</b><br>
        При загрузке фотографий учитывайте, что файлы должны быть не более 2мб и представлять лицо участника крупным
        планом или логотип компании.
    </div>
</div>