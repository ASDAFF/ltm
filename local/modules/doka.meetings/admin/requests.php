<?php

// admin initialization
define("ADMIN_MODULE_NAME", "doka.meetings");
define("DOKA_EDIT_PAGE", "_requests_edit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

use Doka\Meetings\Settings as DMS;
use Doka\Meetings\Requests as DMR;

$APPLICATION->SetTitle(GetMessage('HLBLOCK_ADMIN_ROWS_LIST_PAGE_TITLE'));

$sTableID = DMR::getTableName();
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);


$arFilterFields = Array(
    "find_sender_id",
	"find_receiver_id",
    "find_EXHIBITION_ID",
	"find_update_from",
	"find_update_to"
    // "find_TIMESLOT_ID",
);
$lAdmin->InitFilter($arFilterFields);
$arFilter = array(
	// "STATUS" => $find_STATUS,
	"EXHIBITION_ID" => (int)$find_EXHIBITION_ID,
	// "TIMESLOT_ID" => (int)$find_TIMESLOT_ID,
);

if(!empty($find_sender_id)){
	$find_sender_id = trim($find_sender_id);
	if((int)$find_sender_id == $find_sender_id) {
		$arFilter["SENDER_ID"] = $find_sender_id;
	} else {
		$rsUser = CUser::GetByLogin($find_sender_id);
		if($arUser = $rsUser->Fetch())
			$arFilter["SENDER_ID"] = $arUser["ID"];
		else
			$arFilter["SENDER_ID"] = 0;
	}
}

if($find_receiver_id != ''){
	$find_receiver_id = trim($find_receiver_id);
	if((int)$find_receiver_id == $find_receiver_id) {
		$arFilter["RECEIVER_ID"] = $find_receiver_id;
	} else {
		$rsUser = CUser::GetByLogin($find_receiver_id);
		if($arUser = $rsUser->Fetch())
			$arFilter["RECEIVER_ID"] = $arUser["ID"];
		else
			$arFilter["RECEIVER_ID"] = 0;
	}
}

if($find_update_from != ''){
	$arFilter["!UPDATED_AT"] = date("d.m.Y", strtotime($find_update_from));
}
if($find_update_to != ''){
	$timeUpdateTo = strtotime($find_update_to) + 86400;
	$arFilter["UPDATED_AT"] = date("d.m.Y", $timeUpdateTo);
}

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
	            if(!DMR::Delete($ID))
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
        "id" => "SENDER_ID",
        "content" => GetMessage("IBLIST_A_SENDER_ID"),
        "sort" => "SENDER_ID",
    ),
    array(
        "id" => "RECEIVER_ID",
        "content" => GetMessage("IBLIST_A_RECEIVER_ID"),
        "sort" => "RECEIVER_ID",
    ),
    array(
        "id" => "EXHIBITION_ID",
        "content" => GetMessage("IBLIST_A_EXHIBITION_ID"),
        "sort" => "EXHIBITION_ID",
    ),
    array(
        "id" => "STATUS",
        "content" => GetMessage("IBLIST_A_STATUS"),
        "sort" => "STATUS",
    ),
	array(
		"id" => "UPDATED_AT",
		"content" => GetMessage("IBLIST_A_DATE"),
		"sort" => "UPDATED_AT",
	),
);



// show all by default
foreach ($arHeaders as &$arHeader) {
	$arHeader['default'] = true;
}
unset($arHeader);

$lAdmin->AddHeaders($arHeaders);
if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true)) {
	$by = 'ID';
}

// select data
$rsData = DMR::GetList(array($by => $order), $arFilter);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();


if ($_REQUEST["mode"] !== "list") {
	// menu
	$aMenu = array(
		array(
			"TEXT"	=> GetMessage('HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON'),
			"TITLE"	=> GetMessage('HLBLOCK_ADMIN_ROWS_ADD_NEW_BUTTON'),
			"LINK"	=> ADMIN_MODULE_NAME . DOKA_EDIT_PAGE . "?lang=".LANGUAGE_ID,
			"ICON"	=> "btn_new",
		),
	);
}

$context = new CAdminContextMenu($aMenu);

