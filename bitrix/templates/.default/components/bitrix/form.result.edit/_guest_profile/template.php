<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arParams["QUESTION_TO_SHOW"] = array(
		array("ID"=>"SIMPLE_QUESTION_750"),
		array("ID"=>"SIMPLE_QUESTION_823"),
		array("ID"=>"SIMPLE_QUESTION_115"),
		array("ID"=>"SIMPLE_QUESTION_391"),
		array("ID"=>"SIMPLE_QUESTION_773", "TITLE"=>"Адрес"),
		array("ID"=>"SIMPLE_QUESTION_672"),
		array("ID"=>"SIMPLE_QUESTION_678"),
		array("ID"=>"SIMPLE_QUESTION_756"),
		array("ID"=>"SIMPLE_QUESTION_636"),
		array("ID"=>"SIMPLE_QUESTION_373"),
		array("ID"=>"SIMPLE_QUESTION_552", "TITLE"=>"Web-сайт"),
		array("ID"=>"SIMPLE_QUESTION_166", "TITLE"=>"Описание компании"),
		array("ID"=>"SIMPLE_QUESTION_383"),
		array("ID"=>"SIMPLE_QUESTION_244"),
		array("ID"=>"SIMPLE_QUESTION_212"),
		array("ID"=>"SIMPLE_QUESTION_497"),
		array("ID"=>"SIMPLE_QUESTION_526"),
		array("ID"=>"SIMPLE_QUESTION_878"),
)?>

<?=$arResult["FORM_HEADER"]?>
<?=bitrix_sessid_post()?>

<table class="form-table data-table">
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
	<?foreach($arParams["QUESTION_TO_SHOW"] as $arShowQuestion):
		$FIELD_SID = $arShowQuestion["ID"];
		$arQuestion = $arResult["QUESTIONS"][$FIELD_SID];
		$title = isset($arShowQuestion["TITLE"]) ? $arShowQuestion["TITLE"] : $arQuestion["CAPTION"];
	?>
	<tr>
		<td>(<?=$FIELD_SID?>)
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
			<?endif;?>
			<?=$title?>
			<?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
		</td>
		<td><?=$arQuestion["HTML_CODE"]?></td>
	</tr>
	<?endforeach?>

	<?
	$FIELD_SID = "SIMPLE_QUESTION_269";
	$arQuestion = $arResult["QUESTIONS"][$FIELD_SID]?>
	<tr>
		<td>
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
			<?endif;?>
			<?=$arQuestion["CAPTION"]?>
			<?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
		</td>
		<td><?=$arQuestion["HTML_CODE"]?></td>
	</tr>

	<tr>
		<th colspan="2">
			<input type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
			&nbsp;<input type="hidden" name="web_form_apply" value="Y" /><input type="submit" name="web_form_apply" value="<?=GetMessage("FORM_APPLY")?>" />
			&nbsp;<input type="reset" value="<?=GetMessage("FORM_RESET");?>" />
		</th>
	</tr>
</table>
<?=$arResult["FORM_FOOTER"]?>
