<?php
define("ADMIN_MODULE_NAME", "doka.meetings");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

use Doka\Meetings\Settings as DMS;

// check access & exceptions
$RIGHT = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);
if ($RIGHT == 'D')
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));

if ($ex = $APPLICATION->GetException()) {
    require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

    $strError = $ex->GetString();
    ShowError($strError);

    require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';
    die();
}


// require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/tools/prop_userid.php");

// Р“СЂСѓРїРїС‹ РїРѕР»СЊР·РѕРІР°С‚РµР»РµР№
$arGROUPS = array();
$z = CGroup::GetList(($v1=""), ($v2=""), array("ACTIVE"=>"Y",/* "ADMIN"=>"N", */"ANONYMOUS"=>"N"));
while($zr = $z->Fetch())
{
	$ar = array();
	$ar["ID"] = intval($zr["ID"]);
	$ar["NAME"] = htmlspecialcharsbx($zr["NAME"]);
	$arGROUPS[] = $ar;
}

// Get additional data
$aTabs = array(
    array(
        'DIV' => 'edit1',
        'TAB' => GetMessage('DOKA_MEET_TAB'),
        'ICON' => 'service_request',
        'TITLE' => GetMessage('DOKA_MEET_TAB_DESCR')
    ),
);
$tabControl = new CAdminTabControl('tabControl', $aTabs);

// Data processing
$ID = intval($ID);
$errorMessage = '';
$bVarsFromForm = false;


if ($RIGHT >= 'W' && $REQUEST_METHOD == 'POST' && !empty($Update) && check_bitrix_sessid()) {

    $doka_meet_obj = new DMS();

    $DB->StartTransaction();

    // РџРѕРґРіРѕС‚Р°РІР»РёРІР°РµРј РїРѕР»СЏ
    $arFields = array(
        'NAME' => $NAME,
        'CODE' => $CODE,
        'IS_LOCKED' => $IS_LOCKED == 'Y' ? 1 : 0,
        'ACTIVE' => $ACTIVE == 'Y' ? 1 : 0,
        'GUESTS_GROUP' => (int)$GUESTS_GROUP,
        'IS_GUEST' => $IS_GUEST == 'Y' ? 1 : 0,
        'IS_HB' => $IS_HB == 'Y' ? 1 : 0,
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
    );

    if (!empty($ID)) {
        $res = $doka_meet_obj->Update($ID, $arFields);
    } else {
        $ID = $doka_meet_obj->Add($arFields);
        $res = ($ID > 0);
    }

    if (!$res) {
        $bVarsFromForm = true;
        $DB->Rollback();
    } else {
        $DB->Commit();
        if (!empty($apply)) {
            $_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"]=array("MESSAGE"=>GetMessage("DOKA_MEET_SAVE_OK"), "TYPE"=>"OK");
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_settings_edit.php?ID=".$ID."&lang=".LANG."&".$tabControl->ActiveTabParam());
        } else {
            LocalRedirect('/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?lang='.LANGUAGE_ID);
        }
    }
}

// Default settings
ClearVars();
$str_TIMEOUT_VALUE = 72;
$list_of_works = array();

// Get data from DB
if($ID > 0) {
	$doka_meet_obj = DMS::GetById($ID);
    if (!$doka_meet_obj->ExtractFields('str_'))
        $ID = 0;
}

// If data from form
if ($bVarsFromForm) {
    $DB->InitTableVarsForEdit(DMS::getTableName(), '', 'str_');
    if ($str_ACTIVE != 'Y') {
        $str_ACTIVE = 'N';
    }
}

//set title
if ($ID > 0) {
    $APPLICATION->SetTitle(str_replace('#NAME#', $str_NAME, GetMessage('DOKA_MEET_TITLE_UPDATE')));
} else {
    $APPLICATION->SetTitle(GetMessage('DOKA_MEET_TITLE_ADD'));
}

