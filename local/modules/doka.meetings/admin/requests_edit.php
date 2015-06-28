<?php
define("ADMIN_MODULE_NAME", "doka.meetings");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".ADMIN_MODULE_NAME."/classes/lib.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/tools/prop_userid.php");

IncludeModuleLangFile(__FILE__);

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

use Doka\Meetings\Requests as DMR;
use Doka\Meetings\Settings  as DMS;

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

$arSelects = array();
// Р”РѕСЃС‚СѓРїРЅС‹Рµ РІС‹СЃС‚Р°РІРєРё
$arSelects['EXHIBITION_ID'][] = array(
    'ID' => '',
    'NAME' => GetMessage('DOME_MEET_CHOOSE_EXHIBITION')
);
$z = DMS::GetList(array(), array("ACTIVE" => true), array('ID', 'NAME'));
while($zr = $z->Fetch()) {
    $ar = array();
    $ar["ID"] = intval($zr["ID"]);
    $ar["NAME"] = htmlspecialcharsbx($zr["NAME"]);
    $arSelects['EXHIBITION_ID'][] = $ar;
}

// РЎС‚Р°С‚СѓСЃС‹ Р·Р°РїСЂРѕСЃРѕРІ
$arStatuses = DMR::getStatuses();
foreach ($arStatuses as $id => $code) {
    $arSelects['STATUS'][] = array(
        'ID' => $code,
        'NAME' => GetMessage('DOKA_MEET_' . strtoupper($code))
    );
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

    $doka_meet_obj = new DMR(false, false);
    
    $DB->StartTransaction();

    // РџРѕРґРіРѕС‚Р°РІР»РёРІР°РµРј РїРѕР»СЏ
    $arFields = array(
        'RECEIVER_ID' => $RECEIVER_ID,
        'SENDER_ID' => $SENDER_ID,
        'EXHIBITION_ID' => $EXHIBITION_ID,
        'TIMESLOT_ID' => $TIMESLOT_ID,
        'STATUS' => $STATUS,
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
            LocalRedirect("/bitrix/admin/" . ADMIN_MODULE_NAME . "_requests_edit.php?ID=".$ID."&lang=".LANG."&".$tabControl->ActiveTabParam());
        } else {
            LocalRedirect('/bitrix/admin/' . ADMIN_MODULE_NAME . '_requests.php?lang='.LANGUAGE_ID);
        }
    }
}

// Default settings
ClearVars();
$str_SORT = 10;
if ($ID <= 0)
	$str_STATUS = 'confirmed';

// Get data from DB
if($ID > 0) {
	$doka_meet_obj = DMR::GetById($ID);
    if (!$doka_meet_obj->ExtractFields('str_'))
        $ID = 0;
}

// $test = new DMR(6);

// var_dump($test->getFreeTimeSlots());
// $tmp = $test->getBusyCompanies();
// $tmp =  $test->getFreeCompByTime(25);
// var_dump($test->getFreeTimesByComp(98));
// $tmp2 = $test->getUnconfirmedRequestsTotal();
// var_dump($tmp2);
// while ($item = $tmp->Fetch()) {
//     var_dump($item);
// }
// var_dump($test->getAllTimesByComp(1));


// If data from form
if ($bVarsFromForm) {
    $DB->InitTableVarsForEdit(DMR::getTableName(), '', 'str_');
}

//set title
if ($ID > 0) {
    $APPLICATION->SetTitle( GetMessage('DOKA_MEET_TITLE_UPDATE', array('#EXHIBITION_ID#' => $str_EXHIBITION_ID)) );
} else {
    $APPLICATION->SetTitle(GetMessage('DOKA_MEET_TITLE_ADD'));
}

