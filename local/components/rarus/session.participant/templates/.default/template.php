<script type="text/javascript">
	function newWind(reciver){
		var recHref = reciver;
		window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=700, height=500, left='+(screen.availWidth/2-350)+', top='+(screen.availHeight/2-250)+'');
		return false;
	}
</script>
<div id="session-tab-2">
	<div class="pull-overflow sorting-company">
		<div class="list">
			<a href="<?= $APPLICATION->GetCurPageParam("by=alphabet", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_ALPHABET")?"class='selected'":"";?>>По алфавиту</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=priority_areas", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_PRIORITY_AREAS")?"class='selected'":"";?>>По странам</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=business", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_BUSINESS")?"class='selected'":"";?>>По виду деятельности</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=slots", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_SLOTS")?"class='selected'":"";?>>По свободному времени</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=all", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($arResult["SORT"]=="BY_ALL" || !$by)?"class='selected'":"";?>>Все</a>
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
			<th>Компания</th>
			<th>Представитель</th>
			<th>Написать</th>
			<th class="free-slots">Таймслоты</th>
			<th>Запрос</th>
		</tr>
		<?foreach ($arResult["RESULTS"] as $code => $data):?>
			<tr>
				<td class="company">
					<? if($data["FORM_DATA"]["COMPANY"]["LINK"]):?>
						<a class="company-name" href="<?= $data["FORM_DATA"]["COMPANY"]["LINK"]?>" title="<?= $data["FORM_DATA"]["COMPANY"]["NAME"]?>" target="_blank"><?= $data["FORM_DATA"]["COMPANY"]["NAME"]?></a>
					<? else:?>
						<div class="company-name"><?= $data["FORM_DATA"]["COMPANY"]["NAME"]?></div>
					<? endif;?>
					<div><?= implode(", ", $data["FORM_DATA"]["BUSINESS_TYPE"])?></div>
				</td>
				<td class="representative"><?= $data["FORM_DATA"]["PARTICIPANT"]["FIO"]?></td>
				<td class="contact"><a href="/cabinet/service/write.php?id=<?= $data["ID"]?>&exhib_code=<?=$arResult["APP_CODE"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $data["ID"]?>&exhib_code=<?=$arResult["APP_CODE"]?>'); return false;">Написать<br>сообщение</a></td>
				<?
				if (count ($data["FORM_DATA"]["TIME_SLOTS"]) && $arResult['IS_ACTIVE']){
					?>
					<td class="free-slots">
						<select name="times">
							<? foreach ($data["FORM_DATA"]["TIME_SLOTS"] as $idSlot => $slotName):?>
								<option value="<?= $idSlot?>"><?= $slotName?></option>
							<? endforeach;?>
						</select>
					</td>
					<td class="request">
						<a href="javascript:void(0)" title="" onclick="sendRequest<?=$arResult["USER_TYPE"]?>(this,<?= $arResult["APP_ID"]?>,<?= $arResult["USER_ID"]?>,<?= $data["ID"]?>,'g')">Отправить<br>запрос</a>
					</td>
				<?
				}
				elseif(!$arResult['IS_ACTIVE']){
					?><td colspan="2"><?=GetMessage("SESSION_BLOCK");?></td><?
				}
				else{
					?><td colspan="2">Расписание полное</td><?
				}
				?>
			</tr>
		<?endforeach;?>
	</table>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
</div>