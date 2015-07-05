<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?=str_replace(array("&formresult=editok","formresult=editok"),"", $arResult["FORM_HEADER"])?>
<?=bitrix_sessid_post()?>
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
				if(!isset($arShowQuestion["DISABLED"]))
					{
					    unset($arNonShowedQuestion[$FIELD_SID]);
					}
			?>
				<?if(isset($arShowQuestion["IS_PIC"])):$bShowedLeftPart=true;?>
					<div class="pull-left profil-photo">
						<div class="member">
							<?//if(preg_match('/src="([^"]+)"/', $arQuestion["HTML_CODE"], $matches)):?>
							<?
							$arPhoto = CFormResult::GetFileByAnswerID($arParams["RESULT_ID"], $arQuestion["STRUCTURE"][0]["ID"]);
							if($arPhoto)
							{
							    $arResizePhoto = CFile::ResizeImageGet($arPhoto["USER_FILE_ID"], Array("width"=>108, "height"=>108), BX_RESIZE_IMAGE_EXACT);
							    ?>
							    <img src="<?=$arResizePhoto["src"]?>" alt="userpic">
							    <?
							}
							?>
							<?//endif?>
						</div>
						<label class="photo-uploader">
							<input class="inputfile" type="file" size="0" name="form_image_<?=$arQuestion["STRUCTURE"][0]["ID"]?>">
							Upload photo
						</label>
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
					if(!isset($arShowQuestion["DISABLED"]))
					{
					    unset($arNonShowedQuestion[$FIELD_SID]);
					}
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
		<input type="submit" name="web_form_apply" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
	</div>
	<? endif;?>
</div>
<?=$arResult["FORM_FOOTER"]?>