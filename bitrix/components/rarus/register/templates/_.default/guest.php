<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/header.php");
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/function.php");
?>
<script>
AjaxPatch = <?=CUtil::PhpToJSObject($arResult["AJAX_PATCH"])?>
</script>
<div class="registr_buy">
	<div class="exhibition-select-block">
		<div class="title"><?= GetMessage("R_B_EXTEND_TITLE")?></div> 				
		<div class="choose-b"><?=GetMessage("R_B_EXHIBITION_TITLE")?>:</div>
		<?if(!empty($arResult["EXHIBITION"])):?>
			<table class="exh-select ">
			<thead>
				<tr>
					<th style = "font-size: 12px;"><?=GetMessage("R_MORNING")?></th>
					<th style = "font-size: 12px;"><?=GetMessage("R_EVENING")?></th>
					<th style = "font-size: 12px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?foreach ($arResult["EXHIBITION"] as $exhibID => $arItem):?>
				<?if($arItem['PROPERTIES']['FOR_G']['VALUE'] != 'Y')//����������, ���� �������� ������� ��� ������
				{
					continue;
				}
				?>
				<tr>
					<td class = "morning_b">
						<? if($arItem["STATUS"]["GUEST"]["MORNING"]=="Y"){ ?>
						<label class = "check-exh" for = "ckeck_m_<?=$arItem['ID']?>"></label>
						<input id="ckeck_m_<?=$arItem['ID']?>" type="checkbox" name="EXHIBITION[<?=$arItem['ID']?>][MORNING]" value="843" class = "hide" />
						<? } ?>
					</td>
					<td class = "evening_b">
						<? if($arItem["STATUS"]["GUEST"]["EVENING"]=="Y"){ ?>
						<label class = "check-exh" for = "ckeck_e_<?=$arItem['ID']?>"></label>
						<input id="ckeck_e_<?=$arItem['ID']?>" type="checkbox" name="EXHIBITION[<?=$arItem['ID']?>][EVENING]" value="844" class = "hide" />
						<? } ?>
					</td>
					<td class = "name_b">
						<span class = "name_exhibition"><?=$arItem['NAME']?></span>
					</td>
			   </tr>
	
			<?endforeach;?>
			</tbody>
			</table>
			<span id="choice"><?= GetMessage("R_B_YOUR_CHOICE")?>: <span id="selected-exhibition"></span></span>
		<?else: ?>
		<div><?= GetMessage("R_EXHIBITIONS_NONE")?></div>
		<?endif; ?>
	</div>
	<div class="line-sep-g"></div>
	
	
	
	<?/* ���� ������� ���������� � ���*/?>
	
	
	<div class="some-information">
		<div class="choose"><?=GetMessage("R_B_SOME_INFORMATION")?></div>
		<?/*����� ����������*/?>
		<div class="block-common">
		<? $gQuestions = &$arResult["GUEST_FORM"]["QUESTIONS"];
		   $gAnswers = &$arResult["GUEST_FORM"]["ANSWERS"];
		?>
			<?/*�������� ��������*/?>
			<?= ShowText("SIMPLE_QUESTION_115","COMPANY_NAME", $arResult["GUEST_FORM"], "require en");?>
			<?/*��� ������������*/ ?>
			<?= ShowGroupCheckBox("SIMPLE_QUESTION_677","BUSINESS_TYPE", $arResult["GUEST_FORM"]);?>
			<?/*����������� ����� ��������*/?>
			<?= ShowText("SIMPLE_QUESTION_773","COMPANY_ADDRESS", $arResult["GUEST_FORM"], "require en");?>
			<?/*������*/?>
			<?= ShowText("SIMPLE_QUESTION_756","INDEX", $arResult["GUEST_FORM"], "require index");?>
			<?/*�����*/?>
			<?= ShowText("SIMPLE_QUESTION_672","CITY", $arResult["GUEST_FORM"], "require en");?>
			<?/*������*/?>
			<?= ShowDropDown("SIMPLE_QUESTION_678","COUNTRY", $arResult["GUEST_FORM"]);?>
			<?/*������ ������*/?>
			<?= ShowText("SIMPLE_QUESTION_243","COUNTRY_OTHER", $arResult["GUEST_FORM"], "hide country_other en");?>
			<div class="line-sep-small"></div>
			<?/*���*/?>
			<?= ShowText("SIMPLE_QUESTION_750","NAME", $arResult["GUEST_FORM"], "require en");?>
			<?/*�������*/?>
			<?= ShowText("SIMPLE_QUESTION_823","LAST_NAME", $arResult["GUEST_FORM"], "require en");?>
			<?/*���������*/?>
			<?= ShowText("SIMPLE_QUESTION_391","JOB_POST", $arResult["GUEST_FORM"], "require en");?>
			<?/*�������*/?>
			<?= ShowText("SIMPLE_QUESTION_636","PHONE", $arResult["GUEST_FORM"], "require phone");?>
			<?/*email*/?>
			<?= ShowText("SIMPLE_QUESTION_373","EMAIL", $arResult["GUEST_FORM"], "require email");?>
			<?/*confemail*/?>
			<?= ShowText("SIMPLE_QUESTION_279","CONF_EMAIL", $arResult["GUEST_FORM"], "require confemail");?>
			<?/*��������� �������*/?>
			<?= ShowText("SIMPLE_QUESTION_844","MOBILE_PHONE", $arResult["GUEST_FORM"], "phone");?>
			<?/*����*/?>
			<?= ShowText("SIMPLE_QUESTION_552","WEB_SITE", $arResult["GUEST_FORM"], "web");?>
			<div class="line-sep-small"></div>
		</div>
		
		<?/*���� */?>
		<div class="block-morning hide">
			<?/*�������� ��������*/?>
			<?= ShowTextArea("SIMPLE_QUESTION_166","COMPANY_DESCRIPTION", $arResult["GUEST_FORM"], "description en");?>
			<div class="priority-areas">
				<div class="priority-title"><?=GetMessage("R_B_SELECT_PRIORITY_AREAS")?></div>
				<?/*North America*/?>
				<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_383","NORTH_AMERICA", $arResult["GUEST_FORM"], GetMessage("R_B_CHECK_ALL"));?>
				
				<?/*Europe*/?>
				<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_244","EUROPE", $arResult["GUEST_FORM"], GetMessage("R_B_CHECK_ALL"));?>
				
				<?/*South America*/?>
				<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_212","SOUTH_AMERICA", $arResult["GUEST_FORM"], GetMessage("R_B_CHECK_ALL"));?>
				
				<?/*Africa*/?>
				<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_497","AFRICA", $arResult["GUEST_FORM"], GetMessage("R_B_CHECK_ALL"));?>
				
				<?/*Asia*/?>
				<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_526","ASIA", $arResult["GUEST_FORM"], GetMessage("R_B_CHECK_ALL"));?>
				
				<?/*Oceania*/?>
				<?= ShowPriorityAreasCheckBox("SIMPLE_QUESTION_878","OCEANIA", $arResult["GUEST_FORM"], GetMessage("R_B_CHECK_ALL"));?>
			</div>
			<div class="line-sep-small"></div>
			<div class="authorize-title"><?=GetMessage("R_B_AUTHORIZE_TITLE")?></div>
			<?/*������� �����/�������� ���*/?>
			<?= ShowText("SIMPLE_QUESTION_474","LOGIN", $arResult["GUEST_FORM"], "login en");?>
			<?/*������� ������*/?>
			<?= ShowPassword("SIMPLE_QUESTION_435","PASSWORD", $arResult["GUEST_FORM"], "pass en");?>
			<?/*��������� ������*/?>
			<?= ShowPassword("SIMPLE_QUESTION_300","CONF_PASSWORD", $arResult["GUEST_FORM"], "confpass");?>
			<div class="authorize-epilogue"><?=GetMessage("R_B_AUTHORIZE_EPILOGUE")?></div>
			<div class="line-sep-small"></div>
			
			<div class="authorize-title"><?=GetMessage("R_B_COLLEAGUE_MORNING_TITLE")?></div>
			<?/*��� ������� �� ����*/?>
			<?= ShowText("SIMPLE_QUESTION_816","COLLEAGUE[MORNING][NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_NAME"));?>
			<?/*������� ������� �� ����*/?>
			<?= ShowText("SIMPLE_QUESTION_596","COLLEAGUE[MORNING][LAST_NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_LAST_NAME"));?>
			<?/*��������� ������� �� ����*/?>
			<?= ShowText("SIMPLE_QUESTION_304","COLLEAGUE[MORNING][JOB_POST]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_JOB_POST"));?>
			<?/*EAMIL ������� �� ����*/?>
			<?= ShowText("SIMPLE_QUESTION_278","COLLEAGUE[MORNING][EMAIL]", $arResult["GUEST_FORM"], "email", GetMessage("R_B_EMAIL"));?>
			<div class="line-sep-small"></div>
		</div>
		
		<? /*
		<?// ���� ����� ?>
		<div class="block-morning-evening hide">
			<div class="authorize-title"><?= GetMessage("R_B_COLLEAGUE_EVENING_FOR_ME_1")?></div>
			<div class="authorize-epilogue"><?= GetMessage("R_B_COLLEAGUE_EVENING_FOR_ME_2")?></div>
			<div class="line-sep-small"></div>
		</div>
		*/?>
		
		<?//�����?>
		<div class="block-evening hide">
			<div class="priority-title"><?= GetMessage("R_B_COLLEAGUE_EVENING_TITLE")?></div>
			<div class="line-sep-small"></div>
			
			<div class="collegue-title"><?= GetMessage("R_B_EACH_COLLEAGUE_EVENING_TITLE", array("#NUM#" => "1"))?></div>
			<?/*��� ������� 1*/?>
			<?= ShowText("SIMPLE_QUESTION_367","COLLEAGUE[0][NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_NAME"));?>
			<?/*������� ������� 1*/?>
			<?= ShowText("SIMPLE_QUESTION_482","COLLEAGUE[0][LAST_NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_LAST_NAME"));?>
			<?/*��������� ������� 1*/?>
			<?= ShowText("SIMPLE_QUESTION_187","COLLEAGUE[0][JOB_POST]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_JOB_POST"));?>
			<?/*E-mail ������� 1*/?>
			<?= ShowText("SIMPLE_QUESTION_421","COLLEAGUE[0][EMAIL]", $arResult["GUEST_FORM"], "email", GetMessage("R_B_EMAIL"));?>
			<div class="line-sep-small"></div>
			
			<div class="collegue-title"><?= GetMessage("R_B_EACH_COLLEAGUE_EVENING_TITLE", array("#NUM#" => "2"))?></div>
			<?/*��� ������� 2*/?>
			<?= ShowText("SIMPLE_QUESTION_225","COLLEAGUE[1][NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_NAME"));?>
			<?/*������� ������� 2*/?>
			<?= ShowText("SIMPLE_QUESTION_770","COLLEAGUE[1][LAST_NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_LAST_NAME"));?>
			<?/*��������� ������� 2*/?>
			<?= ShowText("SIMPLE_QUESTION_280","COLLEAGUE[1][JOB_POST]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_JOB_POST"));?>
			<?/*E-mail ������� 2*/?>
			<?= ShowText("SIMPLE_QUESTION_384","COLLEAGUE[1][EMAIL]", $arResult["GUEST_FORM"], "email", GetMessage("R_B_EMAIL"));?>
			<div class="line-sep-small"></div>
			
			<div class="collegue-title"><?= GetMessage("R_B_EACH_COLLEAGUE_EVENING_TITLE", array("#NUM#" => "3"))?></div>
			<?/*��� ������� 3*/?>
			<?= ShowText("SIMPLE_QUESTION_765","COLLEAGUE[2][NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_NAME"));?>
			<?/*������� ������� 3*/?>
			<?= ShowText("SIMPLE_QUESTION_627","COLLEAGUE[2][LAST_NAME]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_LAST_NAME"));?>
			<?/*��������� ������� 3*/?>
			<?= ShowText("SIMPLE_QUESTION_788","COLLEAGUE[2][JOB_POST]", $arResult["GUEST_FORM"], "en", GetMessage("R_B_JOB_POST"));?>
			<?/*E-mail ������� 3*/?>
			<?= ShowText("SIMPLE_QUESTION_230","COLLEAGUE[2][EMAIL]", $arResult["GUEST_FORM"], "email", GetMessage("R_B_EMAIL"));?>
			<div class="line-sep-small"></div>
		</div>
	</div>
	<div class="line-sep-g"></div>
	<?/* ������ ������������� �����������*/?>
	<label class = "check-register"  for = "ckeck_register"><?=GetMessage("R_B_CONF_TERMS")?></label>
	<input id="ckeck_register" type="checkbox" name="CONFIRM_TERMS" class = "hide" />
	
	<input type="button" class="register-button" value="<?=GetMessage("R_B_SEND")?>" name="register_button">
</div>

