<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(isset($arResult["MESSAGE_SUCCESS"]) && $arResult["MESSAGE_SUCCESS"] != ''){
	?>
    <p style="color:#F00;"><?=$arResult["MESSAGE_SUCCESS"]?></p>
	<?
}
else{
	if (!empty($arResult["ERROR_MESSAGE"])):
	?>
	<p style="color:#F00;"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></p>
	<?
	endif;?>
    <form name="REPLIER" id="REPLIER" action="<?=POST_FORM_ACTION_URI?>" method="POST" class="forum-form">
        <input type="hidden" name="action" id="action" value="<?=$arResult["action"]?>" />
        <input type="hidden" name="FID" value="<?=$arResult["FID"]?>" />
        <input type="hidden" name="MID" value="<?=$arResult["MID"]?>" />
        <?=bitrix_sessid_post()?>
        <?
        if($arResult["USERS"]["TO_LIST"]["COUNT"] != 0){
			?><select name="RECIPIENT">
            	<option value="0"><?= GetMessage("HLM_CHOOSE_A_COMPANY")?></option>
            	<? foreach($arResult["USERS"]["TO_LIST"]["LIST"] as $ID => $COMP){
					?><option value="<?=$ID?>"><?=$COMP?></option><?
				}
				?>
            </select><?
		}
		else{
		?>	<input type="text" placeholder="<?= GetMessage("HLM_TO")?>" value="<?=$arResult["USERS"]["TO"]["NAME"]?>" readonly="readonly">
			<input name="RECIPIENT" type="hidden" value="<?=$arResult["USERS"]["TO"]["ID"]?>">
		<?
		}
		?>
            <input name="POST_SUBJECT" type="text" placeholder="<?= GetMessage("HLM_SUBJECT_PH")?>" value="<? if($arResult["POST_VALUES"]["POST_SUBJECT"]){ echo $arResult["POST_VALUES"]["POST_SUBJECT"];}else{ echo $arResult["MESS"]["SUBJECT"];}?>">
            <textarea name="POST_MESSAGE" placeholder="<?= GetMessage("HLM_MESSAGE_PH")?>" class="post_message" cols="55" rows="14"><?=$arResult["POST_VALUES"]["POST_MESSAGE"]?></textarea>
    <input name="COPY_TO_OUTBOX" type="hidden" value="<?= $arParamsp["COPY_TO_OUTBOX"]?>" id="COPY_TO_OUTBOX"/>
    <div class="send"><input type="submit" name="SAVE_BUTTON" id="SAVE_BUTTON" tabindex="<?=$tabIndex++;?>" value="<?= GetMessage("HLM_BUTTON_SEND")?>" tabindex="<?=$tabIndex++;?>" class="send_reg" /></div>
    </form>
	<?
}
?>