//context menu
$aMenu = array(array(
    'TEXT' => GetMessage('DOKA_MEET_LIST'),
    'ICON' => 'btn_list',
    'LINK' => '/bitrix/admin/' . ADMIN_MODULE_NAME . '_requests.php?lang='.LANGUAGE_ID.GetFilterParams('filter_', false),
));
if ($ID > 0) {
    $aMenu[] = array(
        'TEXT' => GetMessage('DOKA_MEET_DELETE'),
        'ICON' => 'btn_delete',
        'LINK' => 'javascript:if(confirm(\''.GetMessage('DOKA_MEET_DELETE_CONF').'\')) window.location=\'/bitrix/admin/' . ADMIN_MODULE_NAME . '_requests.php?action=delete&ID='.$ID.'&lang='.LANGUAGE_ID.'&'.bitrix_sessid_get().'#tb\';',
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
$fields = DMR::getFields();
$arFields = array();
foreach ($fields as $field) {
	$res = Array($field, GetMessage("DOKA_MEET_" . $field), $field, Array("text", 30));
	// РљР°СЃС‚РѕРјРёР·РёСЂСѓРµРј
	if ($field == 'ID') continue;

	if ($field == 'STATUS') {
        $res[3] = array('select');
    } else if ($field == 'TIMESLOT_ID') {
        $res[3] = array('timeslot');
    } else if ($field == 'CREATED_AT' || $field == 'UPDATED_AT') {
        $res[3] = array('info');
        if ($ID <= 0)
            $res[4] = 'hide';
    } else if ($field == 'MODIFIED_BY') {
        $res[3] = array('userNAME');
        if ($ID <= 0)
            $res[4] = 'hide';
    } else if ($field == 'EXHIBITION_ID') {
        $res[4] = 'hide';
    } else if ($field == 'SENDER_ID' || $field == 'RECEIVER_ID') {
    	$res[3] = array('userID');
    }

	$arFields[] = $res;
}
?>

<form method="POST" action="<?php echo $APPLICATION->GetCurPage() ?>?" id="doka_form" name="doka_form">
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
        if ($arOption[4] == 'hide') continue;

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
				<?elseif($type[0] == "info"):?>
                    <?echo htmlspecialcharsbx($val)?>
				<?elseif($type[0]=="textarea"):?>
					<textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
				<?elseif($type[0]=="select"):?>
					<select name="<?echo htmlspecialcharsbx($arOption[0])?>">
						<?
						foreach($arSelects[$arOption[0]] as $item):
						?>
							<option <?if($item["ID"] == $val):?>selected<?endif;?> value="<?=$item["ID"]?>"><?=$item["NAME"] . ($item["ID"] > 0 ? " [".$item["ID"]."]" : "") ?></option>
						<?endforeach?>
					</select>
				<?elseif($type[0]=="timeslot"):?>
					<?echo DokaGetTimeslotDropDownList($val, 'EXHIBITION_ID', 'TIMESLOT_ID', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"'); ?>
				<?elseif($type[0]=="userNAME"):?>
                    <?echo htmlspecialcharsbx($val)?> [<?echo DokaGetUserName($val);?>]
				<?elseif($type[0]=="userID"):?>
	                <?php
	                    $html = CIBlockPropertyUserID::GetPropertyFieldHtml(
	                        array(),
	                        array(
	                            'VALUE' => $val,
	                            'DESCRIPTION' => ''
	                        ),
	                        array(
	                            "VALUE"=>$arOption[0],
	                            "DESCRIPTION"=>'',
	                            "FORM_NAME"=> 'doka_form',
	                            "MODE"=>"FORM_FILL",
	                            "COPY"=> false,
	                        )
	                    );
	                    echo $html;
	                ?>
				<?endif?>
			</td>
		</tr>
	<?endforeach?>

    <?
    $tabControl->Buttons(array(
        'disabled' => $RIGHT < 'W',
        'back_url' => '/bitrix/admin/' . ADMIN_MODULE_NAME . '_requests_edit.php?lang='.LANGUAGE_ID.GetFilterParams('filter_', false),
    ));
    $tabControl->End();
    ?>
</form>
<?php echo BeginNote() ?>
<span class="required">*</span> <?php echo GetMessage('REQUIRED_FIELDS') ?>
<?php echo EndNote() ?>

<?php
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
?>

<?php require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php' ?>
