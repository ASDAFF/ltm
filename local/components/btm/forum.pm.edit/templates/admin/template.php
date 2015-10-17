<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/js/main/utils.js"></script>', true);
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/components/bitrix/forum.interface/templates/.default/script.js"></script>', true);
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/components/bitrix/forum.interface/templates/popup/script.js"></script>', true);
IncludeAJAX();
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
/*******************************************************************/
if (LANGUAGE_ID == 'ru')
{
	$path = str_replace(array("\\", "//"), "/", dirname(__FILE__)."/ru/script.php");
	@include_once($path);
}
$tabIndex = 1;
/********************************************************************
				/Input params
********************************************************************/
?>
<?
if(isset($arResult["MESSAGE_SUCCESS"]) && $arResult["MESSAGE_SUCCESS"] != ''){
	?>
    <p style="color:#F00;"><?=$arResult["MESSAGE_SUCCESS"];?></p>
    <script type="text/javascript" language="javascript"> window.setTimeout("self.close();", 5000); </script>
    <p>This window will close automatically in 5 second.</p>
	<?
}
else{
	if (!empty($arResult["ERROR_MESSAGE"])):
	?>
	<p style="color:#F00;"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></p>
	<?
	endif;?>
    <h2 class="reg_title"><?=GetMessage("PM_HEAD")?></h2>
    <a name="postform"></a>
    <form name="REPLIER" id="REPLIER" action="<?=POST_FORM_ACTION_URI?>" method="POST" onsubmit="return ValidateForm(this);"<?
        ?> onkeydown="if(null != init_form){init_form(this)}" onmouseover="if(init_form){init_form(this)}" class="forum-form">
        <input type="hidden" name="PAGE_NAME" value="pm_edit" />
        <input type="hidden" name="action" id="action" value="<?=$arResult["action"]?>" />
        <input type="hidden" name="FID" value="<?=$arResult["FID"]?>" />
        <input type="hidden" name="MID" value="<?=$arResult["MID"]?>" />
        <input type="hidden" name="mode" value="<?=$arResult["mode"]?>" />
        <input type="hidden" name="USER_ID" id="USER_ID" value="<?=$arResult["POST_VALUES"]["USER_ID"]?>" readonly="readonly" />
        <?=bitrix_sessid_post()?>
    <table width="100%" border="0" cellspacing="0" cellpadding="7"  class="form_edit">
      <tr>
        <td width="120"><strong>From</strong></td>
        <td><?=$arResult["USERS"]["FROM"]?></td>
      </tr>
      <tr>
        <td><strong>To</strong></td>
        <td><?=$arResult["USERS"]["TO"]?></td>
      </tr>
      <tr>
        <td><strong>Subject</strong></td>
        <td><input name="POST_SUBJ" id="POST_SUBJ" type="text" value="<? if($arResult["POST_VALUES"]["POST_SUBJ"]){ echo $arResult["POST_VALUES"]["POST_SUBJ"];}else{ echo $arResult["MESS"]["SUBJ"];}?>" tabindex="<?=$tabIndex++;?>" size="70" /></td>
      </tr>
      <tr>
        <td><strong>Message</strong></td>
        <td><textarea name="POST_MESSAGE" class="post_message" cols="72" rows="10" tabindex="<?=$tabIndex++;?>"><?=$arResult["POST_VALUES"]["POST_MESSAGE"]?></textarea></td>
      </tr>
	<tr>
		<td colspan="2"  class="send">
        	<input name="COPY_TO_OUTBOX" type="hidden" value="Y" id="COPY_TO_OUTBOX" tabindex="<?=$tabIndex++;?>" /><input type="submit" name="SAVE_BUTTON" id="SAVE_BUTTON" tabindex="<?=$tabIndex++;?>" value="<?=($arResult["action"] == "save" ? GetMessage("F_ACT_SAVE") : GetMessage("F_ACT_SEND"))?>" tabindex="<?=$tabIndex++;?>" />
		</td>
	</tr>
</table>
    </form>

    <script language="Javascript">
    <?if (!empty($arResult["POST_VALUES"]["SHOW_NAME"]["text"])):?>
    window.switcher = '<?=CUtil::JSEscape($arResult["POST_VALUES"]["SHOW_NAME"]["text"])?>';
    <?elseif (!empty($arResult["POST_VALUES"]["USER_ID"])):?>
    window.switcher = '<?=CUtil::JSEscape($arResult["POST_VALUES"]["USER_ID"])?>';
    <?else:?>
    window.switcher = '';
    <?endif;?>
    function fSearchUser()
    {
        var name = 'USER_ID';
        var template_path = '<?=CUtil::JSEscape($arResult["pm_search_for_js"])?>';
        var handler = document.getElementById('input_'+name);
        var div_ = document.getElementById('div_'+name);
        if (typeof handler != "object" || null == handler || typeof div_ != "object")
            return false;


        if (window.switcher != handler.value)
        {
            window.switcher = handler.value;
            handler.form.elements[name].value=handler.value;
            if (handler.value != '')
            {
                div_.innerHTML = '<i><?=CUtil::JSEscape(GetMessage("FORUM_MAIN_WAIT"))?></i>';
                document.getElementById('frame_'+name).src=template_path.replace(/\#LOGIN\#/gi, handler.value);
            }
            else
                div_.innerHTML = '';
        }
        setTimeout(fSearchUser, 1000);
        return true;
    }
    fSearchUser();

    var bSendForm = false;
    if (typeof oErrors != "object")
        var oErrors = {};
    oErrors['no_topic_name'] = "<?=CUtil::JSEscape(GetMessage("JERROR_NO_TOPIC_NAME"))?>";
    oErrors['no_message'] = "<?=CUtil::JSEscape(GetMessage("JERROR_NO_MESSAGE"))?>";
    oErrors['max_len'] = "<?=CUtil::JSEscape(GetMessage("JERROR_MAX_LEN"))?>";
    oErrors['no_url'] = "<?=CUtil::JSEscape(GetMessage("FORUM_ERROR_NO_URL"))?>";
    oErrors['no_title'] = "<?=CUtil::JSEscape(GetMessage("FORUM_ERROR_NO_TITLE"))?>";
    oErrors['no_path'] = "<?=CUtil::JSEscape(GetMessage("FORUM_ERROR_NO_PATH_TO_VIDEO"))?>";
    if (typeof oText != "object")
        var oText = {};
    oText['author'] = " <?=CUtil::JSEscape(GetMessage("JQOUTE_AUTHOR_WRITES"))?>:\n";
    oText['enter_url'] = "<?=CUtil::JSEscape(GetMessage("FORUM_TEXT_ENTER_URL"))?>";
    oText['enter_url_name'] = "<?=CUtil::JSEscape(GetMessage("FORUM_TEXT_ENTER_URL_NAME"))?>";
    oText['enter_image'] = "<?=CUtil::JSEscape(GetMessage("FORUM_TEXT_ENTER_IMAGE"))?>";
    oText['list_prompt'] = "<?=CUtil::JSEscape(GetMessage("FORUM_LIST_PROMPT"))?>";
    oText['video'] = "<?=CUtil::JSEscape(GetMessage("FORUM_VIDEO"))?>";
    oText['path'] = "<?=CUtil::JSEscape(GetMessage("FORUM_PATH"))?>:";
    oText['width'] = "<?=CUtil::JSEscape(GetMessage("FORUM_WIDTH"))?>:";
    oText['height'] = "<?=CUtil::JSEscape(GetMessage("FORUM_HEIGHT"))?>:";

    oText['BUTTON_OK'] = "<?=CUtil::JSEscape(GetMessage("FORUM_BUTTON_OK"))?>";
    oText['BUTTON_CANCEL'] = "<?=CUtil::JSEscape(GetMessage("FORUM_BUTTON_CANCEL"))?>";

    if (typeof oHelp != "object")
        var oHelp = {};

    </script>
	<?
}
?>