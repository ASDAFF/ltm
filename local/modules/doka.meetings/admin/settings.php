<?php

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

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!Loader::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}


$APPLICATION->SetTitle(Loc::getMessage("HLBLOCK_ADMIN_ROWS_LIST_PAGE_TITLE"));

$sTableID = SettingsTable::getTableName();
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

if (($arID = $lAdmin->GroupAction()) && check_bitrix_sessid()) {
    foreach ($arID as $ID) {
        if (strlen($ID) <= 0)
            continue;
        $ID = IntVal($ID);
        switch ($request->getQuery("action")) {
            case "delete":
                @set_time_limit(0);
                $DB->StartTransaction();
                if (!SettingsTable::Delete($ID)->isSuccess()) {
                    $DB->Rollback();
                    $lAdmin->AddGroupError(Loc::getMessage("DELETE_ERROR"), $ID);
                }
                $DB->Commit();
                break;
        }
    }
}


$arHeaders = [
    [
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
    ],
    [
        "id" => "NAME",
        "content" => Loc::getMessage("IBLIST_A_NAME"),
        "sort" => "NAME",
    ],
    [
        "id" => "GUESTS_GROUP",
        "content" => Loc::getMessage("IBLIST_A_GUESTS_GROUP"),
        "sort" => "GUESTS_GROUP",
    ],
    [
        "id" => "MEMBERS_GROUP",
        "content" => Loc::getMessage("IBLIST_A_MEMBERS_GROUP"),
        "sort" => "MEMBERS_GROUP",
    ],
    [
        "id" => "IS_LOCKED",
        "content" => Loc::getMessage("IBLIST_A_IS_LOCKED"),
        "sort" => "IS_LOCKED",
    ],
    [
        "id" => "TIMESLOTS",
        "content" => Loc::getMessage("IBLIST_A_TIMESLOTS"),
    ],
];

foreach ($arHeaders as &$arHeader) {
    $arHeader["default"] = true;
}

$lAdmin->AddHeaders($arHeaders);
if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true)) {
    $by = "ID";
}

$rsData = SettingsTable::getList(
    [
        "order" => [
            $by => $order,
        ],
    ]
);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();


if ($request->getQuery("mode") !== "list") {
    $aMenu = [
        [
            "TEXT" => Loc::getMessage("HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON"),
            "TITLE" => Loc::getMessage("HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON"),
            "LINK" => ADMIN_MODULE_NAME . "_settings_edit.php?lang=" . LANGUAGE_ID,
            "ICON" => "btn_new",
        ],
    ];
}

$context = new CAdminContextMenu($aMenu);

$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("PAGES")));
while ($arRes = $rsData->NavNext(true, "f_")) {
    $edit_url = ADMIN_MODULE_NAME . "_settings_edit.php?ID=" . $f_ID;
    $row = $lAdmin->AddRow($f_ID, $arRes);

    $row->AddViewField("ID", "<a href=\"" . $edit_url . "\" title=\"" . Loc::getMessage("IBLIST_A_EDIT") . "\">" . $f_ID . "</a>");
    $row->AddViewField("NAME", $f_NAME);
    $row->AddViewField("GUESTS_GROUP", $f_GUESTS_GROUP . " [" . DokaGetGroupName($f_GUESTS_GROUP) . "]");
    $row->AddViewField("MEMBERS_GROUP", $f_MEMBERS_GROUP . " [" . DokaGetGroupName($f_MEMBERS_GROUP) . "]");
    $row->AddViewField("IS_LOCKED", $f_IS_LOCKED == 1 ? Loc::getMessage("DOKA_MEET_IS_LOCKED") : Loc::getMessage("DOKA_MEET_IS_NOT_LOCKED"));
    $row->AddViewField("TIMESLOTS", "<a href=\"" . ADMIN_MODULE_NAME . "_settings_timeslots.php?set_filter=Y&find_EXHIBITION_ID=" . $f_ID . "&lang=" . LANGUAGE_ID .
        "\" title=\"" . Loc::getMessage("IBLIST_A_TIMESLOTS_SHOW") . "\">" . Loc::getMessage("IBLIST_A_TIMESLOTS_SHOW") . "</a>");

    $can_edit = true;

    $arActions = [];

    $arActions[] = [
        "ICON" => "edit",
        "TEXT" => Loc::getMessage($can_edit ? "MAIN_ADMIN_MENU_EDIT" : "MAIN_ADMIN_MENU_VIEW"),
        "ACTION" => $lAdmin->ActionRedirect(ADMIN_MODULE_NAME . "_settings_edit.php?ID=" . $f_ID),
        "DEFAULT" => true,
    ];

    $arActions[] = [
        "ICON" => "delete",
        "TEXT" => Loc::getMessage("MAIN_ADMIN_MENU_DELETE"),
        "ACTION" => "if(confirm(\"" . Loc::getMessage("HLBLOCK_ADMIN_DELETE_ROW_CONFIRM") . "\")) " .
            $lAdmin->ActionRedirect(ADMIN_MODULE_NAME . "_settings.php?action=delete&ID=" . $f_ID . "&" . bitrix_sessid_get()),
    ];

    $row->AddActions($arActions);

    $row->pList->bCanBeEdited = false;
}

if ($request->getQuery("mode") == "list") {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

    $context->Show();
}

$lAdmin->CheckListMode();

$lAdmin->DisplayList();

function DokaGetGroupName($group_id)
{
    $ID = IntVal($group_id);
    static $cache = [];

    if (!array_key_exists($ID, $cache)) {
        $rsGroup = CGroup::GetByID($ID);
        $arGroup = $rsGroup->Fetch();
        $cache[$ID] = $arGroup["NAME"];
    }

    return $cache[$ID];
}

if ($request->getQuery("mode") == "list") {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}