// build list
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("PAGES")));
while($arRes = $rsData->NavNext(true, "f_"))
{
	$edit_url = ADMIN_MODULE_NAME . DOKA_EDIT_PAGE . '?ID=' . $f_ID;
	$row = $lAdmin->AddRow($f_ID, $arRes);

	$row->AddViewField("ID", '<a href="' . $edit_url . '" title="' . GetMessage("IBLIST_A_EDIT") . '">' . $f_ID . '</a>');
	$row->AddViewField("SENDER_ID", $f_SENDER_ID . ' [' . DokaGetUserName($f_SENDER_ID) .']');
	$row->AddViewField("RECEIVER_ID", $f_RECEIVER_ID . ' [' . DokaGetUserName($f_RECEIVER_ID) .']');
	$row->AddViewField("EXHIBITION_ID", $f_EXHIBITION_ID . ' [' . GetExhibitionName($f_EXHIBITION_ID) .']');
	// $row->AddViewField("STATUS", GetMessage('STATUS'));

	$can_edit = true;

	$arActions = Array();

	$arActions[] = array(
		"ICON" => "edit",
		"TEXT" => GetMessage($can_edit ? "MAIN_ADMIN_MENU_EDIT" : "MAIN_ADMIN_MENU_VIEW"),
		"ACTION" => $lAdmin->ActionRedirect(ADMIN_MODULE_NAME . DOKA_EDIT_PAGE . "?ID=" . $f_ID),
		"DEFAULT" => true
	);

	$arActions[] = array(
		"ICON"=>"delete",
		"TEXT" => GetMessage("MAIN_ADMIN_MENU_DELETE"),
		"ACTION" => "if(confirm('".GetMessageJS('HLBLOCK_ADMIN_DELETE_ROW_CONFIRM')."')) ".
			$lAdmin->ActionRedirect(ADMIN_MODULE_NAME .  "_requests.php?action=delete&ID=" . $f_ID . '&'.bitrix_sessid_get())
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


?>
<form method="GET" name="find_form" id="find_form" action="<?echo $APPLICATION->GetCurPage()?>">
<?
// Р¤РёР»СЊС‚СЂ РїРѕ РІС‹СЃС‚Р°РІРєР°Рј
// $arFindFields['STATUS'] = GetMessage('DOKA_FILTER_STATUS');
$arFindFields['EXHIBITION_ID'] = GetMessage('DOKA_FILTER_EXHIBITION_ID');
$arFindFields['SENDER_ID'] = GetMessage('DOKA_FILTER_SENDER_ID');
$arFindFields['RECEIVER_ID'] = GetMessage('DOKA_FILTER_RECEIVER_ID');
$arFindFields['UPDATE_AT'] = GetMessage('DOKA_FILTER_MODIFY');
$filterUrl = $APPLICATION->GetCurPageParam();
$oFilter = new CAdminFilter($sTableID."_filter", $arFindFields, array("table_id" => $sTableID, "url" => $filterUrl));
?>
<script type="text/javascript">
	var arClearHiddenFields = new Array();
	function applyFilter(el) {
	    BX.adminPanel.showWait(el);
	    <?=$sTableID."_filter";?>.OnSet('<?=CUtil::JSEscape($sTableID)?>', '<?=CUtil::JSEscape($filterUrl)?>');
	    return false;
	}

	function deleteFilter(el) {
	    BX.adminPanel.showWait(el);
	    if (0 < arClearHiddenFields.length)
	    {
	        for (var index = 0; index < arClearHiddenFields.length; index++)
	        {
	            if (undefined != window[arClearHiddenFields[index]])
	            {
	                if ('ClearForm' in window[arClearHiddenFields[index]])
	                {
	                    window[arClearHiddenFields[index]].ClearForm();
	                }
	            }
	        }
	    }
	    <?=$sTableID."_filter"?>.OnClear('<?=CUtil::JSEscape($sTableID)?>', '<?=CUtil::JSEscape($APPLICATION->GetCurPage().'?type='.urlencode($type).'&IBLOCK_ID='.urlencode($IBLOCK_ID).'&lang='.urlencode(LANG).'&')?>');
	    return false;
	}
</script>
<?$oFilter->Begin();?> 
    <tr>
        <td><?echo GetMessage("DOKA_FILTER_EXHIBITION_ID")?>:</td>
        <td>
        	<?
        	// РџРѕР»СѓС‡РёРј СЃРїРёСЃРѕРє РІС‹СЃС‚Р°РІРѕРє
        	$values = array( 'REFERENCE' => array(GetMessage('DOKA_FILTER_EXHIBITION_ID_ALL')), 'REFERENCE_ID' => array('') );
        	$exhibition_list = DMS::GetList(array(), array(), array('NAME', 'ID'));
        	while ( $exhibition = $exhibition_list->Fetch()) {
        		array_push($values['REFERENCE'], $exhibition['NAME']);
        		array_push($values['REFERENCE_ID'], $exhibition['ID']);
        	}
        	?>
        	<?echo SelectBoxFromArray("find_EXHIBITION_ID", $values, $find_EXHIBITION_ID, "", "");?>
        </td>
    </tr>
	<tr>
		<td><?echo GetMessage("DOKA_FILTER_SENDER_ID")?>:</td>
		<td><input type="text" name="find_sender_id" size="47" value="<?echo htmlspecialchars($find_sender_id)?>"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("DOKA_FILTER_RECEIVER_ID")?>:</td>
		<td><input type="text" name="find_receiver_id" size="47" value="<?echo htmlspecialchars($find_receiver_id)?>"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("DOKA_FILTER_MODIFY")?>:</td>
		<td><?echo CalendarPeriod("find_update_from", htmlspecialcharsex($find_update_from), "find_update_to", htmlspecialcharsex($find_update_to), "find_form", "N")?></td>
	</tr>
<?

$oFilter->Buttons();
?><input  class="adm-btn" type="submit" name="set_filter" value="<? echo GetMessage("admin_lib_filter_set_butt"); ?>" title="<? echo GetMessage("admin_lib_filter_set_butt_title"); ?>" onClick="return applyFilter(this);">
<input  class="adm-btn" type="submit" name="del_filter" value="<? echo GetMessage("admin_lib_filter_clear_butt"); ?>" title="<? echo GetMessage("admin_lib_filter_clear_butt_title"); ?>" onClick="deleteFilter(this); return false;">
<?
$oFilter->End();
?>
</form>
<?


$lAdmin->DisplayList();

function DokaGetExhibitionName($exhibition_id) {
    $ID = IntVal($exhibition_id);
    static $cache = array();

    if(!array_key_exists($ID, $cache)) {
    	$rsItem = DMS::GetList(array(), array('ID' => $ID), array('NAME'));
    	$arItem = $rsItem->Fetch();
        $cache[$ID] = $arItem['NAME'];
    }

    return $cache[$ID];
}
function DokaGetUserName($ID) {
	$ID = IntVal($ID);
    static $user_cache = array();
    if(!array_key_exists($ID, $user_cache))
    {
        $rsUser = CUser::GetByID($ID);
        $arUser = $rsUser->Fetch();
        $user_cache[$ID] = $arUser['LOGIN'];
    }
    return $user_cache[$ID];
}

if ($_REQUEST["mode"] == "list") {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
} else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}