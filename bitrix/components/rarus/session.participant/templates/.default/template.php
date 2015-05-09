<script type="text/javascript">
	function newWind(reciver){
		var recHref = reciver;
		window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=700, height=500, left='+(screen.availWidth/2-350)+', top='+(screen.availHeight/2-250)+'');
		return false;
	}
</script>
		<?
		$arAlphabet = array("NUM" =>"#", "A" =>"A", "B" =>"B", "C" =>"C", "D" =>"D", "E" =>"E", "F" =>"F", "G" =>"G", "H" =>"H", "I" =>"I", "J" =>"J", "K" =>"K", "L" =>"L", "M" =>"M", "N" =>"N", "O" =>"O", "P" =>"P", "Q" =>"Q", "R" =>"R", "S" =>"S", "T" =>"T", "U" =>"U", "V" =>"V", "W" =>"W", "X" =>"X", "Y" =>"Y", "Z" =>"Z");
		$by = htmlspecialcharsEx(trim($_REQUEST["by"]));
		$type = htmlspecialcharsEx(trim($_REQUEST["type"]));

		//определение из какой группы брать данные
		switch ($by)
		{
			case "alphabet" : $sort = "BY_ALPHABET"; break;
			case "priority_areas" : $sort = "BY_PRIORITY_AREAS"; break;
			case "business" : $sort = "BY_BUSINESS"; break;
			case "city" : $sort = "BY_CITY"; break;
			case "slots" : $sort = "BY_SLOTS"; break;
			case "all" : $sort = "BY_ALL"; break;
			default:  $sort = "BY_ALL";
		}
		?>
<div id="session-tab-2">
	<div class="pull-overflow sorting-company">
		<div class="list">
			<a href="<?= $APPLICATION->GetCurPageParam("by=alphabet", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="alphabet")?"class='selected'":"";?>>По алфавиту</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=priority_areas", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="priority_areas")?"class='selected'":"";?>>По странам</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=business", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="business")?"class='selected'":"";?>>По виду деятельности</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=slots", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="slots")?"class='selected'":"";?>>По свободному времени</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=all", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="all" || !$by)?"class='selected'":"";?>>Все</a>
		</div>
		<?if($by == "alphabet"):?>

		<div class="alphabet">
    		<? foreach ($arAlphabet as $code => $letter):?>
    		    <a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("type", "EXHIBIT_CODE"));?>" title="" <?= ($code == $type)?"class='selected'":"";?>><?= $letter?></a>
    		<? endforeach;?>
		</div>
		<? elseif($by == "slots"):?>
		    <? $first = true;?>
		    <div class="list">
		        <? foreach ($arResult["SESSION"][$sort] as $code => $data):?>
		            <a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("EXHIBIT_CODE", "type"));?>" title="" <?= (($code == $type)  || (!$type && $first))?"class='selected'":"";?>><?= $data["name"]?></a>
		            <? $first = false;?>
		        <? endforeach;?>
			</div>

		<? elseif($sort != "BY_ALL"):?>

		    <div class="list">
		        <? $first = true;?>
		        <? foreach ($arResult["SESSION"][$sort] as $data):?>
		            <a href="<?= $APPLICATION->GetCurPageParam("type=". $data["NAME"], array("EXHIBIT_CODE", "type"));?>" title="" <?= (($data["NAME"] == $type) || (!$type && $first))?"class='selected'":"";?>><?= $data["NAME"]?></a>
		            <? $first = false;?>
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

		<?
		//определение из какой группы брать данные

		if($sort != "BY_ALL")
		{
			foreach ($arResult["SESSION"][$sort] as $code=>$data)
			{
				if($type)
				{
        			if(($sort == "BY_SLOTS" && $type != $code) || ($sort != "BY_SLOTS" && $type != $data["NAME"]))
        				{
        				    continue;
        				}
        		}
				foreach ($data["ID"] as $id)
				{
					$arUser = $arResult["SESSION"]["USERS"][$id];
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
						<td class="representative"><?= $userFormData["PARTICIPANT"]["FIO"]?></td>
						<td class="contact"><a href="/cabinet/service/write.php?id=<?= $arUser["ID"]?>&exhib_code=<?=$_REQUEST["EXHIBIT_CODE"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $arUser["ID"]?>&exhib_code=<?=$_REQUEST["EXHIBIT_CODE"]?>'); return false;">Написать<br>сообщение</a></td>
                    <?
                    if (count ($userFormData["TIME_SLOTS"]) && $arResult['IS_ACTIVE']){
						?>
					<td class="free-slots">
					    <select name="times">
    						<? foreach ($userFormData["TIME_SLOTS"] as $idSlot => $slotName):?>
    							<option value="<?= $idSlot?>"><?= $slotName?></option>
    						<? endforeach;?>
						</select>
					</td>
					<td class="request">
						<a href="javascript:void(0)" title="" onclick="sendRequest<?=$arResult["USER_TYPE"]?>(this,<?= $arResult["APP_ID"]?>,<?= $arResult["USER_ID"]?>,<?= $arUser["ID"]?>,'g')">Отправить<br>запрос</a>
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
					<?
				}
				if(!$type && $by == "priority_areas"  || $by == "slots" || $by == "business")//если нет типа выводим первый и выходим, а то выведет всех по много раз
				{
					break;
				}
			}
		}
		else
		{
			foreach ($arResult["SESSION"][$sort] as $data):
			//Для всех пользователей
			$arUser = $arResult["SESSION"]["USERS"][$data];
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
					<td class="representative"><?= $userFormData["PARTICIPANT"]["FIO"]?></td>
					<td class="contact"><a href="/cabinet/service/write.php?id=<?= $arUser["ID"]?>&EXHIBIT_CODE=<?=$_REQUEST["EXHIBIT_CODE"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $arUser["ID"]?>&EXHIBIT_CODE=<?=$_REQUEST["EXHIBIT_CODE"]?>'); return false;">Написать<br>сообщение</a></td>
                    <?
                    if (count ($userFormData["TIME_SLOTS"]) && $arResult['IS_ACTIVE']){
						?>
					<td class="free-slots">
					    <select name="times">
    						<? foreach ($userFormData["TIME_SLOTS"] as $idSlot => $slotName):?>
    							<option value="<?= $idSlot?>"><?= $slotName?></option>
    						<? endforeach;?>
						</select>
					</td>
					<td class="request">
						<a href="javascript:void(0)" title="" onclick="sendRequest<?=$arResult["USER_TYPE"]?>(this,<?= $arResult["APP_ID"]?>,<?= $arResult["USER_ID"]?>,<?= $arUser["ID"]?>,'g')">Отправить<br>запрос</a>
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
			<? endforeach;
		}
		?>
	</table>
</div>