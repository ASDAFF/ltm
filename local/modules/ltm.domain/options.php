<? if(!$USER->IsAdmin())
	return;

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$module_id = 'ltm.domain';

$aTabs = [
    array(
        "DIV" => "edit1",
        "TAB" => GetMessage("MAIN_TAB_SET"),
        "ICON" => "ib_settings",
        "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")
    ),
];
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$arAllOptions = $arBonusOptions;
$STAS_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($STAS_RIGHT >= "R") {
    if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid()) {
        if(strlen($RestoreDefaults)>0) {
            COption::RemoveOption($module_id);
        } else {
            foreach($arAllOptions as $arOption)
            {
                $name=$arOption[0];
                $val=$_REQUEST[$name];
                if($arOption[2][0]=="checkbox" && $val!="Y")
                        $val="N";
                COption::SetOptionString($module_id, $name, $val, $arOption[1]);
            }
        }
        if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
            LocalRedirect($_REQUEST["back_url_settings"]);
        else
            LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
    }

    $tabControl->Begin();
    ?>
    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">
    <?$tabControl->BeginNextTab();?>

    <?$tabControl->Buttons();?>
            <input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
            <input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
            <?if(strlen($_REQUEST["back_url_settings"])>0):?>
                    <input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
                    <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
            <?endif?>
            <input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
            <?=bitrix_sessid_post();?>
    <?$tabControl->End();?>
    </form>
<?
}