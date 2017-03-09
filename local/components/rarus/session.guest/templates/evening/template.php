<div id="session-tab-2">
	<div class="pull-overflow sorting-company">
		<div class="list">
			<a href="<?= $APPLICATION->GetCurPageParam("by=alphabet", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_ALPHABET")?"class='selected'":"";?>>By alphabet</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=city", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_CITY")?"class='selected'":"";?>>By city of origin</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=all", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_ALL" || !$arResult["SORT"])?"class='selected'":"";?>>All</a>
		</div>
		<?if(!empty($arResult["FILTER"]["CHILD"])): //alphabet?>
			<div class="list">
				<? foreach ($arResult["FILTER"]["CHILD"] as $code => $data):?>
					<a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("EXHIBIT_CODE", "type"));?>" title="" <?=($code == $arResult["SORT_TYPE"])?"class='selected'":"";?>><?= $data?></a>
				<? endforeach;?>
			</div>
		<?endif;?>
	</div>
	<table class="sorting-company">
		<tr>
			<th>Company</th>
			<th>Representative</th>
			<th>Collegues</th>
		</tr>
		<?
			foreach ($arResult["RESULTS"] as $code => $arUser):
				$userFormData = $arUser["FORM_DATA"];
					?>
					<tr>
						<td class="company">
							<? if($userFormData["COMPANY"]["LINK"]):?>
							<a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>" target="_blank"><?= $userFormData["COMPANY"]["NAME"]?></a>
							<? else:?>
							<div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
							<? endif;?>
							<div><?= implode(", ", $userFormData["BUSINESS_TYPE"])?></div>
						</td>
						<td class="representative"><?= $userFormData["FIO"]?></td>
						<td class="collegues"><?
						foreach($userFormData["COLLEAGUE_E"] as $colleague)
						{
							if($fio = trim($colleague["FIO"]))
							{
								echo $fio . "<br/>";
							}
						}
						?></td>
					</tr>
			<? endforeach;?>
	</table>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
</div>