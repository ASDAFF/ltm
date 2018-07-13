<?

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\TimeslotTable;

@set_time_limit(7200);
@ini_set("max_execution_time", 7200);
define("ADMIN_MODULE_NAME", "doka.meetings");

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadMessages(__FILE__);
global $USER, $APPLICATION;
$request = Context::getCurrent()->getRequest();
if (!Loader::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$RIGHT = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);
if ($RIGHT == "D") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if ($ID != 0) {
    $APPLICATION->SetTitle(Loc::getMessage("DOKA_MEET_TITLE_UPDATE", ["#NAME#" => $str_NAME, "#EXHIBITION_ID#" => $str_EXHIBITION_ID]));
} else {
    $APPLICATION->SetTitle(Loc::getMessage("DOKA_MEET_TITLE_ADD"));
}

if ($ex = $APPLICATION->GetException()) {
    require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php";
    $strError = $ex->GetString();
    ShowError($strError);
    require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php";
    die();
}

$arSelects = [];
$arSelects["EXHIBITION_ID"][] = [
    "ID" => "",
    "NAME" => Loc::getMessage("DOME_MEET_CHOOSE_EXHIBITION"),
];
$exhibitions = SettingsTable::getList(
    [
        "select" => [
            "ID", "NAME",
        ],
        "filter" => [
            "ACTIVE" => true,
        ],
    ]
);
while ($exhibition = $exhibitions->Fetch()) {
    $ar = [];
    $ar["ID"] = (int)$exhibition["ID"];
    $ar["NAME"] = htmlspecialcharsbx($exhibition["NAME"]);
    $arSelects["EXHIBITION_ID"][] = $ar;
}

$arTimeslotTypes = TimeslotTable::getTypes();
foreach ($arTimeslotTypes as $id => $code) {
    $arSelects["SLOT_TYPE"][] = [
        "ID" => $id,
        "NAME" => Loc::getMessage("DOKA_MEET_" . strtoupper($code)),
    ];
}

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("DOKA_MEET_TAB"),
        "ICON" => "service_request",
        "TITLE" => Loc::getMessage("DOKA_MEET_TAB_DESCR"),
    ],
];
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($RIGHT >= "W" && $request->isPost() && !empty($Update) && check_bitrix_sessid()) {

    $DB->StartTransaction();

    $arFields = [
        "NAME" => $NAME,
        "SORT" => $SORT,
        "SLOT_TYPE" => $SLOT_TYPE,
        "EXHIBITION_ID" => $EXHIBITION_ID,
    ];

    if (!empty($ID)) {
        $result = TimeslotTable::update($ID, $arFields);
    } else {
        $result = TimeslotTable::add($arFields);
    }

    if (!$result->isSuccess()) {
        $DB->Rollback();
    } else {
        $DB->Commit();
        if (!empty($apply)) {
            $_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"] = ["MESSAGE" => Loc::getMessage("DOKA_MEET_SAVE_OK"), "TYPE" => "OK"];
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_timeslots_edit.php?ID=" . $ID . "&lang=" . LANG . "&" . $tabControl->ActiveTabParam());
        } else {
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_timeslots.php?lang=" . LANGUAGE_ID);
        }
    }
}

if ($ID > 0) {
    $timeSlot = TimeslotTable::getRowById($ID);
    if (!$timeSlot) {
        $ID = 0;
    }
}

$aMenu = [
    [
        "TEXT" => Loc::getMessage("DOKA_MEET_LIST"),
        "ICON" => "btn_list",
        "LINK" => "/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_timeslots.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false),
    ],
];
if ($ID > 0) {
    $aMenu[] = [
        "TEXT" => Loc::getMessage("DOKA_MEET_DELETE"),
        "ICON" => "btn_delete",
        "LINK" => "javascript:if(confirm(\"" . Loc::getMessage("DOKA_MEET_DELETE_CONF") . "\")) window.location=\"/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_timeslots.php?action=delete&ID=" . $ID . "&lang=" . LANGUAGE_ID . "&" . bitrix_sessid_get() . "#tb\";",
        "WARNING" => "Y",
    ];
}

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php";

$context = new CAdminContextMenu($aMenu);
$context->Show();

$fields = TimeslotTable::getEntity()->getFields();
$arFields = [];
foreach ($fields as $field) {
    if ($field->getName() === "ID") continue;
    $res = [];
    $res = [
        "NAME" => Loc::getMessage("DOKA_MEET_" . $field->getName()),
    ];

    if ($field->getName() == "EXHIBITION_ID" || $field->getName() == "SLOT_TYPE") {
        $res["TYPE"] = "select";
    } else if ($field->getName() == "TIME_FROM" || $field->getName() == "TIME_TO") {
        $res["TYPE"] = "info";
        if ($ID <= 0)
            $res["TYPE"] = "hide";
    } else {
        $res["TYPE"] = "text";
        $res["SIZE"] = 30;
    }

    $arFields[$field->getName()] = $res;
}
?>

<form method="POST" action="<?= $APPLICATION->GetCurPage() ?>?" id="doka_form">
    <?
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>
    <input type="hidden" name="Update" value="Y">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="ID" value="<?= $ID ?>">
    <?= bitrix_sessid_post(); ?>

    <? if ($ID > 0): ?>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">ID:</td>
            <td><?= $ID ?></td>
        </tr>
    <? endif; ?>

    <?
    foreach ($arFields as $code => $arField) {
        if ($arField["TYPE"] === "hide") {
            continue;
        }
        $val = $timeSlot[$code];
        ?>
        <tr>
            <td width="40%" nowrap <?= ($arField["TYPE"] === "textarea") ? "class=\"adm-detail-valign-top\"" : "" ?>>
                <label for="<?= htmlspecialcharsbx($code) ?>"><?= $arField["NAME"] ?>:</label>
            <td width="60%">
                <?
                switch ($arField["TYPE"]) {
                    case "checkbox":
                        ?>
                        <input type="checkbox" id="<?= htmlspecialcharsbx($code) ?>"
                               name="<?= htmlspecialcharsbx($code) ?>"
                               value="Y"<?= ($val == "1") ? " checked" : "" ?>>
                        <?
                        break;
                    case "text":
                        ?>
                        <input type="text" size="<?= $arField["SIZE"] ?: 30 ?>" maxlength="255"
                               value="<?= htmlspecialcharsbx($val) ?>"
                               name="<?= htmlspecialcharsbx($code) ?>">
                        <?
                        break;
                    case "info":
                        ?>
                        <?= htmlspecialcharsbx($val) ?>
                        <?
                        break;
                    case "select":
                        ?>
                        <select name="<?= htmlspecialcharsbx($code) ?>">
                            <?
                            foreach ($arSelects[$code] as $item):
                                ?>
                                <option <? if ($item["ID"] == $val): ?>selected<? endif;
                                ?>
                                        value="<?= $item["ID"] ?>"><?= $item["NAME"] . ($item["ID"] > 0 ? " [" . $item["ID"] . "]" : "") ?></option>
                            <? endforeach ?>
                        </select>
                        <?
                        break;
                } ?>
            </td>
        </tr>
        <?
    }

    $tabControl->Buttons([
        "disabled" => $RIGHT < "W",
        "back_url" => "/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_timeslots.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false),
    ]);
    $tabControl->End();
    ?>
</form>
<?= BeginNote() ?>
<span class="required">*</span> <?= Loc::getMessage("REQUIRED_FIELDS") ?>
<?= EndNote() ?>


<? require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php" ?>
