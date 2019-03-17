<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\TimeslotTable;

@set_time_limit(7200);
@ini_set('max_execution_time', 7200);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadMessages(__FILE__);

global $USER, $APPLICATION;
$APPLICATION->SetTitle(Loc::getMessage('HLBLOCK_ADMIN_ROWS_LIST_PAGE_TITLE'));

$request = Context::getCurrent()->getRequest();

define("ADMIN_MODULE_NAME", "doka.meetings");
define("DOKA_EDIT_PAGE", "_settings_timeslots_edit.php");

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!Loader::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$sTableID = TimeslotTable::getTableName();
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = [
    "find_EXHIBITION_ID",
];

$lAdmin->InitFilter($arFilterFields);

$arFilter = [];
if ($find_EXHIBITION_ID) {
    $arFilter = [
        "EXHIBITION_ID" => (int)$find_EXHIBITION_ID,
    ];
}

if (($arID = $lAdmin->GroupAction()) && check_bitrix_sessid()) {
    foreach ($arID as $ID) {
        if (strlen($ID) <= 0)
            continue;
        $ID = IntVal($ID);
        switch ($request->getQuery('action')) {
            case "delete":
                @set_time_limit(0);
                $DB->StartTransaction();
                if (!TimeslotTable::Delete($ID)->isSuccess()) {
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
        'id' => 'ID',
        'content' => 'ID',
        'sort' => 'ID',
    ],
    [
        "id" => "SORT",
        "content" => Loc::getMessage("IBLIST_A_SORT"),
        "sort" => "SORT",
    ],
    [
        "id" => "NAME",
        "content" => Loc::getMessage("IBLIST_A_NAME"),
        "sort" => "NAME",
    ],
    [
        "id" => "EXHIBITION_ID",
        "content" => Loc::getMessage("IBLIST_A_EXHIBITION_ID"),
        "sort" => "EXHIBITION_ID",
    ],
    [
        "id" => "SLOT_TYPE",
        "content" => Loc::getMessage("IBLIST_A_TYPE"),
        "sort" => "SLOT_TYPE",
    ],
];

foreach ($arHeaders as &$arHeader) {
    $arHeader['default'] = true;
}

$lAdmin->AddHeaders($arHeaders);
if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true)) {
    $by = 'ID';
}

$rsData = TimeslotTable::getList([
    'order' => [
        $by => $order,
    ],
    'filter' => $arFilter,
]);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

if ($request->getQuery('mode') !== "list") {
    // menu
    $aMenu = [
        [
            "TEXT" => Loc::getMessage('HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON'),
            "TITLE" => Loc::getMessage('HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON'),
            "LINK" => ADMIN_MODULE_NAME . DOKA_EDIT_PAGE . "?lang=" . LANGUAGE_ID,
            "ICON" => "btn_new",
        ],
    ];
}

$context = new CAdminContextMenu($aMenu);

$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("PAGES")));
while ($arRes = $rsData->NavNext(true, "f_")) {
    $edit_url = ADMIN_MODULE_NAME . DOKA_EDIT_PAGE . '?ID=' . $f_ID;
    $row = $lAdmin->AddRow($f_ID, $arRes);
    $row->AddViewField("ID", '<a href="' . $edit_url . '" title="' . Loc::getMessage("IBLIST_A_EDIT") . '">' . $f_ID . '</a>');
    $row->AddViewField("SORT", $f_SORT);
    $row->AddViewField("NAME", $f_NAME);
    $row->AddViewField("EXHIBITION_ID", $f_EXHIBITION_ID . ' [' . GetExhibitionName($f_EXHIBITION_ID) . ']');
    $can_edit = true;
    $arActions = [];
    $arActions[] = [
        "ICON" => "edit",
        "TEXT" => Loc::getMessage($can_edit ? "MAIN_ADMIN_MENU_EDIT" : "MAIN_ADMIN_MENU_VIEW"),
        "ACTION" => $lAdmin->ActionRedirect(ADMIN_MODULE_NAME . DOKA_EDIT_PAGE . "?ID=" . $f_ID),
        "DEFAULT" => true,
    ];
    $arActions[] = [
        "ICON" => "delete",
        "TEXT" => Loc::getMessage("MAIN_ADMIN_MENU_DELETE"),
        "ACTION" => "if(confirm('" . Loc::getMessage('HLBLOCK_ADMIN_DELETE_ROW_CONFIRM') . "')) " .
            $lAdmin->ActionRedirect(ADMIN_MODULE_NAME . "_settings_timeslots.php?action=delete&ID=" . $f_ID . '&' . bitrix_sessid_get()),
    ];
    $row->AddActions($arActions);
    $row->pList->bCanBeEdited = false;
}

