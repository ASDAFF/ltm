<?

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\TimeslotTable;

@set_time_limit(7200);
@ini_set("max_execution_time", 7200);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/tools/prop_userid.php");

Loc::loadMessages(__FILE__);

global $USER, $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage("HLBLOCK_ADMIN_ROWS_LIST_PAGE_TITLE"));

$request = Context::getCurrent()->getRequest();

define("ADMIN_MODULE_NAME", "doka.meetings");

if (!Loader::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$RIGHT = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);
if ($RIGHT == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

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
$exhibition_list = SettingsTable::getList([
    'select' => [
        'ID', 'NAME',
    ],
    'filter' => [
        'ACTIVE' => true,
    ],
]);
while ($zr = $exhibition_list->Fetch()) {
    $ar = [];
    $ar["ID"] = (int)$zr["ID"];
    $ar["NAME"] = htmlspecialcharsbx($zr["NAME"]);
    $arSelects["EXHIBITION_ID"][] = $ar;
}

$arStatuses = RequestTable::getStatuses();
foreach ($arStatuses as $id => $code) {
    $arSelects["STATUS"][] = [
        "ID" => $code,
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

$ID = (int)$ID;


if ($RIGHT >= "W" && $request->isPost() && check_bitrix_sessid()) {
    $DB->StartTransaction();
    $arFields = [
        "RECEIVER_ID" => $RECEIVER_ID,
        "SENDER_ID" => $SENDER_ID,
        "EXHIBITION_ID" => $EXHIBITION_ID,
        "TIMESLOT_ID" => $TIMESLOT_ID,
        "STATUS" => $STATUS,
    ];

    if (!empty($ID)) {
        $result = RequestTable::update($ID, $arFields);
    } else {
        $result = RequestTable::add($arFields);
    }

    if (!$result->isSuccess()) {
        $DB->Rollback();
    } else {
        $DB->Commit();
        if (!empty($apply)) {
            $_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"] = ["MESSAGE" => Loc::getMessage("DOKA_MEET_SAVE_OK"), "TYPE" => "OK"];
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_requests_edit.php?ID=" . $ID . "&lang=" . LANG . "&" . $tabControl->ActiveTabParam());
        } else {
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_requests.php?lang=" . LANGUAGE_ID);
        }
    }
}

if ($ID > 0) {
    $requestModel = RequestTable::getRowById($ID);
    if (!$requestModel) {
        $ID = 0;
    }
}

if ($ID > 0) {
    $APPLICATION->SetTitle(Loc::getMessage("DOKA_MEET_TITLE_UPDATE", ["#EXHIBITION_ID#" => $str_EXHIBITION_ID]));
} else {
    $APPLICATION->SetTitle(Loc::getMessage("DOKA_MEET_TITLE_ADD"));
}

$aMenu = [
    [
        "TEXT" => Loc::getMessage("DOKA_MEET_LIST"),
        "ICON" => "btn_list",
        "LINK" => "/bitrix/admin/" . ADMIN_MODULE_NAME . "_requests.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false),
    ],
];
if ($ID > 0) {
    $aMenu[] = [
        "TEXT" => Loc::getMessage("DOKA_MEET_DELETE"),
        "ICON" => "btn_delete",
        "LINK" => "javascript:if(confirm(\"" . Loc::getMessage("DOKA_MEET_DELETE_CONF") . "\")) window.location=\"/bitrix/admin/" . ADMIN_MODULE_NAME . "_requests.php?action=delete&ID=" . $ID . "&lang=" . LANGUAGE_ID . "&" . bitrix_sessid_get() . "#tb\";",
        "WARNING" => "Y",
    ];
}

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php";

$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?
$fields = RequestTable::getEntity()->getFields();
$arFields = [];
foreach ($fields as $field) {
    $fieldCode = $field->getName();
    if ($fieldCode === "ID") continue;
    $res = [];
    $res = [
        "NAME" => Loc::getMessage("DOKA_MEET_" . $fieldCode),
    ];
    if ($fieldCode === 'STATUS') {
        $res["TYPE"] = 'select';
    } else if ($fieldCode === 'TIMESLOT_ID') {
        $res["TYPE"] = 'timeslot';
    } else if ($fieldCode === "CREATED_AT" || $fieldCode === "UPDATED_AT") {
        $res["TYPE"] = 'info';
        if ($ID == 0) {
            $res["TYPE"] = 'hide';
        }
    } else if ($fieldCode === 'EXHIBITION_ID') {
        $res["TYPE"] = 'hide';
    } else if ($fieldCode === 'MODIFIED_BY') {
        $res["TYPE"] = "userNAME";
    } else if ($fieldCode === "SENDER_ID" || $fieldCode === "RECEIVER_ID") {
        $res["TYPE"] = "userID";
    } else {
        $val = $setting[$code];
        $res["TYPE"] = "text";
        $res["SIZE"] = 30;
    }
    $arFields[$fieldCode] = $res;
}
?>

<form method="POST" action="<?= $APPLICATION->GetCurPage() ?>?" id="doka_form" name="doka_form">
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
        if ($arField["TYPE"] === "hide") continue;
        $type = $arField[3];
        $val = $requestModel[$code];
        ?>
        <tr>
            <td width="40%" nowrap <? if ($arField["TYPE"] == "textarea") echo "class=\"adm-detail-valign-top\"" ?>>
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
                    case "textarea":
                        ?>
                        <textarea rows="<?= $arField["SIZE"] ?: 30 ?>" cols="<?= $arField["SIZE"] ?: 30 ?>"
                                  name="<?= htmlspecialcharsbx($code) ?>"><?= htmlspecialcharsbx($val) ?></textarea>
                        <? break;
                    case "select":
                        ?>
                        <select name="<?= htmlspecialcharsbx($code) ?>">
                            <?
                            foreach ($arSelects[$code] as $item) {
                                ?>
                                <option <? if ($item["ID"] == $val): ?>selected<? endif;
                                ?>
                                        value="<?= $item["ID"] ?>"><?= $item["NAME"] . ($item["ID"] > 0 ? " [" . $item["ID"] . "]" : "") ?></option>
                            <? } ?>
                        </select>
                        <?
                        break;
                    case "timeslot":
                        $exhibId = $requestModel['EXHIBITION_ID'];
                        echo TimeslotTable::getHtmlInput($exhibId, $val);
                        break;
                    case "userNAME":
                        ?>
                        <?= htmlspecialcharsbx($val) ?> [<?= DokaGetUserName($val); ?>]
                        <?
                        break;
                    case "userID":
                        $html = CIBlockPropertyUserID::GetPropertyFieldHtml(
                            [],
                            [
                                "VALUE" => $val,
                                "DESCRIPTION" => "",
                            ],
                            [
                                "VALUE" => $code,
                                "DESCRIPTION" => "",
                                "FORM_NAME" => "doka_form",
                                "MODE" => "FORM_FILL",
                                "COPY" => false,
                            ]
                        );
                        echo $html;
                        break;
                } ?>
            </td>
        </tr>
    <? } ?>

    <?
    $tabControl->Buttons([
        "disabled" => $RIGHT < "W",
        "back_url" => "/bitrix/admin/" . ADMIN_MODULE_NAME . "_requests_edit.php?lang=" . LANGUAGE_ID . GetFilterParams("filter_", false),
    ]);
    $tabControl->End();
    ?>
</form>
<?= BeginNote() ?>
<span class="required">*</span> <?= Loc::getMessage("REQUIRED_FIELDS") ?>
<?= EndNote() ?>

<?
function DokaGetUserName($ID)
{
    $ID = IntVal($ID);
    static $user_cache = [];
    if (!array_key_exists($ID, $user_cache)) {
        $rsUser = CUser::GetByID($ID);
        $arUser = $rsUser->Fetch();
        $user_cache[$ID] = $arUser["LOGIN"];
    }
    return $user_cache[$ID];
}

?>

<? require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php" ?>
