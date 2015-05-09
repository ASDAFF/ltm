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
			case "city" : $sort = "BY_CITY"; break;
			case "slots" : $sort = "BY_SLOTS"; break;
			case "all" : $sort = "BY_ALL"; break;
			default:  $sort = "BY_ALL";
		}
		?>
<div id="session-tab-2">
	<div class="pull-overflow sorting-company">
		<div class="list">
	        <a href="<?= $APPLICATION->GetCurPageParam("by=alphabet", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="alphabet")?"class='selected'":"";?>>By alphabet</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=priority_areas", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="priority_areas")?"class='selected'":"";?>>By country of interest</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=city", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="city")?"class='selected'":"";?>>By city of origin</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=slots", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="slots")?"class='selected'":"";?>>By available slots</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=all", array("by", "EXHIBIT_CODE", "type"));?>" title="" <?= ($by=="all" || !$by)?"class='selected'":"";?>>All</a>
		</div>
		<?if($by == "alphabet"):?>
			<div class="alphabet">
			<? foreach ($arAlphabet as $code => $letter):?>
			    <a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("type", "EXHIBIT_CODE"));?>" title="" <?= ($code == $type)?"class='selected'":"";?>><?= $letter?></a>
			<? endforeach;?>
			</div>
		<? elseif($by == "slots"):?>
		    <div class="list">
		        <? $first = true;?>
		        <? foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $code => $data):?>
		            <a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("EXHIBIT_CODE", "type"));?>" title="" <?= (($code == $type)  || (!$type && $first))?"class='selected'":"";?>><?= $data["name"]?></a>
		            <? $first = false;?>
		        <? endforeach;?>
		    </div>
		<? elseif($sort != "BY_ALL"):?>
		    <div class="list">
		        <? $first = true;?>
		        <? foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $data):?>
		            <a href="<?= $APPLICATION->GetCurPageParam("type=". $data["NAME"], array("EXHIBIT_CODE", "type"));?>" title="" <?= (($data["NAME"] == $type) || (!$type && $first))?"class='selected'":"";?>><?= $data["NAME"]?></a>
		            <? $first = false;?>
		        <? endforeach;?>
			</div>
		<?endif;?>
	</div>

	<table class="sorting-company">
		<tr>
			<th>Company</th>
			<th>Representative</th>
			<th>Contact</th>
			<th class="free-slots">Free slots</th>
			<th>Request</th>
		</tr>

		<?

		if($sort != "BY_ALL")
		{
			foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $code => $data)
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
					$arUser = $arResult["SESSION"][$arParams["TYPE"]]["USERS"][$id];
					$userFormData = $arUser["FORM_DATA"];
					?>
					<tr>
						<td class="company">
							<? if($userFormData["COMPANY"]["LINK"]):?>
							    <a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>" target="_blank"><?= $userFormData["COMPANY"]["NAME"]?></a>
							<? else:?>
							    <div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
							<? endif;?>
							    <?/*<div><?= implode(", ", $userFormData["BUSINESS_TYPE"])?></div>*/?>
							    <div class = "rarus_d"><?= implode("", $userFormData["DESCRIPTION"])?></div>
								<div class = "rarus_geo"><?= implode("", $userFormData["TOWN"])?>, <?=$userFormData["COUNTRY"]?></div>							    
						</td>
						<td class="representative"><?= $userFormData["FIO"]?></td>
						<td class="contact"><a href="/cabinet/service/write.php?id=<?= $arUser["ID"]?>&EXHIBIT_CODE=<?=$_REQUEST["EXHIBIT_CODE"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $arUser["ID"]?>&EXHIBIT_CODE=<?=$_REQUEST["EXHIBIT_CODE"]?>'); return false;">Send<br> a message</a></td>
						<?
                        if (count($userFormData["TIME_SLOTS"]) && $arResult['IS_ACTIVE']){
                            ?>
						<td class="free-slots">
                            <select name="times">
                                <? foreach ($userFormData["TIME_SLOTS"] as $idSlot => $slotName):?>
                                    <option value="<?= $idSlot?>"><?= $slotName?></option>
                                <? endforeach;?>
                            </select>
						</td>
						<td class="request"><a href="javascript:void(0)" title="" onclick="sendRequest(this,<?= $arResult["APP_ID"]?>,<?= $arResult["USER_ID"]?>,<?= $arUser["ID"]?>,'p')">Send <br>a request</a></td>
                            <?
                        }
                        elseif(!$arResult['IS_ACTIVE']){
						?><td colspan="2" class="collapsed"><?=GetMessage("SESSION_BLOCK");?></td><?
						}
                        else{
                            ?>
                            <td colspan="2" class="collapsed">Schedule is full</td>
							<?
                        }
                        ?>
					</tr>
					<?
				}
				if(!$type && ($by == "priority_areas" || $by == "slots" || $by == "city"))//если нет типа выводим первый и выходим, а то выведет всех по много раз
				{
					break;
				}
			}
		}
		else
		{
			foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $data):
			//Для всех пользователей
			$arUser = $arResult["SESSION"][$arParams["TYPE"]]["USERS"][$data];
			$userFormData = $arUser["FORM_DATA"];
			?>
				<tr>
					<td class="company">
						<? if($userFormData["COMPANY"]["LINK"]):?>
							    <a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>" target="_blank"><?= $userFormData["COMPANY"]["NAME"]?></a>
							<? else:?>
							    <div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
							<? endif;?>
						    <?/*<div><?= implode(", ", $userFormData["BUSINESS_TYPE"])?></div>*/?>
							<div class = "rarus_d"><?= implode("", $userFormData["DESCRIPTION"])?></div>
							<div class = "rarus_geo"><?= implode("", $userFormData["TOWN"])?>, <?=$userFormData["COUNTRY"]?></div>
					</td>
					<td class="representative"><?= $userFormData["FIO"]?></td>
					<td class="contact"><a href="/cabinet/service/write.php?id=<?= $arUser["ID"]?>&EXHIBIT_CODE=<?=$_REQUEST["EXHIBIT_CODE"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $arUser["ID"]?>&EXHIBIT_CODE=<?=$_REQUEST["EXHIBIT_CODE"]?>'); return false;">Send<br> a message</a></td>
						<?
                        if (count($userFormData["TIME_SLOTS"]) && $arResult['IS_ACTIVE']){
                            ?>
						<td class="free-slots">
                            <select name="times">
                                <? foreach ($userFormData["TIME_SLOTS"] as $idSlot => $slotName):?>
                                    <option value="<?= $idSlot?>"><?= $slotName?></option>
                                <? endforeach;?>
                            </select>
						</td>
						<td class="request"><a href="javascript:void(0)" title="" onclick="sendRequest(this,<?= $arResult["APP_ID"]?>,<?= $arResult["USER_ID"]?>,<?= $arUser["ID"]?>,'p')">Send <br>a request</a></td>
                            <?
                        }
                        elseif(!$arResult['IS_ACTIVE']){
						?><td colspan="2" class="collapsed"><?=GetMessage("SESSION_BLOCK");?></td><?
						}
                        else{
                            ?>
                            <td colspan="2" class="collapsed">Schedule is full</td>
							<?
                        }
                        ?>
				</tr>
			<? endforeach;
		}
		?>
	</table>
</div>