<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?=$arResult["FORM_HEADER"]?>
<?=bitrix_sessid_post()?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
	<tbody>
<?if ($arResult["FORM_NOTE"]):?>
		<tr>
			<td colspan="2" align="center" style="color:#F00"><?=$arResult["FORM_NOTE"]?></td>
		</tr>
<?endif?>
<?if ($arResult["isFormErrors"] == "Y"):?>
		<tr>
			<td colspan="2" align="center" style="color:#F00"><?=$arResult["FORM_ERRORS_TEXT"];?></td>
		</tr>
<?endif;?>
	<?
	if ($arResult["isAccessFormParams"] == "Y")
	{?>
		<tr>
			<td width="130">ID:</td>
			<td><?=$arResult["RESULT_ID"]?></td>
		</tr>
	<?
    }

/***********************************************************************************
					Form questions
***********************************************************************************/ 
		?>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		if($arQuestion["TYPE"] == "check"){
	?>
	<tr>
		<td valign="top">
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
			<?endif;?>
			<?=$arQuestion["CAPTION"]?><?=$arResult["arQuestions"][$FIELD_SID]["REQUIRED"] == "Y" ? $arResult["REQUIRED_SIGN"] : ""?>
			<?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
		</td>
		<td class="checkbox"><?=$arQuestion["HTML_CODE"]?></td>
	</tr>
	<? 
		}
		else{
	?>
	<tr>
		<td>
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
			<?endif;?>
			<?=$arQuestion["CAPTION"]?><?=$arResult["arQuestions"][$FIELD_SID]["REQUIRED"] == "Y" ? $arResult["REQUIRED_SIGN"] : ""?>
			<?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
		</td>
		<td><?=$arQuestion["HTML_CODE"]?></td>
	</tr>
	<? 
		}
	} //endwhile 
	?>
	<tr>
		<td colspan="2"  class="send">
			<input type="hidden" name="web_form_apply" value="Y" /><input type="submit" name="web_form_apply" value="<?=GetMessage("FORM_APPLY")?>" />
			&nbsp;<input type="reset" value="<?=GetMessage("FORM_RESET");?>" />
		</td>
	</tr>
</table>
<?=$arResult["FORM_FOOTER"]?>
<?//print_r($arResult);?>