//context menu
$aMenu = array(array(
    'TEXT' => GetMessage('DOKA_MEET_LIST'),
    'ICON' => 'btn_list',
    'LINK' => '/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?lang='.LANGUAGE_ID.GetFilterParams('filter_', false),
));
if ($ID > 0) {
    $aMenu[] = array(
        'TEXT' => GetMessage('DOKA_MEET_DELETE'),
        'ICON' => 'btn_delete',
        'LINK' => 'javascript:if(confirm(\''.GetMessage('DOKA_MEET_DELETE_CONF').'\')) window.location=\'/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?action=delete&ID='.$ID.'&lang='.LANGUAGE_ID.'&'.bitrix_sessid_get().'#tb\';',
        'WARNING' => 'Y',
    );
}

// SHOW FORM
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

// Add admin context menu
$context = new CAdminContextMenu($aMenu);
$context->Show();

// Show msg
if(is_array($_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"])) {
    CAdminMessage::ShowMessage($_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"]);
    $_SESSION["SESS_ADMIN"]["POSTING_EDIT_MESSAGE"]=false;
}
if($message)
    echo $message->Show();
elseif($doka_meet_obj->LAST_ERROR != "")
    CAdminMessage::ShowMessage($doka_meet_obj->LAST_ERROR);
CAdminMessage::ShowMessage($errorMessage);
?>

<?
$fields = DMS::getFields();
$arFields = array();
foreach ($fields as $field) {
	$res = Array($field, GetMessage("DOKA_MEET_" . $field), $field, Array("text", 30));
	// РљР°СЃС‚РѕРјРёР·РёСЂСѓРµРј
	if ($field == 'ID') continue;

	if ($field == 'IS_LOCKED' || $field == 'ACTIVE' || $field == 'IS_GUEST' || $field == 'IS_HB') {
		$res[3] = array( 'checkbox', 'Y' );
	} else if ($field == 'MEMBERS_GROUP' || $field == 'GUESTS_GROUP' || $field == 'ADMINS_GROUP') {
		$res[3] = array('group');
	}

	$arFields[] = $res;
}
?>

<form method="POST" action="<?php echo $APPLICATION->GetCurPage() ?>?" id="doka_form">
    <?php
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>
    <input type="hidden" name="Update" value="Y">
    <input type="hidden" name="lang" value="<?php echo LANGUAGE_ID ?>">
    <input type="hidden" name="ID" value="<?php echo $ID ?>">
    <?echo bitrix_sessid_post();?>

    <?if ($ID > 0): ?>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">ID:</td>
            <td><?php echo $ID ?></td>
        </tr>
    <?endif;?>

	<?
	foreach($arFields as $arOption):
		$val = ${'str_' . $arOption[2]};
		$type = $arOption[3];
		?>
		<tr>
			<td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
				<label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
			<td width="60%">
				<?if($type[0]=="checkbox"):?>
					<input type="checkbox" id="<?echo htmlspecialcharsbx($arOption[0])?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="1")echo" checked";?>>
				<?elseif($type[0]=="text"):?>
					<input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>">
				<?elseif($type[0]=="textarea"):?>
					<textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
				<?elseif($type[0]=="group"):?>
					<select name="<?echo htmlspecialcharsbx($arOption[0])?>">
						<?
						foreach($arGROUPS as $group):
						?>
							<option <?if($group["ID"] == $val):?>selected<?endif;?> value="<?=$group["ID"]?>"><?=$group["NAME"]." [".$group["ID"]."]"?></option>
						<?endforeach?>
					</select>
				<?endif?>
			</td>
		</tr>
	<?endforeach?>

    <?
    $tabControl->Buttons(array(
        'disabled' => $RIGHT < 'W',
        'back_url' => '/bitrix/admin/' . ADMIN_MODULE_NAME . '_settings.php?lang='.LANGUAGE_ID.GetFilterParams('filter_', false),
    ));
    $tabControl->End();
    ?>
</form>
<?php echo BeginNote() ?>
<span class="required">*</span> <?php echo GetMessage('REQUIRED_FIELDS') ?>
<?php echo EndNote() ?>

<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php' ?>
