<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/header.php");
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/function.php");
?>
<script>
AjaxPatch = <?=CUtil::PhpToJSObject($arResult["AJAX_PATCH"])?>;
</script>
<div class="registr_exh clearfix">
	<span class="before"><?=GetMessage("R_BEFORE_TERMS", array("#TERMS#" => $arParams["TERMS_LINK"]))?> <a href="<?= $arParams["TERMS_LINK"]?>" target = "_blank"><?=GetMessage("R_TERMS")?></a></span> 
	<div class="exhibition-select-block">
		<div class="title"><?= GetMessage("R_E_EXTEND_TITLE")?></div>
		<div class="exhibition-block-validate"> 				
			<div class="choose"><?=GetMessage("R_E_EXHIBITION_TITLE")?>:</div>
			<?if(!empty($arResult["EXHIBITION"])):?>
				<table class="exh-select">
				<?foreach ($arResult["EXHIBITION"] as $exhibID => $arItem):?>
					<?if($arItem['PROPERTIES']['FOR_E']['VALUE'] != 'Y')//пропускаем, если выставка закрыта для Участников
					{
						continue;
					}
					?>
					<tr <?if($arItem["STATUS"]["PARTICIPANT"]["ALL"]=="NO"):?> class="not-available"<?endif;?>>
						<td>
							<label class="check-exh" for="check_<?=$arItem['ID']?>"></label>
							<input id="check_<?=$arItem['ID']?>" type="checkbox" name="EXHIBITION[]" value="<?=$arItem['ID']?>" class = "hide" />
						</td>
						<td>
							<span class = "name_exhibition <?/*=($arItem["STATUS"]["PARTICIPANT"]["ALL"]=="OK" || $arItem["STATUS"]["PARTICIPANT"]["ALL"]=="AN")?"dar_e":"lig_e"*/?>"><?=$arItem['PROPERTIES']['NAME_EN']['VALUE']?></span>
						</td>
						<td>
							<span class="stat_ex_<?=strtolower($arItem["STATUS"]["PARTICIPANT"]["ALL"])?>"><?=$arItem["STATUS"]["PARTICIPANT"]['TEXT']?></span>
						</td>
				  </tr>
		
				<?endforeach;?>
				</table>
				<span id="choice"><?= GetMessage("R_E_YOUR_CHOICE")?>: <span id="selected-exhibition"></span></span>
			<?else: ?>
			<div><?= GetMessage("R_EXHIBITIONS_NONE")?></div>
			<?endif; ?>
		</div>
	</div>
	<div class="line-sep-g"></div>
	
		<?/* БЛОК НЕМНОГО ИНФОРМАЦИИ О ВАС*/?>
	
	
	<div class="some-information">
		<div class="choose"><?=GetMessage("R_E_SOME_INFORMATION")?></div>
	
		<div class="block-company">
			<?/*Company or hotel name*/?>
			<?= ShowText("SIMPLE_QUESTION_988","COMPANY_NAME", $arResult["COMPANY_FORM"], "require en");?>
			<?/*Official name for invoice*/?>
			<? #<div class="dropdown-title">Official name for invoice</div>?>
			<?= ShowText("SIMPLE_QUESTION_106","COMPANY_NAME_FOR_INVOICE", $arResult["COMPANY_FORM"], "en");?>
			<?/*Your login*/?>
			<?= ShowText("SIMPLE_QUESTION_993","LOGIN", $arResult["COMPANY_FORM"], "login");?>
			<?/*Area of the business*/?>
			<?= ShowDropDown("SIMPLE_QUESTION_284","AREA_OF_BUSINESS", $arResult["COMPANY_FORM"]);?>
			<?/*Official adress*/?>
			<?= ShowText("SIMPLE_QUESTION_295","ADDRESS", $arResult["COMPANY_FORM"], "require en");?>
			<?/*City*/?>
			<?= ShowText("SIMPLE_QUESTION_320","CITY", $arResult["COMPANY_FORM"], "require en");?>
			<?/*Country*/?>
			<?= ShowText("SIMPLE_QUESTION_778","COUNTRY", $arResult["COMPANY_FORM"], "require en");?>
			<?/*http://*/?>
			<?= ShowText("SIMPLE_QUESTION_501","WEB_SITE", $arResult["COMPANY_FORM"], "web", GetMessage("R_E_WEB_SITE"));?>
			<?/*Company description (max.1200 symbols)*/?>
			<?= ShowTextArea("SIMPLE_QUESTION_163","COMPANY_DESCRIPTION", $arResult["COMPANY_FORM"], "require description");?>
			<div class="line-sep-small"></div>
			
			<?/*Приоритетные направления*/?>
			<div class="priority-areas">
				<div class="priority-title"><?=GetMessage("R_E_SELECT_PRIORITY_AREAS")?></div>
				<div class="priority-check-global">
					<label class="check-global" for="check_priority_global" type="checkbox"><?=GetMessage("R_E_CHECK_GLOBAL")?></label>
					<input id="check_priority_global" type="checkbox" name="PRIORITY_GLOBAL" value="" class = "none" />
				</div>
				<div class="priority-wrap">
					<?/*North America*/?>
					<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_876","NORTH_AMERICA", $arResult["COMPANY_FORM"], GetMessage("R_E_CHECK_ALL"));?>
					
					<?/*Europe*/?>
					<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_367","EUROPE", $arResult["COMPANY_FORM"], GetMessage("R_E_CHECK_ALL"));?>
					
					<?/*South America*/?>
					<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_328","SOUTH_AMERICA", $arResult["COMPANY_FORM"], GetMessage("R_E_CHECK_ALL"));?>
					
					<?/*Africa*/?>
					<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_459","AFRICA", $arResult["COMPANY_FORM"], GetMessage("R_E_CHECK_ALL"));?>
					
					<?/*Asia*/?>
					<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_931","ASIA", $arResult["COMPANY_FORM"], GetMessage("R_E_CHECK_ALL"));?>
					
					<?/*Oceania*/?>
					<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_445","OCEANIA", $arResult["COMPANY_FORM"], GetMessage("R_E_CHECK_ALL"));?>
				</div>
			</div>
			<div class="line-sep-small"></div>
			<div class="input-photo-block">
				<div class="block-upload-photo">
					<div class="upload-photos" id="company-photos"><?=GetMessage("R_E_UPLOAD_COMPANY_PHOTO")?></div>
					<span class="uploaded"></span>
					<span class="upload-status"></span>
					<ul class="files"></ul>
				</div>
			</div>
		</div>
	 	
		
		<div class="block-participant">
			<div class="input-photo-block">
				<div class="block-upload-photo">
					<div class="upload-photos" id="company-logo"><?=GetMessage("R_E_UPLOAD_COMPANY_LOGO")?></div>
					<span class="uploaded"></span>
					<span class="upload-status"></span>
					<ul class="files"></ul>
				</div>
			</div>
			<?/*Participant first name*/?>
			<?= ShowText("SIMPLE_QUESTION_446","NAME", $arResult["PARTICIPANT_FORM"], "require en");?>
			<?/*Participant last name*/?>
			<?= ShowText("SIMPLE_QUESTION_551","LAST_NAME", $arResult["PARTICIPANT_FORM"], "require en");?>
			<?/*Salutation*/?>
			<?= ShowDropDown("SIMPLE_QUESTION_889","SALUTATION", $arResult["PARTICIPANT_FORM"], GetMessage("R_E_SALUTATION"));?>
			<?/*Job title*/?>
			<?= ShowText("SIMPLE_QUESTION_729","JOB_POST", $arResult["PARTICIPANT_FORM"], "require en");?>
			<?/*Telephone*/?>
			<?= ShowText("SIMPLE_QUESTION_394","PHONE", $arResult["PARTICIPANT_FORM"], "require phone");?>
			<?/*Skype */?>
			<?= ShowText("SIMPLE_QUESTION_211","SKYPE", $arResult["PARTICIPANT_FORM"], "skype en");?>
			<?/*E-mail*/?>
			<?= ShowText("SIMPLE_QUESTION_859","EMAIL", $arResult["PARTICIPANT_FORM"], "require email");?>
			<?/*Please confirm your e-mail*/?>
			<?= ShowText("SIMPLE_QUESTION_585","CONF_EMAIL", $arResult["PARTICIPANT_FORM"], "require confemail");?>
			<?/*Alternative e-mail*/?>
			<?= ShowText("SIMPLE_QUESTION_749","ALT_EMAIL", $arResult["PARTICIPANT_FORM"], "email");?>
			<div class="input-photo-block">
				<div class="block-upload-photo">
					<div class="upload-photos" id="personal-photo"><?=GetMessage("R_E_UPLOAD_PERSONAL_PHOTO")?></div>
					<span class="uploaded"></span>
					<span class="upload-status"></span>
					<ul class="files"></ul>
				</div>
			</div>
		</div>
	</div>
	<div class="line-sep-g"></div>
	<?/* кнопки подтверждения регистрации*/?>
	<label class = "check-register"  for = "ckeck_register"><?=GetMessage("R_E_CONF_TERMS", array("#TERMS#" => $arParams["TERMS_LINK"]))?></label>
	<input id="ckeck_register" type="checkbox" name="CONFIRM_TERMS" class = "hide" />
	
	<input type="button" class="register-button" value="<?=GetMessage("R_E_SEND")?>" name="register_button">
	
</div>