if ($request->getQuery('mode') == "list") {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

    $context->Show();
}
$lAdmin->CheckListMode();
?>
    <form method="GET" name="find_form" id="find_form" action="<? echo $APPLICATION->GetCurPage() ?>">
        <?
        $arFindFields['EXHIBITION_ID'] = Loc::getMessage('DOKA_FILTER_EXHIBITION_ID');
        $filterUrl = $APPLICATION->GetCurPageParam();
        $oFilter = new CAdminFilter($sTableID . "_filter", $arFindFields, ["table_id" => $sTableID, "url" => $filterUrl]);
        ?>
        <script type="text/javascript">
            var arClearHiddenFields = new Array()

            function applyFilter(el) {
                BX.adminPanel.showWait(el)
                <?=$sTableID . "_filter";?>.OnSet('<?=CUtil::JSEscape($sTableID)?>','<?=CUtil::JSEscape($filterUrl)?>')
                return false
            }

            function deleteFilter(el) {
                BX.adminPanel.showWait(el)
                if (0 < arClearHiddenFields.length) {
                    for (var index = 0; index < arClearHiddenFields.length; index++) {
                        if (undefined != window[arClearHiddenFields[index]]) {
                            if ("ClearForm" in window[arClearHiddenFields[index]]) {
                                window[arClearHiddenFields[index]].ClearForm()
                            }
                        }
                    }
                }
                <?=$sTableID . "_filter"?>.
                OnClear('<?=CUtil::JSEscape($sTableID)?>','<?=CUtil::JSEscape($APPLICATION->GetCurPage() . '?type=' . urlencode($type) . '&IBLOCK_ID=' . urlencode($IBLOCK_ID) . '&lang=' . urlencode(LANG) . '&')?>')
                return false
            }
        </script>
        <? $oFilter->Begin(); ?>
        <tr>
            <td><? echo Loc::getMessage("DOKA_FILTER_EXHIBITION_ID") ?>:</td>
            <td>
                <?
                $values = ['REFERENCE' => [Loc::getMessage('DOKA_FILTER_EXHIBITION_ID_ALL')], 'REFERENCE_ID' => ['']];
                $exhibition_list = SettingsTable::getList([
                    'select' => ['ID', 'NAME'],
                ]);
                while ($exhibition = $exhibition_list->Fetch()) {
                    array_push($values['REFERENCE'], $exhibition['NAME']);
                    array_push($values['REFERENCE_ID'], $exhibition['ID']);
                }
                echo SelectBoxFromArray("find_EXHIBITION_ID", $values, $find_EXHIBITION_ID, "", ""); ?>
            </td>
        </tr>
        <? $oFilter->Buttons(); ?>
        <input class="adm-btn" type="submit" name="set_filter"
               value="<? echo Loc::getMessage("admin_lib_filter_set_butt"); ?>"
               title="<? echo Loc::getMessage("admin_lib_filter_set_butt_title"); ?>"
               onClick="return applyFilter(this);">
        <input class="adm-btn" type="submit" name="del_filter"
               value="<? echo Loc::getMessage("admin_lib_filter_clear_butt"); ?>"
               title="<? echo Loc::getMessage("admin_lib_filter_clear_butt_title"); ?>"
               onClick="deleteFilter(this); return false;">
        <? $oFilter->End(); ?>
    </form>
<?


$lAdmin->DisplayList();

function GetExhibitionName($exhibition_id)
{
    $ID = (int)$exhibition_id;
    static $cache = [];
    if (!array_key_exists($ID, $cache)) {
        $arItem = SettingsTable::getRowById($exhibition_id);
        $cache[$ID] = $arItem['NAME'];
    }

    return $cache[$ID];
}

if ($request->getQuery('mode') == "list") {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}