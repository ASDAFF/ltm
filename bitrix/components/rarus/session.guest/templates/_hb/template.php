<script type="text/javascript">
	function newWind(reciver){
		var recHref = reciver;
		window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
		return false;
	}
</script>

<div id="session-tab-2">
	<div class="pull-overflow sorting-company">
		<div class="pull-left">
		<?
		$by = htmlspecialcharsEx(trim($_REQUEST["by"]));
		$type = htmlspecialcharsEx(trim($_REQUEST["type"]));
		?>
			<ul>
				<li><a href="<?= $APPLICATION->GetCurPageParam("by=alphabet", array("by", "EXHIBIT_CODE", "type"));?>" title="">By alphabet</a></li>
				<li><a href="<?= $APPLICATION->GetCurPageParam("by=priority_areas", array("by", "EXHIBIT_CODE", "type"));?>" title="">By country of interest</a></li>
				<li><a href="<?= $APPLICATION->GetCurPageParam("by=city", array("by", "EXHIBIT_CODE", "type"));?>" title="">By city of origin</a></li>
				<li><a href="<?= $APPLICATION->GetCurPageParam("by=slots", array("by", "EXHIBIT_CODE", "type"));?>" title="">By available slots</a></li>
				<li><a href="<?= $APPLICATION->GetCurPageParam("by=all", array("by", "EXHIBIT_CODE", "type"));?>" title="">All</a></li>
			</ul>
		</div>
		<?if($by == "alphabet"):?>
		<div class="pull-right">
			<div class="alphabet">
				<a href="<?= $APPLICATION->GetCurPageParam("type=NUM", array("type", "EXHIBIT_CODE"));?>" title="">#</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=A", array("type", "EXHIBIT_CODE"));?>" title="">A</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=B", array("type", "EXHIBIT_CODE"));?>" title="">B</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=C", array("type", "EXHIBIT_CODE"));?>" title="">C</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=D", array("type", "EXHIBIT_CODE"));?>" title="">D</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=E", array("type", "EXHIBIT_CODE"));?>" title="">E</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=F", array("type", "EXHIBIT_CODE"));?>" title="">F</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=G", array("type", "EXHIBIT_CODE"));?>" title="">G</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=H", array("type", "EXHIBIT_CODE"));?>" title="">H</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=I", array("type", "EXHIBIT_CODE"));?>" title="">I</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=J", array("type", "EXHIBIT_CODE"));?>" title="">J</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=K", array("type", "EXHIBIT_CODE"));?>" title="">K</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=L", array("type", "EXHIBIT_CODE"));?>" title="">L</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=M", array("type", "EXHIBIT_CODE"));?>" title="">M</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=N", array("type", "EXHIBIT_CODE"));?>" title="">N</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=O", array("type", "EXHIBIT_CODE"));?>" title="">O</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=P", array("type", "EXHIBIT_CODE"));?>" title="">P</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=Q", array("type", "EXHIBIT_CODE"));?>" title="">Q</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=R", array("type", "EXHIBIT_CODE"));?>" title="">R</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=S", array("type", "EXHIBIT_CODE"));?>" title="">S</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=T", array("type", "EXHIBIT_CODE"));?>" title="">T</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=U", array("type", "EXHIBIT_CODE"));?>" title="">U</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=V", array("type", "EXHIBIT_CODE"));?>" title="">V</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=W", array("type", "EXHIBIT_CODE"));?>" title="">W</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=X", array("type", "EXHIBIT_CODE"));?>" title="">X</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=Y", array("type", "EXHIBIT_CODE"));?>" title="">Y</a>
				<a href="<?= $APPLICATION->GetCurPageParam("type=Z", array("type", "EXHIBIT_CODE"));?>" title="">Z</a>
			</div>
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


		if($sort != "BY_ALL")
		{
			foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $data)
			{
				if($type)
				{
					if($type != $data["NAME"])
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
							<a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>"><?= $userFormData["COMPANY"]["NAME"]?></a>
							<? else:?>
							<div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
							<? endif;?>
							<div><?= $userFormData["CITY"]?>, <?= $userFormData["COUNTRY"]?></div>
						</td>
						<td class="representative"><?= $userFormData["FIO"]?></td>
						<td class="contact"><a href="/cabinet/service/write.php?id=<?= $arUser["ID"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $arUser["ID"]?>'); return false;">Send<br> a message</a></td>
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
						?><td colspan="2"><?=GetMessage("SESSION_BLOCK");?></td><?
						}
                        else{
                            ?>
                            <td colspan="2">Schedule is full</td>
							<?
                        }
                        ?>
					</tr>
					<?
				}
				if(!$type && $by == "priority_areas")//если нет типа выводим первый и выходим, а то выведет всех по много раз
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
							<a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>"><?= $userFormData["COMPANY"]["NAME"]?></a>
							<? else:?>
							<div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
							<? endif;?>
						<div><?= implode(", ", $userFormData["BUSINESS_TYPE"])?></div>
					</td>
					<td class="representative"><?= $userFormData["FIO"]?></td>
					<td class="contact"><a href="/cabinet/service/write.php?id=<?= $arUser["ID"]?>" target="_blank" onclick="newWind('/cabinet/service/write.php?id=<?= $arUser["ID"]?>'); return false;">Send<br> a message</a></td>
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
						?><td colspan="2"><?=GetMessage("SESSION_BLOCK");?></td><?
						}
                        else{
                            ?>
                            <td colspan="2">Schedule is full</td>
							<?
                        }
                        ?>
				</tr>
			<? endforeach;
		}
		?>
	</table>
</div>