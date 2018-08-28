<?

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;

@set_time_limit(7200);
@ini_set("max_execution_time", 7200);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadMessages(__FILE__);

global $USER, $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage("HLBLOCK_ADMIN_ROWS_LIST_PAGE_TITLE"));

$request = Context::getCurrent()->getRequest();

define("ADMIN_MODULE_NAME", "doka.meetings");

if (!Loader::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$RIGHT = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);
if ($RIGHT == 'D') {
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}
$ID = (int)$ID;
if ($ID > 0) {
    $APPLICATION->SetTitle(str_replace('#NAME#', $str_NAME, Loc::getMessage('DOKA_MEET_TITLE_UPDATE')));
} else {
    $APPLICATION->SetTitle(Loc::getMessage('DOKA_MEET_TITLE_ADD'));
}

if ($ex = $APPLICATION->GetException()) {
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

    $strError = $ex->GetString();
    ShowError($strError);

    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
    die();
}

$arGROUPS = [];
$dbGroups = CGroup::GetList($by = "ID", $order = "asc", [
    "ACTIVE" => "Y",
    "ANONYMOUS" => "N",
]);
while ($group = $dbGroups->Fetch()) {
    $ar = [];
    $ar["ID"] = (int)$group['ID'];
    $ar["NAME"] = htmlspecialcharsbx($group["NAME"]);
    $arGROUPS[] = $ar;
}

// Get additional data
$aTabs = [
    [
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('DOKA_MEET_TAB'),
        'ICON' => 'service_request',
        'TITLE' => Loc::getMessage('DOKA_MEET_TAB_DESCR'),
    ],
];
$tabControl = new CAdminTabControl('tabControl', $aTabs);

if ($RIGHT >= 'W' && $request->isPost() && check_bitrix_sessid()) {

    $DB->StartTransaction();
    $arFields = [
        'NAME' => $NAME,
        'CODE' => $CODE,
        'IS_LOCKED' => $IS_LOCKED == 'Y' ? true : false,
        'ACTIVE' => $ACTIVE == 'Y' ? true : false,
        'GUESTS_GROUP' => (int)$GUESTS_GROUP,
        'IS_GUEST' => $IS_GUEST == 'Y' ? true : false,
        'IS_HB' => $IS_HB == 'Y' ? true : false,
        'MEMBERS_GROUP' => (int)$MEMBERS_GROUP,
        'ADMINS_GROUP' => (int)$ADMINS_GROUP,
        'EVENT_SENT' => $EVENT_SENT,
        'EVENT_REJECT' => $EVENT_REJECT,
        'EVENT_TIMEOUT' => $EVENT_TIMEOUT,
        'TIMEOUT_VALUE' => (int)$TIMEOUT_VALUE,
        'REPR_PROP_ID' => (int)$REPR_PROP_ID,
        'REPR_PROP_CODE' => $REPR_PROP_CODE,
        'FORM_ID' => (int)$FORM_ID,
        'FORM_RES_CODE' => $FORM_RES_CODE,
    ];

    if (!empty($ID)) {
        $result = SettingsTable::update($ID, $arFields);
    } else {
        $result = SettingsTable::add($arFields);
    }
    if (!$result->isSuccess()) {
        $DB->Rollback();
    } else {
        $DB->Commit();
        if (!empty($apply)) {
            $_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"] = ["MESSAGE" => Loc::getMessage("DOKA_MEET_SAVE_OK"), "TYPE" => "OK"];
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_edit.php?ID=" . $ID . "&lang=" . LANG . "&" . $tabControl->ActiveTabParam());
        } else {
            LocalRedirect('/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?lang=' . LANGUAGE_ID);
        }
    }
}

if ($ID != 0) {
    $setting = SettingsTable::getRowById($ID);
    if (!$setting) {
        $ID = 0;
    }
}

$aMenu = [
    [
        'TEXT' => Loc::getMessage('DOKA_MEET_LIST'),
        'ICON' => 'btn_list',
        'LINK' => '/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?lang=' . LANGUAGE_ID . GetFilterParams('filter_', false),
    ],
];
if ($ID > 0) {
    $aMenu[] = [
        'TEXT' => Loc::getMessage('DOKA_MEET_DELETE'),
        'ICON' => 'btn_delete',
        'LINK' => 'javascript:if(confirm(\'' . Loc::getMessage('DOKA_MEET_DELETE_CONF') . '\')) window.location=\'/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?action=delete&ID=' . $ID . '&lang=' . LANGUAGE_ID . '&' . bitrix_sessid_get() . '#tb\';',
        'WARNING' => 'Y',
    ];
}

// SHOW FORM
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

// Add admin context menu
$context = new CAdminContextMenu($aMenu);
$context->Show();

$fields = SettingsTable::getEntity()->getFields();
$arFields = [];
foreach ($fields as $field) {
    $fieldCode = $field->getName();
    if ($fieldCode === "ID") continue;
    $res = [];
    $res = [
        "NAME" => Loc::getMessage("DOKA_MEET_" . $fieldCode),
    ];
    if ($fieldCode === 'IS_LOCKED' || $fieldCode === 'ACTIVE' || $fieldCode === 'IS_GUEST' || $fieldCode === 'IS_HB') {
        $res["TYPE"] = 'checkbox';
    } else if ($fieldCode === 'MEMBERS_GROUP' || $fieldCode === 'GUESTS_GROUP' || $fieldCode === 'ADMINS_GROUP') {
        $res["TYPE"] = 'group';
    } else {
        $res["TYPE"] = "text";
        $res["SIZE"] = 30;
    }
    $arFields[$fieldCode] = $res;
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
        $val = $setting[$code];
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
                               value="Y"<?= ($val) ? " checked" : "" ?>>
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
                    case "group":
                        ?>
                        <select name="<?= htmlspecialcharsbx($code) ?>">
                            <?
                            foreach ($arGROUPS as $group) {
                                ?>
                                <option <? if ($group["ID"] == $val): ?>selected<? endif;
                                ?>
                                        value="<?= $group["ID"] ?>"><?= $group["NAME"] . ($group["ID"] > 0 ? " [" . $group["ID"] . "]" : "") ?></option>
                            <? } ?>
                        </select>
                        <?
                        break;
                } ?>
            </td>
        </tr>
        <?
    }


    $tabControl->Buttons([
        'disabled' => $RIGHT < 'W',
        'back_url' => '/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?lang=' . LANGUAGE_ID . GetFilterParams('filter_', false),
    ]);
    $tabControl->End();
    ?>
</form>
<?= BeginNote() ?>
<span class="required">*</span> <?= Loc::getMessage('REQUIRED_FIELDS') ?>
<?= EndNote() ?>

<? require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php' ?>
