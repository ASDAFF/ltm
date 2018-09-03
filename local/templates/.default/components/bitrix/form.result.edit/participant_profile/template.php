<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?=str_replace(array("&formresult=editok","formresult=editok"),"", $arResult["FORM_HEADER"])?>
<?=bitrix_sessid_post()?>

<? $APPLICATION->AddHeadScript("/assets/js/validate_form.js"); ?>

<?$arNonShowedQuestion = array_flip(array_keys($arResult["QUESTIONS"]))?>

<div class="edit-profil pull-overflow">
	<?if(isset($arResult["FORM_ERRORS"])):?>
		<p><?=$arResult["FORM_ERRORS"]?></p>
	<?endif?>

	<?foreach($arParams["QUESTION_TO_SHOW"] as $arShowQuestionBlock):$bShowedLeftPart=false;?>
		<div class="profil pull-overflow">
			<?if(isset($arShowQuestionBlock["NAME"])):?>
				<div class="pull-overflow headline"><?=$arShowQuestionBlock["NAME"]?></div>
			<?endif?>

			<?foreach($arShowQuestionBlock["ITEMS"] as $arShowQuestion):
				$FIELD_SID = $arShowQuestion["ID"];
				$arQuestion = $arResult["QUESTIONS"][$FIELD_SID];
				$title = isset($arShowQuestion["TITLE"]) ? $arShowQuestion["TITLE"] : $arQuestion["CAPTION"];
				unset($arNonShowedQuestion[$FIELD_SID]);
			?>

				<?if(isset($arShowQuestion["IS_PIC"])):$bShowedLeftPart=true;?>
					<div class="pull-left profil-photo">
						<?$arPhoto = CFormResult::GetFileByAnswerID($arParams["RESULT_ID"], $arQuestion["STRUCTURE"][0]["ID"]); ?>
						<?$APPLICATION->IncludeComponent("rarus:photo.input",
							".default",
							array(
								"WIDTH" => 110,
								"HEIGHT" => 110,
								"INPUT_NAME" => "form_image_{$arQuestion["STRUCTURE"][0]["ID"]}",
								"FILE_ID" => $arPhoto["USER_FILE_ID"] ? $arPhoto["USER_FILE_ID"] : false,
							),
							false
						);?>
					</div>
				<?endif?>
			<?endforeach;?>

			<?if(!$bShowedLeftPart):?>
				<div class="pull-left profil-photo">&nbsp;</div>
			<?endif?>

			<div class="profil-field pull-left">
				<?foreach($arShowQuestionBlock["ITEMS"] as $arShowQuestion):
					$FIELD_SID = $arShowQuestion["ID"];
					$arQuestion = $arResult["QUESTIONS"][$FIELD_SID];
					$title = isset($arShowQuestion["TITLE"]) ? $arShowQuestion["TITLE"] : $arQuestion["CAPTION"];
					unset($arNonShowedQuestion[$FIELD_SID]);
				?>
					<?if(!isset($arShowQuestion["IS_PIC"])):?>
						<?if(isset($arShowQuestion["DISABLED"])) {
							$arQuestion["HTML_CODE"] = str_replace("class=", "disabled class=", $arQuestion["HTML_CODE"]);
						}?>
						<div class="form-group">
							<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
								<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
							<?endif;?>
							<label class="control-label" for="inputdemo"><?=$title?></label>
							<div class="data-control"><?=$arQuestion["HTML_CODE"]?></div>
						</div>
					<?endif?>
				<?endforeach?>
			</div>
		</div>
	<?endforeach?>

	<div style="display: none">
		<?foreach($arNonShowedQuestion as $FIELD_SID=>$somethingNotUsefulData):
			$arQuestion = $arResult["QUESTIONS"][$FIELD_SID]?>
			<?=$arQuestion["HTML_CODE"]?>
		<?endforeach?>
	</div>

	<? if("Y" == $arParams["EDITING"]):?>
	<div class="send-change send">
		<input type="button" name="web_form_apply-btn" class="submit-particip-btn" value="Save" />
		<input type="submit" name="web_form_apply" value="Save" class="submit-particip-send" style="display: none;" />
	</div>
	<? endif;?>

</div>
<?=$arResult["FORM_FOOTER"]?>
<?//вешаем обработчик копирования имейла в подтверждение имейла
if(strlen($arParams["EMAIL_SID"]) > 0 && strlen($arParams["CONF_EMAIL_SID"]) > 0)
{
	$email_question = $arResult["QUESTIONS"][$arParams["EMAIL_SID"]];
	$conf_email_question = $arResult["QUESTIONS"][$arParams["CONF_EMAIL_SID"]];
	
	//echo "<pre>".print_r($email_question, true)."</pre>";
	
	$emailFieldName = "form_{$email_question["STRUCTURE"][0]["FIELD_TYPE"]}_{$email_question["STRUCTURE"][0]["ID"]}";
	$confEmailFieldName = "form_{$conf_email_question["STRUCTURE"][0]["FIELD_TYPE"]}_{$conf_email_question["STRUCTURE"][0]["ID"]}";

	//вываливаем обработчик
	?>
	<script type="text/javascript">
	$(function() {
		$("div.edit-profil").on("change", "input[name=<?=$emailFieldName?>]", function(){
			var inpConfEmail = $("input[name=<?=$confEmailFieldName?>]");
			inpConfEmail.val($(this).val());
		});
	});
	</script>
<?
}
?>
<script type="text/javascript">
	LANGUAGE_ID = 'en';
</script>