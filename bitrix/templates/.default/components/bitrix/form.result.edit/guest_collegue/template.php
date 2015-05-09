<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? 
$APPLICATION->SetAdditionalCSS("/cabinet/edit/style.css");
$APPLICATION->AddHeadScript("/cabinet/edit/script.js");
?>
<?=str_replace(array("&formresult=editok","formresult=editok"),"", $arResult["FORM_HEADER"])?>
<?=bitrix_sessid_post()?>
<?$arNonShowedQuestion = array_flip(array_keys($arResult["QUESTIONS"]))?>
<?$arPriorArea = array("SIMPLE_QUESTION_383", "SIMPLE_QUESTION_244", "SIMPLE_QUESTION_212", "SIMPLE_QUESTION_497", "SIMPLE_QUESTION_526", "SIMPLE_QUESTION_878");?>
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
						<? if(in_array($FIELD_SID, $arPriorArea)){continue;}//пропуск приоритетных направлений?>
						<div class="form-group">
							<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
								<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
							<?endif;?>
							<label class="control-label" for="inputdemo"><?=$title?></label>
							<div class="data-control"><?=$arQuestion["HTML_CODE"]?></div>
						</div>
						
					<?endif?>
				<?endforeach?>
				

					<div class="pull-left company-info priority-wrap" style="display: block; clear: both;">
	
						<div class="title">Выберите приоритетные направления</div>
						
						<div class="priority-check-global">
							<label class="check-global" for="check_priority_global" type="checkbox">Global / Worldwide</label>
							<input id="check_priority_global" type="checkbox" name="PRIORITY_GLOBAL" value="" class = "none" />
						</div>
				<? //вывод приоритетных направлений?>
				<? foreach ($arPriorArea as $SID):?>
				<? 
				$arQuestion = $arResult["QUESTIONS"][$SID]; 
				$arValues = $arResult["arrVALUES"]["form_checkbox_{$SID}"]
				?>
	                    <div class="check-priority">
	                    	<div class="priority-check-all">
								<label class="check-all <?if(count($arQuestion["STRUCTURE"]) == count($arValues)):?>active-all<?endif; ?>" for="check_<?=$SID?>_ALL" type="checkbox"></label>
								<input id="check_<?=$SID?>_ALL" type="checkbox" name="<?=$SID?>_ALL" value="" class = "none" <?if(count($arQuestion["STRUCTURE"]) == count($arValues)):?>checked='checked'<?endif;?>/>
								
								<a href="javascript:void(0);" class="priority-toggle priority-name"><ins><?= $arQuestion["CAPTION"]?></ins></a>
								<a href="javascript:void(0);" class="priority-toggle priority-switch"><ins>Показать все страны</ins></a>
							</div>

	    					 <div class="priority-items" id="priority-items-<?= randString(5)?>">
		    					<? foreach ($arQuestion["STRUCTURE"] as $arAnswerRes):?>
		    						<label class="check-group <?= (in_array($arAnswerRes["ID"], $arValues))?"active-group":"";?>" for="check_<?=$SID?>_<?=$arAnswerRes["ID"]?>" type="checkbox" ><?=$arAnswerRes["MESSAGE"]?></label>
									<input id="check_<?=$SID?>_<?=$arAnswerRes["ID"]?>" type="checkbox" name="form_checkbox_<?= $SID?>[]" value="<?=$arAnswerRes["ID"]?>" class = "none" <?= (in_array($arAnswerRes["ID"], $arValues))?"checked='checked'":"";?>/>
			    				<? endforeach;?>
	    					</div>
	    				</div>
	    		<? endforeach;?>
					</div>
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
<script>LANGUAGE_ID = 'ru';</script>