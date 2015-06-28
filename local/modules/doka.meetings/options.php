<?
$module_id = "doka.meetings";
$CAT_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($CAT_RIGHT >= "R") :

global $MESS;
include(GetLangFileName($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/main/lang/", "/options.php"));
include(GetLangFileName($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/" . $module_id . "/lang/", "/options.php"));
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/iblock.php");
include_once($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/" . $module_id . "/include.php");

if ($ex = $APPLICATION->GetException()) {
	require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");

	$strError = $ex->GetString();
	ShowError($strError);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}


$arInputOptions = Array(
	// Array("EVENT_TIMEOUT_VALUE", GetMessage("DOKA_TIMEOUT_VALUE"), "72", Array("text", 30)),
);

$aTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("IBLOCK_ADM_IMP_TAB1") ,
		"ICON" => "iblock",
		"TITLE" => GetMessage("IBLOCK_ADM_IMP_TAB1_ALT"),
	) ,
	array(
		"DIV" => "edit2", 
		"TAB" => GetMessage("MAIN_TAB_RIGHTS"), 
		"ICON" => "doka_servicerequest_settings", 
		"TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")
	),
);


if($REQUEST_METHOD=="POST" && strlen($Update)>0 && check_bitrix_sessid())
{
	COption::SetOptionInt($module_id, 'doka_meet_admin_id', $GROUP_ADMIN_ID);

	foreach($arInputOptions as $arOption)
	{
		$name=$arOption[0];
		$val=$_REQUEST[$name];
		if($arOption[2][0]=="checkbox" && $val!="Y")
			$val="N";
		COption::SetOptionString($module_id, $name, $val, $arOption[1]);
	}
}

if ($REQUEST_METHOD=="GET" && strlen($RestoreDefaults)>0 && $CAT_RIGHT=="W" && check_bitrix_sessid())
{
	COption::RemoveOption($module_id);
	$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
	while($zr = $z->Fetch())
		$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
		
	LocalRedirect($APPLICATION->GetCurPage()."?lang=".LANG."&mid=".urlencode($mid));
}

$GROUP_ADMIN_ID = COption::GetOptionInt($module_id, 'doka_meet_admin_id');

$arGROUPS = array();
$z = CGroup::GetList(($v1=""), ($v2=""), array("ACTIVE"=>"Y", "ADMIN"=>"N", "ANONYMOUS"=>"N"));
while($zr = $z->Fetch())
{
	$ar = array();
	$ar["ID"] = intval($zr["ID"]);
	$ar["NAME"] = htmlspecialcharsbx($zr["NAME"]);
	$arGROUPS[] = $ar;
}



$tabControl = new CAdminTabControl("tabControl", $aTabs, false, true);
$tabControl->Begin();
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?echo LANG?>" name="ara">
<?=bitrix_sessid_post();?>
<?

$tabControl->BeginNextTab();
?>
	<tr>
		<td><?echo GetMessage("IBLOCK_ADM_ADMIN_ID"); ?></td>
		<td>
			<select name="GROUP_ADMIN_ID">
				<?
				foreach($arGROUPS as $group):
				?>
					<option <?if($group["ID"] == $GROUP_ADMIN_ID):?>selected<?endif;?> value="<?=$group["ID"]?>"><?=$group["NAME"]." [".$group["ID"]."]"?></option>
				<?endforeach?>
			</select>
		</td>
	</tr>

	<?
	foreach($arInputOptions as $arOption):
		$val = COption::GetOptionString($module_id, $arOption[0], $arOption[2]);
		$type = $arOption[3];
		?>
		<tr>
			<td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
				<label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
			<td width="60%">
				<?if($type[0]=="checkbox"):?>
					<input type="checkbox" id="<?echo htmlspecialcharsbx($arOption[0])?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?>>
				<?elseif($type[0]=="text"):?>
					<input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>">
				<?elseif($type[0]=="textarea"):?>
					<textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
				<?endif?>
			</td>
		</tr>
	<?endforeach?>

<?
$tabControl->EndTab();

$tabControl->BeginNextTab();
?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
	<tr>
		<td valign="top"><?echo GetMessage("CO_PAR_DPG_CSV") ?></td>
		<td valign="top"><?
			$strVal = COption::GetOptionString("catalog", "allowed_group_fields", $defCatalogAvailGroupFields);
			$arVal = explode(",", $strVal);
			?>
        </td>
	</tr>
<?
$tabControl->EndTab();

$tabControl->Buttons();?>
<script language="JavaScript">
function RestoreDefaults()
{
	if (confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
		window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid)?>&<?=bitrix_sessid_get()?>";
}
</script>
<input type="submit" <?if ($CAT_RIGHT<"W") echo "disabled" ?> name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
<input type="button" <?if ($CAT_RIGHT<"W") echo "disabled" ?> title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="RestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
<?$tabControl->End();?>
</form>
<?endif;?>
