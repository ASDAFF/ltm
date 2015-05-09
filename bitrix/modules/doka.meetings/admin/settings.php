<?php

// admin initialization
define("ADMIN_MODULE_NAME", "doka.meetings");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

use Doka\Meetings\Settings as DMS;

$APPLICATION->SetTitle(GetMessage('HLBLOCK_ADMIN_ROWS_LIST_PAGE_TITLE'));

$sTableID = DMS::getTableName();
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);
$arFilter = array();

// РЈРґР°Р»РµРЅРёРµ
if(($arID = $lAdmin->GroupAction()) && check_bitrix_sessid()) {
    // РїСЂРѕР№РґРµРј РїРѕ СЃРїРёСЃРєСѓ СЌР»РµРјРµРЅС‚РѕРІ
    foreach($arID as $ID) {
        if(strlen($ID)<=0)
            continue;
        $ID = IntVal($ID);
        
        switch($_REQUEST['action']) {
	        case "delete":
	            @set_time_limit(0);
	            $DB->StartTransaction();
	            if(!DMS::Delete($ID))
	            {
	                $DB->Rollback();
	                $lAdmin->AddGroupError(GetMessage("DELETE_ERROR"), $ID);
	            }
	            $DB->Commit();
	            break;
        }
    }
}


$arHeaders = array(
	array(
		'id' => 'ID',
		'content' => 'ID',
		'sort' => 'ID',
	),
    array(
        "id" => "NAME",
        "content" => GetMessage("IBLIST_A_NAME"),
        "sort" => "NAME",
    ),
    array(
        "id" => "GUESTS_GROUP",
        "content" => GetMessage("IBLIST_A_GUESTS_GROUP"),
        "sort" => "GUESTS_GROUP",
    ),
    array(
        "id" => "MEMBERS_GROUP",
        "content" => GetMessage("IBLIST_A_MEMBERS_GROUP"),
        "sort" => "MEMBERS_GROUP",
    ),
    array(
        "id" => "IS_LOCKED",
        "content" => GetMessage("IBLIST_A_IS_LOCKED"),
        "sort" => "IS_LOCKED",
    ),
    array(
        "id" => "TIMESLOTS",
        "content" => GetMessage("IBLIST_A_TIMESLOTS"),
    ),
);



// show all by default
foreach ($arHeaders as &$arHeader)
{
	$arHeader['default'] = true;
}
unset($arHeader);

$lAdmin->AddHeaders($arHeaders);
if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true)) {
	$by = 'ID';
}

// select data
$rsData = DMS::GetList(array($by => $order), $arFilter);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();


if ($_REQUEST["mode"] !== "list") {
	// menu
	$aMenu = array(
		array(
			"TEXT"	=> GetMessage('HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON'),
			"TITLE"	=> GetMessage('HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON'),
			"LINK"	=> ADMIN_MODULE_NAME . "_settings_edit.php?lang=".LANGUAGE_ID,
			"ICON"	=> "btn_new",
		),
	);
}

$context = new CAdminContextMenu($aMenu);

// build list
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("PAGES")));
while($arRes = $rsData->NavNext(true, "f_"))
{
	$edit_url = ADMIN_MODULE_NAME . '_settings_edit.php?ID=' . $f_ID;
	$row = $lAdmin->AddRow($f_ID, $arRes);

	$row->AddViewField("ID", '<a href="' . $edit_url . '" title="' . GetMessage("IBLIST_A_EDIT") . '">' . $f_ID . '</a>');
	$row->AddViewField("NAME", $f_NAME);
	$row->AddViewField("GUESTS_GROUP", $f_GUESTS_GROUP . ' [' . DokaGetGroupName($f_GUESTS_GROUP) .']');
	$row->AddViewField("MEMBERS_GROUP", $f_MEMBERS_GROUP . ' [' . DokaGetGroupName($f_MEMBERS_GROUP) .']');
	$row->AddViewField("IS_LOCKED", $f_IS_LOCKED == 1 ? GetMessage('DOKA_MEET_IS_LOCKED') : GetMessage('DOKA_MEET_IS_NOT_LOCKED'));
	$row->AddViewField("TIMESLOTS", '<a href="' . ADMIN_MODULE_NAME . '_settings_timeslots.php?set_filter=Y&find_EXHIBITION_ID=' . $f_ID . '&lang=' . LANGUAGE_ID .
		'" title="' . GetMessage("IBLIST_A_TIMESLOTS_SHOW") . '">' . GetMessage("IBLIST_A_TIMESLOTS_SHOW") . '</a>');

	$can_edit = true;

	$arActions = Array();

	$arActions[] = array(
		"ICON" => "edit",
		"TEXT" => GetMessage($can_edit ? "MAIN_ADMIN_MENU_EDIT" : "MAIN_ADMIN_MENU_VIEW"),
		"ACTION" => $lAdmin->ActionRedirect(ADMIN_MODULE_NAME . "_settings_edit.php?ID=" . $f_ID),
		"DEFAULT" => true
	);

	$arActions[] = array(
		"ICON"=>"delete",
		"TEXT" => GetMessage("MAIN_ADMIN_MENU_DELETE"),
		"ACTION" => "if(confirm('".GetMessageJS('HLBLOCK_ADMIN_DELETE_ROW_CONFIRM')."')) ".
			$lAdmin->ActionRedirect(ADMIN_MODULE_NAME . "_settings.php?action=delete&ID=" . $f_ID . '&'.bitrix_sessid_get())
	);

	$row->AddActions($arActions);

	// deny group operations (hide checkboxes)
	$row->pList->bCanBeEdited = false;
}


// view
if ($_REQUEST["mode"] == "list") {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
} else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

	$context->Show();
}

$lAdmin->CheckListMode();

$lAdmin->DisplayList();

function DokaGetGroupName($group_id)
{
    $ID = IntVal($group_id);
    static $cache = array();

    if(!array_key_exists($ID, $cache)) {
    	$rsGroup = CGroup::GetByID($ID);
    	$arGroup = $rsGroup->Fetch();
        $cache[$ID] = $arGroup['NAME'];
    }

    return $cache[$ID];
}

if ($_REQUEST["mode"] == "list") {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
} else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}